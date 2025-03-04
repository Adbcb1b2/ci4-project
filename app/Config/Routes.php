<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// http://localhost/ci4-project/public/
//$routes->get('/', 'Home::index');

// http://localhost/ci4-project/public/jobs-board
$routes->get('jobs-board', 'JobsBoardController::index');
