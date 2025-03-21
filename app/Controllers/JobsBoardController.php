<?php

namespace App\Controllers;

use App\Models\JobsModel;
use CodeIgniter\Controller;

class JobsBoardController extends BaseController
{
    /**
     * Displays the job board page by loading the necessary views.
     * 
     * @return string string Returns the combined HTML content of the header, job board, and footer views.
     */
    public function index(): string
    {
        // Create an instance of the JobsModel
        $jobsModel = new JobsModel();

        // Fetch jobs from the database 
        $jobs = $jobsModel->getJobs();

        //Check if jobs data is outdated (older than 1 hour)
       if($this->isDataOutdated($jobs)) {
            // Fetch new jobs from Reed API, update the DB
            $apiController = new APIController();
            $apiController->fetchJobsFromReed();

            // Get updated jobs from the database after inserting new ones
            $jobs = $jobsModel->getJobs();
      }
        
        //Return the view with the jobs data
        return view('templates/header') . view('jobs/jobs_board', ['jobs' => $jobs]) . view('templates/footer');
    }

    /**
     * Returns True if data in database is older than an hour
     * @param mixed $jobs 
     * @return bool 
     */
    private function isDataOutdated($jobs){
        // If there are no jobs, consider it outdated (data needs to be fetched)
        if (empty($jobs)) {
            return true;
        }

        // If there are jobs, check the created_at field to see if it is within the last hour
        $firstJob = $jobs[0];
        $latestCreatedAtDate = new \DateTime($firstJob['created_at']);
        $currentDate = new \DateTime();
        $timeDifference = $latestCreatedAtDate->diff($currentDate);

        // Return True if the data is older than 1 hour
        return $timeDifference-> h >= 1;
    }

    /**
     * Gets data via AJAX for the dropdown menus on the job board page
     * @return mixed
     */
    public function getDropdownData()
    {   
        // Instance of the JobsModel
        $jobsModel = new JobsModel();

        // List of locations using the getUniqueValues method in the jobs model to get unique values from location column
        $locations = $jobsModel->getUniqueValues('location');
        // List of job titles using the getUniqueValues method in the jobs model to get unique values from job-title column
        $titles = $jobsModel->getUniqueValues('job_title');

        // Returns the result as a JSON object to be used in the AJAX call
        return $this->response->setJSON(['locations' => $locations, 'titles' => $titles]);

    }

    /**
     * Filters jobs via AJAX based on the dropdown criteria
     * @return mixed
     */
    public function filter()
    {
        // Receive incoming data from the dropdowns
        $location = $this->request->getPost('location');
        $title = $this->request->getPost('title');
        $minSalary = $this->request->getPost('minSalary');

        // Instance of the JobsModel
        $jobsModel = new JobsModel();

        // Call method from the model with the data recieve from the dropdowns
        $jobs = $jobsModel->getFilteredJobs($location, $title, $minSalary);

        // Return the response in JSON format, to be used in the AJAX call
        return $this->response->setJSON($jobs);


    }

    /**
     * Displays the job details page by loading the necessary views with the job data
     * @param mixed $id
     * @return string
     */
    public function viewJob($id){
        // Create an instance of the JobsModel
        $jobsModel = new JobsModel(); 

        // Find the job with the given ID
        $job = $jobsModel->find($id); 
        
        // Job data to send to the view
        $data['job'] = $job; 

        // Extract title to be the page title
        $data['title'] = $job['job_title']; 

        // Return the views, with the job data
        return view('templates/header', $data) . view('jobs/job_details', $data) . view('templates/footer');
                

    }





}
