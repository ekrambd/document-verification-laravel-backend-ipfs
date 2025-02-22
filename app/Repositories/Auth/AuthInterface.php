<?php
 namespace App\Repositories\Auth;

 interface AuthInterface
 {
 	public function login($request);
 	public function userDetails();
 	public function logout();
 }