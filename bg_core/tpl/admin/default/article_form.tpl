{* article_form.tpl 文章编辑 *}
{function cate_list arr="" level=""}
	<ul class="list-unstyled{if $level > 0} list_padding{/if}">
		{foreach $arr as $value}
			<li>
				<div class="checkbox_baigo">
					<label for="cate_ids_{$value.cate_id}">
						<input type="checkbox" {if $value.cate_id|in_array:$tplData.articleRow.cate_ids}checked{/if} value="{$value.cate_id}" name="cate_ids[]" id="cate_ids_{$value.cate_id}" class="validate" group="cate_ids">
						{$value.cate_name}
					</label>
				</div>
				{if $value.cate_childs}
					{cate_list arr=$value.cate_childs level=$value.cate_level}
				{/if}
			</li>
		{/foreach}
	</ul>
{/function}

{if $tplData.articleRow.article_id == 0}
	{$title_sub = $lang.page.add}
	{$sub_active = "form"}
{else}
	{$title_sub = $lang.page.edit}
	{$sub_active = "list"}
{/if}

{$cfg = [
	title          => "{$adminMod.article.main.title} - {$title_sub}",
	menu_active    => "article",
	sub_active     => $sub_active,
	baigoValidator => "true",
	baigoSubmit    => "true",
	tinymce        => "true",
	datepicker     => "true",
	tagmanager     => "true",
	upload         => "true",
	str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=article"
]}

