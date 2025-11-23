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
$establishment= $query->establishment();
$facuty_select = $query->facutyTable();


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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container2">
        <form  id="myForm" method="POST" enctype="multipart/form-data">
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

                            <!-- แผนที่ -->
                        <a href="#" id="openPopupBtn">ค้นหาในแผนที่</a>
                            <!-- ^^^แผนที่่^^^ -->

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
                    <span class="input-group-text">ประเภทสถานประกอบการ</span>   
                        <select class="form-select" aria-label="ประเภทสถานประกอบการ" id="facultyName" name="_establishment">
                        <option value="allp">เลือก</option><!-- <option value="" selected>เลือกคณะ</option> -->
                            <?php

                            foreach ($establishment as $estb):
                               
                            ?>
                                    <option value="<?php echo $estb['id']; ?>">
                                        <?php echo $estb['establishment']; ?>
                                    </option>
                            <?php
                               
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
                            <option value="">กรุณาเลือกสาขาวิชา</option>
                            <?php
                            if(isset($facuty_select) && is_array($facuty_select)) {
                                foreach($facuty_select as $fact) {
                                    ?>
                                    <option value="<?php echo htmlspecialchars($fact['id']); ?>">
                                        <?php echo htmlspecialchars($fact['major_subject']); ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
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
                        <input type="number" step="0.000000001" class="form-control" aria-label="Latitude" placeholder="ละติจูด" name="_latitude" id="latitude">
                        <input type="number" step="0.000000001" class="form-control" aria-label="Longitude" placeholder="ลองจิจูด" name="_longitude" id="longitude">
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
                    <h5 class="form-label">ขอบข่ายงาน</h5>
                    <div class="input-group">
                        <textarea class="form-control" id="floatingTextarea3" style="height: 100px" name="_scope"></textarea>
                    </div>
                </div>

                <h5 class="form-label">
                    จำนวนที่รับ
                     <i class="bi bi-info-circle fs-6 ms-1"
                     tabindex="0"
                    data-bs-toggle="popover"
                    data-bs-trigger="focus"
                    data-bs-content="สามารถบัทึกข้อมูล 2 ภาคการศึกษาพร้อมกันได้ 
                                    ถ้าต้องการบันทึกเพียง 1 ภาคการศึกษาให้กรอกข้อมูลเฉพาะในส่วนของภาคการศึกษาที่ต้องการบันทึก และส่วนที่เหลือให้ปล่อยว่างไว้"
                    role="button"
                    style="cursor: pointer;"></i>
                    
                </h5>

                <div class="input-group mb-3">
                    <span class="form-control">
                        ภาคการศึกษาที่ 1
                    </span>
                    <input type="number" class="form-control" placeholder="ปีการศึกษา...XXXX" aria-label="Text input" name="_year1">
                    <input type="number" class="form-control" placeholder="รับ...คน" aria-label="Text input" name="_count1">
                </div>

                <div class="input-group mb-3">
                    <span class="form-control">
                        ภาคการศึกษาที่ 2
                    </span>
                    <input type="number" class="form-control" placeholder="ปีการศึกษา...XXXX" aria-label="Text input" name="_year2">
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
                                <label for="facultyName" class="form-label">ประเภทสถานประกอบการ(ใหม่)</label>
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

       
</div>
    <div class="control-map">
        <div class="popup-overlay" id="popupOverlay"></div>
        <div class="popup" id="popup">

            <div class="map-container">
                <div id="in-search">
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="ค้นหาสถานที่..." autocomplete="off">
                    </div>
                    <div id="results"></div>
                    <div id="suggest" class="suggest"></div>
                </div>
                <div id="map"></div>
            </div>
            <button id="closePopupBtn">ปิด</button>
        </div>
    </div>
    <script>
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
        var map;
        var openPopupBtn = document.getElementById('openPopupBtn');
        var closePopupBtn = document.getElementById('closePopupBtn');
        var popup = document.getElementById('popup');
        var popupOverlay = document.getElementById('popupOverlay');
        var suggest = document.getElementById('suggest');
        var search = document.getElementById('searchInput');
        var suggest = document.getElementById('suggest');
        var currentSuggestionIndex = -1;


       var facuty = <?php echo $jsonDataFacuty; ?>;
       var province = <?php echo $jsonDataProvince; ?>;
    </script>
    <script src="js/addData.js"></script>
</body>
</html>