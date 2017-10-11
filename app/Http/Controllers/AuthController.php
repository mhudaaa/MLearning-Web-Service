<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Model\User;

class AuthController extends Controller{
    
    public function authCheck(Request $request){
    	$username = $request->username;
    	$password = $request->password;

    	// Get User
    	$user = User::where('username', $username)->get();

        if (count($user) > 0) {
        	$userPassword = $user[0]['password'];

        	// Check Password
    		if (password_verify($password, $userPassword)) {
    		    return response('Login Success', 200)->header('Content-Type', 'text/plain');
    		} else {
    		    return response('Invalid Password', 201)->header('Content-Type', 'text/plain');
    		}

        } else{
            return response('Invalid NIM', 202)->header('Content-Type', 'text/plain');
        }

    }

}
