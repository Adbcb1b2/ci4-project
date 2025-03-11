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
        $apiController = new APIController();
        $apiController->fetchJobsFromReed();
        // Fetch jobs from the database 
        $jobs = $jobsModel->findAll();

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





}
