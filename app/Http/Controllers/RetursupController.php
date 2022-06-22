<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetursupController extends Controller
{
    public function index(){

        $dataretur = DB::table("retursup")->get();
        
        return view("retursupplier");
    }

    
}
