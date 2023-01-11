$(document).ready(function() {
  $("#addLeaveDays").click(function() {
    $("#addLeaveDaysBody").toggle()
  }), $(".leaveAdd").click(function() {
    var a = $(this).attr("data-id");
    $("#leaveModalHeader").html("Add Leave Date"), $("#ViewModalContent").html('<form action="" id="addLeaveDateForm" method="post"><div class="wpts-row"><div class="wpts-col-xs-12"><div class="wpts-card"><div class="wpts-panel-heading"><h3 class="wpts-panel-title">Add Leave Date</h3></div><div class="wpts-card-body"><div class="wpts-row"><div class="wpts-col-md-6"><div class="wpts-form-group"> <label class="wpts-label" for="from">From <span class="wpts-required">*</span></label><input type="text" name="spls" class="wpts-form-control spls select_date"></div></div><div class="wpts-col-md-6"><div class="wpts-form-group"><label class="wpts-label" for="from">To <span class="wpts-required">*</span></label><input type="text" name="sple" class="wpts-form-control sple select_date"></div></div><div class="wpts-col-md-12"><div class="wpts-form-group"><label class="wpts-label" for="from">Reason</label><input type="text" name="splr" class="wpts-form-control"></div></div><div class="wpts-col-xs-12"><input type="hidden" name="ClassID" value="' + a + '"><input type="submit" class="wpts-btn wpts-btn-success" id="addLeaveDateSubmit" value="Submit"> </div></div></form>'), $(this).click()
  }), $("#wpts_leave_days").dataTable({
    language: {
      paginate: {
        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
      },
      search: "",
      searchPlaceholder: "Search..."
    },
    dom: '<"wpts-dataTable-top"f>rt<"wpts-dataTable-bottom"<"wpts-length-info"li>p<"clear">>',
    order: [],
    columnDefs: [{
      targets: "nosort",
      orderable: !1
    }],
    drawCallback: function(a) {
      $(this).closest(".dataTables_wrapper").find(".dataTables_paginate").toggle(this.api().page.info().pages > 1)
    },
    responsive: !0
  }), $(".leaveView").click(function() {
    $("#leaveModalHeader").html("Leave Dates"), $("#ViewModalContent").html("");
    var a = $(this).attr("data-id"),
      e = [];
    e.push({
      name: "action",
      value: "getLeaveDays"
    }, {
      name: "cid",
      value: a
    }), $.ajax({
      type: "POST",
      url: ajax_url,
      data: e,
      success: function(a) {
        $("#ViewModalContent").html(a), $(this).click()
      },
      error: function() {
        $(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
      }
    })
  }), $(".leaveDelete").click(function() {
    var a = $(this).attr("data-id");
    $.isNumeric(a) ? $("#leaveModalBody").html("<h4>Are you sure want to delete all dates?</h4><div class='pull-right'><div class='btn btn-default' data-dismiss='modal' id='AllDeleteCancel'>Cancel</div>&nbsp; <div class='btn btn-danger' data-id='" + a + "' id='AllDeleteConfirm'>Confirm</div></div><div style='clear:both'></div>") : $("#leaveModalBody").html("Class id missing can't delete. Please report it to support for deletion"), $("#leaveModalHeader").html("Delete all date"), $("#leaveModal").modal("show")
  }), $(document).on("click", ".dateDelete", function() {
    var a = $(this).attr("data-id");
    $.isNumeric(a) ? $("#leaveModalBody").html("<h4>Are you sure want to delete this dates?</h4><div class='pull-right'><div class='btn btn-default' data-dismiss='modal' id='DateDeleteCancel'>Cancel</div>&nbsp; <div class='btn btn-danger' data-id='" + a + "' id='DateDeleteConfirm'>Confirm</div></div><div style='clear:both'></div>") : $("#leaveModalBody").html("Class id missing can't delete. Please report it to support for deletion"), $("#leaveModalHeader").html("Delete all date"), $("#leaveModal").modal("show")
  }), $(document).on("click", "#d_teacher", function(a) {
    var e = $(this).data("id");
    console.log(e), $("#teacherid").val(e), $("#DeleteModal").css("display", "block")
  }), $(document).on("click", ".ClassDeleteBt", function() {
    var a = $("#teacherid").val(),
      e = [];
    e.push({
      name: "action",
      value: "deleteAllLeaves"
    }, {
      name: "cid",
      value: a
    }), $.ajax({
      type: "POST",
      url: ajax_url,
      data: e,
      success: function(a) {
        "success" == a ? location.reload() : ($(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"))
      }
    })
  }), $(document).on("focus", ".spls", function() {
    $(".spls").datepicker({
      autoclose: !0,
      dateFormat: date_format,
      todayHighlight: !0,
      changeMonth: !0,
      changeYear: !0,
      minDate: "0d",
      beforeShow: function(a, e) {
        $(document).off("focusin.bs.modal")
      },
      onClose: function() {
        $(document).on("focusin.bs.modal")
      }
    })
  }), $(document).on("focus", ".sple", function() {
    $(".sple").datepicker({
      autoclose: !0,
      dateFormat: date_format,
      todayHighlight: !0,
      changeMonth: !0,
      changeYear: !0,
      minDate: "0d",
      beforeShow: function(a, e) {
        $(document).off("focusin.bs.modal")
      },
      onClose: function() {
        $(document).on("focusin.bs.modal")
      }
    })
  }), $(document).on("submit", "#addLeaveDateForm", function(a) {
    a.preventDefault();
    var e = $(this).serializeArray();
    e.push({
      name: "action",
      value: "addLeaveDay"
    }), $.ajax({
      type: "POST",
      url: ajax_url,
      data: e,
      success: function(a) {
        "success" == a ? ($(".wpts-popup-return-data").html("Dates added Successfully"), $("#SuccessModal").css("display", "block"), $("#SavingModal").css("display", "none"), $("#SuccessModal").addClass("wpts-popVisible"), setTimeout(function() {
          location.reload()
        }, 2e3)) : ($(".wpts-popup-return-data").html(a), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")), $("#leaveModal").modal("hide")
      },
      error: function() {
        $(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
      }
    })
  }), $(document).on("click", "#DateDeleteConfirm", function() {
    var a = $(this).attr("data-id"),
      e = [];
    e.push({
      name: "action",
      value: "deleteAllLeaves"
    }, {
      name: "lid",
      value: a
    }), $.ajax({
      type: "POST",
      url: ajax_url,
      data: e,
      success: function(a) {
        "success" == a ? ($(".wpts-popup-return-data").html("Dates added Successfully"), $("#SuccessModal").css("display", "block"), $("#SavingModal").css("display", "none"), $("#SuccessModal").addClass("wpts-popVisible")) : ($(".wpts-popup-return-data").html(a), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")), $("#leaveModal").modal("hide")
      },
      error: function() {
        $(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
      }
    })
  }), $("#teacher_table").on("click", ".wpts-popclick", function() {
    var a = $(this).attr("data-pop");
    $("#" + a).addClass("wpts-popVisible"), $("body").addClass("wpts-bodyFixed")
  }), $("#ClassID").change(function() {
    var a = $(this).val(),
      e = [];
    e.push({
      name: "action",
      value: "getClassYear"
    }, {
      name: "cid",
      value: a
    }), $.ajax({
      type: "POST",
      url: ajax_url,
      data: e,
      success: function(a) {
        try {
          var e = $.parseJSON(a);
          $("#CSDate").val(e.c_sdate), $("#CEDate").val(e.c_edate)
        } catch (a) {}
      },
      error: function() {
        $(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
      }
    })
  })
});
