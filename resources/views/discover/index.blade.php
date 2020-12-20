@extends('layouts.app')

@section('content')
<div class="container">

    <form action="discover/store" method="post" enctype="multipart/form-data">
        @csrf
        <h1>まちねこ情報登録</h1>
        <div class="form-group">
            <label for="nickname">投稿者</label>
                <input type="text" readonly class="form-control" id="nickname" name="nickname" value={{$auths->name}}>
        </div>
        <div class="form-group">
            <label for="pattern">猫さんの柄</label>
                <input class="form-control" type="text" id="pattern" name="pattern">
        </div>
        <div class="form-group">
            <label for="locate">猫さんの発見場所</label>
                <input class="form-control" type="text" id="locate" name="locate" onchange="storeMap()">
        </div>
        <div class="row justify-content-center mt-3 mb-3">
            <div id="map" class="col-8" style="height: 15rem;">
            </div>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="catsphoto">猫さん画像</span>
            </div>
            <div class="custom-file">
                <input type="file" id="cats_img" name="cats_imgs[]" class="custom-file-input" aria-describedby="catsphoto" multiple>
                <label class="custom-file-label" for="cats_img" data-browse="参照">ファイル選択...</label>
            </div>
        </div>
        <div>
            <input class="btn btn-primary" type="submit" value="投稿する">
        </div>
    </form>
</div>
<script src="{{ asset('js/result.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config("services.google-map.apikey") }}&callback=initMap" defer></script>
@endsection