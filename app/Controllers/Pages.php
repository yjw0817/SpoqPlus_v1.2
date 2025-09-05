<?php
namespace App\Controllers;

use CodeIgniter\Controller AS CI_Con;
use CodeIgniter\Exceptions AS CI_Ex;

class Pages extends CI_Con
{
	public function index()
	{
		return view('welcome_message');
	}
	
	public function view($page = 'home')
	{
		if ( ! is_file(APPPATH . '/views/pages/' . $page.'.php'))
		{
			throw new CI_Ex\PageNotFoundException($page);
		}
		
		$data['title'] = ucfirst($page);
		
		echo view('templates/header', $data);
		echo view('pages/'.$page	, $data);
		echo view('templates/footer', $data);
	}
	
	
}