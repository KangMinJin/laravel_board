@extends('layout.layout')

@section('title', 'Login')

@section('contents')
    <h1>로그인</h1>
    {{-- <div>
    {{isset($success) ? $success : ""}}
    </div> --}}
    @include('layout.errorsvalidate')
    <form action="{{route('users.login.post')}}" method="post">
        @csrf
        <label for="email">이메일 : </label>
        <input type="text" id="email" name="email">
        <br>
        <label for="password">비밀번호 : </label>
        <input type="password" id="password" name="password">
        <br>
        <br>
        <button type="submit">로그인</button>
        <button type="button" onclick="location.href='{{route('users.registration')}}'">회원가입</button>
    </form> 
@endsection