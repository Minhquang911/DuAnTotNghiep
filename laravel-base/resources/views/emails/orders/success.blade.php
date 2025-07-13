<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Đặt hàng thành công</title>
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
            background-color: #28a745;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            color: #fff;
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
            <h2>Đặt hàng thành công</h2>
        </div>

        <div class="content">
            <p>Xin chào {{ $order->customer_name }},</p>

            <p>Cảm ơn bạn đã đặt hàng tại <strong>Book360</strong>.</p>

            <div class="order-info">
                <p><strong>Mã đơn hàng:</strong> #{{ $order->order_code }}</p>
                <p><strong>Thời gian đặt:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method_label ?? 'Không xác định' }}</p>
            </div>

            <p><strong>Thông tin đơn hàng:</strong></p>
            <ul>
                <li>Tổng tiền hàng: {{ number_format($order->total_amount) }} VNĐ</li>
                <li>Phí vận chuyển: {{ number_format($order->shipping_fee) }} VNĐ</li>
                @if ($order->discount_amount > 0)
                    <li>Giảm giá: {{ number_format($order->discount_amount) }} VNĐ</li>
                @endif
                <li><strong>Tổng thanh toán:</strong> {{ number_format($order->amount_due) }} VNĐ</li>
            </ul>

            <p><strong>Địa chỉ giao hàng:</strong></p>
            <p>
                {{ $order->customer_address }}<br>
                @if ($order->customer_ward)
                    {{ $order->customer_ward }},
                @endif
                @if ($order->customer_district)
                    {{ $order->customer_district }},
                @endif
                @if ($order->customer_province)
                    {{ $order->customer_province }}
                @endif
            </p>

            <p><strong>Danh sách sản phẩm:</strong></p>

            <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th style="text-align: left;">Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>
                                {{ $item->product_name }}<br>
                                <small>{{ $item->product_variant_name }}</small>
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">{{ number_format($item->price) }}₫</td>
                            <td style="text-align: right;">{{ number_format($item->total) }}₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>Chúng tôi sẽ xử lý đơn hàng và liên hệ khi đơn hàng được giao cho đơn vị vận chuyển.</p>

            <p>Nếu bạn có bất kỳ câu hỏi nào, xin vui lòng liên hệ với chúng tôi qua email hoặc hotline hỗ trợ.</p>

            <p>Trân trọng,<br>Đội ngũ Book360</p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động. Vui lòng không phản hồi.</p>
            <p>&copy; {{ date('Y') }} Book360. All rights reserved.</p>
        </div>
    </div>
</body>

</html>