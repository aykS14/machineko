@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $json_cat = json_encode($cat,JSON_PRETTY_PRINT);
    @endphp
    <div class="card mb-3">
        <div class="card-header">
            <div class="row justify-content-between">
                <div class="col-auto mr-auto">
                    <h5 >{{$cat->pattern}}</h5>
                </div>
                @if ($auths->id == $cat->user_id)
                    <div class="col-auto">
                        <a href="/discover/delete/{{$cat->uuid}}" class="btn btn-outline-danger btn-sm">削除</a>
                        <a href="" class="btn btn-outline-success btn-sm">編集</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 d-flex align-items-center">
                {{-- <img class="card-img-top img-thumbnail" alt="{{$cat->images}}の画像" src="/uploads/cats_imgs/{{ $cat->images }}"> --}}
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($cat_imgs as $key => $cat_img)
                            @if ($key == 0)
                                <div class="carousel-item active">
                                    <img class="d-block w-100" src="{{$cat_img}}" alt="{{$cat_img}}の画像">
                                </div>
                            @else
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="{{$cat_img}}" alt="{{$cat_img}}の画像">
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-body">
                    <span>発見日時：</span><span class="utc" id="discovertime" data-name="{{$cat->created_at}}"></span>
                    <p class="card-text">発見場所：{{$cat->locate}}</p>
                    <div id="map" class="col" style="height: 15rem;"></div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="/home" >≪マップへ戻る</a>
        </div>
    </div>
    <form action="/discover/comment/{{ $cat->uuid }}" method="post">
        @csrf
        <div class="form-group">
            <label for="message">コメント</label>
            <textarea class="form-control" id="message" name="message"></textarea>
        </div>
        <div>
            <input class="btn btn-primary" type="submit" value="投稿">
        </div>
    </form>

    @foreach ($comments as $comment)
        <div class="card mt-3">
            <div class="card-header">
                {{ $comment->user->name }}
            </div>
            <div class="card-body">
                <p class="card-text">{{$comment->message}}</p>
                <small class="text-muted"><p class="card-text utc" id="utc_{{$comment->id}}" data-name="{{$comment->updated_at}}"></p></small>
            </div>
            @if ($auths->id == $comment->user_id)
                <div class="card-footer text-right">
                    <a href="/discover/msgdelete/{{$cat->uuid}}/{{$comment->id}}" class="btn btn-outline-danger btn-sm">削除</a>
                    <a href="" class="btn btn-outline-success btn-sm">編集</a>
                </div>
            @endif
        </div>
    @endforeach

</div>

<script> var cat = @json($cat);</script>
<script src='{{ asset("js/result.js") }}'></script>
<script src='https://maps.googleapis.com/maps/api/js?key={{ config("services.google-map.apikey") }}&callback=placeMap' defer></script>

<script>
    window.onload = function () {
        //指定したクラスの要素をcsに入れる
        var cs=document.getElementsByClassName("utc");console.log('cs:',cs);

        //csの中からIDの情報をループで取り出す 
        for (var i=0; i< cs.length;i++){ 
            idd=cs[i].getAttribute("id");console.log('idd:',idd);
            var utctime = document.getElementById(idd).dataset.name; console.log('utctime:',utctime);
            var utc = new Date(utctime);console.log('UTC:',utc);
            var offset = utc.getTimezoneOffset();console.log('offset:',offset);
            var hours =  parseInt(offset / -60);
            var minutes = (offset % 60) * -1;
            utc.setHours(utc.getHours() + hours,utc.getMinutes() + minutes);
            var tztime = utc.toLocaleString().slice(0,-3);console.log('tztime:',tztime);

            document.getElementById(idd).innerHTML = tztime;
        }
    }
</script>

@endsection