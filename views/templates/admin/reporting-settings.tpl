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
<div class="bootstrap">
	<form class="form-horizontal col-xs-12" action="{$sURI|escape:'htmlall':'UTF-8'}" method="post" id="bt_reporting-form" name="bt_reporting-form" {if $smarty.const._GMC_USE_JS == true}onsubmit="javascript: oGmc.form('bt_feed-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_reporting-settings', 'bt_reporting-settings', false, false, null, 'Reporting', 'loadingReportingDiv');return false;"{/if}>
		<input type="hidden" name="sAction" value="{$aQueryParams.reporting.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.reporting.type|escape:'htmlall':'UTF-8'}" />

		<h3><i class="icon icon-play"></i>&nbsp;{l s='Reporting' mod='gmerchantcenter'}</h3>
		
		{if !empty($bUpdate)}
			{include file="`$sConfirmInclude`"}
		{elseif !empty($aErrors)}
			{include file="`$sErrorInclude`"}
		{/if}


		<div class="alert alert-info" id="info_export">
		<p><strong class="highlight_element">
		{l s='Please read the following FAQ to learn how the tool works :' mod='gmerchantcenter'}</strong>
		<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=160&lg={$sFaqLang|escape:'htmlall':'UTF-8'}#prod_link" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='How does the diagnostic tool work ?' mod='gmerchantcenter'}</a></p>
		<br />
		<p>{l s='This tool allows you' mod='gmerchantcenter'}&nbsp;<strong>{l s='to display again the last' mod='gmerchantcenter'}</strong>&nbsp;{l s='feed quality diagnostic that has been generated.' mod='gmerchantcenter'}</p>
		<br />
		<p><strong class="highlight_element">{l s='Please read carefully the explanations of the options below, passing the mouse over their name.' mod='gmerchantcenter'}</strong></p>
		</div>

		<div class="clr_20"></div>

		<div class="form-group" id="optionplus">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
			<span class="label-tooltip" title="{l s='Select "Yes" to fill out the reporting file automatically every time the matching feed URL is called (manually, or by an automated task). Select "No" to fill it out only for manually generating (in "My feeds" tab -> "Your XML files" -> "Update"). However, If you\'ve an important bulk of products (many thousands), you should leave this option on "No" in order to improve speed and performance of data feeds generating.' mod='gmerchantcenter'}">
				
				<b>{l s='Activate reporting file automatic writing :' mod='gmerchantcenter'}</b></span>
			</label>
			<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_reporting" id="bt_reporting_on" value="1" {if !empty($bReporting)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('display_reporting', 'display_reporting', null, null, true, true);"/>
						<label for="bt_reporting_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_reporting" id="bt_reporting_off" value="0" {if empty($bReporting)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('display_reporting', 'display_reporting', null, null, true, false);" />
						<label for="bt_reporting_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				&nbsp;&nbsp;
				<span class="icon-question-sign label-tooltip" title="{l s='Select "Yes" to fill out the reporting file automatically every time the matching feed URL is called (manually, or by an automated task). Select "No" to fill it out only for manually generating (in "My feeds" tab -> "Your XML files" -> "Update"). However, If you\'ve an important bulk of products (many thousands), you should leave this option on "No" in order to improve speed and performance of data feeds generating.' mod='gmerchantcenter'}"></span>
			</div>
		</div>

		<div class="form-group" id="display_reporting" {if empty($bReporting)}style="display: none;"{/if}>
			{if !empty($aLangCurrencies)}
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<span class="label-tooltip" title="{l s='Select the combination \"language_country_currency\" that matches with the feed you want to display the reporting. WARNING : if you change your feed configuration, remember to manually generate again your feed (in "My feeds" tab -> "Your XML files" -> "Update") and to save again this current page, in order to be sure of having the very last reporting.' mod='gmerchantcenter'}"><b>{l s='Select your reporting file :' mod='gmerchantcenter'}</b></span>
				</label>
				<div class="col-xs-3">
					<select name="bt_select-reporting" id="select_reporting">
						<option value="">...</option>
						{foreach from=$aLangCurrencies item=sISO key=currency}
							<option value="{$sISO|escape:'htmlall':'UTF-8'}">{$sISO|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
					<a style="display: none;" id="handleReportingBox" class="fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.reportingBox.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.reportingBox.type|escape:'htmlall':'UTF-8'}"></a>
					</div>
					
					<span class="icon-question-sign label-tooltip" title="{l s='Select the combination \"language_country_currency\" that matches with the feed you want to display the reporting. WARNING : if you change your feed configuration, remember to manually generate again your feed (in "My feeds" tab -> "Your XML files" -> "Update") and to save again this current page, in order to be sure of having the very last reporting.' mod='gmerchantcenter'}"></span>
				
				
			{else}
				<label class="control-label col-lg-3">
				</label>
				
				<div class="col-xs-6">
					<div class="alert alert-warning">
					<p>{l s='There is currently no report available.' mod='gmerchantcenter'}
					<p>{l s='Please generate again manually your feed (in "My feeds" tab -> "Your XML files" -> "Update"), save again this current page and if there is always nothing, make sure that the "reporting" folder inside the "gmerchantcenter" module folder has correct writing permissions.' mod='gmerchantcenter'}</div>
				</div>
			{/if}
		</div>

		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_10"></div>

		<div class="center">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
					<div id="{$sModuleName|escape:'htmlall':'UTF-8'}ReportingError"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<button  class="btn btn-default pull-right" onclick="oGmc.form('bt_reporting-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_reporting-settings', 'bt_reporting-settings', false, false, null, 'Reporting', 'loadingReportingDiv');return false;"><i class="process-icon-save"></i>{l s='Save' mod='gmerchantcenter'}</button>
				</div>
			</div>
		</div>

	</form>
</div>
{literal}
<script type="text/javascript">
	$(document).ready(function() {
		// manage change value for reporting
		$("#bt_reporting").change(function() {
			if ($(this).val() == "1") {
				$("#display_reporting").show();
			}
			else {
				$("#display_reporting").hide();
			}
		});

		// fancy box
		$("a#handleReportingBox").fancybox({
			'hideOnContentClick' : false
		});

		// handle reporting files
		$("#select_reporting").bind('change', function (event) {
			$("#select_reporting option:selected").each(function () {
				if ($(this).val() != "") {
					$('a#handleReportingBox').attr('href', $('#handleReportingBox').attr('href') + '&lang=' + $(this).val());

					$('a#handleReportingBox').click();
				}
			});
		});

		//bootstrap components init
		{/literal}{if !empty($bAjaxMode)}{literal}
		$('.label-tooltip, .help-tooltip').tooltip();
		{/literal}{/if}{literal}
	});
</script>
{/literal}