<?php
require('../init.php');
require_once "../PHPEXCEL/PHPExcel.php";
session_start();

$Action = $_REQUEST['action'];

$account_id = $_SESSION['Account_ID'];
$company = $_SESSION['Company'];
$position = $_SESSION['Position'];

if ($Action == 'queryConvey') {
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

    if ($position == '1') {
        if ($sr_convey_company != '') {
            $where .= " AND Company = '$sr_convey_company'";
        }
    } else {
        $where .= " AND Company = '$company'";
    }

    if ($sr_convey_no != '') {
        $where .= " AND Convey_No LIKE '%$sr_convey_no%'";
    }

    if ($sr_convey_name != '') {
        $where .= " AND Owner_Name LIKE '%$sr_convey_name%'";
    }

    if ($sr_convey_status != '') {
        $where .= " AND Convey_Status = '$sr_convey_status'";
    }

    $query_convey = "SELECT * FROM Ship_Convey_Web WHERE" . $where;
    $rs_convey = odbc_exec($conn, $query_convey);
    // echo $query_convey;
    $item = array();

    while (@$row = odbc_fetch_object($rs_convey)) {
        array_push($item, $row);
    }

    echo json_encode($item);
}

if ($Action == 'addConvey') {
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

    $check_exist = "SELECT COUNT(1)    FROM Ship_Convey_Web  WHERE Convey_No = '$convey_no'";
    $rs_check = (int) odbc_result(odbc_exec($conn, $check_exist), 1);

    if ($rs_check > 0) {
        echo json_encode(array('status' => false, 'Info' => 'Thông tin phương tiện đã tồn tại!'));
        die();
    }

    $check_exist_driver = "SELECT COUNT(1)    FROM Ship_Convey_Web  WHERE Identify_No = '$identify_no'";
    $rs_check_driver = (int) odbc_result(odbc_exec($conn, $check_exist_driver), 1);
    if ($rs_check_driver > 0) {
        echo json_encode(array('status' => false, 'Info' => 'Thông tin CMND/CCCD đã tồn tại!'));
        die();
    }

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
        N'$remark',
        '1',
        '$company',
        '$account_id',
        getdate()
    )";
    // echo $insert_convey;
    $rs_insert = odbc_exec($conn, $insert_convey);

    if (odbc_num_rows($rs_insert) > 0) {
        echo json_encode(array('status' => true, 'Info' => 'Thêm dữ liệu thành công!'));
    } else {
        echo json_encode(array('status' => false, 'Info' => 'Lỗi dữ liệu!'));
    }
}

if ($Action == 'editConvey') {
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

    if ($id == $convey_no) {
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
                Remark = N'$remark',
                -- Company = '$company',
                UserID = '$account_id',
                UserDate = getdate()
            WHERE Convey_No = '$convey_no'";
        $rs_update = odbc_exec($conn, $update_convey);

        if (odbc_num_rows($rs_update) > 0) {
            echo json_encode(array('status' => true, 'Info' => 'Cập nhật dữ liệu thành công!'));
        } else {
            echo json_encode(array('status' => false, 'Info' => 'Lỗi dữ liệu!'));
        }
    } else {
        $check_exist = "SELECT COUNT(1) FROM Ship_Convey_Web WHERE Convey_No = '$convey_no'";
        $rs_check = odbc_result(odbc_exec($conn, $check_exist), 1);

        if ($rs_check == 0) {
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
                    Remark = N'$remark',
                    -- Company = '$company',
                    UserID = '$account_id',
                    UserDate = getdate()
                WHERE Convey_No = '$id'";
            $rs_update = odbc_exec($conn, $update_convey);

            if (odbc_num_rows($rs_update) > 0) {
                echo json_encode(array('status' => true, 'Info' => 'Cập nhật dữ liệu thành công!'));
            } else {
                echo json_encode(array('status' => false, 'Info' => 'Lỗi dữ liệu!'));
            }
        } else {
            echo json_encode(array('status' => false, 'Info' => 'Dữ liệu này đã tồn tại!'));
        }
    }
}

if ($Action == 'deleteConvey') {
    $convey_no = $_POST['Convey_No'];

    $delete_convey = "UPDATE Ship_Convey_Web
                                SET
                                Convey_Status = 'Dropped'
                            WHERE  Convey_No = '$convey_no'";
    $rs_delete = odbc_exec($conn, $delete_convey);

    if (odbc_num_rows($rs_delete) > 0) {
        echo json_encode(array('status' => true, 'Info' => 'Xóa dữ liệu thành công!'));
    } else {
        echo json_encode(array('status' => false, 'Info' => 'Lỗi dữ liệu!'));
    }

}

