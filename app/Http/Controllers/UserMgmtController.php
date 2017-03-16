<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserMgmtController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

      /**
     * Show the user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user-mgmt');
    }
}
