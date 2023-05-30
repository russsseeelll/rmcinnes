<x-layout>
    <section class="edit-container">
        <div class="container">
            <div class="card edit-card">
                <div class="card-body">
                    <header class="text-center mb-4">
                        <h2 class="portfolio-heading">Edit Portfolio Entry</h2>
                        <p class="portfolio-sub-heading">Edit: {{$portfolio->title}}</p>
                    </header>
                    
                    <form method="POST" action="{{ route('portfolio.update', ['portfolio' => $portfolio->id]) }}" enctype="multipart/form-data" class="edit-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Portfolio Title</label>
                            <input type="text" class="form-control" name="title" value="{{$portfolio->title}}"/>
                            @error('title')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" value="{{date('Y-m-d')}}"/>
                            @error('date')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="Home automation" {{$portfolio->category == "Home automation" ? 'selected' : ''}}>Home automation</option>
                                <option value="Web development" {{$portfolio->category == "Web development" ? 'selected' : ''}}>Web development</option>
                            </select>
                            @error('category')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags (Comma Separated)</label>
                            <input type="text" class="form-control" name="tags" value="{{$portfolio->tags}}"/>
                            @error('tags')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control" name="summary" rows="5">{{$portfolio->summary}}</textarea>
                            @error('summary')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="5">{{$portfolio->description}}</textarea>
                            @error('description')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                           
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" class="form-control" name="link" value="{{$portfolio->link}}"/>
                            @error('link')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" name="image"/>
                            @error('image')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout>
