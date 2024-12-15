<!-- Hero Carousel Section -->
<section class="bg-gray-900 text-gray-100 py-16">
    <div class="container mx-auto px-6 lg:px-8">
        <div id="carousel" class="relative overflow-hidden">
            <!-- Slides -->
            <div class="flex transition-transform duration-700" id="carouselInner" style="transform: translateX(0%);">
                <!-- Slide 1: Text Only -->
                <div class="min-w-full flex items-center justify-center">
                    <div class="text-center lg:text-left max-w-3xl mx-auto">
                        <h1 class="text-5xl font-bold text-blue-400 mb-4">Russell McInnes</h1>
                        <h2 class="text-3xl font-semibold mb-6">Software Engineer</h2>
                        <p class="text-gray-300 leading-relaxed">
                            Software engineer with years of experience in Software Development and Automation.
                        </p>
                        <div class="mt-6">
                            <a href="{{ asset('R_McInnes_CV_2024.pdf') }}" 
                               download="Russell_McInnes_CV" 
                               class="w-full px-4 py-2 bg-teal-500 text-gray-900 font-bold rounded-lg hover:bg-teal-600 transition-all">
                                View/Download CV
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: Image and About Text -->
                <div class="min-w-full flex flex-col lg:flex-row items-center justify-center space-y-8 lg:space-y-0 lg:space-x-12">
                    <div class="lg:w-1/1 flex justify-center">
                        <img src="{{ asset('photo.jpg') }}" alt="About Me" 
                             class="rounded-lg shadow-lg object-cover w-full max-w-sm">
                    </div>
                    <div class="lg:w-1/2 text-center lg:text-left">
                        <h2 class="text-3xl font-bold text-blue-400 mb-4">About Me</h2>
                        <p class="text-gray-300 leading-relaxed">
                            I am Russell, a software engineer from Glasgow, Scotland. I'm currently employed at a local university, 
                            where I oversee backend operations and develop innovative solutions to a variety of problems. My background 
                            includes an HND in Software Development from Glasgow Clyde College, and I am currently pursuing an Honours Degree 
                            in Software Engineering. This is my portfolio website, a small corner of the internet where I can showcase my projects.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Controls Below the Carousel -->
        <div class="mt-8 flex justify-center space-x-4">
            <button id="carouselBtn1" class="w-4 h-4 bg-blue-500 rounded-full focus:outline-none"></button>
            <button id="carouselBtn2" class="w-4 h-4 bg-gray-500 rounded-full focus:outline-none"></button>
        </div>
    </div>
</section>

<!-- JavaScript for Carousel -->
<script>
    const carouselInner = document.getElementById('carouselInner');
    const btn1 = document.getElementById('carouselBtn1');
    const btn2 = document.getElementById('carouselBtn2');

    btn1.addEventListener('click', () => {
        carouselInner.style.transform = 'translateX(0%)';
        btn1.classList.replace('bg-gray-500', 'bg-blue-500');
        btn2.classList.replace('bg-blue-500', 'bg-gray-500');
    });

    btn2.addEventListener('click', () => {
        carouselInner.style.transform = 'translateX(-100%)';
        btn2.classList.replace('bg-gray-500', 'bg-blue-500');
        btn1.classList.replace('bg-blue-500', 'bg-gray-500');
    });
</script>


<!-- Development Section -->
<section id="1stitem" class="bg-gray-800 text-gray-100 py-16">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="text-center mb-8">
            <h3 class="text-4xl font-bold text-blue-400 mb-4">Software Development</h3>
        </div>
        <div class="flex flex-col md:flex-row items-center space-y-8 md:space-y-0 md:space-x-12">
            <div class="md:w-1/2">
                <img src="images/html.jpg" 
                     alt="Software Development" 
                     class="w-full h-64 object-cover rounded-lg shadow-lg">
            </div>
            <div class="md:w-1/2 text-center md:text-left">
                <p class="text-gray-300 leading-relaxed text-lg">
                    I’ve been passionate about web development from a young age. It’s incredible how technology allows us to 
                    connect with people globally. I focus on building functional, clean, and visually appealing web applications, 
                    incorporating modern cloud technologies to deliver optimal results.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- IT Section -->
<section id="2nditem" class="bg-gray-900 text-gray-100 py-16">
    <div class="container mx-auto px-6 lg:px-8">
        <div class="text-center mb-8">
            <h3 class="text-4xl font-bold text-blue-400 mb-4">IT Innovation</h3>
        </div>
        <div class="flex flex-col md:flex-row-reverse items-center md:items-start space-y-8 md:space-y-0 md:space-x-16">
            <!-- Image -->
            <div class="md:w-1/2 flex justify-center">
                <img src="images/automation.jpg" 
                     alt="IT Projects" 
                     class="w-full h-64 object-cover rounded-lg shadow-lg">
            </div>
            <!-- Text -->
            <div class="md:w-1/2 text-center md:text-left md:pl-6">
                <p class="text-gray-300 leading-relaxed text-lg">
                    I’ve developed a deep passion for exploring new ways to deliver efficient and reliable IT services. 
                    From researching innovative tools and technologies to implementing automation solutions, I strive to enhance workflows and improve the overall experience for users. 
                </p>
                <p class="text-gray-300 leading-relaxed text-lg mt-4">
                    Whether it's automating routine tasks to save valuable time, streamlining processes to reduce complexity, or deploying cutting-edge solutions to solve unique challenges, 
                    my goal is always to drive improvement.
                </p>
            </div>
        </div>
    </div>
</section>

