<div id="add_role" class="popup" mask="1" style="width:450px;">
    <div class="popup_title">
<button onclick="cls('add_role');" title="关闭" class="popup_btn_close" href="javascript:void(0);">
    <span class="none">╳</span>
</button>
        <span>修改密码</span>
    </div>

<div class="popup_content">
<div class="form">
    <form onsubmit="ajax('ctl=User&act=set_password&password=' + $('#password').val());return false;">
    <ul>
        <li>
            <span class="name"><u>密码：</u></span>
            <input forme="val" id="password" type="password"  tabindex="2" maxlength="16"  id="p" class="inputstyle" value="" />
        </li>
    </ul>

    <div class="form_btn_submit">
        <input type="submit" class="btn" value="确定" tabindex="5">
    </div>

        </form>
</div>
    <div class="lineright"></div>
</div>
</div>