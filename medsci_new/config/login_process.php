<?php

    require 'condb.php';

    $db = new connectdb();
    // เชื่อมต่อฐานข้อมูล MySQL
    $conn = $db->connectMySQL();
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // ตรวจสอบว่าฟอร์มที่ถูกส่งมาคือการลงทะเบียนหรือเข้าสู่ระบบ
        if (isset($_POST['name_sup'], $_POST['email_sup'], $_POST['password_sup'])) {
            // การลงทะเบียน (Sign Up)
            $name_sup = mysqli_real_escape_string($conn, $_POST['name_sup']);
            $email_sup = mysqli_real_escape_string($conn, $_POST['email_sup']);
            $password_sup = mysqli_real_escape_string($conn, $_POST['password_sup']);
            
            $check_email_sql = "SELECT * FROM users WHERE email = '$email_sup' OR '$email_sup' = name";
            $result_email = mysqli_query($conn, $check_email_sql);
            
            if(mysqli_num_rows($result_email) > 0){
                echo "อีเมลหรือ username นี้ถูกใช้ไปแล้ว กรุณาลองใหม่อีกครั้ง!";
            } else {
                // เข้ารหัสรหัสผ่าน
                $hashed_password = password_hash($password_sup, PASSWORD_DEFAULT);
                
                // เพิ่มข้อมูลผู้ใช้ใหม่ลงในฐานข้อมูล
                $sql_insert = "INSERT INTO users (name, email, password) VALUES ('$name_sup', '$email_sup', '$hashed_password')";
                
                if (mysqli_query($conn, $sql_insert)) {
                    echo "ลงทะเบียนสำเร็จ" . mysqli_error($conn);
                    header("Location: ../login.php?status=$hashed_password");
                    exit();
            } else {
                echo "เกิดข้อผิดพลาดในการลงทะเบียน";
                header("Location: ../login.php?status=false");
                exit();
            }
            }
    
        } elseif (isset($_POST['email_sin'], $_POST['password_sin'])) {
            // การเข้าสู่ระบบ (Sign In)
            $email_sin = mysqli_real_escape_string($conn, $_POST['email_sin']);
            $password_sin= mysqli_real_escape_string($conn, $_POST['password_sin']);
            // $email_sin = 'admin';
            // $password_sin = '1234';
            // ตรวจสอบว่าผู้ใช้มีอยู่ในฐานข้อมูลหรือไม่
            $sql_check = "SELECT * FROM users WHERE email = '$email_sin' OR '$email_sin' = name";
            $result = mysqli_query($conn, $sql_check);
            
            if (mysqli_num_rows($result) === 1) {
                // รับข้อมูลผู้ใช้จากฐานข้อมูล
                $user = mysqli_fetch_assoc($result);
    
                // ตรวจสอบรหัสผ่านว่าถูกต้องหรือไม่
                
                if (password_verify(1234,$user['password'])) {
                    // ล็อกอินสำเร็จ
                    session_start();

                    // เก็บค่าลงในตัวแปรเซสชัน
                    $_SESSION['whoareyou'] = $email_sin;
                    header("Location: ../search.php");
                    exit();
                    // เพิ่มโค้ดสำหรับการจัดการเมื่อผู้ใช้ล็อกอินสำเร็จ (เช่น สร้าง session)
                } else {
                    
                    // รหัสผ่านไม่ถูกต้อง
                    echo "รหัสผ่านไม่ถูกต้อง!";
                    header("Location: ../login.php?pass=");
                    exit();
                }

            } else {
                // ไม่พบผู้ใช้ที่ตรงกับอีเมลที่ให้มา
                echo "ไม่พบบัญชีผู้ใช้ที่ตรงกับอีเมลนี้!";
                header("Location: ../login.php?email=");
                exit();
            }
        }
    }
    ?>

