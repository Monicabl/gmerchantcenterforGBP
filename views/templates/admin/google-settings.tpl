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
<script type="text/javascript">
	{literal}
	var oGoogleSettingsCallBack = [{
		//'name' : 'updateDesc',
		//'url' : '{*/literal}{$sURI}{literal*}',
		//'params' : '{*/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal*}={*/literal}{$sController|escape:'htmlall':'UTF-8'}{literal*}&sAction=display&sType=moderation',
		//'toShow' : '',
		//'toHide' : '',
		//'bFancybox' : false,
		//'bFancyboxActivity' : false,
		//'sLoadbar' : null,
		//'sScrollTo' : null,
		//'oCallBack' : {}
	}];
	{/literal}
</script>

<div class="bootstrap">
	<form class="form-horizontal col-xs-12" method="post" id="bt_google-{$sDisplay|escape:'htmlall':'UTF-8'}-form" name="bt_google-{$sDisplay|escape:'htmlall':'UTF-8'}-form" {if $smarty.const._GMC_USE_JS == true}onsubmit="javascript: oGmc.form('bt_google-{$sDisplay|escape:'htmlall':'UTF-8'}-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_google-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_google-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, oGoogleSettingsCallBack, 'Google', 'loadingGoogleDiv');return false;"{/if}>
		<input type="hidden" name="sAction" value="{$aQueryParams.google.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.google.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sDisplay" id="sGsDisplay" value="{if !empty($sDisplay)}{$sDisplay|escape:'htmlall':'UTF-8'}{else}categories{/if}" />

		{* USE CASE - Google categories *}
		{if !empty($sDisplay) && $sDisplay == 'categories'}
			<h3><i class="icon-briefcase"></i>&nbsp;{l s='Google Categories' mod='gmerchantcenter'}</h3>
			<div class="clr_10"></div>
			
			{if !empty($bUpdate)}
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="alert alert-info" id="info_export">
			<p><strong class="highlight_element">
				{l s='Each merchant has his own category names. But Google cannot manage all the possible and unimaginable names. So to solve this problem, Google created official category names and each merchant have to match his own categories with these. As each Google Shopping country has its own categories tawonomy, you have to match your categories for each country where you want to display Shopping campaigns.' mod='gmerchantcenter'}</strong></p><br />
				<p>{l s='However, please note that not all product types require a Google product category. Please visit ' mod='gmerchantcenter'} <b><a href="https://support.google.com/merchants/answer/6324436?visit_id=1-636353627563137693-1549157338&rd=1&hl={$sCurrentIso|escape:'htmlall':'UTF-8'}" target="_blank">{l s='this page' mod='gmerchantcenter'}</a></b>
				{l s='for more information' mod='gmerchantcenter'}.</p><br />
				<p>
				<ol>
				<li>{l s='Firstly, click on the reload icon' mod='gmerchantcenter'}&nbsp;<span class="icon-refresh">&nbsp;</span>{l s='to do a real-time update of the official Google categories list.' mod='gmerchantcenter'}</li>
				<li>{l s='Then, click on the pencil icon' mod='gmerchantcenter'}&nbsp;<span class="icon-pencil"></span>&nbsp;{l s='to match your own PrestaShop categories to the Google official categories.' mod='gmerchantcenter'}</li>
				</ol>
			</div>

			<div class="clr_20"></div>

			<div id="bt_google-cat-list">
				{include file="`$sGoogleCatListInclude`"}
			</div>

			{*<div class="form-group">
				<div class="col-xs-5 col-md-4 col-lg-3">
					<div class="alert-tag">Google: [g:google_product_category]</div>
				</div>
			</div>*}

			<div class="clr_20"></div>
			<div id="loadingGoogleCatListDiv" style="display: none;">
				<div class="alert alert-info">
					<p style="text-align: center !important;"><img src="{$sBigLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
					<p style="text-align: center !important;">{l s='The update of the official Google categories matching is in progress...' mod='gmerchantcenter'}</p>
				</div>
			</div>
		{/if}
		{* END - Google categories *}

		{* USE CASE - Google analytics *}
		{if !empty($sDisplay) && $sDisplay == 'analytics'}
			<h3>{l s='Google Analytics integration' mod='gmerchantcenter'}</h3>

			<div class="clr_10"></div>

			<div class="alert alert-info" id="info_export">
			<p><strong class="highlight_element">
				{l s='This section allow you to add some parameters in your product links (utm_campaign, utm_source and utm_medium) so that you can better track clicks and sales from your Google Adwords product ads in your Google Analytics account.' mod='gmerchantcenter'}</strong></p><br />
				<p>{l s='If a parameter is left empty below, it will not be added. Please add alphanumerical characters ONLY, without spaces. You can use \"-\" or \"_\" signs however. For more information, please visit ' mod='gmerchantcenter'}
				<b><a href="https://support.google.com/analytics/answer/1033863?hl={$sCurrentIso|escape:'htmlall':'UTF-8'}" target="_blank">{l s='this Google Analytics help page' mod='gmerchantcenter'}</a></b>.</p><br />
				<p>{l s='Note : if you want to use this feature, please make sure that the utm_campaign, utm_source and utm_medium parameters are not disallowed in your robots.txt file.' mod='gmerchantcenter'}</p>
			</div>

			<div class="clr_20"></div>

			<div class="form-group ">
				<label class="control-label col-xs-12 col-lg-3">
					<span><b>{l s='Value of utm_campaign parameter' mod='gmerchantcenter'}</b></span> :
				</label>
				<div class="col-xs-12 col-lg-3">
					<input type="text" size="30" name="bt_utm-campaign" value="{$sUtmCampaign|escape:'htmlall':'UTF-8'}" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-lg-3">
					<span><b>{l s='Value of utm_source parameter' mod='gmerchantcenter'}</b></span> :
				</label>
				<div class="col-xs-12 col-lg-3">
					<input type="text" size="30" name="bt_utm-source" value="{$sUtmSource|escape:'htmlall':'UTF-8'}" />
				</div>
			</div>
			<div class="form-group ">
				<label class="control-label col-xs-12 col-lg-3">
					<span><b>{l s='Value of utm_medium parameter' mod='gmerchantcenter'}</b></span> :
				</label>
				<div class="col-xs-12 col-lg-3">
					<input type="text" size="30" name="bt_utm-medium" value="{$sUtmMedium|escape:'htmlall':'UTF-8'}" />
				</div>
			</div>
			<p class="alert alert-info">{l s='You can also add a "utm_content" parameter in your product links in order to know if the traffic comes from free or paid campaigns. For more info please visit our' mod='gmerchantcenter'}
				&nbsp;&nbsp;<a class="badge badge-info pulse pulse2" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/389" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about the utm_content tag' mod='gmerchantcenter'}</a>
			</p>
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='Select "YES" to add a utm_content parameter in product links' mod='gmerchantcenter'}"><b>{l s='Add a utm_content parameter?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_utm_content" id="bt_utm_content_on" value="1" {if !empty($bUtmContent)}checked="checked"{/if} />
							<label for="bt_utm_content_on" class="radioCheck">
								{l s='YES' mod='gmerchantcenter'}
							</label>
							<input type="radio" name="bt_utm_content" id="bt_utm_content_off" value="0" {if empty($bUtmContent)}checked="checked"{/if} />
							<label for="bt_utm_content_off" class="radioCheck">
								{l s='NO' mod='gmerchantcenter'}
							</label>
							<a class="slide-button btn"></a>
						</span>
					<span class="label-tooltip" data-toggle="tooltip" data-placement="right" data-original-title="{l s='Select "YES" to add a utm_content parameter in product links' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
				</div>
			</div>
		{/if}
		{* END - Google analytics *}

		{* USE CASE - Google custom label *}
		{if !empty($sDisplay) && $sDisplay == 'adwords'}
			<h3>{l s='Google Adwords / Custom label integration' mod='gmerchantcenter'}</h3>
			{*<div class="alert-tag">
				[custom_label]
			</div>*}

			<div class="clr_10"></div>

			<div class="alert alert-info" id="info_export">
			<p><strong class="highlight_element">
				{l s='This section allows you to assign custom labels to your products in order to subdivide your products and have a better Google AdWords campaigns management. For more information, please visit' mod='gmerchantcenter'} <a href="https://support.google.com/adwords/answer/6275295?hl={$sCurrentIso|escape:'htmlall':'UTF-8'}" target="_blank">{l s='the Google official documentation about custom labels' mod='gmerchantcenter'}</a>.</strong></p>
				<p>{l s='You can visit also our ' mod='gmerchantcenter'}<b><a href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=84&lg={$sFaqLang|escape:'htmlall':'UTF-8'}" target="_blank">{l s='FAQ about custom labels creation' mod='gmerchantcenter'}</a></b></p>
				<div class="clr_10"></div>
				
				<p>{l s='Note : Google does not allow more than 5 labels per product. So, if one of your products has more than 5 custom labels, our module will select only the first 5 ones (in order of appearance below). You can change the sort order of the custom labels via drag and drop.' mod='gmerchantcenter'}</p>
			</div>

			<div class="clr_20"></div>

			<div class="add_adwords">
				<a id="handleGoogleAdwords" class="fancybox.ajax btn btn-lg btn-success" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8':'UTF-8'}&sAction={$aQueryParams.custom.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.custom.type|escape:'htmlall':'UTF-8':'UTF-8'}"><span class="icon-plus-circle"></span>&nbsp;{l s='Add a custom label' mod='gmerchantcenter'}</a>
			</div>

			{if !empty($aTags)}
			<div class="clr_15"></div>
			<div class="col-xs-12 col-lg-4">
				<table id="tags" class="table table-striped">
					<tr>
						<th style="text-align: center;">{l s='Custom labels set name' mod='gmerchantcenter'}</th>
						<th style="text-align: center;">{l s='Edit' mod='gmerchantcenter'}</th>
						<th style="text-align: center;">{l s='Delete' mod='gmerchantcenter'}</th>
						<th style="text-align: center;"><a id="handleGoogleAdwords" class="fancybox.ajax btn btn-mini btn-success" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8':'UTF-8'}&sAction={$aQueryParams.custom.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.custom.type|escape:'htmlall':'UTF-8'}"><span class="icon-plus-circle"></span></a></th>
					</tr>
					{foreach from=$aTags name=label key=iKey item=aTag}
					<tr>
						<td style="text-align: center">{$aTag.name|escape:'htmlall':'UTF-8'}</td>
						<td style="text-align: center"><a id="handleGoogleAdwordsEdit" class="fancybox.ajax btn btn-mini btn-default" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.custom.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.custom.type|escape:'htmlall':'UTF-8'}&iTagId={$aTag.id_tag|intval}&sDisplay=adwords"><span class="icon-pencil"></span></a></td>
						<td style="text-align: center"><a href="#"><i class="icon-trash btn btn-mini btn-danger" title="{l s='delete' mod='gmerchantcenter'}" onclick="check = confirm('{l s='Are you sure to want to delete this custom label' mod='gmerchantcenter'} ? {l s='It will be definitely removed from your database' mod='gmerchantcenter'}');if(!check)return false;$('#loadingGoogleDiv').show();oGmc.hide('bt_google-settings');oGmc.ajax('{$sURI|escape:'htmlall':'UTF-8'}', '{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.customDelete.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.customDelete.type|escape:'htmlall':'UTF-8'}&iTagId={$aTag.id_tag|intval}&sDisplay=adwords', 'bt_google-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_google-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', null, null, 'loadingGoogleDiv');" ></i></a></td>
						<td style="text-align: center"></td>
					</tr>
					{/foreach}
				</table>
			</div>
			{/if}
		{/if}
		{* END - Google custom label *}

		{if !empty($sDisplay) && $sDisplay == 'analytics'}
			<div class="clr_10"></div>
			<div class="clr_hr"></div>
			<div class="clr_10"></div>

			<div class="center">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
						<div id="{$sModuleName|escape:'htmlall':'UTF-8'}GoogleError"></div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
						<button  class="btn btn-default pull-right" onclick="oGmc.form('bt_google-{$sDisplay|escape:'htmlall':'UTF-8'}-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_google-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_google-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, oGoogleSettingsCallBack, 'Google', 'loadingGoogleDiv');return false;"><i class="process-icon-save"></i>{l s='Save' mod='gmerchantcenter'}</button>
					</div>
				</div>
			</div>
		{/if}

	</form>
</div>
{literal}
<script type="text/javascript">
	//bootstrap components init
	{/literal}{if !empty($bAjaxMode)}{literal}
	$('.label-tooltip, .help-tooltip').tooltip();
	oGmc.runMainGoogle();
	{/literal}{/if}{literal}
</script>
{/literal}