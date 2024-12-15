<x-layout>
    <section class="edit-container">
        <div class="container">
            <div class="card edit-card">
                <div class="card-body">
                    <header class="text-center mb-4">
                        <h2 class="portfolio-heading">Create a Portfolio Entry</h2>
                    </header>

                    <form method="POST" action="{{route('portfolio.store')}}" enctype="multipart/form-data" class="edit-form">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Portfolio Title</label>
                            <input type="text" class="form-control" name="title" value="{{old('title')}}"/>
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
                                <option value="Automation">Automation</option>
                                <option value="Software Development">Software Development</option>
                            </select>
                            @error('category')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags (Comma Separated)</label>
                            <input type="text" class="form-control" name="tags" value="{{old('tags')}}"/>
                            @error('tags')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control" name="summary" rows="5" value="{{old('summary')}}"></textarea>
                            @error('summary')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="5" value="{{old('description')}}"></textarea>
                            @error('description')
                            <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" class="form-control" name="link" value="{{old('link')}}"/>
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
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout>
