$(document).ready(function() {
  $(document).on("click", "#d_teacher", function(a) {
    var e = $(this).data("id");
    $("#teacherid").val(e), $("#DisapproveModal").css("display", "block")
  }), $(document).on("click", ".ClassDeleteBt", function(a) {
    var e = $("#teacherid").val(),
      s = [];
    s.push({
      name: "action",
      value: "Updateregisterdeactive"
    }, {
      name: "cid",
      value: e
    }), jQuery.post(ajax_url, s, function(a) {
      "success" == a ? location.reload() : ($(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"))
    })
  }), $(document).on("click", "#approved_is", function(a) {
    var e = $(this).data("id"),
      s = [];
    s.push({
      name: "action",
      value: "Updateregisteractive"
    }, {
      name: "cid",
      value: e
    }), jQuery.post(ajax_url, s, function(a) {
      "successsuccess0" == a ? ($("#SuccessModal").css("display", "block"), location.reload()) : ($(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"))
    })
  }), $("#request_table").dataTable({
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
  }), $("#ClassID").change(function() {
    $("#StudentClass").submit()
  }), $("#selectall").click(function() {
    1 == $(this).prop("checked") ? $(".strowselect").prop("checked", !0) : $(".strowselect").prop("checked", !1)
  }), $(".strowselect").click(function() {
    1 != $(this).prop("checked") && $("#selectall").prop("checked", !1)
  }), $("#bulkactionreqest").change(function() {
    var a = $(this).val();
    if ("bulkUsersDisapprove" == a) {
      if (0 == (e = $('input[name^="UID"]').map(function() {
          if (1 == $(this).prop("checked")) return this.value
        }).get()).length) return $(".wpts-popup-return-data").html("No user selected!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"), !1;
      $("#DisapproveModal").css("display", "block"), $(document).on("click", ".ClassDeleteBt", function(a) {
        var s = [];
        s.push({
          name: "action",
          value: "bulkdisaproverequest"
        }), s.push({
          name: "UID",
          value: e
        }), jQuery.post(ajax_url, s, function(a) {
          "success" == a ? location.reload() : ($(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"))
        })
      })
    }
    if ("bulkUsersApprove" == a) {
      var e;
      if (0 == (e = $('input[name^="UID"]').map(function() {
          if (1 == $(this).prop("checked")) return this.value
        }).get()).length) return $(".wpts-popup-return-data").html("No user selected!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"), !1;
      var s = [];
      s.push({
        name: "action",
        value: "bulkaproverequest"
      }), s.push({
        name: "UID",
        value: e
      }), jQuery.post(ajax_url, s, function(a) {
        "success" == a || ($(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#SuccessModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"), location.reload())
      })
    }
  })
});
