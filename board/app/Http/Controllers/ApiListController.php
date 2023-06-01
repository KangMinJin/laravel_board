<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Boards;

class ApiListController extends Controller
{
    // TODO 모든 메소드에 유효성 체크 넣기!
    function getlist($id) {
        $arrData = [
            'code'  => '0'
            ,'msg'  => ''
        ];
        // $id = new Request($id);
        $data = ['id' => $id];
        $validator = Validator::make($data, ['id' => 'required|integer']);
        if ($validator->fails()) {
            // $response = ['error' => '글 조회에 실패했습니다.'];
            // return response()->json($response, 200);
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all();
        } else {
            $boards = Boards::find($id);
            if($boards) {
                return response()->json($boards, 200);
            } else {
                $arrData['code'] = 'E02';
                $arrData['msg'] = 'Already Deleted';
            }
        };
        return $arrData;
        // if ($boards===null) {
        //     $response = ['error' => '삭제된 게시글입니다.'];
        //     return response()->json($response, 200);
        // }

        // return $boards;
    }
    function postlist(Request $req) {
        // 유효성 체크 필요
        // $req->validate([
        //     'title'     => 'required|between:3,30'
        //     ,'content'  => 'required|max:1000'
        // ]);

        $arrData = [
            'code'      => '0'
            ,'msg'      => ''
        ];

        $data = $req->only('title', 'content');

        $validator = Validator::make($data, [
            'title'     => 'required|between:3,30'
            ,'content'  => 'required|max:1000'
        ]);
        
        if ($validator->fails()) {
            // $response = ['error' => '글 작성에 실패했습니다.'];
            // return response()->json($response, 200);
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all();
        } else {
            $boards = new Boards([
                'title'     => $req->title
                ,'content'  => $req->content
            ]);
            $boards->save();
            $arrData['code'] = '0';
            $arrData['msg'] = '작성 완료';
        };

        // return response
        return $arrData;
    }

    function putlist(Request $req, $id) {
        // ? 결과가 담길 배열
        $arrData = [
            'code'      => '0'
            ,'msg'      => ''
        ];
        
        $data = $req->only('title', 'content');
        $data['id'] = $id;

        // * 유효성 검사 - json으로 결과를 받아야 하기 때문에 validate(exception일어나면 에러페이지로 넘어감)말고 validator사용
        $validator = Validator::make($data, [
            'id'        => 'required|integer'
            ,'title'    => 'required|between:3,30'
            ,'content'  => 'required|max:1000'
        ]);

        if ($validator->fails()) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all();
        } else {
            // * 업데이트 처리
            $boards = Boards::find($id);
            $boards->title = $req->title;
            $boards->content = $req->content;
            $boards->save();
            $arrData['code'] = '0'; // ? 결과 담길 배열에 이미 '0'으로 선언되어 있지만 만약을 위해서 다시 선언해준다.
            $arrData['msg'] = 'Success';
            // 만약을 위해서 여기에 try catch 넣기도 함.
        };
        
        return $arrData; // ? laravel은 api통신을 할 때 값이 배열로 돌아오는건 자동으로 json형식으로 변환을 해준다!
    }

    function deletelist($id) {
        $arrData = [
            'code'  => '0'
            ,'msg'  => ''
        ];
        $data = ['id' => $id];
        $validator = Validator::make($data, ['id' => 'required|integer']);
        if ($validator->fails()) {
            // $response = ['error' => '글 삭제에 실패했습니다.'];
            // return response()->json($response, 200);
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all();

        } else {
            $boards = Boards::find($id);
            // * 이미 지워진 게시글도 삭제처리를 하기에 바로 destroy하지 말고 find로 찾아서(softDelete된 게시물은 찾지 않으므로(null로 돌아옴)) if문으로 걸러준다.
            if ($boards) {
                $boards->delete();
                $arrData['code'] = '0';
                $arrData['msg'] = 'Success';
            } else {
                $arrData['code'] = 'E02';
                $arrData['msg'] = 'Already Deleted';
            }
        };
        return $arrData;
    }
}
