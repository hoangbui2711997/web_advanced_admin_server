<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
//    public function login()
//    {
//        return view('layouts.app');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.app');
    }

//    public function showNotFoundPage()
//    {
//        return view('errors.404');
//    }

//    public function authUrl(Request $request)
//    {
//        $url = $request->input('url');
//        return redirect($url);
//    }
}
