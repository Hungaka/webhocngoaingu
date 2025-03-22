const KHOAHOC = [
  { MaKhoaHoc: 1, TenKhoaHoc: "Tiếng Anh" },
  { MaKhoaHoc: 2, TenKhoaHoc: "Tiếng Nhật" },
];
const MUCTIEUHOCTAP = [
  { MaMucTieu: 1, TenMucTieu: "Thông thường", NoiDungMucTieu: "5 từ" },
  { MaMucTieu: 2, TenMucTieu: "Đều đặn", NoiDungMucTieu: "10 từ" },
  { MaMucTieu: 3, TenMucTieu: "Nghiêm túc", NoiDungMucTieu: "15 từ" },
  { MaMucTieu: 4, TenMucTieu: "Cao độ", NoiDungMucTieu: "20 từ" },
];
const USER_CHOOSE = {
  MaKhoaHoc: 0,
  MaMucTieu: 0,
};
$(document).ready(function () {
  $("#choose-enlish").click(function () {
    console.log(USER_CHOOSE);
    $(this).toggleClass("chose");
    if ($(this).hasClass("chose")) {
      USER_CHOOSE.MaKhoaHoc = 1;

      $("#continue-step1").removeClass("no-active");
      $("#continue-step1").addClass("btn-continue");
      $("#continue-step1").click(function () {
        console.log(USER_CHOOSE);
        $("#selection").css("display", "none");
        $("#step-1").addClass("no-active");
        $("#step-2").removeClass("no-active");
        $("#targer").css("display", "flex");
      });
    } else {
      USER_CHOOSE.MaKhoaHoc = 0;
      $("#continue-step1").addClass("no-active");
      $("#continue-step1").removeClass("btn-continue");
      $("#continue-step1").click(function () {
        console.log(USER_CHOOSE);
        $("#selection").css("display", "flex");
        $("#step-1").removeClass("no-active");
        $("#step-2").addClass("no-active");
        $("#targer").css("display", "none");
      });
    }
    if ($(this).hasClass("chose")) {
      $("#choose-japan").removeClass("chose");
    }
    $("#targer__choose-1").click(function () {
      $(this).addClass("targer-chose");
      $("#targer__choose-2").removeClass("targer-chose");
      $("#targer__choose-3").removeClass("targer-chose");
      $("#targer__choose-4").removeClass("targer-chose");
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").removeClass("no-active");
        $("#continue-step2").addClass("btn-continue");
      } else {
        $("#continue-step2").addClass("no-active");
        $("#continue-step2").removeClass("btn-continue");
      }
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").click(function () {
          $("#targer").css("display", "none");
          $("#step-2").addClass("no-active");
          $("#step-3").removeClass("no-active");
          $("#loading").css("display", "flex");
          setTimeout(function () {
            window.location.href = "home_page.html";
          }, 3000);
        });
      }
    });
    $("#targer__choose-2").click(function () {
      $(this).addClass("targer-chose");
      $("#targer__choose-1").removeClass("targer-chose");
      $("#targer__choose-3").removeClass("targer-chose");
      $("#targer__choose-4").removeClass("targer-chose");
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").removeClass("no-active");
        $("#continue-step2").addClass("btn-continue");
      } else {
        $("#continue-step2").addClass("no-active");
        $("#continue-step2").removeClass("btn-continue");
      }
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").click(function () {
          $("#targer").css("display", "none");
          $("#step-2").addClass("no-active");
          $("#step-3").removeClass("no-active");
          $("#loading").css("display", "flex");
          setTimeout(function () {
            window.location.href = "home_page.html";
          }, 3000);
        });
      }
    });
    $("#targer__choose-3").click(function () {
      $(this).addClass("targer-chose");
      $("#targer__choose-2").removeClass("targer-chose");
      $("#targer__choose-1").removeClass("targer-chose");
      $("#targer__choose-4").removeClass("targer-chose");
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").removeClass("no-active");
        $("#continue-step2").addClass("btn-continue");
      } else {
        $("#continue-step2").addClass("no-active");
        $("#continue-step2").removeClass("btn-continue");
      }
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").click(function () {
          $("#targer").css("display", "none");
          $("#step-2").addClass("no-active");
          $("#step-3").removeClass("no-active");
          $("#loading").css("display", "flex");
          setTimeout(function () {
            window.location.href = "home_page.html";
          }, 3000);
        });
      }
    });
    $("#targer__choose-4").click(function () {
      $(this).addClass("targer-chose");
      $("#targer__choose-2").removeClass("targer-chose");
      $("#targer__choose-3").removeClass("targer-chose");
      $("#targer__choose-1").removeClass("targer-chose");
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").removeClass("no-active");
        $("#continue-step2").addClass("btn-continue");
      } else {
        $("#continue-step2").addClass("no-active");
        $("#continue-step2").removeClass("btn-continue");
      }
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").click(function () {
          $("#targer").css("display", "none");
          $("#step-2").addClass("no-active");
          $("#step-3").removeClass("no-active");
          $("#loading").css("display", "flex");
          setTimeout(function () {
            window.location.href = "home_page.html";
          }, 3000);
        });
      }
    });
  });
  $("#choose-japan").click(function () {
    $(this).toggleClass("chose");
    if ($(this).hasClass("chose")) {
      USER_CHOOSE.MaKhoaHoc = 2;
      $("#continue-step1").removeClass("no-active");
      $("#continue-step1").addClass("btn-continue");
      $("#continue-step1").click(function () {
        $("#selection").css("display", "none");
        $("#step-1").addClass("no-active");
        $("#step-2").removeClass("no-active");
        $("#targer").css("display", "flex");
      });
    } else {
      USER_CHOOSE.MaKhoaHoc = 0;
      $("#continue-step1").addClass("no-active");
      $("#continue-step1").removeClass("btn-continue");
      $("#continue-step1").click(function () {
        $("#selection").css("display", "flex");
        $("#step-1").removeClass("no-active");
        $("#step-2").addClass("no-active");
        $("#targer").css("display", "none");
      });
    }
    if ($(this).hasClass("chose")) {
      $("#choose-enlish").removeClass("chose");
    }
    $("#targer__choose-1").click(function () {
      $(this).addClass("targer-chose");
      $("#targer__choose-2").removeClass("targer-chose");
      $("#targer__choose-3").removeClass("targer-chose");
      $("#targer__choose-4").removeClass("targer-chose");
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").removeClass("no-active");
        $("#continue-step2").addClass("btn-continue");
      } else {
        $("#continue-step2").addClass("no-active");
        $("#continue-step2").removeClass("btn-continue");
      }
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").click(function () {
          $("#targer").css("display", "none");
          $("#step-2").addClass("no-active");
          $("#step-3").removeClass("no-active");
          $("#loading").css("display", "flex");
          setTimeout(function () {
            window.location.href = "home_page.html";
          }, 3000);
        });
      }
    });
    $("#targer__choose-2").click(function () {
      $(this).addClass("targer-chose");
      $("#targer__choose-1").removeClass("targer-chose");
      $("#targer__choose-3").removeClass("targer-chose");
      $("#targer__choose-4").removeClass("targer-chose");
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").removeClass("no-active");
        $("#continue-step2").addClass("btn-continue");
      } else {
        $("#continue-step2").addClass("no-active");
        $("#continue-step2").removeClass("btn-continue");
      }
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").click(function () {
          $("#targer").css("display", "none");
          $("#step-2").addClass("no-active");
          $("#step-3").removeClass("no-active");
          $("#loading").css("display", "flex");
          setTimeout(function () {
            window.location.href = "home_page.html";
          }, 3000);
        });
      }
    });
    $("#targer__choose-3").click(function () {
      $(this).addClass("targer-chose");
      $("#targer__choose-2").removeClass("targer-chose");
      $("#targer__choose-1").removeClass("targer-chose");
      $("#targer__choose-4").removeClass("targer-chose");
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").removeClass("no-active");
        $("#continue-step2").addClass("btn-continue");
      } else {
        $("#continue-step2").addClass("no-active");
        $("#continue-step2").removeClass("btn-continue");
      }
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").click(function () {
          $("#targer").css("display", "none");
          $("#step-2").addClass("no-active");
          $("#step-3").removeClass("no-active");
          $("#loading").css("display", "flex");
          setTimeout(function () {
            window.location.href = "home_page.html";
          }, 3000);
        });
      }
    });
    $("#targer__choose-4").click(function () {
      $(this).addClass("targer-chose");
      $("#targer__choose-2").removeClass("targer-chose");
      $("#targer__choose-3").removeClass("targer-chose");
      $("#targer__choose-1").removeClass("targer-chose");
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").removeClass("no-active");
        $("#continue-step2").addClass("btn-continue");
      } else {
        $("#continue-step2").addClass("no-active");
        $("#continue-step2").removeClass("btn-continue");
      }
      if ($(this).hasClass("targer-chose")) {
        $("#continue-step2").click(function () {
          $("#targer").css("display", "none");
          $("#step-2").addClass("no-active");
          $("#step-3").removeClass("no-active");
          $("#loading").css("display", "flex");
          setTimeout(function () {
            window.location.href = "home_page.html";
          }, 3000);
        });
      }
    });
  });
});
