<?php
    //connect to database 192.168.0.94
    $conn = odbc_connect('SSIS_ERP','sa','Su@gor_pet88');

    if(!$conn) {
        die("Connect error!!, Lỗi kết nối CSDL!!");
    }
?>