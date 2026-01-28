<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class WidgetController extends Controller
{
    public function index(): View
    {
        return view('widget.index');
    }
}
