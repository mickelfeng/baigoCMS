<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------文章类-------------*/
class CONTROL_ARTICLE {

	private $cateRow;
	private $articleRow;
	private $tplData;
	private $obj_tpl;
	private $mdl_cate;
	private $mdl_articlePub;
	private $mdl_tag;
	private $mdl_attach;
	private $config;

	function __construct() { //构造函数
		$this->mdl_cate       = new MODEL_CATE(); //设置文章对象
		$this->mdl_articlePub = new MODEL_ARTICLE_PUB(); //设置文章对象
		$this->mdl_tag        = new MODEL_TAG();
		$this->mdl_custom     = new MODEL_CUSTOM();
		$this->article_init();
		$_arr_cfg["pub"]      = true;
		$this->obj_tpl        = new CLASS_TPL(BG_PATH_TPLPUB . $this->config["tpl"], $_arr_cfg); //初始化视图对象
		$this->mdl_attach     = new MODEL_ATTACH();
		$this->mdl_thumb      = new MODEL_THUMB();
	}


	/**
	 * ctl_list function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_show() {
		if ($this->articleId == 0) {
			return array(
				"alert" => "x120212",
			);
			exit;
		}

		if ($this->articleRow["alert"] != "y120102") {
			return $this->articleRow;
			exit;
		}

		if (strlen($this->articleRow["article_title"]) < 1 || $this->articleRow["article_status"] != "pub" || $this->articleRow["article_box"] != "normal" || $this->articleRow["article_time_pub"] > time()) {
			return array(
				"alert" => "x120102",
			);
			exit;
		}

		if ($this->articleRow["article_link"]) {
			return array(
				"alert"         => "x120213",
				"article_link"  => $this->articleRow["article_link"],
			);
			exit;
		}

		if ($this->cateRow["alert"] != "y110102") {
			return $this->cateRow;
			exit;
		}

		if ($this->cateRow["cate_status"] != "show") {
			return array(
				"alert" => "x110102",
			);
			exit;
		}

		if ($this->cateRow["cate_type"] == "link" && $this->cateRow["cate_link"]) {
			return array(
				"alert" => "x110218",
				"cate_link" => $this->cateRow["cate_link"],
			);
			exit;
		}

		$this->articleRow["cateRow"]          = $this->cateRow;
		$this->articleRow["tagRows"]          = $this->mdl_tag->mdl_list(10, 0, "", "show", "tag_id", $this->articleRow["article_id"]);

		if (!file_exists(BG_PATH_CACHE . "sys/thumb_list.php")) {
			$this->mdl_thumb->mdl_cache();
		}
		$this->mdl_attach->thumbRows = include(BG_PATH_CACHE . "sys/thumb_list.php");

		if (!file_exists(BG_PATH_CACHE . "sys/cate_trees.php")) {
			$this->mdl_cate->mdl_cache();
		}

		$_arr_cateRows = include(BG_PATH_CACHE . "sys/cate_trees.php");

		if (!file_exists(BG_PATH_CACHE . "sys/custom_list.php")) {
			$this->mdl_custom->mdl_cache();
		}
		$_arr_customRows = include(BG_PATH_CACHE . "sys/custom_list.php");

		if ($this->articleRow["article_attach_id"] > 0) {
			$_arr_attachRow = $this->mdl_attach->mdl_url($this->articleRow["article_attach_id"]);
			if ($_arr_attachRow["alert"] == "y070102") {
				if ($_arr_attachRow["attach_box"] != "normal") {
					$_arr_attachRow = array(
						"alert" => "x070102",
					);
				}
			}
			$this->articleRow["attachRow"]   = $_arr_attachRow;
		}

		//print_r(date("W", strtotime("2014-12-01")));

		$this->mdl_articlePub->mdl_hits($this->articleRow["article_id"]);

		$_arr_tagIds          = array();
		$_arr_associateRows   = array();

		foreach ($this->articleRow["tagRows"] as $_key=>$_value) {
			$_arr_tagIds[] = $_value["tag_id"];
		}

		if ($_arr_tagIds) {
			$_arr_associateRows = $this->mdl_articlePub->mdl_list(BG_SITE_ASSOCIATE, 0, "", "", "", false, false, false, $_arr_tagIds);

			foreach ($_arr_associateRows as $_key=>$_value) {
				$_arr_associateRows[$_key]["tagRows"] = $this->mdl_tag->mdl_list(10, 0, "", "show", "tag_id", $_value["article_id"]);

				if ($_value["article_attach_id"] > 0) {
					$_arr_attachRow = $this->mdl_attach->mdl_url($_value["article_attach_id"]);
					if ($_arr_attachRow["alert"] == "y070102") {
						if ($_arr_attachRow["attach_box"] != "normal") {
							$_arr_attachRow = array(
								"alert" => "x070102",
							);
						}
					}
					$_arr_associateRows[$_key]["attachRow"]   = $_arr_attachRow;
				}

				if (!file_exists(BG_PATH_CACHE . "sys/cate_" . $_value["article_cate_id"] . ".php")) {
					$this->mdl_cate->mdl_cache();
				}

				$_arr_associateRows[$_key]["cateRow"] = include(BG_PATH_CACHE . "sys/cate_" . $_value["article_cate_id"] . ".php");
			}
		}


		$_arr_tpl = array(
			"cateRows"       => $_arr_cateRows,
			"customRows"     => $_arr_customRows["custom_list"],
			"articleRow"     => $this->articleRow,
			"associateRows"  => $_arr_associateRows,
		);

		$this->obj_tpl->tplDisplay("article_show.tpl", $_arr_tpl);

		return array(
			"alert" => "y120102",
		);
	}


	/**
	 * article_init function.
	 *
	 * @access private
	 * @return void
	 */
	private function article_init() {
		$this->articleId   = fn_getSafe(fn_get("article_id"), "int", 0);

		if(defined("BG_SITE_TPL")) {
			$_str_tpl = BG_SITE_TPL;
		} else {
			$_str_tpl = "default";
		}

		if ($this->articleId > 0) {
			$this->articleRow = $this->mdl_articlePub->mdl_read($this->articleId);
			if ($this->articleRow["alert"] == "y120102") {
    			if (!file_exists(BG_PATH_CACHE . "sys/cate_" . $this->articleRow["article_cate_id"] . ".php")) {
    				$this->mdl_cate->mdl_cache();
    			}

    			if (file_exists(BG_PATH_CACHE . "sys/cate_" . $this->articleRow["article_cate_id"] . ".php")) {
    				$this->cateRow          = include(BG_PATH_CACHE . "sys/cate_" . $this->articleRow["article_cate_id"] . ".php");
    				$this->config["tpl"]    = $this->cateRow["cate_tplDo"];
    			} else {
    				$this->cateRow["alert"] = "x110102";
    				$this->config["tpl"]    = "default";
    			}
			}
		}
	}
}
