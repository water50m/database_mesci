    

   function fetchData(element) {
    // ดึงค่า attribute 'value' จาก element ที่ถูกคลิก
    const value = element.getAttribute('value');
    
    
    // เรียกข้อมูลจากไฟล์ PHP ที่จะ query ข้อมูล
    fetch(`config/fetchdata.php?func=1&value=${value}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // แปลง JSON จากการตอบกลับ
        })
        .then(data => {
            
            
            let htmlContent = '';
            if (Array.isArray(data.value) && data.value.length === 0) {
                // ถ้าเป็นอาร์เรย์ว่าง ให้แสดงข้อความแจ้งเตือนสีแดง
                htmlContent = `
                    <div style="color: red; font-weight: bold; text-align: center; margin: 20px; margin-bottom: 40px;">
                        ไม่มีข้อมูลนี้ในรายการ
                    </div>
                `;
            } else {
    
            // ใช้ forEach เพื่อวนลูปตามข้อมูลใน data
            data.value.forEach(single_data => {
                console.log(single_data);
                htmlContent += `
                    <form action="modify_data.php" method="POST">
                        <div class="button-modify">
                        <button class="button-20 form-control" role="button">แก้ไข</button>
                        </div>
                        <input type="text" id="inputGroupFile01" name="_id" value=${single_data.id} style="display: none;" ">
                        </form>
                    <div class="card" onclick='handleCardClick(${JSON.stringify(single_data)})'>
                        
                        <div class="row">
                            <div class="col">
                            <div class="avatar"></div>
                                <div class="info">
                                    <p class="title">${single_data.location}</p>
                                    
                                </div>
    
                            </div>
                            <div class="col">
                                <div class="detail">
                                    <p>ด้าน: ${single_data.majorName}</p>
                                <p>แผนก: ${single_data.department}</p>
                                
                                </div>
                            </div>
                            <div class="col">
                                <div class="detail">
                                    <p>${single_data.regionName}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            })};

            // ตั้งค่า innerHTML ของ card ด้วย htmlContent
            document.getElementById('detail_internship').innerHTML = htmlContent;
            // เลื่อนหน้าเว็บลงมาที่ข้อมูลที่เพิ่งแสดง
            document.getElementById('detail_internship').scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error('เกิดปัญหากับการทำงานของ fetch:', error);
        });
}

function handleSubmit(event) {
    event.preventDefault(); // ป้องกันการส่งฟอร์มแบบธรรมดา

    // ดึงค่าจาก input และ select
    const location = document.getElementById('location').value;
    const region = document.getElementById('regionSelect').value;
    const department = 'noselect';//document.getElementById('departmentSelect').value;
    const branch = document.getElementById('branchSelect').value;

    // ส่งค่าผ่าน fetch API
    fetch(`config/fetchdata.php?func=2`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `&location=${encodeURIComponent(location)}&region=${encodeURIComponent(region)}&department=${encodeURIComponent(department)}&branch=${encodeURIComponent(branch)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // แปลง JSON จากการตอบกลับ
    })
    .then(data => {
        
        let htmlContent = '';
        if (Array.isArray(data.value) && data.value.length === 0) {
            // ถ้าเป็นอาร์เรย์ว่าง ให้แสดงข้อความแจ้งเตือนสีแดง
            htmlContent = `
                <div style="color: red; font-weight: bold; text-align: center; margin: 20px; margin-bottom: 40px;">
                    ไม่มีข้อมูลนี้ในรายการ
                </div>
            `;
        } else {

        // ใช้ forEach เพื่อวนลูปตามข้อมูลใน data
        data.value.forEach(single_data => {
            console.log(single_data);
            htmlContent += `
            <form action="modify_data.php" method="POST">
                <div class="button-modify">
                <button class="button-20" role="button">แก้ไข</button>
                </div>
                <input type="text" id="inputGroupFile01" name="_id" value='${single_data.id}' style="display: none;" ">
                </form>
                <div class="card" onclick='handleCardClick(${JSON.stringify(single_data)})'>
                    
                    <div class="row">
                        <div class="col">
                        <div class="avatar"></div>
                            <div class="info">
                                <p class="title">${single_data.location}</p>
                                
                            </div>

                        </div>
                        <div class="col">
                            <div class="detail">
                                <p>ด้าน: ${single_data.majorName}</p>
                            <p>แผนก: ${single_data.department}</p>
                            
                            </div>
                        </div>
                        <div class="col">
                            <div class="detail">
                                <p>${single_data.regionName}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })};

        // ตั้งค่า innerHTML ของ card ด้วย htmlContent
        document.getElementById('detail_internship').innerHTML = htmlContent;
        // เลื่อนหน้าเว็บลงมาที่ข้อมูลที่เพิ่งแสดง
        document.getElementById('detail_internship').scrollIntoView({ behavior: 'smooth' });
    })
    .catch(error => {
        console.error('เกิดปัญหากับการทำงานของ fetch:', error);
    });
}
    // แก้ไขข้อมูล
    function modifyClick(data) {
        window.open(`modify_data.php?data=${encodeURIComponent(JSON.stringify(data))}`, '_blank');
        


   }

function searchfromap(element) {
    // ดึงค่า attribute 'value' จาก element ที่ถูกคลิก
    const id = element.getAttribute('value');
    
    
    // เรียกข้อมูลจากไฟล์ PHP ที่จะ query ข้อมูล
    fetch(`config/fetchdata.php?func=1&type=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // แปลง JSON จากการตอบกลับ
        })
        .then(data => {
            
            
            let htmlContent = '';
            if (Array.isArray(data.value) && data.value.length === 0) {
                // ถ้าเป็นอาร์เรย์ว่าง ให้แสดงข้อความแจ้งเตือนสีแดง
                htmlContent = `
                    <div style="color: red; font-weight: bold; text-align: center; margin: 20px; margin-bottom: 40px;">
                        ไม่มีข้อมูลนี้ในรายการ
                    </div>
                `;
            } else {
    
            // ใช้ forEach เพื่อวนลูปตามข้อมูลใน data
            data.value.forEach(single_data => {
                
                htmlContent += `
                    <form action="modify_data.php" method="POST">
                        <div class="button-modify">
                        <button class="button-20 form-control" role="button">แก้ไข</button>
                        </div>
                        <input type="text" id="inputGroupFile01" name="_id" value=${single_data.id} style="display: none;" ">
                        </form>
                    <div class="card" onclick='handleCardClick(${JSON.stringify(single_data)})'>
                        
                        <div class="row">
                            <div class="col">
                            <div class="avatar"></div>
                                <div class="info">
                                    <p class="title">${single_data.location}</p>
                                    
                                </div>
    
                            </div>
                            <div class="col">
                                <div class="detail">
                                    <p>ด้าน: ${single_data.majorName}</p>
                                <p>แผนก: ${single_data.department}</p>
                                
                                </div>
                            </div>
                            <div class="col">
                                <div class="detail">
                                    <p>${single_data.regionName}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            })};

            // ตั้งค่า innerHTML ของ card ด้วย htmlContent
            document.getElementById('detail_internship').innerHTML = htmlContent;
            // เลื่อนหน้าเว็บลงมาที่ข้อมูลที่เพิ่งแสดง
            document.getElementById('detail_internship').scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error('เกิดปัญหากับการทำงานของ fetch:', error);
        });
}