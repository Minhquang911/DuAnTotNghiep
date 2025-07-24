<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class ZaloPayService
{
    private $config;

    public function __construct()
    {
        $this->config = config('services.zalopay');
    }

    /**
     * Tạo URL thanh toán ZaloPay
     */
    public function createPaymentUrl($orderData)
    {
        try {
            // Validation
            if (!isset($orderData['amount']) || $orderData['amount'] <= 0) {
                throw new \Exception('Amount phải lớn hơn 0');
            }
            
            if (!isset($orderData['items']) || empty($orderData['items'])) {
                throw new \Exception('Items không được trống');
            }

            // Format app_trans_id theo đúng ZaloPay spec: yymmdd_appid_timestamp
            $app_trans_id = date('ymd') . '_' . $this->config['app_id'] . '_' . time();
            
            $order = [
                'app_id' => (int)$this->config['app_id'],
                'app_user' => $this->config['app_user'],
                'app_time' => round(microtime(true) * 1000), // milliseconds
                'amount' => (int)round($orderData['amount']), // Đảm bảo là integer
                'app_trans_id' => $app_trans_id,
                'embed_data' => json_encode([
                    'redirecturl' => $this->config['return_url']
                ], JSON_UNESCAPED_SLASHES),
                'item' => json_encode($orderData['items'], JSON_UNESCAPED_UNICODE),
                'description' => $orderData['description'],
                'bank_code' => 'CC',
                'callback_url' => $this->config['callback_url'],
            ];

            // Tạo MAC theo đúng spec ZaloPay
            $order['mac'] = $this->generateCreateOrderMac($order, $this->config['key1']);

            Log::info('ZaloPay Create Order Request:', $order);

            // Gọi API tạo order
            $response = Http::post($this->config['create_order_url'], $order);
            
            Log::info('ZaloPay API Response Status:', ['status' => $response->status()]);
            Log::info('ZaloPay API Response Body:', ['body' => $response->body()]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('ZaloPay Create Order Response:', $result);
                
                if (isset($result['return_code']) && $result['return_code'] == 1) {
                    return [
                        'success' => true,
                        'payment_url' => $result['order_url'],
                        'app_trans_id' => $app_trans_id,
                        'zp_trans_token' => $result['zp_trans_token'] ?? null,
                        'qr_code' => $result['qr_code'] ?? null
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => ($result['return_message'] ?? 'Lỗi tạo đơn hàng ZaloPay') . ' (Code: ' . ($result['return_code'] ?? 'unknown') . ')'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Không thể kết nối đến ZaloPay. HTTP Status: ' . $response->status()
                ];
            }
        } catch (Exception $e) {
            Log::error('ZaloPay Create Order Error:', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate callback từ ZaloPay
     */
    public function validateCallback($data)
    {
        try {
            if (!isset($data['data']) || !isset($data['mac'])) {
                return [
                    'success' => false,
                    'message' => 'Dữ liệu callback không hợp lệ'
                ];
            }

            $callbackData = json_decode($data['data'], true);
            
            // Tạo MAC để verify
            $mac = $this->generateCallbackMac($callbackData, $this->config['key2']);
            
            if ($mac !== $data['mac']) {
                return [
                    'success' => false,
                    'message' => 'MAC không hợp lệ'
                ];
            }

            return [
                'success' => true,
                'data' => $callbackData
            ];
        } catch (Exception $e) {
            Log::error('ZaloPay Validate Callback Error:', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Lỗi validate callback'
            ];
        }
    }

    /**
     * Query trạng thái đơn hàng
     */
    public function queryOrder($appTransId)
    {
        try {
            $data = [
                'app_id' => (int)$this->config['app_id'],
                'app_trans_id' => $appTransId,
            ];

            // MAC cho query order: app_id|app_trans_id|key1 (theo ZaloPay docs)
            $macData = $data['app_id'] . '|' . $data['app_trans_id'] . '|' . $this->config['key1'];
            $data['mac'] = hash_hmac('sha256', $macData, $this->config['key1']);
            
            Log::info('ZaloPay Query MAC Data:', [
                'macData' => $macData,
                'key1' => $this->config['key1'],
                'generated_mac' => $data['mac']
            ]);

            $response = Http::post($this->config['query_order_url'], $data);
            
            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('ZaloPay Query Order Response:', $result);
                
                return [
                    'success' => true,
                    'data' => $result
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Không thể truy vấn trạng thái đơn hàng'
                ];
            }
        } catch (Exception $e) {
            Log::error('ZaloPay Query Order Error:', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Lỗi truy vấn đơn hàng'
            ];
        }
    }

    /**
     * Tạo MAC cho Create Order theo đúng ZaloPay spec
     */
    private function generateCreateOrderMac($data, $key)
    {
        // Theo tài liệu ZaloPay: app_id|app_trans_id|app_user|amount|app_time|embed_data|item
        $hashData = $data['app_id'] . '|' . $data['app_trans_id'] . '|' . $data['app_user'] . '|' . 
                   $data['amount'] . '|' . $data['app_time'] . '|' . $data['embed_data'] . '|' . $data['item'];
        
        return hash_hmac('sha256', $hashData, $key);
    }

    /**
     * Tạo MAC cho request chung (query order, etc.)
     */
    private function generateMac($data, $key)
    {
        // Loại bỏ mac field nếu có
        unset($data['mac']);
        
        // Sắp xếp theo key
        ksort($data);
        
        // Tạo string để hash
        $hashData = '';
        foreach ($data as $k => $v) {
            if ($hashData != '') {
                $hashData .= '&';
            }
            $hashData .= $k . '=' . $v;
        }
        
        return hash_hmac('sha256', $hashData, $key);
    }

    /**
     * Tạo MAC cho callback
     */
    private function generateCallbackMac($data, $key)
    {
        $hashData = $data['app_id'] . '|' . $data['app_trans_id'] . '|' . $data['app_user'] . '|' . $data['amount'] . '|' . $data['app_time'] . '|' . $data['embed_data'] . '|' . $data['item'];
        return hash_hmac('sha256', $hashData, $key);
    }

    /**
     * Lấy thông tin lỗi dựa trên return_code
     */
    public function getErrorMessage($returnCode)
    {
        $errors = [
            '-1' => 'Giao dịch thất bại',
            '-2' => 'Giao dịch bị từ chối',
            '-3' => 'Giao dịch bị hủy',
            '-4' => 'Giao dịch hết hạn',
            '-5' => 'Giao dịch đang xử lý',
            '-6' => 'Giao dịch không tồn tại',
            '-7' => 'Số tiền không hợp lệ',
            '-8' => 'Tài khoản không đủ số dư',
            '-9' => 'Thông tin không hợp lệ',
            '-10' => 'Hệ thống bảo trì',
        ];

        return $errors[$returnCode] ?? 'Lỗi không xác định';
    }

    /**
     * Kiểm tra giao dịch thành công
     */
    public function isSuccessfulPayment($returnCode)
    {
        return $returnCode == 1;
    }
} 