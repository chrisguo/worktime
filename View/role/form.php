<div id="add_role" class="popup" mask="1" style="width:450px;">
    <div class="popup_title">
<button onclick="cls('add_role');" title="关闭" class="popup_btn_close" href="javascript:void(0);">
    <span class="none">╳</span>
</button>
        <span>创建成员</span>
    </div>

<div class="popup_content">
<div class="form">
    <form onsubmit="ajax('ctl=Manage&act=set_role&' + get_url_row('add_role', 'val'));return false;">
    <ul>
        <li>
            <span class="name"><u>名字：</u></span>
            <input type="text" tabindex="1" forme="val" name="id" class="inputstyle" value="<?php echo $this->O_Role->id; ?>" />
        </li>
        <li>
            <span class="name"><u>密码：</u></span>
            <input forme="val" name="passwd" type="password"  tabindex="2" maxlength="16"  id="p" class="inputstyle" value="<?php echo $this->O_Role->passwd; ?>" />
        </li>
        <li>
            <span class="name"><u>SVN：</u></span>
            <input type="text" tabindex="1" forme="val" name="svn_name" class="inputstyle" value="<?php echo $this->O_Role->svn_name; ?>" />
        </li>
        <li>
            <span class="name"><u>管理权限：</u></span>
            <select forme="val" name="manager" class="selectstyle">
                <option value="0" <?php echo !$this->O_Role->manager ? 'selected' : ''; ?>>无</option>
                <option value="1" <?php echo $this->O_Role->manager ? 'selected' : ''; ?>>有</option>
            </select>
        </li>
    </ul>

    <div class="form_btn_submit">
        <input type="submit" class="btn" value="创 建" tabindex="5">
    </div>

        </form>
</div>
    <div class="lineright"></div>
</div>
</div>