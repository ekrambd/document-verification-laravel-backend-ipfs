<?php

namespace App\Http\Controllers;

use App\Repositories\Auth\AuthInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{   

	protected $auth;

	public function __construct(AuthInterface $auth)
	{
		$this->auth = $auth;
	}

    public function login(Request $request)
    {
    	$login = $this->auth->login($request);
    	return $login;
    }

    public function userDetails()
    {
    	$user = $this->auth->userDetails();
    	return $user;
    }

    public function logout()
    {
    	$logout = $this->auth->logout();
    	return $logout;
    }
}
