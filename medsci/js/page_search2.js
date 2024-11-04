    
   function fetchData(element) {
    // ดึงค่า attribute 'value' จาก element ที่ถูกคลิก
    const value = element.getAttribute('value');
    console.log('value is:', value);
    
    // เรียกข้อมูลจากไฟล์ PHP ที่จะ query ข้อมูล
    fetch(`config/fetchdata.php?func=1&value=${value}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // แปลง JSON จากการตอบกลับ
        })
        .then(data => {
            console.log(data);
            let htmlContent = '';

            // ใช้ forEach เพื่อวนลูปตามข้อมูลใน data
            data.value.forEach(single_data => {
                htmlContent += `
                    <button class="button-20" onclick='modifyClick(${JSON.stringify(single_data)})' role="button">แก้ไข</button>
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
                                <p>ขอบข่ายงาน: ${single_data.Scope_work} คน</p>
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
            });

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
    fetch(`config/fetchdata.php?`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `func=2&location=${encodeURIComponent(location)}&region=${encodeURIComponent(region)}&department=${encodeURIComponent(department)}&branch=${encodeURIComponent(branch)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // แปลง JSON จากการตอบกลับ
    })
    .then(data => {
        
        let htmlContent = '';

        // ใช้ forEach เพื่อวนลูปตามข้อมูลใน data
        data.value.forEach(single_data => {
            console.log(single_data);
            htmlContent += `
                <button class="button-20" onclick='modifyClick(${JSON.stringify(single_data)})' role="button">แก้ไข</button>
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
                            <p>ขอบข่ายงาน: ${single_data.Scope_work} คน</p>
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
        });

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
