<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Enjoy Your Own Work Time.</title>

<link href="./Static/css/application.css" media="all" rel="stylesheet" type="text/css" />

<script src="./Static/js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="./Static/js/application.js" type="text/javascript"></script>
<script src="./Static/js/calendar.js" type="text/javascript"></script>
<style type="text/css">
html {overflow-y:scroll;}
</style>
</head>

<body>
<div id="ajax_loading">
    <div class="ajax_loading_img"></div>
</div>
<div id="append_parent"></div>
<div id="popup"></div>

<div class="head">
    <div class="inner">
        <h1>WORK TIME</h1>
        <div class="extra_op">
            <p class="login_info">
                <span id="login_acter"><?php if (isset($this->acter)) { echo $this->acter->id;} ?></span>
<span onclick="ajax('ctl=Role&act=logout');" class="a"><span class="icon icon-user"><span>退出</span></span></span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span onclick="ajax('ctl=User&act=set_password');" class="a"><span class="icon icon-user"><span>修改密码</span></span></span>
            </p>
        </div>
    </div>
</div>

<div class="lineright"></div>

<div id="main_box"></div>

<div class="foot">
	<div class="inner">
		<div class="footer_menu">
    <span class="a" onclick="msg('WORK TIME');">软件信息</span> |
    <span class="a" onclick="msg('工作比较忙...');">版本计划</span> |
    <span class="a" onclick="msg('QQ: 856109988');">关于甜面酱</span>
			</div>
		<p class="en">Copyright &copy; 2011 - 2012 Ting. All Rights Reserved.</p>
		<p>甜面酱 版权所有</p>
	</div>
</div>
</body>
</html>