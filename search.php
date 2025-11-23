<?php 
session_start();
require 'config/querySQL.php';
$query = new SQLquery();
$fucn_query = $query->selectFacuty();

$jsonDataFacuty = json_encode($fucn_query);
$region = $query->selectRegion();
$establishment= $query->establishment();
$facuty_select = $query->facutyTable();
$Year = $query->selectYearFromReceiveTable();

if (isset($_GET['func']) && $_GET['func'] == 3 ) {
    require 'config/fetchdata.php';
    $locationName = isset($_GET['type']) ? $_GET['type'] : null;
    $datafrommap = func3($locationName);
    
}

$isUserLoggedInAndBig =  'false';

if(isset($_SESSION['DoYouKnowImSoBig']) ){
    $isUserLoggedInAndBig = 'true';
    
}
header('Cache-Control: public, max-age=3600'); // Cache for 1 hour
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship-Medsci</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/page_search2.js"></script>
    <link href="css/table_inSearch_page.css" rel="stylesheet">
    
</head>

<body id = myBody>
  <script>
        const body = document.getElementById("myBody");
        body.style.backgroundImage = "url('images/IMG_1216.JPG')";
        body.style.backgroundSize = "100% auto"; // ปรับตามความกว้างเท่านั้น
        body.style.backgroundRepeat = "no-repeat";
        body.style.backgroundPosition = "center top"; // หรือ "center top"
        body.style.fontFamily = "Arial, sans-serif";
  </script>
      

                              
        <?php include 'navbar.php'; ?>
       <!-- การ์ด -->
    <div class="container">
       <div class='container-welcome-title'>
        <div class='title-welcome mt-4'>
            <h1>ระบบฐานข้อมูลฝึกงาน</h1>
            <p>คณะวิทยาศาสตร์การแพทย์</p>
            <p>มหาวิทยาลัยนเรศวร</p>
        </div>
        <div class="rectangle-container">
            <?php $i = 1; 
                    $colorMap = [
                                    'พยาธิวิทยากายวิภาค' => '#7ecf9d',   // medium pastel green
                                    'วิทยาศาสตร์การแพทย์' => '#6ec5e9',   // medium pastel blue
                                    'จุลชีววิทยา' => '#ffba66',            // medium pastel orange
                                    'ชีวเคมีและชีววิทยาโมเลกุล' => '#c59ce6', // medium pastel violet
                                ];


                        foreach ($fucn_query as $facuty) {
                            if ($i > 6){
                                break;
                            }
                                $bgColor = isset($colorMap[$facuty['f_major']]) ? $colorMap[$facuty['f_major']] : 'lightgray';
                                echo "<a onclick='fetchData(this)' class='rectangle'  style='background-color: $bgColor;  '
                                value='".htmlspecialchars($facuty['fid'])."'><h3>".htmlspecialchars($facuty['f_major']).
                                "</h3><p>รับแล้ว ".htmlspecialchars($facuty['total'])." ตำแหน่ง</p></a> "    ;
                                $i++;
                                }
            ?>                
        </div>
       </div>
