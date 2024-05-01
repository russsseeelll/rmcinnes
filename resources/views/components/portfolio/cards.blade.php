@if ($message = Session::get('message'))
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif

@if ($portfolios->isEmpty())
    <p>No portfolio entries found.</p>
@else
    @foreach($portfolios as $portfolio)
        <div class="col-12 col-md-6 col-lg-4 mt-4 d-flex">
            <article class="portfolio-post p-3 p-md-4 shadow" style="border-radius: 10px; display: flex; flex-direction: column; height: 100%; width: 100%;">
                @if ($portfolio->image)
                    <!-- Ensuring the image fits perfectly within a fixed size, maintaining aspect ratio -->
                    <div style="height: 200px; overflow: hidden; border-radius: 10px; display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}" style="max-height: 100%; max-width: 100%; object-fit: cover;">
                    </div>
                @endif
                <div class="flex-grow-1 d-flex flex-column">
                    <h4 class="portfolio-title fs-4 mb-2">{{ $portfolio->title }}</h4>
                    <p class="portfolio-author">
                        At <span>{{ $portfolio->date }}</span>
                    </p>
                    <div class="portfolio-tags mb-2">
                        @foreach(explode(',', $portfolio->tags) as $tag)
                            <a href="{{ route('portfolio', ['search' => trim($tag)]) }}" class="tech-tag">{{ trim($tag) }}</a>
                        @endforeach
                    </div>
                    <p class="portfolio-summary flex-grow-1">
                        {{ $portfolio->summary }}
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('portfolio.single', ['portfolio' => $portfolio->slug]) }}" class="btn btn-primary">More info</a>
                        @auth
                            <a href="{{ route('portfolio.edit', ['portfolio' => $portfolio->slug]) }}" class="btn btn-secondary">Edit</a>
                            <button type="button" class="btn btn-danger delete-portfolio-button" data-bs-toggle="modal" data-bs-target="#delete-modal" data-portfolio-slug="{{ $portfolio->slug }}">Delete</button>
                        @endauth
                    </div>
                </div>
            </article>
        </div>
    @endforeach
@endif

@auth
    <div class="col-12 mt-5">
        <a href="{{ route('portfolio.create')}}" class="btn btn-primary">Add New</a>
    </div>
@endauth


<div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content delete-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this portfolio?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                @if (!$portfolios->isEmpty())
                    <form action="{{ route('portfolio.destroy', ['portfolio' => $portfolio->slug]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $('.delete-portfolio-button').click(function() {
        var portfolioSlug = $(this).data('portfolio-slug');
        $('#deleteForm').attr('action', '/portfolio/' + portfolioSlug);
    });
</script>
