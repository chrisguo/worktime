<?php
class O_Response {

    protected $view = null;

    protected $content = array(
        'id_innerhtml' => array(),
        'popup' => '',
        'eval_js' => '',
    );

    public function set_view($view) {
        $this->view = $view;
    }

    public function get_content() {
        return json_encode($this->content);
    }

    public function add_eval_js($a) {
        $this->content['eval_js'] .= $a;
    }

    public function innerhtml($id, $content) {
        $this->content['id_innerhtml'][$id] = $content;
    }

    public function add_innerhtml($id, $tpl) {
        $this->content['id_innerhtml'][$id] = $this->view->fetch($tpl);
    }

    public function popup($tpl) {
        $this->content['popup'] = $this->view->fetch($tpl);
    }
}