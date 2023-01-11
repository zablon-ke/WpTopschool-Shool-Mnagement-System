! function(s) {
  s.fn.extend({
    easyResponsiveTabs: function(a) {
      var t = a = s.extend({
          type: "default",
          width: "auto",
          fit: !0,
          closed: !1,
          activate: function() {}
        }, a),
        e = t.type,
        i = t.fit,
        p = t.width,
        r = "vertical",
        n = "accordion";
      s(this).bind("tabactivate", function(s, t) {
        "function" == typeof a.activate && a.activate.call(t, s)
      }), this.each(function() {
        var t, c = s(this),
          o = c.find("ul.wpts-resp-tabs-list");
        c.find("ul.wpts-resp-tabs-list li").addClass("wpts-resp-tab-item"), c.css({
            display: "block",
            width: p
          }), c.find(".wpts-resp-tabs-container > div").addClass("wpts-resp-tab-content"),
          function() {
            e == r && c.addClass("resp-vtabs");
            1 == i && c.css({
              width: "100%",
              margin: "0px"
            });
            e == n && (c.addClass("resp-easy-accordion"), c.find(".wpts-resp-tabs-list").css("display", "none"))
          }(), c.find(".wpts-resp-tab-content").before("<h2 class='resp-accordion' role='tab'><span class='resp-arrow'></span></h2>");
        var d = 0;
        c.find(".resp-accordion").each(function() {
          t = s(this);
          var a = c.find(".wpts-resp-tab-item:eq(" + d + ")").html();
          c.find(".resp-accordion:eq(" + d + ")").append(a), t.attr("aria-controls", "tab_item-" + d), d++
        });
        var l = 0;
        c.find(".wpts-resp-tab-item").each(function() {
          $tabItem = s(this), $tabItem.attr("aria-controls", "tab_item-" + l), $tabItem.attr("role", "tab"), !0 === a.closed || "accordion" === a.closed && !o.is(":visible") || "tabs" === a.closed && o.is(":visible") || (c.find(".wpts-resp-tab-item").first().addClass("wpts-resp-tab-active"), c.find(".resp-accordion").first().addClass("wpts-resp-tab-active"), c.find(".wpts-resp-tab-content").first().addClass("wpts-resp-tab-content-active").attr("style", "display:block"));
          var t = 0;
          c.find(".wpts-resp-tab-content").each(function() {
            s(this).attr("aria-labelledby", "tab_item-" + t), t++
          }), l++
        }), c.find("[role=tab]").each(function() {
          var a = s(this);
          a.click(function() {
            var t = a.attr("aria-controls");
            if (a.hasClass("resp-accordion") && a.hasClass("wpts-resp-tab-active")) return c.find(".wpts-resp-tab-content-active").slideUp("", function() {
              s(this).addClass("resp-accordion-closed")
            }), a.removeClass("wpts-resp-tab-active"), s(".wpts-resp-tab-active").removeClass("wpts-resp-tab-active"), !1;
            !a.hasClass("wpts-resp-tab-active") && a.hasClass("resp-accordion") ? (c.find(".wpts-resp-tab-active").removeClass("wpts-resp-tab-active"), c.find(".wpts-resp-tab-active").removeClass("wpts-resp-tab-active"), c.find(".wpts-resp-tab-content-active").slideUp().removeClass("wpts-resp-tab-content-active resp-accordion-closed"), c.find("[aria-controls=" + t + "]").addClass("wpts-resp-tab-active"), c.find(".wpts-resp-tab-content[aria-labelledby = " + t + "]").slideDown().addClass("wpts-resp-tab-content-active")) : (c.find(".wpts-resp-tab-active").removeClass("wpts-resp-tab-active"), c.find(".wpts-resp-tab-content-active").removeAttr("style").removeClass("wpts-resp-tab-content-active").removeClass("resp-accordion-closed"), c.find("[aria-controls=" + t + "]").addClass("wpts-resp-tab-active"), c.find(".wpts-resp-tab-content[aria-labelledby = " + t + "]").addClass("wpts-resp-tab-content-active").attr("style", "display:block")), a.trigger("tabactivate", a)
          }), s(window).resize(function() {
            c.find(".resp-accordion-closed").removeAttr("style")
          })
        })
      })
    }
  })
}(jQuery);
