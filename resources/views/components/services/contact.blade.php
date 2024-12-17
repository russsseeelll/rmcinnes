<!-- Contact Section -->
<section id="contact-form" class="bg-gray-900 text-gray-100 py-16">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Details -->
            <div>
                <h2 class="text-4xl font-bold text-blue-400 mb-6">Contact Me</h2>
                <p class="text-gray-300 leading-relaxed mb-6">
                    Feel free to reach out if you’d like to discuss a project or have any questions about my services. 
                    I’m always happy to connect and explore how I can help meet your goals.
                </p>
                <div class="space-y-4">
                    <a href="mailto:russell@rmcinnes.co.uk" 
                       class="flex items-center text-blue-400 hover:text-blue-500 transition">
                        <i class="fas fa-envelope text-2xl mr-4"></i> russell@rmcinnes.co.uk
                    </a>
                    <a href="https://github.com/russsseeelll" 
                       class="flex items-center text-blue-400 hover:text-blue-500 transition">
                        <i class="fab fa-github text-2xl mr-4"></i> GitHub
                    </a>
                    <a href="https://www.linkedin.com/in/russell-mcinnes-8b94a615/" 
                       class="flex items-center text-blue-400 hover:text-blue-500 transition">
                        <i class="fab fa-linkedin text-2xl mr-4"></i> LinkedIn
                    </a>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                    @if(session('message_sent'))
                        <div class="bg-green-500 text-white p-4 rounded mb-6">
                            {{ session('message_sent') }}
                        </div>
                    @endif
                    <h3 class="text-2xl font-bold text-blue-400 mb-4">Send a Message</h3>
                    <p class="text-gray-300 mb-6">I’d love to hear from you. Fill out the form below, and I’ll get back to you as soon as possible.</p>
                    <form method="POST" action="/contact">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <input type="text" name="name" class="w-full px-4 py-3 bg-gray-900 text-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Your Name" value="{{ old('name') }}">
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <input type="email" name="email" class="w-full px-4 py-3 bg-gray-900 text-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Your Email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <textarea name="message" rows="4" class="w-full px-4 py-3 bg-gray-900 text-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" placeholder="Your Message">{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-right">
                                <button type="submit" class="w-full px-4 py-2 bg-teal-500 text-gray-900 font-bold rounded-lg hover:bg-teal-600 transition-all">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
