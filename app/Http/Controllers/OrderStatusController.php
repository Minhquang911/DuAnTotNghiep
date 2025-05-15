<?php
namespace App\Http\Controllers;

use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function index()
    {
        $statuses = OrderStatus::orderBy('display_order')->get();
        return view('admin.order_statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.order_statuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status_name' => 'required|string|max:255',
            'display_order' => 'required|integer',
        ]);

        OrderStatus::create($request->all());
        return redirect()->route('admin.order-statuses.index')->with('success', 'Thêm trạng thái thành công!');
    }

    public function edit(OrderStatus $orderStatus)
    {
        return view('admin.order_statuses.edit', compact('orderStatus'));
    }

    public function update(Request $request, OrderStatus $orderStatus)
    {
        $request->validate([
            'status_name' => 'required|string|max:255',
            'display_order' => 'required|integer',
        ]);

        $orderStatus->update($request->all());
        return redirect()->route('admin.order-statuses.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(OrderStatus $orderStatus)
    {
        $orderStatus->delete();
        return redirect()->route('admin.order-statuses.index')->with('success', 'Đã xoá!');
    }
}
