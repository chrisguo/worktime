var LOGIN_STATE = 0;
$(document).ready(function() {
    var need_version = 9;
    var m = "<h3>Mozilla Firefox (version >= " + need_version + ") is required.</h3>";
    m += "<p><a href='http://www.mozilla.org/en-US/firefox/new/' target='_blank'>Download</a></p>";

    if (!$.browser.mozilla) {
        msg(m);
        return ;
    }

    if (parseInt($.browser.version, 10) < need_version) {
        msg(m);
        return ;
    }

    ajax();
});

$(window).resize(function(){
    var window_h = $(window).height();
    var document_h = $(document).height();
    $("#popup .mask").css({
        height: Math.max(document_h, window_h)
    });
});

function keep_session() {
    setInterval("search();", 15 * 60 * 1000);
}

function ajax(paras) {
    var ajax_loading = $('#ajax_loading');
    ajax_loading.show();
    ajax_loading.css({height: $(document).height()});

    $.ajax({
        url: "./index.php?ajax=1",
        type: "POST",
        data: paras,
        dataType: "json",
        cache: false,
        async: false,
        success: function(rtn) {
            popup(rtn.popup);

            for (var id in rtn.id_innerhtml) {
                var dom = $(id);
                if (dom) {
                    dom.html(rtn.id_innerhtml[id]);
                }
            }

            eval(rtn.eval_js);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            php_error(XMLHttpRequest.responseText);
        },
        complete: function (jqXHR, textStatus) {
            ajax_loading.hide();
        }
    });
}

function cls(id) {
    $("#" + id).parent().remove();
}

function msg(msg, onOk, onCancel) {
	var html = '<div id="popup-msg" class="popup">'
        + '<div class="popup_title">'
        + '<button onclick="cls(\'popup-msg\');" title="关闭" class="popup_btn_close" href="javascript:void(0);">'
        + '<span class="none">╳</span>'
        + '</button>'
        + '<span>提示信息</span>'
        + '</div>'

        + '<div class=popup_content>' + msg + '</div>'

        + '<div class="confirm_btn">'
        + '<button class="btn_h_25" onclick="'
        + (onOk ? onOk : '')
        + 'cls(\'popup-msg\');">确定</button>'
        + '<button class="btn_h_25" onclick="'
        + (onCancel ? onCancel : '')
        + 'cls(\'popup-msg\');">取消</button>'
        + '</div>'

        + '</div>';
    popup(html);
}

function php_error(content) {
	var html = '<div id="php_error" class="popup">'
        + '<div class="popup_title">'
        + '<button onclick="cls(\'php_error\');" title="关闭" class="popup_btn_close" href="javascript:void(0);">'
        + '<span class="none">╳</span>'
        + '</button>'
        + '<span>提示信息</span>'
        + '</div>'

        + '<div class=popup_content style="height:500px; overflow-y: auto;">' + content + '</div>'

        + '</div>';
    popup(html);
}

function popup(html) {
    if (!html) {
        return ;
    }

    var mask = $("<div class=mask></div>");
    var popup = $(html);
    mask.html(popup);
    cls(popup.attr("id"));
	$("#popup").append(mask);

    var window_h = $(window).height();
    var margin_top = (window_h - popup.outerHeight()) / 2;
    margin_top = Math.min(margin_top, 150);
    margin_top = Math.max(10, margin_top);
    popup.css(
        {
            marginTop : margin_top + $(document).scrollTop(),
            marginLeft : ($(document).width() - popup.outerWidth())/2
        }
    );

    var document_h = $(document).height();

    mask.css({
        minHeight: Math.max(document_h, document.documentElement.scrollHeight)
    });
}

function tab_selected(o, box_id, class_name) {
    $("#" + box_id + " ." + class_name).removeClass(class_name);
    $(o).addClass(class_name);
}

function tab_main(o) {
    var t = $(o);
    t.parent().children(".tab_selected").removeClass("tab_selected");
    t.addClass("tab_selected");
}

