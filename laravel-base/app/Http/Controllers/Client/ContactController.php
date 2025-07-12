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

}
