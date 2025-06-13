<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cập nhật trạng thái đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            margin-top: 20px;
        }
        .order-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .status-change {
            color: #28a745;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Cập nhật trạng thái đơn hàng</h2>
        </div>
        
        <div class="content">
            <p>Kính gửi {{ $order->customer_name }},</p>
            
            <p>Đơn hàng của bạn đã được cập nhật trạng thái:</p>
            
            <div class="order-info">
                <p><strong>Mã đơn hàng:</strong> #{{ $order->order_code }}</p>
                <p><strong>Trạng thái cũ:</strong> {{ $oldStatusLabel }}</p>
                <p class="status-change"><strong>Trạng thái mới:</strong> {{ $newStatusLabel }}</p>
                @if($newStatus === 'cancelled' && $order->cancel_reason)
                    <p class="text-danger"><strong>Lý do hủy:</strong> {{ $order->cancel_reason }}</p>
                @endif
                <p><strong>Thời gian cập nhật:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>

            <p><strong>Thông tin đơn hàng:</strong></p>
            <ul>
                <li>Tổng tiền hàng: {{ number_format($order->total_amount) }} VNĐ</li>
                <li>Phí vận chuyển: {{ number_format($order->shipping_fee) }} VNĐ</li>
                @if($order->discount_amount > 0)
                    <li>Giảm giá: {{ number_format($order->discount_amount) }} VNĐ</li>
                @endif
                <li>Tổng thanh toán: {{ number_format($order->amount_due) }} VNĐ</li>
            </ul>

            <p><strong>Địa chỉ giao hàng:</strong></p>
            <p>
                {{ $order->customer_address }}<br>
                @if($order->customer_ward)
                    {{ $order->customer_ward }},
                @endif
                @if($order->customer_district)
                    {{ $order->customer_district }},
                @endif
                @if($order->customer_province)
                    {{ $order->customer_province }}
                @endif
            </p>

            <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua email hoặc số điện thoại hỗ trợ.</p>
            
            <p>Trân trọng,<br>Book360 Store</p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
            <p>&copy; {{ date('Y') }} Book360. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 