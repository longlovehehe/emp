"use strict";
window.com = window.com || {};
window.com.fn = window.com.fn || {};
window.com.form = window.com.form || {};

/**
 *
 * @returns {undefined}
 */

window.com.form.autocheck = function ()
{
    $("body").delegate
            (
                    'input.cb[type=checkbox]'
                    , 'click'
                    , function ()
                    {
                        $("input#checkall").removeAttr("checked");
                    }
            );
}
/**
 * 低版本浏览器显示提示信息
 * @returns {undefined}
 */
window.com.nosuper = function () {
    $("div.nosuper").text("你使用的是低版本浏览器，或采用的怪异模式（quirks mode）。可能导致样式混乱，请切换到标准模式/严格模式（Standards mode）。");
}

/**
 * 校正宽高
 * @returns {undefined}
 */
window.com.autosize = function () {
    $(".autosize").each(function () {
        var owner = $(this);
        function calcsize() {
            var height = Number(owner.attr("height"));
            var min = Number(owner.attr("min"));
            var diff = $("html").height() - height;
            if (diff >= min) {
                owner.height(diff);
            }
        }
        calcsize();
    });
};
window.com.nosuper();
window.com.form.autocheck();

