let map;

function initMap() {
    /*
    map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -34.397, lng: 150.644 },
    zoom: 8,
    });*/

    map = document.getElementById("map");
    // 東京タワーの緯度は35.6585769,経度は139.7454506と事前に調べておいた
    let tokyoTower = {lat: 35.6585769, lng: 139.7454506};
    // オプションを設定
    opt = {
        zoom: 13, //地図の縮尺を指定
        center: tokyoTower, //センターを東京タワーに指定
    };
    // 地図のインスタンスを作成します。第一引数にはマップを描画する領域、第二引数にはオプションを指定
    mapObj = new google.maps.Map(map, opt);

}
function setLocation(pos) {

    // 緯度・経度を取得
    const lat = pos.coords.latitude;
    const lng = pos.coords.longitude;
    // 定数lat,lng をconsoleに出力
    console.log(lat);
    console.log(lng);

}

// エラー時に呼び出される関数
function showErr(err) {
    switch (err.code) {
        case 1 :
            alert("位置情報の利用が許可されていません");
            break;
        case 2 :
            alert("デバイスの位置が判定できません");
            break;
        case 3 :
            alert("タイムアウトしました");
            break;
        default :
            alert(err.message);
    }
}

// geolocation に対応しているか否かを確認
if ("geolocation" in navigator) {
    var opt = {
        "enableHighAccuracy": true,
        "timeout": 10000,
        "maximumAge": 0,
    };
    navigator.geolocation.getCurrentPosition(setLocation, showErr, opt);
} else {
    alert("ブラウザが位置情報取得に対応していません");
}