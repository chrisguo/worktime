<?php
$test_status_list = Config_App::get_test_status_list();
foreach ($this->ticket->test_report as $log) {?>
<table class="list_content" width="100%">
<tr>
<th style="width:50px;"><?php echo $log->acter_name;?></th>
<th style="width:50px;"><?php echo $test_status_list[$log->status];?></th>
<th><?php echo date('Y-m-d H:i:s', $log->t);?></th>
</tr>
<tr>
    <td colspan="3"><?php echo $log->content;?></td>
</tr>
</table>
<div class="lineright"></div>
<?php }?>