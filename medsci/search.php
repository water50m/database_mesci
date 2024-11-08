<?php 
session_start();
if(!isset($_SESSION['whoareyou']) ){
    header("location: login.php");
    exit();
}
require 'config/querySQL.php';
$query = new SQLquery();
$fucn_query = $query->selectFacuty();
$region = $query->selectRegion();
if (isset($_GET['func']) && $_GET['func'] == 3 ) {
    require 'config/fetchdata.php';
    $id = isset($_GET['type']) ? $_GET['type'] : null;
    $datafrommap = func3($id)[0];
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card with Modal Example</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/page_search2.js"></script>

</head>
<body>
<div class="search-bar">
                              
        <?php include 'navbar.php'; ?>
       <!-- การ์ด -->

       <div class='container-welcome-title'>
        <div class='title-welcome'>
            <h1>ระบบฐานข้อมูลฝึกงาน</h1>
            <p>คณะวิทยาศาสตร์การแพทย์</p>
            <p>มหาวิทยาลัยนเรศวร</p>
        </div>
        <div class="rectangle-container">
            <?php foreach ($fucn_query as $facuty) {
                    echo "<a onclick='fetchData(this)' class='rectangle' value='".htmlspecialchars($facuty['fid'])."'><h3>".htmlspecialchars($facuty['f_major'])."</h3><p>รับแล้ว ".htmlspecialchars($facuty['total'])." ตำแหน่ง</p></a>";
                    }
                ?>                
        </div>
       </div>
<div class='containersearch'>

    <div class="eachrow">
    
    <div class="card-body p-0">
                <form id="search-form" onsubmit="handleSubmit(event)">
                    <div class="row">
                        <style>
                           
                        </style>
                        <div class="search-bars">
                            <div class="search-bar">
                                    
                            <input type="text" class="form-control" id="location" placeholder="สถานที่..." aria-label="Text input">
                                    </div>
                                <div class="search-bar">
                                    <select class="form-control" id="regionSelect">
                                        <option  value="allr" >ภูมิภาค(ทั้งหมด)</option>
                                        <?php 
                                            foreach ($region as $row) {
                                                
                                                echo "<option value='".$row['name']."'>".$row['name']."</option>";
                                             }
                                        ?>

                                    </select>
                        
                                </div>
                                
                               
                                <div class="search-bar">
                                    <select class="form-control" id="departmentSelect">
                                        <option value="allp">ภาควิชา(ทั้งหมด)</option>
                                        <?php 

                                        ?>
                                    </select>
                        
                                </div>
                                <div class="search-bar">
                                    <select class="form-control select-branch" id="branchSelect">
                                        <option value="allp">ด้านการฝึกงาน(ทั้งหมด)</option>
                                        <?php 
                                            foreach ($fucn_query as $facuty) {
                                                echo "<option  value='".$facuty['f_major']."' >".$facuty['f_major']."</option>";
                                             }
                                        ?>

                                    </select>
                        
                                </div>
                                <!-- <div class="search-bars">
                                    <input type="text" placeholder="ชื่อ หรือ รหัสนิสิต" class="form-control" id="search" name="search">
                                    
                                </div> -->
                                
                                
                        </div>
                        <button type="submit" class="btn btn-base search-bar-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>  
                            

                        
                    </div>
                </form>
            </div>
    </div>

              <!-- ได้รับคำสั่งจากหน้า นี้ -->                              
    <div class=eachrow id="detail_internship">
        <!-- ได้รับคำสั่งจากหน้า map -->
    <?php if($datafrommap){ ?>
        <form action="modify_data.php" method="POST">
                        <div class="button-modify">
                        <button class="button-20 form-control" role="button">แก้ไข</button>
                        </div>
                        <input type="text" id="inputGroupFile01" name="_id" value=<?php echo $datafrommap['id'] ?> style="display: none;" >
                        </form>
                    <div class="card" onclick='handleCardClick(<?php echo json_encode($datafrommap); ?>)'>
                        
                        <div class="row">
                            <div class="col">
                            <div class="avatar"></div>
                                <div class="info">
                                    <p class="title"><?php echo $datafrommap['location'] ?></p>
                                    
                                </div>
    
                            </div>
                            <div class="col">
                                <div class="detail">
                                    <p>ด้าน: <?php echo $datafrommap['majorName'] ?></p>
                                <p>แผนก: <?php echo $datafrommap['department'] ?></p>
                                
                                </div>
                            </div>
                            <div class="col">
                                <div class="detail">
                                    <p><?php echo $datafrommap['regionName']?></p>
                                </div>
                            </div>
                        </div>
                    </div>
        


        <?php }?>
</div>
    
  </div>
 

 
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Details</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class=text-modal-card>
            <h5 id="modal-name">ชื่อ:</h5>
            
            <p id="modal-department">แผนก:</p>
            <p id="modal-major">ด้าน:</p>
            <p id="modal-region">ภูมิภาค:</p>
            <p id="modal-scope-work"></p>
            
            </div>

            <div class="table"></div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Apply Now</button> -->
          </div>
        </div>
      </div>
    </div>
    <style>
        .text-modal-card,.modal-title {
    color: #333;
}
    </style>
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    
    <script>
        //modal control
        function handleCardClick(data) {
            
            document.getElementById('modal-name').innerText = data.location;
            document.getElementById('modal-major').innerText ='ด้าน: ' + data.majorName;
            document.getElementById('modal-department').innerText = 'แผนก: '+ data.department;
            document.getElementById('modal-region').innerText ='ภูมิภาค: ' +data.regionName;
            document.getElementById('modal-scope-work').innerText = 'ขอบข่ายงาน: ' +data.Scope_work;

            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        }
        var dataFromMap = <?php echo isset($datafrommap) ? json_encode($datafrommap) : 'null'; ?>;
        console.log(dataFromMap)
        if (dataFromMap){
            document.getElementById('detail_internship').scrollIntoView({ behavior: 'smooth' });
            handleCardClick(dataFromMap);
        }
      //แสดงจังหวัดจากฐานข้อมูล  

    // หรือทำงานอื่นๆ ที่ต้องการ เช่น ส่งข้อมูลไปยัง server

</script>
</body>
</html>
