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

$(function () {
  $("#create-login").validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    rules: {
      Account_ID: {
        required: true,
      },
      Password: {
        required: true,
      },
      // Company: {
      //   required: true,
      // },
    },
    messages: {
      Account_ID: {
        required: "Vui lòng nhập tài khoản!",
      },
      Password: {
        required: "Vui lòng nhập mật khẩu!",
      },
      // Company: {
      //   required: "Vui lòng chọn nhà máy!",
      // },
    },
    submitHandler: function (data) {
      $.ajax({
        type: "POST",
        url: "data/data_login.php?Action=login",
        data: $("#create-login").serialize(),
        success: function (response) {
          let res = JSON.parse(response);
          if (res.status == true) {
            window.location.href = "layout/ship_convey_manager.php";
          } else {
            Toast.fire({
              icon: "error",
              title: res.msg,
            });
          }
        },
      });
    },
  });
});
