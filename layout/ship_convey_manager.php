<?php require("../header.php"); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Ship Convey Manager</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ship Convey Manager</h6>
            <div class="row" style="margin: 30px 0 15px;">
                <div class="col-md-12 col-xl-10">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row" style="align-items: baseline; margin-top: 5px;">
                                <label class="col-md-6">Biển số xe:</label>
                                <input type="text" class="form-control col-md-6" id="sr-convey-no" style="width: 100%;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row" style="align-items: baseline; margin-top: 5px;">
                                <label class="col-md-6">Họ và tên chủ xe:</label>
                                <input type="text" class="form-control col-md-6" id="sr-convey-name"
                                    style="width: 100%;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row" style="align-items: baseline; margin-top: 5px;">
                                <label class="col-md-6">Trạng thái:</label>
                                <select class="form-control col-md-6" id="sr-convey-status" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="Active">Active</option>
                                    <option value="Dropped">Dropped</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" style="margin-top: 5px;">
                            <div class="row col-md-12">
                                <button type='button' class='btn btn-primary' onclick='onQueryShipConvey()'>Tìm
                                    kiếm</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xl-2" style="text-align-last: right;" style="margin-top: 5px;">
                    <button type="button" class="btn btn-primary" onclick="onAddShipConvey(1)">Thêm</button>
                    <button type="button" class="btn btn-success" onclick="onEditShipConvey()">Sửa</button>
                    <button type="button" class="btn btn-danger" onclick="onDeleteShipConvey()">Xóa</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tb-ship-convey" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap;">Biển số xe</th>
                            <!-- <th style="white-space: nowrap;">Công ty</th> -->
                            <th style="white-space: nowrap;">Họ và tên chủ xe</th>
                            <th style="white-space: nowrap;">Năm sinh</th>
                            <th style="white-space: nowrap;">Loại giấy tùy thân</th>
                            <th style="white-space: nowrap;">Số CMND/CCCD</th>
                            <th style="white-space: nowrap;">Địa chỉ</th>
                            <th style="white-space: nowrap;">Điện thoại</th>
                            <th style="white-space: nowrap;">Loại xe</th>
                            <!-- <th style="white-space: nowrap;">Trạng thái</th> -->
                            <!-- <th style="white-space: nowrap;">Mooc</th> -->
                            <th style="white-space: nowrap;">Ghi chú</th>
                            <!-- <th style="white-space: nowrap;">Y/N</th> -->
                            <!-- <th style="white-space: nowrap;">LinkGPS</th> -->
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- Add, Edit -->
    <div class="modal fade" id="modal_add_convey" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form id="create-add-convey">
                    <div class="modal-header">
                        <h3 class="modal-title" style="font-weight: bold" id="header"></h3>
                    </div>
                    <div class="modal-body row">
                        <input type='hidden' id="ID" name='ID' />
                        <div class='col-sm-12 col-md-6'>
                            <label style="font-weight: bold; font-size: 15px">Biển số xe:</label>
                            <input type="text" style='border-radius: 6px; width: 100%;' id="Convey_No" name="Convey_No"
                                class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style="width: 100%">
                            <label style="font-weight: bold; font-size: 15px">Loại giấy tùy thân:</label>
                            <select class="form-control" id="Type_Identify" name="Type_Identify">
                                <option value="CMND">CMND</option>
                                <option value="CCCD">CCCD</option>
                            </select>
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Số CMND/CCCD:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="Identify_No"
                                name="Identify_No" class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Họ và tên:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="Owner_Name"
                                name="Owner_Name" class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%;margin-bottom:10px'>
                            <label style="font-weight: bold; font-size: 15px">Năm sinh:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="Year_of_Birth"
                                name="Year_of_Birth" class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Địa chỉ:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="Owner_Address"
                                name="Owner_Address" class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Điện thoại:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="Hand_Phone"
                                name="Hand_Phone" class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Loại xe:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="Type_Convey"
                                name="Type_Convey" class="form-control" size="10" autocomplete="off">
                        </div>

                        <!-- <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Trạng thái xe:</label>
                            <select class="form-control" id="Convey_Status" name="Convey_Status">
                                <option value="Active" selected>Active</option>
                                <option value="Dropped">Dropped</option>
                            </select>
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Mooc:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="Mooc" name="Mooc"
                                class="form-control" size="10" autocomplete="off">
                        </div>

                        <div class='col-sm-12 col-md-6' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Link GPS:</label>
                            <input type="text" style='border-radius: 6px;  width: 100%;' id="LinkGPS" name="LinkGPS"
                                class="form-control" size="10" autocomplete="off">
                        </div> -->

                        <div class='col-sm-12 col-md-12' style='width:100%'>
                            <label style="font-weight: bold; font-size: 15px">Ghi chú:</label>
                            <textarea type="text" style='border-radius: 6px;  width: 100%;' id="Remark" name="Remark"
                                class="form-control" size="10" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                        <button type="submit" id='save' class="btn btn-primary" check="1">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php require("../footer.php"); ?>