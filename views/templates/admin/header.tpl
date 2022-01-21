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
<link rel="stylesheet" type="text/css" href="{$smarty.const._GMC_URL_CSS|escape:'htmlall':'UTF-8'}admin.css">
<link rel="stylesheet" type="text/css" href="{$smarty.const._GMC_URL_CSS|escape:'htmlall':'UTF-8'}top.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


{* USE CASE - load CSS and JS when PS version is under 1.6 *}
{if empty($bCompare16)}
	<link rel="stylesheet" type="text/css" href="{$smarty.const._GMC_URL_CSS|escape:'htmlall':'UTF-8'}admin-theme.css">
	<link rel="stylesheet" type="text/css" href="{$smarty.const._GMC_URL_CSS|escape:'htmlall':'UTF-8'}admin-15.css">
	<link rel="stylesheet" type="text/css" href="{$smarty.const._GMC_URL_CSS|escape:'htmlall':'UTF-8'}bootstrap-theme.min.css">
	<script type="text/javascript" src="{$smarty.const._GMC_URL_JS|escape:'htmlall':'UTF-8'}bootstrap.min.js"></script>
{/if}
<script type="text/javascript" src="{$autocmp_js|escape:'htmlall':'UTF-8'}"></script>
<link rel="stylesheet" type="text/css" href="{$autocmp_css|escape:'htmlall':'UTF-8'}" />
<script type="text/javascript" src="{$smarty.const._GMC_URL_JS|escape:'htmlall':'UTF-8'}module.js"></script>
<script type="text/javascript" src="{$smarty.const._GMC_URL_JS|escape:'htmlall':'UTF-8'}top.js"></script>
<script type="text/javascript" src="{$smarty.const._GMC_URL_JS|escape:'htmlall':'UTF-8'}feedList.js"></script>

<script type="text/javascript">
	// instantiate object
	var oGmc = oGmc || new Gmc('{$sModuleName|escape:'htmlall':'UTF-8'}');
	var oGmcFeedList = oGmcFeedList || new GmcFeedList('{$sModuleName|escape:'htmlall':'UTF-8'}');
	var oBtUpdateStep = oBtUpdateStep || new btHeaderBar('{$sModuleName|escape:'htmlall':'UTF-8'}');

	// get errors translation
	oGmc.msgs = {$oJsTranslatedMsg};

	// set URL of admin img
	oGmc.sImgUrl = '{$smarty.const._GMC_URL_IMG|escape:'htmlall':'UTF-8'}';

	{if !empty($sModuleURI)}
	// set URL of module's web service
	oGmc.sWebService = '{$sModuleURI|escape:'htmlall':'UTF-8'}';
	{/if}
</script>


