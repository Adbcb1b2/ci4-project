<?php

namespace App\Controllers;

use App\Models\JobsModel;
use DateTime;

class ApiController extends BaseController
{
    /**
     * Fetch jobs from the reed API, filter by keywords: graduate-software-engineer
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

        // Create CURL object with options - first API call
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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

        // Loop through the first API call results
        foreach ($jobs->results as $job) {

            // Convert reed_creation_date to MYSQL format
            $reedCreationDate = null;
            // If there is a date
            if (!empty($job->date)) {
                // Create a DateTime object from the date string
                $dateObj = DateTime::createFromFormat('d/m/Y', $job->date);
                // If a valid DateTIme object was created, format it to MYSQL format
                $reedCreationDate = $dateObj ? $dateObj->format('Y-m-d') : null;
            }

            // Convert expiration_date to MYSQL format
            $expirationDate = null;
            // Create a DateTime object from the date string
            if (!empty($job->expirationDate)) {
                $dateObj = DateTime::createFromFormat('d/m/Y', $job->expirationDate);
                $expirationDate = $dateObj ? $dateObj->format('Y-m-d') : null;
            }

            // Store the short description from the first API call
            $shortDescription = strip_tags($job->jobDescription ?? '');

            // Fetch full job description from detailed endpoint using the job ID
            $detailsUrl = "https://www.reed.co.uk/api/1.0/jobs/{$job->jobId}";
            
            // Create CURL object with options - second API call
            $detailsCh = curl_init();
            curl_setopt($detailsCh, CURLOPT_URL, $detailsUrl);
            curl_setopt($detailsCh, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($detailsCh, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($detailsCh, CURLOPT_USERPWD, "$login:$password");

            // Make CURL call
            $detailsResponse = curl_exec($detailsCh);
            curl_close($detailsCh);

            // Decode the response into a JSON object
            $fullJob = json_decode($detailsResponse);
            // If the full description exists, store it, otherwise store null
            $fullDescription = $fullJob->jobDescription ?? null;

            // Create array to hold data for each record
            $jobData = [
                'reed_job_id'        => $job->jobId,
                'reed_creation_date' => $reedCreationDate,
                'job_title'          => $job->jobTitle,
                'employer_name'      => $job->employerName,
                'location'           => $job->locationName,
                'minimum_salary'     => $job->minimumSalary ?? null,
                'maximum_salary'     => $job->maximumSalary ?? null,
                'short_description'  => $shortDescription, // Short description from first API call
                'job_description'    => $fullDescription, // Full description from second API call
                'job_url'            => $job->jobUrl,
                'expiration_date'    => $expirationDate,
                'applications_count' => $job->applications ?? null,
            ];

            // Insert the job into the database
            $jobsModel->insertJob($jobData);

            // debugging
            // echo '<pre>'; print_r($jobData); echo '</pre>';
        }
    }

    /**
     * Summary of getJobCoordinates
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getJobCoordinates()
    {
        // Get the location from the url
        $location = $this->request->getGet('location');

        // If no location in the URL, return an error
        if(!$location){
            return $this->response->setJSON(['error' => 'No location in the URL']);
        }

        // API Key
        $apiKey = '4393e3c22d7c4668a3d40ecc62ba6c10';

        // URL for the API call
        $placeName = urlencode($location);
        $url = "https://api.opencagedata.com/geocode/v1/json?q={$placeName}&key={$apiKey}";

        // Initialise CURL object
        $ch = curl_init();

        // Set CURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        // Make the CURL call
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode response into JSON
        $data = json_decode($response, true);

        // Return cooridinates as JSON if found
        if (!empty($data['results'][0]['geometry'])) {
            // Return the coordinates of job location as JSON
            return $this->response->setJSON([
                'lat' => $data['results'][0]['geometry']['lat'],
                'lng' => $data['results'][0]['geometry']['lng']
            ]);
        }
        // If no coordinates found, return an error
        return $this->response->setJSON(['error' => 'No coordinates found']);


        
    }
}
