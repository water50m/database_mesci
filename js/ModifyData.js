
// set to default value
function resetLocationData() {
    
    // รีเซ็ตค่าพิกัด
    document.getElementById('latitude').value = defaultData.latitude;
    document.getElementById('longitude').value = defaultData.longtitude;
    
    // รีเซ็ตค่าจังหวัด
    document.getElementById('provinceSelect').value = defaultData.province;
    
    // รีเซ็ตค่าภูมิภาค
    document.getElementById('regionSelect').value = defaultData.regionName;
    document.getElementById('locationInput').value = defaultData.location;
    document.getElementById('floatingTextarea2').value = defaultData.address;
}

// update province and region
var provinceSelect = document.getElementById('provinceSelect');
var regionShow = document.getElementById('regionShow');
updateProvinceInfo(provinceSelect)
function updateProvinceInfo(selectedProvince) {
    province.forEach(function(prov) {
        if(prov.province_name === selectedProvince) {
            // document.getElementById('latitude').value = prov.latitude;
            // document.getElementById('longitude').value = prov.longitude; 
            document.getElementById('regionShow').value = prov.region_name;

        }
    });
}

provinceSelect.addEventListener('change', function() {
    updateProvinceInfo(this.value);
});



var map;
var openPopupBtn = document.getElementById('openPopupBtn');
var closePopupBtn = document.getElementById('closePopupBtn');
var popup = document.getElementById('popup');
var popupOverlay = document.getElementById('popupOverlay');
var suggest = document.getElementById('suggest');
var search = document.getElementById('searchInput');
var suggest = document.getElementById('suggest');
var currentSuggestionIndex = -1;

function initMap() {
    map = new longdo.Map({
        placeholder: document.getElementById('map'),
        language: 'th',
        mousewheel: true,
        zoom: {
            wheelFunction: true,  // เปิดใช้งานการซูมด้วยลูกกลิ้งเมาส์
            wheelZoomRatio: 0.25, // กำหนดอัตราการซูมต่อการเลื่อนลูกกลิ้ง (0.1-1.0)
            zoomToCursor: true    // ซูมเข้าที่ตำแหน่งเคอร์เซอร์
        }
    });

    //When user press an Enter button #search
    search.onkeyup = function(event) {
        const key = event.keyCode;
        
        // ถ้ากด Enter
        if(key === 13) {
            doSearch();
            return;
        }
        
        // ถ้ากดลูกศรขึ้น
        if(key === 38) {
            event.preventDefault();
            if(currentSuggestionIndex > 0) {
                currentSuggestionIndex--;
                highlightSuggestion();
            }
            return;
        }
        
        // ถ้ากดลูกศรลง
        if(key === 40) {
            event.preventDefault();
            const suggestions = suggest.getElementsByTagName('a');
            if(currentSuggestionIndex < suggestions.length - 1) {
                currentSuggestionIndex++;
                highlightSuggestion();
            }
            return;
        }
        
        // สำหรับการพิมพ์ปกติ
        if (search.value.length < 3) {
            suggest.style.display = 'none';
            currentSuggestionIndex = -1;
            return;
        }
        map.Search.suggest(search.value);
    };

    // เพิ่มฟังก์ชันไฮไลท์คำแนะนำที่เลือก
    function highlightSuggestion() {
        const suggestions = suggest.getElementsByTagName('a');
        
        // ลบไฮไลท์เดิมทั้งหมด
        for(let i = 0; i < suggestions.length; i++) {
            suggestions[i].style.backgroundColor = '';
        }
        
        // ไฮไลท์คำที่เลือก
        if(currentSuggestionIndex >= 0) {
            suggestions[currentSuggestionIndex].style.backgroundColor = '#e9ecef';
            search.value = suggestions[currentSuggestionIndex].textContent;
        }
    }

    map.Event.bind('suggest', function(result) {
        if (result.meta.keyword != search.value) return;
        suggest.innerHTML = '';
        currentSuggestionIndex = -1; // รีเซ็ต index เมื่อมีคำแนะนำใหม่
        
        for (var i = 0, item; item = result.data[i]; ++i) {
            longdo.Util.append(suggest, 'a', {
                innerHTML: item.d,
                href: 'javascript:doSuggest(\'' + item.w + '\')',
                style: 'display: block; padding: 5px; text-decoration: none; color: black;'
            });
        }
        suggest.style.display = 'block';
    });
}
function doSuggest(value) {
    search.value = value;
    doSearch();
}

function setLocationDetails(data) {
    
    // Set location details
    document.getElementById('locationInput').value = data.name;
    document.getElementById('latitude').value = data.lat;
    document.getElementById('longitude').value = data.lon;
    document.getElementById('floatingTextarea2').value = data.address;
    

    // Parse address for province
    if (data.address) {
        const addressParts = data.address.split(' ');
        if (addressParts.length >= 2) {
            const provinceText = addressParts[addressParts.length - 2];
            let province = '';
            
            if (provinceText.includes('.')) {
                const provinceParts = provinceText.split('.');
                province = provinceParts[provinceParts.length - 1];
            } else {
                province = provinceText;
            }

            document.getElementById('provinceSelect').value = province;
            updateProvinceInfo(province);
        }
    }

        // Add marker and click handler
        map.Overlays.clear();
        map.Overlays.add(new longdo.Marker({lat: data.lat, lon: data.lon}));
        
    //     map.Event.bind('click', function() {
        
    //     map.Overlays.clear();
    //     var mouseLocation = map.location(longdo.LocationMode.Pointer);
    //     console.log(mouseLocation)
    //     document.getElementById('latitude').value = mouseLocation.lat;
        
    //     document.getElementById('longitude').value = mouseLocation.lon;
    //     map.Overlays.add(new longdo.Marker(mouseLocation));
    // });
}   

