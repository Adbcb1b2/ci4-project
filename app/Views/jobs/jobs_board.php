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
          <option value="60000">£60,000+</option>
          <option value="70000">£70,000+</option>
        </select>
      </div>
    </div>

    <!-- Job Listings Container -->
    <div class="row d-flex align-items-stretch" id="jobResultsContainer">
      <!-- Job 1 -->
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
        <h5 class="card-title">Software Engineer</h5>
        <h6 class="card-subtitle mb-2 text-muted">TechCorp</h6>
        <p class="card-text"><strong>Location:</strong> London</p>
        <p class="card-text"><strong>Salary:</strong> £40,000 - £50,000</p>
        <p class="card-text"><strong>Deadline:</strong> 31st December 2023</p>
        <p class="card-text">As a Software Engineer at TechCorp, you will be responsible for developing innovative software solutions, collaborating with cross-functional teams, and ensuring quality code delivery. You will...</p>
        <a href="#" class="btn view-job-btn">View Job</a>
          </div>
        </div>
      </div>

      <!-- Job 2 -->
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
        <h5 class="card-title">UX Designer</h5>
        <h6 class="card-subtitle mb-2 text-muted">DesignWorks</h6>
        <p class="card-text"><strong>Location:</strong> Manchester</p>
        <p class="card-text"><strong>Salary:</strong> £35,000 - £45,000</p>
        <p class="card-text"><strong>Deadline:</strong> 15th January 2024</p>
        <p class="card-text">Join DesignWorks as a UX Designer, where you will lead the design of intuitive and engaging user interfaces. You'll work closely with product managers to create user-centric designs and improve the overall user experience...</p>
        <a href="#" class="btn view-job-btn">View Job</a>
          </div>
        </div>
      </div>

      <!-- Job 3 -->
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
        <h5 class="card-title">Data Analyst</h5>
        <h6 class="card-subtitle mb-2 text-muted">DataInsights</h6>
        <p class="card-text"><strong>Location:</strong> Remote</p>
        <p class="card-text"><strong>Salary:</strong> £50,000 - £60,000</p>
        <p class="card-text"><strong>Deadline:</strong> 20th February 2024</p>
        <p class="card-text">As a Data Analyst at DataInsights, you will be responsible for extracting, analysing, and interpreting data to provide actionable insights. You will work with the business team to drive data-driven decisions...</p>
        <a href="#" class="btn view-job-btn">View Job</a>
          </div>
        </div>
      </div>

      <!-- Job 4 -->
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
        <h5 class="card-title">Product Manager</h5>
        <h6 class="card-subtitle mb-2 text-muted">ProductCo</h6>
        <p class="card-text"><strong>Location:</strong> Birmingham</p>
        <p class="card-text"><strong>Salary:</strong> £55,000 - £65,000</p>
        <p class="card-text"><strong>Deadline:</strong> 10th March 2024</p>
        <p class="card-text">At ProductCo, we are looking for a Product Manager to lead the development of our product roadmap. You will collaborate with development teams, define product requirements, and ensure successful product launches...</p>
        <a href="#" class="btn view-job-btn">View Job</a>
          </div>
        </div>
      </div>

      <!-- Job 5 -->
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
        <h5 class="card-title">Marketing Specialist</h5>
        <h6 class="card-subtitle mb-2 text-muted">MarketPro</h6>
        <p class="card-text"><strong>Location:</strong> Leeds</p>
        <p class="card-text"><strong>Salary:</strong> £30,000 - £40,000</p>
        <p class="card-text"><strong>Deadline:</strong> 5th April 2024</p>
        <p class="card-text">As a Marketing Specialist at MarketPro, you will create and execute marketing campaigns, analyse performance metrics, and ensure effective brand communication. You will work closely with the creative team...</p>
        <a href="#" class="btn view-job-btn">View Job</a>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


