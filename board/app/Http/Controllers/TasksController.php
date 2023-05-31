<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     * 리소스의 목록 페이지
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'Tasks.index';
    }

    /**
     * Show the form for creating a new resource.
     * 리소스 생성을 위한 입력 폼 페이지
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'Tasks.create';
    }

    /**
     * Store a newly created resource in storage.
     * 리소스 생성 처리
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $arrOnly = $request->only(['id', 'pw']);
        // return 'ID : '.$arrOnly['id'].'<br>'.'PW : '.$arrOnly['pw'];
        return 'ID : '.$request->id.'<br>'.'PW : '.$request->pw; // $request는 객체이기 때문에 이렇게도 값을 가져올 수 있다!
        // laravel에서 추천하는 방식은 get이나 위와같이 $request->id 이런 방식
    }

    /**
     * Display the specified resource.
     * 리소스 조회 페이지
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'Tasks.show : '.$id;
    }

    /**
     * Show the form for editing the specified resource.
     * 리소스 수정 폼 페이지
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return 'Tasks.edit : '.$id;
    }

    /**
     * Update the specified resource in storage.
     * 리소스 수정 처리
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        var_dump($request); // ? request에 무엇이 있는지 확인용
        $arrAll = $request->all(); // * 사용자가 전달하는 모든 데이터 '배열'로 획득
        $arrOnly = $request->only(['id', 'name']); // * 사용자가 전달한 데이터 중 해당하는 데이터만 '배열'로 획득
        $oneGet = $request->get('pw'); // * 사용자가 전달한 데이터 중에 해당하는 데이터의 값'만' 획득
        var_dump($arrAll);
        var_dump($arrOnly);
        var_dump($oneGet);
        return 'Tasks.update : '.$id;
    }

    /**
     * Remove the specified resource from storage.
     * 리소스 삭제 처리
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return 'Tasks.destroy : '.$id;
    }
}
