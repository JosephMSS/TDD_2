<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $repositories=Repository::latest()->get();
        return view('welcome',compact('repositories'));
    }
}
