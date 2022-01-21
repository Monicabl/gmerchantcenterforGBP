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
{if !empty($bAddJsCss)}
	<script type="text/javascript" src="{$smarty.const._GMC_URL_JS|escape:'htmlall':'UTF-8':"UTF-8"}module.js"></script>
	<link rel="stylesheet" type="text/css" href="{$smarty.const._GMC_URL_CSS|escape:'htmlall':'UTF-8':"UTF-8"}hook.css">
{/if}

<script type="text/javascript">
	// instantiate object
	var {$sModuleName|escape:'htmlall':'UTF-8':"UTF-8"} = {$sModuleName|escape:'htmlall':'UTF-8':"UTF-8"} || new MTModule('{$sModuleName|escape:'htmlall':'UTF-8':"UTF-8"}');

	// get errors translation
	{$sModuleName|escape:'htmlall':'UTF-8':"UTF-8"}.msgs = {$oJsTranslatedMsg};

	// set URL of admin img
	{$sModuleName|escape:'htmlall':'UTF-8':"UTF-8"}.sImgUrl = '{$smarty.const._GMC_URL_IMG|escape:'htmlall':'UTF-8':"UTF-8"}';

	{if !empty($sModuleURI)}
		// set URL of module's web service
		{$sModuleName|escape:'htmlall':'UTF-8':"UTF-8"}.sWebService = '{$sModuleURI|escape:'htmlall':'UTF-8':"UTF-8"}';
	{/if}
</script>