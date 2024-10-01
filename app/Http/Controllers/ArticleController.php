<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        try {
            $articles = Article::where('user_id', $user->id)->get();
            if ($articles->count() == 0) {
                return response()->json(["message" => "No Article Posted by " . $user->name], 200);
            }
            return response()->json($articles, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error fetching articles: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $article = Article::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'user_id' => Auth::id(),
            ]);

            return response()->json(["message" => "Article Created", "article" => $article], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error creating article: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $article = Article::where('id', $id)->where('user_id', Auth::id())->first();
            if (empty($article)) {
                return response()->json(["message" => "No Article Found"], 200);
            }
            return response()->json($article, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error fetching article: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {

        try {
            $article = Article::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            if (!empty($request->get('title')) && !empty($request->get('content'))) {
                $article->title = $request->get('title');
                $article->content = $request->get('content');
            } else if (!empty($request->get('title'))) {
                $article->title = $request->get('title');
            } else if (!empty($request->get('content'))) {
                $article->content = $request->get('content');
            } else {
                return response()->json(["message" => "Nothing to be Updated"], 200);
            }
            $article->save();
            return response()->json(["message" => "Update Successfully", "Article" => $article], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error updating article: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $article = Article::where('id', $id)->where('user_id', Auth::id())->first();
            if (empty($article)) {
                return response()->json(["message" => "No Article Found"], 200);
            }
            $article->delete();
            return response()->json(['message' => 'Article deleted'], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error deleting article: ' . $e->getMessage(),
            ], 500);
        }
    }
}
