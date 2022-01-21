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
<div class="bootstrap" id="{$sModuleName|escape:'htmlall':'UTF-8'}" style="width: 900px;">
	<form class="col-xs-12 bt-step-popup"  method="post" id="bt-step-popup" name="bt-step-popup" {if $smarty.const._GMC_USE_JS == true}onsubmit="oGmc.form('bt-step-popup', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt-step-popup', 'bt-step-popup', false, false, '' , 'Advice', '');$.fancybox.close();return false;"{/if}>
		<input type="hidden" name="sAction" value="{$aQueryParams.stepPopupUpd.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.stepPopupUpd.type|escape:'htmlall':'UTF-8'}" />
		<h3 class="text-center">{l s='Import data feed on Google shopping interface' mod='gmerchantcenter'}</h3>
		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_10"></div>

		<div class="row">
			<div class="col-xs-12 text-center">
				<a class="btn btn-info btn-md" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sCurrentLang|escape:'htmlall':'UTF-8'}/faq/94" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='How to import the data feed on Google Merchant Center' mod='gmerchantcenter'}</a>
			</div>
		</div>
		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_10"></div>

		<div class="col-xs-12 text-center">
			<a type="button" name="bt_advice-button" id="bt_advice-button" class="btn btn-success btn-lg pull-left" class="center button" onclick="oGmc.form('bt-step-popup', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt-step-popup', 'bt-step-popup', false, false, '', 'Advice', '', false, 3);$.fancybox.close();return false;" ><i class="fa fa-check">&nbsp;</i>{l s='The data feed is added' mod='gmerchantcenter'}</a>
			<a type="button" name="no_import" id="" class="btn btn-danger btn-lg pull-right" value="{l s='I didn\'t finish this step' mod='gmerchantcenter'}" class="center button" onclick="$.fancybox.close();return false;" ><i class="fa fa-warning">&nbsp;</i>{l s='I didn\'t finish this step' mod='gmerchantcenter'}</a>
		</div>
	</form>
</div>
