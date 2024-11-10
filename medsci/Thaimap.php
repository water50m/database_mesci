<?php 
include 'navbar.php';
require 'config/querySQL.php';
$query = new SQLquery();
$data = $query->selectCoordinate();
$jsonData = json_encode($data);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polygon Display on Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 600px; width: 100%; }
        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }
        .legend {
            line-height: 18px;
            color: #555;
        }
        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }

            </style>
</head>
<body>

<div id="map"></div>
<div id='decorative-map' inert></div>
<script src='https://unpkg.com/wicg-inert@latest/dist/inert.min.js'></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="js/th-new.js"></script>
<script>

// ใช้ Fetch API โหลดไฟล์ GeoJSON
    const map = L.map('map').setView([13.736717, 100.523186], 6); // พิกัดกลางที่ประเทศไทยและซูมระดับ 6

// เพิ่มแผนที่พื้นฐาน (Tile Layer)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors'
}).addTo(map);


// ------------------------------------------------------------------------------------------------------------------marker
var coordinate = <?php echo $jsonData; ?>;



const regionData = [
    { id: '1', name: 'ภาคเหนือ' },
    { id: '2', name: 'ภาคตะวันออกเฉียงเหนือ' },
    { id: '3', name: 'ภาคตะวันตก' },
    { id: '4', name: 'ภาคกลาง' },
    { id: '5', name: 'ภาคตะวันออก' },
    { id: '6', name: 'ภาคใต้' },
    { id: '7', name: 'ภาคกลาง' },
    { id: '8', name: 'ภาคกลาง' },
    { id: '9', name: 'ภาคกลาง' }
];
// สร้าง object เพื่อเก็บ LayerGroup สำหรับแต่ละ region
var regionGroups = {};

// ลูปข้อมูลแต่ละ item (ข้อมูลแต่ละภูมิภาค)
coordinate.forEach(function(item) {
    
    coordinate.forEach((item) => {
    // Find the matching region by id
    const region = regionData.find(r => r.id === item.region_id);
    
    if (region) {
        item.region_id = region.name;  // Replace region_id with the name
         
    }else{
        
    }
});
    // ตรวจสอบว่ามี LayerGroup สำหรับ region_id นี้หรือยัง
    if (!regionGroups[item.region_id]) {
        
        regionGroups[item.region_id] = L.layerGroup().addTo(map); // สร้าง LayerGroup ใหม่ถ้ายังไม่มี
    }
    
    // สร้าง marker สำหรับภูมิภาคที่กำหนด
    var marker = L.marker([item.latitude, item.longtitude])
    .bindPopup(item.location+'<br>จังหวัด '+item.province + '<br><a href="search.php?func=3&type='+item.id+'" target="_blank">รายละเอียดเพิ่มเติม</a>')
    .on('mouseover', function () {
        this.openPopup();
    })
    .on('mouseout', function () {
        if (!this.isPopupOpen) { // ถ้าไม่ใช่การเปิดจากการคลิกให้ปิด
            this.closePopup();
        }
    })
    .on('click', function (e) {
        this.isPopupOpen = true; // ตั้งค่าว่า popup ถูกเปิดจากการคลิก
        this.openPopup(); // เปิด Popup ค้างไว้
        // map.setView(e.latlng, map.getZoom() + 1); // ซูมเข้าหา marker เมื่อคลิก
    })
    .on('popupclose', function () {
        this.isPopupOpen = false; // รีเซ็ตสถานะเมื่อปิด Popup
    });

    // เพิ่ม marker เข้า LayerGroup ที่ตรงกับ region_id
    regionGroups[item.region_id].addLayer(marker);
});

// เพิ่มตัวเลือกการแสดงผลของ LayerGroup ใน layer control
L.control.layers(null, regionGroups).addTo(map);


// ------------------------------------------------------------------------------------------------------------------
function getColor(d) {
    return d > 1000 ? '#800026' :
           d > 500  ? '#BD0026' :
           d > 200  ? '#E31A1C' :
           d > 100  ? '#FC4E2A' :
           d > 50   ? '#FD8D3C' :
           d > 20   ? '#FEB24C' :
           d > 10   ? '#FED976' :
                      '#FFEDA0';
}

// L.geoJson(province).addTo(map);
    // Add the GeoJSON feature to the map
    function style(feature) {
    return {
        fillColor: getColor(feature.properties.density),
        weight: 2,
        opacity: 1,
        color: 'white',
        dashArray: '3',
        fillOpacity: 0.7
    };
}

// L.geoJson(province, {style: style}).addTo(map);
// -------------------------------------------------------------------------------------add province on map
function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        fillColor:'white',
        weight: 2,
        opacity: 1,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

    layer.bringToFront();
    info.update(layer.feature.properties);
}
function resetHighlight(e) {
    geojson.resetStyle(e.target);
    info.update();
}
function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });
}

// geojson = L.geoJson(province, {
//     style: style,
//     onEachFeature: onEachFeature
// }).addTo(map);

// -----------------------------------------------------------------------------------------------------
var info = L.control();
info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
    this.update();
    return this._div;
};
info.update = function (props) {
    this._div.innerHTML = '<h4>US Population Density</h4>' +  (props ?
        '<b>' + props.th_name + '</b><br />' + props.id + ' people / mi<sup>2</sup>'
        : 'Hover over a state');
};

// info.addTo(map);

// -------------------------------------------------------------------------------------------------------------------
var legend = L.control({position: 'bottomright'});

legend.onAdd = function (map) {

    var div = L.DomUtil.create('div', 'info legend'),
        grades = [0, 10, 20, 50, 100, 200, 500, 1000],
        labels = [];

    // loop through our density intervals and generate a label with a colored square for each interval
    for (var i = 0; i < grades.length; i++) {
        div.innerHTML +=
            '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
            grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
    }

    return div;
};

legend.addTo(map);
// ---------------------------------------------------------------------------------------------------------------------


</script>

</body>
</html>