function get_check_box_values(id, name) {
    var a = [];
    var els = $("#" + id + " :checkbox");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if (name == el.attr("name")) {
            if (el.attr("checked")) {
                a.push(el.val());
            }
        }
    }

    var rtn = a.join();
    console.log(rtn);
    return rtn;
}

function check_all(id, name) {
    var els = $("#" + id + " :checkbox");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if (name == el.attr("name")) {
            el.attr("checked", true);
        }
    }
}

function check_toggle(id, name) {
    var els = $("#" + id + " :checkbox");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if (name == el.attr("name")) {
            if (el.attr("checked")) {
                el.attr("checked", false);
            } else {
                el.attr("checked", true);
            }
        }
    }
}

function get_form_values(id, forme, is_check_box) {
    var rtn = "";

    if (!forme) {
        forme = 'val';
    }
    var els = $("#" + id + " [forme='" + forme + "']");
    var dot = "";
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if (!is_check_box || el.attr('checked')) {
            rtn += dot + el.attr("name") + "=" + encodeURIComponent(el.val());
            dot = "&";
        }
    }
    return rtn;
}

function get_form_a(id, forme) {
    var rtn = {};
    if (!forme) {
        forme = 'val';
    }
    var els = $("#" + id + " [forme='" + forme + "']");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        rtn[el.attr("name")] = el.val();
    }
    return rtn;
}

function get_url_row(id, forme) {
    var rtn = "";
    if (!forme) {
        forme = 'val';
    }
    var els = $("#" + id + " [forme='" + forme + "']");
    var dot = "";
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        rtn += dot + "row[" + el.attr("name") + "]=" + encodeURIComponent(el.val());
        dot = "&";
    }
    return rtn;
}

function CreateFCKeditor(toid, fromid) {
    if (fromid) {
        document.getElementById(toid).value = document.getElementById(fromid).value ;
        $("#" + fromid).remove();
    }

    var h = $("#" + toid).attr("height");
    if (undefined == h) {
        h = 350;
    }
    console.log(h);

	var oFCKeditor = new FCKeditor( toid ) ;

	oFCKeditor.BasePath = "./fckeditor/";
	oFCKeditor.ToolbarSet = "Basic" ;
	oFCKeditor.Width = '100%' ;
	oFCKeditor.Height = h;
	oFCKeditor.ReplaceTextarea() ;
}

function commit_ticket() {
    var oEditor = FCKeditorAPI.GetInstance("ticket_content");
    var content = encodeURIComponent(oEditor.GetXHTML(true));

    var param = "ctl=Commit&act=edit&";
    param += get_url_row("ticket_form");
    param += "&row[content]=" + content;
    param += "&save_and_next=" + $('#save_and_next').val();

    console.log(param);

    ajax(param);
}

function commit_ticket_test_report() {
    var oEditor = FCKeditorAPI.GetInstance("ticket_test_report_content");
    var content = encodeURIComponent(oEditor.GetXHTML(true));

    var param = "ctl=Commit&act=test_report&";
    param += get_url_row("test_report_form");
    param += "&content=" + content;

    console.log(param);

    ajax(param);
}

function search() {
    if ($("#ticket_search").length) {
        ajax('act=search&' + get_form_values('ticket_search'));
    } else {
        ajax('ctl=Role&act=keep_session');
    }
}

function change_more(list_box, ck_name, column, param) {
    var t = _change_more(list_box, ck_name, column);
    if ("" == t) {
        return ;
    }
    param += t;
    ajax(param);
}

function _change_more(list_box, ck_name, column_name) {
    var ids = get_check_box_values(list_box, ck_name);
    if ("" == ids) {
        alert("没有选择");
        return "";
    }
    var param = "&ids=" + ids;
    var column = $("#" + column_name);
    param += "&row[" + column.attr("name") + "]=" + column.val();
    return param;
}

