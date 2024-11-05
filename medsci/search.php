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
                                        <option value="allp">สาขาวิชา(ทั้งหมด)</option>
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


    <div class=eachrow id="detail_internship">
        
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
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Apply Now</button>
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
      //แสดงจังหวัดจากฐานข้อมูล  

    // หรือทำงานอื่นๆ ที่ต้องการ เช่น ส่งข้อมูลไปยัง server
;
</script>
</body>
</html>
