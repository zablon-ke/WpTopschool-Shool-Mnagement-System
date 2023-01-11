$(document).ready(function() {
  $(".subjectdataTable").dataTable({
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
  });
  $("#listofsubjects").dataTable({
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
  }), $("#AddSubjectButton").click(function() {
    $("#SClassName").text($("#ClassID option:selected").text()), $("#SCID").val($("#ClassID").val()), $("#AddSubjectModal").modal("show")
  }), $("#ClassID").change(function() {
    $("#SubjectList-Form").submit()
  }), $("#ShowExtraFields").click(function() {
    $(".SubjectExtraDetails").toggle()
  }), "all" == $.trim($("#ClassID").val()) ? $("#subdisable").attr("disabled", "disabled") : $("#subdisable").attr("disabled", !1), $("#SubjectEntryForm").validate({
    onkeyup: !1,
    ignore: [],
    rules: {
      "SNames[]": {
        required: !0,
        minlength: 2
      },
      SCID: {
        required: !0,
        number: !0
      },
      STeacherID: {
        number: !0
      }
    },
    messages: {
      SName: {
        required: "Please enter Subject Name",
        minlength: "Subject must consist of at least 2 characters"
      },
      SCID: {
        required: "Class ID missing please refresh"
      }
    },
    submitHandler: function(a) {
      var e = $("#SubjectEntryForm").serializeArray();
      e.push({
        name: "action",
        value: "AddSubject"
      }), $.ajax({
        type: "POST",
        url: ajax_url,
        data: e,
        success: function(a) {
          if ("success" == a) {
            $(".wpts-popup-return-data").html("Subject created successfully !"), $("#SuccessModal").css("display", "block"), $("#SavingModal").css("display", "none"), $("#SuccessModal").addClass("wpts-popVisible");
            var e = $("#wpts_locationginal1").val() + "admin.php?page=sch-subject";
            setTimeout(function() {
              window.location.href = e
            }, 1e3);
            $("#s_submit").attr("disabled", "disabled"), $("#SubjectEntryForm").trigger("reset")
          } else $(".wpts-popup-return-data").html(a), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible")
        }
      })
    }
  }), $(".EditSubjectLink").click(function() {
    var a = $(this).attr("sid"),
      e = [];
    e.push({
      name: "action",
      value: "SubjectInfo"
    }), e.push({
      name: "sid",
      value: a
    }), $.ajax({
      type: "POST",
      url: ajax_url,
      data: e,
      beforeSend: function() {
        $("#SavingModal").css("display", "block"), $("#u_teacher").attr("disabled", "disabled")
      },
      success: function(a) {
        var e = $.parseJSON(a);
        if ("object" == typeof e) {
          $("#SRowID").val(e.id), $("#ESClassID").val(e.class_id), $("#EditSCode").val(e.sub_code), $("#EditSName").val(e.sub_name), $("#EditBName").val(e.book_name);
          try {
            $("#EditSTeacherID option[value=" + e.sub_teach_id + "]").attr("selected", "selected")
          } catch (a) {}
          $("#EditSubjectModal").modal("show"), $("#u_teacher").attr("disabled", "disabled")
        } else $("#InfoModalTitle").text("Error Information!"), $("#InfoModalBody").html("<h3>Sorry! No data retrived!</h3><span class='text-muted'>You can refresh page and try again</span>"), $("#InfoModal").modal("show")
      },
      error: function() {
        $("#InfoModalTitle").text("Error Information!"), $("#InfoModalBody").html("<h3>Sorry! File not reachable!</h3><span class='text-muted'>Check your internet connection!</span>"), $("#InfoModal").modal("show")
      }
    })
  }), $("#SEditForm").validate({
    onkeyup: !1,
    rules: {
      EditSName: {
        required: !0,
        minlength: 2
      },
      EditSTeacherID: {
        number: !0
      }
    },
    messages: {
      SName: {
        required: "Please enter Subject Name",
        minlength: "Subject must consist of at least 2 characters"
      }
    },
    submitHandler: function(a) {
      var e = $("#SEditForm").serializeArray();
      e.push({
        name: "action",
        value: "UpdateSubject"
      }), $.ajax({
        type: "POST",
        url: ajax_url,
        data: e,
        success: function(a) {
          if ("updated" == a) {
            $(".wpts-popup-return-data").html("Subject information updated Successfully !"), $("#SuccessModal").css("display", "block"), $("#SavingModal").css("display", "none"), $("#SuccessModal").addClass("wpts-popVisible");
            var e = $("#wpts_locationginal1").val() + "admin.php?page=sch-subject";
            setTimeout(function() {
              window.location.href = e
            }, 1e3);
            $("#SEditSave").attr("disabled", "disabled")
          } else $(".wpts-popup-return-data").html(a), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"), $("#SEditSave").attr("disabled", !1)
        }
      })
    }
  }), $(document).on("click", "#d_teacher", function(a) {
    var e = $(this).data("id");
    $("#teacherid").val(e), $("#DeleteModal").css("display", "block")
  }), $(document).on("click", ".ClassDeleteBt", function(a) {
    var e = $("#teacherid").val(),
      t = [];
    t.push({
      name: "action",
      value: "DeleteSubject"
    }, {
      name: "sid",
      value: e
    }), jQuery.post(ajax_url, t, function(a) {
      "deleted" == a ? location.reload() : ($(".wpts-popup-return-data").html("Operation failed.Something went wrong!"), $("#SavingModal").css("display", "none"), $("#WarningModal").css("display", "block"), $("#WarningModal").addClass("wpts-popVisible"))
    })
  })
}), $("#verticalTab").easyResponsiveTabs({
  type: "vertical",
  width: "auto",
  fit: !0
}), $(window).width() < 991 && ($("#verticalTab").find(".wpts-resp-tab-active").removeClass("wpts-resp-tab-active"), $("#verticalTab").find(".wpts-resp-tab-content-active").removeClass("wpts-resp-tab-content-active").css("display", "")), jQuery(window).width() < 991 && ($("#verticalTab").find(".wpts-resp-tab-active").length || $(".wpts-resp-tabs-list .wpts-resp-tab-item:first-child").click()), jQuery(window).resize(function() {
  jQuery(window).width() > 991 && ($("#verticalTab").find(".wpts-resp-tab-active").length || $(".wpts-resp-tabs-list .wpts-resp-tab-item:first-child").click())
});
