<div id="ticket_one" class="popup" mask="1">
    <div class="popup_title">
<button onclick="cls('ticket_one');" title="关闭" class="popup_btn_close" href="javascript:void(0);">
    <span class="none">╳</span>
</button>
        <span>提交任务</span>
    </div>

    <div class="popup_content">
<div class="form" id="ticket_form">
    <input type="hidden" forme="val" name="id" value="0" />
    <input type="hidden" id="save_and_next" value="0" />
<p>
标题：
<input forme="val" name="title" type="text" tabindex="1" class="inputstyle" style="width:450px;">

&nbsp;&nbsp;
<a href="html/howbug.html" target="_black"><b>如何有效地报告 Bug</b></a>
</p>

<p>
类别：
<select forme="val" name="caty" class="selectstyle">
<?php
$caty_list = Config_App::get_caty_list();
echo Lib_Global::format_select_options($caty_list, 0);
?>
</select>

&nbsp;&nbsp;
优先级：
<select forme="val" name="priority" class="selectstyle">
<?php
$priority_list = Config_App::get_priority_list();
echo Lib_Global::format_select_options($priority_list, 0);
?>
</select>

&nbsp;&nbsp;
部门：
<select forme="val" name="department" class="selectstyle">
<?php
$SYS_DEPARTMENT = O_Sys::readone(O_Sys::SYS_DEPARTMENT);
echo Lib_Global::format_select_options($SYS_DEPARTMENT->list, 0);
?>
</select>

&nbsp;&nbsp;
指派负责人：
<select forme="val" name="acter_id" class="selectstyle">
<?php
$admins = Config_Admin::get_select_option_list();
echo Lib_Global::format_select_options($admins, 0);
?>
</select>
</p>

<?php
include(ROOT_DIR . 'fckeditor/fckeditor.php') ;
$oFCKeditor = new FCKeditor('ticket_content');
$oFCKeditor->BasePath = './fckeditor/';
$oFCKeditor->Height = '500px';
$oFCKeditor->ToolbarSet = 'Basic';
$oFCKeditor->Create();
?>
        </div>

    <div class="lineright"></div>

    </div>

    <div class="confirm_btn" style="text-align: left;">
        <button class="btn_h_25" onclick="commit_ticket();">保存并关闭</button>
        <button class="btn_h_25" onclick="$('#save_and_next').val(1);commit_ticket();">保存并继续添加</button>
        <button class="btn_h_25" onclick="cls('ticket_one');">取消</button>
    </div>
</div>