<div class='containersearch mx-auto'>

    <div class="eachrow">
    
    <div class="card-body p-0 mx-auto">
                <form id="search-form "  onsubmit="handleSubmit(event)">
                    <div class="row g-3 ">
                         
                        
                            <div class="search-bar col-12 col-md-6 col-lg-4 ">
                                    <input type="text" class="form-control" id="location" placeholder="ชื่อสถานที่..." aria-label="Text input">
                                
                            </div>
                            <div class="search-bar col-12 col-md-6 col-lg-4 mx-auto">
                                    <select class="form-control mw-100" id="regionSelect">
                                        <option  value="allr" >ภูมิภาค(ทั้งหมด)</option>
                                        <?php 
                                            foreach ($region as $row) {
                                                
                                                echo "<option value='".$row['name']."'>".$row['name']."</option>";
                                             }
                                        ?>

                                    </select>
                        
                            </div>
                                
                               
                            <div class="search-bar col-12 col-md-6 col-lg-4 ">
                                    <select class="form-control" id="establishment" >
                                        <option value="allf">ประเภทสถานประกอบการ(ทั้งหมด)</option>
                                        <?php  

                                        foreach ($establishment as $estab) {
                                           
                                                
                                                echo "<option value='".$estab['id']."'>".$estab['establishment']."</option>";
                                            
                                        }
                                        ?>
                                    </select>
                        
                                </div>
                                <div class="search-bar col-12 col-md-6 col-lg-4 ">
                                    <select class="form-control select-branch" id="branchSelect">
                                        <option value="allp">สาขาวิชา(ทั้งหมด)</option>
                                        <?php  
                                        foreach ($facuty_select as $fact) {
                                           
                                                
                                                echo "<option value='".$fact['id']."'>".$fact['major_subject']."</option>";
                                            
                                        }
                                        ?>
                                    </select>
                        
                                </div>

                                <div class="search-bar col-12 col-md-6 col-lg-4 ">
                                    <select class="form-control select-branch" id="yearSelect">
                                        <option value="all">ปีการศึกษา(ทั้งหมด)</option>
                                        <?php  
                                        foreach ($Year as $Y) {
                                                print_r($Y);
                                                
                                                echo "<option value='".$Y['year']."'>".$Y['year']."</option>";
                                            
                                        }
                                        ?>
                                    </select>
                        
                                </div>
                                <div class="search-bar col-12 col-md-6 col-lg-4 ">
                                    <select class="form-control select-branch" id="semesterSelect">
                                        <option value="all">ภาคการศึกษา(ทั้งหมด)</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
  
                                    </select>
                        
                                </div>
 
                        
                        <button type="submit" class="btn btn-base search-bar-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>  
                            

                        
                    </div>
                </form>
            </div> 
    </div>
    </div>
    </div>


              <!-- ได้รับคำสั่งจากหน้า นี้ -->                              
    <div class="eachrow w-100 mx-auto" id="detail_internship">
        <?php try { 
            if(isset($datafrommap) && $datafrommap) { 
                foreach($datafrommap as $data) {  ?>
                    <form action="modify_data.php" method="POST">
                        <div class="button-modify">
                            <button class="button-20 form-control" role="button">แก้ไข</button>
                        </div>
                        <input type="text" id="inputGroupFile01" name="_id" value="<?php echo $data['id']; ?>" style="display: none;">
                    </form>
                    <div class="card" onclick='handleCardClick(<?php echo json_encode($data); ?>)'>
                        <div class="row g-0 align-items-center">
                        <div class="col-md-6 ps-5">
                                <div class="avatar">
                                    <?php if(!empty($data['picture_path'])): ?>
                                        <img src="<?php echo $data['picture_path']; ?>" class="img-fluid" alt="รูปภาพสถานที่ฝึกงาน">
                                    <?php else: ?>
                                        <img src="images/Medscinu-01.png" class="img-fluid" alt="ไม่มีรูปภาพ">
                                    <?php endif; ?>
                                </div>
                                <div class="info"> 
                                    <p class="title"><?php echo $data['location'];  ?></p>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="detail">
                                    <h5>ประเภทสถานประกอบการ:</h5>
                                    <p> <?php echo $data['establishment']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="detail">
                                    <h5>สาขาวิชา:</h5>
                                    <p><?php echo $data['majorName']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            }
        } catch(Exception $e) {
            echo "";
        } ?>
    </div>
    
 
 

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="modal-name">Details</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class=text-modal-card>
            <p id="modal-major-subject">สาขาวิชา:</p>
            <p id="modal-province">จังหวัด:</p>
            <p id="modal-scope-work"></p>
             <p id="modal-department">แผนก:</p>
             <p id="modal-region">ภูมิภาค:</p>
            
            
            </div>
            <div class="table-recieve" id="table-recieve"></div>
            

          </div>
          <div class="modal-footer">
            
                <form id="modalForm" action="modify_data.php"  method="POST" target="_blank">
                <input type="text" id="modal_id" name="_id" style="display: none;">
                <input type="text" id="modal_location_id" name="location_id" style="display: none;">
                <?php if(isset($_SESSION['DoYouKnowImSoBig'])): ?>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-edit"></i> แก้ไข
                </button>
                <?php endif;   ?>
              </form>
            
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    
    <script>
    const isUserLoggedIn = <?php echo $isUserLoggedInAndBig; ?>;
         
    var dataFromMap = <?php echo isset($datafrommap[0]) ? json_encode($datafrommap[0]) : 'null'; ?>;
    if (dataFromMap){
    document.getElementById('detail_internship').scrollIntoView({ behavior: 'smooth' });
    handleCardClick(dataFromMap);
}else{
    
}




document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
    deleteTable(); // ลบตารางเมื่อปิด modal
});

        var facuty = <?php echo $jsonDataFacuty; ?>;
        

            </script>
        


    </body>
</html>
