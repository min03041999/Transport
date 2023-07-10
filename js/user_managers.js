const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});

const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-success",
    cancelButton: "btn btn-danger",
  },
  buttonsStyling: false,
});

$(document).ready(function () {
  onQueryUser();
});

function getSelect(table) {
  return table.rows(".selected").data()[0];
}
function onEditUser() {
  let table = $("#tb_user_manager").DataTable();
  let row = getSelect(table);
  if (row) {
    $("#header").html("Chỉnh sửa tài khoản");
    $("#Account").val(row.Account_ID);
    $("#Hidden").val(row.Account_ID);
    $("#CompanyID").val(row.Company);
    $("#Name").val(row.Account_Name);
    $("#Password").val(row.Account_PWD);
    $("#modal_add").modal("show");
    onAddUser(2);
  } else {
    Toast.fire({
      icon: "error",
      title: "Vui lòng chọn dữ liệu cần chỉnh sửa!",
    });
  }
}
function onAddUser(check) {
  $("#save").attr("check", check);
  $("label.error").hide();
  if ($("#save").attr("check") == 1) {
    $("#create-add")[0].reset();
    $("#header").html("Tạo tài khoản");
    $("#modal_add").modal("show");
  }
  $("#create-add").validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    rules: {
      Name: {
        required: true,
        validateName: true,
        maxlength: 15,
      },
      Account: {
        required: true,
        validateName: true,
        maxlength: 15,
      },
      CompanyID: {
        required: true,
      },
      Password: {
        required: true,
        minlength: 8,
      },
      hidden: {
        required: true,
      },  
    },
    messages: {
      Name: {
        required: "Vui lòng nhập họ và tên!",
        maxlength: "Hãy nhập tối đa 15 ký tự!",
      },
      Account: {
        required: "Vui lòng nhập tên tài khoản!",
        maxlength: "Hãy nhập tối đa 15 ký tự!",
      },
      CompanyID: {
        required: "Vui lòng nhập tên công ty! ",
      },
      Password: {
        required: "Vui lòng nhập mật khẩu!",
        minlength: "Hãy nhập ít nhất 8 ký tự!",
      },
    },
    submitHandler: function (data) {
      if ($("#save").attr("check") == 1) {
        $.ajax({
          url: "data/data_user_manager.php?action=add",
          data: $("#create-add").serialize(),
          type: "POST",
          success: (res) => {
            response = JSON.parse(res);
            if (response.status == true) {
              $("#modal_add").modal("hide");
              Toast.fire({
                icon: "success",
                title: response.Info,
              });
              $("#create-add")[0].reset();
              $("#tb_user_manager").DataTable().ajax.reload();
            } else {
              $("#create-add")[0].reset();
              $("#modal_add").modal("hide");
              Toast.fire({
                icon: "error",
                title: response.Info,
              });
            }
          },
        });
      } else {
        $.ajax({
          url: "data/data_user_manager.php?action=edit",
          data: $("#create-add").serialize(),
          type: "POST",
          success: (res) => {
            response = JSON.parse(res);
            console.log(response);
            if (response.status == true) {
              $("#modal_add").modal("hide");
              Toast.fire({
                icon: "success",
                title: response.Info,
              });
              $("#create-add")[0].reset();
              $("#Account").val("");
              $("#CompanyID").val("");
              $("#Name").val("");
              $("#Password").val("");
              $("#tb_user_manager").DataTable().ajax.reload();
            } else {
              $("#create-add")[0].reset();
              $("#t").dataTable()._fnAjaxUpdate();
              $("#modal_add").modal("hide");
              Toast.fire({
                icon: "error",
                title: response.Info,
              });
            }
          },
        });
      }
    },
  });

  $.validator.addMethod(
    "validateName",
    function (value, element) {
      return this.optional(element) || /^[^\u00C0-\u1EF9]+$/i.test(value);
    },
    "Vui lòng nhập họ & tên với ký tự không dấu!"
  );
}

function onQueryUser() {
  // let datasearch = $('#search').val()
  $("#tb_user_manager").DataTable({
    ajax: {
      url: "data/data_user_manager.php?action=getdata",
      method: "POST",
      data: {},
      dataSrc: function (d) {
        return d;
      },
    },
    columns: [
      {
        data: "Account_ID",
      },
      {
        data: "Account_Name",
      },
      {
        data: "Company",
      },
      {
        data: "UserID",
      },
      {
        data: "UserDate",
      },
    ],
    searching: false,
    destroy: true,
    pageLength: 10,
    select: true,
  });
}

function onDeleteUser() {
  let table = $("#tb_user_manager").DataTable();
  let row = getSelect(table);
  if (row) {
    swalWithBootstrapButtons
      .fire({
        title: "Bạn có chắc chắn không?",
        text: "Dữ liệu bị xóa sẽ không thể khôi phục!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Vâng, hãy xóa nó!",
        cancelButtonText: "Không, trở lại!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "data/data_user_manager.php?action=delete",
            data: { Account_ID: row.Account_ID },
            type: "POST",
            success: (res) => {
              response = JSON.parse(res);
              if (response.status == true) {
                swalWithBootstrapButtons.fire(
                  "Xóa thành công!",
                  "Dữ liệu bạn chọn đã được xóa.",
                  "thành công"
                );
                $("#tb_user_manager").dataTable()._fnAjaxUpdate();
              } else {
                Swal.fire("Cancelled!", response.msg, "error");
              }
            },
          });
        }
      });
  } else {
    Toast.fire({
      icon: "error",
      title: "Vui lòng chọn dữ liệu cần xóa!",
    });
  }
}
