{*
*
* Google merchant center
*
* @author BusinessTech.fr
* @copyright Business Tech
*
*           ____    _______
*          |  _ \  |__   __|
*          | |_) |    | |
*          |  _ <     | |
*          | |_) |    | |
*          |____/     |_|
*
*}
<div id='{$sModuleName|escape:'htmlall':'UTF-8'}' class="bootstrap form">
	{* HEADER *}
	{include file="`$sHeaderInclude`"  bContentToDisplay=true}
	{* /HEADER *}

	{include file="`$sTopBar`"}

	{* USE CASE - module update not ok  *}
	{if !empty($aUpdateErrors)}
		{include file="`$sErrorInclude`" aErrors=$aUpdateErrors bDebug=true}
		{* USE CASE - display configuration ok *}
	{else}
		{literal}
		<script type="text/javascript">
			var id_language = Number({/literal}{$iCurrentLang|intval}{literal});

			{/literal}
			{* USE CASE - use the new language flags system from PS 1.6 *}
			{if empty($bCompare16)}
			{literal}
			function hideOtherLanguage(id) {
				$('.translatable-field').hide();
				$('.lang-' + id).show();

				var id_old_language = id_language;
				id_language = id;
			}
			{/literal}
			{/if}
			{literal}
		</script>
		{/literal}

		<div class="clr_20"></div>

		<div id="{$sModuleName|escape:'htmlall':'UTF-8'}BlockTab">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
				{*START LEFT MENU*}
				<div id="mainMenu" class="list-group workTabs">
					<a class="list-group-item active" id="tab-2"><span class="icon-heart active"></span>&nbsp;&nbsp;{l s='Basic settings' mod='gmerchantcenter'}</a>
					{*start colapse*}
					<a class="list-group-item" id="tab-001" data-toggle="collapse" href="#collapseOne"><span class="icon-cog"></span>&nbsp;&nbsp;{l s='Feed management' mod='gmerchantcenter'}<span class="pull-right"><i class="icon-caret-down"></i></span> </a>
					<div id="collapseOne" class="panel-collapse collapse">
						<a class="list-group-item" id="tab-001"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Export method' mod='gmerchantcenter'}</a>
						<a class="list-group-item" id="tab-002" href="#feed-management-dropdown2"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Product exclusion rules' mod='gmerchantcenter'}</a>
						<a class="list-group-item" id="tab-003" href="#feed-management-dropdown3"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Feed data options' mod='gmerchantcenter'}</a>
						<a class="list-group-item" id="tab-004" href="#feed-management-dropdown4"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Apparel feed options' mod='gmerchantcenter'}</a>
						<a class="list-group-item" id="tab-005" href="#feed-management-dropdown4"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Taxes and shipping fees' mod='gmerchantcenter'}</a>
					</div>
					<a class="list-group-item" id="tab-010" data-toggle="collapse" href="#collapseTwo"><span class="icon-briefcase"></span>&nbsp;&nbsp;{l s='Google management' mod='gmerchantcenter'}<span class="pull-right"><i class="icon-caret-down"></i></span> </a>
					<div id="collapseTwo" class="panel-collapse collapse">
						<a class="list-group-item" id="tab-010" href="#bing-management-dropdown1"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Matching with Google Categories' mod='gmerchantcenter'}</a>
						<a class="list-group-item" id="tab-011" href="#bing-management-dropdown2"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Google Analytics integration' mod='gmerchantcenter'}</a>
						<a class="list-group-item" id="tab-012" href="#bing-management-dropdown3"><i class="submenu icon icon-chevron-right"></i>&nbsp;{l s='Google Adwords integration / Custom labels' mod='gmerchantcenter'}</a>
					</div>
					<a class="list-group-item" id="tab-3"><span class="icon-align-justify"></span>&nbsp;&nbsp;{l s='My feeds' mod='gmerchantcenter'}</a>
					<a class="list-group-item" id="tab-4"><span class="icon-play"></span>&nbsp;&nbsp;{l s='Reporting' mod='gmerchantcenter'}</a>
				</div>

				{* Doc & FAQ links*}
				<div class="list-group">
					<a class="list-group-item documentation" target="_blank" href="{$sDocUri|escape:'htmlall':'UTF-8'}{$sDocName|escape:'htmlall':'UTF-8'}"><span class="icon-file"></span>&nbsp;&nbsp;{l s='Documentation' mod='gmerchantcenter'}</a>
					<a class="list-group-item" target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}?module=1&lg={$sFaqLang|escape:'htmlall':'UTF-8'}"><span class="icon-info-circle"></span>&nbsp;&nbsp;{l s='Online FAQ' mod='gmerchantcenter'}</a>
					<a class="list-group-item" target="_blank" href="{$sContactUs|escape:'htmlall':'UTF-8'}"><span class="icon-user"></span>&nbsp;&nbsp;{l s='Contact support' mod='gmerchantcenter'}</a>
					<div id="collapseThree" class="panel-collapse collapse">
						<a class="list-group-item" target="_blank" href="https://merchants.google.com"><span class="icon-shopping-cart"></span>&nbsp;&nbsp;{l s='Google shopping account' mod='gmerchantcenter'}</a>
						<a class="list-group-item" target="_blank" href="https://adwords.google.com"><span class="icon-briefcase"></span>&nbsp;&nbsp;{l s='Google Adwords account' mod='gmerchantcenter'}</a>
						<a class="list-group-item" target="_blank" href="https://support.google.com/merchants/topic/7257844?visit_id=1-636189593736100500-4122484685&hl={$sCurrentIso|escape:'htmlall':'UTF-8'}&rd=1"><span class="icon-info"></span>&nbsp;&nbsp;{l s='Best practices guide' mod='gmerchantcenter'}</a>
						<a class="list-group-item" target="_blank" href="https://support.google.com/merchants/answer/2660968?hl={$sCurrentIso|escape:'htmlall':'UTF-8'}&ref_topic=2660962&visit_id=1-636189593736100500-4122484685&rd=1"><span class="icon-link"></span>&nbsp;&nbsp;{l s='Getting started with Shopping campaigns' mod='gmerchantcenter'}</a>
						<a class="list-group-item" target="_blank" href="https://support.google.com/merchants/topic/7286989?hl={$sCurrentIso|escape:'htmlall':'UTF-8'}&ref_topic=7259123"><span class="icon-check"></span>&nbsp;&nbsp;{l s='Google shopping policies' mod='gmerchantcenter'}</a>
					</div>
				</div>

				{* rate me *}
				<div class="list-group">
					<a style="color: #72C279;" class="list-group-item" id="tab-010" data-toggle="collapse" href="#collapseThree"><span class="icon-user" style="color: #72C279;"></span>&nbsp;&nbsp;{l s='Your Google accounts links & Official documentation' mod='gmerchantcenter'}<span class="pull-right"><i class="icon-caret-down"></i></span> </a>
					<a class="list-group-item" target="_blank" href="{$sRateUrl|escape:'htmlall':'UTF-8'}"><i class="icon-star" style="color: #fbbb22;"></i>&nbsp;&nbsp;{l s='Rate me' mod='gmerchantcenter'}</a>
					<a class="list-group-item" href="#"><span class="icon icon-info"></span>&nbsp;&nbsp;{l s='Version' mod='gmerchantcenter'} : {$sModuleVersion|escape:'htmlall':'UTF-8'}</a>
				</div>

			</div>
			{* END LEFT MENU *}
			
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
				{*STAR TAB CONTENT*}
				<div class="tab-content">
					{if empty($bHideConfiguration)}

					{* BASICS SETTINGS *}
					{if !empty($bMultiShop)}
					<div class="alert alert-danger">
						{l s='First of all, you cannot configure your module in the "all shops" or "shops group" mode. Please select one of your shops before moving on into the configuration.' mod='gmerchantcenter'}
					</div>
					{else}
						<div id="content-tab-2" class="tab-pane panel active">
							<div id="bt_basics-settings">
								{include file="`$sBasicsInclude`"}
							</div>
							<div class="clr_20"></div>
							<div id="loadingBasicsDiv" style="display: none;">
								<div class="alert alert-info">
									<p style="text-align: center !important;"><img src="{$sBigLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
									<p style="text-align: center !important;">{l s='Your configuration updating is in progress...' mod='gmerchantcenter'}</p>
								</div>
							</div>
						</div>
					{/if}
					{* /BASICS SETTINGS *}

					{* FEED MANAGEMENT SETTINGS *}
					{if !empty($bMultiShop)}
					<div class="alert alert-danger">
						{l s='First of all, you cannot configure your module in the "all shops" or "shops group" mode. Please select one of your shops before moving on into the configuration.' mod='gmerchantcenter'}
					</div>
					{else}
						<div id="content-tab-001" class="tab-pane panel">
							<div id="bt_feed-settings-export">
								{include file="`$sFeedInclude`" sDisplay="export"}
							</div>
							<div class="clr_20"></div>
						</div>

						<div id="content-tab-002" class="tab-pane panel">
							<div id="bt_feed-settings-exclusion">
								{include file="`$sFeedInclude`" sDisplay="exclusion"}
							</div>
							<div class="clr_20"></div>
						</div>

						<div id="content-tab-003" class="tab-pane panel">
							<div id="bt_feed-settings-data">
								{include file="`$sFeedInclude`" sDisplay="data"}
							</div>
							<div class="clr_20"></div>
						</div>

						<div id="content-tab-004" class="tab-pane panel">
							<div id="bt_feed-settings-apparel">
								{include file="`$sFeedInclude`" sDisplay="apparel"}
							</div>
							<div class="clr_20"></div>
						</div>

						<div id="content-tab-005" class="tab-pane panel">
							<div id="bt_feed-settings-tax">
								{include file="`$sFeedInclude`" sDisplay="tax"}
							</div>
							<div class="clr_20"></div>
						</div>
					{/if}

					<div id="loadingFeedDiv" style="display: none;">
						<div class="alert alert-info">
							<p style="text-align: center !important;"><img src="{$sBigLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
							<p style="text-align: center !important;">{l s='Your configuration updating is in progress...' mod='gmerchantcenter'}</p>
						</div>
					</div>

					{literal}
						<script type="text/javascript">
						// run main feed JS
						oGmc.runMainFeed();
						</script>
					{/literal}
					 {*/FEED MANAGEMENT SETTINGS*}

					{* GOOGLE MANAGEMENT SETTINGS *}
					{if !empty($bMultiShop)}
					<div class="alert alert-danger">
						{l s='First of all, you cannot configure your module in the "all shops" or "shops group" mode. Please select one of your shops before moving on into the configuration.' mod='gmerchantcenter'}
					</div>
					{else}
						<div id="content-tab-010" class="tab-pane panel">
							<div id="bt_google-settings-categories">
								{include file="`$sGoogleInclude`" sDisplay="categories"}
							</div>
							<div class="clr_20"></div>
						</div>

						<div id="content-tab-011" class="tab-pane panel">
							<div id="bt_google-settings-analytics">
								{include file="`$sGoogleInclude`" sDisplay="analytics"}
							</div>
							<div class="clr_20"></div>
						</div>

						<div id="content-tab-012" class="tab-pane panel">
							<div id="bt_google-settings-adwords">
								{include file="`$sGoogleInclude`" sDisplay="adwords"}
							</div>
							<div class="clr_20"></div>
						</div>
					{/if}

					<div id="loadingGoogleDiv" style="display: none;">
						<div class="alert alert-info">
							<p style="text-align: center !important;"><img src="{$sBigLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
							<p style="text-align: center !important;">{l s='Your configuration updating is in progress...' mod='gmerchantcenter'}</p>
						</div>
					</div>

					{literal}
					<script type="text/javascript">
					// run main Google JS
					oGmc.runMainGoogle();
					</script>
					{/literal}
					{* /GOOGLE MANAGEMENT SETTINGS *}

					{* MY FEEDS SETTINGS *}
					<div id="content-tab-3" class="tab-pane panel">
						<div id="bt_feed-list-settings">
							{include file="`$sFeedListInclude`"}
						</div>
						<div class="clr_20"></div>
						<div id="loadingFeedListDiv" style="display: none;">
							<div class="alert alert-info">
								<p style="text-align: center !important;"><img src="{$sBigLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
								<p style="text-align: center !important;">{l s='Your configuration updating is in progress...' mod='gmerchantcenter'}</p>
							</div>
						</div>
					</div>
					{* /MY FEEDS SETTINGS *}

					{* REPORTING SETTINGS *}
					<div id="content-tab-4" class="tab-pane panel">
						<div id="bt_reporting-settings">
							{include file="`$sReportingInclude`"}
						</div>
						<div class="clr_20"></div>
						<div id="loadingReportingDiv" style="display: none;">
							<div class="alert alert-info">
								<p style="text-align: center !important;"><img src="{$sBigLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
								<p style="text-align: center !important;">{l s='Your configuration updating is in progress...' mod='gmerchantcenter'}</p>
							</div>
						</div>
					</div>
					{* /REPORTING SETTINGS *}

				</div>
				{*END TAB CONTENT*}


			</div>
		{else}
			<div class="clr_20"></div>

		{if !empty($bFileStopExec)}
			<div class="alert alert-danger">
				{l s='Please copy the gmerchantcenter.xml.php file from the gmerchantcenter module\'s directory to your shop\'s root directory' mod='gmerchantcenter'}.
			</div>
		{/if}

		{if !empty($bCurlAndContentStopExec)}
			<div class="alert alert-danger">
				{l s='You need to have : either the file_get_contents() with the allow_url_fopen directive enabled in the php.ini file, or the PHP CURL extension enabled, in order to retrieve the Google category definition files from Google\'s website. Please contact your web host. If neither of these options are available to you on your server (but at least one should be in most cases), you will not be able to use this module.' mod='gmerchantcenter'}
			</div>
		{/if}

		{if !empty($bMultishopGroupStopExec)}
			<div class="alert alert-danger">
				{l s='For performance reasons, this module cannot be configured within a shops group context. You must configure it one shop at a time.' mod='gmerchantcenter'}
			</div>
		{/if}
		<div class="clr_20"></div>
	{/if}
		</div> {*END MAIN ROW*}
	</div> {*END FORM*}

	<div class="footer">
		<div class="row">
			<div class="col-xs-12">
				<div class="col-xs-6">
					<ul class="unstyled">
						<li class="footer_title"><i class="fa fa-cog"></i>&nbsp; {l s='Configuration' mod='gmerchantcenter'}<li>
						<li class="footer_link"><a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang}/faq/101">{l s='How to configure my module ?' mod='gmerchantcenter'}</a></li>
						<li class="footer_link"><a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang}/faq/94">{l s='How to import my feeds in Google Shopping?' mod='gmerchantcenter'}</a></li>
					</ul>
				</div>

				<div class="col-xs-6">
					<ul class="unstyled">
						<li class="footer_title"><i class="fa fa-file"></i>&nbsp; {l s='Feed' mod='gmerchantcenter'}<li>
						<li class="footer_link"><a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang}/faq/30">{l s='How to automatically update my feeds?' mod='gmerchantcenter'}</a></li>
						<li class="footer_link"><a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang}/faq/160">{l s='Why some products have not been exported?' mod='gmerchantcenter'}</a></li>
						<li class="footer_link"><a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang}/faq/27">{l s='What to do if my CRON doesn\'t work?' mod='gmerchantcenter'}</a></li>
						<li class="footer_link"><a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang}/faq/51">{l s='Why do I have shipping fees or carrier problems?' mod='gmerchantcenter'}</a></li>
						<li class="footer_link"><a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang}/faq/100">{l s='Why does my feed generate errors?' mod='gmerchantcenter'}</a></li>
					</ul>
				</div>
			</div>

			<div class="clr_10"></div>
			<div class="clr_hr"></div>
			<div class="clr_10"></div>

			<div class="row">
				<div class="col-xs-12">
					<a href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang}/product/43" target="_blank" class="btn btn-lg btn-info pulse pulse_infnite"><i class="fa fa-link"></i>&nbsp; {l s='More FAQ\'s' mod='gmerchantcenter'}</a>
				</div>
			</div>

			<div class="clr_10"></div>

		</div>
	</div>

	{literal}
		<script type="text/javascript">
			// run main Bing JS
			//			oBiad.runMainGoogle();

			$(document).ready(function() {
				$('#content').removeClass('nobootstrap');
				$('#content').addClass('bootstrap');
				$(".workTabs a").click(function(e) {
					e.preventDefault();
					// currentId is the current workTabs id
					var currentId = $(".workTabs a.active").attr('id').substr(4);
					// id is the wanted workTabs id
					var id = $(this).attr('id').substr(4);

					if ($(this).attr("id") != $(".workTabs a.active").attr('id')) {
						$(".workTabs a[id='tab-"+currentId+"']").removeClass('active');
						$("#content-tab-"+currentId).hide();
						$(".workTabs a[id='tab-"+id+"']").addClass('active');
						$("#content-tab-"+id).show();
					}
				});
				$(".workTabs a.active").click();

				$('.label-tooltip, .help-tooltip').tooltip();
				$('.dropdown-toggle').dropdown();
				{/literal}{if !empty($bDisplayAdvice)}{literal}
				$("a#bt_disp-advice").fancybox({
					'hideOnContentClick' : false
				});
				$('#bt_disp-advice').trigger('click');
				{/literal}{/if}{literal}


				// Use case for GSA menu click
				$("#tab-020").click(function(event){
  					 $(this).removeClass('new-bg');
				});

				$("#mainMenu").click(function(event){
  					 $('#tab-020').addClass('new-bg');
				});
			});
		</script>
	{/literal}
	{/if}
</div>