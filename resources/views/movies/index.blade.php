@extends('layouts.app')

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-md-6">
        <form action="{{ route('movies.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="s" id="search-input" class="form-control" placeholder="{{ trans('messages.search_placeholder') }}" value="{{ request('s', $keyword) }}">
                <button class="btn btn-warning" type="submit" id="search-btn">{{ trans('messages.search_button') }}</button>
            </div>
        </form>
    </div>
</div>

<div id="movies-container" class="row">
    @if(isset($movies) && count($movies) > 0)
        @foreach($movies as $movie)
            @php
                $poster = $movie['Poster'] !== "N/A" ? $movie['Poster'] : 'https://via.placeholder.com/300x450?text=No+Image';
                $isFav = in_array($movie['imdbID'], $favorites);
            @endphp
            <div class="col-6 col-md-3 mb-4 movie-item">
                <div class="card movie-card h-100 text-white">
                    <div style="position: relative;">
                         <a href="{{ route('movies.show', $movie['imdbID']) }}">
                            <img data-src="{{ $poster }}" class="card-img-top lazy-img" alt="{{ $movie['Title'] }}" style="min-height: 300px; object-fit: cover; background: #222;">
                         </a>
                         @auth
                         <button class="btn btn-sm btn-favorite {{ $isFav ? 'btn-danger' : 'btn-outline-light' }}"
                                 style="position: absolute; top: 10px; right: 10px; border-radius: 50%;"
                                 data-id="{{ $movie['imdbID'] }}"
                                 data-title="{{ $movie['Title'] }}"
                                 data-poster="{{ $poster }}"
                                 data-year="{{ $movie['Year'] }}"
                                 data-type="{{ $movie['Type'] }}">
                             ♥
                         </button>
                         @endauth
                    </div>
                    <div class="card-body">
                        <a href="{{ route('movies.show', $movie['imdbID']) }}" class="text-decoration-none">
                            <h5 class="card-title text-warning text-truncate" title="{{ $movie['Title'] }}">{{ $movie['Title'] }}</h5>
                        </a>
                        <p class="card-text">{{ $movie['Year'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12 text-center text-muted">
             <h4>{{ trans('messages.no_results') }}</h4>
        </div>
    @endif
</div>

<div id="loader" class="loader text-white" style="display: none;">
    <div class="spinner-border text-warning" role="status">
        <span class="visually-hidden">{{ trans('messages.loading') }}</span>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let page = 1;
    let keyword = "{{ $keyword }}";
    let loading = false;
    let hasMore = true;
    let observer;

    $(document).ready(function() {
        setupObserver();
        observeNewImages();

        $(document).on('click', '.btn-favorite', function() {
            let btn = $(this);
            let id = btn.data('id');
            let isFav = btn.hasClass('btn-danger');

            if (isFav) {
                $.ajax({
                    url: '/favorites/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        btn.removeClass('btn-danger').addClass('btn-outline-light');
                    }
                });
            } else {
                $.ajax({
                    url: '{{ route("favorites.store") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        imdb_id: id,
                        title: btn.data('title'),
                        poster: btn.data('poster'),
                        year: btn.data('year'),
                        type: btn.data('type')
                    },
                    success: function(res) {
                        btn.removeClass('btn-outline-light').addClass('btn-danger');
                    }
                });
            }
        });

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500) {
                if (!loading && hasMore) {
                    page++;
                    fetchMovies(true);
                }
            }
        });
    });

    function setupObserver() {
        observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute('data-src');
                    if (src) {
                        img.setAttribute('src', src);
                        img.removeAttribute('data-src');
                        img.classList.remove('lazy-img');
                        obs.unobserve(img);
                    }
                }
            });
        });
    }

    function fetchMovies(append = false) {
        if (loading) return;
        loading = true;
        $('#loader').show();

        $.ajax({
            url: "{{ route('movies.search') }}",
            data: { s: keyword, page: page },
            success: function(response) {
                if (response.Response === "True") {
                    let newHtml = '';
                    if (response.Search) {
                         response.Search.forEach(movie => {
                            let poster = movie.Poster !== "N/A" ? movie.Poster : 'https://via.placeholder.com/300x450?text=No+Image';
                            let isFavClass = movie.is_favorite ? 'btn-danger' : 'btn-outline-light';
                            let authBtn = '';
                            @auth
                            authBtn = `
                                <button class="btn btn-sm btn-favorite ${isFavClass}"
                                     style="position: absolute; top: 10px; right: 10px; border-radius: 50%;"
                                     data-id="${movie.imdbID}"
                                     data-title="${movie.Title}"
                                     data-poster="${poster}"
                                     data-year="${movie.Year}"
                                     data-type="${movie.Type}">
                                 ♥
                                </button>
                            `;
                            @endauth

                            newHtml += `
                                <div class="col-6 col-md-3 mb-4 movie-item">
                                    <div class="card movie-card h-100 text-white">
                                        <div style="position: relative;">
                                            <a href="/movie/${movie.imdbID}">
                                                <img data-src="${poster}" class="card-img-top lazy-img" alt="${movie.Title}" style="min-height: 300px; object-fit: cover; background: #222;">
                                            </a>
                                            ${authBtn}
                                        </div>
                                        <div class="card-body">
                                            <a href="/movie/${movie.imdbID}" class="text-decoration-none">
                                                <h5 class="card-title text-warning text-truncate" title="${movie.Title}">${movie.Title}</h5>
                                            </a>
                                            <p class="card-text">${movie.Year}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        $('#movies-container').append(newHtml);
                        observeNewImages();
                    }
                } else {
                    hasMore = false;
                }
            },
            complete: function() {
                loading = false;
                $('#loader').hide();
            }
        });
    }

    function observeNewImages() {
        const images = document.querySelectorAll('.lazy-img');
        images.forEach(img => {
            observer.observe(img);
        });
    }
</script>
@endpush

