<div class="container mx-auto px-4 py-6">
    <!-- Success Message -->
    @if ($message = Session::get('message'))
        <div class="alert alert-success text-center mb-6">
            {{ $message }}
        </div>
    @endif

    <!-- Empty State -->
    @if ($portfolios->isEmpty())
        <p class="text-center text-gray-500 text-lg">No portfolio entries found.</p>
    @else
        <!-- Portfolio Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($portfolios as $portfolio)
                <!-- Portfolio Card -->
                <div class="bg-gray-800 text-gray-100 rounded-lg shadow-lg flex flex-col overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gray-800 px-4 py-3 text-center">
                        <h3 class="text-lg font-bold text-gray-200 truncate">{{ $portfolio->title }}</h3>
                    </div>

                    <!-- Image Section -->
                    @if ($portfolio->image)
                    <div class="w-full h-48 bg-gray-700 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($portfolio->image) }}" alt="{{ $portfolio->title }}" 
                            class="object-contain w-full h-full">
                    </div>
                    @endif

                    <!-- Content Section -->
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <p class="text-gray-300 leading-relaxed mb-4">
                            {{ $portfolio->summary }}
                        </p>

                        <!-- Tags Section -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach(explode(',', $portfolio->tags) as $tag)
                                <a href="{{ route('portfolio', ['search' => trim($tag)]) }}" 
                                   class="inline-block bg-gray-700 text-gray-200 px-4 py-2 text-sm font-semibold rounded-lg hover:bg-gray-600 transition-all">
                                    {{ trim($tag) }}
                                </a>
                            @endforeach
                        </div>

                        <!-- Buttons Section -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('portfolio.single', ['portfolio' => $portfolio->slug]) }}" 
                               class="inline-block px-4 py-2 bg-teal-500 text-gray-900 font-bold text-sm rounded-lg hover:bg-teal-600 transition-all">
                                More Info
                            </a>
                            @auth
                                <div class="flex space-x-3">
                                    <a href="{{ route('portfolio.edit', ['portfolio' => $portfolio->slug]) }}" 
                                       class="text-gray-400 hover:text-gray-300 text-sm">Edit</a>
                                    <button type="button" 
                                            class="text-red-400 hover:text-red-300 text-sm delete-portfolio-button" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#delete-modal" 
                                            data-portfolio-slug="{{ $portfolio->slug }}">
                                        Delete
                                    </button>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add New Portfolio Button -->
    @auth
    <div class="text-center mt-8">
        <a href="{{ route('portfolio.create')}}" 
           class="inline-block px-4 py-2 bg-teal-500 text-gray-900 font-bold text-sm rounded-lg hover:bg-teal-600 transition-all">
            Add New
        </a>
    </div>
    @endauth
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-gray-800 text-gray-100 border-0">
            <div class="modal-header border-b border-gray-700">
                <h5 class="modal-title text-teal-400" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this portfolio?
            </div>
            <div class="modal-footer border-t border-gray-700">
                <button type="button" class="btn btn-secondary text-gray-300" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.delete-portfolio-button').forEach(button => {
        button.addEventListener('click', function () {
            const portfolioSlug = this.getAttribute('data-portfolio-slug');
            document.getElementById('deleteForm').setAttribute('action', `/portfolio/${portfolioSlug}`);
        });
    });
</script>
