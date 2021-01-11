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

//Home表示時、現在値周辺のDB猫情報をマーカー表示
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
            //現在値情報の地図表示
            var mapLatLng = new google.maps.LatLng({lat: position.coords.latitude, lng: position.coords.longitude}); // 緯度経度のデータ作成
            mapobj = new google.maps.Map(targetmap, { // #mapに地図を埋め込む
                center: mapLatLng, // 地図の中心を指定
                zoom: 15 // 地図のズームを指定
            });
            //console.log('mapobj1:',mapobj);
            google.maps.event.addListener(mapobj, 'bounds_changed', function() {
                const csrf = document.getElementsByName('csrf-token')[0].content;//'X-CSRF-TOKEN'設定用
                //console.log('csrf:',csrf);

                const requestData = {};
                requestData.lat = position.coords.latitude; //緯度
                requestData.lng = position.coords.longitude; //経度

                var latlngBounds = mapobj.getBounds();//地図の中心

                //console.log('latlngBounds:',latlngBounds,'mapLatLng:',mapLatLng);

                var swLatlng = latlngBounds.getSouthWest();//地図左下
                var neLatlng = latlngBounds.getNorthEast();//地図右上

                requestData.swLat = swLatlng.lat();//地図左下緯度
                requestData.swlng = swLatlng.lng();//地図左下経度
                requestData.neLat = neLatlng.lat();//地図右上緯度
                requestData.nelng = neLatlng.lng();//地図右上経度

                //console.log(requestData);

                fetch('/marker', {
                    method: 'POST', // 👈 メソッド ・・・ ①
                    headers: {
                        'Content-Type': 'application/json', // 👈 データ形式 ・・・ ②
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify(requestData) // 👈 送信データ ・・・ ③
                })
                .then(response => {
            
                    return response.json(); // 👈 Promiseを返す
            
                })
                .then(data => { // 👈 JSONデータ
            
                    //console.log('request_data:',data);
                    geo(data);//function geo 実行
            
                })
                .catch(error => { // 👈 エラーの場合
            
                    console.log(error);
            
                });
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
    //geo();
    //var markerData = discoveries;// マーカーを立てる場所名・緯度・経度　$discoveries
    // マーカー毎の処理
    function geo(discoveries){
        // var cRef = discoveries.length;
        for (var i = 0; i < discoveries.length; i++) {
            (function (i) {

                var pattern = discoveries[i]['pattern']; //console.log(n,pattern);
                var uuid = discoveries[i]['uuid']; //console.log(n,pattern);

                // var img = discoveries[i]['images'].split(',');
                // var imgurl = img[0];
                var img = discoveries[i]['filename'];
                if (img==null) {
                    var imgurl = "https://machineko.s3-ap-northeast-1.amazonaws.com/cats_imgs/mapicon.png"
                } else {
                    var imgurl = "https://machineko.s3.ap-northeast-1.amazonaws.com/cats_imgs/" + img;
                }

                var dblat =discoveries[i]['Lat'];
                var dblng =discoveries[i]['Lng'];
                // console.log('mapobj2:',mapobj);

                // var address = discoveries[i]['locate'];//住所を指定
    
                // var geocoder = new google.maps.Geocoder();

                // geocoder.geocode({
                //     address: address
                // }, function(results, status){
                //     if (status == google.maps.GeocoderStatus.OK) {
                //         if (results[0].geometry) {
                            // var latlng = results[0].geometry.location;
                            var latlng = new google.maps.LatLng({lat: dblat, lng: dblng}); // 緯度経度のデータ作成
                            createMarker(pattern,uuid,latlng,imgurl,mapobj);
                        // }

                    // }else{ 
                    //     //住所が存在しない場合の処理
                    //     alert('住所が正しくないか存在しません。');
                    //     console.log('none');
                    //     targetmap.style.display='none';
                    //     latlng[i] = '';
                    // }
                // });
            }) (i);
        };
    };//geo(callback)の終了
};

function createMarker(name,uuid,latlng,icons,map){
    // console.log('name',name,'uuid',uuid,'latlng',latlng,'icons',icons,'map',map);
    /* InfoWindowクラスのオブジェクトを作成 */
    var infoWindow = new google.maps.InfoWindow();
     
    /* detailへ遷移するURL */
    var url = '<a href="/discover/detail/' + uuid + '">' + name + '</a>';
    // console.log('url:',url);

    /* 指定したオプションを使用してマーカーを作成 */
    var marker = new google.maps.Marker({position: latlng,
                                        icon:{url:icons,scaledSize: new google.maps.Size(45, 45)}, 
                                        map: map});
                                        // position: new google.maps.LatLng(lat, lng), 
    /* addListener を使ってイベントリスナを追加 */
    /* 地図上のmarkerがクリックされると｛｝内の処理を実行。*/
    google.maps.event.addListener(marker, 'click', function() {
    
        /* InfoWindowOptionsオブジェクトを指定 */
        
        infoWindow.setContent(url);

        /* マーカーに情報ウィンドウを表示 */
        infoWindow.open(map,marker);
        
        /* マーカーをクリックした時に地図の中心 */
        map.panTo(latlng);
     
    });
} 

//View-Discover表示時のJavascript
function initMap() {

    targetmap = document.getElementById("map");

    // 現在地を取得
    navigator.geolocation.getCurrentPosition(
        // 取得成功した場合
        function(position) {
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

            getClickLatLng(mapLatLng, mapobj);
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

var MarkerArray = [];
function storeMap() {
//住所を手入力した時に地図上にマーカーを配置する
    var target = document.getElementById('map'); //マップを表示する要素を指定
    var address = document.getElementById('locate').value;//東京都新宿区西新宿2-8-1'; //住所を指定
    var geocoder = new google.maps.Geocoder();  

    geocoder.geocode({ address: address }, function(results, status){
        if (status === 'OK' && results[0]){
            ClearAllIcon();
            //console.log(results[0].geometry.location);

            var map = new google.maps.Map(target, {  
                center: results[0].geometry.location,
                zoom: 18
            });

            var marks = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map,
                animation: google.maps.Animation.DROP
            });
            MarkerArray.push(marks);

        }else{ 
            //住所が存在しない場合の処理
            alert('住所が正しくないか存在しません。');
            target.style.display='none';
        }
    });
}

function getClickLatLng(lat_lng, map) {
//地図をクリックした時にマーカーを配置する
    var geocoder = new google.maps.Geocoder();
   
    geocoder.geocode({
      latLng: lat_lng
    }, function(results, status) {

        if (status == google.maps.GeocoderStatus.OK) {

            if (results[0].geometry) {
                ClearAllIcon();
                var address = results[0].formatted_address.replace(/^日本(、|,)/, '');
                document.getElementById("locate").value = address;
                document.getElementById("lat").value = lat_lng.lat();
                document.getElementById("lng").value = lat_lng.lng();
                var marks = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(lat_lng.lat(), lat_lng.lng()),
                    animation: google.maps.Animation.DROP
                });
                MarkerArray.push(marks);
            }
        } else {
            app.adrs_list.push({address: "", lat: latlng.lat(), lng: latlng.lng(), comment: status });
        }
    });
}
function ClearAllIcon() {
    MarkerArray.forEach(function (marker, idx) { marker.setMap(null); });
}

//View-Detail表示時のJavascript
//detail_lat; detail_lng;
function placeMap() {
    
    targetmap = document.getElementById("map");

    var mapLatLng = new google.maps.LatLng({lat: cat.Lat, lng: cat.Lng}); // 緯度経度のデータ作成
    var mapobj = new google.maps.Map(targetmap, { // #mapに地図を埋め込む
            center: mapLatLng, // 地図の中心を指定
            zoom: 15 // 地図のズームを指定
    });
    var marks = new google.maps.Marker({
        position: mapLatLng,
        map: mapobj,
        animation: google.maps.Animation.DROP
    });
    MarkerArray.push(marks);
}
