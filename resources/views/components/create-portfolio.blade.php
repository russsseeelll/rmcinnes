<x-layout>
    <section class="bg-gray-900 text-gray-100 py-12">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="max-w-3xl mx-auto bg-gray-800 rounded-lg shadow-lg">
                <div class="p-6 lg:p-8">
                    <header class="text-center mb-6">
                        <h2 class="text-3xl font-bold text-blue-400">Create a Portfolio Entry</h2>
                    </header>

                    <form method="POST" action="{{route('portfolio.store')}}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-300">Portfolio Title</label>
                            <input type="text" 
                                   name="title" 
                                   class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                                   value="{{old('title')}}">
                            @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-300">Date</label>
                            <input type="date" 
                                   name="date" 
                                   class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                                   value="{{ date('Y-m-d') }}">
                            @error('date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-300">Category</label>
                            <select name="category" 
                                    class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <option value="Automation">Automation</option>
                                <option value="Development">Software Development</option>
                            </select>
                            @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-300">Tags (Comma Separated)</label>
                            <input type="text" 
                                   name="tags" 
                                   class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                                   value="{{old('tags')}}">
                            @error('tags')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Summary -->
                        <div>
                            <label for="summary" class="block text-sm font-medium text-gray-300">Summary</label>
                            <textarea name="summary" 
                                      rows="4" 
                                      class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none">{{old('summary')}}</textarea>
                            @error('summary')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" 
                                      rows="5" 
                                      class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none">{{old('description')}}</textarea>
                            @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Link -->
                        <div>
                            <label for="link" class="block text-sm font-medium text-gray-300">Link</label>
                            <input type="text" 
                                   name="link" 
                                   class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                                   value="{{old('link')}}">
                            @error('link')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-300">Image</label>
                            <input type="file" 
                                   name="image" 
                                   class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition-all">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout>
