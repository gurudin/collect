<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Upload;

class SiteController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.site');
        }
    }

    /**
     * Upload file.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        return (new Upload)->upload($request);
    }
}
