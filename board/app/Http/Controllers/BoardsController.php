<?php
/********************************************
 * 프로젝트명   :   laravel_board
 * 디렉토리     :   Controllers
 * 파일명       :   BoardsController.php
 * 이력         :   v001 0526 MJ.Kang new
 *                  v002 0530 MJ.Kang 유효성 체크 추가
 ********************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        // 로그인 체크
        if (auth()->guest()) {
            return redirect()->route('users.login');
        }
        
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   // v002 update start
        // return view('write');
        return view('write');
        // v002 update end
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {

        // *** v002 add start
        $req->validate([
            'title'     => 'required|between:3,30' // 제목과 내용은 필수이므로 'required', 3~30글자 제한하고 싶으니 'between:3,30' 연결은 '|'로
            ,'content'  => 'required|max:1000' // 최대 1000글자까지로 제한하고 싶으니 'max:1000'
        ]);
        // ? validate()는 조건에 부합하지 않으면 전 페이지로 돌아간다.(redirect)
        // ? validate()는 에러가 일어나면 view에 $errors를 던져준다.
        // *** v002 add end

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
        // *** v002 add start
        // TODO $req안에 뭐가 들었는지 확인!
        // var_dump($req);
        //     return 'aa';

        // ? ID를 리퀘스트 객체에 merge
        $arr = ['id' => $id]; // ! Request안에는 id가 없으므로 merge를 통해 Request안에 id를 넣어준다!
        // * $req->merge($arr); 아래 코드가 속도가 더 빠르므로 아래 코드 사용!
        $req->request->add($arr);

        $req->validate([
            'id'        => 'required|integer'
            ,'title'    => 'required|between:3,30'
            ,'content'  => 'required|max:1000'
        ]);

        // 유효성 검사 방법 2 - 에러가 났을 때 바로 return하지 않고 임의로 다르게 하고 싶을 때 사용?
        // $validator = Validator::make(
        //     $req->only('id', 'title', 'content')
        //     ,[
        //         'id'        => 'required|integer'
        //         ,'title'    => 'required|between:3,30'
        //         ,'content'  => 'required|max:1000'
        //     ]
        // );

        // if ($validator->fails()) {
        //     return redirect()
        //         ->back() // 이전 페이지로 돌아가는 메소드
        //         ->withErrors($validator)
        //         ->withInput($req->only('title', 'content')); // 받은 Request객체를 세션에 등록하고 가져오는 메소드
        // }

        // *** v002 add end
        $boards = Boards::findOrFail($id);

        $boards->title = $req->title;
        $boards->content = $req->content;
        
        $boards->save();
        // $boards = Boards::findOrFail($id);
        return redirect('/boards/'.$id); // ? URL에 할당되는 view가 없고, 다른 URL로 이동할때 redirect(update에 할당되는 view없고, show로 이동해야 하기 때문에 redirect)
        // return redirect()->route('boards.show',['board' => $id]); // * route 쓰는 방법
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
