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
	var oBasicCallBack = [{
		'name' : 'displayFeedList',
		'url' : '{/literal}{$sURI}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction={/literal}{$aQueryParams.feedList.action|escape:'htmlall':'UTF-8'}{literal}&sType={/literal}{$aQueryParams.feedList.type|escape:'htmlall':'UTF-8'}{literal}',
		'toShow' : 'bt_feed-list-settings',
		'toHide' : 'bt_feed-list-settings',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'displayFeed',
		'url' : '{/literal}{$sURI}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction={/literal}{$aQueryParams.feedDisplay.action|escape:'htmlall':'UTF-8'}{literal}&sType={/literal}{$aQueryParams.feedDisplay.type|escape:'htmlall':'UTF-8'}{literal}&sDisplay=export',
		'toShow' : 'bt_feed-settings-export',
		'toHide' : 'bt_feed-settings-export',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'displayFeed',
		'url' : '{/literal}{$sURI}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction={/literal}{$aQueryParams.feedDisplay.action|escape:'htmlall':'UTF-8'}{literal}&sType={/literal}{$aQueryParams.feedDisplay.type|escape:'htmlall':'UTF-8'}{literal}&sDisplay=exclusion',
		'toShow' : 'bt_feed-settings-exclusion',
		'toHide' : 'bt_feed-settings-exclusion',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'displayFeed',
		'url' : '{/literal}{$sURI}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction={/literal}{$aQueryParams.feedDisplay.action|escape:'htmlall':'UTF-8'}{literal}&sType={/literal}{$aQueryParams.feedDisplay.type|escape:'htmlall':'UTF-8'}{literal}&sDisplay=data',
		'toShow' : 'bt_feed-settings-data',
		'toHide' : 'bt_feed-settings-data',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'displayFeed',
		'url' : '{/literal}{$sURI}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction={/literal}{$aQueryParams.feedDisplay.action|escape:'htmlall':'UTF-8'}{literal}&sType={/literal}{$aQueryParams.feedDisplay.type|escape:'htmlall':'UTF-8'}{literal}&sDisplay=apparel',
		'toShow' : 'bt_feed-settings-apparel',
		'toHide' : 'bt_feed-settings-apparel',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	},
	{
		'name' : 'displayFeed',
		'url' : '{/literal}{$sURI}{literal}',
		'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction={/literal}{$aQueryParams.feedDisplay.action|escape:'htmlall':'UTF-8'}{literal}&sType={/literal}{$aQueryParams.feedDisplay.type|escape:'htmlall':'UTF-8'}{literal}&sDisplay=tax',
		'toShow' : 'bt_feed-settings-tax',
		'toHide' : 'bt_feed-settings-tax',
		'bFancybox' : false,
		'bFancyboxActivity' : false,
		'sLoadbar' : null,
		'sScrollTo' : null,
		'oCallBack' : {}
	}
	];
	{/literal}
</script>

