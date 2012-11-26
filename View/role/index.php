<div class="reWrap">
<div class="head">
    <h3>成员管理
    </h3>
</div>
<div class="body">

    <div>
<span class="a" onclick="ajax('ctl=Manage&act=show_edit_role');"><span class="icon icon-add"></span>添加成员
    <div class="lineright"></div>
    </div>

<table class="list_content" id="protocol_list">
<tr>
    <th style="width:150px;">操作</th>
    <th style="width:150px;">名字</th>
    <th style="width:150px;">SVN账号</th>
    <th style="width:150px;">管理权</th>
</tr>
<?php
$O_Sys = O_Sys::readone(O_Sys::SYS_ROLES);
$alt = false;
foreach ($O_Sys->list as $O_Role) {
    if ($O_Role->is_super_admin()) {
        continue;
    }
?>
<tr class="row<?php $alt = Lib_Global::get_row_alt($alt);?>">
    <td>
<span onclick="ajax('ctl=Manage&act=show_edit_role&id=<?php echo $O_Role->id; ?>');" class="a icon icon-edit">编辑</span>
&nbsp;&nbsp;
<span onclick="cfm(function () {ajax('ctl=Manage&act=delete_role&id=<?php echo $O_Role->id; ?>');}, '确定要执行删除操作？');" class="a icon icon-del">删除</span>
    </td>
    <td>
<span onclick="ajax('ctl=Manage&act=show_edit_role&id=<?php echo $O_Role->id; ?>');" class=a><?php echo $O_Role->id; ?></span>
    </td>
    <td>
<?php echo $O_Role->svn_name; ?>
    </td>
    <td>
<?php echo $O_Role->manager; ?>
    </td>
</tr>
<?php
}
?>

</table>


</div></div>