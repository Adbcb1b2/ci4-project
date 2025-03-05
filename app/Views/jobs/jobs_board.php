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
      <!-- Job Listings will be added dynamically -->
      <?php foreach ($jobs as $job): ?>
        <div class="col-md-4 mb-3">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= esc($job['job_title']); ?></h5>
              <h6 class="card-subtitle mb-2 text-muted"><?= esc($job['employer_name']); ?></h6>
              <p class="card-text"><strong>Location:</strong> <?= esc($job['location']); ?></p>
              <p class="card-text"><strong>Salary:</strong> 
                <?php
                  if ($job['minimum_salary']) {
                      echo '£' . number_format($job['minimum_salary']);
                      if ($job['maximum_salary']) {
                          echo ' - £' . number_format($job['maximum_salary']);
                      }
                  }
                ?>
              </p>
              <p class="card-text"><strong>Applications:</strong> <?= esc($job['applications_count']); ?></p>
              <p class="card-text"><strong>Deadline:</strong> <?= esc($job['expiration_date']); ?></p>
              <p class="card-text"><?= esc(word_limiter($job['job_description'], 50)); ?>...</p>
              <a href="<?= esc($job['job_url']); ?>" class="btn view-job-btn" target="_blank">View Job</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    

  </div>
</div>


