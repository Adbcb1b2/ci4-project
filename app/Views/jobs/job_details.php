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
    <p id="jobDistance" class="mt-3">
        <strong>Distance from your location:</strong>
        <!-- Show spinner until approx distance retrieved -->
        <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
    </p>

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
        const jobDistance = document.getElementById('jobDistance');
        const jobLocation = <?= json_encode($job['location']) ?>; // Get the job location
        const apiUrl = "<?= base_url('api/getCoordinates') ?>?location=" + encodeURIComponent(jobLocation); // Append the location to the API URL

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

                            // Call the calculateDistance function to get the distance between the user and the job using the Haversine formula https://www.geeksforgeeks.org/program-distance-two-points-earth/
                            const distance = calculateDistance(userLat, userLng, jobLat, jobLng);
                            // Update UI with the distance
                            jobDistance.innerHTML = `<strong>Distance from your location:</strong> Approx. ${distance.toFixed(0)} km`;
                        // If the job location doesnt have coordinates    
                        } else {
                            // Update UI with error message
                            jobDistance.innerHTML = `<strong>Distance from your location:</strong> <em>Unable to calculate approximate distance</em>`;
                        }
                    })
                    
                    .catch(err => {
                        console.error("API error:", err);
                        // Update UI with error message 
                        jobDistance.innerHTML = `<strong>Distance from your location:</strong> <em>Unable to calculate approximate distance</em>`;
                    });
            // If getting user's location fail due to denied permissions
            }, () => {
                jobDistance.innerHTML = `<strong>Distance from your location:</strong> <em>Geolocation permission denied</em>`;
            });
        // If geolocation not supported by browser    
        } else {
            jobDistance.innerHTML = `<strong>Distance from your location:</strong> <em>Geolocation not supported by browser</em>`;
        }

        // Function to convert degrees to radians
        function toRadians(degrees) {
            return degrees * (Math.PI / 180);
        }

        // Haversine formula to calculate distance between two points coorindates
        function calculateDistance(lat1, lon1, lat2, lon2) {
            // Convert latitude and longitude to radians with toRadians function
            lat1 = toRadians(lat1);
            lat2 = toRadians(lat2);
            lon1 = toRadians(lon1);
            lon2 = toRadians(lon2);

            // Calculate the differences in latitude and longitude
            const dLat = lat2 - lat1;
            const dLon = lon2 - lon1;

            // The Haversine formula
            const a = Math.pow(Math.sin(dLat / 2), 2) +
                    Math.cos(lat1) * Math.cos(lat2) *
                    Math.pow(Math.sin(dLon / 2), 2);

            const c = 2 * Math.asin(Math.sqrt(a));

            // Step 4: Approx radius of earth in km
            const R = 6371;

            // Calculate final distance
            return R * c;
        }
    });
</script>
