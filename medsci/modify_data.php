<?php
 
session_start();
if(!isset($_SESSION['whoareyou']) ){
    
    header("location: login.php");
    exit();
}
require 'config/querySQL.php';
$query = new SQLquery();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['location_id'];
    $major_id = $_POST['_id'];
    
}

$gettype =  isset($_GET['type']) ? $_GET['type'] : '';
if($gettype){
    $type = $gettype;
    $major_id = $_GET['type2'];
    print($_GET['type2']);
}

if(!$type){
    header("location: search.php");
    exit();
}

$majorSubjectName = $query->selectMajorSubjectName($major_id);

$result = $query->selectAllDetail($type,null,null);
$jsonResult = json_encode($result);
$id = $result['id'];
$fucn_query = $query->selectFacuty();

$jsonDataFacuty = json_encode($fucn_query);
$region = $query->selectRegion();

$num_receive_per_year = $query->selectAllreceive_year($id );
$func_province = $query->selectProvince();
$jsonDataProvince = json_encode($func_province);
$facuty_select = $query->facutyTable();
$establishment= $query->establishment();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/addData.css">
    <link rel="stylesheet" href="css/autoMap.css">
    <link rel="stylesheet" href="css/modify_data.css">
    <script src="https://api.longdo.com/map/?key=bff66f6baa485edba09ca806b597ed30"></script>
    <title>แก้ไขข้อมูล</title>

