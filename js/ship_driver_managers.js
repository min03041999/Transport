//Signature
var signaturePad = new SignaturePad(document.getElementById("signature-pad"), {
  backgroundColor: "#fff",
  penColor: "rgb(0, 0, 0)",
});

$(document).ready(function () {
  onQuery();

  var saveButton = document.getElementById("save");
  var cancelButton = document.getElementById("clear");

  // let format_base64 =
  //   "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/4gHYSUNDX1BST0ZJTEUAAQEAAAHIAAAAAAQwAABtbnRyUkdCIFhZWiAH4AABAAEAAAAAAABhY3NwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAA9tYAAQAAAADTLQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlkZXNjAAAA8AAAACRyWFlaAAABFAAAABRnWFlaAAABKAAAABRiWFlaAAABPAAAABR3dHB0AAABUAAAABRyVFJDAAABZAAAAChnVFJDAAABZAAAAChiVFJDAAABZAAAAChjcHJ0AAABjAAAADxtbHVjAAAAAAAAAAEAAAAMZW5VUwAAAAgAAAAcAHMAUgBHAEJYWVogAAAAAAAAb6IAADj1AAADkFhZWiAAAAAAAABimQAAt4UAABjaWFlaIAAAAAAAACSgAAAPhAAAts9YWVogAAAAAAAA9tYAAQAAAADTLXBhcmEAAAAAAAQAAAACZmYAAPKnAAANWQAAE9AAAApbAAAAAAAAAABtbHVjAAAAAAAAAAEAAAAMZW5VUwAAACAAAAAcAEcAbwBvAGcAbABlACAASQBuAGMALgAgADIAMAAxADb/2wBDABALDA4MChAODQ4SERATGCgaGBYWGDEjJR0oOjM9PDkzODdASFxOQERXRTc4UG1RV19iZ2hnPk1xeXBkeFxlZ2P/2wBDARESEhgVGC8aGi9jQjhCY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2P/wAARCADIASwDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AKAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD//Z";
  saveButton.addEventListener("click", function (event) {
    var data = signaturePad.toDataURL("image/jpeg", 0.5);
    if (signaturePad.isEmpty()) {
      Toast.fire({
        icon: "error",
        title: "Vui lòng cung cấp chữ ký trước khi lưu!",
      });
      return;
    } else {
      let base64_ = data.split(";base64,")[1];
      $("#sig").val(base64_);
      $("#modal_add_driver").modal("show");
      $("#modal_Sig").modal("hide");
    }
  });

  cancelButton.addEventListener("click", function (event) {
    $("#sig").val("");
    signaturePad.clear();
  });
});

function onQuery() {
  let sr_ship_driver_company = $("#sr-ship-driver-company").val();
  let NameDriver = $("#search").val();
  let Select = $("#select_status_ship").val();
  $("#tb_ship_driver_manager").DataTable({
    createdRow: function (row, data, dataIndex) {
      if (data.Driver_Status == "Dropped") {
        $(row).css({ "background-color": "#e74a3b", color: "#fff" });
      }
    },
    ajax: {
      url: "data/data_ship_driver_manager.php?action=get_data_driver",
      method: "GET",
      data: {
        Name: NameDriver,
        Select: Select,
        Ship_Driver_Company: sr_ship_driver_company,
      },
      dataSrc: function (d) {
        return d;
        // console.log(d);
      },
    },
    columns: [
      {
        data: "Company",
        className: "nowrap",
      },
      {
        data: "Driver_Name",
        className: "nowrap",
      },
      {
        data: "Year_of_Birth",
        className: "nowrap",
      },
      {
        data: "Driver_No",
        className: "nowrap",
      },
      {
        data: "Driver_Address",
        className: "nowrap",
      },
      {
        data: "Hand_Phone",
        className: "nowrap",
      },
      {
        data: function (row) {
          let signature =
            row["Signature"] + row["Signature1"] + row["Signature2"];
          return `<img style='display:block; margin: auto; width:100px;height:100px; border: 1px solid rgb(223, 223, 223);' id='base64image' src='data:image/jpeg;base64,${signature}' />`;
        },
      },
      // {
      //   data: "Driver_Status",
      // },
      {
        data: "Remark",
        className: "nowrap",
      },
      // {
      //   data: "YN",
      // },
      {
        data: function (row) {
          let driver_no = row["Driver_No"].toString();
          let driver_name = row["Driver_Name"].toString();
          let driver_address = row["Driver_Address"];
          let hand_phone = row["Hand_Phone"];
          let Year_of_Birth = row["Year_of_Birth"];
          return `<button class="btn btn-success" onclick="printCarDriver('${driver_no}', '${driver_name}','${driver_address}','${hand_phone}','${Year_of_Birth}')">In thẻ</button>`;
        },
      },
    ],
    searching: false,
    destroy: true,
    ordering: false,
    pageLength: 10,
    select: true,
  });
}

