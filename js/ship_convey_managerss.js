$(function () {
  onQueryShipConvey();
});

function getSelect(table) {
  return table.rows(".selected").data()[0];
}

function onQueryShipConvey() {
  let sr_convey_company = $("#sr-convey-company").val();
  let sr_convey_no = $("#sr-convey-no").val();
  let sr_convey_name = $("#sr-convey-name").val();
  let sr_convey_status = $("#sr-convey-status").val();

  $("#tb-ship-convey").DataTable({
    ajax: {
      url:
        "data/data_ship_convey_manager.php?action=queryConvey&ConveyNo=" +
        sr_convey_no +
        "&ConveyName=" +
        sr_convey_name +
        "&ConveyStatus=" +
        sr_convey_status +
        "&ConveyCompany=" +
        sr_convey_company,
      method: "GET",
      dataSrc: function (d) {
        return d;
      },
    },
    columns: [
      {
        data: "Company",
        className: "nowrap",
      },
      {
        data: "Convey_No",
        className: "nowrap",
      },
      // {
      //   data: "Company",
      // },
      {
        data: "Owner_Name",
        className: "nowrap",
      },
      {
        data: "Year_of_Birth",
        className: "nowrap",
      },
      {
        data: "Type_Identify",
        className: "nowrap",
      },
      {
        data: "Identify_No",
        className: "nowrap",
      },
      {
        data: "Owner_Address",
        className: "nowrap",
      },
      {
        data: "Hand_Phone",
        className: "nowrap",
      },
      {
        data: "Type_Convey",
        className: "nowrap",
      },
      // {
      //   data: "Convey_Status",
      // },
      // {
      //   data: "Mooc",
      // },
      {
        data: "Remark",
        className: "nowrap",
      },
      {
        data: function (row) {
          let Year_of_Birth = row["Year_of_Birth"].toString();
          let Type_Convey = row["Type_Convey"].toString();
          let Owner_Name = row["Owner_Name"];
          let Owner_Address = row["Owner_Address"];
          let Identify_No = row["Identify_No"];
          let Hand_Phone = row["Hand_Phone"];
          let Convey_No = row["Convey_No"];
          return `<button class="btn btn-success" onclick="printCarConvey('${Convey_No}', '${Owner_Name}','${Owner_Address}','${Hand_Phone}','${Year_of_Birth}','${Type_Convey}','${Identify_No}')">In thẻ</button>`;
        },
      },
      // {
      //   data: "YN",
      // },
      // {
      //   data: "LinkGPS",
      // },
    ],
    searching: false,
    destroy: true,
    pageLength: 10,
    select: true,
    orderable: false,
    createdRow: function (row, data, dataIndex) {
      if (data["Convey_Status"] == "Dropped") {
        $(row).css({ "background-color": "#e74a3b", color: "#fff" });
      }
    },
  });
}

function printCarConvey(...item) {
  const url =
    "layout/card_ship_convey.php?action=Ship_Convey&Convey_No=" +
    item[0] +
    "&Owner_Name=" +
    item[1] +
    "&Owner_Address=" +
    item[2] +
    "&Hand_Phone=" +
    item[3] +
    "&Year_of_Birth=" +
    item[4] +
    "&Type_Convey=" +
    item[5] +
    "&Identify_No=" +
    item[6];
  window.open(url);
  console.log(url);
}
function onEditShipConvey() {
  let table = $("#tb-ship-convey").DataTable();
  let row = getSelect(table);
  if (row) {
    $("#header").html("Chỉnh sửa thông tin chủ xe");
    $("#ID").val(row.Convey_No);
    $("#Convey_No").val(row.Convey_No);
    $("#Type_Identify").val(row.Type_Identify);
    $("#Identify_No").val(row.Identify_No);
    $("#Owner_Name").val(row.Owner_Name);
    $("#Year_of_Birth").val(row.Year_of_Birth);
    $("#Owner_Address").val(row.Owner_Address);
    $("#Hand_Phone").val(row.Hand_Phone);
    $("#Type_Convey").val(row.Type_Convey);
    // $("#Convey_Status").val(row.Convey_Status);
    // $("#Mooc").val(row.Mooc);
    // $("#LinkGPS").val(row.LinkGPS);
    $("#Remark").val(row.Remark);

    $("#modal_add_convey").modal("show");
    onAddShipConvey(2);
  } else {
    Toast.fire({
      icon: "error",
      title: "Vui lòng chọn dữ liệu cần chỉnh sửa!",
    });
  }
}

