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
    <title>เพิ่มข้อมูลใหม่</title>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container2">
        <form action="config/addDatadb.php" method="POST">
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
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addlocation" style="margin-left: 8px;">+</a>
                    </label>

                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input" name="_location">
                    </div>   
                </div>

                <div class="mb-3">
                    <h5 class="form-label">พิกัด (หมุดจะปักไว้ที่เมืองหลวงของจังหวัดนั้นๆ กรณีที่ไม่ได้กำหนดพิกัด)</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Latitude" placeholder="ละติจูด" name="_latitude" id="latitude">
                        <input type="text" class="form-control" aria-label="Longitude" placeholder="ลองจิจูด" name="_longitude" id="longitude">
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">คณะ</h5>
                    <div class="input-group">
                        <select class="form-select" aria-label="เลือกสาขาวิชา" id="facultyName" name="_facultyname">
                            <option value="noselect" selected>เลือกคณะ</option>
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
                            เพิ่มคณะ 
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">สาขาวิชา</h5>
                    <div class="input-group">
                        <select class="form-select" aria-label="เลือกสาขาวิชา" name="_facultymajor" id="facultyMajor">
                            <option value="noselect" selected>เลือกสาขาวิชา</option>
                            
                        </select>
                        <button type="button" class="btn btn-outline-primary" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                            เพิ่มสาขาวิชา
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">แผนก</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input" name="_department">
                    </div>
                </div>

                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <div class="me-3" style="flex: 1;">
                        <h5 class="form-label">จังหวัด</h5>
                        <select class="form-select" aria-label="Default select example" name="_province" id="provinceSelect">
                            <option value="noselect" selected>เลือกจังหวัด</option>
                            <?php foreach ($func_province as $province): ?>
                                <option value="<?php echo $province['province_name']; ?>">
                                    <?php echo $province['province_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <h5 class="form-label">ภูมิภาค</h5>
                        <select class="form-select" aria-label="Default select example" name="_region" id="regionSelect">
                            <option value="noselect" selected>เลือกภูมิภาค</option>

                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">ที่อยู่</h5>
                    <div class="input-group">
                        <textarea class="form-control" id="floatingTextarea1" style="height: 100px" name="_address"></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">เรียน</h5>
                    <div class="input-group">
                        <textarea class="form-control" id="floatingTextarea2" style="height: 100px" name="_sendto"></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">ผู้ประสานงาน</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Text input" name="_coordinator">           
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="form-label">ขอบข่ายงาน</h5>
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

                <button type="submit" class="button-3" role="button">Submit</button>

                <div class="input-group mb-3" style="padding: 50px;"></div>
            </div>
        </form>

        <!-- Modal -->
        <div class="modal fade" id="addlocation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">เพิ่มสถานที่</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="form-label">ชื่อสถานที่</h5>
                        <input type="text" class="form-control" aria-label="Text input">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-primary">เพิ่ม</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal ชื่อคณะ -->
        <div class="modal fade" id="addFacultyNameModal" tabindex="-1" aria-labelledby="addFacultyNameModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFacultyNameModalLabel">เพิ่มคณะ</h5>
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
                        <h5 class="modal-title" id="addFacultyModalLabel">เพิ่มสาขาวิชา</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="config\addDatadb.php" method="POST">
                            <div class="mb-3">
                                <label for="facultyName" class="form-label">ภาควิชา</label>
                                <select class="form-select" aria-label="เลือกสาขาวิชา" id="facultyName" name="_addfacultyname2">
                                    <option value="noselect" selected>เลือกภาควิชา</option>
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

    </div>

    <script>
        var facuty = <?php echo $jsonDataFacuty; ?>;
        var facultySelect = document.getElementById('facultyName');
        var majorSelect = document.getElementById('facultyMajor');

        facultySelect.addEventListener('change', function() {
            // เคลียร์ตัวเลือกเก่า
            majorSelect.innerHTML = '<option value="noselect" selected>เลือกสาขาวิชา</option>';
            
            // ดึงค่าคณะที่เลือกปัจจุบัน
            var selectedFaculty_value = this.value;
            
            
            // กรองและเพิ่มสาขาที่ตรงกับคณะ
            facuty.forEach(function(faculty) {
                if(faculty.facuty === selectedFaculty_value && faculty.f_major !== '') {
                    const option = document.createElement('option');
                    option.value = faculty.f_major;
                    option.text = faculty.f_major;
                    majorSelect.appendChild(option);
                }
            });
        });

        var province = <?php echo $jsonDataProvince; ?>;
        var provinceSelect = document.getElementById('provinceSelect');
        var regionSelect = document.getElementById('regionSelect');

        provinceSelect.addEventListener('change', function() {
            // เคลียร์ตัวเลือกเก่า
            regionSelect.innerHTML = '<option value="noselect" selected>เลือกภูมิภาค</option>';   
            
            // ดึงค่าจังหวัดที่เลือกปัจจุบัน
            var selectedprovince_value = this.value;
            
            // กรองและเพิ่มภูมิภาคที่ตรงกับจังหวัด
            var foundRegion = false;
            province.forEach(function(prov) {
                if(prov.province_name === selectedprovince_value && !foundRegion) {
                    document.getElementById('latitude').value = prov.latitude;
                    document.getElementById('longitude').value = prov.longitude;
                    const option = document.createElement('option');
                    option.value = prov.region_id;
                    option.text = prov.region_name;
                    option.selected = true; // ตั้งค่าให้เลือกอัตโนมัติ
                    regionSelect.appendChild(option);
                    foundRegion = true; // ป้องกันการเพิ่มซ้ำ
                }
            });
        });
    </script>
</body>
</html>