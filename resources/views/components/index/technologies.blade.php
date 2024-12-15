<section class="py-12 bg-gray-800 text-gray-100">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-blue-400 mb-4">Technologies</h2>
            <p class="text-gray-300 leading-relaxed max-w-2xl mx-auto">
                The technologies I use as a full-stack web developer. This list will regularly update as I grow and change as a developer. 
                Click on the images to explore how I utilize each technology in my projects!
            </p>
        </div>

        <!-- Grid of Technologies -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($tools as $tool)
                <div>
                    <div class="group cursor-pointer" data-bs-toggle="modal" data-bs-target="#toolModal{{ $tool->id }}">
                        <img src="{{ $tool->image }}" 
                             alt="{{ $tool->title }}" 
                             class="w-full h-40 object-cover rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="toolModal{{ $tool->id }}" tabindex="-1" aria-labelledby="toolModalLabel{{ $tool->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content bg-gray-800 text-gray-100">
                            <div class="modal-header border-b border-gray-700">
                                <h5 class="modal-title">{{ $tool->title }}</h5>
                                <button type="button" class="btn-close text-gray-400" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ $tool->image }}" alt="{{ $tool->title }}" class="mb-4 rounded-lg w-1/2 mx-auto">
                                <p class="text-gray-300">{{ $tool->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