function onAddShipConvey(check) {
  $("#save").attr("check", check);
  $("label.error").hide();
  if ($("#save").attr("check") == 1) {
    $("#header").html("Thêm thông tin chủ xe");
    $("#modal_add_convey").modal("show");
    $("#create-add-convey")[0].reset();
  }
  $("#create-add-convey").validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    rules: {
      Convey_No: {
        required: true,
      },
      Identify_No: {
        required: true,
        validateNumber: true,
        minlength: 9,
      },
      Owner_Name: {
        required: true,
        validateName: true,
      },
      Year_of_Birth: {
        required: true,
        validateNumber: true,
        maxlength: 4,
      },
      Owner_Address: {
        required: true,
        validateName: true,
      },
      Hand_Phone: {
        required: true,
      },
      Type_Convey: {
        required: true,
      },
    },
    messages: {
      Convey_No: {
        required: "Vui lòng nhập biển số xe!",
      },
      Identify_No: {
        required: "Vui lòng nhập số CMND/CCCD!",
        minlength: "Vui lòng nhập ít nhất 9 ký tự!",
      },
      Owner_Name: {
        required: "Vui lòng nhập họ và tên!",
      },
      Year_of_Birth: {
        required: "Vui lòng nhập năm sinh!",
        maxlength: "Vui lòng nhập 4 ký tự!",
      },
      Owner_Address: {
        required: "Vui lòng nhập địa chỉ!",
      },
      Hand_Phone: {
        required: "Vui lòng nhập số điện thoại!",
      },
      Type_Convey: {
        required: "Vui lòng nhập loại xe!",
      },
    },
    submitHandler: function (data) {
      if ($("#save").attr("check") == 1) {
        $.ajax({
          type: "POST",
          url: "data/data_ship_convey_manager.php?action=addConvey",
          data: $("#create-add-convey").serialize(),
          success: function (res) {
            let response = JSON.parse(res);
            //   console.log(response);
            if (response.status == true) {
              $("#modal_add_convey").modal("hide");
              Toast.fire({
                icon: "success",
                title: response.Info,
              });
              $("#tb-ship-convey").dataTable()._fnAjaxUpdate();
            } else {
              $("#modal_add_convey").modal("hide");
              Toast.fire({
                icon: "error",
                title: response.Info,
              });
              $("#tb-ship-convey").dataTable()._fnAjaxUpdate();
            }
          },
        });
      } else {
        $.ajax({
          type: "POST",
          url: "data/data_ship_convey_manager.php?action=editConvey",
          data: $("#create-add-convey").serialize(),
          success: function (res) {
            let response = JSON.parse(res);
            //   console.log(response);
            if (response.status == true) {
              $("#modal_add_convey").modal("hide");
              Toast.fire({
                icon: "success",
                title: response.Info,
              });
              $("#tb-ship-convey").dataTable()._fnAjaxUpdate();
            } else {
              $("#modal_add_convey").modal("hide");
              Toast.fire({
                icon: "error",
                title: response.Info,
              });
              $("#tb-ship-convey").dataTable()._fnAjaxUpdate();
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
    "Vui lòng nhập ký tự không dấu!"
  );
  $.validator.addMethod(
    "validateNumber",
    function (value, element) {
      return this.optional(element) || /^[0-9 ]+$/i.test(value);
    },
    "Vui lòng nhập số!"
  );
}

function onDeleteShipConvey() {
  let table = $("#tb-ship-convey").DataTable();
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
            url: "data/data_ship_convey_manager.php?action=deleteConvey",
            data: { Convey_No: row["Convey_No"] },
            type: "POST",
            success: (res) => {
              response = JSON.parse(res);
              if (response.status == true) {
                swalWithBootstrapButtons.fire(
                  "Xóa thành công!",
                  "Dữ liệu bạn chọn đã được xóa.",
                  "thành công"
                );
                $("#tb-ship-convey").dataTable()._fnAjaxUpdate();
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
