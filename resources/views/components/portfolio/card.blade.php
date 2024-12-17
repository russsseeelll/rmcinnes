<div class="container mx-auto px-4 py-6">
    <!-- Main Portfolio Section -->
    <div class="bg-gray-800 text-gray-100 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-r from-gray-700 via-gray-800 to-gray-700">
            <h2 class="text-3xl font-bold text-blue-400">{{ $portfolio->title }}</h2>
            <p class="text-sm text-gray-400 mt-2">
                <span class="font-semibold">Category:</span> {{ $portfolio->category }} 
                | <span class="font-semibold">Date:</span> {{ $portfolio->date }}
            </p>
        </div>

        <!-- Image Section -->
        @if ($portfolio->image)
        <div class="relative bg-gray-700 flex items-center justify-center overflow-hidden">
            <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}" 
                class="max-w-full max-h-[500px] object-contain rounded-lg shadow-md p-4">
        </div>
        @endif

        <!-- Tags Section -->
        <div class="bg-gray-800 px-6 py-4 border-t border-gray-700">
            <div class="flex flex-wrap gap-3 justify-center">
                @foreach(explode(',', $portfolio->tags) as $tag)
                    <span class="inline-block bg-blue-500 text-gray-900 px-3 py-1 text-sm font-semibold rounded-lg shadow-md">
                        {{ trim($tag) }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-6">
            <!-- Summary -->
            <p class="text-gray-300 leading-relaxed text-lg font-medium">{{ $portfolio->summary }}</p>

            <!-- Description -->
            @if ($portfolio->description)
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-200 mb-2">Description</h3>
                <p class="text-gray-400 leading-relaxed whitespace-pre-line">{{ $portfolio->description }}</p>
            </div>
            @endif

            <!-- Footer -->
            @if ($portfolio->link)
            <div class="mt-8 text-center">
                <a href="{{ $portfolio->link }}" 
                   class="inline-flex items-center px-6 py-3 bg-teal-500 text-black font-bold text-lg rounded-lg shadow-md hover:bg-teal-600 transition-all">
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
