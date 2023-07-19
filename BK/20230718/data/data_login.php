<?php 
    require('../init.php');
    session_start();

    $Action = $_REQUEST['Action'];
    if($Action == "login") {
        $account_id = $_POST['Account_ID'];
        $password = $_POST['Password'];
        $company = $_POST['Company'];

        $check = "  SELECT * FROM Transport_User 
                    WHERE Account_ID = '$account_id' AND Account_PWD = '$password' AND Company = '$company'";
        // echo $check;
        $rs_check = odbc_exec($conn, $check);
        
        if(odbc_num_rows($rs_check) > 0) {
            $_SESSION['Name'] = odbc_result($rs_check, "Account_Name");
            $_SESSION['Account_ID'] = odbc_result($rs_check, "Account_ID");
            $_SESSION['Company'] = odbc_result($rs_check, "Company");
            $_SESSION['Position'] = odbc_result($rs_check, "Position");
            echo  json_encode(array('status' => true, 'msg' => 'Đăng nhập không thành công!'));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Tài khoản không chính xác!'));
        }   
    }
?>