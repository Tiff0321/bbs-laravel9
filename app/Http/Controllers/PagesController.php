<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function root(): Factory|View|Application
    {
        return view('pages.root');
    }
}
