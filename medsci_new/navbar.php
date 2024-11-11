<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <link href="css/style.css" rel="stylesheet">
    <title>Document</title>

    <style>  
      .container-navbar{
        margin-bottom:20px;
        position: sticky;
        top:0;
        z-index: 1000;
      }
    </style>
</head>
<body>
<div class="container-navbar text-center">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
        <a class="navbar-brand" href="search.php">
            <div class="image-medsci-navbar" >
                <img src="images/Medscinu-01.png" alt="Description of image" width="90" height="60">
                <div style="margin-left: 10px;">
                    <span class="brand-title" style="display: block;">Medical Science</span>
                    <span class="brand-subtitle" style="display: block;">Naresuan University</span>
                </div>
            </div>
        </a>
        
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="Thaimap.php">ดูในโหมดแผนที่</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="search.php">ค้นหา</a>
                    </li>
                    
                    <li class="nav-item">
                        <a  href="addData.php" aria-current="page" class="nav-link active">
                            เพิ่มข้อมูล
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " href="config/logout.php" aria-disabled="true">Logout</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</div>
</body>
</html>