function protocol_form(save_and_next) {
    var box = $("#protocol_args");

    var get_child = function (_pre, box) {
        var rtn = "";
        var a = $(box).children(".protocol_arg");
        for (var i = 0; i < a.length; i++) {
            var pre = _pre + "[" + i + "]";
            var b = $(a[i]).children();
            var els = $(b[0]).children(".val");
            for (var t = 0; t < els.length; t++) {
                var el = $(els[t]);
                //encodeURIComponent
                rtn += "&" + pre + "[" + el.attr("name") + "]=" + encodeURIComponent(el.val());
            }

            rtn += get_child(pre + "[childrens]", b[1]);
        }

        console.log(rtn);
        return rtn;
    }

    var param = "ctl=Protocol&act=manage&";
    param += get_url_row("protocol_form");
    param += get_child("row[args]", box);

    var associated_protocol = get_associated_protocol();
    for (var i = 0; i < associated_protocol.length; i++) {
        param += "&row[protocol_associated][" + i + "]=" + associated_protocol[i];
    }

    var oEditor = FCKeditorAPI.GetInstance("protocol_detail");
    var content = encodeURIComponent(oEditor.GetXHTML(true));
    param += "&row[detail]=" + content;

    param += "&save_and_next=" + save_and_next;

    ajax(param);
}

function append_associated_protocol() {
    var name = $.trim($('#associated_protocol').val());
    if ("" == name) {
        return ;
    }

    var a = get_associated_protocol();
    if ($.inArray(name, a) >= 0) {
        return ;
    }

    $('#associated_protocol').val("");

    var html = "<span>";
    html += "<span class=\"icon icon-del\" onclick=\"$(this).parent().remove();\"></span>";
    html += "<span class=\"a\">" + name + "</span>";
    html += "&nbsp;&nbsp;</span>";

    $('#associated_protocol_list').append(html);
}

function get_associated_protocol() {
    var rtn = [];
    var els = $("#associated_protocol_list .a");
    for (var i = 0; i < els.length; i++) {
        rtn[i] = $(els[i]).html();
    }
    return rtn;
}

var PROTOCOL_ARGS = null;
function protocol_form_args_init() {
    var box = $("#protocol_args");
    var append_child = function (box, data) {
        for (var i = 0; i < data.length; i++) {
            var pd_property = $($("#protocol_arg_tpl").html());
            var b = pd_property.children();
            var els = $(b[0]).children(".val");
            for (var t = 0; t < els.length; t++) {
                var el = $(els[t]);
                el.val(data[i][el.attr("name")]);
            }

            if (data[i]["childrens"] && data[i]["childrens"].length > 0) {
                append_child($(b[1]), data[i]["childrens"]);
            }

            box.append(pd_property);
        }
    }

    append_child(box, PROTOCOL_ARGS);
}

function get_json_string(object) {
    var type = typeof object;
    if ('object' == type) {
        if (Array == object.constructor)
            type = 'array';
        else if (RegExp == object.constructor)
            type = 'regexp';
        else
            type = 'object';
    }

    var results, value;

    switch(type) {
        case 'undefined':
        case 'unknown':
            return '';
            break;
        case 'function':
        case 'boolean':
        case 'regexp':
            return object.toString();
            break;
        case 'number':
            return isFinite(object) ? object.toString() : 'null';
            break;
        case 'string':
            return '"' + object.replace(/(\\|\")/g,"\\$1").replace(/\n|\r|\t/g, function(){
                var a = arguments[0];
                return  (a == '\n') ? '\\n': (a == '\r') ? '\\r': (a == '\t') ? '\\t': ""
                }) + '"';
            break;
        case 'object':
            if (object === null) return 'null';
            results = [];
            for (var property in object) {
                value = get_json_string(object[property]);
                if (value !== undefined)
                results.push(get_json_string(property) + ':' + value);
            }
            return '{' + results.join(',') + '}';
            break;
        case 'array':
            results = [];
            for(var i = 0; i < object.length; i++) {
                value = get_json_string(object[i]);
                if (value !== undefined) results.push(value);
            }
            return '[' + results.join(',') + ']';
            break;
    }
}

function cfm(f, s) {
    if (s) {
        s = '确定要执行操作吗？';
    }
    if (confirm(s)) {
        f();
    }
}
