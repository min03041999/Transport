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

    <style type="text/css">
    table,
    th {
        border: 3px solid #ffbb1e;
        height: auto;
        font-weight: 600;
    }

    td {
        padding: 0px 10px;
    }

    table {
        width: 450px;
    }

    @font-face {
        font-family: 'Noto Sans', sans-serif;
        font-style: normal;
        font-weight: normal;
        src: url(http://example.com/fonts/firefly.ttf) format('truetype');
    }

    .title-card {
        font-size: 20px;
        color: white;
        background-color: #ffbb1e;
        padding-top: 4px !important;
    }



    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 0 auto;
        width: 50%;
    }

    #qrcode1 {
        width: 80px;
    }

    #barcode {
        width: 75px;
    }

    .footer-card {
        background-color: #ffbb1e;
        color: white;
        text-align: center;
        text-transform: uppercase;
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body>
    <table class="table">
        <tr>
            <th colspan="4" class="title-card"> THẺ CHỦ XE
            </th>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4" style='text-align:center'> CÔNG TY
                <?php echo $company; ?>
            </td>
        </tr>
        <tr>
            <td colspan="4"> Họ tên:
                <?php echo $Owner_Name; ?>
            </td>
        </tr>
        <tr>
            <td colspan="4">Năm sinh :
                <?php echo $Year_of_Birth; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3"> CMND/CCCD:
                <?php echo $Identify_No; ?>
            </td>
            <td rowspan="4" id='qrcode1'>
                <img id='barcode'
                    src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo $Identify_No ?>&amp;size=100x100"
                    alt="" title="" width="80" />
            </td>
        </tr>
        <tr>
            <td colspan="3"> Điện thoại:
                <?php echo $Hand_Phone; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3"> Số xe:
                <?php echo $Convey_No; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3"> Loại xe:
                <?php echo $Type_Convey; ?>
            </td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4" class="footer-card"> Địa chỉ:
                <?php echo $Owner_Address; ?>
            </td>
        </tr>
    </table>

</body>

</html>
<?php
}
// die();
?>
<?php
$html = ob_get_clean();
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options); 
$dompdf->set_paper('A4', 'portrait');
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("Card_Ship_Driver");
?>