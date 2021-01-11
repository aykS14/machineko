@extends('layouts.app')

@section('content')
<div class="container">
    <h1>まちねこ とは</h1>
    <div class="mt-3">
        <p>
            『まちねこ』は、皆さまの町に暮らす猫さんを見守る皆さまの情報交換サイトです。<br>
            <br>
            いつも同じ場所で見かける猫さんの写真を投稿したり、<br>
            おなかすかせている様子なのに手持ちにごはんがない時に<br>
            ごはんをあげて欲しいと呼びかけたり、、、<br>
            <br>
            皆さまの町の地域猫さんを見守る一助になれたら幸いです。
        </p>
    </div>
    <div class="col mt-3">
        <h4 class="text-danger">- attention -</h4>
        <ul class="text-left">
            <li>投稿する猫さんの情報には、Google Map を利用した位置情報が掲載されます。</li>
            <li>マップの表示、及び投稿にはアカウント登録が必要です。</li>
        </ul>
    </div>
    <div class="text-right" style="height: 5rem;">
        <a href="/home">≪ ログインしてマップを見る</a>
    </div>
    <div class="links mt-5">
        <a href="/rules">ご利用ガイド</a>
        <a href="/rules/privacypolicy">プライバシーポリシー</a>
    </div>
</div>

@endsection
