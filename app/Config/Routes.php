<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// http://localhost/ci4-project/public/jobs-board
$routes->get('jobs-board', 'JobsBoard::index');
