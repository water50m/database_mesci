    

   function fetchData(element) {
    // ดึงค่า attribute 'value' จาก element ที่ถูกคลิก
    const value = element.getAttribute('value');
    rights= ``;

                   
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
                            ${rights}
                            <input type="text" id="inputGroupFile01" name="_id" value="${single_data.id}" style="display: none;">
                        </form>
                        <div class="card" onclick='handleCardClick(${JSON.stringify(single_data)})'>
                            <div class="row g-0 align-items-center">
                                <div class="col-md-4">
                                    <div class="avatar">
                                        ${single_data.picture_path ? 
                                            `<img src="${single_data.picture_path}" class="img-fluid" alt="รูปภาพสถานที่ฝึกงาน">` : 
                                            `<img src="images/Medscinu-01.png" class="img-fluid" alt="ไม่มีรูปภาพ">`
                                        }
                                    </div>
                                    <div class="info">
                                        <p class="title">${single_data.location}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail">
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail">
                                        <p></p>
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
    const establishment = document.getElementById('establishment').value;
    const branch = document.getElementById('branchSelect').value;
    console.log(establishment);
    rights = ``;

    // ส่งค่าผ่าน fetch API
    fetch(`config/fetchdata.php?func=2`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `&location=${encodeURIComponent(location)}&region=${encodeURIComponent(region)}&department=${encodeURIComponent(establishment)}&branch=${encodeURIComponent(branch)}`
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
            ${rights}
                <input type="text" id="inputGroupFile01" name="_id" value='${single_data.id}' style="display: none;" ">
                </form>
                <div class="card" onclick='handleCardClick(${JSON.stringify(single_data)}) '>
                    
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4">
                            <div class="avatar">
                                ${single_data.picture_path ? 
                                    `<img src="${single_data.picture_path}" class="img-fluid" alt="รูปภาพสถานที่ฝึกงาน">` : 
                                    `<img src="images/Medscinu-01.png" class="img-fluid" alt="รูปภาพเริ่มต้น">`
                                }
                            </div>
                            <div class="info">
                                <p class="title">${single_data.location}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail">
                                <p></p>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="detail">
                                <p></p>
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
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data2 => {
        
        const major_subject = document.getElementById('modal-major-subject');
        
        // ลบ options เก่า
        major_subject.innerHTML = '';
        
        // ลบตารางเก่า
        deleteTable();

        // สร้าง options และตารางครั้งแรก
        data2.value.forEach(item => {
            const major_option = document.createElement('option');
            major_option.value = item.mid;
            major_option.textContent = item.m_name;
            major_subject.appendChild(major_option);
        });

        // สร้างตารางเริ่มต้นสำหรับตัวเลือกแรก
        const headers = ['ปีการศึกษา'];
        const body = ['จำนวนรับ(คน)'];
        if(data2.value.length > 0) {
            const firstItem = data2.value[0];
            
            document.getElementById('modal_id').value = firstItem.mid;
            document.getElementById('modal_location_id').value = firstItem.location_id;
            headers.push(firstItem.term+'/'+firstItem.year);
            body.push(firstItem.received);
            updateTable(headers, body);
        }

        // event listener สำหรับการเปลี่ยนค่า
        major_subject.addEventListener('change', function(e) {
            
            const selectedValue = e.target.value;
            const selectedText = e.target.options[e.target.selectedIndex].textContent;
            // document.getElementById('modal-major').textContent = `ด้าน: ${selectedText}`;
            
            // ลบตารางเก่าก่อนสร้างใหม่
      
            
            const newHeaders = ['ปีการศึกษา'];
            const newBody = ['จำนวนรับ(คน)'];
           
            data2.value.some(item => {
                
                if(item.mid == selectedValue){
                    document.getElementById('modal_id').value = item.mid;
                    document.getElementById('modal_location_id').value = item.location_id;
                    newHeaders.push(item.term+'/'+item.year);
                    newBody.push(item.received);
                    updateTable(newHeaders, newBody);
                    return true;
                }
                return false;
            });
        });
        
        // แสดงข้อมูลอื่นๆ
        document.getElementById('modal-name').innerText = data.location;
        // document.getElementById('modal-major').innerText = 'ด้าน: ' + data.majorName;
        document.getElementById('modal-department').innerText = 'แผนก: '+ data.department;
        document.getElementById('modal-region').innerText = 'ภูมิภาค: ' +data.regionName;
        document.getElementById('modal-scope-work').innerText = 'ขอบข่ายงาน: ' +data.Scope_work;
        

        // แสดง modal
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
    })
    .catch(error => {
        console.error('Error during fetch or JSON parsing:', error);
    });
}

function deleteTable() {
    // ลบตารางเดิม
    
    const tableContainer = document.getElementById('table-recieve');
    const table = tableContainer.querySelector('.custom-table');
    if (table) {

        table.remove();
    }
}

function updateTable(headers, body) {
    // ลบตารางเก่า
    deleteTable();
    
    // สร้างตารางใหม่
    const table = document.createElement('table');
    table.classList.add('custom-table');
    
    // สร้างส่วนหัว
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);
    
    // สร้างส่วนข้อมูล
    const tbody = document.createElement('tbody');
    const row = document.createElement('tr');
    body.forEach(text => {
        const cell = document.createElement('td');
        cell.textContent = text;
        row.appendChild(cell);
    });
    tbody.appendChild(row);
    table.appendChild(tbody);
    
    // แสดงตาราง
    document.getElementById('table-recieve').appendChild(table);
}
