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
            document.getElementById('latitude').value = prov.latitude;
            document.getElementById('longitude').value = prov.longitude; 
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
        
        map.Event.bind('click', function() {
        
        map.Overlays.clear();
        var mouseLocation = map.location(longdo.LocationMode.Pointer);
        console.log(mouseLocation)
        document.getElementById('latitude').value = mouseLocation.lat;
        document.getElementById('longitude').value = mouseLocation.lon;
        map.Overlays.add(new longdo.Marker(mouseLocation));
    });
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
        form.action = action;
        form.submit();
    }
}
 function handleSelectChange(selectElement) {
    const selectedValue = selectElement.value; // ค่าที่เลือก 
    
    
    if (Array.isArray(numReceivePerYear)) {
        numReceivePerYear.forEach(item=>{
        if (selectedValue == item['id']){
            document.getElementById('countInput').value = item['received'];
            document.getElementById('_term1').value = item['term'];
            document.getElementById('_year1Input').value = item['year'];
            document.getElementById('_term1_before').value = item['term'];
            document.getElementById('_year1_before').value = item['year'];
        }if(selectedValue == 'dontChange'){
            document.getElementById('countInput').value = 'รับ...คน';
            document.getElementById('_term1').value = 'ภาคการศึกษาที่...';
            document.getElementById('_year1Input').value = 'ปีการศึกษา...';
        }
    })
    } else {
        console.error("numReceivePerYear ไม่ใช่อาร์เรย์", numReceivePerYear);
    }
    // console.log(numReceivePerYear)    
};


    var facultySelect = document.getElementById('facultyName_select');
    var majorSelect = document.getElementById('facultyMajor');

    // ฟังก์ชันสำหรับอัพเดทสาขาวิชา
    function updateMajors(selectedFaculty) {
        // เคลียร์ตัวเลือกเก่า
        
        majorSelect.innerHTML = '<option value="" selected>เลือกสาขาวิชา</option>'+'<option value="' + result.majorName + '" selected style="background-color: yellow;">' + result.majorName + '</option>';
        
        // กรองและเพิ่มสาขาที่ตรงกับคณะ
        facuty.forEach(function(faculty) {
            if(faculty.facuty === selectedFaculty && faculty.f_major !== '' && faculty.f_major !== result.majorName) {
                const option = document.createElement('option');
                option.value = faculty.f_major;
                option.text = faculty.f_major;
                majorSelect.appendChild(option);
            }
        });
    }

    // เรียกใช้ฟังก์ชันทันทีที่โหลดหน้า
    updateMajors(facultySelect.value);

    // เพิ่ม event listener สำหรับการเปลี่ยนแปลง
    facultySelect.addEventListener('change', function() {
        updateMajors(this.value);
    });

    // smart select province
   
    
    var provinceSelect = document.getElementById('provinceSelect');
    var regionSelect = document.getElementById('regionSelect');
    
    provinceSelect.addEventListener('change', function() {
        // ดึงค่าจังหวัดที่เลือกปัจจุบัน
        var selectedprovince_value = this.value;
        
        // กรองและเพิ่มภูมิภาคที่ตรงกับจังหวัด
        var foundRegion = false;
        province.forEach(function(prov) {
            console.log(prov);
            if(prov.province_name === selectedprovince_value && !foundRegion) {
                document.getElementById('latitude').value = prov.latitude;
                document.getElementById('longitude').value = prov.longitude;
                document.getElementById('regionSelect').value = prov.region_id;
                document.getElementById('regionShow').placeholder = prov.region_name;

            }
        });
    });