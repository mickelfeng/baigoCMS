<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\index;

use app\model\Custom as Custom_Base;
use ginkgo\Func;
use ginkgo\Cache;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

/*-------------自定义字段模型-------------*/
class Custom extends Custom_Base {

    function m_init() { //构造函数
        $this->obj_cache    = Cache::instance();
    }

    function cache($is_tree = true) {
        $_arr_return = array();

        if ($is_tree) {
            $_str_cacheName = 'custom_tree';

            if (!$this->obj_cache->check($_str_cacheName, true)) {
                $this->cacheTreeProcess();
            }
        } else {
            $_str_cacheName = 'custom_lists';

            if (!$this->obj_cache->check($_str_cacheName, true)) {
                $this->cacheProcess();
            }
        }

        $_arr_return = $this->obj_cache->read($_str_cacheName);

        return $_arr_return;
    }


    function cacheProcess() {
        $_arr_search = array(
            'status'    => 'enable',
        );

        $_arr_customRows = $this->lists(1000, 0, $_arr_search);

        return $this->obj_cache->write('custom_lists', $_arr_customRows);
    }


    function cacheTreeProcess() {
        $_arr_search = array(
            'parent_id' => 0,
            'status'    => 'enable',
        );

        $_arr_customRows = $this->listsTree(1000, 0, $_arr_search);

        //print_r($_arr_customRows);

        return $this->obj_cache->write('custom_tree', $_arr_customRows);
    }
}