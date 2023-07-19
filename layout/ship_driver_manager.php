<?php 
    require("../header.php"); 
    require('../init.php');
?>

<style>
@media only screen and (max-width: 575px) {
    .col-sm-10.col-md-4 {
        width: 88% !important
    }

    .col-sm-1.col-md-1 {
        width: 0% !important;
        padding: 0px
    }
}

@media only screen and (max-width: 569px) {
    .col-sm-10.col-md-4 {
        width: 80% !important
    }

    .col-sm-1.col-md-1 {
        width: 0% !important;
        padding: 0px
    }
}

canvas {
    display: block !important
}
</style>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Quản lý tài xế</h1>
    <div id="qrcode"></div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Quản lý tài xế</h6>
            <div class="row" style="margin: 30px 0 15px;">
                <div class="col-md-12 col-xl-12">
                    <div class="row">
                        <?php if($_SESSION['Position'] == "1") {?>
                        <div class="col-md-3">
                            <div class="row" style="align-items: baseline; margin-top: 5px;">
                                <label class="col-md-6">Công ty:</label>
                                <select class="form-control col-md-6" id="sr-ship-driver-company" style="width: 100%;">
                                    <?php 
                                            $query_company = "SELECT DISTINCT Company FROM  Transport_User";
                                            $rs_company = odbc_exec($conn, $query_company);

                                            while (odbc_fetch_row($rs_company)) {
                                                $company = odbc_result($rs_company, "Company");
                                                echo "<option value='$company'>$company</option>";
                                            }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-md-3">
                            <div class="row" style="align-items: baseline; margin-top: 5px;">
                                <label class="col-md-6">Họ và tên chủ xe:</label>
                                <input type="text" class="form-control col-md-6" id="search" style="width: 100%;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row" style="align-items: baseline; margin-top: 5px;">
                                <label class="col-md-6">Trạng thái:</label>
                                <select class="form-control col-md-6" id="select_status_ship" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="Active">Hoạt động</option>
                                    <option value="Dropped">Ngưng hoạt động</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" style="margin-top: 5px;">
                            <div class="row col-md-12">
                                <button type='button' class='btn btn-primary' onclick='onQuery()'>Tìm
                                    kiếm</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12" style="margin-top: 20px;">
                    <button type="button" class="btn btn-primary" onclick="onAddShipDriver(1)">Thêm</button>
                    <button type="button" class="btn btn-warning" onclick="onEditShipDriver()">Sửa</button>
                    <button type="button" class="btn btn-danger" onclick="onDeleteShipDriver()">Xóa</button>
                    <button type="button" class="btn btn-success"
                        onclick="$('#modal_import_driver').modal('show')">Import
                        Excel</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tb_ship_driver_manager" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <!-- <th style="white-space: nowrap;">Công ty</th> -->
                            <th>Công ty</th>
                            <th>Họ và tên tài xế</th>
                            <th>Năm sinh</th>
                            <th>Loại giấy tờ tùy thân</th>
                            <th>Số CMND/CCCD</th>
                            <th>Địa chỉ</th>
                            <th>Điện thoại</th>
                            <th>Chữ ký</th>
                            <!-- <th style="white-space: nowrap;">Trạng thái</th> -->
                            <th>Ghi chú</th>
                            <!-- <th style="white-space: nowrap;">YN</th> -->
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- Add, Edit -->

    <div class="modal fade" id="modal_add_driver" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form id="create-add-driver">
                    <div class="modal-header">
                        <h3 class="modal-title" style="font-weight: bold" id="header"></h3>
                    </div>
                    <div class="modal-body row">
                        <input type='hidden' id="edit_id" name='edit_id' />
                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Họ và tên tài xế:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="driver_name"
                                name="driver_name" class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%;margin-bottom:10px'>
                            <label style="font-weight: bold; font-size: 15px">Năm sinh:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="year_birth"
                                name="year_birth" class="form-control" size="10" autocomplete="off">
                        </div>
                        <div class='col-sm-12 col-md-6'>
                            <label style="font-weight: bold; font-size: 15px">Loại giấy tờ tùy thân:</label>
                            <select class="form-control " id='select_type_indentity' name="select_type_indentity"
                                style="height:40px!important;border-radius: 5px;" aria-label="Default select example">
                                <option value="CCCD">CCCD</option>
                                <option value="CMND">CMND</option>
                            </select>
                        </div>
                        <div class='col-sm-12 col-md-6'>
                            <label style="font-weight: bold; font-size: 15px">Số CMND/CCCD:</label>
                            <input type="text" style='border-radius: 6px; width: 100%;' id="driver_no" name="driver_no"
                                class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Địa chỉ:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="driver_address"
                                name="driver_address" class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Điện thoại:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="hand_phone"
                                name="hand_phone" class="form-control" size="10" autocomplete="off">
                        </div>

                        <!-- <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Trạng thái:</label>
                            <select class="form-control" id="driver_status" name="driver_status">
                                <option value="Active" selected>Active</option>
                                <option value="Dropped">Dropped</option>
                            </select>
                        </div> -->
                        <!-- <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label type style="font-weight: bold; font-size: 15px">Công ty:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="driver_company"
                                name="driver_company" class="form-control" size="10" autocomplete="off" readonly>
                        </div> -->

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Ký tên</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="sig" name="sig" readonly
                                class="form-control " size="10">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px; visibility: collapse;">Ký tên</label>
                            <div style="display: flex; gap: 10px;">
                                <label for="upload" class="upload-label btn btn-success">
                                    <span class="fa fa-upload" style="white-space: nowrap; font-size: 14px;"> Tải
                                        hình</span>
                                    <input type="file" class="upload" id="upload" style="display: none"
                                        accept="image/png, image/gif, image/jpeg" />
                                </label>
                                <div>
                                    <button type="button" class='btn btn-success' onclick='createSig()'><span
                                            class="fa fa-plus" style="white-space: nowrap; font-size: 14px;"> Tạo chữ
                                            ký</span></button>
                                </div>
                            </div>
                        </div>

                        <div class='col-sm-12 col-md-12' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Ghi chú:</label>
                            <textarea type="text" style='border-radius: 6px;  width: 100%;' id="remark" name="remark"
                                class="form-control" size="10" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" id='save_ship' check=1>Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- modal import excel -->
    <div class="modal fade" id="modal_import_driver" tabindex="-1" role="dialog" aria-labelledby="add"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form id="import_driver" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title" style="font-weight: bold" id="header">Import Excel</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body row">
                        <!-- <div class='col-md-12' style="text-align-last: right;">
                            <a href="#"
                                onclick="window.open(window.location.origin+'/transport/data/ship_driver.xlsx')">Tải
                                xuống file mẫu</a>
                        </div> -->
                        <div class='col-md-12'>
                            <label style="font-weight: bold; font-size: 15px">Chọn file:</label>
                            <a href="#"
                                onclick="window.open(window.location.origin+'/transport/data/ship_drivers.xlsx')"
                                style="float: right;">Tải
                                xuống file mẫu</a>
                            <input type="file"
                                style='border-radius: 6px; width: 100%;height: calc(1.5em + 0.75rem + 7px)'
                                id="file_import" name="file_import" class="form-control">
                        </div>
                        <div class='col-md-12 mt-3'>
                            <button type="submit" id='btn-start-import' class="btn btn-primary  w-100">Import</button>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    </div> -->
                </form>
            </div>
        </div>
    </div>

    <!-- modal import excel errors message -->
    <div class="modal fade  " id="modal_import_driver_errors" tabindex="-1" role="dialog" aria-labelledby="add"
        aria-hidden="fasle">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold" id="header">Thông báo lỗi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive ">
                        <table class="table table-bordered" id="tb_ship_driver_status" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <!-- <th style="white-space: nowrap;">Công ty</th> -->
                                    <th>CCCD/CMND</th>
                                    <th>Lỗi</th>
                                    <th>Giá trị</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Signature -->
    <div class="modal fade" id="modal_Sig" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true"
        style='padding-right:0px'>
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="font-weight: bold" id="header">Bảng tạo chữ ký tay</h3>
                </div>
                <div class="modal-body">
                    <canvas id="signature-pad" class="signature-pad"
                        style="display: block; margin: auto; border: 1px solid rgb(223, 223, 223);" width="300"
                        height=200></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger create" data-dismiss="modal"
                        onclick='closeModal()'>Đóng</button>
                    <button type="button" class="btn btn-primary" id="save">Lưu</button>
                    <button type="button" class="btn btn-success" id="clear">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Show Image -->
    <div class="modal fade" id="modal_Display_Sig" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true"
        style='padding-right:0px'>
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body body-signature" style='padding:0'>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require("../footer.php"); ?>