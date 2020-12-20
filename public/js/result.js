var map;
var marker = [];
var infoWindow = [];

function initialize(){
    var mapProp = {
        center: new google.maps.LatLng(38, -78),
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map'), mapProp);
};

function locationMap() {
    
    targetmap = document.getElementById("map");
    var mapLatLng = new google.maps.LatLng({lat: 35.6585769, lng: 139.7454506}); // 緯度経度のデータ作成
    var mapobj = new google.maps.Map(targetmap, { // #mapに地図を埋め込む
        center: mapLatLng, // 地図の中心を指定
        zoom: 15 // 地図のズームを指定
    });

    // 現在地を取得
    navigator.geolocation.getCurrentPosition(
        // 取得成功した場合
        function(position) {
            //var mapLatLng = {lat: position.coords.latitude, lng: position.coords.longitude};
            var mapLatLng = new google.maps.LatLng({lat: position.coords.latitude, lng: position.coords.longitude}); // 緯度経度のデータ作成
            mapobj = new google.maps.Map(targetmap, { // #mapに地図を埋め込む
                center: mapLatLng, // 地図の中心を指定
                zoom: 15 // 地図のズームを指定
            });
            
        },
        // 取得失敗した場合
        function(error) {
            switch(error.code) {
            case 1: //PERMISSION_DENIED
                alert("位置情報の利用が許可されていません");
                break;
            case 2: //POSITION_UNAVAILABLE
                alert("現在位置が取得できませんでした");
                break;
            case 3: //TIMEOUT
                alert("タイムアウトになりました");
                break;
            default:
                alert("その他のエラー(エラーコード:"+error.code+")");
                break;
            }            
        }
    );
    geo();
    //var markerData = discoveries;// マーカーを立てる場所名・緯度・経度　$discoveries
    // マーカー毎の処理
    function geo(){
        var cRef = discoveries.length;
        for (var i = 0; i < discoveries.length; i++) {
            (function (i) {

                var n = discoveries[i]['id'];
                var pattern = discoveries[i]['pattern']; console.log(n,pattern);
                var imgurl = '/uploads/cats_imgs/' + discoveries[i]['images'];
                var address = discoveries[i]['locate'];//住所を指定
    
                var geocoder = new google.maps.Geocoder();

                geocoder.geocode({
                    address: address
                }, function(results, status){
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0].geometry) {
                            var latlng = results[0].geometry.location;

                            createMarker(pattern,latlng,imgurl,mapobj);
                            console.log('pattern',discoveries[i]['pattern']);
                            
                        }

                    }else{ 
                        //住所が存在しない場合の処理
                        //alert('住所が正しくないか存在しません。');
                        console.log('none');
                        //targetmap.style.display='none';
                        latlng[i] = '';
                    }
                });
            }) (i);
        };
    };//geo(callback)の終了
};

function createMarker(name,latlng,icons,map){
    console.log('name',name,'latlng',latlng,'icons',icons,'map',map);
    /* InfoWindowクラスのオブジェクトを作成 */
    var infoWindow = new google.maps.InfoWindow();
     
    /* 指定したオプションを使用してマーカーを作成 */
    var marker = new google.maps.Marker({position: latlng, icon:{url:icons,scaledSize: new google.maps.Size(40, 40)}, map: map});
     
    /* addListener を使ってイベントリスナを追加 */
    /* 地図上のmarkerがクリックされると｛｝内の処理を実行。*/
    google.maps.event.addListener(marker, 'click', function() {
    
        /* InfoWindowOptionsオブジェクトを指定 */
        infoWindow.setContent(name);
        
        /* マーカーに情報ウィンドウを表示 */
        infoWindow.open(map,marker);
        
        /* マーカーをクリックした時に地図の中心 */
        map.panTo(latlng);
     
    });
} 

function storeMap() {

    var target = document.getElementById('map'); //マップを表示する要素を指定
    var address = document.getElementById('locate').value;//東京都新宿区西新宿2-8-1'; //住所を指定
    var geocoder = new google.maps.Geocoder();  

    geocoder.geocode({ address: address }, function(results, status){
        if (status === 'OK' && results[0]){

            console.log(results[0].geometry.location);

            var map = new google.maps.Map(target, {  
                center: results[0].geometry.location,
                zoom: 18
            });

            var marker = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map,
                animation: google.maps.Animation.DROP
            });

        }else{ 
            //住所が存在しない場合の処理
            alert('住所が正しくないか存在しません。');
            target.style.display='none';
        }
    });
}

function initMap() {

    targetmap = document.getElementById("map");

    // 現在地を取得
    navigator.geolocation.getCurrentPosition(
        // 取得成功した場合
        function(position) {
            //var mapLatLng = {lat: position.coords.latitude, lng: position.coords.longitude};
            var mapLatLng = new google.maps.LatLng({lat: position.coords.latitude, lng: position.coords.longitude}); // 緯度経度のデータ作成
            var mapobj = new google.maps.Map(targetmap, { // #mapに地図を埋め込む
                center: mapLatLng, // 地図の中心を指定
                zoom: 15 // 地図のズームを指定
            });
            // クリックイベントを追加
            mapobj.addListener('click', function(e) {
                getClickLatLng(e.latLng, mapobj);
                //getAddress(e.latLng);
            });
        },
        // 取得失敗した場合
        function(error) {
            switch(error.code) {
            case 1: //PERMISSION_DENIED
                alert("位置情報の利用が許可されていません");
                break;
            case 2: //POSITION_UNAVAILABLE
                alert("現在位置が取得できませんでした");
                break;
            case 3: //TIMEOUT
                alert("タイムアウトになりました");
                break;
            default:
                alert("その他のエラー(エラーコード:"+error.code+")");
                break;
            }
            //var mapLatLng = {lat: 35.6585769, lng: 139.7454506};
            var mapLatLng = new google.maps.LatLng({lat: 35.6585769, lng: 139.7454506}); // 緯度経度のデータ作成
            var mapobj = new google.maps.Map(targetmap, { // #mapに地図を埋め込む
                center: mapLatLng, // 地図の中心を指定
                zoom: 15 // 地図のズームを指定
            });
            // クリックイベントを追加
            mapobj.addListener('click', function(e) {
                getClickLatLng(e.latLng, mapobj);
                //getAddress(e.latLng);
            });
        }
    );
}

function getClickLatLng(lat_lng, map) {

    var geocoder = new google.maps.Geocoder();
   
    geocoder.geocode({
      latLng: lat_lng
    }, function(results, status) {

        if (status == google.maps.GeocoderStatus.OK) {

            if (results[0].geometry) {
                var address = results[0].formatted_address.replace(/^日本(、|,)/, '');
                document.getElementById("locate").value = address ;
                var marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(lat_lng.lat(), lat_lng.lng()),
                    animation: google.maps.Animation.DROP
                });
            }
        } else {
            app.adrs_list.push({address: "", lat: latlng.lat(), lng: latlng.lng(), comment: status });
        }
    });
}
