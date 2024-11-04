<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="js/scripts.js"></script>
    <script src="js/page_search2.js"></script>
    <title>แก้ไขข้อมูล</title>
    <style>
        .container2 {
            
            justify-content: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-label {
            display: inline-flex;
            align-items: center;
        }

        .icon-container {
            display: inline-flex;
            align-items: center;
            background-color: #e0f7fa; /* สีพื้นหลังเบาๆ */
            border-radius: 50%; /* ให้เป็นวงกลม */
            padding: 4px;
            margin-right: 8px;
        }

        .size-3 {
            width: 24px; /* ปรับขนาดไอคอน */
            height: 24px;
            stroke: #00796b; /* สีขอบไอคอน */
        }

        .box {
            width: 100%;
            max-width: 1000px;
            
        }
        .modal h1,h5{
            color:#333;
        }
        input{
            color: rgba(255, 247, 204, 0.8);
        }
        .form-label {
            color:#333;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container2">
    
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

            <select id='location' class="form-select" aria-label="Default select example">
                <option velue='noSelect' selected>เลือกสถานที่</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>

            
        </div>

    <div class="mb-3">
        <h5  class="form-label">ที่อยู่</h5>
        <div class="input-group">
            <textarea id='address' class="form-control" id="floatingTextarea2" style="height: 100px"></textarea>
            
        </div>
    </div>

    <div class="mb-3">
        <h5  class="form-label">เรียน</h5>
        <div class="input-group">
        <textarea id='sender_to' class="form-control"  id="floatingTextarea2" style="height: 100px"></textarea>
        
        </div>
    </div>


    <div class="mb-3">
        <h5  class="form-label">ผู้ประสานงาน</h5>
        <div class="input-group">
            <input id='coordinator' type="text" class="form-control" aria-label="Text input">
        
        </div>
    </div>


    <div class="mb-3">
        <h5  class="form-label">ขอบข่ายงาน</h5>
        <div class="input-group">
        <textarea id='scope_work' class="form-control"  id="floatingTextarea2" style="height: 100px"></textarea>
        
        </div>
    </div>

    
    <h5  class="form-label">จำนวนที่รับ เทอม 1</h5>
    <div class="input-group mb-3">
        <select id='term1' class="form-select" id="button-addon1" aria-label="Default select example">
        <option velue='noSelect' selected>ภาคการศึกษา</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">ฤดูร้อน</option>
        </select>
        
        <input id='year' type="number" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input">
        <input id='receive'type="number" class="form-control" placeholder="รับ...คน"aria-label="Text input">
    </div>
    
    <h5  class="form-label">จำนวนที่รับ  เทอม 2</h5>
    <div class="input-group mb-3">
        <select id='term2' class="form-select" id="button-addon1" aria-label="Default select example">
        <option velue='noSelect' selected>ภาคการศึกษา</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">ฤดูร้อน</option>
        </select>
        
        <input id='year' type="number" class="form-control" placeholder="ปีการศึกษา..." aria-label="Text input">
        <input id='receive'type="number" class="form-control" placeholder="รับ...คน"aria-label="Text input">
    </div>

    <div class="input-group mb-3" style="padding: 50px;">
        
    </div>





    <script>
    
    // ฟังก์ชันเพื่อดึงค่าจาก URL
    function getQueryParameter(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
    }

    // ตั้งค่าตามค่าที่ได้จาก URL
    const dataParam = getQueryParameter('data');
    if (dataParam) {
            try {
                // แปลงข้อมูล JSON String เป็น Object
                const data = JSON.parse(decodeURIComponent(dataParam));

                // document.getElementById('location').value = data.location;
                // document.getElementById('address').value = data.test;
                // document.getElementById('sender_to').value = data.test;
                // document.getElementById('coordinator').value = data.coordinator;
                // document.getElementById('scope_work').value = data.test;
                document.getElementById('term1').value = '1';
                // document.getElementById('term2').value = '2';
                // document.getElementById('year1').value = data.year;
                // document.getElementById('receive1').value = data.receive;
                // document.getElementById('year2').value = data.year;
                // document.getElementById('receive2').value = data.receive;
            } catch (error) {
                console.error('Error parsing JSON:', error);
                document.getElementById('dataDisplay').innerText = 'ไม่สามารถแปลงข้อมูล JSON ได้';
            }
        } else {
            document.getElementById('dataDisplay').innerText = 'ไม่พบข้อมูล';
        }
        
    
    
</script>
</div>
</div>
</body>
</html>