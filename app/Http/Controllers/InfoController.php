<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class InfoController
{
    public function index()
    {
        return Inertia::render('info/Index');
    }
}
