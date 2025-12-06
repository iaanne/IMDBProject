@extends('layouts.app')

@section('content')
<div class="container py-5">

    <form action="{{ route('titles.search') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search title..."
                   value="{{ $keyword }}">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <h3 class="text-white mb-4">
        @if($keyword) Results for "{{ $keyword }}" @endif
    </h3>

    <div class="row">
        @forelse($results as $item)
            <div class="col-md-3 mb-4">
                <a href="{{ route('titles.show', $item->tconst) }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5>{{ $item->primaryTitle }}</h5>
                            <p class="text-muted">
                                {{ $item->startYear ?? '-' }} |
                                {{ $item->titleType ?? '-' }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p class="text-light">No results found.</p>
        @endforelse
    </div>

</div>
@endsection
