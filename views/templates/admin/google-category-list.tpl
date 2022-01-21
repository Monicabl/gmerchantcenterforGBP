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
{/if}
<table class="table table-responsive">
	<tr class="bt_tr_header text-center">
		<th class="center">{l s='Google taxonomy code' mod='gmerchantcenter'}</th>
		<th class="center">{l s='Taxonomy file' mod='gmerchantcenter'}</th>
		<th class="center">{l s='Concerned countries' mod='gmerchantcenter'}</th>
		<th class="center">{l s='Match my categories' mod='gmerchantcenter'}</th>
		<th class="center">{l s='Synchronise from Google' mod='gmerchantcenter'}</th>
	</tr>
	{foreach from=$aCountryTaxonomies name=taxonomy key=sCode item=aTaxonomy}
		<tr>
			<td class="center">{$sCode|escape:'htmlall':'UTF-8'}</td>
			<td class="center"><a class="btn btn-sm btn-primary" target="_blank" href="https://www.google.com/basepages/producttype/taxonomy.{$sCode|escape:'htmlall':'UTF-8'}.txt"><i class="fa fa-file"></i> </a> </td>
			<td class="center">{$aTaxonomy.countryList|escape:'htmlall':'UTF-8'}</td>
			{if !empty($aTaxonomy.updated)}
				<td id="gcupd_{$sCode|escape:'htmlall':'UTF-8'}" class="center">
					<a id="handleGoogle" class="fancybox.ajax btn btn-sm btn-success" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.googleCat.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.googleCat.type|escape:'htmlall':'UTF-8'}&iLangId={$aTaxonomy.id_lang|intval}&sLangIso={$sCode|escape:'htmlall':'UTF-8'}"><i class="icon-pencil"></i></a>
				</td>
			{else}
				<td class="center text-warning" id="gcupd_{$sCode|escape:'htmlall':'UTF-8'}">{l s='Please synchronise first, click there -->' mod='gmerchantcenter'}</td>
			{/if}
			<td class="center">
				<a class="btn btn-sm btn-default" id="updateGoogleCategories" href="#" onclick="$('#loadingGoogleCatListDiv').show();oGmc.hide('bt_google-cat-list');oGmc.ajax('{$sURI|escape:'htmlall':'UTF-8'}', '{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.googleCatSync.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.googleCatSync.type|escape:'htmlall':'UTF-8'}&iLangId={$aTaxonomy.id_lang|intval}&sLangIso={$sCode|escape:'htmlall':'UTF-8'}', 'bt_google-cat-list', 'bt_google-cat-list', null, null, 'loadingGoogleCatListDiv');"><i class="fa fa-refresh"></i></a>
				{if !empty($aTaxonomy.currentUpdated)}<i class="text-success fa fa-2x fa-check-circle-o"></i>{/if}
			</td>
		</tr>
	{/foreach}
</table>
{literal}
	<script type="text/javascript">
		$("a#handleGoogle").fancybox({
			'hideOnContentClick' : false
		});
	</script>
{/literal}