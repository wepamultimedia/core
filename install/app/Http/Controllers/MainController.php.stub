<?php

namespace App\Http\Controllers;


use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Wepa\Core\Http\Controllers\Frontend\InertiaController;
use Wepa\Core\Models\Site;


class MainController extends InertiaController
{
	public string $packageName = 'core';
	
	public function home()
	{
		$this->addSeo('home');
		
		return $this->render('Home');
	}
	
	public function sendContact(ContactRequest $request)
	{
		$site = Site::first();
		Mail::to($site->email)
			->send(new ContactMail($request));
	}
}
