<?php
/********************************************
 * 프로젝트명   :   laravel_board
 * 디렉토리     :   Controllers
 * 파일명       :   UserController.php
 * 이력         :   v001 0526 MJ.Kang new
 ********************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    function login() {

        // * 로그 단계별로
        $arr['key'] = 'test'; 
        $arr['kim'] = 'park'; 
        Log::emergency('emergency', $arr);
        Log::alert('alert', $arr);
        Log::critical('critical', $arr);
        Log::error('error', $arr);
        Log::warning('warning', $arr);
        Log::notice('notice', $arr);
        Log::info('info', $arr);
        Log::debug('debug', $arr);

        return view('login');
    }

    function loginpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'email'    => 'required|email|max:100'
            ,'password' =>  'required|regex:/^(?=.*[a-zA-Z])(?=.*[\!\@\#\$\%\^\*\-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))) {
            // ? Log::debug($req->password." : ". $user->password); 이런식으로 디버그 넣으면 된다.
            $error = '아이디와 비밀번호를 확인해 주세요.';
            return redirect()->back()->with('error', $error);
        }

        // 유저 인증작업
        Auth::login($user);
        if (Auth::check()) {
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index')); // * intended 사용 시 전에 있던 데이터 날리고 redirect
            // * 유저 인증 작업 후 인증 하기 직전 페이지로 돌아갈 수 있도록 해주는 메서드. (그 페이지가 없다면 우리가 설정해둔 페이지로 이동하게 해 준다)
            // * 미들웨어 관련된 메서드...! 일단 유저 인증 작업(성공) 후 intended 넣는다고 생각하면 됨...!
        } else {
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
        }
    }

    function registration() {
        return view('registration');
    }
    function registrationpost(Request $req) {
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'password' =>  'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[\!\@\#\$\%\^\*\-])(?=.*[0-9]).{8,20}$/' // * same은 password와 passwardchk를 비교해준다
            // ? same:field 필드의 값이 주어진 field 의 값과 일치해야 합니다.
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        $user = User::create($data); // insert

        if (!$user) {
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.<br>잠시 후에 다시 회원가입을 시도해 주십시요';
            return redirect()
                ->route('users.registration')
                ->with('error', $error);
        }

        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.'); // $success가 session에 저장된다.
    }
    
    function logout() {
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    function withdraw() {
        $id = session('id'); // id 검증 필요.
        // var_dump(session());
        // return 'aa';
        $result = User::destroy($id); // 에러에 대한 처리 필요. 에러 핸들링 사용!
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    // 회원정보수정
    function edit() {
        $id = session('id');
        $user = User::findOrFail($id);
        // * 서버 리소스는 들지만 보안적으로는 더 나은 방법.
        // ? $user = User::findOrFail(Auth::User()->id);
        return view('useredit')->with('data', $user);
        // ! with는 view에 붙어있을 땐 view에 저장한다.
    }
    function editpost(Request $req) {
        $arrKey = []; // 수정할 항목을 담는 변수
        
        $baseUser = User::findOrFail(Auth::User()->id); // 기존 데이터 획득

        // 기존 비밀번호 체크
        if (!(Hash::check($req->passwordnow, $baseUser->password))) {
            return redirect()->back()->with('error', '기존 비밀번호를 확인해 주세요.');
            // ! with는 redirect에 붙어있을 땐 session에 저장한다.(통신 방식의 차이때문에 view와 redirect 각각 데이터가 저장되는곳이 다르다)
        }

        // 수정할 항목을 배열에 담는 처리
        if ($req->email !== $baseUser->email) {
            $arrKey[] = 'email';
        }
        if (isset($req->password)) {
            $arrKey[] = 'password';
        }

        // 유효성체크를 하는 모든 항목 리스트
        $chkList = [
            'email'        => 'required|unique:App\Models\User,email|email|max:100' // unique사용하면 중복되는 이메일이 있는 경우를 에러로 뺄 필요가 없다.
            ,'passwordnow'  => 'regex:/^(?=.*[a-zA-Z])(?=.*[\!\@\#\$\%\^\*\-])(?=.*[0-9]).{8,20}$/'
            ,'password'  => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[\!\@\#\$\%\^\*\-])(?=.*[0-9]).{8,20}$/'
        ];

        // 유효성 체크 할 항목 셋팅하는 처리
        $arrChk['passwordnow'] = $chkList['passwordnow'];
        foreach ($arrKey as $val) {
            $arrChk[$val] = $chkList[$val];
        }

        // return var_dump($arrChk);
        // 유효성 체크
        $req->validate($arrChk);

        // 수정할 데이터 셋팅
        foreach ($arrKey as $val) {
            if($val === 'password') {
                $val = Hash::make($req->val);
                continue;
            }
            $baseUser->$val = $req->$val;
        }

        $baseUser->save(); // update

        return redirect()->route('users.edit');


        // $id = ['id' => session('id')];
        // $req->request->add($id);
        // 이메일만 변경 할 경우
        // if (!empty($req->email)) {
        //     $req->validate([
        //         'id'            => 'required|integer'
        //         ,'email'        => 'required|unique:App\Models\User,email|email|max:100'
        //     ]);
        //     $user = User::where('id', $req->id)->first();
        //     $user->email = $req->email;
        //     $user->save();
        //     return redirect()->route('boards.index');
        // }
        // // 비밀번호만 변경 할 경우
        // if (!empty($req->password)) {
        //     $req->validate([
        //         'id'            => 'required|integer'
        //         ,'password'  => 'required'
        //         ,'passwordnew'  => 'required|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*[\!\@\#\$\%\^\*\-])(?=.*[0-9]).{8,20}$/'
        //     ]);
        //     $user = User::where('id', $req->id)->first();
        //     if (!$user || !(Hash::check($req->password, $user->password))) {
        //         $error = '비밀번호가 맞지 않습니다.';
        //         return redirect()->back()->with('error', $error);
        //     }
        //     $user->password = Hash::make($req->passwordnew);
        //     $user->save();
        //     return redirect()->route('boards.index');
        // }
        // $req->validate([
        //     'id'            => 'required|integer'
        //     ,'email'        => 'required|unique:App\Models\User,email|email|max:100' // unique사용하면 중복되는 이메일이 있는 경우를 에러로 뺄 필요가 없다.
        //     ,'password'  => 'required'
        //     ,'passwordnew'  => 'required|confirmed|regex:/^(?=.*[a-zA-Z])(?=.*[\!\@\#\$\%\^\*\-])(?=.*[0-9]).{8,20}$/'
        // ]);
        // $user = User::where('id', $req->id)->first();
        // if (!$user || !(Hash::check($req->password, $user->password))) {
        //     $error = '비밀번호가 맞지 않습니다.';
        //     return redirect()->back()->with('error', $error);
        // }
        // $user->email = $req->email;
        // $user->password = Hash::make($req->passwordnew);

        // $user->save();
        // return redirect()->route('boards.index');
    }
}
