
<?php 

session_start();
include 'navbar.php';
require 'config/querySQL.php';

$query = new SQLquery();
$data = $query->selectCoordinate();
$jsonData = json_encode($data);
$region_province = $query->selectProvince();
$jsonData_RP = json_encode($region_province);
$establishment = $query->establishment();
$establishmentData = json_encode($establishment);
$Facuty = $query->selectMajor();
$FacutyData = json_encode($Facuty);
// print_r($data);
header('Cache-Control: public, max-age=3600'); 

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polygon Display on Map</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />



        

            </style>
            <title>Internship Display on Map</title>
</head>
<body>

<div id="map"></div>
<div id='decorative-map' inert></div>
<script src='js/inert.min.js'></script>
<script src="js/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
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
var establishmentData = <?php echo $establishmentData; ?>;
var region_province = <?php echo $jsonData_RP; ?>;
var FacutyData = <?php echo $FacutyData; ?>;

const regions = {
    allRegion:[],
    north: [],
    northeast: [],
    central: [],
    south:[],
    east:[],
    west : []
}


// นับจำนวนว่ามีสถานที่ฝึกงานกี่แห่งในจังหวัดนั้นๆ
const countedProvince = coordinate.reduce((acc, value) => {
    
    acc[value.province] = (acc[value.province] || 0) + 1;
    return acc;
}, {});

// บอกว่าในจังหวัดนั้นๆมีกี่ location
region_province.forEach( item =>{
if(item.province_name in countedProvince){
    item.province_name = item.province_name+' ('+ countedProvince[item.province_name]+')';
}
regions['allRegion'].push(item.province_name)//แก้ไขชื่อ

// เตรียมจังหวัดไว้ให้เลือก
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
            <br/>
            <label for="province">เลือกจังหวัด:</label>
            <select id="province" onchange="watchWithRegion();">
                <option value="allProvince">เลือกจังหวัด(ทั้งหมด)</option>  
            </select>
            <br/>
                <label for="establishment">เลือกประเภทสถานประกอบการ:</label>
            <select id="establishment" onchange="watchWithRegion();">
                <option value="allEstablishment">เลือกประเภทสถานประกอบการ(ทั้งหมด)</option>  
            </select>

            <br/>
            <label for="major_subject">เลือกสาขาวิชา:</label>
            <select id="major_subject" onchange="watchWithRegion();">
                <option value="allMajor">เลือกสาขาวิชา(ทั้งหมด)</option>  
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
        
        if (region_province) {

                region_province.forEach(province => {
                const option = document.createElement('option');
                option.value = province.province_id;
                option.textContent = province.province_name;
                provinceSelect.appendChild(option);
            });
        }
    }


    // select establishmentData-------------------------------------------------------------------------------
    function updateEstablishment() {
    const establishment = document.getElementById('establishment');
    
    establishment.innerHTML = '<option value="allEstablishment">เลือกประเภทสถานประกอบการ(ทั้งหมด)</option>'; // Clear previous options
    // const unique_Faculty = [...new Set(coordinate.map(item => item.facuty))];
    
    establishmentData.forEach(item => {

        const establishment_option = document.createElement('option');
        establishment_option.value = item.id;
        establishment_option.textContent = item.establishment;
        establishment.appendChild(establishment_option);
    });

    // // Call the update function when a faculty is selected
    // establishment.addEventListener('change', updateMajorSubjects);
    
    // // Initialize major subjects based on the initial faculty selection
    // 
    }
    updateMajorSubjects();
    // update major from selecting facuty-------------------------------------------------------------------------------
    function updateMajorSubjects() {
        const establishment_selected = document.getElementById('establishment').value;
        const major_subject = document.getElementById('major_subject');
        major_subject.innerHTML = '<option value="">สาขาวิชา(ทั้งหมด)</option>'; // Clear previous options

        const unique_MajorNames = [
            ...new Set(
                
                FacutyData
                    
            )
        ];
        
        unique_MajorNames.forEach(item => {
            const major_option = document.createElement('option');
            major_option.value = item.id;
            major_option.textContent = item.major_subject;
            major_subject.appendChild(major_option);
        });
    }
    updateEstablishment()
    let new_coordinate = '';

// เตรียมข้อมูลสำหรับสร้าง marker หลังจากเลือกจังหวัดหรือภาคแล้ว-----------------------------------------------------------------
function watchWithRegion() {
    const province = document.getElementById('province').value;
    const regionSelect = document.getElementById('regionSelect').value;
    const major_subject = document.getElementById('major_subject').value;
    const establishment = document.getElementById('establishment').value;
    
    // console.log(province);
    // console.log(regionSelect);
    // console.log(major_subject);
    // console.log(establishment);

    fetch(`config/fetchdata.php?func=5`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
       body: `province=${encodeURIComponent(province)}&region=${encodeURIComponent(regionSelect)}&establishment=${encodeURIComponent(establishment)}&major_subject=${encodeURIComponent(major_subject)}`
    })
    .then(response => {
    

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {

        new_coordinate = data.value;

        // Check if new_coordinate is an array
        if (Array.isArray(new_coordinate) && new_coordinate.length > 0) {
            onmap = '';
            updateSelecting(new_coordinate); // Call updateProvinces with new data
        } else {
            alert("ไม่มีข้อมูล");
        }
    })
    .catch(error => {
        console.error("Fetch error:", error);
    });
}

// Initial call with PHP data (assuming $jsonData is a valid JSON array)

let previousLayers = []; 
updateSelecting(coordinate);
// สร้าง marker
function updateSelecting(new_coordinate) {
    
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

        var marker = L.marker([item.latitude, item.longtitude], {
            rotationAngle: 45  // กำหนดมุมหมุน 45 องศา
        })
            .bindPopup(item.location + '<br>จังหวัด ' + item.province + 
                       '<br><a href="search.php?func=3&type=' + item.location + '" target="_blank">รายละเอียดเพิ่มเติม</a>')
            .on('mouseover', function () {
                this.openPopup();
            })
            .on('mouseout', function () {
                if (!this.isPopupOpen) {
                    this.closePopup();
                }
            })
            .on('click', function() {
                this.isPopupOpen = true;
                this.openPopup(); 
            })
            .on('popupclose', function () {
                this.isPopupOpen = false;
            });

        regionGroups[item.rid].addLayer(marker);
        
        // Add this marker to the previousLayers array
        previousLayers.push(marker);
    });

    // L.control.layers(null, regionGroups).addTo(map);
}


// ------------------------------------------------------------------------------------------------------------------

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