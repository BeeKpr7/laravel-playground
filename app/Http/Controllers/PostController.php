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

        foreach ($validated['images'] as $path) {
            // Copy the file from a temporary location to a permanent location.
            // $fileLocation = Storage::putFile(
            //     path: 'images',
            //     file: new File(Storage::path($path))
            // );
            $url = "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Macaca_nigra_self-portrait_large.jpg/1280px-Macaca_nigra_self-portrait_large.jpg";

            $post->addMediaFromUrl($url)->toMediaCollection();

            // Add the file to the post media collection.
            // $post->addMediaFromDisk($path, 'public')
            //      ->usingName($post->title)
            //      ->toMediaCollection('images');
            
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

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        // Find the post by ID
        $post = Post::findOrFail($id);

        // Update the post with the validated data
        $post->update($validatedData);

        // Redirect to the post show page
        return redirect()->route('posts.show', $post->id);
    }

    public function destroy($id)
    {
        // Find the post by ID
        $post = Post::findOrFail($id);

        // Delete the post
        $post->delete();

        // Redirect to the posts index page
        return redirect()->route('posts.index');
    }
}
