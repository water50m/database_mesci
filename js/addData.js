var facultySelect = document.getElementById('facultyName');
var majorSelect = document.getElementById('facultyMajor');


// smart select province


var provinceSelect = document.getElementById('provinceSelect');
var regionSelect = document.getElementById('regionSelect');
updateProvinceInfo(provinceSelect)
function updateProvinceInfo(selectedProvince) {
    province.forEach(function(prov) {
        if(prov.province_name === selectedProvince) {
            document.getElementById('latitude').value = prov.latitude;
            document.getElementById('longitude').value = prov.longitude; 
            document.getElementById('regionSelect').value = prov.region_name;

        }
    });
}

provinceSelect.addEventListener('change', function() {
    updateProvinceInfo(this.value);
});




// control map
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
        const suggestions = doSuggest.getElementsByTagName('a');
        
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

    function setLocationDetails(data) {
        // Set location details
        document.getElementById('locationInput').value = data.name;
        document.getElementById('latitude').value = data.lat;
        document.getElementById('longitude').value = data.lon;
        document.getElementById('addressInput').value = data.address;

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
            document.getElementById('latitude').value = mouseLocation.lat;
            document.getElementById('longitude').value = mouseLocation.lon;
            map.Overlays.add(new longdo.Marker(mouseLocation));
        });
    }   

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


// ควบคุมการ submit
document.getElementById("myForm").addEventListener("submit", function(e){
    console.log('show form');
    e.preventDefault(); // ป้องกัน reload

    const formData = new FormData(this);

    fetch("config/addDatadb.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "error"){
            console.log(data.data);
            alert(data.message);  // แสดง error
        } else {
            alert("บันทึกสำเร็จ");
            window.location.href = "success.php"; 
        }
    });
});
