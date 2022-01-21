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
	<div id="bt_custom-tag" class="col-xs-12 bt_adwords">
		{if !empty($aTag)}
		<h3>{l s='Update a custom label' mod='gmerchantcenter'}</h3>
		{else}
		<h3>{l s='Create a custom label' mod='gmerchantcenter'}</h3>
		{/if}
		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<script type="text/javascript">
			{literal}
			var oCustomCallBack = [{
				'name' : 'displayGoogleList',
				'url' : '{/literal}{$sURI}{literal}',
				'params' : '{/literal}{$sCtrlParamName|escape:'htmlall':'UTF-8'}{literal}={/literal}{$sController|escape:'htmlall':'UTF-8'}{literal}&sAction=display&sType={/literal}{$aQueryParams.google.type|escape:'htmlall':'UTF-8'}{literal}&sDisplay=adwords',
				'toShow' : 'bt_google-settings-adwords',
				'toHide' : 'bt_google-settings-adwords',
				'bFancybox' : false,
				'bFancyboxActivity' : false,
				'sLoadbar' : null,
				'sScrollTo' : null,
				'oCallBack' : {}
			}];
			{/literal}
		</script>

		<form class="form-horizontal" method="post" id="bt_form-custom-tag" name="bt_form-custom-tag" {if $smarty.const._GSR_USE_JS == true}onsubmit="oGmc.form('bt_form-custom-tag', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_custom-tag', 'bt_custom-tag', false, true, oCustomCallBack, 'CustomTag', 'loadingCustomTagDiv');return false;"{/if}>
			<input type="hidden" name="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" value="{$sController|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sAction" value="{$aQueryParams.customUpdate.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.customUpdate.type|escape:'htmlall':'UTF-8'}" />
			{if !empty($aTag)}
			<input type="hidden" name="bt_tag-id" value="{$aTag.id_tag|intval}" id="tag_id" />
			{/if}

			
			<div class="alert alert-info">
				<p><strong class="highlight_element">{l s='To help you in your custom labels creation, don\'t hesitate to read our ' mod='gmerchantcenter'}
				<a href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=84&lg={$sFaqLang|escape:'htmlall':'UTF-8'}" target="_blank">{l s='FAQ : How to create custom labels ?' mod='gmerchantcenter'}</a></strong></p>
			</div>
			
			
			<div class="form-group" id="optionplus">
				<label class="control-label col-lg-2">
					<b>{l s='Your custom label\'s name' mod='gmerchantcenter'}</b>
				</label>
				<div class="col-xs-3">
					<input type="text" name="bt_label-name" value="{if !empty($aTag)}{$aTag.name|escape:'htmlall':'UTF-8'}{/if}" />
				</div>
			</div>

			<div class="clr_15"></div>

	
			<div class="table-responsive">
				<table class="table table-responsive">
					<thead>
					<tr class="bt_tr_header">
						<th id="bt_cl_configure_cat_header" style="border-right: 1px solid #FFFFFF" class="col-xs-3">
							<div class="row">
								<div class="col-xs-5">
									<b><h4>{l s='Manage by categories' mod='gmerchantcenter'}</h4><b>
								</div>
								<div class="col-xs-6 pull-right">
									<span class="pull-right">
										<div class="btn btn-default btn-mini" id="categoryCheck" onclick="return oGmc.selectAll('input.categoryBoxLabel', 'check');"><i class="icon-plus-square"></i>&nbsp;{l s='Check All' mod='gmerchantcenter'}</div> - <div class="btn btn-default btn-mini" id="categoryUnCheck" onclick="return oGmc.selectAll('input.categoryBoxLabel', 'uncheck');"><i class="icon-minus-square"></i>&nbsp;{l s='Uncheck All' mod='gmerchantcenter'}</div>
									</span>
								</div>
							</div>
						</th>
						<th id="bt_cl_configure_brand_header" style="border-right: 1px solid #FFFFFF" class="col-xs-3">
							<div class="row">
								<div class="col-xs-5">
									<b><h4>{l s='Manage by brands' mod='gmerchantcenter'}</h4><b>
								</div>
								<div class="col-xs-6 pull-right">
									<span class="pull-right">
										<div class="btn btn-default btn-mini" id="brandCheck" onclick="return oGmc.selectAll('input.brandBoxLabel', 'check');"><i class="icon-plus-square"></i>&nbsp;{l s='Check All' mod='gmerchantcenter'}</div> - <div class="btn btn-default btn-mini" id="brandUnCheck" onclick="return oGmc.selectAll('input.brandBoxLabel', 'uncheck');"><i class="icon-minus-square"></i>&nbsp;{l s='Uncheck All' mod='gmerchantcenter'}</div>
									</span>
								</div>
							</div>
						</th>
						<th id="bt_cl_configure_supplier_header" style="border-right: 1px solid #FFFFFF" class="col-xs-3">
							<div class="row">
								<div class="col-xs-5">
									<b><h4>{l s='Manage by suppliers' mod='gmerchantcenter'}</h4><b>
								</div>
								<div class="col-xs-6 pull-right">
									<span class="pull-right">
										<div class="btn btn-default btn-mini" id="supplierCheck" onclick="return oGmc.selectAll('input.supplierBoxLabel', 'check');"><i class="icon-plus-square"></i>&nbsp;{l s='Check All' mod='gmerchantcenter'}</div> - <div class="btn btn-default btn-mini" id="supplierUnCheck" onclick="return oGmc.selectAll('input.supplierBoxLabel', 'uncheck');"><i class="icon-minus-square"></i>&nbsp;{l s='Uncheck All' mod='gmerchantcenter'}</div>
									</span>
								</div>
							</div>
						</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td class="bt_table_td" id="bt_cl_configure_cat">
							<div id="bt_cat_tree" class="col-xs-12 bt_select_product">
								<table cellspacing="0" cellpadding="0" class="table  table-bordered table-striped" style="width: 100%;">
									{foreach from=$aFormatCat name=category key=iKey item=aCat}
										<tr class="alt_row">
											<td>
												{$aCat.id_category|intval}
											</td>
											<td>
												<input type="checkbox" name="bt_category-box[]" class="categoryBoxLabel" id="bt_category-box_{$aCat.iNewLevel|intval}" value="{$aCat.id_category|intval}" {if !empty($aCat.bCurrent)}checked="checked"{/if} />
											</td>
											<td>
												<i class="icon icon-folder{if !empty($aCat.bCurrent)}-open{/if}" style="margin-left: {$aCat.iNewLevel}5px;"></i>&nbsp;<span style="font-size:12px;">{$aCat.name|escape:'htmlall':'UTF-8'}</span>
											</td>
										</tr>
									{/foreach}
								</table>
							</div>
						</td>
						<td class="bt_table_td" id="bt_cl_configure_brand">
							<div class="col-xs-12 bt_select_product">
								<table cellspacing="0" cellpadding="0" class="table  table-bordered table-striped" style="width: 100%;">
									{foreach from=$aFormatBrands name=brand key=iKey item=aBrand}
										<tr class="alt_row">
											<td>
												{$aBrand.id|intval}
											</td>
											<td>
												<input type="checkbox" name="bt_brand-box[]" class="brandBoxLabel" id="bt_brand-box_{$aBrand.id|intval}" value="{$aBrand.id|intval}" {if !empty($aBrand.checked)}checked="checked"{/if} />
											</td>
											<td>
												<i class="icon icon-folder{if !empty($aBrand.checked)}-open{/if}"></i>&nbsp;&nbsp;<span style="font-size:12px;">{$aBrand.name|escape:'htmlall':'UTF-8'}</span>
											</td>
										</tr>
									{/foreach}
								</table>
							</div>
						</td>
						<td class="bt_table_td" id="bt_cl_configure_supplier">
							<div class="col-xs-12 bt_select_product" >
								<table cellspacing="0" cellpadding="0" class="table  table-bordered table-striped" style="width: 100%;">
									{foreach from=$aFormatSuppliers name=supplier key=iKey item=aSupplier}
										<tr class="alt_row">
											<td>
												{$aSupplier.id|intval}
											</td>
											<td>
												<input type="checkbox" name="bt_supplier-box[]" class="supplierBoxLabel" id="bt_supplier-box_{$aSupplier.id|intval}" value="{$aSupplier.id|intval}" {if !empty($aSupplier.checked)}checked="checked"{/if} />
											</td>
											<td>
												<i class="icon icon-folder{if !empty($aSupplier.checked)}-open{/if}"></i>&nbsp;&nbsp;<span style="font-size:12px;">{$aSupplier.name|escape:'htmlall':'UTF-8'}</span>
											</td>
										</tr>
									{/foreach}
								</table>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
			</div>

			<div class="clr_20"></div>

			<div id="{$sModuleName|escape:'htmlall':'UTF-8'}CustomTagError"></div>

			<div class="clr_20"></div>

			<p style="text-align: center !important;">
				{if $smarty.const._GMC_USE_JS == true}
					<input type="button" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{if !empty($aTag)}{l s='Modify' mod='gmerchantcenter'}{else}{l s='Add' mod='gmerchantcenter'}{/if}" onclick="oGmc.form('bt_form-custom-tag', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_custom-tag', 'bt_custom-tag', false, true, oCustomCallBack, 'CustomTag', 'loadingCustomTagDiv');return false;" />
				{else}
					<input type="submit" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='gmerchantcenter'}" />
				{/if}
				<button class="btn btn-danger btn-lg" value="{l s='Cancel' mod='gmerchantcenter'}"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gmerchantcenter'}</button>
			</p>
		</form>
	</div>
</div>
<div id="loadingCustomTagDiv" style="display: none;">
	<div class="alert alert-info">
		<p style="text-align: center !important;"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
		<p style="text-align: center !important;">{l s='Your configuration updating is in progress...' mod='gmerchantcenter'}</p>
	</div>
</div>
{/if}