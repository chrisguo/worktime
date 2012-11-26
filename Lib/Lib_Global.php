<?php

class Lib_Global {

    public static function xml2array($fileurl) {
        $xml = simplexml_load_file($fileurl, 'SimpleXMLElement', LIBXML_NOCDATA);
        self::_xml2array($xml);
        return $xml;
    }

    private static function _xml2array(&$xml) {
        $xml = (array) $xml;
        if ($xml) {
            foreach ($xml as &$v) {
                if (is_object($v)) {
                    self::_xml2array($v);
                }
            }
        } else {
            $xml = '';
        }
    }

    static public function clone_($o) {
        return unserialize(serialize($o));
    }

    public static function get_hash($num) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $hash = '';
        for ($i = 0; $i < $num; $i++) {
            $hash .= $chars[mt_rand(0, 60)];
        }
        return $hash;
    }

    public static function get_from_api($url, $data = array()) {
        $url = $url;

        $paras = '';
        $glue = '&';
        $comma = '';
        foreach ($data as $k => $v) {
            $v = urlencode($v);
            $paras .= $comma . $k . '=' . $v;
            $comma = $glue;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    }

    /*
     * @param       int     $total          总记录数
     * @param       int     $limit          每页显示条数
     * @param       int     $page           当前页
     * @param       int     $show_limit     显示的页数
     * @param       string  $url            每页连接
     */

    static public function page($num, $perpage, $curpage, $mpurl) {
        $multipage = '';
        $mpurl .= '&';
        if($num > $perpage) {
            $page = 10;
            $offset = 2;

            $pages = @ceil($num / $perpage);

            if($page > $pages) {
                $from = 1;
                $to = $pages;
            } else {
                $from = $curpage - $offset;
                $to = $from + $page - 1;
                if($from < 1) {
                    $to = $curpage + 1 - $from;
                    $from = 1;
                    if($to - $from < $page) {
                        $to = $page;
                    }
                } elseif($to > $pages) {
                    $from = $pages - $page + 1;
                    $to = $pages;
                }
            }

            $multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="javascript:;" onclick="ajax(\''.$mpurl.'page=1\');" class="first">首页</a>' : '').
                ($curpage > 1 ? '<a href="javascript:;" onclick="ajax(\''.$mpurl.'page='.($curpage - 1).'\');" class="prev">上页</a>' : '');
            for($i = $from; $i <= $to; $i++) {
                $multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
                    '<a href="javascript:;" onclick="ajax(\''.$mpurl.'page='.$i.($i == $pages ? '#' : '').'\');">'.$i.'</a>';
            }

            $multipage .= ($curpage < $pages ? '<a href="javascript:;" onclick="ajax(\''.$mpurl.'page='.($curpage + 1).'\');" class="next">下页</a>' : '').
                ($to < $pages ? '<a href="javascript:;" onclick="ajax(\''.$mpurl.'page='.$pages.'\');" class="last">未页</a>' : '').
                ($pages > $page ? '<label><input value="' . $curpage . '" class="px" type="text" name="custompage" title="输入页码，按回车快速跳转" size="3" onkeydown="if(event.keyCode==13) {ajax(\''.$mpurl.'page=\'+this.value\'); return false;}" /><span title="共 ' . $pages . ' 页"> / ' . $pages . ' 页</span></label>' : '');

            $multipage = $multipage ? '<div class="pages"><em>&nbsp;'.$num.'&nbsp;</em>' . $multipage.'</div>' : '';
        }
        return $multipage;
    }

    static public function page_get_start($page, $ppp, $totalnum) {
        $totalpage = ceil($totalnum / $ppp);
        $page =  max(1, min($totalpage, intval($page)));
        return ($page - 1) * $ppp;
    }

    static public function get_row_alt($alt) {
        if ($alt) {
            $alt = false;
            echo ' alt';
        } else {
            $alt = true;
        }
        return $alt;
    }

    static public function get_day_week($day) {
        $a = array(
            1 => '一',
            2 => '二',
            3 => '三',
            4 => '四',
            5 => '五',
            6 => '六',
            7 => '日',
        );
        $t = strtotime($day);
        return date('Y-m-d', $t) . ' 星期' . $a[date('N', $t)];
    }

    static public function format_select_options($a, $selected) {
        $rtn = '';
        foreach ($a as $k => $v) {
            $rtn .= '<option value="' . $k . '" ' . ($selected && $k == $selected ? 'selected' : '') . '>' . $v . '</option>';
        }
        return $rtn;
    }
}