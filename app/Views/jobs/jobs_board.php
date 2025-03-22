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

      <!-- Sorting Dropdown Filter-->
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

      <!-- Reset Filters Button -->
      <div class="col-12 text-end mt-3">
        <button id="reset-filters-btn" class="btn btn-secondary">
        <!-- Bootstrap Icon -->
        <i class="bi bi-arrow-clockwise me-2"></i> Reset Filters
        </button>
      </div>
      
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

  // Listener for when the DOM is fully loaded
  document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded, fetching dropdown criteria');

    // References to the filter elements
    const locationFilter = document.getElementById('locationFilter');
    const jobTitleFilter = document.getElementById('jobTitleFilter');
    const salaryFilter = document.getElementById('salaryFilter');
    const sortBy = document.getElementById('sortFilter');
    const resetFiltersBtn = document.getElementById('reset-filters-btn');

    // Reference to the job results container
    const resultsContainer = document.getElementById('jobResultsContainer');

    // Attach listener to initial View Job buttons on page load, need to attach agin after rendering new jobs
    attachViewButtonListeners();

    // Vibration function - Hardware API from browser. Default 100ms
    function triggerVibration(duration = 100) {
      if (navigator.vibrate) {
        console.log(`Vibrating for ${duration}ms...`);
        navigator.vibrate(duration);
      }
    }

    // Function with logic to populate dropdowns - for Location and Job Title
    function fillDropdown(dropdownMenu, items, key, defaultLabel) {
      // Clear the dropdown first, add the feault option
      dropdownMenu.innerHTML = `<option value="">${defaultLabel}</option>`;
      // Loop through the items, either location or job title, and add them to the dropdown
      items.forEach(item => {
        // If the item has a value
        if (item[key]) {
          // Create a dropdown option
          const option = document.createElement('option');
          // Get the value of the item, using the key
          option.value = item[key]; // To be used in the filter logic
          option.textContent = item[key]; // To be displayed in the dropdown UI
          // Add the option to the dropdown
          dropdownMenu.appendChild(option); 
        }
      });
    }

    // Function to create job cards dynamically and append them to the container, argument is the fetched jobs array
    function renderJobs(jobs) {
      // Clear the job cards Container
      resultsContainer.innerHTML = '';

      // If no jobs, dislay a message
      if (jobs.length === 0) {
        resultsContainer.innerHTML = '<p class="black-p">No jobs found.</p>';
        return;
      }

      // If jobs are found, loop through them, creating a card for each
      jobs.forEach(job => {
        // Create a card (a new div)
        const div = document.createElement('div');
        div.className = 'col-md-4 col-sm-6 col-xsm-1 mb-3'; // Styling for cards - 3 cards per row on medium screens, 2 on small, 1 on extra small
        // Set the inner HTML of the card (the new div)
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
        // Append the card to the results container
        resultsContainer.appendChild(div);
      });

      // Re-attach listeners after rendering dynamic job cards
      attachViewButtonListeners();
    }

    // Attach vibration to all view job buttons
    function attachViewButtonListeners() {
      document.querySelectorAll('.view-job-btn').forEach(button => {
        button.addEventListener('click', () => {
          console.log('View job clicked');
          triggerVibration();
        });
      });
    }

    // Function to fetch jobs with filter parameters
    function fetchFilteredJobs(filters = {}) {
      // Create a new form object
      const formData = new FormData();

      // Append data to form, if it exists, otherwise use the default value passed to the function
      formData.append('location', filters.location ?? locationFilter.value);
      formData.append('title', filters.title ?? jobTitleFilter.value);
      formData.append('minSalary', filters.minSalary ?? salaryFilter.value);
      formData.append('sortBy', filters.sortBy ?? sortBy.value);

      // Fetch the jobs with the form data
      fetch(`${baseUrl}jobs-board/filter`, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json()) // Parse the response as JSON
        .then(jobs => renderJobs(jobs)) // Call the render jobs function with the fetched, filtered jobs
        .then(() => console.log('Jobs fetched and rendered'))
        .catch(err => console.error('Fetch error:', err));
    }

    // Function to reset all dropdowns to default and fetch all jobs
    function resetFilters() {
      // Reset dropdowns to default values in the UI
      locationFilter.selectedIndex = 0;
      jobTitleFilter.selectedIndex = 0;
      salaryFilter.selectedIndex = 0;
      sortBy.selectedIndex = 0;
      triggerVibration();

      // Call the fetchFilteredJobs function with default values
      fetchFilteredJobs({
        location: '',
        title: '',
        minSalary: '0',
        sortBy: 'most_recent'
      });
    }

    // resetFiltersBtn click listener
    resetFiltersBtn.addEventListener('click', resetFilters);

    // 1. Fetch dropdown values on page load
    fetch(`${baseUrl}jobs-board/getDropdownData`)
      .then(response => response.json())
      .then(data => {
        // Fill the 2 dynamic dropdowns with the fetched data
        fillDropdown(locationFilter, data.locations, 'location', 'Any Location');
        fillDropdown(jobTitleFilter, data.titles, 'job_title', 'Any Job Title');
      })
      .then(() => console.log('Dropdown values fetched'))
      .catch(err => console.error('Dropdown fetch error:', err));

    // 2. Trigger filtering when any dropdown changes
    [locationFilter, jobTitleFilter, salaryFilter, sortBy].forEach(select => {
      select.addEventListener('change', () => {
        console.log('Filter changed');
        triggerVibration();
        fetchFilteredJobs();
      });
    });


  });
</script>


