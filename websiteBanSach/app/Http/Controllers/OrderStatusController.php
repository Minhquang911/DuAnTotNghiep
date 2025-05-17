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
        return redirect()->route('order_statuses.index')->with('success', 'Thêm trạng thái thành công');
    }

    public function edit($id)
    {
        $status = OrderStatus::findOrFail($id);
        return view('admin.order_statuses.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_name' => 'required|string|max:255',
            'display_order' => 'required|integer',
        ]);

        $status = OrderStatus::findOrFail($id);
        $status->update($request->all());

        return redirect()->route('order_statuses.index')->with('success', 'Cập nhật trạng thái thành công');
    }

    public function destroy($id)
    {
        OrderStatus::destroy($id);
        return redirect()->route('order_statuses.index')->with('success', 'Xóa trạng thái thành công');
    }
}