{include "include/admin_head.tpl" cfg=$cfg}

	<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=article&act_get=list">{$adminMod.article.main.title}</a></li>
	<li>{$title_sub}</li>

	{include "include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<ul class="list-inline">
			<li>
				<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=article&act_get=list">
					<span class="glyphicon glyphicon-chevron-left"></span>
					{$lang.href.back}
				</a>
			</li>
			<li>
				<a href="{$smarty.const.BG_URL_HELP}?lang=zh_CN&mod=help&act=article#form" target="_blank">
					<span class="glyphicon glyphicon-question-sign"></span>
					{$lang.href.help}
				</a>
			</li>
		</ul>
	</div>

	<form name="article_form" id="article_form">
		<input type="hidden" name="token_session" value="{$common.token_session}">
		<input type="hidden" name="act_post" id="act_post" value="submit">
		<input type="hidden" name="article_id" value="{$tplData.articleRow.article_id}">

		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="form-group">
							<div id="group_article_title">
								<label for="article_title" class="control-label">{$lang.label.articleTitle}<span id="msg_article_title">*</span></label>
								<input type="text" name="article_title" id="article_title" value="{$tplData.articleRow.article_title}" class="validate form-control">
							</div>
						</div>

						<div class="form-group">
							<label class="control-label">
								{$lang.label.articleContent}
								<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=attach&act_get=form&target=editor&view=iframe" class="btn btn-success btn-xs" data-toggle="modal" data-target="#attach_modal">
									<span class="glyphicon glyphicon-picture"></span>
									{$lang.href.uploadList}
								</a>
							</label>
							<textarea name="article_content" id="article_content" class="tinymce text_bg">{$tplData.articleRow.article_content}</textarea>
						</div>

						<label for="article_tag" class="control-label">{$lang.label.articleTag}<span id="msg_article_tag"></span></label>

						<div class="form-group form-inline">
							<input type="text" name="article_tag" id="article_tag" class="form-control tm-input tm-input-success">
							<button type="button" class="btn btn-info tm-btn" id="tag_add">{$lang.btn.add}</button>
						</div>

						<div class="form-group">
							<label for="more_checkbox" class="checkbox-inline">
								<input type="checkbox" id="more_checkbox" name="more_checkbox" {if $tplData.articleRow.article_link}checked{/if}>
								{$lang.label.more}
							</label>
						</div>

						<div id="more_input">
							<div class="form-group">
								<div id="group_article_link">
									<label for="article_link" class="control-label">{$lang.label.articleLink}<span id="msg_article_link"></span></label>
									<input type="text" name="article_link" id="article_link" value="{$tplData.articleRow.article_link}" class="validate form-control">
									<p class="help-block">{$lang.label.articleLinkNote}</p>
								</div>
							</div>

							<div class="form-group">
								<label for="article_excerpt" class="control-label">
									{$lang.label.articleExcerpt}
									<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=attach&act_get=form&target=editor_excerpt&view=iframe" class="btn btn-success btn-xs" data-toggle="modal" data-target="#attach_modal">
										<span class="glyphicon glyphicon-picture"></span>
										{$lang.href.uploadList}
									</a>
								</label>
								<textarea name="article_excerpt" id="article_excerpt" class="tinymce text_md">{$tplData.articleRow.article_excerpt}</textarea>
							</div>
						</div>

						<div class="form-group">
							<button type="button" class="go_submit btn btn-primary">{$lang.btn.save}</button>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="well">
					{if $tplData.articleRow.article_id > 0}
						<div class="form-group">
							<label class="control-label">{$lang.label.id}</label>
							<p class="form-control-static">{$tplData.articleRow.article_id}</p>
						</div>
					{/if}

					<label class="control-label">{$lang.label.articleCate}<span id="msg_cate_ids">*</span></label>
					<div class="form-group">
						{cate_list arr=$tplData.cateRows}
					</div>

					<label class="control-label">{$lang.label.status}<span id="msg_article_status">*</span></label>
					<div class="form-group">
						{foreach $status.article as $key=>$value}
							<label for="article_status_{$key}" class="radio-inline">
								<input type="radio" name="article_status" id="article_status_{$key}" {if $tplData.articleRow.article_status == $key}checked{/if} value="{$key}" class="validate" group="article_status" {if $tplData.adminLogged.groupRow.group_allow.article.approve != 1}disabled{/if}>
								{$value}
							</label>
						{/foreach}
					</div>

					<label class="control-label">{$lang.label.box}<span id="msg_article_box">*</span></label>
					<div class="form-group">
						<label for="article_box_normal" class="radio-inline">
							<input type="radio" name="article_box" id="article_box_normal" {if $tplData.articleRow.article_box == "normal"}checked{/if} value="normal" class="validate" group="article_box">
							{$lang.label.normal}
						</label>

						<label for="article_box_draft" class="radio-inline">
							<input type="radio" name="article_box" id="article_box_draft" {if $tplData.articleRow.article_box == "draft"}checked{/if} value="draft" class="validate" group="article_box">
							{$lang.label.draft}
						</label>

						<label for="article_box_recycle" class="radio-inline">
							<input type="radio" name="article_box" id="article_box_recycle" {if $tplData.articleRow.article_box == "recycle"}checked{/if} value="recycle" class="validate" group="article_box">
							{$lang.label.recycle}
						</label>
					</div>

					<div class="form-group">
						<label for="article_mark_id" class="control-label">{$lang.label.articleMark}</label>
						<select name="article_mark_id" class="form-control">
							<option value="">{$lang.option.noMark}</option>
							{foreach $tplData.markRows as $value}
								<option {if $value.mark_id == $tplData.articleRow.article_mark_id}selected{/if} value="{$value.mark_id}">{$value.mark_name}</option>
							{/foreach}
						</select>
					</div>

					<div class="form-group">
						<div class="checkbox">
							<label for="deadline_checkbox">
								<input type="checkbox" {if $tplData.articleRow.article_time_pub > $smarty.now}checked{/if} id="deadline_checkbox">
								{$lang.label.deadline}
								<span id="msg_article_time_pub"></span>
							</label>
						</div>
					</div>

					<div id="deadline_input">
						<div class="form-group">
							<input type="text" name="article_time_pub" id="article_time_pub" value="{$tplData.articleRow.article_time_pub|date_format:"%Y-%m-%d %H:%M"}" class="validate form-control input_date">
						</div>
						<p class="help-block">{$lang.label.timeNote}</p>
					</div>

					<div class="form-group">
						<label class="control-label">{$lang.label.articleSpec}</label>
						<select name="article_spec_id" class="form-control">
							<option value="">{$lang.option.noSpec}</option>
							{if $tplData.specRow.spec_name}
								<option {if $tplData.specRow.spec_id == $tplData.articleRow.article_spec_id}selected{/if} value="{$tplData.specRow.spec_id}">{$tplData.specRow.spec_name}</option>
							{/if}
							<optgroup label="{$lang.option.pleaseSelect}" id="spec_list"></optgroup>
						</select>
						<div id="spec_page"></div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="modal fade" id="attach_modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content"></div>
		</div>
	</div>

{include "include/admin_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	function reload_spec(_page) {
		$("#spec_list").empty();
		$("#spec_page").empty();

		$.getJSON("{$smarty.const.BG_URL_ADMIN}ajax.php?mod=spec&act_get=list&page=" + _page, function(result){

			_str_appent_page = "<ul class=\"pagination pagination-sm\">";
				_str_appent_page += "<li";
				if (result.pageRow.page <= 1) {
					_str_appent_page += " class=\"disabled\"";
				}
				_str_appent_page += ">";
					if (result.pageRow.page <= 1) {
						_str_appent_page += "<span title=\"{$lang.href.pagePrev}\">&laquo;</span>";
					} else {
						_str_appent_page += "<a href=\"javascript:reload_spec(" + (result.pageRow.page - 1) + ");\" title=\"{$lang.href.pagePrev}\">&laquo;</a>";
					}
				_str_appent_page += "</li>";

				_str_appent_page += "<li>";
					_str_appent_page += "<a href=\"javascript:reload_spec(" + (result.pageRow.page) + ");\">{$lang.btn.reloadSpec}</a>";
				_str_appent_page += "</li>";

				_str_appent_page += "<li";
				if (result.pageRow.page >= result.pageRow.total) {
					_str_appent_page += " class=\"disabled\"";
				}
				_str_appent_page += ">";
					if (result.pageRow.page >= result.pageRow.total) {
						_str_appent_page += "<span title=\"{$lang.href.pageNext}\">&raquo;</span>";
					} else {
						_str_appent_page += "<a href=\"javascript:reload_spec(" + (result.pageRow.page + 1) + ");\" title=\"{$lang.href.pageNext}\">&raquo;</a>";
					}
				_str_appent_page += "</li>";
			_str_appent_page += "</ul>";

			$("#spec_page").append(_str_appent_page);

			$.each(result.specRows, function(i_spec, field_spec){
				_str_appent_spec = "<option value=\"" + field_spec.spec_id + "\">" +
					field_spec.spec_name;
				"</option>";

				$("#spec_list").append(_str_appent_spec);
			});
		});
	}

	var opts_validator_form = {
		article_title: {
			length: { min: 1, max: 300},
			validate: { type: "str", format: "text", group: "group_article_title" },
			msg: { id: "msg_article_title", too_short: "{$alert.x120201}", too_long: "{$alert.x120202}" }
		},
		article_link: {
			length: { min: 0, max: 900 },
			validate: { type: "str", format: "url", group: "group_article_link" },
			msg: { id: "msg_article_link", too_long: "{$alert.x120204}", format_err: "{$alert.x120205}" }
		},
		article_excerpt: {
			length: { min: 0, max: 900 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_article_excerpt", too_long: "{$alert.x120206}" }
		},
		cate_ids: {
			length: { min: 1, max: 0 },
			validate: { type: "checkbox" },
			msg: { id: "msg_cate_ids", too_few: "{$alert.x120207}" }
		},
		article_status: {
			length: { min: 1, max: 0 },
			validate: { type: "radio" },
			msg: { id: "msg_article_status", too_few: "{$alert.x120208}" }
		},
		article_box: {
			length: { min: 1, max: 0 },
			validate: { type: "radio" },
			msg: { id: "msg_article_box", too_few: "{$alert.x120209}" }
		},
		article_time_pub: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "datetime" },
			msg: { id: "msg_article_time_pub", too_short: "{$alert.x120210}", format_err: "{$alert.x120211}" }
		}
	};

	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=article",
		btn_text: "{$lang.btn.ok}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$cfg.str_url}"
	};

	function show_deadline() {
		if ($("#deadline_checkbox").prop("checked")) {
			$("#deadline_input").show();
		} else {
			$("#deadline_input").hide();
		}
	}

	function show_more() {
		if ($("#more_checkbox").prop("checked")) {
			$("#more_input").show();
		} else {
			$("#more_input").hide();
		}
	}

	$(document).ready(function(){
		reload_spec(1);
		show_deadline();
		show_more();
		$("#attach_modal").on("hidden.bs.modal", function() {
		    $(this).removeData("bs.modal");
		});

		var obj_validate_form = $("#article_form").baigoValidator(opts_validator_form);
		var obj_submit_form = $("#article_form").baigoSubmit(opts_submit_form);
		$(".go_submit").click(function(){
			tinyMCE.triggerSave();
			if (obj_validate_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
		$("#deadline_checkbox").click(function(){
			show_deadline();
		});

		$("#more_checkbox").click(function(){
			show_more();
		});

		$(".input_date").datetimepicker(opts_datetimepicker);

		var obj_tagMan = jQuery("#article_tag").tagsManager({
			{if $tplData.articleRow.article_tags}
				prefilled: {$tplData.articleRow.article_tags},
			{/if}
			maxTags: 5,
			backspace: ""
		});

		$("#article_tag").typeahead({
			limit: 200,
			prefetch: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=tag&act_get=list"
		}).on("typeahead:selected", function (e, d) {
			obj_tagMan.tagsManager("pushTag", d.value);
		});

		$("#tag_add").on("click", function (e) {
			var _str_tag = $("#article_tag").val();
			obj_tagMan.tagsManager("pushTag", _str_tag);
		});
	});
	</script>

{include "include/html_foot.tpl" cfg=$cfg}
