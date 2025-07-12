<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    // Hiển thị form liên hệ
    public function index()
    {
        $title = 'Liên hệ';
        return view('client.contact.index', compact('title'));
    }

    // Lưu liên hệ
    public function store(Request $request)
    {
        if (!Auth::check()) { 
            return response()->json(['message' => 'Bạn cần đăng nhập để gửi yêu cầu.'], 401);
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'content' => 'required|string|min:10',
        ]);

        $contact = Contact::create([
            'user_id' => Auth::id(),
            'email' => $validated['email'],
            'content' => $validated['content'],
            'is_read' => false,
        ]);

        return response()->json(['message' => 'Gửi liên hệ thành công!']);
    }
}