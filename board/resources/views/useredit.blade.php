@extends('layout.layout')

@section('title', 'UserEdit')

@section('contents')
    <h1>회원정보수정</h1>
    {{-- session에 $success가 있으면 메세지 출력, 없으면 빈 문자열. --}}
    <div class="errMsg">
    {!! session()->has('success') ? session('success') : ""!!}
    </div>
    @include('layout.errorsvalidate')
    <form action="{{route('users.edit.post')}}" method="post">
        @csrf
        <span>이름 : {{$data->name}}</span>
        <br>
        <label for="email">이메일 : </label>
        <input type="text" id="email" name="email" value="{{$data->email}}">
        <br>
        <label for="passwordnow">기존 비밀번호 : </label>
        <input type="password" id="passwordnow" name="passwordnow">
        <br>
        <label for="password">새로운 비밀번호 : </label>
        <input type="password" id="password" name="password">
        <br>
        <label for="password_confirmation">비밀번호 재입력 : </label>
        <input type="password" id="password_confirmation" name="password_confirmation">
        <br>
        <br>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{route('boards.index')}}'">취소</button>
    </form>
    <a href="{{route('users.withdraw')}}">회원탈퇴</a>
@endsection