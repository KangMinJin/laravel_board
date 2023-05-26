<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Boards;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $boards = new Boards([ // ? 새로운 레코드 insert하는건 DB에 그 데이터가 없어서 select자체가 불가능하므로 insert는 new로 선언 해준다!
            'title'     => $req->input('title')
            ,'content'  => $req->input('content')
        ]);

        $boards->save();

        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id); // $id값으로 기존 값 가져온다
        $boards->hits++; // 조회수 증가
        $boards->save(); // update처리
        // save()는 insert를 먼저 시도하고 실패하면 update를 한다!

        return view('detail')->with('data', Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boards = Boards::findOrFail($id);
        return view('edit')->with('data', $boards);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $boards = Boards::findOrFail($id);

        $boards->title = $req->title;
        $boards->content = $req->content;
        
        $boards->save();
        // $boards = Boards::findOrFail($id);
        // return redirect('/boards/'.$id); // ? URL에 할당되는 view가 없고, 다른 URL로 이동할때 redirect(update에 할당되는 view없고, show로 이동해야 하기 때문에 redirect)
        return redirect()->route('boards.show',['board' => $id]); // * route 쓰는 방법
        // ! view는 include와 마찬가지이므로 redirect해서 URL을 변경해줘야한다!(URL이 바뀌어야하면 무조건 redirect!)
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Boards::findOrFail($id)->delete();
        Boards::destroy($id);
        // $boards->deleted_at = now();
        // $boards->save();
        return redirect('/boards'); // ? 마지막에 보여줘야하는 페이지가 다르면 redirect
    }
}
