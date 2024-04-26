<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Login\LupaPasswordRepository;

class LupaPasswordController extends Controller
{
    public function __construct(LupaPasswordRepository $_LupaPasswordRepository)
    {
        $route_name = explode('.',\Route::currentRouteName());

        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->lupaPassword = $_LupaPasswordRepository;
    }


    public function index()
    {
        return view($this->route1.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function resetPassword(Request $request)
    {
        return view('Login.LupaPassword.form_reset_password');
    }

    public function resetPasswordProcess(Request $request)
    {
        return $this->lupaPassword->resetPasswordProcess($request);
    }

    public function sendEmailResetPass(Request $request)
    {
        return $this->lupaPassword->sendEmail($request->email);
    }

    public function checkEmail(Request $request)
    {
      return $this->lupaPassword->checkEmail($request);
    }
}
