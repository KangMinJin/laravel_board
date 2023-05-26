<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>
    <form action="{{route('boards.update', ['board' => $data->id])}}" method="POST">
        @csrf
        @method('put')
        <label for="title">제목 : </label>
        <input type="text" id="title" name="title" value="{{$data->title}}">
        <br>
        <br>
        <label for="content">내용 : </label>
        <textarea id="content" name="content">{{$data->content}}</textarea>
        <br>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{route('boards.show', ['board' => $data->id])}}'">취소</button>
    </form>
</body>
</html>