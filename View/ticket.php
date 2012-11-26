<div id="ticket_one" class="popup" mask="1" style="width:98%;">
    <div class="popup_title">
<button onclick="cls('ticket_one');" title="关闭" class="popup_btn_close" href="javascript:void(0);">
    <span class="none">╳</span>
</button>
        <span>详细信息 #<?php echo $this->ticket->id ?></span>
    </div>

<?php
$admins = Config_Admin::get_select_option_list();
?>

    <div class="popup_content">

<div class="menu_2 menu_3">
<ul>
<li onclick="tab_main(this);tab_selected('#ticket_form_tab', 'ticket_detail', 'block')" class="tab_selected">任务描述</li>
<li onclick="tab_main(this);tab_selected('#ticket_log_tab', 'ticket_detail', 'block')" class="">操作日志</li>
<li onclick="tab_main(this);tab_selected('#ticket_report_tab', 'ticket_detail', 'block');if ('none' != $('#ticket_test_report_content').css('display')) {CreateFCKeditor('ticket_test_report_content');}" class="">测试日志</li>
</ul>
</div>


<div id="ticket_detail">
    <div id="ticket_form_tab" class="none block">

<div class="reWrap">
<div class="head"><h3>基本操作</h3></div>
<div class="body">
调整优先级：
<select id="ticket_change_priority" class="selectstyle">
<?php
$priority_list = Config_App::get_priority_list();
echo Lib_Global::format_select_options($priority_list, $this->ticket->priority);
?>
</select>
&nbsp;&nbsp;
<button onclick="ajax('ctl=Commit&act=edit&row[id]=<?php echo $this->ticket->id; ?>&row[priority]=' + $('#ticket_change_priority').val());">确定</button>

&nbsp;&nbsp;
调整负责人：
<select id="ticket_change_acter_id" class="selectstyle">
<?php
if (!$this->ticket->acter_id) {
    echo '<option value=0>未指定</option>';
}
echo Lib_Global::format_select_options($admins, $this->ticket->acter_id);
?>
</select>
&nbsp;&nbsp;
<button onclick="ajax('ctl=Commit&act=edit&row[id]=<?php echo $this->ticket->id; ?>&row[acter_id]=' + $('#ticket_change_acter_id').val());">确定</button>

</div>
</div>

<div class="lineright"></div>

<div class="reWrap">
<div class="head"><h3>任务详情</h3></div>
<div class="body" id="ticket_form">
<input type="hidden" forme="val" name="id" value="<?php echo $this->ticket->id;?>" />
<input type="hidden" id="save_and_next" value="0" />
<p>标题：
<select forme="val" name="caty" class="selectstyle">
<?php
$caty_list = Config_App::get_caty_list();
echo Lib_Global::format_select_options($caty_list, $this->ticket->caty);
?>
</select>

&nbsp;&nbsp;
<select forme="val" name="department" class="selectstyle">
<?php
$SYS_DEPARTMENT = O_Sys::readone(O_Sys::SYS_DEPARTMENT);
echo Lib_Global::format_select_options($SYS_DEPARTMENT->list, $this->ticket->department);
?>
</select>

&nbsp;&nbsp;
<input forme="val" name="title" type="text" tabindex="1" class="inputstyle" style="width:450px;" value="<?php echo $this->ticket->title; ?>" />
</p>
<p>
报告人：
<?php
echo $this->ticket->reporter_id;
?>

&nbsp;&nbsp;
创建时间：
<?php
echo date('Y-m-d H:i:s', $this->ticket->t);
?>

&nbsp;&nbsp;
修改时间：
<?php
echo date('Y-m-d H:i:s', $this->ticket->last_t);
?>
</p>

<?php
include(ROOT_DIR . 'fckeditor/fckeditor.php') ;
$oFCKeditor = new FCKeditor('ticket_content');
$oFCKeditor->BasePath = './fckeditor/';
$oFCKeditor->Height = '500px';
$oFCKeditor->ToolbarSet = 'Basic';
$oFCKeditor->Value = $this->ticket->content;
$oFCKeditor->Create();
?>

<div class="lineright"></div>
<button class="btn_h_25" onclick="commit_ticket();">重新编辑任务</button>
</div>
</div>




    </div>

    <div id="ticket_log_tab" class="popup_content none">
<?php $this->_include('ticket_log'); ?>
    </div>

    <div id="ticket_report_tab" class="none">
        <div id="ticket_report">
<?php $this->_include('ticket_report'); ?>
        </div>

<div class="reWrap">
<div class="head"><h3>提交测试报告</h3></div>
<div class="body">
<div id="test_report_form">
<input type="hidden" forme="val" name="id" value="<?php echo $this->ticket->id;?>" />
测试结果：
<select forme="val" name="test_status" class="selectstyle">
<?php
$test_status_list = Config_App::get_test_status_list();
echo Lib_Global::format_select_options($test_status_list, $this->ticket->test_status);
?>
</select>

</div>

<div class="lineright"></div>

<script type="text/javascript" src="./fckeditor/fckeditor.js"></script>
<textarea id="ticket_test_report_content" width="200"></textarea>

<div class="lineright"></div>
<button class="btn_h_25" onclick="commit_ticket_test_report()">提交测试报告</button>
</div>
</div>


    </div>
</div>


    </div>
</div>