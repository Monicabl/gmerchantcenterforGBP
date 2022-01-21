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
<div id="header_bar" class="row bg-white">
    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="row">
            <div class="col-xs-3">
                <img  class="img-responsive" src="{$smarty.const._GMC_URL_IMG|escape:'htmlall':'UTF-8'}admin/logo.png" height="70" width="70" alt="" />
            </div>
            <div class="col-xs-6">
                <img class="img-responsive" src="{$smarty.const._GMC_URL_IMG|escape:'htmlall':'UTF-8'}admin/bt_logo.jpg" width="100px" alt="" />
            </div>

        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="text-center">
            <div id="step-by-step" class="row bs-wizard text-center" style="border-bottom:0;">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 bs-wizard-step step-1 {if empty($bConfigureStep1)}disabled{else}complete{/if} text-center">
                    <div class="text-center bs-wizard-stepnum">{l s='1 - Basic configuration' mod='gmerchantcenter'}</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="clr_5"></div>
                    <div class="workTabs">
                        {if empty($bConfigureStep1)}
                            <a class="btn btn-sm btn-warning btn-step-1" id="tab-2" ><i class="fa fa-cog"></i> {l s='Configure' mod='gmerchantcenter'} </a>
                        {/if}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 bs-wizard-step step-2 {if empty($bConfigureStep2)}disabled{else}complete{/if} text-center">
                    <div class="text-center bs-wizard-stepnum">{l s='2 - Data feed configuration' mod='gmerchantcenter'}</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="clr_5"></div>
                    <div class="workTabs">
                        {if empty($bConfigureStep2)}
                            <a class="btn btn-sm btn-warning btn-step-2" id="tab-001" ><i class="fa fa-cog"></i> {l s='Configure' mod='gmerchantcenter'} </a>
                        {/if}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 bs-wizard-step step-3 {if empty($bConfigureStep3)}disabled{else}complete{/if} text-center">
                    <div class="text-center bs-wizard-stepnum">{l s='3 - Import data feed' mod='gmerchantcenter'}</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="clr_5"></div>
                    <div class="workTabs">
                        {if empty($bConfigureStep3)}
                            <a class="fancybox.ajax btn btn-sm btn-warning btn-step-3 bt_add-feed" href="{$sURI}&{$sCtrlParamName}={$sController}&sAction={$aQueryParams.stepPopup.action}&sType={$aQueryParams.stepPopup.type}"  id="tab-3"><i class="fa fa-cog"></i> {l s='Configure' mod='gmerchantcenter'} </a>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
        <a class="btn btn-info btn-lg col-xs-12" target="_blank" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}/{$sFaqLang|escape:'htmlall':'UTF-8'}/product/43"><span class="fa fa-2x fa-question-circle"></span><br/>{l s='Online FAQ' mod='gmerchantcenter'}</a>
    </div>

</div>


<script type="text/javascript">
	$("a.bt_add-feed").fancybox({
		'hideOnContentClick' : false
	});
</script>
