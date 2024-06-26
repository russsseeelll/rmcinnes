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
   </head>
   <body>
   <div class="ezy__nav1 dark-gray">
       <nav class="navbar navbar-expand-lg navbar-light py-3">
           @auth
               <li class="nav-item">
                   <a class="nav-link">Welcome, {{ auth()->user()->name }}</a>
               </li>
           @endauth
           <div class="container">
               <a class="navbar-brand" href="/">rmcinnes</a>
               <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                       aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                   <span><span></span></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarText">
                   <ul class="navbar-nav ms-auto mb-2 mb-lg-0 mt-4 mt-lg-0">
                       <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                       <li class="nav-item"><a class="nav-link" href="/portfolio">Portfolio</a></li>
                       <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                       @auth
                           <li class="nav-item">
                               <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   Logout
                               </a>
                           </li>
                           <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                               @csrf
                           </form>
                       @endauth
                   </ul>
               </div>
           </div>
       </nav>

     </div>
     <div style="display: flex; flex-direction: column; min-height: 100vh; background-color: rgb(11, 23, 39);">
         {{$slot}}
            </div>
         <section class="page_footer dark-gray">
          <div class="container">
            <div class="row d-flex justify-content-between align-items-center">
              <div class="col-6 col-sm-4">
                <div class="footer-logo">
                  <img src="/images/logo.png" alt="Company Logo" class="img-fluid mb-4 mb-lg-0" />
                </div>
              </div>
              <div class="col-6 col-sm-4">
                <p class="page_footer-text mb-lg-0" id="copyright">Copyright 2023</p>
              </div>
              <div class="col-12 col-sm-4 d-flex justify-content-center justify-content-lg-end">
                <ul class="page_footer-social nav">
                  <li>
                    <a href="https://www.linkedin.com/in/russell-mcinnes-8b94a615/" class="social-link" aria-label="LinkedIn profile"><i class="fab fa-linkedin"></i></a>
                  </li>
                  <li>
                    <a href="https://github.com/russsseeelll" class="social-link" aria-label="GitHub profile"><i class="fab fa-github"></i></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </section>




   <!-- END: Body Content -->
   <!-- Require scripts load from local -->
   <!-- <script src="./dist/js/popper.min.js" ></script> -->
   <!-- <script src="./dist/js/bootstrap.min.js" ></script> -->
   <!-- Required scripts -->
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
   <script src="/js/scripts.js"></script>
  </body>
  </html>
