{* html_foot.tpl HTML 底部通用 *}

	{if $cfg.tagmanager}
		<script src="{$smarty.const.BG_URL_JS}typeahead/typeahead.min.js" type="text/javascript"></script>
		<script src="{$smarty.const.BG_URL_JS}tagmanager/tagmanager.js" type="text/javascript"></script>
	{/if}

	{if $cfg.upload}
		<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
		<script src="{$smarty.const.BG_URL_JS}jQuery-File-Upload/jquery.ui.widget.js" type="text/javascript"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		<script src="{$smarty.const.BG_URL_JS}jQuery-File-Upload/jquery.iframe-transport.js" type="text/javascript"></script>
		<!-- The basic File Upload plugin -->
		<script src="{$smarty.const.BG_URL_JS}jQuery-File-Upload/jquery.fileupload.js" type="text/javascript"></script>
	{/if}

	{if $cfg.baigoValidator}
		<!--表单验证 js-->
		<script src="{$smarty.const.BG_URL_JS}baigoValidator/baigoValidator.js" type="text/javascript"></script>
	{/if}

	{if $cfg.baigoSubmit}
		<!--表单 ajax 提交 js-->
		<script src="{$smarty.const.BG_URL_JS}baigoSubmit/baigoSubmit.js" type="text/javascript"></script>
	{/if}

	{if $cfg.reloadImg}
		<!--重新载入图片 js-->
		<script src="{$smarty.const.BG_URL_JS}reloadImg.js" type="text/javascript"></script>
	{/if}

	{if $cfg.baigoCheckall}
		<!--全选 js-->
		<script src="{$smarty.const.BG_URL_JS}baigoCheckall.js" type="text/javascript"></script>
	{/if}

	{if $cfg.datepicker}
		<!--日历插件-->
		<script src="{$smarty.const.BG_URL_JS}datetimepicker/jquery.datetimepicker.js" type="text/javascript"></script>
		<script type="text/javascript">
		var opts_datetimepicker = {
			lang: "{$config.lang}",
			i18n: {
				{$config.lang}: {
					months: ["{$lang.digit.1}{$lang.label.month}", "{$lang.digit.2}{$lang.label.month}", "{$lang.digit.3}{$lang.label.month}", "{$lang.digit.4}{$lang.label.month}", "{$lang.digit.5}{$lang.label.month}", "{$lang.digit.6}{$lang.label.month}", "{$lang.digit.7}{$lang.label.month}", "{$lang.digit.8}{$lang.label.month}", "{$lang.digit.9}{$lang.label.month}", "{$lang.digit.10}{$lang.label.month}", "{$lang.digit.10}{$lang.digit.1}{$lang.label.month}", "{$lang.digit.10}{$lang.digit.2}{$lang.label.month}"],
		    monthsShort: ["{$lang.digit.1}", "{$lang.digit.2}", "{$lang.digit.3}", "{$lang.digit.4}", "{$lang.digit.5}", "{$lang.digit.6}", "{$lang.digit.7}", "{$lang.digit.8}", "{$lang.digit.9}", "{$lang.digit.10}", "{$lang.digit.10}{$lang.digit.1}", "{$lang.digit.10}{$lang.digit.2}"],
					dayOfWeek: ["{$lang.digit.0}", "{$lang.digit.1}", "{$lang.digit.2}", "{$lang.digit.3}", "{$lang.digit.4}", "{$lang.digit.5}", "{$lang.digit.6}"]
				}
			},
			//timepicker: false,
			format: "Y-m-d H:i",
			step: 30,
			mask: true
		};
		var opts_datepicker = {
			lang: "{$config.lang}",
			i18n: {
				{$config.lang}: {
					months: ["{$lang.digit.1}{$lang.label.month}", "{$lang.digit.2}{$lang.label.month}", "{$lang.digit.3}{$lang.label.month}", "{$lang.digit.4}{$lang.label.month}", "{$lang.digit.5}{$lang.label.month}", "{$lang.digit.6}{$lang.label.month}", "{$lang.digit.7}{$lang.label.month}", "{$lang.digit.8}{$lang.label.month}", "{$lang.digit.9}{$lang.label.month}", "{$lang.digit.10}{$lang.label.month}", "{$lang.digit.10}{$lang.digit.1}{$lang.label.month}", "{$lang.digit.10}{$lang.digit.2}{$lang.label.month}"],
		    monthsShort: ["{$lang.digit.1}", "{$lang.digit.2}", "{$lang.digit.3}", "{$lang.digit.4}", "{$lang.digit.5}", "{$lang.digit.6}", "{$lang.digit.7}", "{$lang.digit.8}", "{$lang.digit.9}", "{$lang.digit.10}", "{$lang.digit.10}{$lang.digit.1}", "{$lang.digit.10}{$lang.digit.2}"],
					dayOfWeek: ["{$lang.digit.0}", "{$lang.digit.1}", "{$lang.digit.2}", "{$lang.digit.3}", "{$lang.digit.4}", "{$lang.digit.5}", "{$lang.digit.6}"]
				}
			},
			timepicker: false,
			format: "Y-m-d",
			mask: true
		};
		</script>
	{/if}

	<script src="{$smarty.const.BG_URL_JS}bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

</html>