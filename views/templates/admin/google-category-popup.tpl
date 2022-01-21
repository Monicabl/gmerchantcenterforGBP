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
{if !empty($aErrors)}
{include file="`$sErrorInclude`"}
{* USE CASE - edition review mode *}
{else}
<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	<div id="bt_google-category" class="col-xs-12">
		<h3>{l s='Google product categories for the feed ' mod='gmerchantcenter'}: {$sLangIso|escape:'htmlall':'UTF-8'}</h3>

		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="alert alert-success">
			{l s='INSTRUCTIONS : for each of your shop categories, start to type keywords that represent the category, using as many words as you wish (simply separate each word by a space). A list of Google categories that could match with will appear, containing all the words you entered. Simply select the best match from the list.' mod='gmerchantcenter'}
		</div>

		{if $iMaxPostVars != false && $iShopCatCount > $iMaxPostVars}
		<div class="alert alert-warning">
			{l s='IMPORTANT NOTE : be careful, apparently the number of variables that can be posted via the form is limited by your server, and your total number of categories is higher than this variables maximum number allowed' mod='gmerchantcenter'} :<br/>
			<strong>{$iShopCatCount|intval}</strong>&nbsp;{l s='categories' mod='gmerchantcenter'}</strong>&nbsp;{l s='on' mod='gmerchantcenter'}&nbsp;<strong>{$iMaxPostVars|intval}</strong>&nbsp;{l s='possible variables ... (PHP directive => max_input_vars)' mod='gmerchantcenter'}<br/><br/>
			<strong>{l s='It\'s possible that all your categories AREN\'T properly registered, PLEASE VISIT OUR' mod='gmerchantcenter'}</strong>: <a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?lg={$sCurrentIso|escape:'htmlall':'UTF-8'}&id=59">{l s='FAQ : \"why my selection of categories not properly saved\"' mod='gmerchantcenter'}</a>
		</div>
		{/if}

		<div class="clr_20"></div>

		<form class="form-horizontal" method="post" id="bt_form-google-cat" name="bt_form-google-cat" {if $smarty.const._GSR_USE_JS == true}onsubmit="oGmc.form('bt_form-google-cat', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_google-category', 'bt_google-category', false, true, null, 'GoogleCat', 'loadingGoogleCatDiv');return false;"{/if}>
			<input type="hidden" name="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" value="{$sController|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sAction" value="{$aQueryParams.googleCatUpdate.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.googleCatUpdate.type|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sLangIso" value="{$sLangIso|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="iLangId" value="{$iLangId|intval}" />

			{foreach from=$aShopCategories name=category item=aCategory}
			<table class="table table-bordered table-responsive">
				<tr>
					<td class="label_tag_categories">{l s='Your shop category' mod='gmerchantcenter'} : {$aCategory.path}</td>
				</tr>
				<tr>
					<td>
						{l s='Google category' mod='gmerchantcenter'}&nbsp;:&nbsp;<input class="autocmp" style="font-size: 11px; width: 800px;" type="text" name="bt_google-cat[{$aCategory.id_category}]" id="bt_google-cat{$aCategory.id_category|intval}" value="{$aCategory.google_category_name}" />
						<p class="duplicate_category">
						{if $smarty.foreach.category.first}
							<br /><a href="#" onclick="return oGmc.duplicateFirstValue('input.autocmp', $('#bt_google-cat{$aCategory.id_category|intval}').val());">{l s='Duplicate this value for all the categories below' mod='gmerchantcenter'}</a>
						{/if}
						</p>
					</td>
				</tr>
			</table>
			{/foreach}

			<div class="clr_20"></div>

			<p style="text-align: center !important;">
				{if $smarty.const._GMC_USE_JS == true}
					<script type="text/javascript">
						{literal}
						var oGoogleCatCallback = [{}];
						{/literal}
					</script>
					<input type="button" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='gmerchantcenter'}" onclick="oGmc.form('bt_form-google-cat', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_google-category', 'bt_google-category', false, true, null, 'GoogleCat', 'loadingGoogleCatDiv');return false;" />
				{else}
					<input type="submit" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='gmerchantcenter'}" />
				{/if}
				<button class="btn btn-danger btn-lg" value="{l s='Cancel' mod='gmerchantcenter'}"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gmerchantcenter'}</button>
			</p>
		</form>
		{literal}
		<script type="text/javascript">
			$('input.autocmp').each(function(index, element) {
				var query = $(element).attr("id");
				$(element).autocomplete('{/literal}{$sURI}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.autocomplete.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.autocomplete.type|escape:'htmlall':'UTF-8'}&sLangIso={$sLangIso|escape:'htmlall':'UTF-8'}&query='+query{literal}, {
					minChars: 3,
					autoFill: false,
					max:50,
					matchContains: true,
					mustMatch:false,
					scroll:true,
					cacheLength:0,
					formatItem: function(item) {
						return item[0];
					}
				});
			});

			$("form").bind("keypress", function (e) {
				if (e.keyCode == 13) {
					return false;
				}
			});
		</script>
		{/literal}
	</div>
</div>
<div id="loadingGoogleCatDiv" style="display: none;">
	<div class="alert alert-info">
		<p style="text-align: center !important;"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
		<p style="text-align: center !important;">{l s='Your configuration updating is in progress...' mod='gmerchantcenter'}</p>
	</div>
</div>
{/if}