@extends('layouts.app')

@section('content')
<div class="container">
    <h1>まちねこ</h1>
    @php
        $json_discoveries = json_encode($discoveries,JSON_PRETTY_PRINT);
    @endphp
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center mt-3 mb-3">
        <div id="map" class="col-8" style="height: 20rem;">
        </div>
    </div>
    <div class="row">
        <a href="/discover" class="btn btn-outline-info">ねこ情報を投稿する</a>
    </div>
    @foreach ($discoveries as $discovery)
        <div class="card">
            <div class="card-body">
                <img class="card-img-top img-thumbnail" alt="{{$discovery->images}}の画像" src="/uploads/cats_imgs/{{ $discovery->images }}">
                <h5 class="card-title">{{$discovery->pattern}}</h5>
                <p class="card-text">発見場所：{{$discovery->locate}}</p>
            </div>
        </div>
    @endforeach
</div>
<script>var discoveries = <?php echo $json_discoveries ?>;</script>
<script src="{{ asset('js/result.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config("services.google-map.apikey") }}&callback=locationMap&libraries=&v=weekly" defer></script>

@endsection
