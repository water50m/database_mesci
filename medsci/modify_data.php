<?php 
session_start();
if(!isset($_SESSION['whoareyou']) ){
    
    header("location: login.php");
    exit();
}
require 'config/querySQL.php';
$query = new SQLquery();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['_id'];
    
}

$gettype =  isset($_GET['type']) ? $_GET['type'] : '';
if($gettype){
    $type = $gettype;
}
if(!$type){
    header("location: search.php");
    exit();
}
$result = $query->selectAllDetail($type,null,null);
$jsonResult = json_encode($result);
$id = $result['id'];
$fucn_query = $query->selectFacuty();

$jsonDataFacuty = json_encode($fucn_query);
$region = $query->selectRegion();

$num_receive_per_year = $query->selectAllreceive_year($id );
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

                <div class="input-group">
                    <?php 
                    echo '<textarea type="text"  value='.$result['location'].' class="form-control" aria-label="Text input" name="_loName">'.$result['location'].'</textarea>';
                    echo '<input type="text" style="display: none;" value='.$result['id'].' class="form-control" aria-label="Text input" name="_location">';
                    ?>
                </div>
            </div>
            <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile01">รูปภาพ</label>
                    <input type="file" class="form-control" id="inputGroupFile01" name="picture_" accept="image/*">
                </div>
           

        <div class="mb-3">
            <h5 class="form-label">คณะ</h5>
            <div class="input-group">
                <select class="form-select" aria-label="Default select example" id="facultyName_select"  name="_facultyname">
                    <option selected>เลือกคณะ</option>
                    <?php 
                        echo '<option selected value='.$result['facuty'].'>'.$result['facuty'].'</option>';
                    ?>
                    <?php
                        $shown_faculties = [];
                        foreach ($fucn_query as $faculty):
                            if (!in_array($faculty['facuty'], $shown_faculties)):
                                
                                if ($result['facuty'] ==$faculty['facuty']){}else{
                                $shown_faculties[] = $faculty['facuty'];
                        ?>
                                <option value="<?php echo $faculty['facuty']; ?>">
                                    <?php echo $faculty['facuty']; ?>
                                </option>
                        <?php
                                }endif;
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
            <h5 class="form-label">พิกัด <a href="#" onclick="resetLocationData(); return false;">default</a></h5>
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
                echo '<textarea class="form-control"  id="floatingTextarea2" style="height: 100px" name="_sendto">'.$result['sendto'].'</textarea>';
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
                echo '<textarea class="form-control"  id="floatingTextarea2" style="height: 100px" name="_scope">'.$result['Scope_work'].'</textarea>';
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
            <button type="submit" class="btn btn-success w-100 mx-2" onclick="saveNewLocation('config/addDatadb.php?newlocation=<?php echo $result['location']; ?>')" role="button">บันทึกเป็นสถานที่ใหม่</button>
            <button type="submit" class="btn btn-warning w-100 ms-2" onclick="modifydata('config/modify_datadb.php')" role="button">แก้ไข</button>
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
</div>
<script>
    function saveNewLocation(action) {
        const form = document.getElementById('myForm');
        form.action = action;
        form.submit();
    }
    function modifydata(action) {
        const form = document.getElementById('myForm');
        form.action = action;
        form.submit();
    }
    function deletedata(action) {
        const isConfirmed = confirm("คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?");
        
        // หากผู้ใช้กด "OK" ให้ดำเนินการส่งฟอร์ม
        if (isConfirmed) {
            const form = document.getElementById('myForm');
            form.action = action;
            form.submit();
        }
    }
     function handleSelectChange(selectElement) {
        const selectedValue = selectElement.value; // ค่าที่เลือก 
        
        const numReceivePerYear = <?php echo json_encode($num_receive_per_year); ?>;
        if (Array.isArray(numReceivePerYear)) {
            numReceivePerYear.forEach(item=>{
            if (selectedValue == item['id']){
                document.getElementById('countInput').value = item['received'];
                document.getElementById('_term1').value = item['term'];
                document.getElementById('_year1Input').value = item['year'];
                document.getElementById('_term1_before').value = item['term'];
                document.getElementById('_year1_before').value = item['year'];
            }if(selectedValue == 'dontChange'){
                document.getElementById('countInput').value = 'รับ...คน';
                document.getElementById('_term1').value = 'ภาคการศึกษาที่...';
                document.getElementById('_year1Input').value = 'ปีการศึกษา...';
            }
        })
        } else {
            console.error("numReceivePerYear ไม่ใช่อาร์เรย์", numReceivePerYear);
        }
        // console.log(numReceivePerYear)    
    };

    var result = <?php echo json_encode($result); ?>;
    
    var facuty = <?php echo $jsonDataFacuty; ?>;
        var facultySelect = document.getElementById('facultyName_select');
        var majorSelect = document.getElementById('facultyMajor');

        // ฟังก์ชันสำหรับอัพเดทสาขาวิชา
        function updateMajors(selectedFaculty) {
            // เคลียร์ตัวเลือกเก่า
            majorSelect.innerHTML = '<option value="" selected>เลือกสาขาวิชา</option>'+'<option value="' + result.majorName + '" selected style="background-color: yellow;">' + result.majorName + '</option>';
            
            // กรองและเพิ่มสาขาที่ตรงกับคณะ
            facuty.forEach(function(faculty) {
                if(faculty.facuty === selectedFaculty && faculty.f_major !== '' && faculty.f_major !== result.majorName) {
                    const option = document.createElement('option');
                    option.value = faculty.f_major;
                    option.text = faculty.f_major;
                    majorSelect.appendChild(option);
                }
            });
        }

        // เรียกใช้ฟังก์ชันทันทีที่โหลดหน้า
        updateMajors(facultySelect.value);

        // เพิ่ม event listener สำหรับการเปลี่ยนแปลง
        facultySelect.addEventListener('change', function() {
            updateMajors(this.value);
        });

        // smart select province
        var province = <?php echo $jsonDataProvince; ?>;
        
        var provinceSelect = document.getElementById('provinceSelect');
        var regionSelect = document.getElementById('regionSelect');
        
        provinceSelect.addEventListener('change', function() {
            // ดึงค่าจังหวัดที่เลือกปัจจุบัน
            var selectedprovince_value = this.value;
            
            // กรองและเพิ่มภูมิภาคที่ตรงกับจังหวัด
            var foundRegion = false;
            province.forEach(function(prov) {
                if(prov.province_name === selectedprovince_value && !foundRegion) {
                    document.getElementById('latitude').value = prov.latitude;
                    document.getElementById('longitude').value = prov.longitude;
                    document.getElementById('regionSelect').value = prov.region_id;
                    document.getElementById('regionShow').placeholder = prov.region_name;
                }
            });
        });

    function resetLocationData() {
        // รีเซ็ตค่าพิกัด
        document.getElementById('latitude').value = '<?php echo $result['latitude']; ?>';
        document.getElementById('longitude').value = '<?php echo $result['longtitude']; ?>';
        
        // รีเซ็ตค่าจังหวัด
        document.getElementById('provinceSelect').value = '<?php echo $result['province']; ?>';
        
        // รีเซ็ตค่าภูมิภาค
        document.getElementById('regionSelect').value = '<?php echo $result['regionName']; ?>';
    }
</script>

</body>
</html>