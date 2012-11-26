<?php
class Ctl_Index extends Ctl_Base {

    public function index() {
        if (Lib_Req::any('ajax')) {
            $this->_after_login();

            $this->ajax->add_eval_js('keep_session();');

            $this->on_ajax();
        } else {
            $this->display('login');
        }
    }

    public function search() {
        $ticket = new O_Ticket();

        $page = Lib_Req::post('page');
        $page = max(1, $page);
        $this->view->page = $page;

        $ppp = 15;

        $sqladd = ' WHERE 1 ';
        $mpurl = 'act=search';

        $this->view->advanced_options = Lib_Req::post('advanced_options', 0);
        $mpurl .= '&advanced_options=' . $this->view->advanced_options;

        $time_start = Lib_Req::post('time_start', '');
        if ($time_start) {
            $time_b = strtotime($time_start);
            $sqladd .= ' AND last_t>=' . $time_b;
            $mpurl .= '&time_start=' . urlencode($time_start);
        }
        $this->view->time_start = $time_start;

        $time_end = Lib_Req::post('time_end', '');
        if ($time_end) {
            $time_e = strtotime($time_end);
            $sqladd .= ' AND last_t<=' . $time_e;
            $mpurl .= '&time_end=' . urlencode($time_end);
        }
        $this->view->time_end = $time_end;

        $title = Lib_Req::post('title', '');
        if ($title) {
            $sqladd .= ' AND title like "%' . addslashes($title) . '%"';
            $mpurl .= '&title=' . $title;
        }
        $this->view->title = $title;

        $sqladd_columns = array(
            'caty', 'status',
            'acter_id', 'test_status', 'reporter_id', 'department',
            'version'
        );
        foreach ($sqladd_columns as $sqladd_column) {
            $t = Lib_Req::post($sqladd_column, '');
            if ($t) {
                $exclude_column = 'exclude_' . $sqladd_column;
                $not = Lib_Req::post($exclude_column, '0');

                $sqladd .= ' AND ' . $sqladd_column . ($not ? '!' : '') . '="' . $t . '"';

                $mpurl .= '&' . $sqladd_column . '=' . $t;
                $mpurl .= '&' . $exclude_column . '=' . $not;

                $this->view->$exclude_column = $not;
            }

            $this->view->$sqladd_column = $t;
        }

        $db = $ticket->get_db();

        $sql_count = 'SELECT count(*) AS num FROM ' . $ticket->get_table() . $sqladd;
        $row = $db->fetch_first($sql_count);
        if ($row) {
            $totalnum = $row['num'];
        } else {
            $totalnum = 0;
        }

        $start = Lib_Global::page_get_start($page, $ppp, $totalnum);

        $sql = 'select * from ' . $ticket->get_table() . $sqladd . " ORDER by status, priority desc, last_t desc LIMIT $start, $ppp";

        //$this->ajax->add_eval_js('console.log("' . addslashes($sql) . '");');

        $this->view->sql = $sql;
        $this->view->db = $db;

        $multipage = Lib_Global::page($totalnum, $ppp, $page, $mpurl);
        $this->view->multipage = $multipage;

        $this->ajax->add_innerhtml('#detail_box', 'list');
        $this->on_ajax();
    }
}