<div>
  <!-- Navigation Bar --> 
  <nav class="navbar bg-body-tertiary custom-background">
    <div class="container-fluid">
      <a class="navbar-brand banner-title px-3">Jobs Board for Graduate Developers</a>

      <!-- Search Bar Form -->
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
    <div class="filter-bar row mt-4 mb-4">

      <!--Location Filter -->
      <div class="col-md-4">
        <label for="locationFilter" class="form-label">Location</label>
        <select id="locationFilter" class="form-select">
          <option value="">Any Location</option>
          <!-- The rest of the options will be filled in by AJAX -->
        </select>
      </div>

      <!-- Job Title Filter -->
      <div class="col-md-4">
        <label for="jobTitleFilter" class="form-label">Job Title</label>
        <select id="jobTitleFilter" class="form-select">
          <option value="">Any </option>
          <!-- The rest of the options will be filled in by AJAX -->
        </select>
      </div>

      <!-- Minimum Salary Filter -->
      <div class="col-md-4">
        <label for="salaryFilter" class="form-label">Minimum Salary</label>
        <select id="salaryFilter" class="form-select">
          <option value="0">Any Salary</option>
          <option value="20000">£20,000+</option>
          <option value="30000">£30,000+</option>
          <option value="40000">£40,000+</option>
          <option value="50000">£50,000+</option>
          <option value="60000">£60,000+</option>
          <option value="70000">£70,000+</option>
        </select>
      </div>
    </div>

    <!-- Filter Button AJAX function is called when this is clicked-->
    <div class="row mt-3 mb-5">
      <div class="col-md-12 text-end">
        <button id="filterBtn" class="btn filter-btn">Filter Results</button>
        
      </div>
    </div>

    <!-- Job Listings Container to show jobs cards-->
    <div class="row d-flex align-items-stretch" id="jobResultsContainer">
      <?php foreach ($jobs as $job): ?>
        <div class="col-md-4 col-sm-6 col-xsm-1 mb-3">
          <div class="card h-100">
            <!-- Card Contents -->
            <div class="card-body">
              <!-- Job Title -->
              <h5 class="card-title"><?= esc($job['job_title']); ?></h5>
              <!-- Employer Name -->
              <h6 class="card-subtitle mb-2 text-muted"><?= esc($job['employer_name']); ?></h6>
              <!-- Location -->
              <p class="card-text"><strong>Location:</strong> <?= esc($job['location']); ?></p>
              <!-- Salary -->
              <p class="card-text"><strong>Salary:</strong>
                <?php
                  // If job has a minimum salary, show it
                  if ($job['minimum_salary']) {
                    echo '£' . number_format($job['minimum_salary']);
                    // If job has a maximum salary, show it
                  }
                  // If job has a maximum salary, show it
                  if ($job['maximum_salary']) {
                      echo ' - £' . number_format($job['maximum_salary']);
                    }
                  
                ?>
              </p>
              <!-- Number of Applications -->
              <p class="card-text"><strong>Applications:</strong> <?= esc($job['applications_count']); ?></p>
              <!-- Deadline to apply -->
              <p class="card-text"><strong>Deadline:</strong> <?= esc($job['expiration_date']); ?></p>
              <!-- First 50 words of job description -->
              <p class="card-text mb-5"><?= esc(word_limiter($job['job_description'], 50, '.')); ?>...</p> <!-- Show first 50 words of job description using word_limiter helper -->
              <!-- View Job Button -->
              <a href="#" class="btn view-job-btn">View Job</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<script>
