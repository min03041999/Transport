<?php require("../header.php"); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Users Manager</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Users Manager</h6>
            <div class="row" style="margin: 30px 0 10px;">
                <!-- <div class="col-sm-6" style="margin-bottom: 10px;">
                    <button type='button' class='btn btn-primary' onclick='onQuery()'> Query</button>
                </div> -->
                <div class="col-sm-6">
                    <button type="button" class="btn btn-primary" onclick="onAddUser(1)">Thêm</button>
                    <button type="button" class="btn btn-success" onclick="onEditUser()">Sửa</button>
                    <button type="button" class="btn btn-danger" onclick="onDeleteUser()">Xóa</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tb_user_manager" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap;">Tài khoản</th>
                            <th style="white-space: nowrap;">Họ và tên</th>
                            <th style="white-space: nowrap;">Công ty</th>
                            <th style="white-space: nowrap;">ID người tạo</th>
                            <th style="white-space: nowrap;">Ngày tạo</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- Modal -->
<!-- Add, Edit -->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <form id="create-add">
                <div class="modal-header">
                    <h3 class="modal-title" style="font-weight: bold" id="header"></h3>
                </div>
                <div class="modal-body row">
                    <input type="hidden" id='Hidden' name="Hidden">
                    <div class='col-sm-12 col-md-6'>
                        <label style="font-weight: bold; font-size: 15px">Họ và tên:</label>
                        <input type="text" style='border-radius: 6px; width: 100%;' class="form-control" size="10"
                            id="Name" name="Name" autocomplete="off">
                    </div>

                    <div class=' col-sm-12 col-md-6' style='width:100%'>
                        <label style="font-weight: bold; font-size: 15px">Tên công ty:</label>
                        <input type="text" style='border-radius: 6px;  width: 100%;' class="form-control" size="10"
                            id="CompanyID" name="CompanyID" autocomplete="off">
                    </div>

                    <div class=' col-sm-12 col-md-6' style='width:100%;margin-bottom:10px'>
                        <label style="font-weight: bold; font-size: 15px">Tài khoản:</label>
                        <input type="text" style='border-radius: 6px;  width: 100%;' class="form-control" size="10"
                            id="Account" name="Account" autocomplete="off">
                    </div>

                    <div class=' col-sm-12 col-md-6' style='width:100%'>
                        <label style="font-weight: bold; font-size: 15px">Mât khẩu:</label>
                        <input type="password" style='border-radius: 6px;  width: 100%;' class="form-control" size="10"
                            id="Password" name="Password" autocomplete="off">
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
<!-- #/ container -->

<!-- /.container-fluid -->

<?php require("../footer.php"); ?>