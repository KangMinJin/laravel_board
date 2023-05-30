<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
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
    <form action="{{route('boards.update', ['board' => $data->id])}}" method="POST">
        @csrf
        @method('put')
        <label for="title">제목 : </label>
        {{-- <input type="text" id="title" name="title" value="{{old('title') !== null ? old('title') : $data->title}}"> --}}
        <input type="text" id="title" name="title" value="{{count($errors) > 0 ? old('title') : $data->title}}">
        <br>
        <br>
        <label for="content">내용 : </label>
        {{-- <textarea id="content" name="content">{{old('content') !== null ? old('content') : $data->content}}</textarea> --}}
        <textarea id="content" name="content">{{count($errors) > 0 ? old('content') : $data->content}}</textarea>
        <br>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{route('boards.show', ['board' => $data->id])}}'">취소</button>
    </form>
</body>
</html>