<div class="portfolio__single-wrapper">
    <div class="portfolio__single">
        <div class="row">
            <div class="col-lg-6 portfolio__single-image">
                <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}">
            </div>
            <div class="col-lg-6">
                <div class="portfolio__single-header">
                    <h2 class="portfolio__single-title">
                        <i class="fas fa-home"></i>
                        {{ $portfolio->title }}
                    </h2>
                    <span class="portfolio__single-date">{{ $portfolio->date }}</span>
                </div>
                <div class="portfolio__single-content">
                    <div class="portfolio__single-category">Category: {{ $portfolio->category }}</div>
                    <div class="portfolio__single-tech-tags">
                        <!-- As tags are stored as comma-separated values, let's split them -->
                        @foreach(explode(',', $portfolio->tags) as $tag)
                            <span class="tech-tag">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                    <p class="portfolio__single-description">
                        {{ $portfolio->description }}
                    </p>
                    <p class="portfolio__single-summary">
                        {{ $portfolio->summary }}
                    </p>
                </div>
                <div class="portfolio__single-footer">
                    <!-- If there's a link, display it -->
                    @if($portfolio->link)
                        <a href="{{ $portfolio->link }}" class="portfolio__single-youtube-link">
                            <i class="fab fa-youtube"></i> Watch Project Demo
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>