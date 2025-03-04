<?php

namespace App\Controllers;

class JobsBoardController extends BaseController
{
    public function index(): string
    {
        return view('templates/header') . view('jobs/jobs_board') . view('templates/footer');
    }
}