function getSelect(table) {
  return table.rows(".selected").data()[0];
}
$(document).on("click", "#base64image", function () {
  let table = $("#tb_ship_driver_manager").DataTable();
  let row = getSelect(table);
  let sig_real = row.Signature + row.Signature1 + row.Signature2;
  let img_sig =
    "<img style='display:block; margin: auto; width:100%;height:100%; border: 1px solid rgb(223, 223, 223);' id='base64image' src='data:image/jpeg;base64," +
    sig_real +
    "'/>";
  $(".body-signature").html(img_sig);
  $("#modal_Display_Sig").modal("show");
});
function createSig() {
  $("#modal_Sig").modal({
    backdrop: "static",
    keyboard: true,
  });
  $("#modal_Sig").modal("show");
  $("#modal_add_driver").modal("hide");
}
function closeModal() {
  $("#modal_Sig").modal("hide");
  $("#modal_add_driver").modal("show");
}
function onAddShipDriver(check) {
  $("#save_ship").attr("check", check);
  $("label.error").hide();
  if ($("#save_ship").attr("check") == 1) {
    signaturePad.clear();
    $("#header").html("Thêm thông tin tài xế");
    $("#modal_add_driver").modal("show");
    $("#create-add-driver")[0].reset();
  }
  $("#create-add-driver").validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    rules: {
      driver_no: {
        required: true,
        validateYear: true,
        minlength: 9,
        maxlength: 12,
      },
      driver_name: {
        required: true,
        validateName: true,
      },
      year_birth: {
        required: true,
        validateYear: true,
        maxlength: 4,
      },
      driver_address: {
        required: true,
        validateName: true,
      },
      hand_phone: {
        required: true,
      },
      sig: {
        required: true,
      },
      // Remark: {
      //   validateName: true,
      // },
    },
    messages: {
      driver_no: {
        required: "Vui lòng nhập số CMND/CCCD!",
        minlength: "Vui lòng nhập ít nhất 9 ký tự!",
        maxlength: "Vui lòng nhập không quá 12 ký tự!",
      },
      driver_name: {
        required: "Vui lòng nhập họ và tên tài xế!",
      },
      year_birth: {
        required: "Vui lòng nhập năm sinh! ",
        maxlength: "Vui lòng nhập tối đa 4 ký tự!",
      },
      driver_address: {
        required: "Vui lòng nhập địa chỉ!",
      },
      hand_phone: {
        required: "Vui lòng nhập số điện thoại!",
      },
      sig: {
        required: "Vui lòng chọn nút tạo!",
      },
    },
    submitHandler: function (data) {
      if ($("#save_ship").attr("check") == 1) {
        $.ajax({
          url: "data/data_ship_driver_manager.php?action=add_driver",
          data: $("#create-add-driver").serialize(),
          type: "POST",
          success: (res) => {
            let response = JSON.parse(res);
            //   console.log(response);
            if (response.status == true) {
              $("#modal_add_driver").modal("hide");
              Toast.fire({
                icon: "success",
                title: response.Info,
              });
              $("#create-add-driver")[0].reset();
              $("#tb_ship_driver_manager").DataTable().ajax.reload();
              signaturePad.clear();
            } else {
              $("#modal_add_driver").modal("hide");
              Toast.fire({
                icon: "error",
                title: response.Info,
              });
              $("#create-add-driver")[0].reset();
              $("#tb_ship_driver_manager").DataTable().ajax.reload();
              signaturePad.clear();
            }
          },
        });
      } else {
        $.ajax({
          url: "data/data_ship_driver_manager.php?action=edit_driver",
          data: $("#create-add-driver").serialize(),
          type: "POST",
          success: (res) => {
            let response = JSON.parse(res);
            // console.log(response);
            if (response.status == true) {
              $("#modal_add_driver").modal("hide");
              Toast.fire({
                icon: "success",
                title: response.Info,
              });
              // $("#create-add-driver").reset();
              $("#tb_ship_driver_manager").DataTable().ajax.reload();
            } else {
              $("#modal_add_driver").modal("hide");
              Toast.fire({
                icon: "error",
                title: response.Info,
              });
              $("#tb_ship_driver_manager").DataTable().ajax.reload();
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
  $.validator.addMethod(
    "validateYear",
    function (value, element) {
      return this.optional(element) || /^[0-9 ]+$/i.test(value);
    },
    "Vui lòng nhập số!"
  );
}

function draw() {
  let canvas = $("#signature-pad")[0];
  canvas.height = 200;
  canvas.width = 300;
  var ctx = canvas.getContext("2d");

  // create new image object to use as pattern
  var img = new Image();
  img.src = "data:image/jpeg;base64," + $("#sig").val();
  img.onload = function () {
    // create pattern
    var ptrn = ctx.createPattern(img, "no-repeat");
    ctx.fillStyle = ptrn;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
  };
}

function onEditShipDriver() {
  let table = $("#tb_ship_driver_manager").DataTable();
  let row = getSelect(table);
  if (row.Driver_Status == "Dropped") {
    Toast.fire({
      icon: "error",
      title: "Dữ liệu này đã được ngưng hoạt động!",
    });
  } else {
    if (row) {
      $("#header").html("Sửa thông tin tài xế");
      $("#modal_add_driver").modal("show");
      $("#edit_id").val(row["Driver_No"]);
      $("#driver_no").val(row["Driver_No"]);
      $("#driver_name").val(row["Driver_Name"]);
      $("#year_birth").val(row["Year_of_Birth"]);
      $("#driver_address").val(row["Driver_Address"]);
      $("#hand_phone").val(row["Hand_Phone"]);
      $("#select_type_indentity").val(row["Type_Driver"]);
      $("#remark").val(row["Remark"]);
      $("#driver_company").val(row["Company"]);
      $("#hidden_ship").val(row["driver_no"]);
      let signature = row["Signature"] + row["Signature1"] + row["Signature2"];
      $("#sig").val(signature);
      draw();
      onAddShipDriver(2);
    } else {
      Toast.fire({
        icon: "error",
        title: "Vui lòng chọn dữ liệu !",
      });
    }
  }
}

function onDeleteShipDriver() {
  let table = $("#tb_ship_driver_manager").DataTable();
  let row = getSelect(table);
  if (row.Driver_Status == "Dropped") {
    Toast.fire({
      icon: "error",
      title: "Dữ liệu này đã được ngưng hoạt động!",
    });
  } else {
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
              url: "data/data_ship_driver_manager.php?action=del_driver",
              data: { del_id: row["Driver_No"] },
              type: "POST",
              success: (res) => {
                response = JSON.parse(res);
                if (response.status == true) {
                  swalWithBootstrapButtons.fire(
                    "Xóa thành công!",
                    "Dữ liệu bạn chọn đã được xóa.",
                    "thành công"
                  );
                  $("#tb_ship_driver_manager").dataTable()._fnAjaxUpdate();
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
}

function printCarDriver(...item) {
  // console.log(driver_no, driver_name);
  // var qrcode= new QRCode("qrcode", 'haha');
  //var jpegUrl = canvas.toDataURL("image/jpeg");

  // qrcode.makeCode("http://naver.com")
  window.open(
    "layout/card_ship_driver.php?action=Ship_driver&driver_no=" +
      item[0] +
      "&driver_name=" +
      item[1] +
      "&driver_address=" +
      item[2] +
      "&Hand_Phone=" +
      item[3] +
      "&Year_of_Birth=" +
      item[4]
  );
}
function toPdf() {}

//Upload Signature

// const uploadImage = async (event) => {
//   const file = event.target.files[0];
//   const base64 = await convertBase64(file);
//   let base64_ = base64.split(";base64,")[1];
//   $("#sig").val(base64_);
//   draw();
// };

// $("#upload").change(function (e) {
//   uploadImage(e);
// });

const convertBase64 = (file) => {
  return new Promise((resolve, reject) => {
    const fileReader = new FileReader();
    fileReader.readAsDataURL(file);

    fileReader.onload = () => {
      resolve(fileReader.result);
    };

    fileReader.onerror = (error) => {
      reject(error);
    };
  });
};

const uploadImage = async (event, width, height) => {
  try {
    const file = event.target.files[0];
    const base64 = await convertBase64(file);
    const img = new Image();

    img.onload = () => {
      const canvas = document.createElement("canvas");
      canvas.width = width;
      canvas.height = height;
      const ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0, width, height);
      const resizedBase64 = canvas.toDataURL("image/jpeg");
      let base64_ = resizedBase64.split(";base64,")[1];
      $("#sig").val(base64_);
      draw();
    };

    img.src = base64;
  } catch (error) {
    console.error(error);
  }
};

$("#upload").change(function (e) {
  uploadImage(e, 300, 200); // set width and height as desired
});
