<?php

namespace App\Http\Controllers\Client;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)->latest()->paginate(12);
        return view('client.posts.index', compact('posts'));
    }
}