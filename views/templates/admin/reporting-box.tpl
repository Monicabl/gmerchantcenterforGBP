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
<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
{if !empty($aErrors)}
{include file="`$sErrorInclude`"}
{* USE CASE - edition review mode *}
{else}
<div id="bt_facebook-reporting fpa" class="col-xs-12" style="height:860px !important; min-width: 100% !important;" >

<div class="row">
	<div class="col-xs-12">
		<h3>
			{l s='Feed data diagnostic tool' mod='gmerchantcenter'}&nbsp;:&nbsp;
			{l s='language' mod='gmerchantcenter'} "<strong>{$sLangName|escape:'htmlall':'UTF-8'}</strong>" / {l s='country' mod='gmerchantcenter'} "<strong>{$sCountryName|escape:'htmlall':'UTF-8'}</strong>"
			{if isset($iProductCount)}
				{if $iProductCount > 0}
					<p class="col-xs-3 badge badge-success pull-right"> {l s='Total of products exported :' mod='gmerchantcenter'} <strong>{$iProductCount|intval}</strong></p>
				{else}
					<p class="col-xs-3 badge badge-danger pull-right"> {l s='Total of products exported :' mod='gmerchantcenter'} <strong>{$iProductCount|intval}</strong></p>
				{/if}
			{/if}
		</h3>
	</div>
</div>

<div class="clr_hr"></div>
<div class="clr_10"></div>

<div class="col-xs-12">
	<div class="alert alert-success">
		<p>{l s='Data feed is exported.' mod='gmerchantcenter'}</p>
	</div>
</div>

<div class="clr_5"></div>

<div class="alert alert-info">
		<p><strong class="highlight_element">
		{l s='Please read the following FAQ to learn how the diagnostic tool works :' mod='gmerchantcenter'}</strong>
		<a class="badge badge-info" href="{$smarty.const._GMC_BT_FAQ_MAIN_URL|escape:'htmlall':'UTF-8'}faq.php?id=160&lg={$sFaqLang|escape:'htmlall':'UTF-8'}#prod_link" target="_blank"><i class="icon icon-link"></i>&nbsp;{l s='How does the diagnostic tool work ?' mod='gmerchantcenter'}</a></p>
		<br />
		<p>{l s='This tool allows you to display the feed quality diagnostic. The diagnostic catches and shows you any more or less important information about your data, so that you can take them into account and make some corrections to your products or your feed configuration before submitting (again) your feed to Google.' mod='gmerchantcenter'}</p>
		<p>{l s='For each type of notice, we will provide you the ability to see which products are affected as well as how to fix the potential issue.' mod='gmerchantcenter'}</p><br/>
</div>

<div class="clr_10"></div>

