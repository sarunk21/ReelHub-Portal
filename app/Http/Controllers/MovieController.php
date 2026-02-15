<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MovieController extends Controller
{
    public function index()
    {
        $keyword = request('s', 'Batman');
        $data = $this->fetchFromOmdb($keyword, 1);

        $movies = [];
        if (isset($data['Search'])) {
            $movies = $data['Search'];
        }

        $favorites = [];
        if (Auth::check()) {
            $favorites = Auth::user()->favorites()->pluck('imdb_id')->toArray();
        }

        return view('movies.index', compact('movies', 'favorites', 'keyword'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('s', 'Batman');
        $page = $request->input('page', 1);

        $data = $this->fetchFromOmdb($keyword, $page);

        if (Auth::check() && isset($data['Search'])) {
            $favorites = Auth::user()->favorites()->pluck('imdb_id')->toArray();
            foreach ($data['Search'] as &$movie) {
                $movie['is_favorite'] = in_array($movie['imdbID'], $favorites);
            }
        }

        return response()->json($data);
    }

    public function show($id)
    {
        $apiKey = env('OMDB_API_KEY');
        $cacheKey = "omdb_movie_{$id}";

        $movie = \Cache::remember($cacheKey, 60, function () use ($apiKey, $id) {
            $client = new Client();
            try {
                $response = $client->request('GET', 'http://www.omdbapi.com/', [
                    'query' => [
                        'apikey' => $apiKey,
                        'i' => $id,
                        'plot' => 'full'
                    ]
                ]);
                return json_decode($response->getBody(), true);
            } catch (\Exception $e) {
                return null;
            }
        });

        $isFavorite = false;
        if (Auth::check() && $movie && isset($movie['imdbID'])) {
            $isFavorite = Auth::user()->favorites()->where('imdb_id', $movie['imdbID'])->exists();
        }

        return view('movies.show', compact('movie', 'isFavorite'));
    }

    private function fetchFromOmdb($keyword, $page)
    {
        $apiKey = env('OMDB_API_KEY');
        $cacheKey = "omdb_search_{$keyword}_{$page}";

        return \Cache::remember($cacheKey, 60, function () use ($apiKey, $keyword, $page) {
            $client = new Client();
            try {
                $response = $client->request('GET', 'http://www.omdbapi.com/', [
                    'query' => [
                        'apikey' => $apiKey,
                        's' => $keyword,
                        'page' => $page,
                    ]
                ]);

                return json_decode($response->getBody(), true);
            } catch (\Exception $e) {
                return [];
            }
        });
    }
}
