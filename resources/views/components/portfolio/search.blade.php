<form method="GET" action="/portfolio">
    <div class="search_form row mt-4 justify-content-center align-items-center">
        <div class="col-lg-5">
            <input type="text" class="form-control portfolio-input" name="search" id="searchBar" placeholder="Search" oninput="doSearch(this.value)">
        </div>
        <div class="col-lg-5">
            <select class="form-select portfolio-input" name="category" id="filterDropdown">
                <option value="" selected>All</option>
                <option value="Web Development">Web Projects</option>
                <option value="Automation">Automation Projects</option>
            </select>
        </div>
        <div class="col-lg-2">
            <button type="submit" class="btn btn-primary contact-btn">Search</button>
        </div>
    </div>
</form>