</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container2">
    <form  id="myForm" method="POST" enctype="multipart/form-data">
        <div class="box">
            <div class="mb-3">
            <div class="avatar">
                    <?php if(!empty($result['picture_path'])): ?>
                        <img src="<?php echo $result['picture_path']; ?>" class="img-fluid" alt="รูปภาพสถานที่ฝึกงาน">
                    <?php else: ?>
                        <img src="images/Medscinu-01.png" class="img-fluid" alt="รูปภาพเริ่มต้น">
                    <?php endif; ?>
                </div>
                <label class="form-label">
                
                    
                    ชื่อสถานที่ฝึกงาน                    
                </label>
                <!-- แผนที่ -->
                <a href="#" id="openPopupBtn">ค้นหาในแผนที่</a> 
                <!-- ^^^แผนที่่^^^ -->
                <a href="#" onclick="resetLocationData(); return false;">default</a>
                <div class="input-group">
                    <?php 
                    echo '<textarea id="locationInput" type="text"  value='.$result['location'].' class="form-control" aria-label="Text input" name="_loName">'.$result['location'].'</textarea>';
                    echo '<input type="text" style="display: none;" value='.$result['id'].' class="form-control" aria-label="Text input" name="_location">';
                    ?>
                </div>
            </div>
            <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile01">รูปภาพ</label>
                    <input type="file" class="form-control" id="inputGroupFile01" name="picture_" accept="image/*">
                </div>
           

        <div class="mb-3">
            <h5 class="form-label">ประเภทสถานประกอบการ</h5>
            <div class="input-group">
                <select class="form-select" aria-label="Default select example" id="facultyName_select"  name="_establishment">

                    <?php 
                    if (isset($result['establishment'])){
                        echo '<option selected value='.$result['eid'].'>'.$result['establishment'].'</option>';
                    }else{
                        echo '<option selected value=null>เลือก</option>';
                    }
                    ?>
                    <?php
         
                        foreach ($establishment as $esta):
                                if ($result['eid'] ==$esta['id']){}else{
                        ?>

                                <option value="<?php echo $esta['id']; ?>">
                                    <?php echo $esta['establishment']; ?>
                                </option>
                        <?php
                                }
                        endforeach;
                        ?>
                </select>
                <button type="button" class="btn btn-outline-primary" style="width: 150px;"
                    data-bs-toggle="modal" data-bs-target="#addFacultyNameModal">
                    เพิ่มคณะ 
                </button>
            </div>
        </div>

        <div class="mb-3">
            <h5 class="form-label">สาขาวิชา</h5>
            <div class="input-group">
                <select class="form-select" aria-label="Default select example" id="facultyMajor" name="_facultymajor">
                <option selected>เลือกสาขาวิชา...</option>
                    <?php 
                    if (isset($major_id)){
                        echo '<option selected value='.$major_id.' style="background-color: #ffc107;">'.$majorSubjectName[0]['major_subject'].'</option>';
                    }else{?>
                        <option selected>เลือกสาขาวิชา...</option>
                        <?php 
                    }
                    ?>
                    ?>
                    <?php
         
                        foreach ($facuty_select  as $fac):
                                if ($major_id ==$fac['id']){}else{
                        ?>

                                <option value="<?php echo $fac['id']; ?>">
                                    <?php echo $fac['major_subject']; ?>
                                </option>
                        <?php
                                }
                        endforeach;
                        ?>

                </select>
                <button type="button" class="btn btn-outline-primary" style="width: 150px;"
                    data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                    เพิ่มสาขาวิชา
                </button>
            </div>
        </div>
        
        <div class="mb-3">
            <h5  class="form-label">แผนก</h5>
            <div class="input-group">
                <?php 
                if($result['department']){
                echo '<input type="text" value='.$result['department'] .' class="form-control" aria-label="Text input" name="_department">';
                }else{
                    echo '<input type="text"  class="form-control" aria-label="Text input" name="_department">';
                }
                ?>
               
            </div>
        </div>

        <div class="mb-3 d-flex align-items-center justify-content-between">
            <div class="me-3" style="flex: 1;">
                <h5 class="form-label">จังหวัด</h5>
                <select class="form-select" aria-label="Default select example" name="_province" id="provinceSelect">
                    <option value="noselect" selected>เลือกจังหวัด</option>
                    <?php 
                            echo '<option selected value='.$result['province'].' style="background-color: yellow;">'.$result['province'].'</option>';
                    ?>
                    <?php foreach ($func_province as $province): ?>
                        <?php if($province['province_name'] == $result['province']){ }else{?>
                        <option value="<?php echo $province['province_name']; ?>">
                            <?php echo $province['province_name']; ?>
                        </option>
                    <?php }endforeach; ?>
                </select>
            </div>
            <div style="flex: 1;">
                <h5 class="form-label">ภูมิภาค</h5>
                <input type="text" class="form-control" value="<?php echo $result['regionName']; ?>" aria-label="Default select example" readonly id="regionShow">
                <input type="hidden" name="_region" value="<?php echo $result['rid']; ?>" id="regionSelect">
            </div>
        </div>
        <div class="mb-3">
            
            <div class="input-group">
                    
                <?php
                    echo '<input type="number" step="0.000001" value="'.$result['latitude'].'" class="form-control" aria-label="Latitude" placeholder="ละติจูด" name="_latitude" id="latitude">';
                    echo '<input type="number" step="0.000001" value="'.$result['longtitude'].'" class="form-control" aria-label="Longitude" placeholder="ลองจิจูด" name="_longitude" id="longitude">';
                ?>
            </div>
        </div>
        <div class="mb-3">
            <h5  class="form-label">ที่อยู่</h5>
            <div class="input-group">
                <?php 
                if($result['address']){ echo '<textarea class="form-control" id="floatingTextarea2" style="height: 100px" name="_address">'.$result['address'].'</textarea>';
                }else{
                    echo '<textarea class="form-control" id="floatingTextarea2" style="height: 100px" name="_address"></textarea>' ;
                }              
                ?>                               
            </div>
        </div>

        <div class="mb-3">
            <h5 class="form-label">เรียน</h5>
            <div class="input-group">
                <?php
                echo '<textarea class="form-control"  id="sendto" style="height: 100px" name="_sendto">'.$result['sendto'].'</textarea>';
                ?>
            </div>
        </div>

        <div class="mb-3">
            <h5  class="form-label">ผู้ประสานงาน</h5>
            <div class="input-group">  
                <?php
                    echo '<input type="text" value="'.$result['coordinator'].'" class="form-control" aria-label="Text input" name="_coordinator">';
                ?>
            </div>
        </div>

        <div class="mb-3">
            <h5  class="form-label">ขอบข่ายงาน</h5>
            <div class="input-group">           
                <?php
                echo '<textarea class="form-control"  id="scopework" style="height: 100px" name="_scope">'.$result['Scope_work'].'</textarea>';
                ?>
            </div>
        </div>
        
        <h5  class="form-label">จำนวนที่รับ</h5>
        <div class="input-group mb-3">

            <input type="text" class="form-control"  value="แก้ไขจากปีที่มีในฐานข้อมูลแล้ว" ria-label="Text input" name="_count1" readonly>
            
            <select class="form-select" aria-label="Default select example" id='_year1'  onchange="handleSelectChange(this)">
                <option value='dontChange' selected>ภาคการศึกษา</option>
                <?php foreach ($num_receive_per_year as $_year){
                    echo '<option value="'.htmlspecialchars($_year['id']).'">'.htmlspecialchars($_year['term']).'/'.htmlspecialchars($_year['year']).'</option>';
                    }                    
                ?>
            </select>
            <input type="number" style="display: none;" class="form-control" placeholder="ภาคการศึกษาที่..." aria-label="Text input" id="_term1_before" name="_term1_before" >
            <input type="number" style="display: none;" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input" id="_year1_before" name="_year1_before" >           
            <input type="number" class="form-control" placeholder="ภาคการศึกษาที่..." aria-label="Text input" id="_term1" name="_term1" readonly ondblclick="this.removeAttribute('readonly'); this.style.cursor = 'text';" onblur="this.setAttribute('readonly', true); this.style.cursor = 'pointer'; " style="cursor: pointer;">
            <input type="number" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input" id="_year1Input" name="_year1" readonly ondblclick="this.removeAttribute('readonly'); this.style.cursor = 'text';" onblur="this.setAttribute('readonly', true); this.style.cursor = 'pointer'; " style="cursor: pointer;">
            <input 
            type="number" 
            class="form-control" 
            placeholder="รับ...คน" 
            aria-label="Text input" 
            id="countInput" 
            name="_receive" 
            readonly
            ondblclick="this.removeAttribute('readonly'); this.style.cursor = 'text';" 
            onblur="this.setAttribute('readonly', true); this.style.cursor = 'pointer'; " 
            style="cursor: pointer;"
        >
        </div>

        <div class="input-group mb-3">
            <input type="text" class="form-control"  value="เพิ่มปีการศึกษาใหม่" ria-label="Text input"  readonly>
            <input type="number" class="form-control" placeholder="ภาคการศึกษาที่..." aria-label="Text input" name="_term2">
            <input type="number" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input" name="_year2">
            <input type="number" class="form-control" placeholder="รับ...คน" aria-label="Text input" name="_count2">
        </div>
        


        <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn btn-danger w-100 me-2" onclick="deletedata('config/delete.php')" role="button">ลบ</button>
            
            <button type="submit" class="btn btn-warning w-100 ms-2" onclick="modifydata('config/modify_datadb.php')" role="button">บันทึกการแก้ไข</button>
        </div>

        <div class="input-group mb-3" style="padding: 50px;">
    
        </div>
    </form>

