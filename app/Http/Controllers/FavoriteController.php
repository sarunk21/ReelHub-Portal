<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $favorites = Auth::user()->favorites()->orderBy('created_at', 'desc')->get();
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'imdb_id' => 'required|string',
            'title' => 'required|string',
            'poster' => 'nullable|string',
            'year' => 'nullable|string',
            'type' => 'nullable|string',
        ]);

        $user = Auth::user();

        if ($user->favorites()->where('imdb_id', $data['imdb_id'])->exists()) {
            if ($request->ajax()) {
                return response()->json(['status' => 'exists', 'message' => 'Movie already in favorites']);
            }
            return redirect()->back()->with('error', 'Movie already in favorites');
        }

        $user->favorites()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Movie added to favorites']);
        }
        return redirect()->back()->with('success', 'Movie added to favorites');
    }

    public function destroy($imdb_id)
    {
        Auth::user()->favorites()->where('imdb_id', $imdb_id)->delete();

        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Movie removed from favorites']);
        }
        return redirect()->back()->with('success', 'Movie removed from favorites');
    }
}
