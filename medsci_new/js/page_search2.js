    

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
               
                htmlContent += `
                    <form action="modify_data.php" method="POST">
                        <div class="button-modify">
                        <button class="button-20 form-control" role="button">แก้ไข</button>
                        </div>
                        <input type="text" id="inputGroupFile01" name="_id" value=${single_data.id} style="display: none;" ">
                        </form>
                    <div class="card" onclick='handleCardClick(${JSON.stringify(single_data)}) '>
                        
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
            
            htmlContent += `
            <form action="modify_data.php" method="POST">
                <div class="button-modify">
                <button class="button-20" role="button">แก้ไข</button>
                </div>
                <input type="text" id="inputGroupFile01" name="_id" value='${single_data.id}' style="display: none;" ">
                </form>
                <div class="card" onclick='handleCardClick(${JSON.stringify(single_data)}) id="openModal"'>
                    
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

  
//    madalhandle
function handleCardClick(data) {
    
    fetch(`config/fetchdata.php?func=4&value=${data.id}`)

    .then(response => {
    // ตรวจสอบสถานะของการตอบกลับจากเซิร์ฟเวอร์
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    // แปลงข้อมูลจาก response เป็น JSON
    return response.json();
})
.then(data2 => {
    console.log(data2)

    // ตรวจสอบข้อมูลที่ได้มา
    const headers = ['ปีการศึกษา'];
    const body = ['จำนวนรับ(คน)'];
    data2.value.forEach(item=>{
        headers.push(item.term+'/'+item.year)
        body.push(item.received)
    })
    



// ฟังก์ชันสร้างตาราง
function createTable() {
    // สร้าง table element
    const table = document.createElement('table');
    table.classList.add('custom-table');
    
    // สร้างส่วนหัวของตาราง
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    
    headers.forEach(headerText => {
        
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);
    
    // สร้างส่วนข้อมูลของตาราง
    const tbody = document.createElement('tbody');
    
        const row = document.createElement('tr');
        Object.values(body).forEach(text => {
            const cell = document.createElement('td');
            cell.textContent = text;
            row.appendChild(cell);
        });
        tbody.appendChild(row);
    
    table.appendChild(tbody);
    
    // แสดงตารางบนหน้าเว็บ
    document.getElementById('table-recieve').appendChild(table);
}

// เรียกฟังก์ชันสร้างตารางด้วยข้อมูล
createTable(data);
    // คุณสามารถนำข้อมูลที่ได้ไปใช้ในฟังก์ชันต่อไปได้ที่นี่
    // เช่น แสดงข้อมูลใน UI หรือการใช้งานอื่น ๆ
})
.catch(error => {
    // แสดงข้อผิดพลาดในกรณีที่เกิดข้อผิดพลาด
    console.error('Error during fetch or JSON parsing:', error);
});
    

    document.getElementById('modal-name').innerText = data.location;
    document.getElementById('modal-major').innerText ='ด้าน: ' + data.majorName;
    document.getElementById('modal-department').innerText = 'แผนก: '+ data.department;
    document.getElementById('modal-region').innerText ='ภูมิภาค: ' +data.regionName;
    document.getElementById('modal-scope-work').innerText = 'ขอบข่ายงาน: ' +data.Scope_work;

    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
    myModal.show();

}


function deleteTable() {
    const tableContainer = document.getElementById('table-recieve');
    const table = tableContainer.querySelector('table');
    if (table) {
        table.remove(); // ลบตาราง
    }
}
