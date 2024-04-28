<section class="contact dark" id="contact-form">
<div class="container">
       <div class="row justify-content-between">
          <div class="col-lg-5 mb-5 mb-lg-0">
             <h2 class="contact-title mb-4 mt-0">Contact</h2>
             <p class="contact-sub-heading mb-0">Feel free to review my accomplishments or examine my work to determine if I am a good fit for your project:
             </p>
             <div class="mt-5 pt-md-4">
                <div class="card contact-link mt-4">
                   <div class="card-body d-flex align-items-center">
                      <i class="fas fa-envelope contact-icon fs-3 px-2"></i>
                      <a class="contact-link-content ms-4" href="mailto:admin@rmcinnes.co.uk">Email</a>
                   </div>
                </div>
                <div class="card contact-link mt-4">
                   <div class="card-body d-flex align-items-center">
                      <i class="fab fa-github contact-icon fs-3 px-2"></i>
                      <a class="contact-link-content ms-4" href="https://github.com/russsseeelll">GitHub</a>
                   </div>
                </div>
                <div class="card contact-link mt-4">
                   <div class="card-body d-flex align-items-center">
                      <i class="fab fa-linkedin contact-icon fs-3 px-2"></i>
                      <a class="contact-link-content ms-4" href="https://www.linkedin.com/in/russell-mcinnes-8b94a615/">LinkedIn</a>
                   </div>
                </div>
             </div>
          </div>
          <div class="col-lg-5">
             <div class="card contact-form-card">
                 @if(session('message_sent'))
                     <div class="alert alert-success">
                         {{ session('message_sent') }}
                     </div>
                 @endif
                 <div class="card-body p-md-5">
                   <h2 class="contact-heading mb-3">Contact Me</h2>
                   <p class="contact-sub-heading mb-5">Please contact me if you have any queries or want to discuss a project!
                   </p>
                   <form method="POST" action="/contact">
                     @csrf
                     <div class="form-group mb-3 mt-2">
                        <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ old('name') }}" />
                        @error('name')
                           <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                     <div class="form-group mb-3 mt-2">
                        <input type="email" name="email" class="form-control" placeholder="Enter Email" value="{{ old('email') }}" />
                        @error('email')
                           <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                     <div class="form-group mb-3">
                        <textarea name="message" class="form-control" placeholder="Enter Message" rows="3">{{ old('message') }}</textarea>
                        @error('message')
                           <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                     <div class="text-end">
                         <button type="submit" class="btn btn-primary contact-btn">Submit</button>
                     </div>
                 </form>
                </div>
             </div>
          </div>
       </div>
    </div>
 </section>
