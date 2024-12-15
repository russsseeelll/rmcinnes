<section class="bg-gray-800 text-gray-100 py-12 mt-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-6 lg:space-y-0 lg:space-x-12">
            <!-- About Text Section -->
            <div class="lg:w-2/3 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-blue-400 mb-4">My Portfolio</h2>
                <p class="text-gray-300 leading-relaxed">
                    Welcome to a selection of projects I've either developed or had the privilege of contributing to. 
                    What you're seeing is a dynamic list, regularly updated straight from my backend database. 
                    Keep an eye out for fresh additions, as I'm constantly on the move with new projects!
                </p>
            </div>

            <!-- Search Form Section -->
            <div class="lg:w-1/3 bg-gray-700 p-6 rounded-lg shadow-lg">
                <form method="GET" action="/portfolio">
                    <div class="flex flex-col space-y-4">
                        <!-- Search Bar -->
                        <input 
                            type="text" 
                            class="w-full px-4 py-2 bg-gray-900 text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" 
                            name="search" 
                            id="searchBar" 
                            placeholder="Search"
                            oninput="doSearch(this.value)">

                        <!-- Category Dropdown -->
                        <select 
                            class="w-full px-4 py-2 bg-gray-900 text-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" 
                            name="category" 
                            id="filterDropdown">
                            <option value="" selected>All</option>
                            <option value="Development">Software Development Projects</option>
                            <option value="Automation">Automation Projects</option>
                        </select>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2 bg-teal-500 text-gray-900 font-bold rounded-lg hover:bg-teal-600 transition-all">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
