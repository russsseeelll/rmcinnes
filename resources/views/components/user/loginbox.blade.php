<section class="bg-gray-900 text-gray-100 py-12">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-lg">
            <div class="p-6 lg:p-8">
                <h2 class="text-3xl font-bold text-center text-blue-400 mb-6">Login</h2>
                
                @if ($errors->any())
                    <div class="bg-red-600 text-white rounded-lg p-4 mb-6">
                        <ul class="list-disc pl-5 space-y-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Username Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-300">User Name</label>
                        <input type="text" 
                               id="email" 
                               name="name" 
                               class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                               placeholder="Enter your username">
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="mt-1 w-full px-4 py-2 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none" 
                               placeholder="Enter your password">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition-all">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