<!-- Modal ชื่อคณะ -->
<div class="modal fade" id="addFacultyNameModal" tabindex="-1" aria-labelledby="addFacultyNameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFacultyNameModalLabel">เพิ่มคณะ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="config\modify_datadb.php" method="POST">
                    <div class="mb-3">
                        <label for="facultyName" class="form-label">ชื่อคณะ</label>
                        <input type="text" class="form-control" id="facultyName" name="_addfacultyname1" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">เพิ่ม</button>
                    </div>
                    <?php echo '<input type="text" style="display: none;" value='.$result['id'].' class="form-control" aria-label="Text input" name="_location">'; ?>
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
                <form action="config\modify_datadb.php" method="POST">
                    <div class="mb-3">
                        <label for="facultyName" class="form-label">คณะ</label>
                        <select class="form-select" aria-label="เลือกสาขาวิชา" name="_addfacultyname2">
                            <option value="noselect" selected>เลือกคณะ</option>
                            <?php
                            $shown_faculties = []; // ตัวแปรเก็บค่า facuty ที่แสดงไปแล้ว

                            foreach ($fucn_query as $faculty):
                                if (!in_array($faculty['facuty'], $shown_faculties)): // ตรวจสอบว่าค่า facuty ยังไม่ได้แสดง
                                    $shown_faculties[] = $faculty['facuty']; // เพิ่มค่า facuty ลงในตัวแปร
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
                    <?php echo '<input type="text" style="display: none;" value='.$result['id'].' class="form-control" aria-label="Text input" name="_location">'; ?>
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
    
    var province = <?php echo $jsonDataProvince; ?>;
    const result = <?php echo json_encode($result); ?>;
    const numReceivePerYear = <?php echo json_encode($num_receive_per_year); ?>;
    var facuty = <?php echo $jsonDataFacuty; ?>;


    const defaultValue = '<?php echo $jsonResult; ?>';
    const defaultData = JSON.parse(defaultValue);
    
   
</script>
<script src="js/ModifyData.js"></script>
</body>
</html>