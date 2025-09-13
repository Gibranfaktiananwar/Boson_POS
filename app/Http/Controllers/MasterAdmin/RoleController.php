<?php

namespace App\Http\Controllers\masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('role.index');
    }
}