function doSearch() {
    map.Search.search(search.value);
    map.Event.bind('search', function(result) {
        All_result = result.data;
        setLocationDetails(All_result[0]);
    });
    const resultsDiv = document.getElementById('results');
    resultsDiv.addEventListener('click', function(e) {
        // ดูโครงสร้างของ element ที่ถูกคลิก
        const listItem = e.target.closest('.ldsearch_item');
        const Name = listItem.childNodes[2].textContent;

        // หา item ที่ตรงกับชื่อ
        if (All_result.some(item => item.name === Name)) {
            const data = All_result.find(item => item.name === Name);
            
            if (data) {                 
                setLocationDetails(data);
                
                // เพิ่มการซูมไปยังตำแหน่งที่เลือก
                map.location({ lon: data.lon, lat: data.lat }, true);
                map.zoom(8, true); // ปรับระดับการซูมตามต้องการ (1-20)
            }
        }
        
        
    });

    

    map.Search.placeholder(document.getElementById('results'));
    suggest.style.display = 'none';
}

openPopupBtn.addEventListener('click', function() {
    popup.style.display = 'block';
    popupOverlay.style.display = 'block';
    setTimeout(function() {
        initMap();
        search.oninput = function() {
            if (search.value.length < 3) {
                suggest.style.display = 'none';
                return;
            }
            map.Search.suggest(search.value);
        };
        map.Event.bind('suggest', function(result) {
            if (result.meta.keyword != search.value) return;
            suggest.innerHTML = '';
            for (var i = 0, item; item = result.data[i]; ++i) {
                longdo.Util.append(suggest, 'a', {
                innerHTML: item.d,
                href: 'javascript:doSuggest(\'' + item.w + '\')'
                });
            }
            suggest.style.display = 'block';
        });

    }, 100);
});

closePopupBtn.addEventListener('click', function() {
    popup.style.display = 'none';
    popupOverlay.style.display = 'none';
});

popupOverlay.addEventListener('click', function() {
    popup.style.display = 'none';
    popupOverlay.style.display = 'none';
});




// smart complete
function saveNewLocation(action) {
    const form = document.getElementById('myForm');
    form.action = action;
    form.submit();
}
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

        // สร้าง hidden input ใหม่
        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "func";
        hidden.id = "func";
        hidden.value = 1;

        // ใส่เข้าไปในฟอร์ม
        form.appendChild(hidden);

        form.action = action;
        form.submit();
    }
}



    
    // smart select province
   
    
    var provinceSelect = document.getElementById('provinceSelect');
    var regionSelect = document.getElementById('regionSelect');
    
    provinceSelect.addEventListener('change', function() {
        // ดึงค่าจังหวัดที่เลือกปัจจุบัน
        var selectedprovince_value = this.value;


        // กรองและเพิ่มภูมิภาคที่ตรงกับจังหวัด
        var foundRegion = false;
        province.forEach(function(prov) {
            if(prov.province_name === selectedprovince_value && !foundRegion) {
                document.getElementById('latitude').value = prov.latitude;
                document.getElementById('longitude').value = prov.longitude;
                document.getElementById('regionSelect').value = prov.region_id;
                document.getElementById('regionShow').placeholder = prov.region_name;

            }
        });
    });


function deleteSemester(apiUrl) {
    
    const yearSelect = document.getElementById('_year1');
    const location_id = document.getElementById('location_id').value;

    const selectedValue = yearSelect.value;
    if (selectedValue === 'dontChange') {
        alert('กรุณาเลือกภาคการศึกษาที่ต้องการลบ');
        return;
    }

    if (!confirm("คุณต้องการลบข้อมูลเทอมนี้จริงหรือไม่?")) {
        return;
    }
    
    // ส่งข้อมูลไปให้ delete.php
    fetch(apiUrl + '?func=2', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'data=' + encodeURIComponent(selectedValue)+'&func=' + encodeURIComponent(2)+'&location_id=' + encodeURIComponent(location_id)
    })
    .then(response => response.text())
    .then(result => {
        const data = JSON.parse(result);

        alert(data.message);
        if(data.status == "success"){
                location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("เกิดข้อผิดพลาดระหว่างลบข้อมูล");
    });
}

function handleSelectChange(selectElem) {
    const selectedOption = selectElem.options[selectElem.selectedIndex];

    // ถ้าเลือก "dontChange" ก็ไม่ต้องทำอะไร
    if (selectedOption.value === "dontChange") {
        document.getElementById("_receive").value = "";
        return;
    }

    // ดึงค่ารับ (received)
    const received = selectedOption.getAttribute("data-received");

    // อัปเดต input
    document.getElementById("_receive").value = received;

    // เตรียมข้อมูลสำหรับการ save
     const selectedValue = selectElem.value; // ค่าที่เลือก 
    
    if (Array.isArray(numReceivePerYear)) {
        numReceivePerYear.forEach(item=>{
        if (selectedValue == item['reid']){
            document.getElementById('receive_before').value = item['received'];
            document.getElementById('_term1_edit').value = item['term'];
            document.getElementById('_year1_edit').value = item['year'];
        }if(selectedValue == 'dontChange'){
            document.getElementById('countInput').value = 'รับ...คน';
        }
    })
    } else {
        console.error("numReceivePerYear ไม่ใช่อาร์เรย์", numReceivePerYear);
    }
}


// ป้องกัน enter กับปุ่มลบ
document.getElementById('_receive').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault(); // ป้องกัน form submit
    }
});