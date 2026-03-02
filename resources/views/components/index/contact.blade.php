<!-- Contact Section -->
<section id="contact" class="bg-gray-900 text-gray-100 py-16">
    <div class="container mx-auto px-6 lg:px-8">

        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-blue-400 mb-4">Get In Touch</h2>
            <p class="text-gray-300 leading-relaxed max-w-2xl mx-auto">
                Have a project in mind or just want to say hello? Fill out the form or reach out directly.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <!-- Contact Details -->
            <div class="flex flex-col justify-center space-y-6">
                <a href="mailto:russell@rmcinnes.co.uk"
                   class="flex items-center gap-4 bg-gray-800 rounded-lg px-6 py-4 hover:bg-gray-700 transition-colors duration-200">
                    <div class="bg-blue-600 rounded-full p-3 flex-shrink-0">
                        <i class="fas fa-envelope text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Email</p>
                        <p class="text-gray-100 font-medium">russell@rmcinnes.co.uk</p>
                    </div>
                </a>

                <a href="https://github.com/russsseeelll"
                   class="flex items-center gap-4 bg-gray-800 rounded-lg px-6 py-4 hover:bg-gray-700 transition-colors duration-200">
                    <div class="bg-blue-600 rounded-full p-3 flex-shrink-0">
                        <i class="fab fa-github text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">GitHub</p>
                        <p class="text-gray-100 font-medium">github.com/russsseeelll</p>
                    </div>
                </a>

                <a href="https://www.linkedin.com/in/russell-mcinnes-8b94a615/"
                   class="flex items-center gap-4 bg-gray-800 rounded-lg px-6 py-4 hover:bg-gray-700 transition-colors duration-200">
                    <div class="bg-blue-600 rounded-full p-3 flex-shrink-0">
                        <i class="fab fa-linkedin text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">LinkedIn</p>
                        <p class="text-gray-100 font-medium">Russell McInnes</p>
                    </div>
                </a>
            </div>

            <!-- Contact Form -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                @if(session('message_sent'))
                    <div class="bg-green-500 text-white px-4 py-3 rounded-lg mb-6 text-sm">
                        {{ session('message_sent') }}
                    </div>
                @endif

                <form method="POST" action="/contact" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Name</label>
                        <input type="text" name="name"
                               class="w-full px-4 py-3 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none transition"
                               placeholder="your name!" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                        <input type="email" name="email"
                               class="w-full px-4 py-3 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none transition"
                               placeholder="youremail@email.com" value="{{ old('email') }}">
                        @error('email')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Message</label>
                        <textarea name="message" rows="5"
                                  class="w-full px-4 py-3 bg-gray-900 text-gray-100 rounded-lg border border-gray-700 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none transition resize-none"
                                  placeholder="Nice website! Now lets collab....">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full px-6 py-3 bg-teal-500 text-gray-900 font-bold rounded-lg hover:bg-teal-400 transition-all duration-200">
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>
