<?php


namespace PICOExplorer\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class LocaleRequest
{

    public function index(Request $request)
    {
        $this->validateData($request);


    }

    public function validateData($request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => required | email | max:255 | unique:users',
		'password' => 'required | confirmed | min:6 |,
	 ]);
  }

}
