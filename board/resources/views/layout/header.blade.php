<a href="{{route('boards.index')}}">
    <h2>header</h2>
</a>

{{-- 로그인 상태 --}}
@auth
    <div><a href="{{route('users.logout')}}">로그아웃</a></div>
    <div><a href="{{route('users.edit')}}">회원정보수정</a></div>
@endauth

{{-- 비로그인 상태 --}}
@guest
    <div><a href="{{route('users.login')}}">로그인</a></div>
@endguest
<hr>