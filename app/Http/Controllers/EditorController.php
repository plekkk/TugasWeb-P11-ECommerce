<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class EditorController extends Controller
{
    public function index(): View
    {
        return view('dashboards.editor');
    }
}
