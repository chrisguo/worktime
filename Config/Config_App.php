<?php
class Config_App {
    static public function get_priority_list() {
        $a = array(
            1 => '非常低',
            2 => '低',
            3 => '一般',
            7 => '高',
            8 => '非常高',
            9 => '紧急',
        );
        return $a;
    }

    const STATUS_NEW = 10;
    const STATUS_RESOLVED = 20;
    const STATUS_TEST = 30;
    const STATUS_CLOSE = 50;
    static public function get_status_list() {
        $a = array(
            self::STATUS_NEW => '打开',
            self::STATUS_RESOLVED => '已解决',
            self::STATUS_TEST => '可测试',
            self::STATUS_CLOSE => '关闭',
        );
        return $a;
    }

    const CATY_BUG = 1;
    const CATY_GAI_JIN = 2;
    const CATY_XUE_QIU = 3;
    static public function get_caty_list() {
        $a = array(
            self::CATY_BUG => 'BUG',
            self::CATY_GAI_JIN => '改进',
            self::CATY_XUE_QIU => '需求',
        );
        return $a;
    }

    const TEST_STATUS_NULL = 1;
    const TEST_STATUS_NO = 2;
    const TEST_STATUS_OK = 3;
    static public function get_test_status_list() {
        $a = array(
            self::TEST_STATUS_NULL => '未测试',
            self::TEST_STATUS_NO => '未通过',
            self::TEST_STATUS_OK => '已通过',
        );
        return $a;
    }
}