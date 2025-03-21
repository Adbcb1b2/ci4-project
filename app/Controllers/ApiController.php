<?php

 namespace App\Controllers;

 use App\Models\JobsModel;

 use DateTime;

 class ApiController extends BaseController
 {
    /**
     * Fetch jobs from the reed API, filter by keywords: graduate-software
     * @return void
     */
    public function fetchJobsFromReed()
    {
        // Initialise JobModel object
        $jobsModel = new JobsModel();

        // Set connection details
        $login = '2ee0efe8-5279-42de-80f8-6e7af96a0830';
        $password = '';
        $url = 'https://www.reed.co.uk/api/1.0/search?keywords=graduate-software-engineer';
    
        // Create CURL object with options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        
        // Make CURL call
        $response = curl_exec($ch);
        // Convert response into a JSON object
        $jobs = json_decode($response);
        curl_close($ch);

        // Delete all existing jobs in the database and re-set autoincrement - do not want to store expired jobs
        $jobsModel->truncate();

        // Loop through the result
        foreach ($jobs->results as $job) {
            // Convert reed_creation_date to MYSQL format
            $reedCreationDate = null;
            if (!empty($job->date)) {
                $dateObj = DateTime::createFromFormat('d/m/Y', $job->date);
                $reedCreationDate = $dateObj ? $dateObj->format('Y-m-d') : null;
            }

            // Convert expiration_date to MYSQL format
            $expirationDate = null;
            if (!empty($job->expirationDate)) {
                $dateObj = DateTime::createFromFormat('d/m/Y', $job->expirationDate);
                $expirationDate = $dateObj ? $dateObj->format('Y-m-d') : null;
            }

            // Create array to hold data for each record
            $jobData = [
                'reed_job_id' => $job->jobId,
                'reed_creation_date' => $reedCreationDate,
                'job_title' => $job->jobTitle,
                'employer_name' => $job->employerName,
                'location' => $job->locationName,
                'minimum_salary' => $job->minimumSalary ?? null,
                'maximum_salary' => $job->maximumSalary ?? null,
                'job_description' => $job->jobDescription ?? null,
                'job_url' => $job->jobUrl,
                'expiration_date' => $expirationDate, 
                'applications_count' => $job->applications ?? null,
            ];

            // Insert the job into the database
            $jobsModel->insertJob($jobData);

            // Print repsone to the browser
            // echo '<pre>';
            // print_r($jobs);
            // echo '</pre>';

    
        }
    }
 }