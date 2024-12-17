<section class="py-12 bg-gray-800 text-gray-100">
    <div class="container mx-auto px-4">
        <!-- Header -->
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
                    <!-- Technology Card -->
                    <div class="group cursor-pointer" onclick="openModal({{ $tool->id }})">
                        <img src="{{ $tool->image }}" 
                             alt="{{ $tool->title }}" 
                             class="w-full h-40 object-cover rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                    </div>
                </div>

                <!-- Modal -->
                <div id="toolModal{{ $tool->id }}" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-80 flex items-center justify-center">
                    <div class="bg-gray-800 rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2">
                        <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                            <h5 class="text-teal-400 font-bold">{{ $tool->title }}</h5>
                            <button class="text-gray-400 hover:text-gray-300 transition" onclick="closeModal({{ $tool->id }})">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="px-6 py-4 text-center">
                            <img src="{{ $tool->image }}" alt="{{ $tool->title }}" class="mb-4 rounded-lg w-1/2 mx-auto">
                            <p class="text-gray-300">{{ $tool->description }}</p>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-700 text-center">
                            <button class="px-4 py-2 bg-teal-500 text-gray-900 font-bold rounded-lg hover:bg-teal-600 transition-all"
                                    onclick="closeModal({{ $tool->id }})">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    // Open Modal
    function openModal(id) {
        const modal = document.getElementById(`toolModal${id}`);
        modal.classList.remove('hidden');
    }

    // Close Modal
    function closeModal(id) {
        const modal = document.getElementById(`toolModal${id}`);
        modal.classList.add('hidden');
    }
</script>
