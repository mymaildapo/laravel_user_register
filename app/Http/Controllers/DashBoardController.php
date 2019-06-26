<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class DashBoardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); //block everything in dash.blade.php is going to be block if user hasnt signed in
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_idAnyname = auth()->user()->id; 
        $uuuser = User::find($user_idAnyname); 
        return view('dash')->with('postAKA', $uuuser->postsAnyname);
    }
}
