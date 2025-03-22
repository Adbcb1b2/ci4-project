<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// http://localhost/ci4-project/public/
$routes->get('/', 'Home::index');

// http://localhost/ci4-project/public/jobs-board
$routes->get('jobs-board', 'JobsBoardController::index');

// http://localhost/ci4-project/public/api/fetch-jobs
$routes->get('api/fetch-jobs', 'ApiController::fetchJobsFromReed');

// Endpoint for coordinates of job location
// http://localhost/ci4-project/public/api/getJobCoordinates
$routes->get('api/getCoordinates', 'ApiController::getJobCoordinates');

// Endpoint for populating dropdown menus
$routes->get('/jobs-board/getDropdownData', 'JobsBoardController::getDropdownData');

// Endpoint for filtering jobs by dropdown criteria
$routes->post('/jobs-board/filter', 'JobsBoardController::filter');

// Route for viewing individual job, num for job ID and $1 to get first wildcard from url
$routes->get('/jobs-board/job/(:num)', 'JobsBoardController::viewJob/$1');