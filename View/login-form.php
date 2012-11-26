<div id="login-form" class="popup" mask="1" style="width:450px;">
    <div class="popup_title">

        <span>登录</span>
    </div>

<div class="popup_content">
<div class="form">
    <form onsubmit="ajax('ctl=Role&act=login&login_state=' + LOGIN_STATE + '&' + get_form_values('login-form', 'val'));return false;">
    <ul>
        <li>
            <span class="name"><u>帐号：</u></span>
            <input type="text" tabindex="1" forme="val" name="login_name" class="inputstyle">
        </li>
        <li>
            <span class="name"><u>密码：</u></span>
            <input forme="val" name="passwd" type="password"  tabindex="2" maxlength="16"  id="p" class="inputstyle">
        </li>
    </ul>

    <div class="form_btn_submit">
        <input type="submit" class="btn" value="登 录" tabindex="5">
        <label>
<span class="a" onclick="ajax('ctl=Role&act=install');">安装...</span>
        </label>
    </div>

        </form>
</div>
    <div class="lineright"></div>
</div>
</div>