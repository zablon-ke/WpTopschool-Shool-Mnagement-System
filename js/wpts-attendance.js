$(document).ready(function() {
  $(".clserror").hide(), $(".clsdate").hide(), $(document).on("click", ".checkAll", function(e) {
    $(this).prop("checked") && $("input[name=absent\\[\\]]").prop("checked", !1)
  }), $(document).on("click", "input[name=absent\\[\\]]", function(e) {
    $(this).prop("checked") && $(".checkAll").prop("checked", !1)
  }), $(".select_date").datepicker({
    autoclose: !0,
    dateFormat: date_format,
    todayHighlight: !0,
    changeMonth: !0,
    changeYear: !0,
    maxDate: 0
  }), $("#AttendanceEnter").click(function() {
    $("#AttendanceClass").parent().parent().find(".clserror").removeClass("error"), $("#AttendanceDate").parent().parent().find(".clsdate").removeClass("error"), $("#AddModalContent").html(""), $("#wpts-error-msg").html("");
    var e = $("#AttendanceClass").val(),
      a = $("#AttendanceDate").val();
    if ("" == e && ($(".clserror").show(), $("#AttendanceClass").parent().parent().find(".clserror").addClass("error")), "" == a && ($(".clsdate").show(), $("#AttendanceDate").parent().parent().find(".clsdate").addClass("error")), "" != e && "" != a) {
      var t = [];
      t.push({
        name: "action",
        value: "getStudentsList"
      }, {
        name: "classid",
        value: e
      }, {
        name: "date",
        value: a
      }), $.ajax({
        type: "POST",
        url: ajax_url,
        data: t,
        beforeSend: function() {
          $("#AttendanceEnter").attr("disabled", "disabled")
        },
        success: function(e) {
          $("#AttendanceEnter").removeAttr("disabled");
          var a = jQuery.parseJSON(e);
          0 == a.status ? ($("#wpts-error-msg").html(a.msg), location.reload(!0)) : $("#AddModalContent").html(a.msg)
        },
        error: function() {
          $("#AttendanceEnter").removeAttr("disabled"), $(".wpts-popup-return-data").html("Something went wrong. Try after refreshing page.."), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
        },
        complete: function() {
          $("#AttendanceEnter").removeAttr("disabled")
        }
      })
    }
  }), $(document).on("click", "#AttendanceSubmit", function(e) {
    if (e.preventDefault(), $('input[type="checkbox"]:checked').length > 0) {
      var a = $("#AttendanceEntryForm").serializeArray();
      a.push({
        name: "action",
        value: "AttendanceEntry"
      }), jQuery.post(ajax_url, a, function(e) {
        "success" == e ? ($(".wpts-popup-return-data").html("Attendance entered successfully!"),
		$("#SuccessModal").css("display", "block"),
		$("#SavingModal").css("display", "none"),
		$("#SuccessModal").addClass("wpts-popVisible"),
		$("#AttendanceEntryForm").trigger("reset"), 
		setTimeout(function() {
		  $(".alert").remove(), 
		  $("#SuccessModal").css("display", "none"),
		  $("#Attendanceview").click()
        }, 2e3)) : "updated" == e ? ($("#formresponse").html("<div class='alert alert-warning'>Attendance updated successfully!</div>"),
		location.reload(!0)) : ($(".wpts-popup-return-data").html("Something went "),
		$("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"),
		$("#WarningModal").addClass("wpts-popVisible"), window.setTimeout(function() {
          $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove()
          })
        }, 5e3))
      })
    } else $(".wpts-popup-return-data").html("If no absent please select Nil at bottom!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
  }), $(document).on("click", ".deleteAttendance", function() {
    if (confirm("Are you want to delete this entry?")) {
      var e = $(this).attr("data-id");
      if ($.isNumeric(e)) {
        var a = [];
        a.push({
          name: "action",
          value: "deleteAttendance"
        }, {
          name: "aid",
          value: e
        }), $.ajax({
          type: "POST",
          url: ajax_url,
          data: a,
          beforeSend: function() {},
          success: function(e) {
            $(".wpts-popup-return-data").html("Attendance entry deleted successfully.."),
			$("#SuccessModal").css("display", "block"),
			$("#SavingModal").css("display", "none"),
			$("#SuccessModal").addClass("wpts-popVisible")
          },
          error: function() {
            $(".wpts-popup-return-data").html("Something went wrong. Try after refreshing page.."), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
          },
          complete: function() {
            $(".pnloader").remove()
          }
        })
      } else $(".wpts-popup-return-data").html("Attendance ID Missing!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
    }
  }), $(".viewAbsentees").click(function() {
    var e = $(this).attr("data-id");
    if ($.isNumeric(e)) {
      var a = [];
      a.push({
        name: "action",
        value: "getAbsentees"
      }, {
        name: "classid",
        value: e
      }), $.ajax({
        type: "POST",
        url: ajax_url,
        data: a,
        beforeSend: function() {},
        success: function(e) {
          $("#ViewModalContent").html(e)
        },
        error: function() {
          $(".wpts-popup-return-data").html("Something went wrong. Try after refreshing page.."), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
        },
        complete: function() {
          $(".pnloader").remove()
        }
      }), $("#ViewModal").modal("show")
    } else $(".wpts-popup-return-data").html("Class ID Missing.."), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
  }), $(document).on("click", ".viewAbsentDates", function() {
    var e = $(this).attr("data-id");
    if ($.isNumeric(e)) {
      var a = [];
      a.push({
        name: "action",
        value: "getAbsentDates"
      }, {
        name: "sid",
        value: e
      }), $.ajax({
        type: "POST",
        url: ajax_url,
        data: a,
        beforeSend: function() {},
        success: function(e) {
          $("#ViewModalContent").html(e)
        },
        error: function() {
          $(".wpts-popup-return-data").html("Something went wrong. Try after refreshing page.."), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
        },
        complete: function() {
          $(".pnloader").remove()
        }
      }), $("#ViewModal").modal("show")
    } else $(".wpts-popup-return-data").html("Class ID Missing.."), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
  }), $(document).on("click", "#Attendanceview", function() {
    $("#AttendanceClass").parent().parent().find(".clserror").removeClass("error"), $("#AttendanceDate").parent().parent().find(".clsdate").removeClass("error"), $("#wpts-error-msg").html();
    var e = $("#AttendanceClass").val(),
      a = $("#AttendanceDate").val();
    if ("" == e && $("#AttendanceClass").parent().parent().find(".clserror").addClass("error"), "" == a && $("#AttendanceDate").parent().parent().find(".clsdate").addClass("error"), "" != e && "" != a) {
      var t = [];
      t.push({
        name: "action",
        value: "getStudentsAttendanceList"
      }, {
        name: "classid",
        value: e
      }, {
        name: "date",
        value: a
      }), $.ajax({
        type: "POST",
        url: ajax_url,
        data: t,
        beforeSend: function() {
          $("#Attendanceview").attr("disabled", "disabled")
        },
        success: function(e) {
          $("#Attendanceview").removeAttr("disabled");
          var a = jQuery.parseJSON(e);
          0 == a.status ? ($(".wpts-popup-return-data").html("Something went wrong. Try after refreshing page.."), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")) : $(".AttendanceView").html(a.msg)
        },
        error: function() {
          $("#Attendanceview").removeAttr("disabled"), $(".wpts-popup-return-data").html("Something went wrong. Try after refreshing page.."), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
        },
        complete: function() {
          $("#Attendanceview").removeAttr("disabled")
        }
      })
    }
  }), $("#verticalTab").easyResponsiveTabs({
    type: "vertical",
    width: "auto",
    fit: !0
  }), $(window).width() < 991 && ($("#verticalTab").find(".wpts-resp-tab-active").removeClass("wpts-resp-tab-active"), $("#verticalTab").find(".wpts-resp-tab-content-active").removeClass("wpts-resp-tab-content-active").css("display", "")), jQuery(window).width() < 991 && ($("#verticalTab").find(".wpts-resp-tab-active").length || $(".wpts-resp-tabs-list .wpts-resp-tab-item:first-child").click())
}), jQuery(window).resize(function() {
  jQuery(window).width() > 991 && ($("#verticalTab").find(".wpts-resp-tab-active").length || $(".wpts-resp-tabs-list .wpts-resp-tab-item:first-child").click())
});
