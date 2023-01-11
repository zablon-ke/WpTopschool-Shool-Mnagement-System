jQuery(document).ready(function (p) {
    p(".wpts-navigation li:has(ul)").prepend('<span class="wpts-droparrow"></span>'), p(".wpts-droparrow").click(function () {
        p(this).siblings(".sub-menu").slideToggle("slow"), p(this).toggleClass("up")
    }), p(".sidebarScroll").slimScroll({
        height: "100%",
        size: "6px"
    }), p(".wpts-dropdown-toggle").click(function () {
        p(this).toggleClass("wpts-dropdown-active"), p(this).siblings(".wpts-dropdown").slideToggle("slow"), p(this).parents(".wpts-dropdownmain").toggleClass("wpts-dropdown-open")
    }), p(document).mouseup(function (s) {
        var o = p(".wpts-dropdown-open");
        o.is(s.target) || 0 !== o.has(s.target).length || (p(".wpts-dropdown").fadeOut(), p(".wpts-dropdown-toggle").removeClass("wpts-dropdown-active"), p(".wpts-dropdownmain").removeClass("wpts-dropdown-open"))
    }), p(".wpts-menuIcon").click(function () {
        p(this).toggleClass("wpts-close"), p(".wpts-sidebar").toggleClass("wpts-slideMenu"), p("body").toggleClass("wpts-bodyFix")
    }), p(".wpts-overlay").click(function () {
        p(".wpts-menuIcon").removeClass("wpts-close"), p(".wpts-sidebar").removeClass("wpts-slideMenu"), p(".wpts-bodyFix").removeClass("wpts-bodyFix")
    }), p("#datetimepicker1").datepicker({
        autoclose: !0,
        todayHighlight: !0
    }).datepicker("update", new Date), p(".wpts-popclick").off("click"), p(document).ready(function () {
        p("body").on("click", ".wpts-popclick", function () {
            var s = p(this).attr("data-pop");
            p("#" + s).addClass("wpts-popVisible"), p("body").addClass("wpts-bodyFixed")
        })
    }), p(".wpts-closePopup, .wpts-popup-cancel, .wpts-overlayer").click(function () {
        p("body").removeClass("wpts-bodyFixed"), p("#SavingModal").css("display", "none"), p("#WarningModal").css("display", "none"), p("#SuccessModal").css("display", "none"), p("#DeleteModal").css("display", "none"), p(".wpts-popupMain").removeClass("wpts-popVisible")
    }), p("input[type=file]").change(function () {
        var s = this.value.split("\\").pop();
        p(this).closest(".wpts-btn-file").next(".text").text(s)
    }), p(".wpts-closeLoading, .wpts-preLoading-onsubmit").click(function () {
        p(".wpts-preLoading-onsubmit").css("display", "none")
    })
});
(function ($) {
    $(window).load(function () {
        $(".wpts-preLoading").fadeOut()
    })
})(jQuery);