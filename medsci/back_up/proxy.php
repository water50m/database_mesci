<?php
session_start();
if(!isset($_SESSION['whoareyou']) ){
    header("location: login.php");
    exit();
}
    $url = "https://search.longdo.com/mapsearch/json/search?keyword=" . urlencode($_GET['keyword']) . "&limit=10&key=bff66f6baa485edba09ca806b597ed30&fields=lat,lng,name,province";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    header('Content-Type: application/json');
echo $response;


?>