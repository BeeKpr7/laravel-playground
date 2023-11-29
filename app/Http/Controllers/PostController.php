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
        // Retrieve all posts
        $posts = Post::latest()->paginate(7);

        // Return the view with the posts data
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        // Return the view for creating a new post
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();
        // Create a new post with the validated data
        $post = Post::create($validated);

        foreach ($validated['images'] as $key =>  $path) {
            // Copy the file from a temporary location to a permanent location.
            // $fileLocation = Storage::putFile(
            //     path: 'images',
            //     file: new File(Storage::path($path))
            // );

            // Add the file to the post media collection.
            $post->addMediaFromDisk($path, 'public')
                 ->usingName($post->title.'-'.$key)
                 ->toMediaCollection('images');
            
            // Delete the temporary directory.
            Storage::deleteDirectory('tmp/'.explode('/', $path)[1]);
        }
        // Redirect to the post show page
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show($id)
    {
        // Find the post by ID
        $post = Post::findOrFail($id);

        // Return the view with the post data
        return view('posts.show', compact('post'));
    }

    public function edit($id)
    {
        // Find the post by ID
        $post = Post::findOrFail($id);

        // Return the view for editing the post
        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        // Validate the request data
        $validatedData = $request->validated();

        // Update the post with the validated data
        $post->update($validatedData);

        if (isset($validatedData['images'])) {

            // Delete all the post media
            $post->clearMediaCollection('images');

            foreach ($validatedData['images'] as $key => $path) {
                
                // Add the file to the post media collection.
                $post->addMediaFromDisk($path, 'public')
                     ->usingName($post->title.'-'.$key)
                     ->toMediaCollection('images');

                // Delete the temporary directory.
                Storage::deleteDirectory('tmp/'.explode('/', $path)[1]);
            }
        }

        // Redirect to the post show page
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        // Delete the post
        $post->delete();

        // Redirect to the posts index page
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
