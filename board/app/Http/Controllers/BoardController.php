<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ---------------
        // * 쿼리 빌더
        // ---------------

        // * SELECT
        // $result = DB::select('SELECT * FROM categories');
        // $no = 5;
        // $result = DB::select(
        //     'SELECT * FROM categories WHERE no = :no'
        //     , ['no' => $no]
        // );
        // * 이런식으로도 prepare 사용 가능
        // $result = DB::select(
            //     'SELECT * FROM categories WHERE no = ? and no = ?'
            //     , [$no, 7]
            // );
    
        // $input = ['4', '7' ,'8']; // categorise의 no 컬럼
        // 게시글 번호, 게시글 제목, 카테고리명을 출력하시오. (게시글 번호로 오름차순 정렬 후 상위 5개만)
        // $result = DB::select(
        //     'select b.bno, b.btitle, c.name
        //         from categories c inner join boards b on c.no = b.category
        //         where b.category in (?, ?, ?)
        //         order by b.bno limit 5'
        //     , $input
        //     );
        // ? inner join을 이렇게도 쓴다! (위와 동일한 join문 - 하지만 속도는 달라질 수 있다..)
        // ! 그리고 내용이 많은 테이블이 주 테이블이 되면 속도 저하 있을 수 있다 !
        // $result = DB::select(
        //     'select b.bno, b.btitle, c.name
        //         from categories c , boards b
        //         where  c.no = b.category
        //             and c.no in (?, ?, ?)
        //         order by b.bno
        //         limit 5', $input);

        // * INSERT
        // $inp = ['btitle' => '냠냐미', 'category' => '2', 'bcontent' => '냠냐미는 냠냠', 'created_at' =>''];
        // $result = DB::insert(
        //     'insert into boards (category, btitle, bcontent, created_at, updated_at)
        //         values (:category, :btitle, :bcontent, NOW(), NOW())'
        //         , ['category' => '2'
        //         , 'btitle' => '냠냐미2'
        //         , 'bcontent' => '냠냐미는 냠냠2'
        //     ]
        // );

        // * UPDATE
        // 마지막 게시글의 제목 : test, 내용 : testtest 으로 변경하라.

        // $result = DB::update(
        //     'update boards set btitle = :btitle, bcontent = :bcontent, updated_at = NOW()
        //         where bno = :bno'
        //         , ['btitle' => 'test'
        //             ,'bcontent' => 'testtest'
        //             ,'bno' => 10002
        //     ]);

        // * DELETE
        // $result = DB::delete('delete from boards where bno = :bno', ['bno' => 10002]);

        // ---------------
        // * 쿼리 빌더 체이닝 (리턴값이 object)
        // ---------------
        // SELECT
        $no = '5';
        // $result = DB::table('categories')->where('no', '=', $no)->get(); // TODO : 삭제 예정 (dd나 var_dump 사용하면 적어둔다! 나중에 완료 하기 전 TODO를 검색해서 마지막으로 처리해야 할 일 찾아서 수정)
        // ? 마지막에 get();이 아니고 dd(); 라고 하면
        // ? "select * from `categories` where `no` = ?" // vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3850
        // ? array:1 [  ▼ // vendor\laravel\framework\src\Illuminate\Database\Query\Builder.php:3850
        //?     0 => "5"
        //?     ] 이런식으로 쿼리문과 prepared 셋팅 출력된다!
        // * dd 메소드는 컬렉션의 아이템을 덤프하여 표시하고 스크립트를 종료한다.(return까지 안 감!)
        
        // SELECT * FROM categories WHERE no = ? or no = ?
        $no1 = '5';
        $no2 = '8';
        // $result = DB::table('categories')->where('no', $no1)->orWhere('no', $no2)->get(); // '=' 는 생략 가능!
        
        // SELECT * FROM categories WHERE no = ? and no = ?
        // $result = DB::table('categories')->where('no', $no1)->where('no', $no2)->get();
        
        // SELECT * FROM categories WHERE no in (?, ?)
        // $result = DB::table('categories')->whereIn('no', [$no1, $no2])->get(); // * whereIn은 여러 값이 들어가므로 두번째 인수가 배열로 들어간다!
        
        // SELECT * FROM categories WHERE no NOT in (?, ?)
        // $result = DB::table('categories')->whereNotIn('no', [$no1, $no2])->get();

        // select id, no, name from catergories
        // $result = DB::table('categories')
        //     ->select('id', 'no', 'name')
        //     ->dd(); // * 가독성 위해서 체이닝마다 개행

        // select id, no, name from catergories order by name desc
        // $result = DB::table('categories')
        //     ->select('id', 'no', 'name')
        //     ->orderBy('name', 'desc')
        //     ->dd();

        // ! 주의해서 사용 whereRaw()
        // $result = DB::table('categories')->whereRaw('no = '.$no1)->get(); // ! 보안적으로 취약
        // DB::raw 를 사용하는 대신, 쿼리의 다양한 부분을 raw 표현식으로 대체하기 위해서 다음의 메소드를 사용할 수 있습니다.
        // ! 라라벨은 raw 표현식을 사용하는 쿼리가 SQL 인젝션 취약성으로부터 보호된다는 것을 보장할 수 없습니다.

        // first() : Limit 1과 비슷한 작동, 실패 시 false 리턴
        // $result = DB::table('boards')->orderBy('bno', 'desc')->first();
        
        // firstOrFail() : first() 와 같은 동작을 하나, 실패시 결과가 예외 발생(엘로퀀트 모델 객체에서만 사용 가능)
        // $result = DB::table('boards')->orderBy('bno', 'desc')->firstOrFail();
        // Board::orderBy('bno', 'desc')->firstOrFail(); // Model에 바로 붙여서 사용. 쿼리빌더에선 사용 불가능!

        // * 집계 메소드
        // $result = DB::table('boards')->count(); // 결과의 레코드 수를 반환!
        // $result = DB::table('boards')->max('bno'); // 해당 컬럼의 최대값을 반환!

        // * Transaction
        // DB::beginTransaction(); // 트랜잭션 시작
        // DB::rollback(); // 롤백
        // DB::commit(); // 커밋

        // 체이닝 메소드로 카테고리가 활성화 되어있는 게시글의 카테고리 별 게시글 갯수를 출력하라.
        // 카테고리 번호, 카테고리 명, 갯수
        $result = DB::table('categories as c')
            ->join('boards as b', 'c.no', 'b.category') // * join에서도 '=' 생략 가능!
            ->select('c.no', 'c.name', DB::raw('count(*) as total')) // * MariaDB에서 사용 할 함수는 raw를 써서 문자열로 보낸다!(laravel 함수와는 다르므로)
            ->where('c.active_flg', '1')
            ->groupBy('c.no', 'c.name') // * groupBy는 select하는 컬럼들을 모두 묶어줘야하기 때문에 컬럼명을 다 적어준다!
            // ! 컬럼명 하나만 적는것은 MariaDB에서만 가능한 기법! 다른 DB들도 모두 적어줘야한다!
            ->get();

        return var_dump($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
