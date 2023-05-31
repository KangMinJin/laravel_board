<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BladeController extends Controller
{
    function index() {
        $arr = [
            'name'      => '김미현'
            ,'gender'   => '여자'
            ,'birthday' => '2008-10-23'
            ,'addr'     => '구미'
            ,'tel'      => '010-1234-5678'
        ];

        $arr2 =[];
        return view('blade')->with('data', $arr)->with('data2', $arr2);
    }
}
