<div class="container mt-4">

    <!-- Buttons Container -->
    <div class="d-flex justify-content-between mb-3">
        <a href="<?= base_url('jobs-board') ?>" class="btn custom-button">Back to Jobs Board</a>
        <a href="<?= esc($job['job_url']) ?>" target="_blank" class="btn custom-button">Apply on Reed</a>
    </div>

    <!-- Job Title-->
    <h1><?= esc($job['job_title'])?></h1>
    <!-- Employer -->
    <h2 class="text-muted"><?=esc($job['employer_name'])?></h2>
    <hr>
    <!-- Job Location -->
    <p><strong>Location:</strong> <?= esc($job['location']) ?></p>
    <!-- Salary -->
    <p><strong>Salary:</strong>
        <?php
        // If minimum salary is set, echo to display
        if ($job['minimum_salary']) {
            echo '£' . number_format($job['minimum_salary']);
            // If maximum salary is set, echo to display
            if ($job['maximum_salary']) {
                echo ' - £' . number_format($job['maximum_salary']);
            }
        // If no salary is set, display 'Not specified'
        } else {
            echo 'Not specified';
        }
        ?>
    </p>
    
    <!-- Application Count -->
    <p><strong>Applications:</strong> <?= esc($job['applications_count']) ?? '0' ?></p>
    <!-- Application Deadline -->
    <p><strong>Application Deadline:</strong> <?= esc($job['expiration_date']) ?></p>
    <!-- Date job posted on Reed -->
    <p><strong>Job Posted:</strong> <?= esc($job['reed_creation_date']) ?></p>

    <!-- User's distance from job, will be inserted dynamically, depending on user's location -->
    <div class="d-flex align-items-center justify-content-between mt-3">
        <div>
            <strong>Distance from your location:</strong>
            <!-- Show spinner until approx distance retrieved -->
            <span id="loadingSpinner" class="spinner-border spinner-border-sm text-primary"></span>
            <span id="distanceValue" class="ms-2"></span>
        </div>

        <!-- Toggle Switch for KM/Mi -->
        <div class="d-flex align-items-center">
            <span class="me-2">Km</span>
            <div class="form-check form-switch m-0">
                <input class="form-check-input" type="checkbox" id="unitToggle">
            </div>
            <span class="ms-2">Mi</span>
        </div>
    </div>

    <hr>

    <!-- Full job description, keep actual formatting returned from reed so that HTML is rendered-->
    <?= htmlspecialchars_decode($job['job_description']) ?>
    <hr>
    <!-- Button to job application on Reed -->
    <a href="<?= esc($job['job_url']) ?>" target="_blank" class="btn custom-button">Apply on Reed</a>
</div>

<!-- JavaScript Section -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const distanceValue = document.getElementById('distanceValue'); // Span to show calculated value
        const loadingSpinner = document.getElementById('loadingSpinner'); // Spinner element
        const unitToggle = document.getElementById('unitToggle'); // Toggle switch
        const jobLocation = <?= json_encode($job['location']) ?>; // Get the job location
        const apiUrl = "<?= base_url('api/getCoordinates') ?>?location=" + encodeURIComponent(jobLocation); // Append location to API URL

        let currentUnit = 'km'; // Default unit
        let distanceInKm = 0; // Default distance

        // Attempt to use browser's Geolocation API to get user's latitude and longitude, requires permission
        if (navigator.geolocation) {
            // Create user's position object
            navigator.geolocation.getCurrentPosition(userPosition => {
                // Extract user's latitude and longitude from the position object
                const userLat = userPosition.coords.latitude;
                const userLng = userPosition.coords.longitude;

                // Fetch request to get the coordinates of the job
                fetch(apiUrl)
                    // Convert the response to JSON
                    .then(response => response.json())
                    // Extract the latitude and longitude of the job
                    .then(data => {
                        // If the job location has coordinates
                        if (data.lat && data.lng) {
                            const jobLat = data.lat;
                            const jobLng = data.lng;

                            // Call the calculateDistance function to get the distance between the user and the job using the Haversine formula
                            distanceInKm = calculateDistance(userLat, userLng, jobLat, jobLng);

                            // Update UI with the distance in km
                            distanceValue.innerText = `Approx. ${Math.round(distanceInKm)} km`;
                        // If the job location doesn't have coordinates    
                        } else {
                            distanceValue.innerHTML = `<em>Unable to calculate approximate distance</em>`;
                        }

                        // Hide spinner once distance retrieved
                        loadingSpinner.style.display = 'none';
                    })
                    .catch(err => {
                        console.error("API error:", err);
                        distanceValue.innerHTML = `<em>Unable to calculate approximate distance</em>`;
                        // Hide spinner if unable to retrieve distance
                        loadingSpinner.style.display = 'none';
                    });
            // If getting user's location fails 
            }, () => {
                distanceValue.innerHTML = `<em>Unable to calculate approximate distance</em>`;
                // Hide spinner if unable to retrieve distance
                loadingSpinner.style.display = 'none';
            });
        // If geolocation not supported by browser    
        } else {
            distanceValue.innerHTML = `<em>Geolocation not supported by browser</em>`;
            // Hide spinner if unable to retrieve distance
            loadingSpinner.style.display = 'none';
        }

        // Function to convert degrees to radians
        function toRadians(degrees) {
            return degrees * (Math.PI / 180);
        }

        // Haversine formula to calculate distance between two coordinate points
        function calculateDistance(lat1, lon1, lat2, lon2) {
            // Convert latitude and longitude to radians
            lat1 = toRadians(lat1);
            lat2 = toRadians(lat2);
            lon1 = toRadians(lon1);
            lon2 = toRadians(lon2);

            // Calculate differences between latitudes and longitudes
            const dLat = lat2 - lat1;
            const dLon = lon2 - lon1;

            // Haversine formula
            const a = Math.pow(Math.sin(dLat / 2), 2) +
                      Math.cos(lat1) * Math.cos(lat2) *
                      Math.pow(Math.sin(dLon / 2), 2);
            const c = 2 * Math.asin(Math.sqrt(a));
            const R = 6371; // Radius of Earth in km
            return R * c;
        }

        // Add event listener to the toggle button
        unitToggle.addEventListener('change', () => {
            if (unitToggle.checked) {
                // Convert to miles (1 km = 0.621371 miles)
                const miles = distanceInKm * 0.621371;
                distanceValue.innerText = `Approx. ${Math.round(miles)} miles`;
                currentUnit = 'miles';
            } else {
                // Switch back to kilometres
                distanceValue.innerText = `Approx. ${Math.round(distanceInKm)} km`;
                currentUnit = 'km';
            }
        });
    });
</script>
