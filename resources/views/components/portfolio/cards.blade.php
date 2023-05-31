@if ($message = Session::get('message'))
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif

@if ($portfolios->isEmpty())
    <p>No portfolio entries found.</p>
@else

@foreach($portfolios as $portfolio)
    <div class="col-12 col-md-6 col-lg-4 mt-4">
        <article class="portfolio-post p-3 p-md-4 h-100">
            @if ($portfolio->image)
                <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}" class="img-fluid w-100 portfolio-banner" />
            @endif
            <div class="mt-4">
                <h4 class="portfolio-title fs-4 mb-2">{{ $portfolio->title }}</h4>
                <p class="portfolio-author">
                    <span>At <span>{{ $portfolio->date }}</span></span>
                </p>
                @foreach(explode(',', $portfolio->tags) as $tag)
                    <a href="{{ route('portfolio', ['search' => trim($tag)]) }}" class="tech-tag">{{ trim($tag) }}</a>
                @endforeach

                <p class="portfolio-summary mt-3 mb-4">
                    {{ $portfolio->summary }}
                </p>
                <a href="{{ route('portfolio.single', ['id' => $portfolio->id]) }}" class="btn portfolio-btn-read-more">More info</a>
                <a href="{{ route('portfolio.edit', ['portfolio' => $portfolio->id]) }}" class="btn portfolio-btn-read-more">Edit</a>
                <button type="button" class="btn portfolio-btn-read-more delete-portfolio-button" data-bs-toggle="modal" data-bs-target="#tech-modal" data-portfolio-id="{{ $portfolio->id }}">Delete</button>
            </div>
        </article>
    </div>
@endforeach
@endif

    <div class="col-12 mt-5" >
        <a href="{{ route('portfolio.create')}}" class="btn btn-primary contact-btn">Add New</a>
    </div>

<div class="modal fade" id="tech-modal" tabindex="-1" aria-labelledby="tech-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tech-modal">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this portfolio?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                @if (!$portfolios->isEmpty())
                    <form action="{{ route('portfolio.destroy', ['portfolio' => $portfolio->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn portfolio-btn-read-more">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
