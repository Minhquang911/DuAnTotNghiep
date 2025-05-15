<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->get();
        return view('admin.promotions.index', compact('promotions'));
    }
    public function create()
    {
        return view('admin.promotions.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promotions',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
        ]);
        Promotion::create($request->all());
        return redirect()->route('promotions.index')->with('success', 'Tạo mã thành công');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:promotions,code,' . $promotion->id,
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
        ]);

        $promotion->update($request->all());
        return redirect()->route('promotions.index')->with('success', 'Cập nhật mã thành công');
    }

    public function destroy($id)
    {
        Promotion::destroy($id);
        return back()->with('success', 'Đã xoá mã khuyến mãi');
    }

    public function apply(Request $request)
    {
        $request->validate(['code' => 'required', 'order_total' => 'required|numeric']);
        $promo = Promotion::where('code', $request->code)->where('is_active', true)->first();

        if (!$promo) return back()->with('error', 'Mã không tồn tại');

        $now = now();
        if ($promo->start_date && $now < $promo->start_date)
            return back()->with('error', 'Mã chưa có hiệu lực');
        if ($promo->end_date && $now > $promo->end_date)
            return back()->with('error', 'Mã đã hết hạn');
        if ($promo->max_usage && $promo->usage_count >= $promo->max_usage)
            return back()->with('error', 'Mã đã hết lượt sử dụng');
        if ($promo->min_order_amount && $request->order_total < $promo->min_order_amount)
            return back()->with('error', 'Đơn hàng chưa đủ điều kiện');

        $discount = $promo->discount_type === 'percent'
            ? min($request->order_total * $promo->discount_value / 100, $promo->max_discount_amount ?? INF)
            : $promo->discount_value;

        $promo->increment('usage_count');
        return back()->with('success', "Áp dụng thành công! Giảm $discount");
    }
}
