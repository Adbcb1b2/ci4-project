<div>
  <nav class="navbar bg-body-tertiary custom-background">
    <div class="container-fluid">
      <a class="navbar-brand banner-title px-3">Jobs Board</a>
      <form class="d-flex" role="search">
        <div class="input-group">
          <!-- Icon -->
          <span class="input-group-text">
            <i class="bi bi-search text-dark"></i>
          </span>
          <!-- Search Bar -->
          <input type="text" class="form-control search-bar" placeholder="Search jobs...">
        </div>
      </form>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-3">
    
        <!-- Filter Section -->
    <div class="row mt-4 mb-4">
        <div class="col-md-4">
            <label for="locationFilter" class="form-label">Location</label>
            <select id="locationFilter" class="form-select">
                <!-- Options will be added dynamically -->
            </select>
        </div>
        <div class="col-md-4">
            <label for="jobDescriptionFilter" class="form-label">Job Title</label>
            <select id="jobDescriptionFilter" class="form-select">
                <!-- Options will be added dynamically -->
            </select>
        </div>
        <div class="col-md-4">
            <label for="salaryFilter" class="form-label">Minimum Salary</label>
            <select id="salaryFilter" class="form-select">
                <option value="30000">£30,000+</option>
                <option value="40000">£40,000+</option>
                <option value="50000">£50,000+</option>
                <option value="50000">£60,000+</option>
                <option value="50000">£70,000+</option>
                
            </select>
        </div>
    </div>
  </div> 

</div> 
