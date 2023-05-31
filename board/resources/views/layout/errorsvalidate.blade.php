@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div>{{$error}}</div>
    @endforeach
@endif

@if(session()->has('error'))
    <div>{!!session('error')!!}</div>
@endif