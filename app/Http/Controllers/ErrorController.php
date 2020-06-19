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
        Mail::to($email)->send(new ErrorMail($request)); 

        return "Poruka je poslana!";
    }
}
