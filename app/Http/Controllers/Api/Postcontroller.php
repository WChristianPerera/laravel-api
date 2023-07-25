<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // filtro risultati
        // gestione parametro q
        $searchString = $request->query('q');
        $categoryId = $request->query('category');

        $query = Post::with('category', 'tags');

        if ($searchString) {
            $query = $query->where('title', 'LIKE', "%${searchString}%");
        }

        if ($categoryId) {
            $query = $query->where('category_id', $categoryId);
        }

        $posts = $query->paginate(6);

        return response()->json([
            'success'   => true,
            'results'   => $posts,
        ]);
    }


    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();

        return response()->json([
            'success'   => $post ? true : false,
            'results'   => $post,
        ]);
    }


    public function random() {
        $posts = Post::inRandomOrder()->limit(9)->get();

        return response()->json([
            'success'   => true,
            'results'   => $posts,
        ]);
    }
}