// Listener for pafe to fully load
document.addEventListener('DOMContentLoaded', () => {
  console.log('DOM fully loaded, fetching dropdown criteria');

    // Always add vibration listener to View Job buttons present on page load
    document.querySelectorAll('.view-job-btn').forEach(button => {
    button.addEventListener('click', () => {
      console.log('View job clicked!');
      if (navigator.vibrate) {
        console.log('Vibrating...');
        navigator.vibrate(100);
      }
    });
  });
  
  // Get references to DOM elements
  const locationFilter = document.getElementById('locationFilter');
  const jobTitleFilter = document.getElementById('jobTitleFilter');
  const salaryFilter = document.getElementById('salaryFilter');
  const resultsContainer = document.getElementById('jobResultsContainer');
  const filterBtn = document.getElementById('filterBtn');

  // AJAX get request
  fetch('/ci4-project/public/jobs-board/getDropdownData')
    // Convert response to JSON
    .then(response => response.json())
    // When JSON is received, populate dropdowns
    .then(data => {
      // Call fillDropdown function to populate dropdown for location
      fillDropdown(locationFilter, data.locations, 'location', 'Any Location');
      // Call fillDropdown function to populate dropdown for job description
      fillDropdown(jobTitleFilter, data.titles, 'job_title', 'Any Job Title');
    })
    // Catch errors
    .catch(error => console.error('Dropdown fetch error:', error));

  // Function with logic to populate dropdowns
  function fillDropdown(dropdownMenu, items, key, defaultLabel) {
    // Clear dropdown, make the first value the 'defaultLabel' parameter
    dropdownMenu.innerHTML = `<option value="">${defaultLabel}</option>`;
    // Loop through the items returned from the AJAX call
    items.forEach(item => {
      if (item[key]) {
        // Create an option element for each item
        const option = document.createElement('option');
        // Set the value and text content of the option element
        option.value = item[key];
        option.textContent = item[key];
        // Append the option element to the dropdown
        dropdownMenu.appendChild(option);
      }
    });
  }

  // Event listener for filter button
  filterBtn.addEventListener('click', () => {
    console.log('Filter button clicked');

    // Vibrate when filter button is clicked
    if (navigator.vibrate) {
      console.log('Vibrating...');
      navigator.vibrate(100); // vibrate for 100ms on filter click
    }

    // Create a form object
    const formData = new FormData();
    // Append the values of the dropdowns to the form
    formData.append('location', locationFilter.value);
    formData.append('title', jobTitleFilter.value);
    formData.append('minSalary', salaryFilter.value);

    // AJAX post request, sending formData
    fetch('/ci4-project/public/jobs-board/filter', {
      method: 'POST',
      body: formData
    })
    // Convert the server's response to JSON
    .then(response => response.json())
    // When JSON is received, populate the results container
    .then(jobs => {
      // Clear the results container
      resultsContainer.innerHTML = '';
      // If no jobs are returned, display 'No jobs found'
      if (jobs.length === 0) {
        resultsContainer.innerHTML = '<p>No jobs found.</p>';
        return;
      }

      // If jobs are returned, dynamically create a card for each job
      jobs.forEach(job => {
        const div = document.createElement('div');
        div.className = 'col-md-4 col-sm-6 col-xsm-1 mb-3';
        div.innerHTML = `
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">${job.job_title}</h5>
              <h6 class="card-subtitle mb-2 text-muted">${job.employer_name}</h6>
              <p class="card-text"><strong>Location:</strong> ${job.location}</p>
              <p class="card-text"><strong>Salary:</strong> £${Number(job.minimum_salary).toLocaleString()} - £${Number(job.maximum_salary).toLocaleString()}</p>
              <p class="card-text"><strong>Applications:</strong> ${job.applications_count ?? 0}</p>
              <p class="card-text"><strong>Deadline:</strong> ${job.expiration_date}</p>
              <p class="card-text mb-5">${job.job_description.split(' ').slice(0, 50).join(' ')}...</p>
              <a href="#" class="btn view-job-btn">View Job</a>
            </div>
          </div>
        `;
        // Insert cards to resultsContainer
        resultsContainer.appendChild(div);
      });

        // Add listener to all view job buttons
      document.querySelectorAll('.view-job-btn').forEach(button => {
      button.addEventListener('click', () => {
        console.log('View job clicked!');
        // If the browser supports vibration
        if (navigator.vibrate) {
          // Vibrate for 100ms
          console.log('Vibrating...');
          navigator.vibrate(100); 
        }
        });
      });
    })
    // Catch errors
    .catch(error => console.error('Filtered Fetch error:', error));
  });



});
</script>