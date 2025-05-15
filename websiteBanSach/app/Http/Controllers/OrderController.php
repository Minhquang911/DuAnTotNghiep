<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;



use App\Models\Order;
use App\Models\OrderStatus;


class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // Hiển thị chi tiết đơn hàng
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $order = Order::findOrFail($id);

        // Lấy đầy đủ id và status_name
        $statuses = OrderStatus::select('id', 'status_name')->get();

        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $data = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email',
            'user_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'status_id' => 'required|integer',
            'payment_method' => 'required|string',
            'promotion_code' => 'nullable|string',
            'promotion_discount' => 'nullable|numeric',
            'shipping_fee' => 'required|numeric',
            'final_amount' => 'required|numeric',
        ]);

        // Lấy đơn hàng
        $order = Order::findOrFail($id);

        // Kiểm tra điều kiện trạng thái
        if (in_array($order->status_id, [4, 5])) {
            return redirect()->back()->withErrors('Không thể thay đổi trạng thái vì đơn hàng đã hoàn tất hoặc bị huỷ.');
        }

        if ($order->status_id == 3 && in_array($data['status_id'], [1, 2])) {
            return redirect()->back()->withErrors('Không thể chọn trạng thái cũ khi đơn hàng đang giao.');
        }

        if ($order->status_id == 2 && $data['status_id'] == 1) {
            return redirect()->back()->withErrors('Không thể quay lại trạng thái "Chờ xác nhận".');
        }

        // Cập nhật toàn bộ thông tin
        $order->update($data);

        return redirect()->route('admin.orders.index')->with('success', 'Đã cập nhật đơn hàng thành công!');
    }



    // Tìm kiếm đơn hàng
    public function search(Request $request)
    {
        $query = $request->input('query');

        $orders = Order::where('id', $query)
            ->orWhere('user_name', 'like', "%$query%")
            ->orWhere('user_phone', 'like', "%$query%")
            ->paginate(10);

        return view('admin.orders.index', compact('orders', 'query'));
    }



}

