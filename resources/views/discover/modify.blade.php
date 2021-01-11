@extends('layouts.app')

@section('content')
<div class="container">
    @php
        $json_cat = json_encode($cat,JSON_PRETTY_PRINT);
    @endphp
    
    <form action="/discover/update/{{$cat->uuid}}" method="post" enctype="multipart/form-data">
        @csrf
        <h1>{{ __('messages.Post detailed infomation') }}</h1>
        <div class="form-group">
            <label for="nickname">{{ __('messages.Contributor') }}</label>
            <input type="text" readonly class="form-control" id="nickname" name="nickname" value="{{$auths->name}}">
        </div>
        <div class="form-group">
            <label for="pattern">{{ __('messages.Cat Pattern') }}</label>
            <input class="form-control" type="text" id="pattern" name="pattern" value="{{$cat->pattern}}">
        </div>
        <div class="form-group">
            <label for="locate">{{ __('messages.Discovery location') }}</label>
            <input class="form-control" type="text" id="locate" name="locate" onchange="storeMap()" value="{{$cat->locate}}">
        </div>
        <!--map-->
        <div class="row justify-content-center mt-3 mb-3">
            <div id="map" class="col-11" style="height: 15rem;">
            </div>
        </div>

        <div class="row">
            <div class="input-group col-6 mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="catsphoto">{{ __('messages.Latitude') }}</span>
                </div>
                <input type="text" readonly class="form-control" id="lat" name="lat" value="{{$cat->Lat}}">
            </div>
            <div class="input-group col-6 mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="catsphoto">{{ __('messages.Longitude') }}</span>
                </div>
                <input type="text" readonly class="form-control" id="lng" name="lng" value="{{$cat->Lng}}">
            </div>
        </div>
        <!--↑↑↑-->

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="catsphoto">{{ __('messages.Image Files') }}</span>
            </div>
            <div class="custom-file">
                <input type="file" id="cats_img" name="cats_imgs[]" class="custom-file-input" aria-describedby="catsphoto" multiple>
                <label class="custom-file-label" for="cats_img" id="cats_img_filename" data-browse="参照">{{ __('messages.Choose File') }}</label>
            </div>
        </div>

        <div class="row justify-content-between mb-3">
            <div class="col-auto mr-auto">
                <input class="btn btn-primary" type="submit" value="{{ __('messages.Post') }}">
            </div>
            <div class="col-auto">
                <a href="/home" class="btn btn-outline-secondary">{{ __('messages.Return to Map') }}</a>
            </div>
        </div>
    </form>
</div>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        document.getElementById("cats_img").addEventListener("change", function(e){
            var fileList = document.getElementById("cats_img").files; // console.log('fileList:',fileList);
            if(fileList.length==0){
                document.getElementById("cats_img_filename").innerHTML = "ファイル選択...";
            }else{
                var list = "";
                for(var i=0; i<fileList.length; i++){
                    if(list==""){
                        list += fileList[i].name;
                    }else{
                        list += ", " + fileList[i].name;
                    }
                }; // console.log('filename:',list);
                document.getElementById("cats_img_filename").innerHTML = list;
            }
        });
    });
</script>
<script> var cat = @json($cat);</script>
<script src='{{ asset("js/gmap.js") }}'></script>
<script src='https://maps.googleapis.com/maps/api/js?key={{ config("services.google-map.apikey") }}&callback=placeMap' defer></script>

@endsection