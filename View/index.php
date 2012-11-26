<div>

<div id="nav">
    <div class="main-nav-module">
<ul class="main-nav-list" id="main-nav-list">
<li class="main-nav-sublist">
<div class="bg main-nav-sub-head">
<span class="bg pointer"></span>
<div>操作</div>
</div>
<ul>

<li onclick="ajax('ctl=Index&act=search&acter_id=<?php echo $this->acter->id; ?>');tab_selected(this, 'main-nav-list', 'main-nav-item-selected');" class="bg main-nav-item main-nav-item-selected">
<a class="" href="javascript:void(0);">BACKLOG</a>
</li>
<li onclick="ajax('ctl=Commit');" class="bg main-nav-item ">
<a class="" href="javascript:void(0);">Write</a>
</li>
<li onclick="ajax('ctl=Manage&act=get_role_list');tab_selected(this, 'main-nav-list', 'main-nav-item-selected');" class="bg main-nav-item ">
<a class="" href="javascript:void(0);">成员管理</a>
</li>
<li onclick="ajax('ctl=Manage&act=get_department_list');" class="bg main-nav-item ">
<a class="" href="javascript:void(0);">部门管理</a>
</li>
</ul>
</li>
</ul>
        <div class="bg main-nav-foot"></div>
    </div>
</div>

    <div id="detail_box">
    </div>

</div>
