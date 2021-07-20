<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\install;

use app\classes\install\Model;
use ginkgo\Loader;
use ginkgo\Func;

//不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------管理员模型-------------*/
class Admin extends Model {

    private $create;

    function m_init() {
        $this->mdl_adminBase    = Loader::model('Admin', '', false);
        $this->arr_status       = $this->mdl_adminBase->arr_status;
        $this->arr_type         = $this->mdl_adminBase->arr_type;

        $_str_status    = implode('\',\'', $this->arr_status);
        $_str_type      = implode('\',\'', $this->arr_type);

        $this->create = array(
            'admin_id' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'ai'        => true,
                'comment'   => 'ID',
            ),
            'admin_name'  => array(
                'type'      => 'varchar(30)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '用户名'
            ),
            'admin_note' => array(
                'type'      => 'varchar(30)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '备注'
            ),
            'admin_nick' => array(
                'type'      => 'varchar(30)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '昵称',
            ),
            'admin_allow_cate' => array(
                'type'      => 'varchar(3000)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '栏目权限',
            ),
            'admin_allow_profile' => array(
                'type'      => 'varchar(1000)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '个人权限',
            ),
            'admin_prefer' => array(
                'type'      => 'varchar(3000)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '个人偏好',
            ),
            'admin_shortcut' => array(
                'type'      => 'varchar(1000)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '仪表板设置',
            ),
            'admin_group_id' => array(
                'type'      => 'smallint(6)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '群组 ID',
            ),
            'admin_time' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '登录时间',
            ),
            'admin_time_login' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '最后登录',
            ),
            'admin_status' => array(
                'type'      => 'enum(\'' . $_str_status . '\')',
                'not_null'  => true,
                'default'   => $this->arr_status[0],
                'comment'   => '状态',
                'update'    => $this->arr_status[0], //修改表结构以后是否更新
            ),
            'admin_type' => array(
                'type'      => 'enum(\'' . $_str_type . '\')',
                'not_null'  => true,
                'default'   => $this->arr_type[0],
                'comment'   => '类型',
                'update'    => $this->arr_type[0],
            ),
            'admin_ip' => array(
                'type'      => 'char(15)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => 'IP',
            ),
            'admin_access_token' => array(
                'type'      => 'char(32)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '访问口令',
            ),
            'admin_access_expire' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '访问口令过期时间',
            ),
            'admin_refresh_token' => array(
                'type'      => 'char(32)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '刷新口令',
            ),
            'admin_refresh_expire' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '刷新口令过期时间',
            ),
        );
    }

    /** 创建表
     * mdl_create_table function.
     *
     * @access public
     * @return void
     */
    function createTable() {
        $_num_count  = $this->create($this->create, 'admin_id', '管理帐号');

        if ($_num_count !== false) {
            $_str_rcode = 'y020105'; //更新成功
            $_str_msg   = 'Create table successfully';
        } else {
            $_str_rcode = 'x020105'; //更新成功
            $_str_msg   = 'Create table failed';
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
            'msg'   => $_str_msg,
        );
    }


    /** 修改表
     * mdl_alter_table function.
     *
     * @access public
     * @return void
     */
    function alterTable() {
        $_arr_alter = $this->alterProcess($this->create);

        $_str_rcode = 'y020111';
        $_str_msg   = 'No need to update table';

        if (!Func::isEmpty($_arr_alter)) {
            $_num_count  = $this->alter($_arr_alter);

            if ($_num_count !== false) {
                $_str_rcode = 'y020106';
                $_str_msg   = 'Update table successfully';

                foreach ($this->create as $_key=>$_value) {
                    if (isset($_value['update'])) {
                        $_arr_data = array(
                            $_key => $_value['update'],
                        );

                        $_result = $this->where('LENGTH(`' . $_key . '`) < 1')->update($_arr_data);
                    }
                }
            } else {
                $_str_rcode = 'x020106';
                $_str_msg   = 'Update table failed';
            }
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    function check($mix_admin, $str_by = 'admin_id', $num_notId = 0) {
        return $this->mdl_adminBase->check($mix_admin, $str_by, $num_notId);
    }


    function submit() {
        $_arr_adminRow  = $this->check($this->inputSubmit['admin_id']);

        $_arr_adminData = array(
            'admin_id'          => $this->inputSubmit['admin_id'],
            'admin_name'        => $this->inputSubmit['admin_name'],
            'admin_time'        => GK_NOW,
            'admin_time_login'  => GK_NOW,
            'admin_ip'          => $this->obj_request->ip(),
            'admin_type'        => 'super',
            'admin_status'      => 'enable',
        );

        $_mix_vld = $this->validate($_arr_adminData, '', 'submit_db');

        if ($_mix_vld !== true) {
            return array(
                'admin_id'  => $this->inputSubmit['admin_id'],
                'rcode'     => 'x020201',
                'msg'       => end($_mix_vld),
            );
        }

        if ($_arr_adminRow['rcode'] == 'x020102') {
            $_num_adminId   = $this->insert($_arr_adminData);
            if ($_num_adminId >= 0) {
                $_str_rcode = 'y020101'; //插入成功
                $_str_msg   = 'Add administrator successfully';
            } else {
                $_str_rcode = 'x020101'; //插入失败
                $_str_msg   = 'Add administrator failed';
            }
        } else {
            $_num_adminId   = $this->inputSubmit['admin_id'];
            $_num_count     = $this->where('admin_id', '=', $_num_adminId)->update($_arr_adminData); //更新数
            if ($_num_count > 0) {
                $_str_rcode = 'y020103'; //更新成功
                $_str_msg   = 'Add administrator successfully';
            } else {
                $_str_rcode = 'x020103'; //更新成功
                $_str_msg   = 'Add administrator successfully';
            }
        }

        return array(
            'admin_id'   => $_num_adminId,
            'rcode'      => $_str_rcode,
            'msg'        => $_str_msg,
        );
    }


    /** 安装时创建
     * inputSubmit function.
     *
     * @access public
     * @return void
     */
    function inputSubmit() {
        $_arr_inputParam = array(
            'admin_name'            => array('str', ''),
            'admin_pass'            => array('str', ''),
            'admin_pass_confirm'    => array('str', ''),
            'admin_mail'            => array('str', ''),
            'admin_nick'            => array('str', ''),
            '__token__'             => array('str', ''),
        );

        $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x020201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputSubmit['rcode'] = 'y020201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }


    function inputAuth() {
        $_arr_inputParam = array(
            'admin_name'            => array('str', ''),
            '__token__'             => array('str', ''),
        );

        $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'auth');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x020201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputSubmit['rcode'] = 'y020201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }
}
