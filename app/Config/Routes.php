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

// Route for populating dropdown menus
$routes->get('/jobs-board/getDropdownData', 'JobsBoardController::getDropdownData');

// Route for filtering jobs by dropdown criteria
$routes->post('/jobs-board/filter', 'JobsBoardController::filter');