<div class="container">

	<div class="row">

		{*USE CASE ERROR BOX*}
		{if !empty($aReport) && !empty($aReport.error)}
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
				<div class="box box-reporting box-reporting-error" >
					<div class="box-icon box-icon-danger">
						<span class="icon icon-exclamation-sign icon-3x"></span>
					</div>
					<div class="info"  style="min-height: 120px;">
						<h4 class="text-center">{l s='ERRORS' mod='gmerchantcenter'}</h4>
						<p class="center" ">{l s='By clicking on \"view details\", you will see products that have generated errors' mod='gmerchantcenter'}</p>
						<p class="center""><strong>{l s='All of these products will NOT be exported in the data feed' mod='gmerchantcenter'}</strong></p>
						<div class="clr_30"></div>
						<div class="center">
							<a id="btn-reporting-error" class="btn btn-lg btn-lg-custom btn-danger">{l s='View details' mod='gmerchantcenter'}</a>
						</div>
					</div>
				</div>
			</div>
		{else}
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
				<div class="box box-reporting box-reporting-success" >
					<div class="box-icon box-icon-success">
						<span class="icon icon-check-circle icon-3x"></span>
					</div>
					<div class="info bt_box_success"  style="min-height: 120px;">
						<p></p>
						<p class="center"><strong>{l s='Good job ! There isn\'t any error' mod='gmerchantcenter'}</strong></p>
						<p class="center bt_success_text"><strong><i class="icon icon-thumbs-up icon-3x"></i> </strong></p>
					</div>
				</div>
			</div>
		{/if}
		{*END USE CASE ERROR BOX*}

		{if !empty($aReport) && !empty($aReport.warning)}
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
				<div class="box  box-reporting box-reporting-warning">
					<div class="box-icon box-icon-warning">
						<span class="icon icon-warning icon-3x"></span>
					</div>
					<div class="info" style="min-height: 120px;">
						<h4 class="text-center">{l s='WARNINGS' mod='gmerchantcenter'}</h4>
						<p class="center" >{l s='By clicking on \"view details\", you will see products that require your attention' mod='gmerchantcenter'}</p>
						<p class="center""><strong>{l s='However, these products will be exported in your data feed' mod='gmerchantcenter'}</strong></p>
						<div class="clr_30"></div>
						<div class="center">
							<a id="btn-reporting-warning" class="btn btn-lg btn-lg-custom btn-warning">{l s='View details' mod='gmerchantcenter'}</a>
						</div>
					</div>
				</div>
			</div>
		{else}
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
				<div class="box  box-reporting box-reporting-success">
					<div class="box-icon box-icon-success">
						<span class="icon icon-warning icon-3x"></span>
					</div>
					<div class="info bt_box_success" style="min-height: 120px;">
						<p></p>
						<p class="center"><strong>{l s='Good job ! There isn\'t any warning' mod='gmerchantcenter'}</strong></p>
						<p class="center bt_success_text"><strong><i class="icon icon-thumbs-up icon-3x"></i> </strong></p>
					</div>
				</div>
			</div>
		{/if}

		{if !empty($aReport) && !empty($aReport.notice)}
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
				<div class="box  box-reporting box-reporting-info">
					<div class="box-icon box-icon-info">
						<span class="icon icon-info icon-3x"></span>
					</div>
					<div class="info" style="min-height: 120px;">
						<h4 class="text-center">{l s='INFORMATION' mod='gmerchantcenter'}</h4>
						<p class="center">{l s='By clicking on \"view details\", you will see the products YOU have decided not to export (according to your module configuration).' mod='gmerchantcenter'}</p>
						<p class="center"><strong>{l s=' As you have decided, all of these products will not be exported in the data feed' mod='gmerchantcenter'}</strong></p>
						
						<div class="clr_30"></div>
						<div class="center">
							<a id="btn-reporting-info" class="btn btn-lg btn-lg-custom btn-info">{l s='View details' mod='gmerchantcenter'}</a>
						</div>
					</div>
				</div>
			</div>
		{else}
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
				<div class="box box-reporting box-reporting-success">
					<div class="box-icon box-icon-success">
						<span class="icon icon-check-circle icon-3x"></span>
					</div>
					<div class="info bt_info_text" style="min-height: 120px;">
						<p class="center"><strong>{l s='No information. In your configuration, you have' mod='gmerchantcenter'}<br />
						{l s='not chosen to exclude any product' mod='gmerchantcenter'}</strong></p>
						<p class="center  bt_success_text"><strong><i class="icon icon-thumbs-up icon-3x"></i> </strong></p>
						
					</div>
				</div>
			</div>
		{/if}
	</div>
</div>

