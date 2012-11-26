<div class="reWrap">
<div class="head"><h3>BACKLOG</h3></div>
<div class="body">

    <div id="ticket_search">
        <input id="ticket_search_page" type="hidden" forme="val" name="page" value="<?php echo $this->page; ?>" />
<div id="advanced_options_box" style="display: <?php echo $this->advanced_options ? 'block' : 'none'; ?>;">
        <p>
日期：
<input forme="val" name="time_start" value="<?php echo $this->time_start; ?>" type="text" onclick="showcalendar(event, this, true)" class="inputstyle time" />
<img src="./Static/images/datebox_arrow.png" alt="..." title="...">
&nbsp;-&nbsp;
<input forme="val" name="time_end" value="<?php echo $this->time_end; ?>" type="text" onclick="showcalendar(event, this, true)" class="inputstyle time" />
<img src="./Static/images/datebox_arrow.png" alt="..." title="...">
        </p>

        <p>
类型：
<select forme="val" name="caty" class="selectstyle">
    <option value=0>全部</option>
<?php
$caty_list = Config_App::get_caty_list();
$caty_color = array(
    Config_App::CATY_BUG => 'red',
    Config_App::CATY_GAI_JIN => 'green',
    Config_App::CATY_XUE_QIU => 'blue',
);
echo Lib_Global::format_select_options($caty_list, $this->caty);
?>
</select>

&nbsp;&nbsp;
    部门：
<select forme="val" name="exclude_department" class="selectstyle">
<?php
$not = array(
    0 => '等于',
    1 => '排除',
);
echo Lib_Global::format_select_options($not, $this->exclude_department);
?>
</select>
<select forme="val" name="department" class="selectstyle">
    <option value=0>全部</option>
<?php
$SYS_DEPARTMENT = O_Sys::readone(O_Sys::SYS_DEPARTMENT);
echo Lib_Global::format_select_options($SYS_DEPARTMENT->list, $this->department);
?>
</select>

    &nbsp;&nbsp;
负责人：
<select forme="val" name="acter_id" class="selectstyle">
    <option value=0>全部</option>
<?php
$admins = Config_Admin::get_select_option_list();
echo Lib_Global::format_select_options($admins, $this->acter_id);
?>
</select>

&nbsp;&nbsp;
报告人：
<select forme="val" name="reporter_id" class="selectstyle">
    <option value=0>全部</option>
<?php
echo Lib_Global::format_select_options($admins, $this->reporter_id);
?>
</select>

&nbsp;&nbsp;
版本：
<input forme="val" name="version" value="<?php echo $this->version; ?>" type="text" class="inputstyle input_100" />
</p>
</div>

<p>
    状态：
<select forme="val" name="status" class="selectstyle">
<option value=0>全部</option>
<?php
$status_list = Config_App::get_status_list();
echo Lib_Global::format_select_options($status_list, $this->status);
?>
</select>

&nbsp;&nbsp;
测试结果：
<select forme="val" name="test_status" class="selectstyle">
<option value=0>全部</option>
<?php
$test_status_list = Config_App::get_test_status_list();
echo Lib_Global::format_select_options($test_status_list, $this->test_status);
?>
</select>

&nbsp;&nbsp;
标题：
<input forme="val" name="title" type="text" class="inputstyle input_100" value="<?php echo $this->title; ?>" />

&nbsp;&nbsp;
<button onclick="$('#ticket_search_page').val(1);search()">
    查询
</button>



&nbsp;&nbsp;
&nbsp;&nbsp;
<input id="ticket_go_id" type="text" class="inputstyle input_100" />
&nbsp;&nbsp;
<button onclick="ajax('ctl=Ticket&id=' + $('#ticket_go_id').val());">
    ID定位
</button>

&nbsp;&nbsp;
&nbsp;&nbsp;
<input id="advanced_options" type="hidden" forme="val" name="advanced_options" value="<?php echo $this->advanced_options ?>" />
<span class="a" onclick="if ('1' == $('#advanced_options').val()) {$('#advanced_options').val(0);$('#advanced_options_box').hide();} else {$('#advanced_options').val(1);$('#advanced_options_box').show();}">高级选项</span>
</p>
    </div>

<?php $this->_include('multipage'); ?>

<table class="list_content" id="ticket_list" style="min-width:1200px;width:98%;">
<tr>
    <th style="width:15px;">
<input type="checkbox" onclick="check_toggle('ticket_list', 'ticket');" />
    </th>
    <th style="width:50px;">ID</th>
    <th style="width:50px;">状态</th>
    <th style="width:50px;">测试</th>
    <th style="width:50px;">优先级</th>
    <th style="width:30px;">类型</th>
    <th style="width:390px;">标题</th>
    <th style="width:50px;">部门</th>
    <th style="width:50px;">负责者</th>
    <th style="width:50px;">报告者</th>

    <th style="width:100px;">版本</th>
    <th style="width:120px;">最后修改</th>
    <td class="last_hidden">&nbsp;</td>
