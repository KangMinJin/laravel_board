@extends('layout.layout')

@section('title', 'Registration')

@section('contents')
    <h1>회원가입</h1>
    @include('layout.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">이름 : </label>
        <input type="text" id="name" name="name">
        <br>
        <label for="email">이메일 : </label>
        <input type="text" id="email" name="email">
        <br>
        <label for="password">비밀번호 : </label>
        <input type="password" id="password" name="password">
        <br>
        <label for="passwordchk">비밀번호 : </label>
        <input type="password" id="passwordchk" name="passwordchk">
        <br>
        <br>
        <button type="submit">회원가입</button>
        <button type="button" onclick="location.href='{{route('users.login')}}'">취소</button>
    </form> 
@endsection