<?php
require_once("../dompdf/autoload.inc.php");
require_once("../dompdf/autoload.inc.php");
use Dompdf\Dompdf;
use Dompdf\Options;

ob_start();
ini_set('allow_url_fopen', false);
session_start();
require('../init.php');
?>
<?php
if (($_GET['action']) == 'Ship_driver') {
    $userid = @$_SESSION['Account_ID'];
    $CompanyID = @$_SESSION['Company'];
    $Driver_no = $_REQUEST['driver_no'];
    $Driver_Name = $_REQUEST['driver_name'];
    $Driver_Address = $_REQUEST['driver_address'];
    $Hand_Phone = $_REQUEST['Hand_Phone'];
    $Year_of_Birth = $_REQUEST['Year_of_Birth'];
    $sql = "SELECT * FROM Ship_Driver_Web WHERE Driver_No='$Driver_no'";
    $rs_sql = odbc_exec($conn, $sql);
    $company = odbc_result($rs_sql, 'Company');
    $path_img = '../img/truck.png';
    $data = file_get_contents('../img/truck.png');
    $base64 = 'data:image/png' . ';base64,' . base64_encode($data);
    ?>
<html>

<head>
    <style type="text/css">
    table {
        /* border: 1px solid #00b0ff; */
        border-collapse: collapse;
        height: auto;
    }

    th {
        /* border: 2px solid #696969; */
        border-collapse: collapse;
        height: auto;
    }

    @font-face {
        font-family: 'Noto Sans', sans-serif;
        font-style: normal;
        font-weight: normal;
        src: url(http://example.com/fonts/firefly.ttf) format('truetype');
    }

    td {
        padding: 0px 5px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        position: relative;
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body style="position: relative;">
    <div style='    padding: 3px;
    width: 410px;
    border-collapse: collapse;
    border: 3px solid #00b0ff;'>
        <table style='width: 410px; height: auto; positon: relative;' class="table table-bordered">
            <tr>
                <th colspan='3' style='height: 50px; font-size:25px;color: white;background-color: #00b0ff;'> THẺ TÀI XẾ
                </th>
            </tr>
            <tr>
                <td colspan="3" style='padding:3px'></td>
            </tr>
            <tr>
                <td rowspan="5" id='qrcode1' style="overflow:hidden;width:80px;height:auto;padding: 0px 5px;">
                    <img id='barcode' style=" "
                        src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo $Driver_no ?>&amp;size=100x100"
                        alt="" title="" width="110" height="120" />
                </td>
                <td colspan="2" style='text-align:center;padding: 1px 5px!important; '><b>CÔNG TY
                        <?php echo $company; ?>
                    </b></td>

            </tr>
            <tr>
                <td colspan="2"><b>Họ tên:</b>
                    <?php echo $Driver_Name; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><b>Năm sinh</b>:
                    <?php echo $Year_of_Birth; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><b>CMND/CCCD:</b>
                    <?php echo $Driver_no; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><b>Điện thoại:</b>
                    <?php echo $Hand_Phone; ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding:3px"></td>
            </tr>
            <tr>
                <td colspan="3" style="background-color: #00b0ff;color: white;text-align: center;padding-bottom:5px">
                    <b>Địa chỉ:</b>
                    <?php echo $Driver_Address; ?>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
<?php } ?>
<?php

if (($_GET['action']) == 'Ship_Convey') {
    $userid = @$_SESSION['Account_ID'];
    $CompanyID = @$_SESSION['Company'];
    $Convey_No = $_REQUEST['Convey_No'];
    $Owner_Name = $_REQUEST['Owner_Name'];
    $Owner_Address = $_REQUEST['Owner_Address'];
    $Hand_Phone = $_REQUEST['Hand_Phone'];
    $Year_of_Birth = $_REQUEST['Year_of_Birth'];
    $Type_Convey = $_REQUEST['Type_Convey'];
    $Identify_No = $_REQUEST['Identify_No'];
    $sql = "SELECT * FROM Ship_Convey_Web WHERE Convey_No='$Convey_No'";
    $rs_sql = odbc_exec($conn, $sql);
    $company = odbc_result($rs_sql, 'Company');
    $path_img = '../img/truck.png';
    $data = file_get_contents('../img/truck.png');
    $base64 = 'data:image/png' . ';base64,' . base64_encode($data);
    ?>
<html>

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js">
    </script>
    <style type="text/css">
    table,
    th {
        border: 2px solid #696969;
        border-collapse: collapse;
        height: auto;
    }

    @font-face {
        font-family: 'Noto Sans', sans-serif;
        font-style: normal;
        font-weight: normal;
        src: url(http://example.com/fonts/firefly.ttf) format('truetype');
    }

    .title-card {
        font-size: 15px;
        color: white;
        background-color: #00b0ff;
    }

    td {
        padding: 0 5px;
        border: 1px solid #696969;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
    }

    table {}
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body>
    <table style='width:410px;positon:absolute' class="table table-bordered">
        <tr>
            <th colspan='3' class="title-card"> Thẻ Tài Xế
            </th>
        </tr>
        <tr>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td rowspan="7" id='qrcode1' style="overflow:hidden;width:80px;height:auto;padding:0px 5px">
                <img id='barcode' style=" "
                    src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo $Convey_No ?>&amp;size=100x100"
                    alt="" title="" width="120" height="120" />
            </td>
            <td colspan="2" style='text-align:center'><b>Công Ty
                    <?php echo $company; ?>
                </b></td>

        </tr>
        <tr>
            <td colspan="2"><b>Họ tên:</b>
                <?php echo $Owner_Name; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>Năm sinh</b>:
                <?php echo $Year_of_Birth; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>CMND/CCCD:</b>
                <?php echo $Identify_No; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><b>Điện thoại:</b>
                <?php echo $Hand_Phone; ?>
            </td>
        </tr>

        <tr>
            <td colspan="2"><b>Số xe:</b>
                <?php echo $Convey_No; ?>
            </td>
        </tr>

        <tr>
            <td colspan="2"><b>Loại xe:</b>
                <?php echo $Type_Convey; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" style='height: 5px;'></td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #00b0ff;color: white;text-align: center;"><b>Địa chỉ:</b>
                <?php echo $Owner_Address; ?>
            </td>
        </tr>
    </table>
</body>

</html>
<?php die();} ?>
<?php
$html = ob_get_clean();
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
// $dompdf = new DOMPDF(array('enable_remote' => true));
$dompdf->set_paper('A4', 'portrait');
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("Card_Ship_Driver");
?>