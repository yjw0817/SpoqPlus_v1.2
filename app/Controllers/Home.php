<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		echo "<script>location.href='/login';</script>";
		//return view('welcome_message');
	}
}
