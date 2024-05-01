<div class="portfolio__single-wrapper">
    <div class="portfolio__single">
        <div class="row">
            <div class="col-lg-6 portfolio__single-image">
                @if ($portfolio->image)
                <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}" />
                 @endif
            </div>
            <div class="col-lg-6">
                <div class="portfolio__single-header">
                    <h2 class="portfolio__single-title">
                        {{ $portfolio->title }}
                    </h2>
                    <span class="portfolio__single-date">{{ $portfolio->date }}</span>
                </div>
                <div class="portfolio__single-content">
                    <div class="portfolio__single-category" style="padding-bottom:15px">Category: {{ $portfolio->category }}</div>
                    <div class="portfolio__single-tech-tags">
                        @foreach(explode(',', $portfolio->tags) as $tag)
                            <span class="tech-tag">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                    <p class="portfolio__single-summary">
                        {{ $portfolio->summary }}
                    </p>
                    <p class="portfolio__single-description">
                        {!! nl2br(e($portfolio->description)) !!}
                    </p>

                </div>
                <div class="portfolio__single-footer">
                @if($portfolio->link)
                <a href="{{ $portfolio->link }}" class="portfolio__single-youtube-link">
                    @if(strpos($portfolio->link, 'github') !== false)
                        <i class="fab fa-github"></i> View project on Github
                    @elseif(strpos($portfolio->link, 'youtube') !== false)
                        <i class="fab fa-youtube"></i> Watch Project Demo
                    @endif
                </a>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
