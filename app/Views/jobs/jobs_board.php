<div >


  <!-- Main Content -->
  <div class="container mt-3">

    <!-- Filter Section -->
    <div class="filter-bar row mt-4 mb-4">

      <!--Location Dropdown Filter -->
      <div class="col-md-3">
        <label for="locationFilter" class="form-label">Location</label>
        <select id="locationFilter" class="form-select">
          <option value="">Any Location</option>
          <!-- The rest of the options will be filled in by AJAX -->
        </select>
      </div>

      <!-- Job Title Dropdown Filter -->
      <div class="col-md-3">
        <label for="jobTitleFilter" class="form-label">Job Title</label>
        <select id="jobTitleFilter" class="form-select">
          <option value="">Any Job Title</option>
          <!-- The rest of the options will be filled in by AJAX -->
        </select>
      </div>

      <!-- Minimum Salary Dropdown Filter -->
      <div class="col-md-3">
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

      <!-- Sorting Drop Down Filter-->
      <div class="col-md-3">
        <label for="sortFilter" class="form-label">Sort By</label>
        <select id="sortFilter" class="form-select">
          <option value="most_recent">Most Recent</option>
          <option value="salary_asc">Salary (Low to High)</option>
          <option value="salary_desc">Salary (High to Low)</option>
          <option value="application_count_asc">Applications (Low to High)</option>
          <option value="application_count_desc">Applications (High to Low)</option>
          <option value="deadline_asc">Deadline (Oldest to Newest)</option>
          <option value="deadline_desc">Deadline (Newest to Oldest)</option>
        </select>
      </div>
    </div>

    <!-- Reset Filters Button -->
    <div class="col-12 text-end">
      <button id="reset-filters-btn" class="btn btn-secondary">
      <!-- Bootstrap Icon -->
      <i class="bi bi-arrow-clockwise me-2"></i> Reset Filters
      </button>
    </div>


    <!-- Job Listings Container to show jobs cards - initial load-->
    <div class="row d-flex align-items-stretch card-container-background" id="jobResultsContainer">
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
                  // If there's salary info, format and display it
                  if ($job['minimum_salary'] || $job['maximum_salary']) {
                    if ($job['minimum_salary']) {
                      echo '£' . number_format($job['minimum_salary']);
                    }
                    if ($job['maximum_salary']) {
                      echo ' - £' . number_format($job['maximum_salary']);
                    }
                  } else {
                    // If neither is present, show 'Not specified'
                    echo 'Not specified';
                  }
                ?>
              </p>
              <!-- Number of Applications -->
              <p class="card-text"><strong>Applications:</strong> <?= esc($job['applications_count']); ?></p>
              <!-- Deadline to apply -->
              <p class="card-text"><strong>Deadline:</strong> <?= esc($job['expiration_date']); ?></p>
              <!-- First 50 words of job description -->
              <p class="card-text mb-5"><?= esc(word_limiter($job['short_description'], 50, '.')); ?>...</p>
              <!-- View Job Button -->
              <a href="<?= base_url('jobs-board/job/' . $job['id']) ?>" class="btn view-job-btn">View Job</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<!-- JavaScript Section -->
<script>
  const baseUrl = "<?= base_url() ?>";

  document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded, fetching dropdown criteria');

    // References to the filter elements
    const locationFilter = document.getElementById('locationFilter');
    const jobTitleFilter = document.getElementById('jobTitleFilter');
    const salaryFilter = document.getElementById('salaryFilter');
    const sortBy = document.getElementById('sortFilter');
    

    // Reference to the job results container
    const resultsContainer = document.getElementById('jobResultsContainer');

    // Reference to the reset filters button
    const resetFiltersBtn = document.getElementById('reset-filters-btn');

    // Event Listner for the reset filters button
    resetFiltersBtn.addEventListener('click', () => {
      // Reset dropdowns in the UI
      locationFilter.selectedIndex = 0;
      jobTitleFilter.selectedIndex = 0;
      salaryFilter.selectedIndex = 0;
      sortBy.selectedIndex = 0;
      
      //
    });

    // Add listener to all view job buttons on intiial load
    document.querySelectorAll('.view-job-btn').forEach(button => {
      button.addEventListener('click', () => {
        console.log('View job clicked');
        triggerVibration();
      });
    });

    // Vibration function - Hardware API from browser
    function triggerVibration(duration = 100) {
      if (navigator.vibrate) {
        console.log(`Vibrating for ${duration}ms...`);
        navigator.vibrate(duration);
      }
    }

    // On page load, fetch unique dropdown options from the server (locations and job titles)
    fetch(`${baseUrl}jobs-board/getDropdownData`)
      .then(response => response.json()) // Convert response to JSON
      .then(data => {
        // Use function to populate each dropdown
        fillDropdown(locationFilter, data.locations, 'location', 'Any Location');
        fillDropdown(jobTitleFilter, data.titles, 'job_title', 'Any Job Title');
      })
      .catch(err => console.error('Dropdown fetch error:', err));

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

    // Trigger filtering when any dropdown changes
    [locationFilter, jobTitleFilter, salaryFilter, sortBy].forEach(select => {
      // Listen for changes in the dropdown menu
      select.addEventListener('change', () => {
        console.log('Filter changed');

        // If the browser supports vibration, vibrate when there's a change in filter values
        triggerVibration();

        // Create a form object
        const formData = new FormData();
        // Append the values of the dropdowns to the form
        formData.append('location', locationFilter.value);
        formData.append('title', jobTitleFilter.value);
        formData.append('minSalary', salaryFilter.value);
        formData.append('sortBy', sortBy.value);

        // AJAX post request, sending formData
        fetch(`${baseUrl}jobs-board/filter`, {
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
            // If salary given, format and display, otherwise show 'Not specified'
            jobs.forEach(job => {
              const div = document.createElement('div');
              div.className = 'col-md-4 col-sm-6 col-xsm-1 mb-3';
              div.innerHTML = `
                <div class="card h-100">
                  <div class="card-body">
                    <h5 class="card-title">${job.job_title}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">${job.employer_name}</h6>
                    <p class="card-text"><strong>Location:</strong> ${job.location}</p>
                    <p class="card-text"><strong>Salary:</strong> ${
                      (job.minimum_salary || job.maximum_salary)
                        ? `${job.minimum_salary ? '£' + Number(job.minimum_salary).toLocaleString() : ''}${job.maximum_salary ? ' - £' + Number(job.maximum_salary).toLocaleString() : ''}`
                        : 'Not specified'
                    }</p>
                    <p class="card-text"><strong>Applications:</strong> ${job.applications_count ?? 0}</p>
                    <p class="card-text"><strong>Deadline:</strong> ${job.expiration_date}</p>
                    <p class="card-text mb-5">${job.short_description.split(' ').slice(0, 50).join(' ')}...</p>
                    <a href="${baseUrl}jobs-board/job/${job.id}" class="btn view-job-btn">View Job</a>
                  </div>
                </div>
              `;
              // Insert cards to resultsContainer
              resultsContainer.appendChild(div);
            });


            // Add listener to all view job buttons after fetch
            document.querySelectorAll('.view-job-btn').forEach(button => {
              button.addEventListener('click', () => {
                console.log('View job clicked');
                triggerVibration();
              });
            });
          })
          // Catch errors
          .catch(error => console.error(' Fetch error:', error));
        });

        

    });
  });
</script>

