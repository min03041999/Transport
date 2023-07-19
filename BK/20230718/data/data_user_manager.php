<?php 
    require('../init.php');
    session_start();
    $userid = @$_SESSION['Account_ID'];
    
    if($_GET['action'] == 'add'){
        $Account = $_POST['Account'];
        $CompanyID = $_POST['CompanyID'];
        $Name = $_POST['Name'];
        $Password = $_POST['Password'];
        $check_account = "SELECT COUNT(1) FROM Transport_User where Account_ID = '$Account'";
        $result = odbc_result(odbc_exec($conn, $check_account), 1);
        if($result == 0){
            $sql_create = "INSERT INTO Transport_User 
                            (   Account_ID,
                                Account_PWD,
                                Account_Name,
                                Company,
                                UserID,
                                UserDate,
                                Position) 
                            VALUES
                            (   '$Account',
                                '$Password',
                                '$Name',
                                '$CompanyID',
                                '$userid',
                                GETDATE(),
                                '0'
                            )";
            $rs_create = odbc_exec($conn, $sql_create);
            if ($rs_create == true){
                echo json_encode(array('status' => true, 'Info' => 'Đăng ký thành công!'));
            }
        } else {
            echo json_encode(array('status' => false, 'Info' => 'Tài khoản đã tồn tại!'));
        }
    }

    if($_GET['action'] == 'edit'){
        $Account = $_POST['Account'];
        $CompanyID = $_POST['CompanyID'];
        $Name = $_POST['Name'];
        $Password = $_POST['Password'];
        $Hidden = $_POST['Hidden'];
        if ( $Account == $Hidden){
            $sql_update = "UPDATE Transport_User
                            SET 
                                Account_PWD = '$Password',
                                Company = '$CompanyID',
                                Account_Name = '$Name',
                                UserID = '$userid',
                                UserDate = GETDATE()
                            WHERE Account_ID = '$Hidden'";
            $rs_update = odbc_exec($conn, $sql_update);
            if( $rs_update == true){
                echo json_encode(array('status' => true, 'Info' => 'Cập nhật thành công!'));
            }
        } else {
            $check_acc = "SELECT count(1) FROM Transport_User WHERE Account_ID ='$Account' ";
            $rs_check_acc = odbc_exec($conn,$check_acc);
            $check_acc = odbc_result($rs_check_acc,1);     
            if($check_acc == 0){
                $sql_update = "UPDATE Transport_User
                                SET 
                                    Account_ID = '$Account',
                                    Account_PWD = '$Password',
                                    Company = '$CompanyID',
                                    Account_Name = '$Name',
                                    UserID = '$userid',
                                    UserDate = GETDATE()
                                WHERE Account_ID ='$Hidden'";
                $rs_update = odbc_exec($conn,$sql_update);
                if( $rs_update == true){
                    echo json_encode(array('status' => true, 'Info' => 'Cập nhật thành công!'));
                }
            }else{
                echo json_encode(array('status' => false, 'Info' => 'Dữ liệu đã tồn tại!'));  
            }
        }
    }
    
    if($_GET['action'] == 'delete'){
        $Account_ID =  $_POST['Account_ID'];
        $sql_delete = "DELETE Transport_User WHERE Account_ID = '$Account_ID' ";
        $rs_delete = odbc_exec($conn,$sql_delete);
        if($rs_delete == true){
            echo json_encode(array('status' => true, 'Info' => 'Xóa thành công!'));
        }else{
            echo json_encode(array('status' => false, 'Info' => 'Xóa thất bại!'));  
        }
    }
    
    if($_GET['action'] == 'getdata'){
        $sql_getdata = "SELECT *
        FROM   Transport_User ORDER BY Account_Name";
        $rs_sql_getdata = odbc_exec($conn,$sql_getdata);
        $data = [];
        while($row = odbc_fetch_array($rs_sql_getdata)){   
            $data[] = $row;
        }
        echo json_encode($data);
    }
?>