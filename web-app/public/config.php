<?php
$con = mysqli_connect("localhost", "root", "", "web-app");

if(!$con){
    echo "Connection failed" .mysqli_connect_error();
}

?>
