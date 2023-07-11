<?php
    require('../init.php');
    session_start();

    $Action = $_REQUEST['action'];

    $account_id = $_SESSION['Account_ID'];
    $company = $_SESSION['Company'];
    $position = $_SESSION['Position'];

    if($Action == 'queryConvey') {
        $sr_convey_company = isset($_GET['ConveyCompany']) ? $_GET['ConveyCompany'] : "";
        $sr_convey_no = $_GET['ConveyNo'];
        $sr_convey_name = $_GET['ConveyName'];
        $sr_convey_status = $_GET['ConveyStatus'];

        $where = " 1 = 1 ";

        // if($company != '') {
        //     $where .= " AND Company = '$company'";
        // } else {
        //     $where .= " AND Company = '$sr_convey_company'";
        // }

        if($position == '1') {
            if($sr_convey_company != '') {
                $where .= " AND Company = '$sr_convey_company'";
            }
        } else {
            $where .= " AND Company = '$company'";
        }

        if($sr_convey_no != '') {
            $where .= " AND Convey_No LIKE '%$sr_convey_no%'";
        }

        if($sr_convey_name != '') {
            $where .= " AND Owner_Name LIKE '%$sr_convey_name%'";
        }

        if($sr_convey_status != '') {
            $where.= " AND Convey_Status = '$sr_convey_status'";
        }

        $query_convey = "SELECT * FROM Ship_Convey_Web WHERE".$where;
        $rs_convey = odbc_exec($conn, $query_convey);
        // echo $query_convey;
        $item = array();

        while(@$row = odbc_fetch_object($rs_convey)) {
            array_push($item, $row);
        }

        echo json_encode($item);
    }

    if($Action == 'addConvey') {
        $convey_no = $_POST['Convey_No'];
        $type_identify = $_POST['Type_Identify'];
        $identify_no = $_POST['Identify_No'];
        $owner_name = $_POST['Owner_Name'];
        $year_of_birth = $_POST['Year_of_Birth'];
        $owner_address = $_POST['Owner_Address'];
        $hand_phone = $_POST['Hand_Phone'];
        $type_convey = $_POST['Type_Convey'];
        // $convey_status = $_POST['Convey_Status'];
        // $mooc = $_POST['Mooc'];
        // $linkGPS = $_POST['LinkGPS'];
        $remark = $_POST['Remark'];

        $check_exist = "    SELECT COUNT(1)
                            FROM Ship_Convey_Web 
                            WHERE Convey_No = '$convey_no'";
        // echo $check_exist;
        $rs_check = (int)odbc_result(odbc_exec($conn, $check_exist), 1);

        if($rs_check == 0) {
            $insert_convey = "INSERT INTO Ship_Convey_Web
                                (
                                    Convey_No,
                                    Type_Identify,
                                    Identify_No,
                                    Owner_Name,
                                    Year_of_Birth,
                                    Owner_Address,
                                    Hand_Phone,
                                    Type_Convey,
                                    Convey_Status,
                                    Remark,
                                    YN,
                                    Company,
                                    UserID,
                                    UserDate
                                )
                                VALUES
                                (
                                    '$convey_no',
                                    '$type_identify',
                                    '$identify_no',
                                    '$owner_name',
                                    '$year_of_birth',
                                    '$owner_address',
                                    '$hand_phone',
                                    '$type_convey',
                                    'Active',
                                    '$remark',
                                    '1',
                                    '$company',
                                    '$account_id',
                                    getdate()
                                )";
                // echo $insert_convey;
                $rs_insert = odbc_exec($conn, $insert_convey);

                if(odbc_num_rows($rs_insert) > 0) {
                    echo json_encode(array('status' => true, 'Info' => 'Thêm dữ liệu thành công!'));
                } else {
                    echo json_encode(array('status' => false, 'Info' => 'Lỗi dữ liệu!'));
                }
            
        } else {
            echo json_encode(array('status' => false, 'Info' => 'Thông tin phương tiện đã tồn tại!'));
        }
    }

    if($Action == 'editConvey') {
        $id = $_POST['ID'];
        $convey_no = $_POST['Convey_No'];
        $type_identify = $_POST['Type_Identify'];
        $identify_no = $_POST['Identify_No'];
        $owner_name = $_POST['Owner_Name'];
        $year_of_birth = $_POST['Year_of_Birth'];
        $owner_address = $_POST['Owner_Address'];
        $hand_phone = $_POST['Hand_Phone'];
        $type_convey = $_POST['Type_Convey'];
        // $convey_status = $_POST['Convey_Status'];
        // $mooc = $_POST['Mooc'];
        // $linkGPS = $_POST['LinkGPS'];
        $remark = $_POST['Remark'];

        if($id == $convey_no) {
            $update_convey = "UPDATE Ship_Convey_Web
            SET
                Convey_No = '$convey_no',
                Type_Identify = '$type_identify',
                Identify_No = '$identify_no',
                Owner_Name = '$owner_name',
                Year_of_Birth = '$year_of_birth',
                Owner_Address = '$owner_address',
                Hand_Phone = '$hand_phone',
                Type_Convey = '$type_convey',
                Remark = '$remark',
                -- Company = '$company',
                UserID = '$account_id',
                UserDate = getdate()
            WHERE Convey_No = '$convey_no'";
            $rs_update = odbc_exec($conn, $update_convey);

            if(odbc_num_rows($rs_update) > 0) {
                echo json_encode(array('status' => true, 'Info' => 'Cập nhật dữ liệu thành công!'));
            } else {
                echo json_encode(array('status' => false, 'Info' => 'Lỗi dữ liệu!'));
            }
        } else {
            $check_exist = "SELECT COUNT(1) FROM Ship_Convey_Web WHERE Convey_No = '$convey_no'";
            $rs_check = odbc_result(odbc_exec($conn, $check_exist), 1);

            if($rs_check == 0) {
                $update_convey = "UPDATE Ship_Convey_Web
                SET
                    Convey_No = '$convey_no',
                    Type_Identify = '$type_identify',
                    Identify_No = '$identify_no',
                    Owner_Name = '$owner_name',
                    Year_of_Birth = '$year_of_birth',
                    Owner_Address = '$owner_address',
                    Hand_Phone = '$hand_phone',
                    Type_Convey = '$type_convey',
                    Remark = '$remark',
                    -- Company = '$company',
                    UserID = '$account_id',
                    UserDate = getdate()
                WHERE Convey_No = '$id'";
                $rs_update = odbc_exec($conn, $update_convey);

                if(odbc_num_rows($rs_update) > 0) {
                    echo json_encode(array('status' => true, 'Info' => 'Cập nhật dữ liệu thành công!'));
                } else {
                    echo json_encode(array('status' => false, 'Info' => 'Lỗi dữ liệu!'));
                }
            } else {
                echo json_encode(array('status' => false, 'Info' => 'Dữ liệu này đã tồn tại!'));  
            }
        }
    }

    if($Action == 'deleteConvey') {
        $convey_no = $_POST['Convey_No'];

        $delete_convey   = "UPDATE Ship_Convey_Web
                                SET
                                Convey_Status = 'Dropped'
                            WHERE  Convey_No = '$convey_no'";
        $rs_delete = odbc_exec($conn, $delete_convey);

        if(odbc_num_rows($rs_delete) > 0) {
            echo json_encode(array('status' => true, 'Info' => 'Xóa dữ liệu thành công!'));
        } else {
            echo json_encode(array('status' => false, 'Info' => 'Lỗi dữ liệu!'));
        }

    }
?>