<?php
    require('../init.php');
    session_start();
    $userid = @$_SESSION['Account_ID'];
    $companyss = @$_SESSION['Company'];
    $action = $_REQUEST['action'];

    if ($action == 'get_data_company') {
        $driver_company = $_POST['driver_company'];
        $sql_query_company = "SELECT a.Company
                            FROM Transport_User AS a
                            LEFT JOIN Ship_Driver_Web AS b ON b.Driver_No = a.Account_ID
                            WHERE a.Account_ID = '$driver_company'";
        $exec_query_company = odbc_exec($conn, $sql_query_company);
        $get_row_company = odbc_result($exec_query_company, 'Company');

        echo $get_row_company;
    }

    if ($action == 'get_data_driver') {
        $Name = $_GET['Name'];
        $Select = $_GET['Select'];
        $Condition = '1=1';

        if($companyss != '') {
            $Condition.= " and Company = '$companyss'";
        }

        if($Name !=''){
            $Condition .= "AND Driver_Name like '$Name'";
        }

        if($Select !=''){
            $Condition .= "AND Driver_Status like '$Select'";
        }
        $sql_query_driver = "   SELECT Driver_No, Type_Driver, Driver_Name, Year_of_Birth, Driver_Address, 
                                    Hand_Phone, Driver_Status, Remark, SUBSTRING(Signature, 1, 4000) AS Signature, SUBSTRING(Signature, 4001, 4000) AS Signature1,
                                    SUBSTRING(Signature, 8001, 4000) AS Signature2
                                FROM   Ship_Driver_Web
                                WHERE $Condition";
        // echo $sql_query_driver;
        $exec_query_driver = odbc_exec($conn, $sql_query_driver);
        $item = array();
        while (@$row = odbc_fetch_array($exec_query_driver)) {
            array_push($item, $row);
        }
        echo json_encode($item);
    }

    if ($action == 'add_driver') {
        $driver_no = $_POST['driver_no'];
        $driver_name = $_POST['driver_name'];
        $year_birth = $_POST['year_birth'];
        $driver_address = $_POST['driver_address'];
        $hand_phone = $_POST['hand_phone'];
        $type_indentity = $_POST['select_type_indentity'];
        $signature = $_POST['sig'];
        // $image_signature = explode(";base64,", $signature)[1];
        $remark = $_POST['remark'];
        
        $sql_query = "SELECT COUNT(1)
                        FROM Ship_Driver_Web 
                        WHERE Driver_No = '$driver_no'";
        $rs_query = odbc_result(odbc_exec($conn, $sql_query), 1);
        if ($rs_query == 0) {
            $sql_add_driver = " INSERT INTO Ship_Driver_Web
                            (
                                Driver_No,
                                Driver_Name,
                                Year_of_Birth,
                                Driver_Address,
                                Hand_Phone,
                                Driver_Status,
                                Remark,
                                YN,
                                Company,
                                Signature,
                                UserID,
                                UserDate,
                                Type_Driver
                            )
                            VALUES
                            (
                                '$driver_no',
                                '$driver_name',
                                '$year_birth',
                                '$driver_address',
                                '$hand_phone',
                                'Active',
                                '$remark',
                                '1',
                                '$companyss',
                                '$signature',
                                '$userid',
                                getdate(),
                                '$type_indentity'
                            )";
            $exec_add_driver = odbc_exec($conn, $sql_add_driver);

            $update_image = "SET ARITHABORT ON;
                            UPDATE Ship_Driver_Web SET  Image = CAST('' AS XML).value('xs:base64Binary(sql:column(\"[Signature]\"))[1]', 'VARBINARY(MAX)')
                            WHERE Driver_No = '$driver_no'";
            $exec_update_image = odbc_exec($conn, $update_image);

            if ($exec_add_driver == true) {
                echo json_encode(array('status' => true, 'Info' => "Thêm thành công!"));
            }
        } else {
            echo json_encode(array('status' => false, 'Info' => 'Thông tin tài xế đã tồn tại!'));
        }
    }

    if ($action == 'edit_driver') {
        $edit_id = $_POST['edit_id'];
        $driver_no = $_POST['driver_no'];
        $driver_name = $_POST['driver_name'];
        $year_birth = $_POST['year_birth'];
        $driver_address = $_POST['driver_address'];
        $hand_phone = $_POST['hand_phone'];
        $type_indentity = $_POST['select_type_indentity'];
        // $driver_status = $_POST['driver_status'];
        // $driver_company = $_POST['driver_company'];
        $signature = $_POST['sig'];
        // if(strpos($signature, ";base64,")) {
        //     $image_signature = explode(";base64,", $signature)[1];
        // } else {
        //     $image_signature = $signature;
        // }
        // echo $image_signature;
        $remark = $_POST['remark'];
        // echo $remark;
        
        try {
            if( $edit_id == $driver_no){
                $sql_edit_driver = "UPDATE Ship_Driver_Web
                SET
                    Driver_No = '$driver_no',
                    Driver_Name = '$driver_name',
                    Year_of_Birth = '$year_birth',
                    Driver_Address = '$driver_address',
                    Hand_Phone = '$hand_phone',
                    Signature = '$signature',
                    Remark = '$remark',
                    UserID = '$userid',
                    UserDate = getdate(),
                    Type_Driver='$type_indentity'
                WHERE Driver_No = '$edit_id'";
                $exec_edit_driver = odbc_exec($conn, $sql_edit_driver);

                $update_image = "SET ARITHABORT ON;
                UPDATE Ship_Driver_Web SET  Image = CAST('' AS XML).value('xs:base64Binary(sql:column(\"[Signature]\"))[1]', 'VARBINARY(MAX)')
                WHERE Driver_No = '$driver_no'";
                $exec_update_image = odbc_exec($conn, $update_image);

                if ($exec_edit_driver == true) {
                    echo json_encode(array('status' => true, 'Info' => "Chỉnh sửa thành công!"));
                } else {
                    echo json_encode(array('status' => false, 'Info' => 'Chỉnh sửa thất bại!'));
                }

            } else {
                $check_acc = "SELECT count(1) FROM Ship_Driver_Web WHERE Driver_No ='$driver_no' ";
                $rs_check_acc = odbc_exec($conn,$check_acc);
                $check_acc = odbc_result($rs_check_acc,1);  
                if( $check_acc == 0) {
                    $sql_edit_driver = "UPDATE Ship_Driver_Web
                    SET
                        Driver_No = '$driver_no',
                        Driver_Name = '$driver_name',
                        Year_of_Birth = '$year_birth',
                        Driver_Address = '$driver_address',
                        Hand_Phone = '$hand_phone',
                        Driver_Status = '$driver_status',
                        Signature = '$image_signature',
                        Remark = '$remark',
                        UserID = '$userid',
                        UserDate = getdate()
                    WHERE Driver_No = '$edit_id'";
                    $exec_edit_driver = odbc_exec($conn, $sql_edit_driver);

                    $update_image = "SET ARITHABORT ON;
                    UPDATE Ship_Driver_Web SET  Image = CAST('' AS XML).value('xs:base64Binary(sql:column(\"[Signature]\"))[1]', 'VARBINARY(MAX)')
                    WHERE Driver_No = '$driver_no'";
                    $exec_update_image = odbc_exec($conn, $update_image);
                    
                    if ($exec_edit_driver == true) {
                        echo json_encode(array('status' => true, 'Info' => "Chỉnh sửa thành công!"));
                    } else {
                        echo json_encode(array('status' => false, 'Info' => 'Chỉnh sửa thất bại!'));
                    }
                } else {
                    echo json_encode(array('status' => false, 'Info' => 'Dữ liệu đã tồn tại!')); 
                }
            }
        } catch(Exception $e) {
            echo json_encode(array('status' => false, 'Info' => 'Chỉnh sửa thất bại!'));
        }  
    }


    if ($action == 'del_driver') {
        $del_id = $_POST['del_id'];
        try {
            // $sql_del_driver = "DELETE 
            //                     FROM   Ship_Driver_Web
            //                     WHERE  Driver_No = '$del_id'";
            $sql_del_driver = "UPDATE Ship_Driver_Web 
                               SET Driver_Status ='Dropped'
                               WHERE  Driver_No = '$del_id' ";
            $exec_del_driver = odbc_exec($conn, $sql_del_driver);
            if ($exec_del_driver == true) {
                echo json_encode(array('status' => true, 'Info' => "Xóa thành công!"));
            }
        } catch(Exception $e) {
            echo json_encode(array('status' => false, 'Info' => 'Xóa thất bại!'));
        }
    }