<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title >rmcinnes</title>
      <link rel="icon" href="./favicon.ico">
      <link rel="apple-touch-icon" href="./logo192.png">
      <meta name="theme-color" content="rgb(121, 0, 255)">
      <link rel="manifest" href="./manifest.json">
      <meta name="description" content="rmcinnes" />
      <meta name="keywords" content="" />
      <meta property="og:title" content="rmcinnes" />
      <meta property="og:image" content="" />
      <meta property="og:description" content="rmcinnes" />
      <link href="https://fonts.googleapis.com/css?family=Merriweather:&amp;display=swap" rel="stylesheet" />
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
      <link href="/css/style.css" rel="stylesheet"/>
      <link href="/css/pages-style.css" rel="stylesheet"/>
      <script src="https://cdn.tailwindcss.com"></script>
   </head>
   <body class="bg-gray-900 text-gray-100">
      <div class="flex flex-col min-h-screen">
         <!-- Navbar -->
         <header class="bg-gray-800 border-b border-gray-700 shadow-md">
            <div class="container mx-auto px-4">
               <div class="flex items-center justify-between py-4">
                  <!-- Logo -->
                  <a href="/" class="text-2xl font-bold text-blue-400">rmcinnes</a>

                  <!-- Mobile Toggle -->
                  <button class="block lg:hidden" id="nav-toggle">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                     </svg>
                  </button>

                  <!-- Navbar Links -->
                  <nav class="hidden lg:flex space-x-6" id="nav-menu">
                     <a href="/" class="hover:text-blue-400 transition">Home</a>
                     <a href="/portfolio" class="hover:text-blue-400 transition">Portfolio</a>
                     <a href="/services" class="hover:text-blue-400 transition">Services</a>
                     @auth
                     <a href="#" class="hover:text-blue-400 transition" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                     </a>
                     @endauth
                  </nav>
               </div>
            </div>
         </header>

         <!-- Main Content -->
         <main class="flex-grow bg-gray-900">
            <div class="container mx-auto bg-gray-900 px-4 py-12">
               {{$slot}}
            </div>
         </main>

         <!-- Footer -->
         <footer class="bg-gray-800 border-t border-gray-700 shadow-md">
            <div class="container mx-auto px-4 py-6">
               <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                  <!-- Logo Section -->
                  <div class="flex justify-center sm:justify-start w-full sm:w-1/3">
                     <img src="/images/logo.png" alt="Company Logo" class="h-12 object-contain">
                  </div>

                  <!-- Copyright Section -->
                  <div class="text-center sm:text-left w-full sm:w-1/3">
                     <p id="copyright" class="text-sm text-gray-400">&copy; Copyright 2024</p>
                  </div>

                  <!-- Social Links -->
                  <div class="flex justify-center sm:justify-end w-full sm:w-1/3 space-x-4">
                     <a href="https://www.linkedin.com/in/russell-mcinnes-8b94a615/" 
                        class="text-gray-400 hover:text-blue-400 transition-colors duration-300" 
                        aria-label="LinkedIn profile">
                     <i class="fab fa-linkedin text-2xl"></i>
                     </a>
                     <a href="https://github.com/russsseeelll" 
                        class="text-gray-400 hover:text-blue-400 transition-colors duration-300" 
                        aria-label="GitHub profile">
                     <i class="fab fa-github text-2xl"></i>
                     </a>
                  </div>
               </div>
            </div>
         </footer>
      </div>

      <!-- Logout Form -->
      @auth
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
         @csrf
      </form>
      @endauth

    <!-- Script for Navbar Toggle -->
    <script>
        const navToggle = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('nav-mobile');

        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('hidden');
        });
    </script>
</body>
