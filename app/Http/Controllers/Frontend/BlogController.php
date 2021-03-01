<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function list()
    {
        $posts = Post::active()->paginate(1);
        return view('frontend.pages.blog-list', compact('posts'));
    }

    public function view($slug)
    {
        $post = Post::active()->where('slug', $slug)->firstOrFail();
        $tags = $post->tags;
        $similarPosts = Post::active()->whereHas('tags', function ($query) use ($tags) {
            for ($i = 0; $i < count($tags); $i++){
                $query->orwhere('name', 'like',  '%' . $tags[$i]->name .'%');
            }
        })->where('id', '!=', $post->id)->limit(3)->get();
        return view('frontend.pages.blog-view', compact('post', 'similarPosts'));
    }
}