if ($Action == 'importExcel') {
    set_time_limit(10000);
    if (!isset($_FILES['data_import']['tmp_name'])) {
        echo json_encode(array('status' => false, 'Info' => "Vui lòng chọn file!"));

        die();
    }
    $fileImport = $_FILES['data_import']['tmp_name'];
    $dReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($fileImport));

    // //Chỉ đọc dữ liệu
    // $objData->setReadDataOnly(true);

    // // Load dữ liệu sang dạng đối tượng
    $dataExcel = $dReader->load($fileImport);
    // //Lấy ra số trang sử dụng phương thức getSheetCount();
    // // Lấy Ra tên trang sử dụng getSheetNames();

    // //Chọn trang cần truy xuất
    $sheet = $dataExcel->setActiveSheetIndex(0);

    //Lấy ra số dòng cuối cùng
    $Totalrow = $sheet->getHighestRow();
    //Lấy ra tên cột cuối cùng
    $LastColumn = $sheet->getHighestColumn();
    $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
    $failData = [];
    for ($i = 2; $i <= $Totalrow; $i++) {
        //----Lặp cột
        $data = [];
        for ($j = 0; $j < $TotalCol; $j++) {
            // Tiến hành lấy giá trị của từng ô đổ vào mảng
            $data[] = $sheet->getCellByColumnAndRow($j, $i)->getValue();
        }

        $company = vn_to_str($data[0]);
        $conveyNo = $data[1];
        $name = vn_to_str($data[2]);
        $typeDriverNo = $data[4];
        $driverNo = $data[5];


        if ($conveyNo == "") {
            continue;
        }

        $YearOfBirth = $data[3];
        if (strlen($YearOfBirth) != 4) {
            $failData[] = [
                "message" => "Năm sinh yêu cầu 4 ký tự",
                "value" => $YearOfBirth,
                "id" => $conveyNo
            ];
            continue;
        }

        //validate driver no 
        if (strlen($driverNo) != 12 && strlen($driverNo) != 9) {
            $failData[] = [
                "message" => "Yêu cầu CMND 9 ký tự, CCCD 12 ký tự",
                "value" => $driverNo,
                "id" => $conveyNo
            ];
            continue;
        }

        $address = vn_to_str($data[6]);
        $handPhone = vn_to_str($data[7]);
        $typeConvey = vn_to_str($data[8]);


        $remark = $data[9];
        if (strlen($remark) > 50) {
            $failData[] = [
                "message" => "Ghi chú tối đa 50 ký tự",
                "value" => $remark,
                "id" => $conveyNo
            ];
            continue;
        }
        $check_exist_driver = "SELECT COUNT(1)    FROM Ship_Convey_Web  WHERE Identify_No = '$driverNo'";
        $rs_check_driver = (int) odbc_result(odbc_exec($conn, $check_exist_driver), 1);
        if ($rs_check_driver > 0) {
            $failData[] = [
                "message" => "Thông tin CMND/CCCD đã tồn tại!",
                "value" => $driverNo,
                "id" => $conveyNo
            ];
            continue;
        }

        $check_exist = " SELECT COUNT(1)
        FROM Ship_Convey_Web 
        WHERE Convey_No = '$conveyNo'";
        // echo $check_exist;
        $rs_check = (int) odbc_result(odbc_exec($conn, $check_exist), 1);

        if ($rs_check > 0) {

            $failData[] = [
                "message" => "Biển số xe đã tồn tại!",
                "value" => $conveyNo,
                "id" => $conveyNo
            ];
            continue;
        }
        $insert_convey = "INSERT INTO Ship_Convey_Web
            (
                Company,
                Convey_No,
                Owner_Name,
                Year_of_Birth,
                Type_Identify,
                Identify_No,  
                Owner_Address,
                Hand_Phone,
                Type_Convey,
                Remark,
                Convey_Status, 
                YN, 
                UserID,
                UserDate
            )
            VALUES
            (
                '$company',
                '$conveyNo',
                '$name',
                '$YearOfBirth',
                '$typeDriverNo',
                '$driverNo', 
                '$address',
                '$handPhone',
                '$typeConvey',
                '$remark',
                'Active', 
                '1', 
                '$account_id',
                getdate()
            )";
        // echo $insert_convey;
        $rs_insert = odbc_exec($conn, $insert_convey);

        // add to database
    }
    echo json_encode($failData);
}



function vn_to_str($str)
{

    $unicode = array(

        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

        'd' => 'đ',

        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

        'i' => 'í|ì|ỉ|ĩ|ị',

        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

        'D' => 'Đ',

        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

    );
    foreach ($unicode as $nonUnicode => $uni) {

        $str = preg_replace("/($uni)/i", $nonUnicode, $str);

    }
    return $str;

}
?>