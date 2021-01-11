@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $json_cat = json_encode($cat,JSON_PRETTY_PRINT);
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header">
            <div class="row justify-content-between">
                <div class="col-auto mr-auto">
                    <h5 >{{$cat->pattern}}</h5>
                </div>
                @if ($auths->id == $cat->user_id)
                    <div class="col-auto">
                        <a href="/discover/delete/{{$cat->uuid}}"  class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('messages.CatInfo DeleteSentence')}}')">{{ __('messages.Delete') }}</a>
                        <a href="/discover/modify/{{$cat->uuid}}" class="btn btn-outline-success btn-sm">{{ __('messages.Edit') }}</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 d-flex align-items-center">
                {{-- <img class="card-img-top img-thumbnail" alt="{{$cat->images}}の画像" src="/uploads/cats_imgs/{{ $cat->images }}"> --}}
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        {{-- @if($images)
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="https://machineko.s3-ap-northeast-1.amazonaws.com/cats_imgs/mapicon.png" alt="no image">
                            </div>
                        @else --}}
                            @foreach ($images as $key => $image)
                                @if ($key == 0)
                                    <div class="carousel-item active mx-auto">
                                        <img class="d-block img-fluid" src="https://machineko.s3.ap-northeast-1.amazonaws.com/cats_imgs/{{$image['filename']}}" alt="{{$image['filename']}}の画像">
                                    </div>
                                @else
                                    <div class="carousel-item mx-auto">
                                        <img class="d-block img-fluid" src="https://machineko.s3.ap-northeast-1.amazonaws.com/cats_imgs/{{$image['filename']}}" alt="{{$image['filename']}}の画像">
                                    </div>
                                @endif
                            @endforeach
                        {{-- @endif --}}
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
                    <span>{{ __('messages.Date of discovery') }}</span><span class="utc" id="discovertime" data-name="{{$cat->created_at}}"></span>
                    <p class="card-text">{{ __('messages.Location of discovery') }}{{$cat->locate}}</p>
                    <div id="map" class="col" style="height: 15rem;"></div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="/home" >≪{{ __('messages.Return to Map') }}</a>
        </div>
    </div>

    <form action="/discover/comment/{{ $cat->uuid }}" method="post">
        @csrf
        
        <div class="form-group">
            <label for="message">{{ __('messages.Comments') }}</label>
            <textarea class="form-control" id="message" name="message"></textarea>
        </div>
        <div class="text-right">
            <input class="btn btn-primary" type="submit" name="new" value="{{ __('messages.Post') }}">
        </div>
    </form>

    @foreach ($comments as $comment)
        <form action="/discover/msgedit/{{ $cat->uuid }}/{{ $comment->id }}" method="post">
            @csrf
            <div class="card mt-3">
                <div class="card-header">
                    @if (!optional($comment->user)->name)
                        <span class="text-muted">Unknown</span>
                    @else
                        {{ optional($comment->user)->name }}
                    @endif
                </div>
                <div class="card-body">
                    <p class="card-text" id="p_{{$comment->id}}">{!! nl2br(e($comment->message)) !!}</p>
                    <div id="area_{{$comment->id}}"></div>
                    
                    <small class="text-muted"><p class="card-text utc" id="utc_{{$comment->id}}" data-name="{{$comment->created_at}}"></p></small>
                </div>
                @if ($auths->id == $comment->user_id)
                    <div class="card-footer text-right">
                        <a href="/discover/msgdelete/{{$cat->uuid}}/{{$comment->id}}" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('messages.Comment DeleteSentence')}}')">{{ __('messages.Delete') }}</a>
                        <input type="button" class="btn btn-outline-success btn-sm" id="bt_{{$comment->id}}" onclick="commentedit({{$comment->id}})" value="{{ __('messages.Edit') }}">
                    </div>
                @endif
            </div>
        </form>
    @endforeach

</div>

<script src='{{ asset("js/detail.js") }}'></script>
<script> var cat = @json($cat);</script>
<script src='{{ asset("js/gmap.js") }}'></script>
<script src='https://maps.googleapis.com/maps/api/js?key={{ config("services.google-map.apikey") }}&callback=placeMap' defer></script>

@endsection