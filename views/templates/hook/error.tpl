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
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">×</button>
	{foreach from=$aErrors name=condition key=nKey item=aError}
	<h3>{$aError.msg|escape:'htmlall':'UTF-8':"UTF-8"}</h3>
	{if $bDebug == true}
	<ol>
		{if !empty($aError.code)}<li>{l s='Error code' mod='gmerchantcenter'} : {$aError.code|intval}</li>{/if}
		{if !empty($aError.file)}<li>{l s='Error file' mod='gmerchantcenter'} : {$aError.file|escape:'htmlall':'UTF-8':"UTF-8"}</li>{/if}
		{if !empty($aError.line)}<li>{l s='Error line' mod='gmerchantcenter'} : {$aError.line|intval}</li>{/if}
		{if !empty($aError.context)}<li>{l s='Error context' mod='gmerchantcenter'} : {$aError.context|escape:'htmlall':'UTF-8':"UTF-8"}</li>{/if}
	</ol>
	{/if}
	{/foreach}
</div>