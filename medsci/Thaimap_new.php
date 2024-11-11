<?php 
include 'navbar.php';
require 'config/querySQL.php';
$query = new SQLquery();
$data = $query->selectCoordinate();
$jsonData = json_encode($data);
$region_province = $query->selectProvince();
$jsonData_RP = json_encode($region_province);



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

        .leaflet-control-custom {
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
        }
        select {
            font-size: 16px;
        }
            </style>
</head>
<body>

<div id="map"></div>
<div id='decorative-map' inert></div>
<!-- Include Leaflet.markercluster.js and its CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

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


//---------------------------------------------------------------------------------------------------testLayer 
var coordinate = <?php echo $jsonData; ?>;
var region_province = <?php echo $jsonData_RP; ?>;
const regions = {
    allRegion:[],
    north: [],
    northeast: [],
    central: [],
    south:[],
    east:[],
    west : []
}

const countedProvince = coordinate.reduce((acc, value) => {
    
    acc[value.province] = (acc[value.province] || 0) + 1;
    return acc;
}, {});

region_province.forEach( item =>{
if(item.province_name in countedProvince){
    item.province_name = item.province_name+' ('+ countedProvince[item.province_name]+')';
}

regions['allRegion'].push(item.province_name)
if (regions[item.region_category]) {
    regions[item.region_category].push(item.province_name)
}}) 


    // Create a custom control
    const regionControl = L.control({position: 'topright'});

    regionControl.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'leaflet-control-custom');
        div.innerHTML = `
            <label for="region">เลือกภูมิภาค:</label>
            <select id="regionSelect" onchange="updateProvinces();watchWithRegion();">
                <option value="allRegion">เลือกภูมิภาค(ทั้งหมด)</option>
                <option value="north">ภาคเหนือ</option>
                <option value="northeast">ภาคตะวันออกเฉียงเหนือ</option>
                <option value="central">ภาคกลาง</option>
                <option value="south">ภาคใต้</option>
                <option value="west">ภาคตะวันตก</option>
                <option value="east">ภาคตะวันออก</option>
            </select>
            <br />
            <label for="province">เลือกจังหวัด:</label>
            <select id="province" onchange="watchWithRegion();">
                <option value="allProvince">เลือกจังหวัด(ทั้งหมด)</option>
                
            </select>
        `;
        
        return div;
    };

    regionControl.addTo(map);
    updateProvinces()
    // Update provinces based on selected region
    function updateProvinces() {
        const region = document.getElementById('regionSelect').value;
        const provinceSelect = document.getElementById('province');
        provinceSelect.innerHTML = '<option value="allProvince">เลือกจังหวัด(ทั้งหมด)</option>'; // Clear previous options

        if (regions[region]) {
            regions[region].forEach(province => {
                const option = document.createElement('option');
                option.value = province;
                option.textContent = province;
                provinceSelect.appendChild(option);
            });
        }
    }

    let new_coordinate = '';

function watchWithRegion() {
    const province = document.getElementById('province').value;
    const regionSelect = document.getElementById('regionSelect').value;

    fetch(`config/fetchdata.php?func=5`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `&province=${encodeURIComponent(province)}&region=${encodeURIComponent(regionSelect)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data)
        new_coordinate = data.value;

        // Check if new_coordinate is an array
        if (Array.isArray(new_coordinate) && new_coordinate.length > 0) {
            onmap = '';
            updateSelecting(new_coordinate); // Call updateProvinces with new data
        } else {
            alert("ไม่มีสถานที่ฝึกงานในจังหวัด หรือ ในภูมิภาคนี้");
        }
    })
    .catch(error => {
        console.error("Fetch error:", error);
    });
}

// Initial call with PHP data (assuming $jsonData is a valid JSON array)

let previousLayers = []; 
updateSelecting(coordinate);
function updateSelecting(new_coordinate) {
    const markerClusterGroup = L.markerClusterGroup();
    // Remove previous layers if any
    if (previousLayers.length > 0) {
        previousLayers.forEach(layer => {
            map.removeLayer(layer); // Remove the layer from the map
        });
        previousLayers = []; // Clear the array of layers
    }

    if (!new_coordinate || !Array.isArray(new_coordinate)) {
        console.error('Invalid new_coordinate data:', new_coordinate);
        return; // Exit function if data is invalid
    }

    const regionData = [
        { id: 1, name: 'ภาคเหนือ' },
        { id: 2, name: 'ภาคตะวันออกเฉียงเหนือ' },
        { id: 3, name: 'ภาคตะวันตก' },
        { id: 4, name: 'ภาคกลาง' },
        { id: 5, name: 'ภาคตะวันออก' },
        { id: 6, name: 'ภาคใต้' },
        { id: 7, name: 'ภาคกลาง' },
        { id: 8, name: 'ภาคกลาง' },
        { id: 9, name: 'ภาคกลาง' }
    ];

    var regionGroups = {};
    

    new_coordinate.forEach(function (item) {
        const region = regionData.find(r => r.id === item.rid);
        if (region) {
            item.rid = region.name; // Replace region_id with the name
        }

        if (!regionGroups[item.rid]) {
            regionGroups[item.rid] = L.layerGroup().addTo(map);
        }

        var marker = L.marker([item.latitude, item.longtitude])
            .bindPopup(item.location + '<br>จังหวัด ' + item.province + 
                       '<br><a href="search.php?func=3&type=' + item.id + '" target="_blank">รายละเอียดเพิ่มเติม</a>')
            .on('mouseover', function () {
                this.openPopup();
            })
            .on('mouseout', function () {
                if (!this.isPopupOpen) {
                    this.closePopup();
                }
            })
            .on('click', function (e) {
                this.isPopupOpen = true;
                this.openPopup();
            })
            .on('popupclose', function () {
                this.isPopupOpen = false;
            });
            
        regionGroups[item.rid].addLayer(marker);
        
        // Add this marker to the previousLayers array
        previousLayers.push(marker);
        markerClusterGroup.addLayer(marker);
    });
map.addLayer(markerClusterGroup);
    // L.control.layers(null, regionGroups).addTo(map);
}


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

// ---------------------------------------------------------------------------------------------------------------------


</script>

</body>
</html>
