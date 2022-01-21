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
<div class="bootstrap" id="gmc">
{if !empty($sGmcLink)}
	{if !empty($iTotalProductToExport)}
		{literal}
		<script type="text/javascript">
			var aDataFeedGenOptions = {
				'sURI' : '{/literal}{$sURI}{literal}',
				'sParams' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.dataFeed.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.dataFeed.type|escape:'htmlall':'UTF-8'}{literal}',
				'iShopId' : {/literal}{$iShopId|intval}{literal},
				'sFilename' : '',
				'iLangId' : 0,
				'sLangIso' : '',
				'sCountryIso' : '',
				'sCurrencyIso' : '',
				'iStep' : 0,
				'iTotal' : {/literal}{$iTotalProductToExport|intval}{literal},
				'iProcess' : 0,
				'sDisplayedCounter' : '#regen_counter',
				'sDisplayedBlock' : '#syncCounterDiv',
				'sDisplaySuccess' : '#regen_xml',
				'sDisplayTotal' : '#total_product_processed',
				'sLoaderBar' : 'myBar',
				'sErrorContainer' : 'AjaxFeed',
				//'bReporting' : {/literal}{$bReporting|escape:'htmlall':'UTF-8'}{literal},
				'bReporting' : 1,
				'sDisplayReporting' : '#handleGenerateReportingBox',
				'sResultText' : '{/literal}{l s='product(s) exported' mod='gmerchantcenter'}{literal}'
		};
		</script>
		{/literal}

		<h3><i class="icon icon-align-justify"></i>&nbsp;{l s='Products data feed' mod='gmerchantcenter'}</h3>
		<div class="clr_10"></div>
		{if !empty($bUpdate)}
			{include file="`$sConfirmInclude`"}
		{elseif !empty($aErrors)}
			{include file="`$sErrorInclude`"}
		{/if}


		{* USE CASE - AVAILABLE FEED FILE LIST *}
		{if !empty($aFeedFileList)}
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-2"></div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<div class="box xml">
							{if $iTotalProduct > 5000}
								<div class="ribbon"><span>{l s='Recommended' mod='gmerchantcenter'}</span></div>
							{/if}
							<div class="box-icon icon-active-cog">
								<span class="icon icon-cogs icon-3x"></span>
							</div>
							<div class="info col-xs-12">
								<h4 class="text-center">{l s='PHYSICAL FILE + CRON TASK' mod='gmerchantcenter'}</h4>
								<p class="center box-export col-xs-12">{l s='This export method is recommended for large products catalogs (usually > 5000 products)' mod='gmerchantcenter'}</p>
								<div class="center col-xs-12">
									<a id="btn-xml" class="btn btn-lg btn-lg-custom btn-success">{l s='Use this solution' mod='gmerchantcenter'}</a>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
						<div class="box fly">
							{if $iTotalProduct <= 5000}
								<div class="ribbon"><span>{l s='Recommended' mod='gmerchantcenter'}</span></div>
							{/if}
							<div class="box-icon icon-active-file">
								<span class="icon icon-file icon-3x"></span>
							</div>
							<div class="info col-xs-12">
								<h4 class="text-center">{l s='ON THE FLY OUTPUT' mod='gmerchantcenter'}</h4>
								<p class="center box-export col-xs-12">{l s='This export method is recommended for small products catalogs (usually < 5000 products)' mod='gmerchantcenter'}</p>
								<div class="center col-xs-12">
									<a id="btn-fly" class="btn btn-lg btn-lg-custom btn-success">{l s='Use this solution' mod='gmerchantcenter'}</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="clr_50"></div>

			<div class="bt-fb-cron" style="display: none;">
				<ul class="nav nav-tabs" id="myTab"">
					<li class="active">
						<a data-toggle="tab" href="#xml"><i class="fa fa-file-code-o"></i>&nbsp;{l s='Your XML files' mod='gmerchantcenter'}</a>
					</li>
					<li class="nav-item">
						<a data-toggle="tab" href="#cron"><i class="fa fa-server"></i>&nbsp;{l s='Your CRON URL\'s' mod='gmerchantcenter'}</a>
					</li>
				</ul>

				<div class="tab-content" id="myTabContent">
					<div class="tab-pane active" id="xml">
						<form class="form-horizontal col-xs-12" action="{$sURI|escape:'htmlall':'UTF-8'}" method="post" id="bt_feedlist-form" name="bt_feedlist-form" {if $smarty.const._GMC_USE_JS == true}onsubmit="javascript: oGmc.form('bt_feedlist-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_feed-list-settings', 'bt_feed-list-settings', false, false, null, 'FeedList', 'loadingFeedListDiv');return false;"{/if}>
							<input type="hidden" name="sAction" value="{$aQueryParams.feedListUpdate.action|escape:'htmlall':'UTF-8'}" />
							<input type="hidden" name="sType" value="{$aQueryParams.feedListUpdate.type|escape:'htmlall':'UTF-8'}" />
							<div class="clr_50"></div>
							<div id="syncCounterDiv" style="display: none;" class="alert alert-success">
								<button type="button" class="close" onclick="$('#syncCounterDiv').hide();">×</button>
								<h3>{l s='Export in progress' mod='gmerchantcenter'}</h3>
								<div class="row">
									<b>{l s='Number of products generated:' mod='gmerchantcenter'}</b>&nbsp;
									<input  size="5" name="bt_regen-counter" id="regen_counter" value="0" />&nbsp;
									{l s='on' mod='gmerchantcenter'}&nbsp;{$iTotalProduct|intval} ({l s='total of products on the shop' mod='gmerchantcenter'})
									<div class="clr_10"></div>
									<div class="progress">
										<div class="progress-bar bg-success progress-bar-striped active" id="myBar"></div>
									</div>

								</div>
								<div class="row">
									<div id="{$sModuleName|escape:'htmlall':'UTF-8'}AjaxFeedError"></div>
								</div>
								<div class="clr_20"></div>
							</div>

							<div class="clr_20"></div>

							<div class="alert alert-info">
								<p><strong class="highlight_element">{l s='Here is the XML files that will receive your feed data every time the CRON task will be executed.' mod='gmerchantcenter'}</strong></p><br />
								<ul>
									<li>{l s='If you want to use a general CRON task, in order to update XML files in the same time, you have first to tick all the files that you want to be filled in. Then,' mod='gmerchantcenter'}<b>&nbsp;{l s='SAVE YOUR SELECTION' mod='gmerchantcenter'}</b>&nbsp;{l s='(button under the table, on the right) and set up your CRON task by using the general CRON URL that appears.' mod='gmerchantcenter'}</li>
									<li>{l s='If you want to set up a CRON task for each feed in order to update them one by one (if data number is too large to execute several feeds in the same time and you get a time-out server), you don\'t have to tick any file. Use the independent CRON URL\'s that are under the general one.' mod='gmerchantcenter'}</li>
								</ul>
							</div>

							<div class="clr_10"></div>

							<div class="btn-actions pull-right">
								<div class="btn btn-default btn-mini" id="categoryCheck" onclick="return oGmc.selectAll('input.bt_export_feed', 'check');"><span class="icon-plus-square"></span>&nbsp;{l s='Check All' mod='gmerchantcenter'}</div> - <div class="btn btn-default btn-mini" id="categoryUnCheck" onclick="return oGmc.selectAll('input.bt_export_feed', 'uncheck');"><span class="icon-minus-square"></span>&nbsp;{l s='Uncheck All' mod='gmerchantcenter'}</div>
								<div class="clr_10"></div>
							</div>

							<table border="0" cellpadding="2" cellspacing="2" class="table table-responsive">
								<tr class="bt_tr_header text-center">
									<th class="center col-xs-1">{l s='Regenerate during CRON' mod='gmerchantcenter'}</th>
									<th class="center">{l s='Country' mod='gmerchantcenter'}</th>
									<th class="center">{l s='Language' mod='gmerchantcenter'}</th>
									<th class="center">{l s='Currency' mod='gmerchantcenter'}</th>
									<th class="center">{l s='Last update' mod='gmerchantcenter'}</th>
									<th class="center">{l s='Action' mod='gmerchantcenter'}</th>
								</tr>
								{foreach from=$aFeedFileList name=feed key=iKey item=aFeed}
									<tr id="regen_xml_{$aFeed.lang|lower|escape:'htmlall':'UTF-8'}_{$aFeed.country|lower|escape:'htmlall':'UTF-8'}">
										<td class="center"><input type="checkbox" class="bt_export_feed" name="bt_cron-export[]" value="{$aFeed.lang|lower|escape:'htmlall':'UTF-8'}_{$aFeed.country|escape:'htmlall':'UTF-8'}_{$aFeed.currencyIso|escape:'htmlall':'UTF-8'}" {if !empty($aFeed.checked)}checked="checked"{/if} /></td>
										<td class="center">{$aFeed.countryName|escape:'htmlall':'UTF-8'} - {$aFeed.country|escape:'htmlall':'UTF-8'}</td>
										<td class="center">{$aFeed.langName|escape:'htmlall':'UTF-8'}</td>
										<td class="center">{$aFeed.currencySign|escape:'htmlall':'UTF-8'} - {$aFeed.currencyIso|escape:'htmlall':'UTF-8'}</td>
										<td class="center">{$aFeed.filemtime|escape:'htmlall':'UTF-8'}</td>
										<td  class="center">
											<a class="label-tooltip btn btn-sm btn-default" title="{l s='Generate' mod='gmerchantcenter'}" href="javascript:void(0);" class="regenXML" onclick="if (oGmc.bGenerateXmlFlag){literal}{{/literal}alert('{l s='One data feed is currently in progress...' mod='gmerchantcenter'}'); return false;{literal}}{/literal}aDataFeedGenOptions.sLangIso='{$aFeed.lang|lower|escape:'htmlall':'UTF-8'}';aDataFeedGenOptions.sCountryIso='{$aFeed.country|lower|escape:'htmlall':'UTF-8'}';aDataFeedGenOptions.sCurrencyIso='{$aFeed.currencyIso|escape:'htmlall':'UTF-8'}';aDataFeedGenOptions.iLangId='{$aFeed.langId|intval}';aDataFeedGenOptions.sFilename='{$aFeed.filename|escape:'htmlall':'UTF-8'}';aDataFeedGenOptions.sFeedType='product';$('#syncCounterDiv').show();oGmc.generateDataFeed(aDataFeedGenOptions);"><span class="icon-refresh"></span></a>&nbsp;<div id="total_product_processed_{$aFeed.lang|lower|escape:'htmlall':'UTF-8'}_{$aFeed.country|lower|escape:'htmlall':'UTF-8'}" style="font-style: bold; display: none; margin-left:20px; vertical-align:text-top;"></div>
											<a class="label-tooltip btn btn-default btn-md" title="{l s='See' mod='gmerchantcenter'}" target="_blank" href="{$aFeed.link|escape:'htmlall':'UTF-8'}"><i class="fa fa-eye"></i></a>
											<a type="button" href="{$aFeed.link|escape:'htmlall':'UTF-8'}" download class="label-tooltip btn btn-md btn-default" title="{l s='Download' mod='gmerchantcenter'}">&nbsp;<i class="fa fa-download"></i></a>
											<a type="button" class="label-tooltip btn btn-md btn-default btn-copy js-tooltip js-copy" title="{l s='Copy' mod='gmerchantcenter'}" data-toggle="tooltip" data-placement="bottom" data-copy="{$aFeed.link|escape:'htmlall':'UTF-8'}">&nbsp;<i class="fa fa-copy"></i></a>
										</td>
									</tr>
								{/foreach}
							</table>

							<a style="display: none;" id="handleGenerateReportingBox" class="fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.reportingBox.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.reportingBox.type|escape:'htmlall':'UTF-8'}"></a>

							<div class="center">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
									</div>
									<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
										<button  class="btn btn-default pull-right" onclick="oGmc.form('bt_feedlist-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_feed-list-settings', 'bt_feed-list-settings', false, false, null, 'FeedList', 'loadingFeedListDiv');return false;"><i class="process-icon-save"></i>{l s='Save' mod='gmerchantcenter'}</button>
									</div>
								</div>
							</div>
						</form>
					</div>

					<div class="tab-pane" id="cron">
						<div class="clr_10"></div>

						<div class="alert alert-info form-group">
							<p><strong class="highlight_element">{l s='Please follow our FAQ to know ' mod='gmerchantcenter'}<a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=30&lg={$sFaqLang|escape:'htmlall':'UTF-8'}#bt_cron">{l s='how to create a CRON task' mod='gmerchantcenter'}</a></strong></p>
							<p><b>{l s='Be careful :' mod='gmerchantcenter'}</b>&nbsp;{l s='schedule your CRON task so that the XML files are up to date when Google will retreive them to update your data in Google Shopping.' mod='gmerchantcenter'}</p>

							<div class="clr_5"></div>

						</div>

						<div class="clr_15"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-md-11 col-lg-2">
								<span class="label-tooltip" title="{l s='Use this URL to update several feed files in the same time (TICK THE CONCERNED FILES and SAVE your selection BEFORE). If you note that all your files aren\'t correctly generated, set up a CRON task for each feed in order to update them one by one (use the independent URL\'s below), in a time-shifted manner (to avoid a servor time-out).' mod='gmerchantcenter'}"><b>{l s='My general CRON URL' mod='gmerchantcenter'}</b></span> :
							</label>

							{if !empty($aCronLang)}
								<div class="col-xs-12 col-md-5 col-lg-5">
									<input type="text" value="{$sCronUrl|escape:'htmlall':'UTF-8'}">
								</div>
								<a class="badge badge-info" href="{$sCronUrl|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='Execute the CRON in browser' mod='gmerchantcenter'}</a>
							{else}
								<div class="col-xs-12 col-md-5 col-lg-5">
									<div class="alert alert-warning">
										{l s='You cannot use this CRON URL because you didn\'t select any XML file above. Please tick all the files that you want to be filled in,' mod='gmerchantcenter'}&nbsp;<b>{l s='SAVE YOUR SELECTION (button above to the right)' mod='gmerchantcenter'}</b>&nbsp;{l s='and then use the general URL that will appear to set up your CRON task.' mod='gmerchantcenter'}
									</div>
								</div>
							{/if}
						</div>

						<div class="clr_15"></div>

						{if !empty($aCronList)}
							<table border="0" cellpadding="2" cellspacing="2" class="table table-responsive">
								<tr class="bt_tr_header text-center">
									<th class="center">{l s='Language' mod='gmerchantcenter'}</th>
									<th class="center">{l s='Country' mod='gmerchantcenter'}</th>
									<th class="center">{l s='Currency' mod='gmerchantcenter'}</th>
									<th class="center">{l s='Action' mod='gmerchantcenter'}</th>
								</tr>
								{foreach from=$aCronList name=feed key=iKey item=aCronFeed}
									<tr>
										<td class="center">{$aCronFeed.langName|escape:'htmlall':'UTF-8'}</td>
										<td class="center">{$aCronFeed.countryName|escape:'htmlall':'UTF-8'} - {$aCronFeed.country|escape:'htmlall':'UTF-8'}</td>
										<td class="center">{$aCronFeed.currencySign} - {$aCronFeed.currencyIsoCron}</td>
										<td class="center">
											<a type="button" class="label-tooltip btn btn-md btn-default btn-copy js-tooltip js-copy" title="{l s='Copy' mod='gmerchantcenter'}" data-toggle="tooltip" data-placement="bottom" data-copy="{$aCronFeed.link|escape:'htmlall':'UTF-8'}">&nbsp;<i class="fa fa-copy"></i></a>
											<a class="label-tooltip btn btn-default btn-md" target="_blank" title="{l s='Execute' mod='gmerchantcenter'}" href="{$aCronFeed.link|escape:'htmlall':'UTF-8'}"><i class="fa fa-play-circle"></i></a>
										</td>
									</tr>
								{/foreach}
							</table>
						{/if}
					</div>
				</div>
			</div>

			{* USE CASE - NO AVAILABLE LANGUAGE : CURRENCY : COUNTRY *}
		{else}

			<div class="clr_15"></div>
			<div class="alert alert-warning">
				{l s='Either you just updated your configuration by deactivating the advanced file security feature (in which case, please reload the page), or, there are no file because of no valid languages / currencies / countries, according to the Google\'s requirements.' mod='gmerchantcenter'}
			</div>
		{/if}



		<div class="bt-fb-fly" style="display: none;">
			<h2 class="bt-md-title">{l s='Your PHP URL\'s for on-the-fly output (for catalogs of < 5000 products)' mod='gmerchantcenter'}</h2>
			<div class="clr_hr"></div>
			<div class="clr_20"></div>

			{* USE CASE - AVAILABLE FEED FILE LIST *}
			{if !empty($aFlyFileList)}

				<p class="alert alert-info form-group"><strong class="highlight_element">{l s='Please follow our FAQ to know' mod='gmerchantcenter'}&nbsp;<a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=30&lg={$sFaqLang|escape:'htmlall':'UTF-8'}#bt_fly">{l s='how to manage the on-the-fly output URL\'s' mod='gmerchantcenter'}</a></strong>
					<br/>
					<br/>
					{l s='You can use the \"on-the-fly output\" URL\'s if your catalog is relatively small (5000 products maximum), if not, choose the solution of setting up a CRON task. However, if you are on a dedicated server, this one may also be able to process larger catalogs if you increase its PHP time-out and memory usage limits.' mod='gmerchantcenter'}</p>
				<div class="clr_5"></div>

				<table border="0" cellpadding="2" cellspacing="2" class="table ">
					<tr class="bt_tr_header text-center">
						<th class="center">{l s='Country' mod='gmerchantcenter'}</th>
						<th class="center">{l s='Language ' mod='gmerchantcenter'}</th>
						<th class="center">{l s='Currency' mod='gmerchantcenter'}</th>
						<th class="center"></th>
					</tr>
					{foreach from=$aFlyFileList name=feed key=iKey item=aFlyFeed}
						<tr>
							<td class="center">{$aFlyFeed.countryName|escape:'htmlall':'UTF-8'} - {$aFlyFeed.countryIso|escape:'htmlall':'UTF-8'}</td>
							<td class="center">{$aFlyFeed.langName|escape:'htmlall':'UTF-8'} - {$aFlyFeed.iso_code|strtoupper|escape:'htmlall':'UTF-8'}</td>
							<td class="center">{$aFlyFeed.currencySign|escape:'htmlall':'UTF-8'} - {$aFlyFeed.currencyIso|escape:'htmlall':'UTF-8'}</td>
							<td class="center">
								<a class="label-tooltip btn btn-default btn-md" title="{l s='See' mod='gmerchantcenter'}" target="_blank" href="{$aFlyFeed.link|escape:'htmlall':'UTF-8'}"><i class="fa fa-eye"></i></a>
								<a type="button" class="label-tooltip btn btn-md btn-default btn-copy js-tooltip js-copy" title="{l s='Copy' mod='gmerchantcenter'}" data-toggle="tooltip" data-placement="bottom" data-copy="{$aFlyFeed.link|escape:'htmlall':'UTF-8'}">&nbsp;<i class="fa fa-copy"></i</a>
							</td>
						</tr>
					{/foreach}
				</table>

			{* USE CASE - NO AVAILABLE LANGUAGE : CURRENCY : COUNTRY *}
			{else}
				<div class="clr_10"></div>
				<div class="alert alert-warning">
					{l s='There are no file because of no valid languages / currencies / countries according to the Google\'s requirements.' mod='gmerchantcenter'}
					<b><a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=52&lg={$sFaqLang|escape:'htmlall':'UTF-8'}">{l s='See our FAQ about localization prerequisites.' mod='gmerchantcenter'}</a></b>
				</div>
			{/if}
			{* USE CASE - THE OUTPUT PHP FILE HASN'T BEEN COPIED *}
			
		</div>

		<div id="{$sModuleName|escape:'htmlall':'UTF-8'}FeedListError"></div>
		{* USE CASE - NO CATEGORY OR BRAND HAVE BEEN SELECTED *}
	{else}
		<div class="clr_15"></div>
		<div class="alert alert-warning">
			{l s='No category or brand have been selected : please go to "Feeds management -> Export method" tab, and tick at least one category (or brand). You also need to check if there is at least one product in the selected categories (or brands). Remember : the categories used here are the products DEFAULT categories.' mod='gmerchantcenter'}
		</div>
	{/if}
	{* USE CASE - NO GOOGLE LINK HAS BEEN FILLED OUT *}
{else}
	<div class="clr_15"></div>

	<div class="alert alert-warning">
		{l s='You must first update the module\'s configuration options before the files can be accessed.' mod='gmerchantcenter'}
	</div>
{/if}
</div>
{literal}
<script type="text/javascript">
	// fancy box
	$("a#handleGenerateReportingBox").fancybox({
		'hideOnContentClick' : false
	});

	oGmcFeedList.dynamicDisplay();


	//bootstrap components init
	{/literal}{if !empty($bAjaxMode)}{literal}
	$('.label-tooltip, .help-tooltip').tooltip();
	{/literal}{/if}{literal}
</script>
{/literal}