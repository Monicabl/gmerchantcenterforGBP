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
	{assign var=sep value="\n"}
	{foreach from=$aErrors name=condition key=nKey item=aError}
		{$aError.msg|escape:'htmlall':'UTF-8'}{$sep}
		{if $bDebug == true}
			{if !empty($aError.code)}{l s='Error code' mod='gmerchantcenter'} : {$aError.code|intval}{$sep|escape:'htmlall':'UTF-8'}{/if}
			{if !empty($aError.file)}{l s='Error file' mod='gmerchantcenter'} : {$aError.file|escape:'htmlall':'UTF-8'}{$sep|escape:'htmlall':'UTF-8'}{/if}
			{if !empty($aError.line)}{l s='Error line' mod='gmerchantcenter'} : {$aError.line|intval}{$sep|escape:'htmlall':'UTF-8'}{/if}
			{if !empty($aError.context)}{l s='Error context' mod='gmerchantcenter'} : {$aError.context|escape:'htmlall':'UTF-8'}{$sep|escape:'htmlall':'UTF-8'}{/if}
		{/if}
	{/foreach}
{/if}