<?php 
session_start();

$message = isset($_GET['message']) ? $_GET['message'] : '';
$func =  isset($_GET['func']) ? $_GET['func'] : '';
if(!(isset($_SESSION['whoareyou'])) && $func != '4'){
    header("location: login.php");
    exit();
}
if($func == 1){
    $redirectUrl = 'addData.php';
}else if($func == 2){
    $type =  isset($_GET['type']) ? $_GET['type'] : '';
    $redirectUrl = 'modify_data.php?func=2&type='.$type;
}else if($func == 3){
    $redirectUrl = 'search.php?';       
}else if($func == 4){
    $redirectUrl = 'login.php';
    session_destroy();
}


?>

<script>
    const message = "<?php echo htmlspecialchars($message) ?>";
    const redirectUrl = "<?php echo $redirectUrl ?>";
    
    function redirect() {
        window.location.href = redirectUrl;
    }

    
    if (message) {
        alert(message);
        setTimeout(redirect, 100); // รอให้ alert แสดงเสร็จก่อนแล้วค่อย redirect
    } else {
        redirect();
    }
</script>