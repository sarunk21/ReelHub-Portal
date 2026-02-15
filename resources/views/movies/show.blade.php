@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <img src="{{ $movie['Poster'] !== 'N/A' ? $movie['Poster'] : 'https://via.placeholder.com/300x450' }}" class="img-fluid rounded shadow-lg" alt="{{ $movie['Title'] }}" style="width: 100%;">
    </div>
    <div class="col-md-8 text-white">
        <h1 class="text-warning display-4">{{ $movie['Title'] }} <span class="text-muted h3">({{ $movie['Year'] }})</span></h1>

        <div class="mb-3">
            <span class="badge bg-warning text-dark me-2">{{ $movie['Rated'] }}</span>
            <span class="badge bg-secondary me-2">{{ $movie['Runtime'] }}</span>
            <span class="badge bg-info text-dark">{{ $movie['Genre'] }}</span>
        </div>

        <div class="d-flex align-items-center mb-4">
            <h3 class="text-warning me-3 mb-0">⭐ {{ $movie['imdbRating'] }}</h3>

            @if($isFavorite)
                <form action="{{ route('favorites.destroy', $movie['imdbID']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-lg btn-danger">{{ trans('messages.remove_favorite') }}</button>
                </form>
            @else
                <form action="{{ route('favorites.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="imdb_id" value="{{ $movie['imdbID'] }}">
                    <input type="hidden" name="title" value="{{ $movie['Title'] }}">
                    <input type="hidden" name="poster" value="{{ $movie['Poster'] }}">
                    <input type="hidden" name="year" value="{{ $movie['Year'] }}">
                    <input type="hidden" name="type" value="{{ $movie['Type'] }}">
                    <button type="submit" class="btn btn-lg btn-outline-light">{{ trans('messages.add_favorite') }}</button>
                </form>
            @endif
        </div>

        <div class="mb-4">
            <h4 class="text-muted">{{ trans('messages.plot') }}</h4>
            <p class="lead">{{ $movie['Plot'] }}</p>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <h5 class="text-warning">{{ trans('messages.director') }}</h5>
                <p>{{ $movie['Director'] }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h5 class="text-warning">{{ trans('messages.writer') }}</h5>
                <p>{{ $movie['Writer'] }}</p>
            </div>
            <div class="col-md-12 mb-3">
                <h5 class="text-warning">{{ trans('messages.actors') }}</h5>
                <p>{{ $movie['Actors'] }}</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('movies.index') }}" class="btn btn-secondary">{{ trans('messages.back_list') }}</a>
        </div>
    </div>
</div>
@endsection
