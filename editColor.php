<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gradient Filter</title>
  <style>
    body {
      font-family: sans-serif;
      text-align: center;
      padding: 20px;
    }
    canvas {
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>

  <h2>ไล่ระดับสีจากล่างขึ้นบน (เข้มไปอ่อน)</h2>
    <button button onclick="applyGradient()">🎨 ใส่ Gradient Filter</button>
    <button onclick="increaseBrightness()">💡 เพิ่มความสว่าง</button>
    <button onclick="downloadImage()">⬇ ดาวน์โหลด</button>
    <input type="file" accept="image/*" onchange="loadImage(event)" />

  <br><br>

  <canvas id="myCanvas" width="300" height="300"></canvas>
  <img id="myImage" src="images/maker/location-purple.png" crossorigin="anonymous" style="display: none;" />

  <script>
    const canvas = document.getElementById('myCanvas');
    const ctx = canvas.getContext('2d');
    const img = document.getElementById('myImage');

    img.onload = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    };

    function applyGradient() {
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

      // สร้าง gradient ดำล่าง → โปร่งใสบน
      const gradient = ctx.createLinearGradient(0, canvas.height, 0, 0);
      gradient.addColorStop(0, 'rgba(0, 0, 0, 0.6)');
      gradient.addColorStop(1, 'rgba(0, 0, 0, 0)');

      ctx.fillStyle = gradient;
      ctx.globalCompositeOperation = 'multiply'; // ทำให้ overlay แบบเข้ม
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      ctx.globalCompositeOperation = 'source-over'; // reset
    }

    function downloadImage() {
      const link = document.createElement('a');
      link.download = 'gradient-filtered.png';
      link.href = canvas.toDataURL();
      link.click();
    }

    function increaseBrightness() {
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const data = imageData.data;
        const brightnessAmount = 40; // ปรับค่าตามต้องการ

        for (let i = 0; i < data.length; i += 4) {
            data[i]     = Math.min(data[i] + brightnessAmount, 255);     // R
            data[i + 1] = Math.min(data[i + 1] + brightnessAmount, 255); // G
            data[i + 2] = Math.min(data[i + 2] + brightnessAmount, 255); // B
            // alpha (data[i + 3]) ไม่เปลี่ยน
        }

        ctx.putImageData(imageData, 0, 0);
    }

    function loadImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const uploadedImage = new Image();
            uploadedImage.onload = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(uploadedImage, 0, 0, canvas.width, canvas.height);
            img.src = uploadedImage.src; // ให้ img ใช้ source นี้ในการ apply filter
            };
            uploadedImage.src = e.target.result;
        };
        reader.readAsDataURL(file);
        }

  </script>

</body>
</html>
