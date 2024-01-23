<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('posts', [
            'posts' => Post::latest()->filter(request(['search']))->get(),
            'categories' => Category::all()
        ]);
    }

    public function show(Post $post)
    {
        return view('post', [
            'post' => $post
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {

        $attributes = request()->validate([
            'title' => 'required',
            'excerpt' => 'required',
            'body' => 'required',
            'img' => 'required',
            'category_id' => ['required', Rule::exists('categories', 'id')]
        ]);

        $filename = $request->getSchemeAndHttpHost() . '/public/img/' . time() . '.' . $request->img->extension();

        $request->img->move(public_path('/public/img/'), $filename);

        $attributes['img'] = $filename;

        $attributes['user_id'] = auth()->id(); // add the author id

        Post::Create($attributes);

        return redirect('/');
    }

    public function manage(User $author)
    {
        return view('posts.manage', [
            'posts' => $author->posts
        ]);

    }
    public function edit(Post $post)
    {
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Post $post)
    {
        $attributes = request()->validate([
            'title' => 'required',
            'excerpt' => 'required',
            'body' => 'required',
            'img' => 'required',
            // the id must exist in table categories, field id
            'category_id' => ['required', Rule::exists('categories', 'id')]
        ]);

        $filename='';

        $filename = request()->getSchemeAndHttpHost() . '/public/img/' . time() . '.' . request()->img->extension();

        request()->img->move(public_path('/public/img/'), $filename);

        $attributes['img'] = $filename;

        $post->update($attributes);

        return back()->with('success', 'Post Updated!');

    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Post Deleted!');
    }
}
