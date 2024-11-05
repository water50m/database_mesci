<?php 

require 'config/querySQL.php';
$query = new SQLquery();
$fucn_query = $query->selectFacuty();
$region = $query->selectRegion();




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/addData.css">

    
    <title>เพิ่มข้อมูลใหม่</title>
    <style>

    </style>
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
                <div class="input-group mb-3">
                <label class="input-group-text" for="inputGroupFile01">รูปภาพ</label>
                <input type="file" class="form-control" id="inputGroupFile01" name="_image">
                </div>
        
        <div class="mb-3">
            <h5  class="form-label">แผนก</h5>
            <div class="input-group">
                <input type="text" class="form-control" aria-label="Text input" name="_department">
            </div>
        </div>

        <div class="mb-3">
            <h5 class="form-label">สาขาวิชา</h5>
            <select class="form-select" aria-label="Default select example" name="_faculty">
                <option value="noselect" selected>เลือกสาขาวิชา</option>
                <?php foreach ($fucn_query as $faculty): ?>
                    <option value="<?php echo $faculty['fid']; ?>">
                        <?php echo $faculty['f_major']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <h5 class="form-label">ภูมิภาค</h5>
            <select class="form-select" aria-label="Default select example" name="_region">
                <option value="noselect" selected>เลือกภูมิภาค</option>
                <?php foreach ($region as $_region): ?>
                    <option value="<?php echo $_region['id']; ?>" >
                        <?php echo $_region['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
         </div>

        <div class="mb-3">
            <h5  class="form-label">ที่อยู่</h5>
            <div class="input-group">
                <textarea class="form-control" id="floatingTextarea1" style="height: 100px" name="_address"></textarea>
                
            </div>
        </div>

        <div class="mb-3">
            <h5  class="form-label">เรียน</h5>
            <div class="input-group">
            <textarea class="form-control"  id="floatingTextarea2" style="height: 100px" name="_sendto"></textarea>
            
            </div>
        </div>


        <div class="mb-3">
            <h5  class="form-label">ผู้ประสานงาน</h5>
            <div class="input-group">
                <input type="text" class="form-control" aria-label="Text input" name="_coordinator">
            
            </div>
        </div>


        <div class="mb-3">
            <h5  class="form-label">ขอบข่ายงาน</h5>
            <div class="input-group">
            <textarea class="form-control"  id="floatingTextarea3" style="height: 100px" name="_scope"></textarea>
            
            </div>
        </div>

        
        <h5  class="form-label">จำนวนที่รับ</h5>
        <div class="input-group mb-3">
            <!-- <select class="form-select" id="button-addon1" aria-label="Default select example">
            <option selected>ภาคการศึกษา</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">ฤดูร้อน</option>
            </select> -->
            
            <span class="form-control" >
                ภาคการศึกษาที่ 1
            </span>
            <input type="number" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input" name="_year1">
            <input type="number" class="form-control" placeholder="รับ...คน"aria-label="Text input" name="_count1">
        </div>

        <div class="input-group mb-3">
            <span class="form-control" >
                ภาคการศึกษาที่ 2
            </span>
            <input type="number" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input" name="_year2">
            <input type="number" class="form-control" placeholder="รับ...คน" aria-label="Text input" name="_count2">
        </div>
        
        <button type="submit" class="button-3" role="button">Submit</button>

        <div class="input-group mb-3" style="padding: 50px;">
    
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



</div>
</div>
</body>
</html>