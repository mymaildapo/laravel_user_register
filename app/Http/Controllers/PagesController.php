<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        $title ="Welcome to Dapo Oloruntola Page"; 
    return view('pages.index')->with('titleAnyname', $title); 

    }

    public function about()
    {
        $title ="About Dapo "; 
       
        return view('pages.about')->with('titleAnyname', $title); 
    }

    public function services()
    {   
        $data = array(
            'title' => "Dapo Services",
            'servicesAnyname' => ['Programming','Data', 'Having Fun']
        );
        return view('pages.services')->with($data);
    }

    public function welcome()
    {   
        
        return view('welcome');
    }

}