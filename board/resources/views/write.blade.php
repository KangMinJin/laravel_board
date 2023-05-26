<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    <form action="{{route('boards.store')}}" method="POST">
        @csrf
        <label for="title">제목 : </label>
        <input type="text" id="title" name="title">
        <br>
        <br>
        <label for="content">내용 : </label>
        <textarea id="content" name="content"></textarea>
        <br>
        <button type="submit">작성</button>
    </form>
</body>
</html>