<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_FUNC . "include.func.php"); //验证是否已登录
fn_include(true, true, "Content-type: application/json; charset=utf-8", true, "ajax", true);

include_once(BG_PATH_CONTROL . "admin/ajax/group.class.php"); //载入模板类

$ajax_group = new AJAX_GROUP();

switch ($GLOBALS["act_post"]) {
	case "submit":
		$ajax_group->ajax_submit();
	break;

	case "enable":
	case "disable":
		$ajax_group->ajax_status();
	break;

	case "del":
		$ajax_group->ajax_del();
	break;

	default:
		switch ($GLOBALS["act_get"]) {
			case "chkname":
				$ajax_group->ajax_chkname();
			break;
		}
	break;
}
