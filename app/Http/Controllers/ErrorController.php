<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ErrorMail;
use Illuminate\Support\Facades\Mail;

class ErrorController extends Controller
{
    public function errorMessage (Request $request) 
    {
        $email = 'jelena.juras@duplico.hr';
        $url = $_SERVER['REQUEST_URI'];
        Mail::to($email)->send(new ErrorMail( $request, $url)); 
   
        return "Došlo je do problema, poslana je mail obavijest administratoru!";
    }
}
