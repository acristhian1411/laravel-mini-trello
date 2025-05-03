<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Posts/Index',[
            'posts' => Post::all()
        ]);
    }

    public function create(){
        return Inertia::render('Posts/Create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        $filePath = $request->file('image')?->store('uploads', 'public');

        Post::create(array_merge($validated, [
            'image' => $filePath,
        ]));

        return redirect()->route('posts.index');
    }

    public function edit(Post $post){
        return Inertia::render('Posts/Edit',[
            'post'=> $post
        ]);
    }

    public function update(Request $request, Post $post){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'file' => 'nullable|file',
        ]);

        $filePath = $request->file('file')?->store('uploads', 'public');

        $post->update(array_merge($validated, [
            'file_path' => $filePath,
        ]));

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index');
    }
}
