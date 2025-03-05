<?php

namespace App\Models;

use CodeIgniter\Model;

class JobsModel extends Model
{
    // Set table name
    protected $table = 'jobs';

    // Set the primary key
    protected $primaryKey = 'id';

    // Define the fields that can be inserted/updated.
    // id and created_at needn't be included as they are auto values
    protected $allowedFields = ['reed_job_id', 
        'reed_creation_date', 
        'job_title', 
        'employer_name', 
        'location', 
        'minimum_salary', 
        'maximum_salary', 
        'job_description', 
        'job_url', 
        'expiration_date', 
        'applications_count'];

    /**
     * Retrieves all jobs from the database
     * @return array
     */
    public function getJobs()
    {
        return $this-> findAll(); // Return all data from the database
    }

    /**
     * Inserts a single job into the database
     * @param mixed $jobData
     * @return bool True if successful, False if unsuccessful
     */
    public function insertJob($jobData)
    {
        return $this->insert($jobData); // Need to build a 
    }

}