<div>
修改状态：
<select id="ticket_change_status" class="selectstyle">
<?php
$status_list = Config_App::get_status_list();
echo Lib_Global::format_select_options($status_list, $this->ticket->status);
?>
</select>
&nbsp;&nbsp;
原因：
<input type="text" id="ticket_change_status_reason" class="inputstyle input_350" />
&nbsp;&nbsp;
<button onclick="ajax('ctl=Commit&act=edit&row[id]=<?php echo $this->ticket->id; ?>&row[status]=' + $('#ticket_change_status').val() + '&reason=' + $('#ticket_change_status_reason').val());">确定</button>
</div>
<div class="lineright"></div>

<?php
if ($this->ticket->log) {
?>
        <table class="list_content" width="98%">
<tr>
    <th style="width:120px;">时间</th>
    <th style="width:50px;">操作</th>
    <th style="width:50px;">字段</th>
    <th>备注</th>
</tr>

    <?php
foreach ($this->ticket->log as $log) {?>
<tr>
<td>
    <?php echo date('Y-m-d H:i:s', $log->t);?>
</td>
<td>
    <?php echo $log->name;?>
</td>
<td>
    <?php echo $log->k;?>
</td>
<td>
    <?php echo $log->v;?>
</td>
</tr>
<?php
}
?>

</table>
<?php }?>

<div class="lineright"></div>