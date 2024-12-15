<div class="container mx-auto px-4 py-6">
    <!-- Main Portfolio Section -->
    <div class="bg-gray-800 text-gray-100 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-blue-400">{{ $portfolio->title }}</h2>
            <p class="text-sm text-gray-400 mt-1">
                <span class="font-semibold">Category:</span> {{ $portfolio->category }} 
                | <span class="font-semibold">Date:</span> {{ $portfolio->date }}
            </p>
        </div>
<!-- Image Section with Subtle Background -->
@if ($portfolio->image)
<div class="w-full h-64 bg-gray-700 flex items-center justify-center overflow-hidden">
    <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}" 
        class="object-contain h-full w-auto">
</div>
@endif

<!-- Tags Section: Dedicated Below Image -->
<div class="bg-gray-800 px-6 py-4 border-t border-gray-700">
    <div class="flex flex-wrap gap-2 justify-center">
        @foreach(explode(',', $portfolio->tags) as $tag)
            <span class="inline-block bg-gray-700 text-gray-100 px-3 py-1 text-sm rounded">
                {{ trim($tag) }}
            </span>
        @endforeach
    </div>
</div>




        <!-- Content Section -->
        <div class="p-6">
            <!-- Summary -->
            <p class="text-gray-300 leading-relaxed">{{ $portfolio->summary }}</p>

            <!-- Description -->
            @if ($portfolio->description)
            <div class="mt-4 text-gray-400 leading-relaxed">
                <h3 class="text-lg font-semibold text-gray-300 mb-2">Description</h3>
                <p class="whitespace-pre-line">{{ $portfolio->description }}</p>
            </div>
            @endif



            <!-- Footer -->
            @if ($portfolio->link)
            <div class="mt-6">
                <a href="{{ $portfolio->link }}" class="inline-flex items-center px-4 py-2 text-blue-300 border border-blue-400 rounded hover:bg-blue-400 hover:text-gray-900 transition-all">
                    @if (strpos($portfolio->link, 'github') !== false)
                        <i class="fab fa-github mr-2"></i> View on GitHub
                    @elseif (strpos($portfolio->link, 'youtube') !== false)
                        <i class="fab fa-youtube mr-2"></i> Watch Demo
                    @else
                        <i class="fas fa-external-link-alt mr-2"></i> Visit Project
                    @endif
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
