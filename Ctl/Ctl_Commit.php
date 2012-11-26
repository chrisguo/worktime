<?php

class Ctl_Commit extends Ctl_Base {

    public function index() {
        $this->ajax->popup('commit');
        $this->on_ajax();
    }

    public function edit() {
        $row = Lib_Req::post('row');

        if (isset($row['content'])) {
            //data:image
            if (false === strpos($row['content'], 'data:image')) {
            } else {
                $this->on_msg('图像格式不正确');
            }
        }

        $is_new = true;

        $O_Ticket = new O_Ticket();
        if ($row['id']) {
            //已经有
            $O_Ticket->init_from_id($row['id']);
            if (!$O_Ticket->is_in_db()) {
                $this->on_msg('不存在');
            }

            $is_new = false;

            //检查权限
            if (isset($row['acter_id']) || isset($row['status'])) {
                $this->_check_pri();
            } else {
                if ($this->acter_id != $O_Ticket->reporter_id) {
                    $this->_check_pri();
                }
            }
        } else {
            //新增
            $O_Ticket->status = Config_App::STATUS_NEW;
            $O_Ticket->reporter_id = $this->acter_id;
        }

        $changed = false;
        foreach ($row as $k => $v) {
            if (!$is_new && $v == $O_Ticket->$k) {
                continue;
            }

            $O_Ticket->set($k, '=', $v);
            $changed = true;
        }

        if ($is_new) {
            if (empty($O_Ticket->title) || empty($O_Ticket->content)) {
                $this->on_msg('标题或内容不能为空！');
            }
        }

        if (!$changed) {
            $this->on_msg('修改成功！');
        }

        $refresh_log = false;
        if (!$is_new) {
            $log_a = array(
                'acter_id' => '负责人',
                'status' => '状态',
                'test_status' => '测试结果',
            );
            $config = array(
                'status' => Config_App::get_status_list(),
                'test_status' => Config_App::get_test_status_list()
            );

            $reason = Lib_Req::post('reason');

            foreach ($log_a as $log_column => $log_column_name) {
                if (isset($row[$log_column])) {
                    $O_TicketLog = new O_TicketLog();
                    $O_TicketLog->name = $this->acter->id;
                    $O_TicketLog->k = $log_column_name;

                    if ('acter_id' == $log_column) {
                        $O_TicketLog->v = $row['acter_id'];
                    } else {
                        $O_TicketLog->v = $config[$log_column][$O_Ticket->$log_column];
                    }

                    if ($reason) {
                        $O_TicketLog->v .= ' ' . $reason;
                    }

                    $O_Ticket->log[] = $O_TicketLog;
                    $O_Ticket->set('log');

                    $refresh_log = true;
                }
            }
        }

        $O_Ticket->flush();

        if ($refresh_log) {
            $this->view->ticket = $O_Ticket;
            $this->ajax->add_innerhtml('#ticket_log', 'ticket_log');
        }

        $save_and_next = Lib_Req::post('save_and_next');
        if (!$save_and_next) {
            $this->ajax->add_eval_js('msg("提交成功");');
        }

        $this->ajax->add_eval_js('search();');

        if ($is_new) {
            if ($save_and_next) {
                $this->index();
            } else {
                $this->ajax->add_eval_js('cls("ticket_one");');
            }
        }

        $this->on_ajax();
    }

    public function change_more() {
        $ids = Lib_Req::any('ids');

        $is_from_svn = Lib_Req::any('from_svn');
        if ($is_from_svn) {
            $row = array(
                'status' => Config_App::STATUS_RESOLVED,
                'test_status' => Config_App::TEST_STATUS_NULL
            );
        } else {
            $row = Lib_Req::any('row');

            $this->_check_pri();
        }

        if ('' == $ids) {
            $this->on_msg('未选择');
        }

        $O_Ticket = new O_Ticket();
        $db = $O_Ticket->get_db();

        $sql = 'update ' . $O_Ticket->get_table() . ' set last_t=' . RUNTIME;
        foreach ($row as $k => $v) {
            $sql .= ', ' . $k . '="' . $v . '"';
        }

        $sql .= ' where id in(' . $ids . ')';
        $db->query($sql);

        if ($is_from_svn) {
            $author = Lib_Req::any('author');
            $author_name = $this->_get_role_id_by_svn($author);

            $O_TicketLog = new O_TicketLog();
            $O_TicketLog->name = $author_name;
            $O_TicketLog->k = '状态';
            $O_TicketLog->v = '已解决 svn version: ' . Lib_Req::any('version');

            $ids_explode = explode(',', $ids);
            foreach ($ids_explode as $id) {
                $O_Ticket = new O_Ticket();
                $O_Ticket->init_from_id($id);
                if ($O_Ticket->is_in_db()) {
                    $O_Ticket->log[] = $O_TicketLog;
                    $O_Ticket->set('log');
                    $O_Ticket->flush();
                }
            }
        }

        $this->ajax->add_eval_js('search();');
        $this->on_ajax();
    }

    private function _get_role_id_by_svn($author) {
        $O_Sys = $O_Sys::readone(O_Sys::SYS_ROLES);
        foreach ($O_Sys->list as $id => $O_Role) {
            if ($O_Role->svn_name && $O_Role->svn_name == $author) {
                return $O_Role->id;
            }
        }
        return $author;
    }

    public function test_report() {
        $row = Lib_Req::post('row');

        $O_Ticket = new O_Ticket();
        $O_Ticket->init_from_id($row['id']);
        if (!$O_Ticket->is_in_db()) {
            $this->on_msg('不存在');
        }

        foreach ($row as $k => $v) {
            if ($k != $O_Ticket->get_pk_name()) {
                $O_Ticket->set($k, '=', $v);
            }
        }

        $content = Lib_Req::post('content');
        $O_TicketTestReport = new O_TicketTestReport();
        $O_TicketTestReport->acter_name = $this->acter->id;
        $O_TicketTestReport->status = $O_Ticket->test_status;
        $O_TicketTestReport->content = $content;

        $O_Ticket->test_report[] = $O_TicketTestReport;
        $O_Ticket->set('test_report');

        $O_Ticket->flush();

        $this->view->ticket = $O_Ticket;
        $this->ajax->add_innerhtml('#ticket_report', 'ticket_report');
        $this->ajax->add_eval_js('FCKeditorAPI.GetInstance("ticket_test_report_content").SetData("");');

        $this->on_ajax();
    }
}