<div id="add_department" class="popup" mask="1" style="width:450px;">
    <div class="popup_title">
<button onclick="cls('add_department');" title="关闭" class="popup_btn_close" href="javascript:void(0);">
    <span class="none">╳</span>
</button>
        <span>部门管理</span>
    </div>

<div class="popup_content">
<div class="form">
    <form onsubmit="ajax('ctl=Manage&act=set_department&' + get_form_values('add_department', 'val'));return false;">
    <ul>
        <li>
            <span class="name"><u>名字：</u></span>
            <input type="text" tabindex="1" forme="val" name="name" class="inputstyle input_100" value="" />
            <input type="submit" class="btn" value="添 加" tabindex="5">
        </li>
    </ul>

    </form>
</div>
    <div class="lineright"></div>

<table class="list_content" width="100%">
<tr>
    <th style="width:150px;">操作</th>
    <th>名字</th>
</tr>
<?php
$O_Sys = O_Sys::readone(O_Sys::SYS_DEPARTMENT);
$alt = false;
foreach ($O_Sys->list as $name) {
?>
<tr class="row<?php $alt = Lib_Global::get_row_alt($alt);?>">
    <td>
<span onclick="cfm(function () {ajax('ctl=Manage&act=set_department&name=<?php echo $name; ?>&delete=1');}, '确定要执行删除操作？');" class="a icon icon-del">删除</span>
    </td>
    <td>
<?php echo $name; ?>
    </td>
</tr>
<?php
}
?>

</table>

</div>

</div>