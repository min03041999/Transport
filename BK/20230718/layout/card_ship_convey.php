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
    <link rel="stylesheet" href="../css/sb-admin-2.css">
    <style type="text/css">
    @font-face {
        font-family: 'Noto Sans', sans-serif;
        font-style: normal;
        font-weight: normal;
        src: url(http://example.com/fonts/firefly.ttf) format('truetype');
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 0 auto;
        width: 50%;
    }

    .card_ {
        position: relative;
        display: block;
        margin: auto;
        width: 310px;
        /* height: 300px; */
        border: 2px solid #000;
        border-radius: 8px;
        border-collapse: collapse;
        height: auto;
        padding: 5px 10px 10px;
    }

    .card_header {
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        color: #4e73df;
    }

    .card_header_company {
        text-align: center;
        font-size: 13px;
        font-weight: 600;
        color: #000;
    }

    .card_body_image {
        margin-top: 8px;
        text-align: center;
        padding: 0px 5px;
    }

    .card_body_name {
        text-align: center;
        font-size: 20px;
        font-weight: 600;
        color: #4e73df;
    }

    .card_body_info {
        padding: 0 16px;
    }

    b {
        color: #000;
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body>
    <div class="container">
        <div class="card_">
            <div class="card_header">
                THẺ CHỦ XE
            </div>
            <div class="card_header_company">
                CÔNG TY
                <div><?= $company; ?></div>
            </div>
            <div class="card_body">
                <div class="card_body_image">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo $Identify_No ?>&amp;size=100x100"
                        alt="" title="" width="80" height="80" />
                </div>
                <div class="card_body_name">
                    <?= $Owner_Name; ?>
                </div>
                <div class="card_body_info">
                    <div>
                        <b>Biển số xe: </b>
                        <?= $Convey_No; ?>
                    </div>
                    <div>
                        <b>Năm sinh: </b>
                        <?= $Year_of_Birth; ?>
                    </div>
                    <div>
                        <b>Số điện thoại: </b>
                        <?= $Hand_Phone; ?>
                    </div>
                    <div>
                        <b>Số CMND/CCCD: </b>
                        <?= $Identify_No; ?>
                    </div>
                    <div>
                        <b>Địa chỉ: </b>
                        <?= $Owner_Address; ?>
                    </div>
                    <div>
                        <b>Loại xe: </b>
                        <?= $Type_Convey; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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