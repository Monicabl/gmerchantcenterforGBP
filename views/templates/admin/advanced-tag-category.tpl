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
	<div id="bt_advanced-tag" class="col-xs-12 bt_adwords">
		<h3>{l s='Tags assignation for each products category' mod='gmerchantcenter'}</h3>

		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="alert alert-warning">
			{l s='WARNING : before starting, please note that the categories displayed below are the DEFAULT categories of your products. The product default category is the one you indicate in the \"Associations\" tab of the back office product sheet (in the \"Default category\" field). So, make sure that your products are correctly assigned to the right default category.' mod='gmerchantcenter'}
		</div>
		<div class="alert alert-info">
			<div class="form-group">
				<label class="col-xs-2">
					<b>{l s='Select which type of tags you want to set :' mod='gmerchantcenter'}</b>
				</label>
				
					<div class="col-xs-3">
						<select class="set_tag" name="set_tag" id="set_tag">
							<option value="0">---</option>
							{if !empty($bMaterial)}
								<option value="material">{l s='Set product material tags' mod='gmerchantcenter'}</option>
							{/if}
							{if !empty($bPattern)}
								<option value="pattern">{l s='Set product pattern tags' mod='gmerchantcenter'}</option>
							{/if}
							{if !empty($bGender)}
								<option value="gender">{l s='Set product gender tags' mod='gmerchantcenter'}</option>
							{/if}
							{if !empty($bAgeGroup)}
								<option value="agegroup">{l s='Set product age group tags' mod='gmerchantcenter'}</option>
							{/if}
							{if !empty($bTagAdult)}
								<option value="adult">{l s='Set product for adults only tags' mod='gmerchantcenter'}</option>
							{/if}
							{if !empty($bSizeType)}
								<option value="sizeType">{l s='Set product size type tags' mod='gmerchantcenter'}</option>
							{/if}
							{if !empty($bSizeSystem)}
								<option value="sizeSystem">{l s='Set product size system tags' mod='gmerchantcenter'}</option>
							{/if}
							{if !empty($bExcludedDest)}
								<option value="excluded_destination">{l s='Set excluded destination tags' mod='gmerchantcenter'}</option>
							{/if}
							{if !empty($bExcludedCountry)}
								<option value="excluded_country">{l s='Set excluded country tags' mod='gmerchantcenter'}</option>
							{/if}
						</select>
					</div>
			</div>
			<div class="clr_5"></div>
		</div>
		
		<div class="bulk-actions">
			<table class="table">
				<tr id="bulk_action_material">
					<td class="label_tag_categories">{l s='Set MATERIAL tags : for each product default category, if available, you will have to indicate the feature that defines the material of the products that are in this category. Features are those you have created in the \"Product features\" tab of your products catalog (see the left menu of your PrestaShop back office).' mod='gmerchantcenter'}</td>
					<td>
						<select name="set_material_bulk_action" class="set_material_bulk_action">
							{foreach from=$aFeatures item=feature}
								<option value="{$feature.id_feature|intval}">{$feature.name|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</select>
					</td>
					<td><span class="btn btn-default" onclick="oGmc.doSet('.material', $('.set_material_bulk_action').val());">{l s='Set for all categories' mod='gmerchantcenter'}</span> - <span class="btn btn-default" onclick="oGmc.doSet('.material', 0);">{l s='Reset' mod='gmerchantcenter'}</td>
				</tr>
				<tr id="bulk_action_pattern">
					<td class="label_tag_categories col-xs-6">{l s='Set PATTERN tags : for each product default category, if available, you will have to indicate the feature that defines the pattern of the products that are in this category. Features are those you have created in the \"Product features\" tab of your products catalog (see the left menu of your PrestaShop back office).' mod='gmerchantcenter'}</td>
					<td>
						<select name="set_pattern_bulk_action" class="set_pattern_bulk_action">
							{foreach from=$aFeatures item=feature}
								<option value="{$feature.id_feature|intval}">{$feature.name|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</select>
					</td>
					<td><span class="btn btn-default" onclick="oGmc.doSet('.pattern', $('.set_pattern_bulk_action').val());">{l s='Set for all categories' mod='gmerchantcenter'}</span> - <span class="btn btn-default" onclick="oGmc.doSet('.pattern', 0);">{l s='Reset' mod='gmerchantcenter'}</span></td>
				</tr>
				<tr id="bulk_action_adult">
					<td class="label_tag_categories">{l s='Set AGE GROUP tags : for each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"age group\" value defines the age group for which the products of this category are reserved. To assign the same tag to all categories, click on one of the opposite buttons  -------->' mod='gmerchantcenter'}</td>
					<td>
						<span class="btn btn-default" onclick="oGmc.doSet('.agegroup', 'adult');">{l s='Adults (>13y.o)' mod='gmerchantcenter'} </span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.agegroup', 'kids');">{l s='Kids (5-13y.o)' mod='gmerchantcenter'} </span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.agegroup', 'toddler');">{l s='Toddlers (1-5y.o)' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.agegroup', 'infant');">{l s='Infants (3-12m.o)' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default btn-special" onclick="oGmc.doSet('.agegroup', 'newborn');">{l s='Newborns (<3m.o) ' mod='gmerchantcenter'}</span>
						
						- <span class="btn btn-default" onclick="oGmc.doSet('.agegroup', 0);">{l s='Reset' mod='gmerchantcenter'}</span>
					</td>
				</tr>
				<tr id="bulk_action_gender">
					<td class="label_tag_categories col-xs-6"> {l s='Set GENDER tags : for each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"gender\" value defines the gender for which the products of this category are reserved. To assign the same tag to all categories, click on one of the opposite buttons  -------->' mod='gmerchantcenter'}</td>
					<td><span class="btn btn-default" onclick="oGmc.doSet('.gender', 'male');">{l s='Men (male)' mod='gmerchantcenter'} </span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.gender', 'female');">{l s='Women (female)' mod='gmerchantcenter'} </span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.gender', 'unisex');">{l s='Unisex' mod='gmerchantcenter'} </span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.gender', 0);">{l s='Reset' mod='gmerchantcenter'}</span>
					</td>
				</tr>
				<tr id="bulk_action_tagadult">
					<td class="label_tag_categories" >{l s='Set ADULT tags : for each product default category, if the products of the category are for adult only, select the \"true\" value in the drop and down menu.' mod='gmerchantcenter'}</td>
					<td>
					<span class="btn btn-default" onclick="oGmc.doSet('.adult', 'true');">{l s='Set for all categories' mod='gmerchantcenter'}</span>
					- <span class="btn btn-default" onclick="oGmc.doSet('.adult', 0);">{l s='Reset' mod='gmerchantcenter'}</span></td>
				</tr>
				<tr id="bulk_action_sizeType">
					<td class="label_tag_categories col-xs-6">{l s='Set SIZE TYPE tags : for each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"size type\" value defines the size type of the products that are in this category. To assign the same tag to all categories, click on one of the opposite buttons  -------->' mod='gmerchantcenter'}</td>
					<td><span class="btn btn-default" onclick="oGmc.doSet('.sizeType', 'maternity');">{l s='Maternity' mod='gmerchantcenter'} </span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeType', 'oversize');">{l s='Oversize' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeType', 'petite');">{l s='Petite' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeType', 'regular');">{l s='Regular' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeType', 0);">{l s='Reset' mod='gmerchantcenter'}</span>
					</td>
				</tr>
				<tr id="bulk_action_sizeSystem">
					<td class="label_tag_categories col-xs-6">{l s='Set SIZE SYSTEM tags : for each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"size system\" value defines the size system of the products that are in this category. To assign the same tag to all categories, click on one of the opposite buttons  -------->' mod='gmerchantcenter'}</td>
					<td><span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'US');">{l s='US' mod='gmerchantcenter'} </span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'UK');">{l s='UK' mod='gmerchantcenter'} </span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'UE');">{l s='UE' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'DE');">{l s='DE' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'FR');">{l s='FR' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'JP');">{l s='JP' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'CN');">{l s='CN' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'IT');">{l s='IT' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'BR');">{l s='BR' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'MEX');">{l s='MEX' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 'AU');">{l s='AU' mod='gmerchantcenter'}</span>
						- <span class="btn btn-default" onclick="oGmc.doSet('.sizeSystem', 0);">{l s='Reset' mod='gmerchantcenter'}</span>
					</td>
				</tr>
				<tr id="bulk_action_excluded_destination">
						<td class="label_tag_categories_value col-xs-6">{l s='Set EXCLUDED DESTINATION tags: for each product default category, select in the drop and down menu the advertising channel on which you DO NOT want products of this category to be displayed. You can select several channels by holding down the CTRL (or CMD) key. To assign the same tag to all categories, click on one of the opposite buttons -------->' mod='gmerchantcenter'}</td>			
							<td>
								<select multiple name="set_excluded_destination_bulk_action" class="set_excluded_destination_bulk_action">
									<option value="">{l s='--' mod='gmerchantcenter'}</option>
									<option value="shopping">{l s='Shopping Ads' mod='gmerchantcenter'}</option>
									<option value="actions">{l s='Buy on Google listing' mod='gmerchantcenter'}</option>
									<option value="display">{l s='Display Ads' mod='gmerchantcenter'}</option>
									<option value="local">{l s='Local inventory ads' mod='gmerchantcenter'}</option>
									<option value="free-listing">{l s='Free listings' mod='gmerchantcenter'}</option>
									<option value="free-local-listing">{l s='Free local listings' mod='gmerchantcenter'}</option>
							</select>
							<td>
						<td><span class="btn btn-default" onclick="oGmc.doSet('.excluded_destination', $('.set_excluded_destination_bulk_action').val());">{l s='Set for all categories' mod='gmerchantcenter'}</span> - <span class="btn btn-default" onclick="oGmc.doSet('.excluded_destination', '');">{l s='Reset' mod='gmerchantcenter'}</span></td>
					</tr>

				<tr id="bulk_action_excluded_country">
					<td class="label_tag_categories_value col-xs-6">{l s='Set EXCLUDED COUNTRY tags: for each product default category, select in the drop and down menu the acountry on which you DO NOT want products of this category to be displayed. You can select several channels by holding down the CTRL (or CMD) key. To assign the same tag to all categories, click on one of the opposite buttons -------->' mod='gmerchantcenter'}</td>			
						<td>
							<select multiple name="set_excluded_country_bulk_action" class="set_excluded_country_bulk_action">
								{foreach from=$aCountries item=country}
									<option value="{$country|escape:'htmlall':'UTF-8'}">{$country|escape:'htmlall':'UTF-8'}</option>
								{/foreach}
							</select>
						<td>
					<td><span class="btn btn-default" onclick="oGmc.doSet('.excluded_country', $('.set_excluded_country_bulk_action').val());">{l s='Set for all categories' mod='gmerchantcenter'}</span> - <span class="btn btn-default" onclick="oGmc.doSet('.excluded_country', '');">{l s='Reset' mod='gmerchantcenter'}</span></td>
				</tr>
			</table>
		</div>
		<form class="form-horizontal" method="post" id="bt_form-advanced-tag" name="bt_form-advanced-tag" {if $smarty.const._GSR_USE_JS == true}onsubmit="oGmc.form('bt_form-advanced-tag', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_advanced-tag', 'bt_advanced-tag', false, true, null, 'AdvancedTag', 'loadingAdvancedTagDiv');return false;"{/if}>
			<input type="hidden" name="{$sCtrlParamName}" value="{$sController|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sAction" value="{$aQueryParams.tagUpdate.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.tagUpdate.type|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sUseTag" value="{$sUseTag|escape:'htmlall':'UTF-8'}" id="default_tag" />
			<table class="table table-responsive">
				<thead>
					<th class="bt_tr_header text-center"><b>{l s='Shop category' mod='gmerchantcenter'}</b></th>
					<th class="bt_tr_header text-center"><b>{l s='Tag' mod='gmerchantcenter'}</b></th>
				</thead>
				{foreach from=$aShopCategories item=cat}
					<tr>
						<td class="label_tag_categories">{l s='Shop category' mod='gmerchantcenter'} : {$cat.path}</td>
						<td>
							<div class="value_material">
								{l s='Material :' mod='gmerchantcenter'}
								<select name="material[{$cat.id_category|intval}]" class="material" >
									<option value="0">-----</option>
									{foreach from=$aFeatures item=feature}
										<option value="{$feature.id_feature|intval}" {if $cat.material == $feature.id_feature} selected {/if}>{$feature.name|escape:'htmlall':'UTF-8'}</option>
									{/foreach}
								</select>
							</div>
							<div class="value_pattern">
								{l s='Pattern :' mod='gmerchantcenter'}
								<select name="pattern[{$cat.id_category|intval}]" class="pattern" >
									<option value="0">-----</option>
									{foreach from=$aFeatures item=feature}
										<option value="{$feature.id_feature|intval}" {if $cat.pattern == $feature.id_feature} selected {/if}>{$feature.name|escape:'htmlall':'UTF-8'}</option>
									{/foreach}
								</select>
							</div>
							<div class="value_agegroup">
								{l s='Age group :' mod='gmerchantcenter'}


								<select class="agegroup" name="agegroup[{$cat.id_category|intval}]" id="agegroup{$cat.id_category|intval}">
									<option value="0"{if $cat.agegroup=="0"} selected{/if}>--</option>
									<option value="adult"{if $cat.agegroup=="adult"} selected{/if}>{l s='Adults (>13y.o)' mod='gmerchantcenter'}</option>
									<option value="kids"{if $cat.agegroup=="kids"} selected{/if}>{l s='Kids (5-13y.o)' mod='gmerchantcenter'}</option>
									<option value="toddler"{if $cat.agegroup=="toddler"} selected{/if}>{l s='Toddlers (1-5y.o)' mod='gmerchantcenter'}</option>
									<option value="infant"{if $cat.agegroup=="infant"} selected{/if}>{l s='Infants (3-12m.o)' mod='gmerchantcenter'}</option>
									<option value="newborn"{if $cat.agegroup=="newborn"} selected{/if}>{l s='Newborns (<3m.o) ' mod='gmerchantcenter'}</option>
								</select>
							</div>
							<div class="value_gender">
								{l s='Gender :' mod='gmerchantcenter'}
								<select class="gender" name="gender[{$cat.id_category|intval}]" id="gender{$cat.id_category|intval}">
									<option value="0"{if $cat.gender=="0"} selected{/if}>--</option>
									<option value="male"{if $cat.gender=="male"} selected{/if}>{l s='Men (male)' mod='gmerchantcenter'}</option>
									<option value="female"{if $cat.gender=="female"} selected{/if}>{l s='Women (female)' mod='gmerchantcenter'}</option>
									<option value="unisex"{if $cat.gender=="unisex"} selected{/if}>{l s='Unisex' mod='gmerchantcenter'}</option>
								</select>
							</div>
							<div class="value_tagadult">
								{l s='Tag product for adults only :' mod='gmerchantcenter'}
								<select class="adult" name="adult[{$cat.id_category|intval}]" id="adult{$cat.id_category|intval}">
									<option value="0"{if $cat.adult=="0"} selected{/if}>--</option>
									<option value="true"{if $cat.adult=="true"} selected{/if}>true</option>
								</select>
							</div>
							<div class="value_sizeType">
								{l s='Size type :' mod='gmerchantcenter'}
								<select class="sizeType" name="sizeType[{$cat.id_category|intval}]" id="sizeType{$cat.id_category|intval}">
									<option value="0"{if $cat.sizeType=="0"} selected{/if}>--</option>
									<option value="regular"{if $cat.sizeType=="regular"} selected{/if}>{l s='Regular' mod='gmerchantcenter'}</option>
									<option value="petite"{if $cat.sizeType=="petite"} selected{/if}>{l s='Petite' mod='gmerchantcenter'}</option>
									<option value="plus"{if $cat.sizeType=="oversize"} selected{/if}>{l s='Oversize' mod='gmerchantcenter'}</option>
									<option value="maternity"{if $cat.sizeType=="maternity"} selected{/if}>{l s='Maternity' mod='gmerchantcenter'}</option>
								</select>
							</div>
							<div class="value_sizeSystem">
								{l s='Size system :' mod='gmerchantcenter'}
								<select class="sizeSystem" name="sizeSystem[{$cat.id_category|intval}]" id="sizeSystem{$cat.id_category|intval}">
									<option value="0"{if $cat.sizeSystem=="0"} selected{/if}>--</option>
									<option value="US"{if $cat.sizeSystem=="US"} selected{/if}>US</option>
									<option value="UK"{if $cat.sizeSystem=="UK"} selected{/if}>UK</option>
									<option value="EU"{if $cat.sizeSystem=="EU"} selected{/if}>EU</option>
									<option value="DE"{if $cat.sizeSystem=="DE"} selected{/if}>DE</option>
									<option value="FR"{if $cat.sizeSystem=="FR"} selected{/if}>FR</option>
									<option value="JP"{if $cat.sizeSystem=="JP"} selected{/if}>JP</option>
									<option value="CN"{if $cat.sizeSystem=="CN"} selected{/if}>CN</option>
									<option value="IT"{if $cat.sizeSystem=="IT"} selected{/if}>IT</option>
									<option value="BR"{if $cat.sizeSystem=="BR"} selected{/if}>BR</option>
									<option value="MEX"{if $cat.sizeSystem=="MEX"} selected{/if}>MEX</option>
									<option value="AU"{if $cat.sizeSystem=="AU"} selected{/if}>AU</option>
								</select>
							</div>

							<div class="value_excluded_destination">
								<select multiple name="excluded_destination[{$cat.id_category|intval}][]" class="excluded_destination">
									<option value="">{l s='--' mod='gmerchantcenterpro'}</option>
									<option {if in_array('shopping', $cat.excluded_destination)} selected {/if} value="shopping">{l s='Shopping Ads' mod='gmerchantcenter'}</option>
									<option {if in_array('actions', $cat.excluded_destination)} selected {/if} value="actions">{l s='Buy on Google listing' mod='gmerchantcenter'}</option>
									<option {if in_array('display', $cat.excluded_destination)} selected {/if} value="display">{l s='Display Ads' mod='gmerchantcenter'}</option>
									<option {if in_array('local', $cat.excluded_destination)} selected {/if} value="local">{l s='Local inventory ads' mod='gmerchantcenter'}</option>
									<option {if in_array('free-listing', $cat.excluded_destination)} selected {/if} value="free-listing">{l s='Free listings' mod='gmerchantcenter'}</option>
									<option {if in_array('free-local-listing', $cat.excluded_destination)} selected {/if} value="free-local-listing">{l s='Free local listings' mod='gmerchantcenter'}</option>
								</select>
							</div>

							<div class="value_excluded_country">
								<select multiple name="excluded_country[{$cat.id_category|intval}][]" class="excluded_country">
									{foreach from=$aCountries item=country}
										<option value="{$country|escape:'htmlall':'UTF-8'}" {if in_array($country, $cat.excluded_country)} selected {/if}> {$country|escape:'htmlall':'UTF-8'}</option>
									{/foreach}
								</select>
							<div>
						</td>
					</tr>
				{/foreach}
			</table>
			<p style="text-align: center !important;">
				{if $smarty.const._GMC_USE_JS == true}
					<script type="text/javascript">
						{literal}
						var oAdvancedCallback = [{}];
						{/literal}
					</script>
					<input type="button" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='gmerchantcenter'}" onclick="oGmc.form('bt_form-advanced-tag', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_advanced-tag', 'bt_advanced-tag', false, true, oAdvancedCallback, 'AdvancedTag', 'loadingAdvancedTagDiv');return false;" />
				{else}
					<input type="submit" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='gmerchantcenter'}" />
				{/if}
				<button class="btn btn-danger btn-lg" value="{l s='Cancel' mod='gmerchantcenter'}"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='gmerchantcenter'}</button>
			</p>
		</form>
		{literal}
		<script type="text/javascript">
			function handleOptionToDisplay(sTagType) {
				// initialize the list of elt to show and hide
				var aShow = [];
				var aHide = [];

				switch (sTagType) {
					case 'material':
						oGmc.doSet('#set_tag', 'material');
						aShow = ['#bulk_action_material', '.value_material'];
						aHide = [
						'#bulk_action_pattern', '#bulk_action_adult', 
						'#bulk_action_gender', '#bulk_action_tagadult', 
						'.value_pattern', '.value_agegroup', 
						'.value_gender', '.value_tagadult', 
						'#bulk_action_sizeType', '.value_sizeType',
						'#bulk_action_sizeSystem', 
						'.value_sizeSystem', 
						'#bulk_action_excluded_destination', '.value_excluded_destination',
						'#bulk_action_excluded_country', '.value_excluded_country' 
						 ];
						break;
					case 'pattern':
						oGmc.doSet('#set_tag', 'pattern');
						aShow = ['#bulk_action_pattern', '.value_pattern'];
						aHide = [
							'#bulk_action_material', '#bulk_action_adult',
							'#bulk_action_gender', '#bulk_action_tagadult',
							'.value_material', '.value_agegroup',
							'.value_gender', '.value_tagadult', 
							'#bulk_action_sizeType', '.value_sizeType',
							'#bulk_action_sizeSystem', '.value_sizeSystem',
							'#bulk_action_excluded_destination', '.value_excluded_destination',
							'#bulk_action_excluded_country', '.value_excluded_country' 
						];
						break;
					case 'agegroup':
						oGmc.doSet('#set_tag', 'agegroup');
						aShow = ['#bulk_action_adult', '.value_agegroup'];
						aHide = [
							'#bulk_action_material', '#bulk_action_pattern',
							'#bulk_action_gender', '#bulk_action_tagadult', 
							'.value_material', '.value_pattern', '.value_gender', 
							'.value_tagadult', '#bulk_action_sizeType', '.value_sizeType', 
							'#bulk_action_sizeSystem', '.value_sizeSystem', 
							'#bulk_action_excluded_destination', '.value_excluded_destination',
							'#bulk_action_excluded_country', '.value_excluded_country' 
						];
						break;
					case 'gender':
						oGmc.doSet('#set_tag', 'gender');
						aShow = ['#bulk_action_gender', '.value_gender'];
						aHide = [
							'#bulk_action_material', '#bulk_action_pattern', 
							'#bulk_action_adult', '#bulk_action_tagadult', 
							'.value_material', '.value_pattern', 
							'.value_agegroup', '.value_tagadult', 
							'#bulk_action_sizeType', '.value_sizeType', 
							'#bulk_action_sizeSystem', '.value_sizeSystem', 
							'#bulk_action_excluded_destination', '.value_excluded_destination',
							'#bulk_action_excluded_country', '.value_excluded_country'
						];
						break;
					case 'adult':
						oGmc.doSet('#set_tag', 'adult');
						aShow = ['#bulk_action_tagadult', '.value_tagadult'];
						aHide = [
							'#bulk_action_material', '#bulk_action_pattern', 
							'#bulk_action_adult', '#bulk_action_gender', 
							'.value_material', '.value_pattern', '.value_agegroup', 
							'.value_gender', '#bulk_action_sizeType', '.value_sizeType', 
							'#bulk_action_sizeSystem', '.value_sizeSystem', 
							'#bulk_action_excluded_destination', '.value_excluded_destination',
							'#bulk_action_excluded_country', '.value_excluded_country'
						];
						break;
					case 'sizeType':
						oGmc.doSet('#set_tag', 'sizeType');
						aShow = ['#bulk_action_sizeType', '.value_sizeType'];
						aHide = [
							'#bulk_action_material', '#bulk_action_pattern', 
							'#bulk_action_adult', '#bulk_action_gender', 
							'.value_material', '.value_pattern', 
							'.value_agegroup', '.value_gender', 
							'#bulk_action_tagadult', '.value_tagadult', 
							'#bulk_action_sizeSystem', '.value_sizeSystem', 
							'#bulk_action_excluded_destination', '.value_excluded_destination',
							'#bulk_action_excluded_country', '.value_excluded_country'
						];
						break;
					case 'sizeSystem':
						oGmc.doSet('#set_tag', 'sizeSystem');
						aShow = ['#bulk_action_sizeSystem', '.value_sizeSystem'];
						aHide = [
							'#bulk_action_material', '#bulk_action_pattern', 
							'#bulk_action_adult', '#bulk_action_gender', 
							'.value_material', '.value_pattern', 
							'.value_agegroup', '.value_gender', 
							'#bulk_action_tagadult', '.value_tagadult', 
							'#bulk_action_sizeType', '.value_sizeType', 
							'#bulk_action_excluded_destination', '.value_excluded_destination',
							'#bulk_action_excluded_country', '.value_excluded_country'
						];
						break;
					case 'excluded_destination':
						oGmc.doSet('#set_tag', 'excluded_destination');
						aShow = ['#bulk_action_excluded_destination', '.value_excluded_destination'];
						aHide = [
							'#bulk_action_material', '#bulk_action_pattern', 
							'#bulk_action_adult', '#bulk_action_gender', 
							'.value_material', '.value_pattern', 
							'.value_agegroup', '.value_gender', 
							'#bulk_action_tagadult', '.value_tagadult', 
							'#bulk_action_sizeType', '.value_sizeType', 
							'#bulk_action_sizeSystem', '.value_sizeSystem',
							'#bulk_action_excluded_country', '.value_excluded_country' 
						];
						break;
					case 'excluded_country':
						oGmc.doSet('#set_tag', 'excluded_country');
						aShow = ['#bulk_action_excluded_country', '.value_excluded_country'];
						aHide = [
							'#bulk_action_material', '#bulk_action_pattern', 
							'#bulk_action_adult', '#bulk_action_gender', 
							'.value_material', '.value_pattern', '.value_agegroup', 
							'.value_gender', '#bulk_action_tagadult', '.value_tagadult', 
							'#bulk_action_sizeType', '.value_sizeType', '#bulk_action_sizeSystem', 
							'.value_sizeSystem', 
							'#bulk_action_excluded_destination', '.value_excluded_destination'
						];
						break;	
					case '0':
						aHide = [
							'#bulk_action_material', '#bulk_action_pattern', 
							'#bulk_action_adult', '#bulk_action_gender', 
							'#bulk_action_tagadult', '.value_material', 
							'.value_pattern', '.value_agegroup', 
							'.value_gender', '.value_tagadult', 
							'#bulk_action_excluded_destination', 
							'.value_excluded_destination', 
							'#bulk_action_excluded_country', '.value_excluded_country'
						];
						break;
					default:
						break;
				}
				oGmc.initHide(aHide);
				oGmc.initShow(aShow);
			}

			// execute management of options
			handleOptionToDisplay($("#default_tag").val());

			$("#set_tag").change(function () {
				handleOptionToDisplay($(this).val());
			});
		</script>
		{/literal}
	</div>
</div>
<div id="loadingAdvancedTagDiv" style="display: none;">
	<div class="alert alert-info">
		<p style="text-align: center !important;"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
		<p style="text-align: center !important;">{l s='Your update configuration is in progress' mod='gmerchantcenter'}</p>
	</div>
</div>
{/if}