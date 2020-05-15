<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\index;

use app\model\Cate as Cate_Base;
use ginkgo\Config;
use ginkgo\Func;
use ginkgo\Cache;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

/*-------------栏目模型-------------*/
class Cate extends Cate_Base {

    protected $obj_cache;

    function m_init() { //构造函数
        parent::m_init();

        $this->obj_cache  = Cache::instance();

        $_str_visitType   = Config::get('visit_type', 'var_extra.visit');

        if ($_str_visitType != 'default') {
            Config::set('route_type', 'noBaseFile', 'route');
        }
    }


    function cache($num_cateId = 0) {
        if ($num_cateId > 0) {
            $_str_cacheName = 'cate_' . $num_cateId;

            if (!$this->obj_cache->check($_str_cacheName, true)) {
                $this->cacheProcess($num_cateId);
            }
        } else {
            $_str_cacheName = 'cate_tree';

            if (!$this->obj_cache->check($_str_cacheName, true)) {
                $this->cacheTreeProcess();
            }
        }

        $_arr_return = $this->obj_cache->read($_str_cacheName);

        if ($num_cateId > 0) {
            if (!isset($_arr_return['rcode'])) {
                $_arr_return['rcode'] = 'x250102';
            }

            if (!isset($_arr_return['msg'])) {
                $_arr_return['msg'] = 'Category not found';
            }
        }

        return $_arr_return;
    }


    function urlProcess($arr_cateRow) {
        $_arr_configVisit   = Config::get('visit', 'var_extra');
        $_str_routeCate     = Config::get('cate', 'index.route');

        $_arr_urlRow = array(
            'url'           => '',
            'url_more'      => '',
            'param'         => 'page/',
            'param_more'    => 'page/',
            'suffix'        => '',
        );

        if (isset($arr_cateRow['cate_link']) && !Func::isEmpty($arr_cateRow['cate_link'])) {
            $_arr_urlRow['url'] = $arr_cateRow['cate_link'];
        } else {
            if (isset($arr_cateRow['cate_breadcrumb'][0]['cate_prefix']) && !Func::isEmpty($arr_cateRow['cate_breadcrumb'][0]['cate_prefix'])) {
                $_str_urlPrefix = $arr_cateRow['cate_breadcrumb'][0]['cate_prefix'];
            } else {
                $_str_urlPrefix = $this->obj_request->baseUrl();
            }

            if (!isset($arr_cateRow['cate_id'])) {
                $arr_cateRow['cate_id'] = 0;
            }

            if (!isset($arr_cateRow['cate_url_name'])) {
                $arr_cateRow['cate_url_name'] = '';
            }

            switch ($_arr_configVisit['visit_type']) {
                case 'static':
                    $_arr_urlRow['url']         = $_str_urlPrefix . '/' . $_str_routeCate . '/' . $arr_cateRow['cate_url_name'];
                    $_arr_urlRow['url_more']    = $_str_urlPrefix . '/' . $_str_routeCate . '/' . $arr_cateRow['cate_url_name'] . 'id/' . $arr_cateRow['cate_id'] . '/';
                    $_arr_urlRow['param']       = 'page-';
                    $_arr_urlRow['suffix']      = '.' . $_arr_configVisit['visit_file'];
                break;

                default:
                    $_arr_urlRow['url'] = $_str_urlPrefix . '/' . $_str_routeCate . '/' . $arr_cateRow['cate_url_name'] . 'id/' . $arr_cateRow['cate_id'] . '/';
                break;
            }
        }

        //$_arr_urlRow = str_replace('//', '/', $_arr_urlRow);

        return $_arr_urlRow;
    }


    function listsTree($num_no, $num_except = 0, $arr_search = array(), $num_level = 1) {
        $_arr_cateRows  = $this->lists($num_no, $num_except, $arr_search);

        //print_r($_arr_cateRows);

        $_arr_cates = array();

        foreach ($_arr_cateRows as $_key=>$_value) {
            $_arr_cateRow                                   = $_value;
            $_arr_cateRow['cate_url']                       = $this->urlProcess($_arr_cateRow);

            $_arr_cates[$_value['cate_id']]                 = $_arr_cateRow;

            $_arr_cates[$_value['cate_id']]['cate_level']   = $num_level;
            unset($_arr_cates[$_value['cate_id']]['cate_breadcrumb']);
            $arr_search['parent_id']                        = $_value['cate_id'];
            $_arr_cates[$_value['cate_id']]['cate_childs']  = $this->listsTree(1000, 0, $arr_search, $num_level + 1);
        }

        return $_arr_cates;
    }


    function cacheProcess($num_cateId) {
        $_return = 0;
        $_arr_cateRow = $this->read($num_cateId);

        //print_r($_arr_cateRow);

        if ($_arr_cateRow['rcode'] == 'y250102') {
            $_arr_cateRow['cate_url']        = $this->urlProcess($_arr_cateRow);
            $_arr_cateRow['cate_breadcrumb'] = $this->breadcrumbRowProcess($_arr_cateRow['cate_breadcrumb']);
            $_arr_cateRow['cate_ids']        = $this->ids($num_cateId);
            $_return                         = $this->obj_cache->write('cate_' . $num_cateId, $_arr_cateRow);
        }

        return $_return;
    }


    function cacheTreeProcess() {
        $_arr_search = array(
            'parent_id' => 0,
            'status'    => 'show',
        );

        $_arr_cateRows = $this->listsTree(1000, 0, $_arr_search);

        return $this->obj_cache->write('cate_tree', $_arr_cateRows);
    }


    function breadcrumbRowProcess($arr_breadcrumbRows) {
        foreach ($arr_breadcrumbRows as $_key=>$_value) {
            $_arr_breadcrumbRow                       = $this->rowProcess($_value);
            $arr_breadcrumbRows[$_key]                = $_arr_breadcrumbRow;
            $arr_breadcrumbRows[$_key]['cate_url']    = $this->urlProcess($_arr_breadcrumbRow);
        }

        return $arr_breadcrumbRows;
    }
}