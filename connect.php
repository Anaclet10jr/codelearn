<?php
$conn=mysqli_connect('localhost','root','','inventory');

if(!$conn){
    echo "connection error".mysqli_connect_error();
}
?>