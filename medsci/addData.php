<?php 
session_start();
if(!isset($_SESSION['whoareyou']) ){
    header("location: login.php");
    exit();
}
require 'config/querySQL.php';
$query = new SQLquery();
$fucn_query = $query->selectFacuty();
$jsonDataFacuty = json_encode($fucn_query);
$region = $query->selectRegion();
$func_province = $query->selectProvince();
$jsonDataProvince = json_encode($func_province);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/addData.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>เพิ่มข้อมูลใหม่</title>
    <script src="https://api.longdo.com/map/?key=bff66f6baa485edba09ca806b597ed30"></script>
    
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container2">
        <form action="config/addDatadb.php" method="POST" enctype="multipart/form-data">
            <div class="box">
                <div class="mb-3">
                    <label class="form-label">
                        <span class="icon-container">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon" class="size-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"></path>
                            </svg>
                        </span>
                        สถานที่ฝึกงาน
                        <a href="#" onclick="openLongdoMap()" style="margin-left: 8px;">
                            <i class="fas fa-search"></i>
                        </a>
                        
                    </label>

                    <div class="input-group">
                        <textarea class="form-control" aria-label="Text input" name="_location" id="locationInput"></textarea>
                    </div>   
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile01">รูปภาพ</label>
                    <input type="file" class="form-control" id="inputGroupFile01" name="picture_" accept="image/*">
                </div>
               

                <div class="mb-3">
                    
                    <div class="input-group">
                    <span class="input-group-text">ภาควิชา</span>   
                        <select class="form-select" aria-label="เลือกสาขาวิชา" id="facultyName" name="_facultyname">
                            <!-- <option value="" selected>เลือกคณะ</option> -->
                            <?php
                            $shown_faculties = [];
                            foreach ($fucn_query as $faculty):
                                if (!in_array($faculty['facuty'], $shown_faculties)):
                                    $shown_faculties[] = $faculty['facuty'];
                            ?>
                                    <option value="<?php echo $faculty['facuty']; ?>">
                                        <?php echo $faculty['facuty']; ?>
                                    </option>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </select>
                        <button type="button" class="btn btn-outline-primary" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#addFacultyNameModal">
                            เพิ่มใหม่
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    
                    <div class="input-group">
                    <span class="input-group-text">สาขาวิชา</span>   
                        <select class="form-select" aria-label="เลือกสาขาวิชา" name="_facultymajor" id="facultyMajor">
                            <option value="" selected>เลือกสาขาวิชา</option>
                            
                        </select>
                        <button type="button" class="btn btn-outline-primary" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                        เพิ่มใหม่
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    
                    <div class="input-group">
                    <span class="input-group-text">แผนก</span>   
                        <input type="text" class="form-control" aria-label="Text input" name="_department">
                    </div>
                </div>

                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <div class="me-3" style="flex: 1;">
                        
                        <select class="form-select" aria-label="Default select example" name="_province" id="provinceSelect">
                            <option value="" selected>เลือกจังหวัด</option>
                            <?php foreach ($func_province as $province): ?>
                                <option value="<?php echo $province['province_name']; ?>">
                                    <?php echo $province['province_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        
                        <input type="text" class="form-control" value="ภูมิภาค" aria-label="Default select example" name="_region" id="regionSelect" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <p class="form-label">พิกัด (หมุดจะปักไว้ที่จังหวัดนั้นๆ กรณีที่ไม่ได้ระบุพิกัดแบบเจาะจง)</p>
                    <div class="input-group">
                        <input type="number" step="0.000001" class="form-control" aria-label="Latitude" placeholder="ละติจูด" name="_latitude" id="latitude">
                        <input type="number" step="0.000001" class="form-control" aria-label="Longitude" placeholder="ลองจิจูด" name="_longitude" id="longitude">
                    </div>
                </div>
                <div class="mb-3">
                    <h5 class="form-label">ที่อยู่</h5>
                    <div class="input-group">
                        <textarea class="form-control"  style="height: 100px" name="_address" id="addressInput"></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">เรียน</h5>
                    <div class="input-group">
                        <textarea class="form-control" id="floatingTextarea2" style="height: 100px" name="_sendto"></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    
                    <div class="input-group">
                    <span class="input-group-text">ผู้ประสานงาน</span>   
                        <input type="text" class="form-control" aria-label="Text input" name="_coordinator">           
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">ขอข่ายงาน</h5>
                    <div class="input-group">
                        <textarea class="form-control" id="floatingTextarea3" style="height: 100px" name="_scope"></textarea>
                    </div>
                </div>

                <h5 class="form-label">จำนวนที่รับ</h5>
                <div class="input-group mb-3">
                    <span class="form-control">
                        ภาคการศึกษาที่ 1
                    </span>
                    <input type="number" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input" name="_year1">
                    <input type="number" class="form-control" placeholder="รับ...คน" aria-label="Text input" name="_count1">
                </div>

                <div class="input-group mb-3">
                    <span class="form-control">
                        ภาคการศึกษาที่ 2
                    </span>
                    <input type="number" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input" name="_year2">
                    <input type="number" class="form-control" placeholder="รับ...คน" aria-label="Text input" name="_count2">
                </div>

                <button type="submit" class="button-3" role="button">เพิ่ม</button>

                <div class="input-group mb-3" style="padding: 50px;"></div>
            </div>
        </form>



        <!-- Modal ชื่อคณะ -->
        <div class="modal fade" id="addFacultyNameModal" tabindex="-1" aria-labelledby="addFacultyNameModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFacultyNameModalLabel">เพิ่มใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="config\addDatadb.php" method="POST">
                            <div class="mb-3">
                                <label for="facultyName" class="form-label">ชื่อคณะ</label>
                                <input type="text" class="form-control"  name="_addfacultyname1" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                <button type="submit" class="btn btn-primary">เพิ่ม</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal สำหรับเพิ่มสาขาวิชา -->
        <div class="modal fade" id="addFacultyModal" tabindex="-1" aria-labelledby="addFacultyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFacultyModalLabel">เพิ่มใหม่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="config\addDatadb.php" method="POST">
                            <div class="mb-3">
                                <label for="facultyName" class="form-label">ภาควิชา</label>
                                <select class="form-select" aria-label="เลือกสาขาวิชา" id="facultyName" name="_addfacultyname2">
                                    <option value="" selected>เลือกภาควิชา</option>
                                    <?php
                                    $shown_faculties = [];
                                    foreach ($fucn_query as $faculty):
                                        if (!in_array($faculty['facuty'], $shown_faculties)):
                                            $shown_faculties[] = $faculty['facuty'];
                                    ?>
                                            <option value="<?php echo $faculty['facuty']; ?>">
                                                <?php echo $faculty['facuty']; ?>
                                            </option>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </select>
                                <label for="facultyName" class="form-label" style="margin-top: 5px;">ชื่อสาขาวิชา</label>
                                <input type="text" class="form-control" id="facultyName" name="_addfacultymajor" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                <button type="submit" class="btn btn-primary">เพิ่ม</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- เพิ่ม Modal สำหรับแผนที่ -->
        <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mapModalLabel">เลือกตำแหน่งบนแผนที่</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div id="result" class="mb-3">
                        <input class="form-control" type="text" id="searchInput" placeholder="ค้นหาสถานที่..." style="margin-bottom: 10px;">
                        <div id="suggest" class="suggest-div"></div>
                    </div>
                    <div id="map" style="height: 500px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        var facuty = <?php echo $jsonDataFacuty; ?>;
        var facultySelect = document.getElementById('facultyName');
        var majorSelect = document.getElementById('facultyMajor');

        // ฟังก์ชันสำหรับอัพเดทสาขาวิชา
        function updateMajors(selectedFaculty) {
            // ��คลียร์ตัวเลือกเก่า
            majorSelect.innerHTML = '<option value="" selected>เลือกสาขาวิชา</option>';
            
            // กรองและเพิ่มสาขาที่ตรงกับคณะ
            facuty.forEach(function(faculty) {
                if(faculty.facuty === selectedFaculty && faculty.f_major !== '') {
                    const option = document.createElement('option');
                    option.value = faculty.f_major;
                    option.text = faculty.f_major;
                    majorSelect.appendChild(option);
                }
            });
        }

        // เียกใช้ฟังก์ชันทันทีที่โหลดหน้า
        updateMajors(facultySelect.value);

        // เพิ่ม event listener สำหรับการเปลี่ยนแปลง
        facultySelect.addEventListener('change', function() {
            updateMajors(this.value);
        });
        
        // smart select province
        var province = <?php echo $jsonDataProvince; ?>;
        
        var provinceSelect = document.getElementById('provinceSelect');
        var regionSelect = document.getElementById('regionSelect');
        updateProvinceInfo(provinceSelect)
        function updateProvinceInfo(selectedProvince) {
            province.forEach(function(prov) {
                if(prov.province_name === selectedProvince) {
                    document.getElementById('latitude').value = prov.latitude;
                    document.getElementById('longitude').value = prov.longitude; 
                    document.getElementById('regionSelect').value = prov.region_name;
     
                }
            });
        }

        provinceSelect.addEventListener('change', function() {
            updateProvinceInfo(this.value);
        });
        
        function openLongdoMap() {
            var mapModal = new bootstrap.Modal(document.getElementById('mapModal'));
            mapModal.show();
            
            // รอให้ modal แสดงผลเสร็จก่อน แล้วค่อย initialize แผนที่
            mapModal._element.addEventListener('shown.bs.modal', function () {
                initializeMap();
            });
        }

        // แยก map initialization ออกมา
        var map;
        function initializeMap() {
            map = new longdo.Map({
                placeholder: document.getElementById('map'),
                language: 'th'
            });

            map.Search.placeholder(
                document.getElementById('result')
            );

            // เพิ่ม event listener สำหรับการคลิกบนแผนที่
            map.Event.bind('click', function(overlay) {
                if (overlay.location) {
                    var lat = overlay.location.lat;
                    var lon = overlay.location.lon;
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;
                    map.location(overlay.location, true);
                }
            });
        }

        // เรียกใช้ initializeMap หลังจากโหลด DOM เสร็จ
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            
            // ส่วนของ search functionality ที่เหลือ
            var searchInput = document.getElementById('searchInput');
            var suggestDiv = document.getElementById('suggest');

            // ปรับปรุงส่วน suggest
            searchInput.addEventListener('input', function() {
                if (this.value.length < 3) {
                    suggestDiv.innerHTML = '';
                    suggestDiv.style.display = 'none';
                    return;
                }
                
                // เรียกใช้ suggest API โดยตรง
                $.ajax({
                    url: 'https://search.longdo.com/mapsearch/json/suggest',
                    data: {
                        keyword: this.value,
                        limit: 10
                    },
                    success: function(result) {
                        if (!result || result.data.length === 0) {
                            suggestDiv.style.display = 'none';
                            return;
                        }

                        suggestDiv.innerHTML = '';
                        result.data.forEach(function(item) {
                            
                            var link = document.createElement('a');
                            link.innerHTML = item.d + ' (' + item.province + ')';
                            link.href = '#';
                            link.className = 'suggest-item';
                            link.style.display = 'block';
                            link.style.padding = '8px 15px';
                            link.style.textDecoration = 'none';
                            link.style.color = '#333';
                            link.style.backgroundColor = '#fff';
                            
                            link.addEventListener('mouseover', function() {
                                this.style.backgroundColor = '#f0f0f0';
                            });
                            
                            link.addEventListener('mouseout', function() {
                                this.style.backgroundColor = '#fff';
                            });

                            link.addEventListener('click', function(e) {
                                e.preventDefault();
                                searchInput.value = item.d;
                                
                                map.Search.search(item.w);
                                
                                fetch('proxy.php?keyword=' + item.w)
                                .then(response => response.json())
                                .then(data => {
                                    
                                    const dataDetail = data; // เก็บค่าไว้ในตัวแปรชื่อ dataDetail
                                    document.getElementById('locationInput').value = dataDetail.data[0].name;
                                    document.getElementById('latitude').value = dataDetail.data[0].lat;
                                    document.getElementById('longitude').value = dataDetail.data[0].lon;
                                    document.getElementById('addressInput').value = dataDetail.data[0].address;
                                    
                                    const addressParts = dataDetail.data[0].address.split(' ');
                                    const provinceParts = addressParts[addressParts.length - 2].split('.');
                                    if (addressParts[addressParts.length - 2].includes('.')) {
                                        
                                        var province_value = provinceParts[provinceParts.length - 1];
                                       
                                        document.getElementById('provinceSelect').value = province_value;
                                        updateProvinceInfo(province_value);
                                        map.Event.bind('click', function() {
                                            map.Overlays.clear();
                                            var mouseLocation = map.location(longdo.LocationMode.Pointer);
                                            document.getElementById('latitude').value = mouseLocation.lat;
                                            document.getElementById('longitude').value = mouseLocation.lon;
                                            map.Overlays.add(new longdo.Marker(mouseLocation));
                                            
                                        });         
                                    }else{
                                        var provincePart = provinceParts[0];
                                        document.getElementById('provinceSelect').value = provincePart;
                                        updateProvinceInfo(provincePart);
                                        
                                    }
                                    
                                })
                                .catch(error => console.error('Error:', error));
                                
                                
                                map.zoom(15);
                                
                                suggestDiv.style.display = 'none';
                            });
                            
                            suggestDiv.appendChild(link);
                        });
                        
                        
                    }
                });
            });
        });

        // ฟังก์ชันสำหรับเคลียร์ข้อมูลใน modal
        function clearModalData() {
            document.getElementById('searchInput').value = ''; // เคลียร์ช่องค้นหา
            document.getElementById('suggest').innerHTML = ''; // เคลียร์ผลลัพธ์การแนะนำ
            document.getElementById('suggest').style.display = 'none'; // ซ่อนผลลัพธ์การแนะนำ
            map.Overlays.clear(); // เคลียร์มาร์กเกอร์บนแผนที่
        }

        // จับเหตุการณ์เมื่อ modal ถูกปิด
        var mapModal = document.getElementById('mapModal');
        mapModal.addEventListener('hidden.bs.modal', function () {
            clearModalData();
        });



       
    </script>
</body>
</html>