<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(7);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        $validated = $request->validated();
        $post = Post::create($validated);

        foreach ($validated['images'] as $key =>  $path) {
            $post->addMediaFromDisk($path, 'public')
                 ->usingName($post->title.'-'.$key)
                 ->toMediaCollection('images');
            
            Storage::deleteDirectory('tmp/'.explode('/', $path)[1]);
        }
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $validatedData = $request->validated();
        $post->update($validatedData);

        if (isset($validatedData['images'])) {
            $post->clearMediaCollection('images');

            foreach ($validatedData['images'] as $key => $path) {
                $post->addMediaFromDisk($path, 'public')
                     ->usingName($post->title.'-'.$key)
                     ->toMediaCollection('images');

                Storage::deleteDirectory('tmp/'.explode('/', $path)[1]);
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}