<div id="reporting" class="bt-reporting-details">
	{if !empty($aReport) && !empty($aReport.notice)}
		<div id="btn-reporting-info-box" style="display: none">
			<div class="clr_20"></div>
			<table class="table table-striped">
				<thead>
				<th class="bt_notice col-lg-3">{l s='Type' mod='gmerchantcenter'}</th>
				<th class="bt_notice">{l s='Description' mod='gmerchantcenter'}</th>
				<th class="bt_notice" > {l s='Affected product(s)' mod='gmerchantcenter'}</th>
				<th class="bt_notice" > {l s='Solution' mod='gmerchantcenter'}</th>
				</thead>
				<tbody>
				{foreach from=$aReport.notice item=aTag key=tagName name=report}
					<tr class="bt_reporting_line">
						<td>
							<span class="bt_report_notification">{$aTag.label|escape:'htmlall':'UTF-8'}</span><span class="badge badge-info pull-right">{$aTag.count|intval}</strong>&nbsp;{l s='notice(s)' mod='gmerchantcenter'}{if $aTag.count > 1}s{/if}</span>
						</td>
						<td>
							<p class="summary bt_report_notification"">{$aTag.msg}</p>
						</td>
						<td>
							<span class="bt_report_notification"><a href="#" class="btn btn-sm btn-warning" onclick="$('#tagReport{$tagName|escape:'htmlall':'UTF-8'}').toggle(); return false;"><i class="icon-eye-open"></i> &nbsp;{l s='View affected product(s)' mod='gmerchantcenter'}</a></span>
						</td>
						<td>
							<span class="bt_report_notification"><a class="btn btn-sm btn-info" href="{$sFaqURL|escape:'htmlall':'UTF-8'}{$aTag.faq_id|intval}&lg={$sFaqLang|escape:'htmlall':'UTF-8'}{if !empty($aTag.anchor)}#{$aTag.anchor|escape:'htmlall':'UTF-8'}{/if}" target="_blank"><i class="icon-question-sign"></i>&nbsp; {l s='Learn how to fix this problem' mod='gmerchantcenter'}</a></span>
						</td>
					</tr>
					<tr>
						<td class="bt_notice_products" colspan="5" id="tagReport{$tagName|escape:'htmlall':'UTF-8'}" style="display: none;">
							<table class="table table-striped">
								<thead>
								<th class="bt_reporting_products">{l s='Product ID' mod='gmerchantcenter'}</th>
								<th class="bt_reporting_products">{l s='Product name' mod='gmerchantcenter'}</th>
								<th class="bt_reporting_products">{l s='Actions' mod='gmerchantcenter'}</th>
								</thead>
								<tbody>
								{foreach from=$aTag.data item=aProduct key=key}
									<tr>
										<td class="bt_reporting_products-lines">{$aProduct.productId|intval}</td>
										<td class="bt_reporting_products-lines">{$aProduct.productName|escape:'htmlall':'UTF-8'}</td>
										<td class="bt_reporting_products-lines"><a class="btn btn-sm btn-success" href="{$aProduct.productUrl|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-eye-open"></i> {l s='View product' mod='gmerchantcenter'}</a>
											&nbsp;
											<a class="btn btn-sm btn-success" href="{$sProductLinkController|escape:'htmlall':'UTF-8'}&id_product={$aProduct.productId|intval}{$sProductAction|escape:'htmlall':'UTF-8'}&token={$sToken|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-edit"></i> {l s='Edit' mod='gmerchantcenter'}</a>
										</td>
									</tr>
								{/foreach}
								</tbody>
							</table>
						</td>
					</tr>
				{/foreach}
				</tbody>
			</table>
		</div>
	{/if}

	{if !empty($aReport) && !empty($aReport.error)}
		<div id="btn-reporting-error-box" style="display: none">
			<div class="clr_20"></div>
			<table class="table table-striped">
				<thead>
				<th class="bt_error col-lg-3">{l s='Type' mod='gmerchantcenter'}</th>
				<th class="bt_error">{l s='Description' mod='gmerchantcenter'}</th>
				<th class="bt_error" > {l s='Affected product(s)' mod='gmerchantcenter'}</th>
				<th class="bt_error" > {l s='Solution' mod='gmerchantcenter'}</th>
				</thead>
				<tbody>
				{foreach from=$aReport.error item=aTag key=tagName name=report}
					<tr class="bt_reporting_line">
						<td>
							<span class="bt_report_notification">{$aTag.label|escape:'htmlall':'UTF-8'}</span><span class="badge badge-danger pull-right">{$aTag.count|intval}</strong>&nbsp;{l s='notice(s)' mod='gmerchantcenter'}{if $aTag.count > 1}s{/if}</span>
						</td>
						<td>
							<p class="summary bt_report_notification"">{$aTag.msg}</p>
						</td>
						<td>
							<span class="bt_report_notification"><a href="#" class="btn btn-sm btn-warning" onclick="$('#tagReport{$tagName|escape:'htmlall':'UTF-8'}').toggle(); return false;"><i class="icon-eye-open"></i> &nbsp;{l s='View affected product(s)' mod='gmerchantcenter' }</a></span>
						</td>
						<td>
							<span class="bt_report_notification"><a class="btn btn-sm btn-info" href="{$sFaqURL|escape:'htmlall':'UTF-8'}{$aTag.faq_id|intval}&lg={$sFaqLang|escape:'htmlall':'UTF-8'}{if !empty($aTag.anchor)}#{$aTag.anchor|escape:'htmlall':'UTF-8'}{/if}" target="_blank"><i class="icon-question-sign"></i>&nbsp; {l s='Learn how to fix this problem' mod='gmerchantcenter'}</a></span>
						</td>
					</tr>
					<tr>
						<td class="bt_error_products" colspan="5" id="tagReport{$tagName|escape:'htmlall':'UTF-8'}" style="display: none;">
							<table class="table table-striped">
								<thead>
								<th class="bt_reporting_products">{l s='Product Id' mod='gmerchantcenter'}</th>
								<th class="bt_reporting_products">{l s='Product name' mod='gmerchantcenter'}</th>
								<th class="bt_reporting_products">{l s='Actions' mod='gmerchantcenter'}</th>
								</thead>
								<tbody>
								{foreach from=$aTag.data item=aProduct key=key}
									<tr>
										<td class="bt_reporting_products-lines">{$aProduct.productId|intval}</td>
										<td class="bt_reporting_products-lines">{$aProduct.productName|escape:'htmlall':'UTF-8'}</td>
										<td class="bt_reporting_products-lines"><a class="btn btn-sm btn-success" href="{$aProduct.productUrl|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-eye-open"></i> {l s='View product' mod='gmerchantcenter'}</a>
											&nbsp;
											<a class="btn btn-sm btn-success" href="{$sProductLinkController|escape:'htmlall':'UTF-8'}&id_product={$aProduct.productId|intval}{$sProductAction|escape:'htmlall':'UTF-8'}&token={$sToken|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-edit"></i> {l s='Edit' mod='gmerchantcenter'}</a>
										</td>
									</tr>
								{/foreach}
								</tbody>
							</table>
						</td>
					</tr>
				{/foreach}
				</tbody>
			</table>
		</div>
	{/if}
	{if !empty($aReport) && !empty($aReport.warning)}

		<div id="btn-reporting-warning-box" style="display: none">
			<div class="clr_20"></div>
			<table class="table table-striped">
				<thead>
				<th class="bt_warning col-lg-3">{l s='Type' mod='gmerchantcenter'}</th>
				<th class="bt_warning">{l s='Description' mod='gmerchantcenter'}</th>
				<th class="bt_warning" > {l s='Affected product(s)' mod='gmerchantcenter'}</th>
				<th class="bt_warning" > {l s='Solution' mod='gmerchantcenter'}</th>
				</thead>
				<tbody>
				{foreach from=$aReport.warning item=aTag key=tagName name=report}
					<tr class="bt_reporting_line">
						<td>
							<span class="bt_report_notification">{$aTag.label|escape:'htmlall':'UTF-8'}</span><span class="badge badge-warning pull-right">{$aTag.count|intval}</strong>&nbsp;{l s='notice(s)' mod='gmerchantcenter'}{if $aTag.count > 1}s{/if}</span>
						</td>
						<td>
							<p class="summary bt_report_notification"">{$aTag.msg}</p>
						</td>
						<td>
							<span class="bt_report_notification"><a href="#" class="btn btn-sm btn-warning" onclick="$('#tagReport{$tagName|escape:'htmlall':'UTF-8'}').toggle(); return false;"><i class="icon-eye-open"></i> &nbsp;{l s='View affected product(s)' mod='gmerchantcenter'}</a></span>
						</td>
						<td>
							<span class="bt_report_notification"><a class="btn btn-sm btn-info" href="{$sFaqURL|escape:'htmlall':'UTF-8'}{$aTag.faq_id|intval}&lg={$sFaqLang|escape:'htmlall':'UTF-8'}{if !empty($aTag.anchor)}#{$aTag.anchor|escape:'htmlall':'UTF-8'}{/if}" target="_blank"><i class="icon-question-sign"></i>&nbsp; {l s='Learn how to fix this problem' mod='gmerchantcenter'}</a></span>
						</td>
					</tr>
					<tr>
						<td class="bt_warning_products" colspan="5" id="tagReport{$tagName|escape:'htmlall':'UTF-8'}" style="display: none;">
							<table class="table table-striped">
								<thead>
								<th class="bt_reporting_products">{l s='Product Id' mod='gmerchantcenter'}</th>
								<th class="bt_reporting_products">{l s='Product name' mod='gmerchantcenter'}</th>
								<th class="bt_reporting_products">{l s='Actions' mod='gmerchantcenter'}</th>
								</thead>
								<tbody>
								{foreach from=$aTag.data item=aProduct key=key}
									<tr>
										<td class="bt_reporting_products-lines">{$aProduct.productId|intval}</td>
										<td class="bt_reporting_products-lines">{$aProduct.productName|escape:'htmlall':'UTF-8'}</td>
										<td class="bt_reporting_products-lines"><a class="btn btn-sm btn-success" href="{$aProduct.productUrl|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-eye-open"></i> {l s='View product' mod='gmerchantcenter'}</a>
											&nbsp;
											<a class="btn btn-sm btn-success" href="{$sProductLinkController|escape:'htmlall':'UTF-8'}&id_product={$aProduct.productId|intval}{$sProductAction|escape:'htmlall':'UTF-8'}&token={$sToken|escape:'htmlall':'UTF-8'}" target="_blank"><i class="icon icon-edit"></i> {l s='Edit' mod='gmerchantcenter'}</a>
										</td>
									</tr>
								{/foreach}
								</tbody>
							</table>
						</td>
					</tr>
				{/foreach}
				</tbody>
			</table>
		</div>
	{/if}

	<div class="clr_20"></div>

	<p style="text-align: center !important;">
		<button class="btn btn-danger btn-lg" value="{l s='Close' mod='gmerchantcenter'}"  onclick="$.fancybox.close();return false;">{l s='Close' mod='gmerchantcenter'}</button>
	</p>
