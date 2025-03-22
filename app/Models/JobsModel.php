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
        'applications_count',
        'short_description'];

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
        return $this->insert($jobData); // Insert the job data into the database
    }

    /**
     * Returns the unique values of a column in the database, column name given by parameter 
     * @param mixed $column
     * @return mixed
     */
    public function getUniqueValues($column)
    {
        
        return $this->select($column)
                    ->distinct()
                    ->where($column. ' IS NOT NULL')
                    ->orderBy($column, 'ASC') // Order by ascending
                    ->findAll();

    }

    /**
     * Gets jobs from the database based on the filters applied
     * @param mixed $location
     * @param mixed $title
     * @param mixed $minSalary
     * @return array
     */
    public function getFilteredJobs($location = '', $title = '', $minSalary = 0, $sortBy='')
    {
        // To build complex query, step by step, as includes conditional logic
        $builder = $this->builder();

        // If the location isn't empty (i.e. has a filter selected), add it to the query
        if(!empty($location)){
            $builder->where('location', $location);
        }

        // If the job title isn't empty (i.e. has a filter selected), add it to the query
        if(!empty($title)){
            $builder->where('job_title', $title);
        }

        // If the minimum salary is greater than 0 (i.e. has a filter selected), add it to the query
        if($minSalary > 0){
            $builder->where('minimum_salary >=', $minSalary);
        }

            // Apply sorting
        switch ($sortBy) {
            case 'salary_asc':
                $builder->orderBy('minimum_salary', 'ASC');
                break;
            case 'salary_desc':
                $builder->orderBy('maximum_salary', 'DESC');
                break;
            case 'application_count_asc':
                $builder->orderBy('applications_count', 'ASC');
                break;
            case 'application_count_desc':
                $builder->orderBy('applications_count', 'DESC');
                break;
            case 'deadline_asc':
                $builder->orderBy('expiration_date', 'ASC');
                break;
            case 'deadline_desc':
                $builder->orderBy('expiration_date', 'DESC');
                break;
            case 'most_recent':
            default:
                $builder->orderBy('reed_creation_date', 'DESC');
                break;
    }


        return $builder->get()->getResultArray();

    }

}