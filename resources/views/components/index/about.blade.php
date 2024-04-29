<section class="py-3 my-md-3 text-white" style="position: relative;">
    <div class="container">
        <div class="carousel slide text-center py-3 pointer-event" id="aboutCarousel" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-lg-6">
                            <h2 class="about-heading">Russell McInnes</h2>
                            <h1 class="display-4 fw-bold mb-2">Software Engineer</h1>
                            <p class="about-sub-heading">Software engineer with years of experience in Web Development and Automation.</p>
                            <div class="d-grid col-6 mx-auto pt-4">
                                <a class="btn btn-outline-light btn-hover" href="{{ asset('R_McInnes_CV_2024.pdf') }}" download="Russell_McInnes_CV">View/Download CV</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-lg-6">
                            <img src="{{ asset('photo.jpg') }}" class="img-fluid rounded mb-3" alt="About Me Image" style="max-height: 330px; width: auto;">
                        </div>
                        <div class="col-lg-6">
                            <h2 class="about-heading">About</h2>
                            <p class="about-sub-heading">I'm Russell, a web developer and automation artisan from Glasgow. I'm currently employed at a local university, handling their systems and ensuring everything's up to speed. My background includes an HND in Software Development from Glasgow Clyde College, which adds a solid foundation to my everyday work.
                                This is my portfolio website - a small corner of the internet where I get to display my projects. Feel free to explore and see what I've been working on. Enjoy your visit!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-controls d-flex justify-content-center position-absolute w-100" style="bottom: 10px;">
                <button class="carousel-indicator" data-bs-target="#aboutCarousel" data-bs-slide-to="0"></button>
                <button class="carousel-indicator" data-bs-target="#aboutCarousel" data-bs-slide-to="1"></button>
            </div>
        </div>
    </div>
</section>


@include('components.break')

<section class="about dark" id="1stitem">
    <div class="container">
       <div class="row align-items-center justify-content-center">
          <div class="col-12 col-md-5 order-2">
             <div class="d-flex flex-column justify-content-center about-content pe-md-5">
                <h4 class="fw-bold mb-3">Web-App Development <i class="fas fa-laptop-code"></i></h4>
                <p class="mb-0">
                 I developed a strong passion for web development and cloud technology at a young age.
                 Drawing from my technical background, I chose to pursue this field and enrolled in college to deepen my knowledge.
                 Now equipped with a considerable skill set, I have decided to venture into freelance web development.
                </p>
             </div>
          </div>
          <div class="col-12 col-md-5 order-1 order-md-2 mb-4 mb-md-0">
             <div>
                <img src="images/html.jpg" alt="" class="img-fluid" />
             </div>
          </div>
       </div>
    </div>
 </section>
 <section class="about dark" id="2nditem">
    <div class="container">
       <div class="row align-items-center justify-content-center ">
          <div class="col-12 col-md-5 order-2" >
             <div class="d-flex flex-column justify-content-center about-content ps-md-5">
                <h4 class="fw-bold mb-3">Automation <i class='fas fa-house-user'></i></h4>
                <p class="mb-0">
                 I became fascinated with automation at the start of the COVID-19 pandemic. Spending more time at home made me realize the potential for technology to enhance our daily lives.
                 I eagerly explored smart devices, IoT technologies and innovative solutions. This newfound passion has driven me to incorporate automation into my professional pursuits.
                </p>
             </div>
          </div>
          <div class="col-12 col-md-5 order-1 mb-4 mb-md-0">
             <div>
                <img src="images/automation.jpg" alt="" class="img-fluid" />
             </div>
          </div>
       </div>
    </div>
    <br>
    <br>
 </section>
