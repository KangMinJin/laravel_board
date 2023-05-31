<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

class ApiListController extends Controller
{
    function getlist($id) {
        $board = Boards::find($id);
        return response()->json([$board], 200);
    }
    function postlist(Request $req) {
        // 유효성 체크 필요
        $boards = new Boards([
            'title'     => $req->title
            ,'content'  => $req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title');
        // return response
        return $arr;
    }

    function putlist(Request $req, $id) {
        $boards = Boards::find($id);
        $boards->title = $req->title;
        $boards->content = $req->content;
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id', 'title');
        // return response
        return $arr;
    }

    function deletelist($id) {
        Boards::destroy($id);
        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        return $arr;
    }
}
