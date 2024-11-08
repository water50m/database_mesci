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
$result = $query->selectAllDetail($type);
$id = $result['id'];

$fucn_query = $query->selectFacuty();
$region = $query->selectRegion();

$num_receive_per_year = $query->selectAllreceive_year($id );
$func_province = $query->selectProvince();
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
    <form  id="myForm" method="POST">
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
                </label>

                <div class="input-group">
                    <?php 
                    echo '<input type="text"  value='.$result['location'].' class="form-control" aria-label="Text input" name="_loName">';
                    echo '<input type="text" style="display: none;" value='.$result['id'].' class="form-control" aria-label="Text input" name="_location">';
                    ?>
                </div>
            </div>

        <div class="mb-3">
            <h5  class="form-label">จังหวัด</h5>
            <select class="form-select" aria-label="Default select example" name="_province">
                <option value="noselect" selected>เลือกจังหวัด</option>
                <?php 
                        echo '<option selected value='.$result['id'].'>'.$result['province'].'</option>';
                 ?>
                <?php foreach ($func_province as $province): ?>
                    <?php if($province['province_name'] == $result['province']){ }else{?>
                    <option value="<?php echo $province['province_id']; ?>">
                        <?php echo $province['province_name']; ?>
                    </option>
                <?php }endforeach; ?>
            </select>
        </div>
        

        <div class="mb-3">
            <h5 class="form-label">พิกัด</h5>
            <div class="input-group">
                    
                <?php
                    echo '<input type="text" value="'.$result['latitude'].'" class="form-control" aria-label="Latitude" placeholder="ละติจูด" name="_latitude">';
                    echo '<input type="text" value="'.$result['longtitude'].'" class="form-control" aria-label="Longitude" placeholder="ลองจิจูด" name="_longitude">';
                ?>
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

        <div class="mb-3">
            <h5 class="form-label">สาขาวิชา</h5>
            <select class="form-select" aria-label="Default select example" name="_faculty">
                <option selected>เลือกสาขาวิชา</option>
                <?php 
                        echo '<option selected value='.$result['fid'].'>'.$result['majorName'].'</option>';
                 ?>
                <?php foreach ($fucn_query as $faculty): ?>
                    <?php if($faculty['f_major'] == $result['majorName']){ }else{?>
                    <option value="<?php echo $faculty['fid']; ?>">
                        <?php echo $faculty['f_major']; ?>
                    </option>
                <?php }endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <h5 class="form-label">ภูมิภาค</h5>
            <select class="form-select" aria-label="Default select example" name="_region">
                <option selected>เลือกภูมิภาค</option>
                <?php 
                        echo '<option selected value='.$result['rid'].'>'.$result['regionName'].'</option>';
                 ?>
                <?php foreach ($region as $_region): ?>
                    <?php if($_region['id'] == $result['rid']){ }else{?>
                    <option value="<?php echo $_region['id']; ?>" >
                        <?php echo $_region['name']; ?>
                    </option>
                <?php }endforeach; ?>
            </select>
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
        
        <!-- <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn btn-danger btn-lg w-100 me-2" onclick="deletedata('config/delete.php')" role="button">ลบ</button>
            <button type="submit" class="btn btn-warning btn-lg w-100 ms-2" onclick="modifydata('config/modify_datadb.php')" role="button">แก้ไข</button>
        </div> -->

        <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn btn-danger" onclick="deletedata('config/delete.php')" role="button">ลบ</button>
            <button type="submit" class="btn btn-warning" onclick="modifydata('config/modify_datadb.php')" role="button">แก้ไข</button>
        </div>

        <div class="input-group mb-3" style="padding: 50px;">
    
        </div>
    </form>

<script>
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
</script>
</div>
</div>
</body>
</html>