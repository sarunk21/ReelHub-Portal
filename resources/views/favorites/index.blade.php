@extends('layouts.app')

@section('content')
<h2 class="mb-4 text-warning">My Favorites</h2>

@if($favorites->count() > 0)
<div class="row">
    @foreach($favorites as $movie)
        <div class="col-6 col-md-3 mb-4 movie-item">
            <div class="card movie-card h-100 text-white">
                <div style="position: relative;">
                    <a href="{{ route('movies.show', $movie->imdb_id) }}">
                        <img src="{{ $movie->poster }}" class="card-img-top" alt="{{ $movie->title }}" style="min-height: 300px; object-fit: cover; background: #222;">
                    </a>

                    <button class="btn btn-sm btn-danger btn-remove-fav"
                             style="position: absolute; top: 10px; right: 10px; border-radius: 50%;"
                             data-id="{{ $movie->imdb_id }}">
                         ✕
                     </button>
                </div>
                <div class="card-body">
                    <a href="{{ route('movies.show', $movie->imdb_id) }}" class="text-decoration-none">
                        <h5 class="card-title text-warning text-truncate" title="{{ $movie->title }}">{{ $movie->title }}</h5>
                    </a>
                    <p class="card-text">{{ $movie->year }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@else
<div class="text-center text-muted mt-5">
    <h4>You haven't added any favorites yet.</h4>
    <a href="{{ route('movies.index') }}" class="btn btn-warning mt-3">Browse Movies</a>
</div>
@endif

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.btn-remove-fav').click(function() {
            let btn = $(this);
            let id = btn.data('id');

            if(confirm('Remove from favorites?')) {
                $.ajax({
                    url: '/favorites/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        btn.closest('.col-6').fadeOut();
                    }
                });
            }
        });
    });
</script>
@endpush