<div class="bootstrap">
	<form class="form-horizontal col-xs-12" method="post" id="bt_basics-form" name="bt_basics-form" {if $smarty.const._GMC_USE_JS == true}onsubmit="javascript: oGmc.form('bt_basics-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_basics-settings', 'bt_basics-settings', false, false, oBasicCallBack, 'Basics', 'loadingBasicsDiv');return false;"{/if}>
		<input type="hidden" name="sAction" value="{$aQueryParams.basic.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.basic.type|escape:'htmlall':'UTF-8'}" />

		<h3><i class="icon-heart"></i>&nbsp;{l s='Basic settings'  mod='gmerchantcenter'}</h3>
		<div class="clr_10"></div>
		{if !empty($bUpdate)}
			{include file="`$sConfirmInclude`"}
		{elseif !empty($aErrors)}
			{include file="`$sErrorInclude`"}
		{/if}

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='Example: http://www.myshop.com - Even if your shop is located in a sub-directory (e.g. http://www.myshop.com/shop), you should still only enter the fully qualified domain name http://www.myshop.com - DO NOT include a trailing slash (/) at the end' mod='gmerchantcenter'}"><b>{l s='Your PrestaShop\'s URL :' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-12 col-md-4 col-lg-2">
				<input type="text" name="bt_link" value="{$sLink|escape:'htmlall':'UTF-8'}" />
			</div>
			<span class="icon-question-sign label-tooltip" title="{l s='Example: http://www.myshop.com - Even if your shop is located in a sub-directory (e.g. http://www.myshop.com/shop), you should still only enter the fully qualified domain name http://www.myshop.com - DO NOT include a trailing slash (/) at the end' mod='gmerchantcenter'}">&nbsp;</span>
			<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/204" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product links' mod='gmerchantcenter'}</a>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Select YES if you want your products to be identified only by their numerical ID' mod='gmerchantcenter'}"><b>{l s='Assign simple product ID ?' mod='gmerchantcenter'}</b></span> :</label>
			<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_simple_id" id="bt_simple_id_on" value="1" {if !empty($bSimpleId)}checked="checked"{/if} onclick="oGmc.changeSelect('bt_prefix_string', 'bt_prefix_string', null, null, true, false);$('#prefix-id').val('');" />
						<label for="bt_simple_id_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_simple_id" id="bt_simple_id_off" value="0" {if empty($bSimpleId)}checked="checked"{/if} onclick="oGmc.changeSelect('bt_prefix_string', 'bt_prefix_string', null, null, true, true);" />
						<label for="bt_simple_id_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Select YES if you want your products to be identified only by their numerical ID' mod='gmerchantcenter'}">&nbsp;<span class="icon-question-sign"></span></span>
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/267" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about simple product ID' mod='gmerchantcenter'}</a>
			</div>
		</div>

		<div class="form-group" id="bt_prefix_string">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='Enter a short prefix that represents your shop. For example, if your shop is called "Janes\'s Flowers", enter "jf". This prefix is mandatory and must be unique for each of your shops.' mod='gmerchantcenter'}"><b>{l s='Product ID prefix for your shop :' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-12 col-md-3 col-lg-2">
				<input type="text" id='prefix-id' name="bt_prefix-id" value="{$sPrefixId|escape:'htmlall':'UTF-8'}" />
			</div>
			<span class="icon-question-sign label-tooltip" title="{l s='Enter a short prefix that represents your shop. For example, if your shop is called "Janes\'s Flowers", enter "jf". This prefix is mandatory and must be unique for each of your shops.' mod='gmerchantcenter'}">&nbsp;</span>
			<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/204" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product ID' mod='gmerchantcenter'}</a>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='This determines how many products are processed per AJAX / CRON cycle. Default is 200. Only increase this value if you have a large shop and run into problems with server limits. Otherwise, leave it at its default 200 value. It should not be higher than 1000 in any case.' mod='gmerchantcenter'}"><b>{l s='Number of products per cycle :' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-12 col-md-3 col-lg-2">
				<input type="text" name="bt_ajax-cycle" value="{$iProductPerCycle|intval}" />
			</div>
			<span class="icon-question-sign label-tooltip" title="{l s='This determines how many products are processed per AJAX / CRON cycle. Default is 200. Only increase this value if you have a large shop and run into problems with server limits. Otherwise, leave it at its default 200 value. It should not be higher than 1000 in any case.' mod='gmerchantcenter'}">&nbsp;</span>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='Choose the largest image size available (such as thickbox). Google requires at least 250x250 and recommends at least 400x400 pixels.' mod='gmerchantcenter'}"><b>{l s='Image size for product photos :' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-12 col-md-3 col-lg-2">
				<select name="bt_image-size">';
					{foreach from=$aImageTypes item=aImgType}
						{if $aImgType.width >= '250' && $aImgType.height >= '250'}
							<option value="{$aImgType.name|escape:'htmlall':'UTF-8'}" {if $aImgType.name == $sImgSize}selected="selected"{/if}>{$aImgType.name|escape:'htmlall':'UTF-8'}</option>
						{/if}
					{/foreach}
				</select>
				{*<div class="alert-tag">Google: [g:image_link]</div>*}
			</div>
			<div>
				<span class="icon-question-sign label-tooltip" title="{l s='Choose the largest image size available (such as thickbox). Google requires at least 250x250 and recommends at least 400x400 pixels.' mod='gmerchantcenter'}">&nbsp;</span>
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/203" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about image size' mod='gmerchantcenter'}</a>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you want to export only the cover image select NO' mod='gmerchantcenter'}"><b>{l s='Export additional images ?' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-5 col-md-5 col-lg-6">
				<span class="switch prestashop-switch fixed-width-lg">
					<input type="radio" name="bt_add_images" id="bt_add_images_on" value="1" {if !empty($bAddImages)}checked="checked"{/if} />
					<label for="bt_add_images_on" class="radioCheck">
						{l s='Yes' mod='gmerchantcenter'}
					</label>
					<input type="radio" name="bt_add_images" id="bt_add_images_off" value="0" {if empty($bAddImages)}checked="checked"{/if} />
					<label for="bt_add_images_off" class="radioCheck">
						{l s='No' mod='gmerchantcenter'}
					</label>
					<a class="slide-button btn"></a>
				</span>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you want to export only the cover image select NO' mod='gmerchantcenter'}">&nbsp;<span class="icon-question-sign"></span>&nbsp;</span>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you want to force the identifier_exists tag select YES' mod='gmerchantcenter'}"><b>{l s='Force the identifier_exists tag ?' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-5 col-md-5 col-lg-6">
				<span class="switch prestashop-switch fixed-width-lg">
					<input type="radio" name="bt_identifier_exist" id="bt_identifier_exist_on" value="1" {if !empty($bIdentifierExist)}checked="checked"{/if} />
					<label for="bt_identifier_exist_on" class="radioCheck">
						{l s='Yes' mod='gmerchantcenter'}
					</label>
					<input type="radio" name="bt_identifier_exist" id="bt_identifier_exist_off" value="0" {if empty($bIdentifierExist)}checked="checked"{/if} />
					<label for="bt_identifier_exist_off" class="radioCheck">
						{l s='No' mod='gmerchantcenter'}
					</label>
					<a class="slide-button btn"></a>
				</span>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you want to force the identifier_exists tag select YES' mod='gmerchantcenter'}">&nbsp;<span class="icon-question-sign"></span>&nbsp;</span>
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=72&lg={$sFaqLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='How to manage the identifier_exists tag ?' mod='gmerchantcenter'}</a>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='Please select the category that is the starting point of your tree view (it\'s usually your root or home category)' mod='gmerchantcenter'}"><b>{l s='Please select your "Home" category :' mod='gmerchantcenter'}</b></span>
			</label>
			<div class="col-xs-12 col-md-3 col-lg-2">
				<select name="bt_home-cat-id">';
					{foreach from=$aHomeCat item=aCat}
						<option value="{$aCat.id_category|intval}" {if $aCat.id_category == $iHomeCatId}selected="selected"{/if}>{$aCat.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
			</div>
			<span class="icon-question-sign label-tooltip" title="{l s='Please select the category that is the starting point of your tree view (it\'s usually your root or home category)' mod='gmerchantcenter'}">&nbsp;</span>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='For example "Electronic" or "Clothing". In most cases, the product path will correctly be retreived. But, for security reasons, in case where the product parent category wouldn\'t be found, the module needs to have a replacement value to enter in place of it. This value will then allow you to easily find, in your Google AdWords account, the products concerned.' mod='gmerchantcenter'}"><b>{l s='What type of products are you selling ?' mod='gmerchantcenter'}</b></span></label>
			<div id="homecat" class="col-xs-12 col-md-3 col-lg-2" >
				{foreach from=$aLangs item=aLang}
					<div id="bt_home-cat-name_{$aLang.id_lang|intval}" class="translatable-field row lang-{$aLang.id_lang|intval}" {if $aLang.id_lang != $iCurrentLang}style="display:none"{/if}>
						<div class="col-xs-9 col-md-9 col-lg-10">
							<input type="text" id="bt_home-cat-name_{$aLang.id_lang|intval}" name="bt_home-cat-name_{$aLang.id_lang|intval}" {if !empty($aHomeCatLanguages)}{foreach from=$aHomeCatLanguages key=idLang item=sLangTitle}{if $idLang == $aLang.id_lang} value="{$sLangTitle|escape:'htmlall':'UTF-8'}"{/if}{/foreach}{/if} />
						</div>
						<div class="col-xs-12 col-md-3 col-lg-2">
							<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">{$aLang.iso_code|escape:'htmlall':'UTF-8'}&nbsp;<i class="icon-caret-down"></i></button>
							<ul class="dropdown-menu">
								{foreach from=$aLangs item=aLang}
									<li><a href="javascript:hideOtherLanguage({$aLang.id_lang|intval});" tabindex="-1">{$aLang.name|escape:'htmlall':'UTF-8'}</a></li>
								{/foreach}
							</ul>
						</div>
					</div>
				{/foreach}
			</div>
			<div>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="icon-question-sign label-tooltip" title="{l s='For example "Electronic" or "Clothing". In most cases, the product path will correctly be retreived. But, for security reasons, in case where the product parent category wouldn\'t be found, the module needs to have a replacement value to enter in place of it. This value will then allow you to easily find, in your Google AdWords account, the products concerned.' mod='gmerchantcenter'}">&nbsp;</span>
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/211" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product type' mod='gmerchantcenter'}</a>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If your shop uses multiple currencies, you have to select "Yes".' mod='gmerchantcenter'}"><b>{l s='Does your shop use multiple currencies ?' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-5 col-md-5 col-lg-6">
				<span class="switch prestashop-switch fixed-width-lg">
					<input type="radio" name="bt_add-currency" id="bt_add-currency_on" value="1" {if !empty($bAddCurrency)}checked="checked"{/if} />
					<label for="bt_add-currency_on" class="radioCheck">
						{l s='Yes' mod='gmerchantcenter'}
					</label>
					<input type="radio" name="bt_add-currency" id="bt_add-currency_off" value="0" {if empty($bAddCurrency)}checked="checked"{/if} />
					<label for="bt_add-currency_off" class="radioCheck">
						{l s='No' mod='gmerchantcenter'}
					</label>
					<a class="slide-button btn"></a>
				</span>
				<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If your shop uses multiple currencies, you have to select "Yes".' mod='gmerchantcenter'}">&nbsp;<span class="icon-question-sign"></span>&nbsp;</span>
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/34" target="_blank"><i class="icon icon-link">&nbsp;</i>{l s='FAQ about the robot.txt' mod='gmerchantcenter'}</a>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
			<span class="label-tooltip" title="{l s='In most cases, the product condition will correctly be retreived. But, for security reasons, in case where it wouldn\'t be found, the module needs to have a replacement value to enter in place of it. The products concerned will have this condition in your Google AdWords account.' mod='gmerchantcenter'}"><b>{l s='In general, what\'s your products condition ?' mod='gmerchantcenter'}</b></span></label>
				
			<div class="col-xs-12 col-md-3 col-lg-2">
				<select name="bt_product-condition">
					<option value="0" {if empty($sCondition)}selected="selected"{/if}>--</option>
					{foreach from=$aAvailableCondition item=aCondition key=sCondName}
						<option value="{$sCondName|escape:'htmlall':'UTF-8'}" {if $sCondition == $sCondName}selected="selected"{/if}>{$aCondition|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>

				{*<div class="alert-tag">Google: [g:condition]</div>*}
			</div>
			<div>
				<span class="icon-question-sign label-tooltip" title="{l s='In most cases, the product condition will correctly be retreived. But, for security reasons, in case where it wouldn\'t be found, the module needs to have a replacement value to enter in place of it. The products concerned will have this condition in your Google AdWords account.' mod='gmerchantcenter'}">&nbsp;</span>
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/195" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product condition' mod='gmerchantcenter'}</a>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='We advise you to add either the product category or the product brand in your product titles.'  mod='gmerchantcenter'}"><b>{l s='Advanced product name :' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-12 col-md-3 col-lg-3">
				<select name="bt_advanced-prod-name" id="bt_advanced-prod-name">
					<option value="0" {if $iAdvancedProductName == 0}selected="selected"{/if} >{l s='Just the normal product name' mod='gmerchantcenter'}</option>
					<option value="1" {if $iAdvancedProductName == 1}selected="selected"{/if} >{l s='Current category name + Product name' mod='gmerchantcenter'}</option>
					<option value="2" {if $iAdvancedProductName == 2}selected="selected"{/if} >{l s='Product name + Current category name' mod='gmerchantcenter'}</option>
					<option value="3" {if $iAdvancedProductName == 3}selected="selected"{/if} >{l s='Brand name + Product name' mod='gmerchantcenter'}</option>
					<option value="4" {if $iAdvancedProductName == 4}selected="selected"{/if} >{l s='Product name + Brand name' mod='gmerchantcenter'}</option>
				</select>
				<br/>
				<div class="alert alert-info">
					{l s='Google recommends you to include a characteristic such as category or brand in the title in order to differentiate the item from the others.' mod='gmerchantcenter'}
				</div>
				<div class="alert alert-warning" id="bt_info-title-category">
					{l s='Be careful : Google will require your product titles to be NO MORE than 150 characters long. So, make sure your titles include less than 150 characters and if they don\'t, change the drag and drop menu value above.' mod='gmerchantcenter'}
				</div>
				<div class="alert alert-warning" id="bt_info-title-brand">
					{l s='Be careful : Google will require your product titles to be NO MORE than 150 characters long. So, make sure your titles include less than 150 characters and if they don\'t, change the drag and drop menu value above.' mod='gmerchantcenter'}
				</div>
			</div>
			<span class="icon-question-sign label-tooltip" title="{l s='We advise you to add either the product category or the product brand in your product titles.' mod='gmerchantcenter'}">&nbsp;</span>
		</div>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='Be careful : Google will refuse your product feed if your product titles have too many UPPERCASE letters. So if it\'s the case, choose one of the two solutions suggested in the opposite drag and drop menu.'  mod='gmerchantcenter'}"><b>{l s='Do you have too many uppercases in titles ?' mod='gmerchantcenter'}</b></span></label>
			</label>
			<div class="col-xs-12 col-md-3 col-lg-3">
				<select name="bt_advanced-prod-title" id="bt_advanced-prod-title">
					<option value="0" {if $iAdvancedProductTitle == 0}selected="selected"{/if} >{l s='No' mod='gmerchantcenter'}</option>
					<option value="1" {if $iAdvancedProductTitle == 1}selected="selected"{/if} >{l s='Yes : Uppercase the first character of each title word' mod='gmerchantcenter'}</option>
					<option value="2" {if $iAdvancedProductTitle == 2}selected="selected"{/if} >{l s='Yes : Uppercase the title first character only' mod='gmerchantcenter'}</option>
				</select>
			</div>
			<span class="icon-question-sign label-tooltip" title="{l s='Be careful : Google will refuse your product feed if your product titles have too many UPPERCASE letters. So if it\'s the case, choose one of the two solutions suggested in the opposite drag and drop menu.' mod='gmerchantcenter'}">&nbsp;</span>
		</div>

		<div class="clr_10"></div>

		<h3>{l s='Advanced file security' mod='gmerchantcenter'}</h3>

		<div class="form-group">
			<label class="control-label col-xs-12 col-md-3 col-lg-3">
				<span class="label-tooltip" title="{l s='This is a security measure so that people from the outside cannot call your feed URL and view your data. For your convenience, we have already automatically generated this secure key during the module installation.' mod='gmerchantcenter'}"><b>{l s='Your secure token :' mod='gmerchantcenter'}</b></span></label>
			<div class="col-xs-12 col-md-3 col-lg-3">
				<input type="text" maxlength="32" name="bt_feed-token" id="bt_feed-token" value="{$sFeedToken|escape:'htmlall':'UTF-8'}" />
			</div>
			<span class="icon-question-sign label-tooltip" title="{l s='This is a security measure so that people from the outside cannot call your feed URL and view your data. For your convenience, we have already automatically generated this secure key during the module installation.' mod='gmerchantcenter'}">&nbsp;</span>
		</div>


		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_10"></div>

		<div class="center">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
					<div id="{$sModuleName|escape:'htmlall':'UTF-8'}BasicsError"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<button  class="btn btn-default pull-right" onclick="oGmc.form('bt_basics-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_basics-settings', 'bt_basics-settings', false, false, oBasicCallBack, 'Basics', 'loadingBasicsDiv', false, 1);return false;"><i class="process-icon-save"></i>{l s='Save' mod='gmerchantcenter'}</button>
				</div>
			</div>
		</div>

	</form>
</div>



{literal}
<script type="text/javascript">
	//bootstrap components init
	// manage change value for advance protection
	//$("#bt_protection-mode").change(function() {
	$("input [name='bt_protection-mode']").bind($.browser.msie ? 'click' : 'change', function (event){
		if ($(this).val() == "0") {
			$("#protection_off").show();
		}
		else {
			$("#protection_off").hide();
		}
	});

	{/literal}{if !empty($bSimpleId)}{literal}
	$('#bt_prefix_string').hide()
	$('#prefix-id').val('')
	{/literal}{/if}{literal}

	//manage information for info title
	if ($("#bt_advanced-prod-name").val() == "0") {
		$("#bt_info-title-category").hide();
		$("#bt_info-title-brand").hide();
	}
	if ($("#bt_advanced-prod-name").val() == "1"
		|| $("#bt_advanced-prod-name").val() == "2"
	) {
		$("#bt_info-title-category").show();
		$("#bt_info-title-brand").hide();
	}
	if ($("#bt_advanced-prod-name").val() == "3"
		|| $("#bt_advanced-prod-name").val() == "4"
	) {
		$("#bt_info-title-category").hide();
		$("#bt_info-title-brand").show();
	}
	$("#bt_advanced-prod-name").change(function() {
		if ($(this).val() == "0" ) {
			$("#bt_info-title-category").hide();
			$("#bt_info-title-brand").hide();
		}
		if ($(this).val() == "1"
			|| $(this).val() == "2"
		) {
			$("#bt_info-title-category").show();
			$("#bt_info-title-brand").hide();
		}
		if ($(this).val() == "3"
			|| $(this).val() == "4"
		) {
			$("#bt_info-title-category").hide();
			$("#bt_info-title-brand").show();
		}
	});
	{/literal}{if !empty($bAjaxMode)}{literal}
	$('.label-tooltip, .help-tooltip').tooltip();
	{/literal}{/if}{literal}
</script>
{/literal}