</tr>
<?php
$priority_list = Config_App::get_priority_list();
$sql = $this->sql;
$query = $this->db->query($sql);
$alt = false;
while ($row = $this->db->fetch_array($query)) {
    $gray = (Config_App::STATUS_CLOSE == $row['status']) ? ' gray' : '';
?>
<tr class="row<?php $alt = Lib_Global::get_row_alt($alt); echo $gray;?>">
    <td><div class="td_inner">
<input type="checkbox" name="ticket" value="<?php echo $row['id']; ?>" />
    </div></td>
    <td><div class="td_inner">
<?php echo $row['id']; ?>
    </div></td>
    <td><div class="td_inner">
<?php
echo $status_list[$row['status']];
?>
    </div></td>
    <td><div class="td_inner <?php echo Config_App::TEST_STATUS_NO == $row['test_status'] ? 'red' : ''; echo $gray;?>">
<?php echo $test_status_list[$row['test_status']];?>
    </div></td>
    <td><div class="td_inner">
<?php
echo $priority_list[$row['priority']];
?>
    </div></td>
    <td class="<?php echo $caty_color[$row['caty']] . $gray; ?>"><div class="td_inner">
<?php
echo $caty_list[$row['caty']];
?>
    </div></td>
    <td><div class="td_inner">
        <span class="a <?php echo $gray; ?>" onclick="ajax('ctl=Ticket&id=<?php echo $row['id']; ?>');">
<?php
echo $row['title'];
?>
            </span>
    </div></td>
    <td><div class="td_inner">
<?php
echo $row['department'];
?>
    </div></td>

    <td><div class="td_inner">
<?php
echo $row['acter_id'];
?>
    </div></td>
    <td><div class="td_inner">
<?php
echo $row['reporter_id'];
?>
    </div></td>

    <td><div class="td_inner">
<?php echo $row['version'];?>
    </div></td>
    <td><div class="td_inner">
<?php echo date('Y-m-d H:i:s', $row['last_t']);?>
    </div></td>
    <td class="last_hidden">&nbsp;</td>
</tr>
<?php
}
?>

</table>

    <?php $this->_include('multipage'); ?>

    <div>
<p>
        批量操作：
<span class="a" onclick="check_all('ticket_list', 'ticket');">全选</span>
&nbsp;&nbsp;
<span class="a" onclick="check_toggle('ticket_list', 'ticket');">反选</span>
&nbsp;&nbsp;
<select id="change_more_status" name="status" class="selectstyle">
<?php
echo Lib_Global::format_select_options($status_list, 0);
?>
</select>
&nbsp;&nbsp;
<button onclick="change_more('ticket_list', 'ticket', 'change_more_status', 'ctl=Commit&act=change_more');">
    修改状态
</button>

&nbsp;&nbsp;
<select id="change_more_test_status" name="test_status" class="selectstyle">
<?php
echo Lib_Global::format_select_options($test_status_list, 0);
?>
</select>
&nbsp;&nbsp;
<button onclick="change_more('ticket_list', 'ticket', 'change_more_test_status', 'ctl=Commit&act=change_more');">
    修改测试
</button>

&nbsp;&nbsp;
<select id="change_more_acter_id" name="acter_id"  class="selectstyle">
<?php
echo Lib_Global::format_select_options($admins, 0);
?>
</select>
&nbsp;&nbsp;
<button onclick="change_more('ticket_list', 'ticket', 'change_more_acter_id', 'ctl=Commit&act=change_more');">
    修改负责人
</button>

&nbsp;&nbsp;
<select id="change_more_department" name="department"  class="selectstyle">
<?php
echo Lib_Global::format_select_options($SYS_DEPARTMENT->list, 0);
?>
</select>
&nbsp;&nbsp;
<button onclick="change_more('ticket_list', 'ticket', 'change_more_department', 'ctl=Commit&act=change_more');">
    修改部门
</button>

&nbsp;&nbsp;
<input id="change_more_version" name="version" type="text" class="inputstyle input_100" />
&nbsp;&nbsp;
<button onclick="change_more('ticket_list', 'ticket', 'change_more_version', 'ctl=Commit&act=change_more');">
    设置版本
</button>
</p>

<p>
<select id="change_more_priority" name="priority"  class="selectstyle">
<?php
echo Lib_Global::format_select_options(Config_App::get_priority_list(), 0);
?>
</select>
&nbsp;&nbsp;
<button onclick="change_more('ticket_list', 'ticket', 'change_more_priority', 'ctl=Commit&act=change_more');">
    修改优先级
</button>
</p>
    </div>

</div></div>