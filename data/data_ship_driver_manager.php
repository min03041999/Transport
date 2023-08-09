<?php
require('../init.php');
require_once "../PHPEXCEL/PHPExcel.php";
session_start();
$userid = @$_SESSION['Account_ID'];
$companyss = @$_SESSION['Company'];
$position = @$_SESSION['Position'];
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
    $sr_ship_driver_company = isset($_GET['Ship_Driver_Company']) ? $_GET['Ship_Driver_Company'] : "";
    $Name = $_GET['Name'];
    $Select = $_GET['Select'];
    $Condition = '1=1';

    // if($companyss != '') {
    //     $Condition .= " AND Company = '$companyss'";
    // } else {
    //     $Condition .= " AND Company = '$sr_ship_driver_company'";
    // }

    if ($position == '1') {
        if ($sr_ship_driver_company != '') {
            $Condition .= " AND Company = '$sr_ship_driver_company'";
        }
    } else {
        $Condition .= " AND Company = '$companyss'";
    }

    if ($Name != '') {
        $Condition .= "AND Driver_Name like '%$Name%'";
    }

    if ($Select != '') {
        $Condition .= "AND Driver_Status like '$Select'";
    }
    $sql_query_driver = "   SELECT Company, Driver_No, Type_Driver, Driver_Name, Year_of_Birth, Driver_Address, 
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
                                N'$remark',
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
        if ($edit_id == $driver_no) {
            $sql_edit_driver = "UPDATE Ship_Driver_Web
                SET
                    Driver_No = '$driver_no',
                    Driver_Name = '$driver_name',
                    Year_of_Birth = '$year_birth',
                    Driver_Address = '$driver_address',
                    Hand_Phone = '$hand_phone',
                    Signature = '$signature',
                    Remark = N'$remark',
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
            $rs_check_acc = odbc_exec($conn, $check_acc);
            $check_acc = odbc_result($rs_check_acc, 1);
            if ($check_acc == 0) {
                $sql_edit_driver = "UPDATE Ship_Driver_Web
                    SET
                        Driver_No = '$driver_no',
                        Driver_Name = '$driver_name',
                        Year_of_Birth = '$year_birth',
                        Driver_Address = '$driver_address',
                        Hand_Phone = '$hand_phone',
                        Driver_Status = '$driver_status',
                        Signature = '$image_signature',
                        Remark = N'$remark',
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
    } catch (Exception $e) {
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
    } catch (Exception $e) {
        echo json_encode(array('status' => false, 'Info' => 'Xóa thất bại!'));
    }
}

if ($action == 'importexcel') {

    //code...  
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
    $drawings = $sheet->getDrawingCollection();
    $imageList = [];
    $countSuccess = 0;
    $failData = [];

    foreach ($drawings as $drawing) {
        try {
            // Lấy tọa độ của hình ảnh trong file Excel
            $coordinates = $drawing->getCoordinates();

            // Tách tọa độ thành hàng và cột
            $matches = [];
            preg_match('/([A-Z]+)(\d+)/', $coordinates, $matches);
            $row = $matches[2];
            $dataRow = [];
            for ($i = 0; $i < $TotalCol; $i++) {
                // Lấy dữ liệu tương ứng với hàng và cột
                $dataRow[] = $sheet->getCellByColumnAndRow($i, $row)->getValue();
            }

            $company = vn_to_str($dataRow[0]);
            $name = vn_to_str($dataRow[1]);
            $typeDriverNo = $dataRow[3];
            $driverNo = $dataRow[4]; 
            $YearOfBirth = $dataRow[2];
            if (strlen($YearOfBirth) != 4 && strlen($YearOfBirth) != "") {
                $failData[] = [
                    "message" => "Ngày sinh yêu cầu 4 ký tự",
                    "value" => $YearOfBirth,
                    "id" => $driverNo
                ];
                continue;
            }

            //validate driver no
           
            if (strlen($driverNo) != 12 && strlen($driverNo) != 9 && strlen($driverNo) != "") {
                $failData[] = [
                    "message" => "Yêu cầu CMND 9 ký tự, CCCD 12 ký tự",
                    "value" => $driverNo,
                    "id" => $driverNo
                ];
                continue;
            }
    

            $address = vn_to_str($dataRow[5]);
            $handPhone = vn_to_str($dataRow[6]);

            $signature = base64_encode(file_get_contents($drawing->getPath()));

            $remark = $dataRow[7];
            if (strlen($remark) > 50) {
                $failData[] = [
                    "message" => "Ghi chú tối đa 50 ký tự",
                    "value" => $remark,
                    "id" => $driverNo
                ];
                continue;
            }


            $sql_query = "SELECT COUNT(1)     FROM Ship_Driver_Web   WHERE Driver_No = '$driverNo'";
            $rs_query = odbc_result(odbc_exec($conn, $sql_query), 1);

            if ($rs_query == 0) {
                $sql_add_driver = " INSERT INTO Ship_Driver_Web
                ( 
                    Company,
                    Driver_Name,
                    Year_of_Birth,
                    Type_Driver,
                    Driver_No, 
                    Driver_Address,
                    Hand_Phone,
                    Signature,
                    Driver_Status,
                    Remark,
                    YN, 
                    UserID,
                    UserDate
                )
                VALUES
                (
                    '$company',  
                    '$name',
                    '$YearOfBirth',
                    '$typeDriverNo',
                    '$driverNo',
                    '$address',
                    '$handPhone',
                    '$signature',
                    'Active',
                    '$remark',
                    '1', 
                    '$userid',
                    getdate()
                
                )";
                $exec_add_driver = odbc_exec($conn, $sql_add_driver);

                $update_image = "SET ARITHABORT ON;
                UPDATE Ship_Driver_Web SET  Image = CAST('' AS XML).value('xs:base64Binary(sql:column(\"[Signature]\"))[1]', 'VARBINARY(MAX)')
                WHERE Driver_No = '$driverNo'";
                $exec_update_image = odbc_exec($conn, $update_image);
            } else {
                $failData[] = [
                    "message" => "CMND/CCCD Đã tồn tại!",
                    "value" => $driverNo,
                    "id" => $driverNo
                ];
                continue;
            }
            $countSuccess++;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            //throw $th;
        }
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