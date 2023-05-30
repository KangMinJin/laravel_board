<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    {{-- <div>
        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        @endif
    </div> --}}
    @include('layout.errorsvalidate')

    <form action="{{route('boards.store')}}" method="POST">
        @csrf
        <label for="title">제목 : </label>
        <input type="text" id="title" name="title" value="{{old('title')}}"> {{-- old()를 쓰면 기존 입력값이 남는다! input.value 안 써도 됨... --}}
        <br>
        <br>
        <label for="content">내용 : </label>
        <textarea id="content" name="content">{{old('content')}}</textarea>
        <br>
        <button type="submit">작성</button>
    </form>
</body>
</html>