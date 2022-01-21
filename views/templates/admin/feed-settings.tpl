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
{if !empty($sDisplay) && $sDisplay == 'export'}
	<script type="text/javascript">
		{literal}
		var oFeedSettingsCallBack = [{
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
		}];
		{/literal}
	</script>
{/if}

<div class="bootstrap">
	<form class="form-horizontal col-xs-12" method="post" id="bt_feed-{$sDisplay|escape:'htmlall':'UTF-8'}-form" name="bt_feed-{$sDisplay|escape:'htmlall':'UTF-8'}-form" {if $smarty.const._GMC_USE_JS == true}onsubmit="javascript: oGmc.form('bt_feed-{$sDisplay|escape:'htmlall':'UTF-8'}-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_feed-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_feed-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, {if empty($sDisplay) || (!empty($sDisplay) && $sDisplay == 'export')}oFeedSettingsCallBack{else}null{/if}, 'Feed{$sDisplay|escape:'htmlall':'UTF-8'}', 'loadingFeedDiv');return false;"{/if}>
		<input type="hidden" name="sAction" value="{$aQueryParams.feed.action|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sType" value="{$aQueryParams.feed.type|escape:'htmlall':'UTF-8'}" />
		<input type="hidden" name="sDisplay" id="sDisplay" value="{if !empty($sDisplay)}{$sDisplay|escape:'htmlall':'UTF-8'}{else}export{/if}" />
		
		{* USE CASE - Export *}
		{if !empty($sDisplay) && $sDisplay == 'export'}
			<h3><i class="icon-cog"></i>&nbsp;{l s='Export method' mod='gmerchantcenter'}</h3>
			<div class="clr_10"></div>
			{if !empty($bUpdate)}
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				{include file="`$sErrorInclude`"}
			{/if}

			<div {if !empty($bExportMode)}style="display: none;"{/if}>
				{if $iMaxPostVars != false && $iShopCatCount > $iMaxPostVars}
					<div class="alert alert-warning">
						{l s='IMPORTANT NOTE : be careful, apparently the number of variables that can be posted via the form is limited by your server, and your total number of categories is higher than this variables maximum number allowed' mod='gmerchantcenter'} :<br/>
						<strong>{$iShopCatCount|intval}</strong>&nbsp;{l s='categories' mod='gmerchantcenter'}</strong>&nbsp;{l s='on' mod='gmerchantcenter'}&nbsp;<strong>{$iMaxPostVars|intval}</strong>&nbsp;{l s='possible variables ... (PHP directive => max_input_vars)' mod='gmerchantcenter'}<br/><br/>
						<strong>{l s='IT IS POSSIBLE THAT YOU CANNOT REGISTER PROPERLY ALL YOUR CATEGORIES, PLEASE VISIT OUR ' mod='gmerchantcenter'}</strong>: <a target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?lg={$sCurrentIso|escape:'htmlall':'UTF-8'}&id=59">{l s='FAQ "why was my selection of categories not properly saved ?' mod='gmerchantcenter'}</a>
					</div>
				{/if}
			</div>

			<div class="form-group" id="optionplus">
				<label class="control-label col-xs-12 col-md-2 col-lg-2">
					<span class="label-tooltip" title="{l s='You can choose to export your products by categories or by brands.' mod='gmerchantcenter'}"><b>{l s='Select your export method' mod='gmerchantcenter'}</b></span> :
				</label>
				<div class="col-xs-12 col-md-3 col-lg-2">
					<select name="bt_export" id="bt_export">
						<option value="0" {if empty($bExportMode)}selected="selected"{/if}>{l s='Export by categories' mod='gmerchantcenter'}</option>
						<option value="1" {if !empty($bExportMode)}selected="selected"{/if}>{l s='Export by brands' mod='gmerchantcenter'}</option>
					</select>
				</div>
				<span class="icon-question-sign label-tooltip" title="{l s='You can choose to export your products by categories or by brands.' mod='gmerchantcenter'}"></span>
			</div>
			{* categories tree *}
			<div id="bt_categories" {if !empty($bExportMode)}style="display: none;"{/if}>
				<div class="form-group">
					<label class="control-label col-xs-12 col-md-2 col-lg-2">
						<span class="label-tooltip" title="{l s='Select the categories you want to export. You will be able to exclude some products from these selected categories in the next tab (\"Product exclusion rules\")' mod='gmerchantcenter'}"><b>{l s='Categories' mod='gmerchantcenter'}</b></span> :
					</label>
					<div class="col-xs-12 col-md-5 col-lg-4">
						<div class="btn-actions">
							<div class="btn btn-default btn-mini" id="categoryCheck" onclick="return oGmc.selectAll('input.categoryBox', 'check');"><span class="icon-plus-square"></span>&nbsp;{l s='Check All' mod='gmerchantcenter'}</div> - <div class="btn btn-default btn-mini" id="categoryUnCheck" onclick="return oGmc.selectAll('input.categoryBox', 'uncheck');"><span class="icon-minus-square"></span>&nbsp;{l s='Uncheck All' mod='gmerchantcenter'}</div>
							<div class="clr_10"></div>
						</div>
						<table cellspacing="0" cellpadding="0" class="table  table-bordered table-striped table-responsive" >
						{foreach from=$aFormatCat name=category key=iKey item=aCat}
							<tr class="alt_row">
								<td>
									{$aCat.id_category|intval}
								</td>
								<td>
									<input type="checkbox" name="bt_category-box[]" class="categoryBox" id="bt_category-box_{$aCat.iNewLevel|intval}" value="{$aCat.id_category|intval}" {if !empty($aCat.bCurrent)}checked="checked"{/if} />
								</td>
								<td>
									<span class="icon icon-folder{if !empty($aCat.bCurrent)}-open{/if}" style="margin-left: {$aCat.iNewLevel|intval}5px;"></span>&nbsp;&nbsp;<span style="font-size:12px;">{$aCat.name|escape:'htmlall':'UTF-8'}</span>
								</td>
							</tr>
						{/foreach}
						</table>
						<div class="clr_10"></div>
					</div>
				</div>
			</div>

			{* brands tree *}
			<div id="bt_brands" {if empty($bExportMode)}style="display: none;"{/if}>
				<div class="form-group">
					<label class="control-label col-xs-12 col-md-2 col-lg-2">
						<span class="label-tooltip" title="{l s='Select the brands you want to export. You will be able to exclude some products from these selected brands in the next tab (\"Product exclusion rules\")' mod='gmerchantcenter'}"><b>{l s='Brands' mod='gmerchantcenter'}</b></span> :
					</label>
					<div class="col-xs-12 col-md-5 col-lg-4">
						<div class="btn-actions">
							<div class="btn btn-default btn-mini" id="brandCheck" onclick="return oGmc.selectAll('input.brandBox', 'check');"><i class="icon-plus-square"></i>&nbsp;{l s='Check All' mod='gmerchantcenter'}</div> - <div class="btn btn-default btn-mini" id="brandUnCheck" onclick="return oGmc.selectAll('input.brandBox', 'uncheck');"><i class="icon-minus-square"></i>&nbsp;{l s='Uncheck All' mod='gmerchantcenter'}</div>
							<div class="clr_10"></div>
						</div>
						<table cellspacing="0" cellpadding="0" class="table  table-bordered table-striped" style="width: 100%;">
							{foreach from=$aFormatBrands name=brand key=iKey item=aBrand}
								<tr class="alt_row">
									<td>
										{$aBrand.id|intval}
									</td>
									<td>
										<input type="checkbox" name="bt_brand-box[]" class="brandBox" id="bt_brand-box_{$aBrand.id|intval}" value="{$aBrand.id|intval}" {if !empty($aBrand.checked)}checked="checked"{/if} />
									</td>
									<td>
										<i class="icon icon-folder{if !empty($aBrand.checked)}-open{/if}">&nbsp;&nbsp;<span style="font-size:12px;"></i><span>{$aBrand.name|escape:'htmlall':'UTF-8'}</span>
									</td>
								</tr>
							{/foreach}
						</table>
						<div class="clr_10"></div>
					</div>
				</div>
			</div>
		{/if}
		{* END - Export *}

		{* USE CASE - Exclusion *}
		{if !empty($sDisplay) && $sDisplay == 'exclusion'}
			<h3>{l s='Product exclusion rules' mod='gmerchantcenter'}</h3>
			<div class="clr_10"></div>
			{if !empty($bUpdate)}
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select YES : all products, even those that are out of stock, will be exported. If you select NO : only the products that are in stock will be exported.' mod='gmerchantcenter'}"><b>{l s=' Do you want to export out of stock products ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_export-oos" id="bt_export-oos_on" value="1" {if !empty($bExportOOS)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('bt_div_product_oos', 'bt_div_product_oos', null, null, true, true);" />
						<label for="bt_export-oos_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_export-oos" id="bt_export-oos_off" value="0" {if empty($bExportOOS)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('bt_div_product_oos', 'bt_div_product_oos', null, null, true, false);" />
						<label for="bt_export-oos_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select YES : all products, even those that are out of stock, will be exported. If you select NO : only the products that are in stock will be exported.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/213" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product availability' mod='gmerchantcenter'}</a>
				</div>
			</div>

			<div class="form-group" id="bt_div_product_oos" style="display: {if !empty($bExportOOS)} block{else} none{/if}">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select YES : the products that are out of stock and authorized for orders will be exported. If you select NO : all out of stock products, even those that are denied for orders, will be exported.' mod='gmerchantcenter'}">
						<b>{l s='Do not export when you deny orders for out-of-stock products?' mod='gmerchantcenter'}</b>
					</span>
				</label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_product-oos-order" id="bt_product-oos-order_on" value="1" {if !empty($bProductOosOrder)}checked="checked"{/if} />
						<label for="bt_product-oos-order_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_product-oos-order" id="bt_product-oos-order_off" value="0" {if empty($bProductOosOrder)}checked="checked"{/if} />
						<label for="bt_product-oos-order_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select YES : the products that are out of stock and authorized for orders will be exported. If you select NO : all out of stock products, even those that are denied for orders, will be exported.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq/237" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about out of stock product export' mod='gmerchantcenter'}</a>
				</div>
			</div>

			<div class="clr_5"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select YES : products without EAN13/JAN or UPC code will NOT be exported. This will get rid of the Google errors about missing EAN13/JAN or UPC codes until you are able to get all your product codes from suppliers. If you select NO : even products without EAN13/JAN or UPC code will be exported.' mod='gmerchantcenter'}"><b>{l s='Do you want to NOT export products without EAN13/JAN or UPC ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_excl-no-ean" id="bt_excl-no-ean_on" value="1" {if !empty($bExcludeNoEan)}checked="checked"{/if} />
						<label for="bt_excl-no-ean_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_excl-no-ean" id="bt_excl-no-ean_off" value="0" {if empty($bExcludeNoEan)}checked="checked"{/if} />
						<label for="bt_excl-no-ean_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select YES : products without EAN13/JAN or UPC code will NOT be exported. This will get rid of the Google errors about missing EAN13/JAN or UPC codes until you are able to get all your product codes from suppliers. If you select NO : even products without EAN13/JAN or UPC code will be exported.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/192" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about GTIN codes' mod='gmerchantcenter'}</a>
				</div>
			</div>

			<div class="clr_5"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select YES : products without MPN (manufacturer) code will NOT be exported. This will get rid of the Google errors about missing MPN codes until you are able to get all your product codes from suppliers. If you select NO : even products without MPN code will be exported.' mod='gmerchantcenter'}"><b>{l s='Do you want to NOT export products without a manufacturer (MPN) reference ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_excl-no-mref" id="bt_excl-no-mref_on" value="1" {if !empty($bExcludeNoMref)}checked="checked"{/if} />
						<label for="bt_excl-no-mref_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_excl-no-mref" id="bt_excl-no-mref_off" value="0" {if empty($bExcludeNoMref)}checked="checked"{/if} />
						<label for="bt_excl-no-mref_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you select YES : products without MPN (manufacturer) code will NOT be exported. This will get rid of the Google errors about missing MPN codes until you are able to get all your product codes from suppliers. If you select NO : even products without MPN code will be exported.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/198" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about MPN codes' mod='gmerchantcenter'}</a>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<span class="label-tooltip" title="{l s='Any product whose CURRENT price (taking specific prices into account) is lower than this value will be excluded from the feed. Cart rules are NOT taken into account, only specific prices. This allows you to exclude low margin products and not pay for clicks on them, making your Google ads campaigns more efficient and profitable.' mod='gmerchantcenter'}"><b>{l s='Do NOT export products with price lower than :' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-1 col-lg-1">
					<input type="text" size="5" name="bt_min-price" value="{if !empty($iMinPrice)}{$iMinPrice|floatval}{/if}" />
				</div>{l s='Tax excluded' mod='gmerchantcenter'}
				&nbsp;
				<span class="icon-question-sign label-tooltip" title="{l s='Any product whose CURRENT price (taking specific prices into account) is lower than this value will be excluded from the feed. Cart rules are NOT taken into account, only specific prices. This allows you to exclude low margin products and not pay for clicks on them, making your Google ads campaigns more efficient and profitable.' mod='gmerchantcenter'}"></span>&nbsp;
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=22&lg={$sFaqLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product exclusion' mod='gmerchantcenter'}</a>
			</div>

			<div class="clr_5"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<span class="label-tooltip" title="{l s='Start to type the name of a product you want to exclude and select it from the autocomplete list that will appear. All the products you want to exclude will display in a list below.' mod='gmerchantcenter'}"><b>{l s='What product(s) do you want to exclude ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-3 col-lg-2">
					<input type="text" size="5" id="bt_search-p" name="bt_search-p" value="" />
				</div>
				<span class="icon-question-sign label-tooltip" title="{l s='Start to type the name of a product you want to exclude and select it from the autocomplete list that will appear. All the products you want to exclude will display in a list below.' mod='gmerchantcenter'}"></span>&nbsp;&nbsp;
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=22&lg={$sFaqLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product exclusion' mod='gmerchantcenter'}</a>
			</div>

			<input type="hidden" value="{if !empty($sProductIds)}{$sProductIds|escape:'htmlall':'UTF-8'}{else}{/if}" id="hiddenProductIds" name="hiddenProductIds" />
			<input type="hidden" value="{if !empty($sProductNames)}{$sProductNames}{/if}" id="hiddenProductNames" name="hiddenProductNames" />

			<div class="clr_10"></div>
			<h3>{l s='Your excluded products list' mod='gmerchantcenter'}&nbsp;:</h3>

			<div class="col-xs-12 col-md-5 col-lg-4">
				<table id="bt_product-list" border="0" cellpadding="2" cellspacing="2" class="table table-striped table-responsive">
					<thead>
					<tr>
						<th>{l s='Product(s)' mod='gmerchantcenter'}</th>
						<th>{l s='Delete' mod='gmerchantcenter'}</th>
					</tr>
					</thead>
					<tbody id="bt_excluded-products">
					{if !empty($aProducts)}
						{foreach name=product key=key item=aProduct from=$aProducts}
							<tr>
								<td>{$aProduct.id|intval}{if isset($aProduct.attrId) && $aProduct.attrId != 0} (attr: {$aProduct.attrId|intval}){/if} - {$aProduct.name|escape:'htmlall':'UTF-8'}</td>
								<td><span class="icon-trash" style="cursor:pointer;" onclick="javascript: oGmc.deleteProduct('{$aProduct.stringIds|escape:'htmlall':'UTF-8'}');"></span></td>
							</tr>
						{/foreach}
					{else}
						<tr id="bt_exclude-no-products">
							<td colspan="2">{l s='No product' mod='gmerchantcenter'}</td>
						</tr>
					{/if}
					</tbody>
				</table>
			</div>
		{/if}
		{* END - Exclusion *}

		{* BEGIN - Feed data options *}
		{if !empty($sDisplay) && $sDisplay == 'data'}
			<h3>{l s='Feed data options' mod='gmerchantcenter'}</h3>
			<div class="clr_10"></div>
			{if !empty($bUpdate)}
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="alert alert-info">
				{l s='The more detailed information you provide to Google, the better your products will rank. Try to include as much information as possible. Please note that some fields are not appropriate for all products. See ' mod='gmerchantcenter'}
				<b><a href="https://support.google.com/merchants/answer/7052112?visit_id=1-636342381361070010-4017773094&rd=2&hl={$sCurrentIso|escape:'htmlall':'UTF-8'}" target="_blank">{l s='this Google documentation' mod='gmerchantcenter'}</a></b> {l s='for product data specification by country and details.' mod='gmerchantcenter'}
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<b>{l s='About products with combinations' mod='gmerchantcenter'}</b> :
				</label>
				<div class="col-xs-12 col-md-3 col-lg-3">
					<select name="bt_prod-combos" id="bt_prod-combos">
						<option value="0" {if empty($bProductCombos)}selected="selected"{/if}>{l s='Export all combinations in a single product' mod='gmerchantcenter'}</option>
						<option value="1" {if !empty($bProductCombos)}selected="selected"{/if}>{l s='Export each combination as a product in its own right' mod='gmerchantcenter'}</option>
					</select>
				</div>
			</div>

			<div id="bt_prod-combos-opts" style="display: {if !empty($bProductCombos)} block{else} none{/if}">
				<div class="form-group">
					<label class="control-label col-xs-12 col-md-3 col-lg-3">
						<b>{l s='Do you want to rewrite attribute numeric values with "," or "." in combination URLs?' mod='gmerchantcenter'}</b>
					</label>
					<div class="col-xs-12 col-md-5 col-lg-6">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_rewrite-num-attr" id="bt_rewrite-num-attr_on" value="1" {if !empty($bRewriteNumAttrValues)}checked="checked"{/if} />
							<label for="bt_rewrite-num-attr_on" class="radioCheck">
								{l s='Yes' mod='gmerchantcenter'}
							</label>
							<input type="radio" name="bt_rewrite-num-attr" id="bt_rewrite-num-attr_off" value="0" {if empty($bRewriteNumAttrValues)}checked="checked"{/if} />
							<label for="bt_rewrite-num-attr_off" class="radioCheck">
								{l s='No' mod='gmerchantcenter'}
							</label>
							<a class="slide-button btn"></a>
						</span>
						&nbsp;<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=173&lg={$sFaqLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='Should I activate this option?' mod='gmerchantcenter'}</a>
					</div>
				</div>

				{* USE CASE - we display this feature from PS 1.6.0.13 because they changed the way to format attributes into the combination URL and they added the attribute id, and sometimes some theme editors  *}
				{if !empty($bPS16013)}
					<div class="form-group">
						<label class="control-label col-xs-12 col-md-3 col-lg-3">
							<b>{l s='Do you want to include the attribute ID into the combination URLs?' mod='gmerchantcenter'}</b>
						</label>
						<div class="col-xs-12 col-md-5 col-lg-6">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="bt_incl-attr-id" id="bt_incl-attr-id_on" value="1" {if !empty($bUrlInclAttrId)}checked="checked"{/if} />
							<label for="bt_incl-attr-id_on" class="radioCheck">
								{l s='Yes' mod='gmerchantcenter'}
							</label>
							<input type="radio" name="bt_incl-attr-id" id="bt_incl-attr-id_off" value="0" {if empty($bUrlInclAttrId)}checked="checked"{/if} />
							<label for="bt_incl-attr-id_off" class="radioCheck">
								{l s='No' mod='gmerchantcenter'}
							</label>
							<a class="slide-button btn"></a>
						</span>
							&nbsp;<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=174&lg={$sFaqLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='How to set this option?' mod='gmerchantcenter'}</a>
						</div>
					</div>
				{/if}
				<div class="clr_10"></div>

				<div class="form-group">
					<label class="control-label col-xs-12 col-md-3 col-lg-3">
						<span class="label-tooltip" title="{l s='The "feed id" of a product is built like this: "Shop prefix + Language + product id + separator + combination id". For example, the feed id BMFR17v32 corresponds to the combination of id 32 of the product of id 17 of the French feed of the BM shop. You can here choose the separator between product id and combination id. By default the separator is "v".' mod='gmerchantcenter'}"><b>{l s='Choose the separator between product id and combination id' mod='gmerchantcenter'}</b></span></label>
					<div class="col-xs-4 col-md-4 col-lg-2">
						<input type="text" name="bt_combo-separator" value="{$sComboSeparator|escape:'htmlall':'UTF-8'}" />
					</div>
					<span class="icon-question-sign label-tooltip" title="{l s='The "feed id" of a product is built like this: "Shop prefix + Language + product id + separator + combination id". For example, the feed id BMFR17v32 corresponds to the combination of id 32 of the product of id 17 of the French feed of the BM shop. You can here choose the separator between product id and combination id. By default the separator is "v".' mod='gmerchantcenter'}">&nbsp;</span>
				</div>

			</div>

			{if !empty($aProducts)}
				<div class="form-group">
					<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
					<div class="col-xs-12 col-md-3 col-lg-3">
						<p class="alert alert-warning">
							{l s='Note : as it seems that you have defined some product exclusions, if you change the option above, you will have to define again the product exclusions (go in the previous \"Product exclusion rules\" tab to delete your list of products exclusion, save the page and make the list again). Indeed, if you want to export each combination as a product in its own right, the exclusions are to make by combination and no more by product.' mod='gmerchantcenter'}
						</p>
					</div>
				</div>
			{/if}

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<b>{l s='Which description type do you want to use?' mod='gmerchantcenter'}</b>
				</label>
				<div class="col-xs-12 col-md-3 col-lg-3">
					<select name="bt_prod-desc-type">
						{foreach from=$aDescriptionType name=desc key=iKey item=sType}
							<option value="{$iKey|intval}" {if $iKey == $iDescType}selected="selected"{/if}>{$sType|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
					{*<div class="alert-tag">Google: [description]</div>*}
				</div>
				&nbsp;&nbsp;
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/196" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product description' mod='gmerchantcenter'}</a>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<b>{l s='About product availability' mod='gmerchantcenter'}</b> :
				</label>
				<div class="col-xs-12 col-md-3 col-lg-3">
					<select name="bt_incl-stock">
						<option value="1" {if $iIncludeStock == 1}selected="selected"{/if}>{l s='Only indicate products as available IF they are actually in stock' mod='gmerchantcenter'}</option>
						<option value="2" {if $iIncludeStock == 2}selected="selected"{/if}>{l s='Always indicate products as available, EVEN IF they are in fact out of stock' mod='gmerchantcenter'}</option>
					</select>
					{*<div class="alert-tag">Google: [g:availability]</div>*}
				</div>
				&nbsp;&nbsp;
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/213" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product availability' mod='gmerchantcenter'}</a>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you have both EAN13/JAN and UPC codes for some of your products, you can decide to let the module check and use one of these two types of codes in priority over the other. For example, if your shop uses mostly EAN13/JAN code (and uses UPC codes for only some products), you\'ll probably want the module to first check the EAN13/JAN code and use it if it\'s available. However if the EAN13/JAN value is empty, then the module will check and use the UPC code, if it\'s available.' mod='gmerchantcenter'}"><b>{l s='Determination of priority GTIN (EAN13/JAN or UPC) :' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-3 col-lg-3">
					<select name="bt_gtin-pref">
						<option value="ean" {if $sGtinPreference == 'ean'}selected="selected"{/if}>{l s='Check EAN13/JAN code first' mod='gmerchantcenter'}</option>
						<option value="upc" {if $sGtinPreference == 'upc'}selected="selected"{/if}>{l s='Check UPC code first' mod='gmerchantcenter'}</option>
					</select>
				</div>
				<div>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='If you have both EAN13/JAN and UPC codes for some of your products, you can decide to let the module check and use one of these two types of codes in priority over the other. For example, if your shop uses mostly EAN13/JAN code (and uses UPC codes for only some products), you\'ll probably want the module to first check the EAN13/JAN code and use it if it\'s available. However if the EAN13/JAN value is empty, then the module will check and use the UPC code, if it\'s available.' mod='gmerchantcenter'}"><span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/192" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about GTIN codes' mod='gmerchantcenter'}</a>
				</div>
			</div>

			<div class="clr_10"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Use this tag for products that are for adults only. Select YES, save the tab and then click to configure your tags.' mod='gmerchantcenter'}"><b>{l s='Do you want to include adult tags?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_incl-tag-adult" id="bt_incl-tag-adult_on" value="1" {if !empty($bIncludeTagAdult)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('tag_adult_link', 'tag_adult_link', null, null, true, true);" />
						<label for="bt_incl-tag-adult_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_incl-tag-adult" id="bt_incl-tag-adult_off" value="0" {if empty($bIncludeTagAdult)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('tag_adult_link', 'tag_adult_link', null, null, true, false);" />
						<label for="bt_incl-tag-adult_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Use this tag for products that are for adults only. Select YES, save the tab and then click to configure your tags.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}/{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/222" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about adult tags' mod='gmerchantcenter'}</a>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
				{*<div class="alert-tag">Google: [g:adult]</div>*}
				</div>
			</div>


			<div class="form-group" id="tag_adult_link" {if empty($bIncludeTagAdult)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
					<div class="clr_15"></div>
					{if !empty($bIncludeTagAdult)}
						<a id="handleTagAdult" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.tag.type|escape:'htmlall':'UTF-8'}&sUseTag=adult">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
					{else}
						<div class="alert alert-danger">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</div>
					{/if}
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-4 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Use this tag to prevent some products from appearing on certain advertising channels. Select YES, save the tab and then click to configure your tags.' mod='gmerchantcenter'}"><b>{l s='Do you want to include excluded destination tags?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_excl_dest" id="bt_excl_dest_on" value="1" {if !empty($bExcludedDest)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('excl_dest', 'excl_dest', null, null, true, true);"/>
						<label for="bt_excl_dest_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_excl_dest" id="bt_excl_dest_off" value="0" {if empty($bExcludedDest)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('excl_dest', 'excl_dest', null, null, true, false);" />
						<label for="bt_excl_dest_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Use this tag to prevent some products from appearing on certain advertising channels. Select YES, save the tab and then click to configure your tags.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info pulse pulse2" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/318" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about excluded destination tags' mod='gmerchantcenter'}</a>
				</div>
			</div>
			
			<div class="clr_10"></div>

			<div class="form-group" id="excl_dest" {if empty($bExcludedDest)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-6 col-md-5 col-lg-4">

					{if !empty($bExcludedDest)}
						<span>
							<a id="handleTagMaterial" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action}&sType={$aQueryParams.tag.type}&sUseTag=excluded_destination">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
						</span>
					{else}
						<div class="clr_10"></div>
						<span class="alert alert-danger" id="save_require">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</span>
						<div class="clr_10"></div>
					{/if}

				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-4 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Use this tag to prevent some products from appearing on certain countries. Select YES, save the tab and then click to configure your tags.' mod='gmerchantcenter'}"><b>{l s='Do you want to include excluded country tags?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_excl_country" id="bt_excl_country_on" value="1" {if !empty($bExcludedCountry)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('excl_country', 'excl_country', null, null, true, true);"/>
						<label for="bt_excl_country_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_excl_country" id="bt_excl_country_off" value="0" {if empty($bExcludedCountry)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('excl_country', 'excl_country', null, null, true, false);" />
						<label for="bt_excl_country_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='Use this tag to prevent some products from appearing on certain countries. Select YES, save the tab and then click to configure your tags.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info pulse pulse2" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/386" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about excluded country tags' mod='gmerchantcenter'}</a>
				</div>
			</div>
			
			<div class="clr_10"></div>

			<div class="form-group" id="excl_country" {if empty($bExcludedCountry)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-6 col-md-5 col-lg-4">

					{if !empty($bExcludedCountry)}
						<span>
							<a id="handleTagMaterial" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action}&sType={$aQueryParams.tag.type}&sUseTag=excluded_country">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
						</span>
					{else}
						<div class="clr_10"></div>
						<span class="alert alert-danger" id="save_require">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</span>
						<div class="clr_10"></div>
					{/if}

				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='In order to export product sizes in your data feed (hightly recommended for clothing), select what feature or(and) attribute(s) define the size of your products.' mod='gmerchantcenter'}"><b>{l s='Do you want to include product sizes?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<div class="col-xs-12 col-md-4 col-lg-6">
						<select name="bt_incl-size" id="inc_size">
							<option value="" {if $sIncludeSize == ''}selected="selected"{/if}>{l s='No' mod='gmerchantcenter'}</option>
							<option value="attribute" {if $sIncludeSize == 'attribute'}selected="selected"{/if}>{l s='Yes : select ATTRIBUTE(S) that define sizes' mod='gmerchantcenter'}</option>
							<option value="feature" {if $sIncludeSize == 'feature'}selected="selected"{/if}>{l s='Yes : select FEATURE that defines sizes' mod='gmerchantcenter'}</option>
							<option value="both" {if $sIncludeSize == 'both'}selected="selected"{/if}>{l s='Yes : select ATTRIBUTE(S) AND FEATURE that define sizes' mod='gmerchantcenter'}</option>
						</select>
					</div>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='In order to export product sizes in your data feed (hightly recommended for clothing), select what feature or(and) attribute(s) define the size of your products.' mod='gmerchantcenter'}"><span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/201" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product sizes' mod='gmerchantcenter'}</a>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
				{*<div class="alert-tag">Google: [g:size]</div>*}
				</div>
			</div>

			<div class="form-group" id="div_size_opt_attr" style="display: none;">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
					<select name="bt_size-opt[attribute][]" multiple="multiple" size="8" id="size_opt_attr">
						<option value="" disabled="disabled" style="color: #aaa;font-weight: bold;">{l s='Attributes (multiple choice)' mod='gmerchantcenter'}</option>
						{foreach from=$aAttributeGroups name=attribute key=iKey item=aGroup}
							<option value="{$aGroup.id_attribute_group|intval}" {if !empty($aSizeOptions.attribute) && is_array($aSizeOptions.attribute) && in_array($aGroup.id_attribute_group, $aSizeOptions.attribute)}selected="selected"{/if} style="padding-left: 10px;font-weight: bold;">{$aGroup.name|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="form-group" id="div_size_opt_feat" style="display: none;">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
					<select name="bt_size-opt[feature][]" size="8" id="size_opt_feat">
						<option value="" disabled="disabled" style="color: #aaa;font-weight: bold;">{l s='Features (one choice)' mod='gmerchantcenter'}</option>
						{foreach from=$aFeatures name=feature key=iKey item=aFeature}
							<option value="{$aFeature.id_feature|intval}" {if !empty($aSizeOptions.feature) && is_array($aSizeOptions.feature) && in_array($aFeature.id_feature, $aSizeOptions.feature)}selected="selected"{/if} style="padding-left: 10px;font-weight: bold;">{$aFeature.name|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</div>
			</div>

			{*use case color*}
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3">
					<span class="label-tooltip" title="{l s='In order to export product colors in your data feed (hightly recommended for clothing), select what feature or(and) attribute(s) define the color of your products.' mod='gmerchantcenter'}"><b>{l s='Do you want to include product colors?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
					<select name="bt_incl-color" id="inc_color">
						<option value="" {if $sIncludeColor == ''}selected="selected"{/if}>{l s='No' mod='gmerchantcenter'}</option>
						<option value="attribute" {if $sIncludeColor == 'attribute'}selected="selected"{/if}>{l s='Yes : select ATTRIBUTE(S) that define colors' mod='gmerchantcenter'}</option>
						<option value="feature" {if $sIncludeColor == 'feature'}selected="selected"{/if}>{l s='Yes : select FEATURE that defines colors' mod='gmerchantcenter'}</option>
						<option value="both" {if $sIncludeColor == 'both'}selected="selected"{/if}>{l s='Yes : select ATTRIBUTE(S) AND FEATURE that define colors' mod='gmerchantcenter'}</option>
					</select>
				</div>
				<div>
				
				<span class="icon-question-sign label-tooltip" title="{l s='In order to export product colors in your data feed (hightly recommended for clothing), select what feature or(and) attribute(s) define the color of your products.' mod='gmerchantcenter'}"></span>
				&nbsp;&nbsp;
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/199" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about product colors' mod='gmerchantcenter'}</a>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
				{*<div class="alert-tag">Google: [g:color]</div>*}
				</div>
			</div>

			<div class="form-group" id="div_color_opt_attr" style="display: none;">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
					<select name="bt_color-opt[attribute][]" multiple="multiple" size="8" id="color_opt_attr">
						<option value="" disabled="disabled" style="color: #aaa;font-weight: bold;">{l s='Attributes (multiple choice)' mod='gmerchantcenter'}</option>
						{foreach from=$aAttributeGroups name=attribute key=iKey item=aGroup}
							<option value="{$aGroup.id_attribute_group|intval}" {if !empty($aColorOptions.attribute) && is_array($aColorOptions.attribute) && in_array($aGroup.id_attribute_group, $aColorOptions.attribute)}selected="selected"{/if} style="padding-left: 10px;font-weight: bold;">{$aGroup.name|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="form-group" id="div_color_opt_feat" style="display: none;">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
					<select name="bt_color-opt[feature][]" size="8" id="color_opt_feat">
						<option value="" disabled="disabled" style="color: #aaa;font-weight: bold;">{l s='Features (one choice)' mod='gmerchantcenter'}</option>
						{foreach from=$aFeatures name=feature key=iKey item=aFeature}
							<option value="{$aFeature.id_feature|intval}" {if !empty($aColorOptions.feature) && is_array($aColorOptions.feature) && in_array($aFeature.id_feature, $aColorOptions.feature)}selected="selected"{/if} style="padding-left: 10px;font-weight: bold;">{$aFeature.name|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</div>
			</div>
			
		{/if}
		{* END - Feed data options *}

		{* BEGIN - Apparel *}
		{if !empty($sDisplay) && $sDisplay == 'apparel'}
			<h3>{l s='Apparel feed options' mod='gmerchantcenter'}</h3>

			{if !empty($bUpdate)}
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				{include file="`$sErrorInclude`"}
			{/if}
			<div class="clr_10"></div>
			<div class="alert alert-info">
			<p><span class="highlight_element">
				<b>{l s='If available, Clothing and Apparel stores should try to include these options.' mod='gmerchantcenter'}</b></span>
					{l s='Also, for the other stores, please note that the more information you will provide about your products to Google, the better your feed will be and the better your products will be ranked in Google Shopping. So when it\'s possible, don\'t hesitate to attribute the following tags to your products.' mod='gmerchantcenter'}</p>
			</div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to indicate the feature that defines the material of the products that are in this category. Features we\'re talking about are those you have created in the \"Product features\" tab of your PrestaShop.' mod='gmerchantcenter'}"><b>{l s='Do you want to include material tags ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_incl-material" id="bt_incl-material_on" value="1" {if !empty($bIncludeMaterial)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('material_link', 'material_link', null, null, true, true);"/>
						<label for="bt_incl-material_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_incl-material" id="bt_incl-material_off" value="0" {if empty($bIncludeMaterial)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('material_link', 'material_link', null, null, true, false);" />
						<label for="bt_incl-material_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to indicate the feature that defines the material of the products that are in this category. Features we\'re talking about are those you have created in the \"Product features\" tab of your PrestaShop.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/205" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about material tags' mod='gmerchantcenter'}</a>
				</div>
			</div>

			<div class="form-group" id="material_link" {if empty($bIncludeMaterial)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-6 col-md-5 col-lg-4">
					<div class="clr_15"></div>
					{if !empty($bIncludeMaterial)}
						<a id="handleTagMaterial" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.tag.type|escape:'htmlall':'UTF-8'}&sUseTag=material">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
					{else}
						<span class="alert alert-danger" id="save_require">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</span>
					 {/if}
				</div>
			</div>
			
			<div class="clr_30"></div>
			
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to indicate the feature that defines the pattern of the products that are in this category. Features are those you have created in the \"Product features\" tab of your PrestaShop.' mod='gmerchantcenter'}"><b>{l s='Do you want to include pattern tags ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_incl-pattern" id="bt_incl-pattern_on" value="1" {if !empty($bIncludePattern)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('pattern_link', 'pattern_link', null, null, true, true);"/>
						<label for="bt_incl-pattern_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_incl-pattern" id="bt_incl-pattern_off" value="0" {if empty($bIncludePattern)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('pattern_link', 'pattern_link', null, null, true, false);" />
						<label for="bt_incl-pattern_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to indicate the feature that defines the pattern of the products that are in this category. Features are those you have created in the \"Product features\" tab of your PrestaShop.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/206" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about pattern tags' mod='gmerchantcenter'}</a>
				</div>
			</div>
			

			<div class="form-group" id="pattern_link" {if empty($bIncludePattern)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-6 col-md-5 col-lg-4">
					<div class="clr_15"></div>
					{if !empty($bIncludePattern)}
						<a id="handleTagPattern" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.tag.type|escape:'htmlall':'UTF-8'}&sUseTag=pattern">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
					{else}
						<span class="alert alert-danger" id="save_require">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</span>
					{/if}
				</div>
			</div>
			
			<div class="clr_30"></div>
			
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"gender\" value defines the gender for which the products of this category are reserved.' mod='gmerchantcenter'}"><b>{l s='Do you want to include gender tags ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_incl-gender" id="bt_incl-gender_on" value="1" {if !empty($bIncludeGender)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('gender_link', 'gender_link', null, null, true, true);"/>
						<label for="bt_incl-gender_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_incl-gender" id="bt_incl-gender_off" value="0" {if empty($bIncludeGender)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('gender_link', 'gender_link', null, null, true, false);" />
						<label for="bt_incl-gender_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"gender\" value defines the gender for which the products of this category are reserved.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/209" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about gender tags' mod='gmerchantcenter'}</a>
				</div>
			</div>
			

			<div class="form-group" id="gender_link" {if empty($bIncludeGender)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-6 col-md-5 col-lg-4">
					<div class="clr_15"></div>
					{if !empty($bIncludeGender)}
						<a id="handleTagGender" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.tag.type|escape:'htmlall':'UTF-8'}&sUseTag=gender">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
					{else}
						<span class="alert alert-danger" id="save_require">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</span>
					{/if}
				</div>
			</div>
			
			<div class="clr_30"></div>

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"age group\" value defines the age group for which the products of this category are reserved.' mod='gmerchantcenter'}"><b>{l s='Do you want to include age group tags ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_incl-age" id="bt_incl-age_on" value="1" {if !empty($bIncludeAge)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('age_group_link', 'age_group_link', null, null, true, true);"/>
						<label for="bt_incl-age_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_incl-age" id="bt_incl-age_off" value="0" {if empty($bIncludeAge)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('age_group_link', 'age_group_link', null, null, true, false);" />
						<label for="bt_incl-age_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"age group\" value defines the age group for which the products of this category are reserved.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/202" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about age group tags' mod='gmerchantcenter'}</a>
				</div>
			</div>

			<div class="form-group" id="age_group_link" {if empty($bIncludeAge)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-6 col-md-5 col-lg-4">
					<div class="clr_15"></div>
					{if !empty($bIncludeAge)}
						<a id="handleTagAge" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.tag.type|escape:'htmlall':'UTF-8'}&sUseTag=agegroup">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
					{else}
						<span class="alert alert-danger" id="save_require">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</span>
					{/if}
				</div>
			</div>
			
			<div class="clr_30"></div>

			{*size type*}
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"size type\" value defines the size type of the products that are in this category.' mod='gmerchantcenter'}"><b>{l s='Do you want to include size type tags ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_incl-size_type" id="bt_incl-size_type_on" value="1" {if !empty($bSizeType)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('size_type', 'size_type', null, null, true, true);"/>
						<label for="bt_incl-size_type_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_incl-size_type" id="bt_incl-size_type_off" value="0" {if empty($bSizeType)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('size_type', 'size_type', null, null, true, false);" />
						<label for="bt_incl-size_type_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"size type\" value defines the size type of the products that are in this category.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}/{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/220" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about size type tags' mod='gmerchantcenter'}</a>
				</div>
			</div>
			
			{*<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
				<div class="alert-tag">Google: [g:size_type]</div>
				</div>
			</div>*}

			<div class="form-group" id="size_type" {if empty($bSizeType)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-6 col-md-5 col-lg-4">
					<div class="clr_15"></div>
					{if !empty($bSizeType)}
						<a id="handleTagAge" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.tag.type|escape:'htmlall':'UTF-8'}&sUseTag=sizeType">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
					{else}
						<span class="alert alert-danger" id="save_require">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</span>
					{/if}
				</div>
			</div>
			
			<div class="clr_30"></div>

			{*size system*}
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"size system\" value defines the size system used for the products that are in this category.' mod='gmerchantcenter'}"><b>{l s='Do you want to include size system tags ?' mod='gmerchantcenter'}</b></span></label>
				<div class="col-xs-12 col-md-5 col-lg-6">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_incl-size_system" id="bt_incl-size_system_on" value="1" {if !empty($bSizeSystem)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('size_system', 'size_system', null, null, true, true);"/>
						<label for="bt_incl-size_system_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_incl-size_system" id="bt_incl-size_system_off" value="0" {if empty($bSizeSystem)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('size_system', 'size_system', null, null, true, false);" />
						<label for="bt_incl-size_system_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
					<span class="label-tooltip" data-toggle="tooltip" title data-original-title="{l s='For each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"size system\" value defines the size system used for the products that are in this category.' mod='gmerchantcenter'}">&nbsp;&nbsp;<span class="icon-question-sign"></span></span>
					&nbsp;&nbsp;
					<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}/{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/221" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about size system tags' mod='gmerchantcenter'}</a>
				</div>
			</div>
			
			{*<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
				<div class="alert-tag">Google: [g:size_system]</div>
				</div>
			</div>*}

			<div class="form-group" id="size_system" {if empty($bSizeSystem)}style="display: none;"{/if}>
				<label class="control-label col-xs-12 col-md-3 col-lg-3"></label>
				<div class="col-xs-6 col-md-5 col-lg-4">
					<div class="clr_15"></div>
					{if !empty($bSizeSystem)}
						<a id="handleTagAge" class="btn btn-success btn-md fancybox.ajax" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.tag.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.tag.type|escape:'htmlall':'UTF-8'}&sUseTag=sizeSystem">{l s='Click here to configure the tag for each category' mod='gmerchantcenter'}</a>
					{else}
						<span class="alert alert-danger" id="save_require">{l s='Please save this page before configuring the tag' mod='gmerchantcenter'}</span>
					{/if}
				</div>
			</div>
		{/if}
		{* END - Apparel *}

		{* BEGIN - Taxes and shipping fees *}
		{if !empty($sDisplay) && $sDisplay == 'tax'}
			<h3>{l s='Taxes management' mod='gmerchantcenter'}</h3>
			<div class="clr_10"></div>
			{if !empty($bUpdate)}
				{include file="`$sConfirmInclude`"}
			{elseif !empty($aErrors)}
				{include file="`$sErrorInclude`"}
			{/if}

			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><b>{l s='Select the feeds for which you want the product prices to be displayed with "tax included" :' mod='gmerchantcenter'}</b></label>
				<div class="col-xs-12 col-md-4 col-lg-3">
					{if !empty($aFeedTax)}
					<table border="0" cellpadding="2" cellspacing="2" class="table table-striped table-responsive">
						<tr>
							<th>{l s='Language / country' mod='gmerchantcenter'}</th>
							<th>{l s=' ' mod='gmerchantcenter'}</th>
						</tr>
						{foreach from=$aFeedTax name=feed key=iKey item=aTax}
							<tr>
								<td>{$aTax.lang|escape:'htmlall':'UTF-8'}-{$aTax.country|escape:'htmlall':'UTF-8'}</td>
								<td class="center">
									<input type="hidden" id="bt_feed-tax-{$aTax.lang|lower|escape:'htmlall':'UTF-8'}_{$aTax.country|escape:'htmlall':'UTF-8'}" name="bt_feed-tax-hidden[]" value="{$aTax.lang|lower|escape:'htmlall':'UTF-8'}_{$aTax.country|escape:'htmlall':'UTF-8'}" {if !empty($aTax.tax)}checked="checked"{/if} />
									<input type="checkbox" id="bt_feed-tax-{$aTax.lang|lower|escape:'htmlall':'UTF-8'}_{$aTax.country|escape:'htmlall':'UTF-8'}" name="bt_feed-tax[]" value="{$aTax.lang|lower|escape:'htmlall':'UTF-8'}_{$aTax.country|escape:'htmlall':'UTF-8'}" {if !empty($aTax.tax)}checked="checked"{/if} />
								</td>
							</tr>
						{/foreach}
					</table>
					{else}
					<div class="alert alert-warning">
						{l s='Either you just updated your configuration by deactivating the advanced file security feature (in which case, please reload the page), or there are no files because no valid languages / currencies / countries are available according to the Google\'s requirements' mod='gmerchantcenter'}.
					</div>
					{/if}
				</div>
			</div>

			<div class="clr_30"></div>
			<h3>{l s='Dimensions of the package' mod='gmerchantcenter'}</h3>
			<div class="clr_15"></div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><b>{l s='Do you want to include the dimensions of the package ?' mod='gmerchantcenter'}</b></label>
				<div class="col-xs-12 col-md-5 col-lg-3">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_manage-dimension" id="bt_manage-dimension_on" value="1" {if !empty($bDimensionUse)}checked="checked"{/if}/>
						<label for="bt_manage-dimension_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_manage-dimension" id="bt_manage-dimension_off" value="0" {if empty($bDimensionUse)}checked="checked"{/if}/>
						<label for="bt_manage-dimension_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
				<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}{$sFaqLang|escape:'htmlall':'UTF-8'}/faq/452" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='FAQ about the dimensions of the package' mod='gmerchantcenter'}</a>
			</div>
			
			<div class="clr_30"></div>
			<h3>{l s='Shipping fees' mod='gmerchantcenter'}</h3>

			<div class="clr_15"></div>
			<div class="form-group">
				<label class="control-label col-xs-12 col-md-3 col-lg-3"><b>{l s='Do you want the module to handle shipping fees ?' mod='gmerchantcenter'}</b></label>
				<div class="col-xs-12 col-md-5 col-lg-3">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="bt_manage-shipping" id="bt_manage-shipping_on" value="1" {if !empty($bShippingUse)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('bt_conf-shipping', 'bt_conf-shipping', null, null, true, true);"/>
						<label for="bt_manage-shipping_on" class="radioCheck">
							{l s='Yes' mod='gmerchantcenter'}
						</label>
						<input type="radio" name="bt_manage-shipping" id="bt_manage-shipping_off" value="0" {if empty($bShippingUse)}checked="checked"{/if} onclick="javascript: oGmc.changeSelect('bt_conf-shipping', 'bt_conf-shipping', null, null, true, false);" />
						<label for="bt_manage-shipping_off" class="radioCheck">
							{l s='No' mod='gmerchantcenter'}
						</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
			
			<div id="bt_conf-shipping" {if empty($bShippingUse)}style="display: none;"{/if}>

				<div class="alert alert-info">
					{l s='Please select the appropriate default carrier for each country below' mod='gmerchantcenter'}&nbsp;:
				</div>

				{if !empty($aShippingCarriers)}
					{foreach from=$aShippingCarriers name=shipping key=sCountry item=aShipping}
						<div class="form-group">
							<label class="control-label col-xs-12 col-md-3 col-lg-3">
								<span title=""><b>{$sCountry|escape:'htmlall':'UTF-8'}</b></span>
							</label>
							<div class="col-xs-12 col-md-3 col-lg-3">
								<select name="bt_ship-carriers[{$sCountry|escape:'htmlall':'UTF-8'}]">
									{foreach from=$aShipping.carriers name=carrier key=iKey item=aCarrier}
										<option {if $aCarrier.id_carrier == $aShipping.shippingCarrierId}selected=selected{/if} value="{$aCarrier.id_carrier|intval}">{$aCarrier.name|escape:'htmlall':'UTF-8'}</option>
									{/foreach}
								</select>
							</div>
						</div>
						<div class="clr_15"></div>
					{/foreach}
				{else}
					<div class="alert alert-warning">
						{l s='There isn\'t any carrier available' mod='gmerchantcenter'}
						<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=51&lg={$sFaqLang|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='Click here to get more information' mod='gmerchantcenter'}</a>
					</div>
					<div class="clr_15"></div>
				{/if}


				{*handle the free shipping for few products*}
				<div class="clr_10"></div>
				<h3>{l s='Free shipping management' mod='gmerchantcenter'}</h3>

				<div class="form-group">
					<label class="control-label col-xs-12 col-md-3 col-lg-3">
						<span class="label-tooltip" title="{l s='Start to type a product name and select it in the autocomplete list that will appear' mod='gmerchantcenter'}"><b>{l s='Enter products with free shipping :' mod='gmerchantcenter'}</b></span></label>
					<div class="col-xs-12 col-md-3 col-lg-2">
						<input type="text" size="5" id="bt_search-p-free-shipping" name="bt_search-p-free-shipping" value="" placeholder="{l s='Start to type a product name' mod='gmerchantcenter'}"/>
					</div>
					
					<span class="icon-question-sign label-tooltip" title="{l s='Start to type a product name and select it in the autocomplete list that will appear' mod='gmerchantcenter'}">&nbsp;</span>
				</div>

				<input type="hidden" value="{if !empty($sProductFreeShippingIds)}{$sProductFreeShippingIds|escape:'htmlall':'UTF-8'}{else}{/if}" id="hiddenProductFreeShippingIds" name="hiddenProductFreeShippingIds" />
				<input type="hidden" value="{if !empty($sProductFreeShippingNames)}{$sProductFreeShippingNames}{/if}" id="hiddenProductFreeShippingNames" name="hiddenProductFeedNames" />

				<h4>{l s='Products with free shipping list :' mod='gmerchantcenter'}</h4>

				<div class="clr_hr"></div>
				<div class="clr_10"></div>

				<div class="col-xs-12 col-md-5 col-lg-4">
					<table id="bt_product-list-free-shipping" border="0" cellpadding="2" cellspacing="2" class="table table-striped table-resposive">
						<thead>
						<tr>
							<th>{l s='Product(s)' mod='gmerchantcenter'}</th>
							<th>{l s='Delete' mod='gmerchantcenter'}</th>
						</tr>
						</thead>
						<tbody id="bt_free-shipping-products">
						{if !empty($aProductsFreeShipping)}
							{foreach name=product key=key item=aProduct from=$aProductsFreeShipping}
								<tr>
									<td>{$aProduct.id|intval}{if isset($aProduct.attrId) && $aProduct.attrId != 0} (attr: {$aProduct.attrId|intval}){/if} - {$aProduct.name|escape:'htmlall':'UTF-8'}</td>
									<td><span class="icon-trash" style="cursor:pointer;" onclick="javascript: oGmc.deleteProductFreeShipping('{$aProduct.stringIds|escape:'htmlall':'UTF-8'}');"></span></td>
								</tr>
							{/foreach}
						{else}
							<tr id="bt_free-shipping-no-products">
								<td colspan="2">{l s='No product' mod='gmerchantcenter'}</td>
							</tr>
						{/if}
						</tbody>
					</table>
				</div>
			</div>
		{/if}
		{* END - Taxes and shipping fees *}

		<div class="clr_10"></div>
		<div class="clr_hr"></div>
		<div class="clr_10"></div>

		<div class="center">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
					<div id="{$sModuleName|escape:'htmlall':'UTF-8'}Feed{$sDisplay|escape:'htmlall':'UTF-8'}Error"></div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<button  class="btn btn-default pull-right" onclick="oGmc.form('bt_feed-{$sDisplay|escape:'htmlall':'UTF-8'}-form', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_feed-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', 'bt_feed-settings-{$sDisplay|escape:'htmlall':'UTF-8'}', false, false, {if empty($sDisplay) || (!empty($sDisplay) && $sDisplay == 'export')}oFeedSettingsCallBack{else}null{/if}, 'Feed{$sDisplay|escape:'htmlall':'UTF-8'}', 'loadingFeedDiv' , false, 2);return false;"><i class="process-icon-save"></i>{l s='Save' mod='gmerchantcenter'}</button>
				</div>
			</div>
		</div>
	</form>
</div>
{literal}
<script type="text/javascript">
	$(document).ready(function() {
		{/literal}{if !empty($sDisplay) && $sDisplay == 'exclusion'}{literal}
		// set all elements for autocomplete
		oGmc.aParamsAutcomplete = {sInputSearch : '#bt_search-p', sExcludeNoProducts : '#bt_exclude-no-products', sExcludeProducts : '#bt_excluded-products', sHiddenProductNames : '#hiddenProductNames' , sHiddenProductIds : '#hiddenProductIds'};
		// autocomplete
		oGmc.autocomplete('{/literal}{$sURI}&sAction={$aQueryParams.searchProduct.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.searchProduct.type|escape:'htmlall':'UTF-8'}{literal}', '#bt_search-p');
		{/literal}{/if}{literal}

		{/literal}{if !empty($sDisplay) && $sDisplay == 'tax'}{literal}
		// autocomplete
		oGmc.autocompleteFreeShipping('{/literal}{$sURI}&sAction={$aQueryParams.searchProduct.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.searchProduct.type|escape:'htmlall':'UTF-8'}{literal}', '#bt_search-p-free-shipping');
		{/literal}{/if}{literal}
	});
	//bootstrap components init
	{/literal}{if !empty($bAjaxMode)}{literal}
		$('.label-tooltip, .help-tooltip').tooltip();
		oGmc.runMainFeed();
	{/literal}{/if}{literal}

	// handle export type
	$("#bt_prod-combos").bind('change', function (event) {
		$("#bt_prod-combos option:selected").each(function () {
			switch ($(this).val()) {
				case '0' :
					$("#bt_prod-combos-opts").hide();
					break;
				case '1' :
					$("#bt_prod-combos-opts").show();
					break;
				default:
					$("#bt_prod-combos-opts").hide();
					break;
			}
		});
	}).change();
</script>
{/literal}