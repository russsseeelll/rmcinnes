<!-- HTML Part -->
<section class="technologies dark">
   <div class="container">
       <div class="row justify-content-center mb-4 mb-md-5">
           <div class="col-lg-6 text-center">
               <h2 class="technologies-heading mb-4">Web Technologies</h2>
               <p class="technologies-sub-heading mb-0">
                   The technologies I use as a full-stack web developer. This list will regularly update, as I grow and change as a developer.
                   Click on the images to explore how I utilize each technology in my projects!
               </p>

           </div>
       </div>
       <div class="row justify-content-center text-center">
         @foreach($tools as $tool)
         <div class="col-sm-6 col-md-4 col-xl-3 mt-3 mt-sm-4">
           <div class="technologies-item p-1" data-bs-toggle="modal" data-bs-target="#toolModal{{ $tool->id }}">
             <img src="{{ $tool->image }}" alt="{{ $tool->title }}" class="technologies-img img-fluid" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $tool->title }}" />
           </div>
         </div>
         
         <!-- Modal -->
         <div class="modal fade" id="toolModal{{ $tool->id }}" tabindex="-1" aria-labelledby="toolModalLabel{{ $tool->id }}" aria-hidden="true">
           <div class="modal-dialog modal-dialog-centered modal-lg">
             <div class="modal-content tech-modal">
               <div class="modal-header border-0">
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                 <img src="{{ $tool->image }}" alt="{{ $tool->title }}" class="img-fluid mb-3 rounded" />
                 <h5 class="text-center modal-title">{{ $tool->title }}</h5>
                 <p class="text-center modal-description">{{ $tool->description }}</p>
               </div>
             </div>
           </div>
         </div>
         @endforeach                            
       </div>
   </div>
</section>