</div>
{/if}
</div>

{literal}
	<script type="text/javascript">

		$( document ).ready(function() {
			{/literal}
				{if !empty($aReport.error)}
					$('#btn-reporting-error-box').css('display','block');
					$(".box-reporting-error").css( 'border', '2px solid #da7b82');
					$("#btn-reporting-error").css( 'background-color', '#a94442');
					$("#btn-reporting-error").css( 'text-decoration', 'underline');
				{/if}
			{literal}

			{/literal}
				{if empty($aReport.error) && !empty($aReport.warning)}
					$('#btn-reporting-warning-box').css('display','block');
					$(".box-reporting-warning").css( 'border', '2px solid #fbb309');
					$("#btn-reporting-warning").css( 'background-color', '#f0ad4e');
					$("#btn-reporting-warning").css( 'text-decoration', 'underline');
				{/if}
			{literal}
			{/literal}
				{if empty($aReport.error) && empty($aReport.warning) && !empty($aReport.notice)}
					$('#btn-reporting-notice-box').css('display','block');
					$(".box-reporting-info").css( 'border', '2px solid #4ac7e0');
					$("#btn-reporting-info").css( 'background-color', '#21a6c1');
					$("#btn-reporting-info").css( 'text-decoration', 'underline');
				{/if}
			{literal}
		// This code manages the event click and color display on the reporting

		// Manage the click event on reporting block error
		$("#btn-reporting-error").click(function() {
			$("#btn-reporting-warning-box").slideUp();
			$("#btn-reporting-info-box").slideUp();
			$("#btn-reporting-error-box").slideDown();

			// Bloc for error
			$(".box-reporting-error").css( 'border', '2px solid #da7b82');
			$("#btn-reporting-error").css( 'background-color', '#a94442');
			$("#btn-reporting-error").css( 'text-decoration', 'underline');

			// Bloc for notice
			$(".box-reporting-info").css( 'border', '2px solid #CCCED7');
			$("#btn-reporting-info").css( 'background-color', '#4ac7e0');
			$("#btn-reporting-info").css( 'text-decoration', 'none');

			// bloc for warning
			$(".box-reporting-warning").css( 'border', '2px solid #CCCED7');
			$("#btn-reporting-warning").css( 'background-color', '#fcc94f');
			$("#btn-reporting-warning").css( 'text-decoration', 'none');

		});

		// Manage the click event on reporting block info
		$("#btn-reporting-info").click(function() {
			$("#btn-reporting-warning-box").slideUp();
			$("#btn-reporting-error-box").slideUp();
			$("#btn-reporting-info-box").slideDown();

			// Bloc for error
			$(".box-reporting-error").css( 'border', '2px solid #CCCED7');
			$("#btn-reporting-error").css( 'background-color', '#da7b82');
			$("#btn-reporting-error").css( 'text-decoration', 'none');

			// Bloc for notice
			$(".box-reporting-info").css( 'border', '2px solid #4ac7e0');
			$("#btn-reporting-info").css( 'background-color', '#21a6c1');
			$("#btn-reporting-info").css( 'text-decoration', 'underline');

			// bloc for warning
			$(".box-reporting-warning").css( 'border', '2px solid #CCCED7');
			$("#btn-reporting-warning").css( 'background-color', '#fcc94f');
			$("#btn-reporting-warning").css( 'text-decoration', 'none');

		});

		// Manage the click event on reporting block warning
		$("#btn-reporting-warning").click(function() {
			$("#btn-reporting-error-box").slideUp();
			$("#btn-reporting-info-box").slideUp();
			$("#btn-reporting-warning-box").slideDown();

			// Bloc for error
			$(".box-reporting-error").css( 'border', '2px solid #CCCED7');
			$("#btn-reporting-error").css( 'background-color', '#da7b82');
			$("#btn-reporting-error").css( 'text-decoration', 'none');

			// Bloc for notice
			$(".box-reporting-info").css( 'border', '2px solid #CCCED7');
			$("#btn-reporting-info").css( 'background-color', '#4ac7e0');
			$("#btn-reporting-info").css( 'text-decoration', 'none');

			//bloc for warning
			$(".box-reporting-warning").css( 'border', '2px solid #fbb309');
			$("#btn-reporting-warning").css( 'background-color', '#f0ad4e');
			$("#btn-reporting-warning").css( 'text-decoration', 'underline');
		});
	});
	</script>
{/literal}
