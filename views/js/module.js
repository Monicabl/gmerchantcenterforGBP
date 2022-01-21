/*
 * 2003-2021 Business Tech
 *
 *  @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 *  @copyright 2003-2021 Business Tech SARL
 */
// declare main object of module
var Gmc = function(sName){
	// set name
	this.name = sName;

	// set name
	this.oldVersion = false;

	// set translated js msgs
	this.msgs = {};

	// stock error array
	this.aError = [];

	// set url of admin img
	this.sImgUrl = '';

	// set url of module's web service
	this.sWebService = '';

	// variable to control the generation of the XML content
	this.bGenerateXmlFlag = false;

	// set this in obj context
	var oThis = this;

	/**
	 * show() method show effect and assign HTML in
	 *
	 * @param string sId : container to show in
	 * @param string sHtml : HTML to display
	 */
	this.show = function(sId, sHtml){
		$("#" + sId).html(sHtml).css('style', 'none');
		$("#" + sId).show('fast');
	};

	/**
	 * hide() method hide effect and delete html
	 *
	 * @param string sId : container to hide in
	 */
	this.hide = function(sId, bOnlyHide){
		$('#' + sId).hide('fast');
		if (bOnlyHide == null) {
			$('#' + sId).empty();
		}
		//$("#" + sId).hide('fast', function(){
		//		$("#" + sId).html('');
		//	}
		//);
	};

	/**
	 * form() method check all fields of current form and execute : XHR or submit => used for update all admin config
	 *
	 * @see ajax
	 * @param string sForm : form
	 * @param string sURI : query params used for XHR
	 * @param string sRequestParam : param action and type in order to send with post mode
	 * @param string sToDisplay :
	 * @param string sToHide : force to hide specific ID
	 * @param bool bSubmit : used only for sending main form
	 * @param bool bFancyBox : used only for fancybox in xhr
	 * @param string oCallBack : used only for callback to execute as ajax request
	 * @param string sErrorType :
	 * @param string sLoadBar :
	 * @param string sScrollTo :
	 * @param int iStepUpdate :
	 * @return string : HTML returned by smarty
	 */
	this.form = function(sForm, sURI, sRequestParam, sToDisplay, sToHide, bSubmit, bFancyBox, oCallBack, sErrorType, sLoadBar, sScrollTo, iStepUpdate){
		// set loading bar
		if (sLoadBar) {
			$('#'+sLoadBar).show();
		}

		// set return validation
		var aError = [];

		// get all fields of form
		var fields = $("#" + sForm).serializeArray();

		// set counter
		var iCounter = 0;

		// set bIsError
		var bIsError = false;

		// check element form
		jQuery.each(fields, function(i, field) {
			bIsError = false;

			switch(field.name) {
				case 'bt_link' :
					if (field.value == '') {
						oThis.aError[iCounter] = oThis.msgs.link;
						bIsError = true;
					}
					break;
				case 'bt_feed-token' :
					if (field.value == '' || field.value.length < 32) {
						oThis.aError[iCounter] = oThis.msgs.token;
						bIsError = true;
					}
					break;
				case 'bt_label-name' :
					if (field.value == '') {
						oThis.aError[iCounter] = oThis.msgs.customlabel;
						bIsError = true;
					}
					break;
				case 'bt_api-key' :
					if (field.value == '') {
						oThis.aError[iCounter] = oThis.msgs.apikey;
						bIsError = true;
					}
				break;
				case 'bt_merchant-id' :
					if (field.value == '') {
						oThis.aError[iCounter] = oThis.msgs.merchantid;
						bIsError = true;
					}
				break;
				case 'bt_export' :
					var bChecked = false;
					var sType = field.value == '0' ? 'categoryBox' : 'brandBox';

					jQuery.each($("input." + sType), function(i, checkbox) {
						if (checkbox.checked == true) {
							bChecked = true;
						}
					});
					if (!bChecked) {
						oThis.aError[iCounter] = field.value == '0'? oThis.msgs.category : oThis.msgs.brand;
						bIsError = true;
					}
					break;
				case 'bt_incl-color' :
					var bChecked = false;
					var bCheckAttr = false;
					var bCheckFeature = false;
					if (field.value == 'both') {
						bCheckAttr = true;
						bCheckFeature = true;
					}
					else if (field.value == 'attribute') {
						bCheckAttr = true;
					}
					else if (field.value == 'feature') {
						bCheckFeature = true;
					}
					else {
						bChecked = true;
					}
					if (bCheckAttr) {
						jQuery.each($("#color_opt_attr option:selected"), function (i, selectList) {
							bChecked = true;
						});
					}
					if (bCheckFeature) {
						jQuery.each($("#color_opt_feat option:selected"), function (i, selectList) {
							bChecked = true;
						});
					}
					if (!bChecked) {
						oThis.aError[iCounter] = oThis.msgs.color;
						bIsError = true;
					}
					break;
				default:
					// check if language field
					if (field.name.indexOf('bt_home-cat-name') != -1 &&  field.value == '') {
						var aLangId = field.name.split('name_');
						oThis.aError[iCounter] = oThis.msgs.homecat[aLangId[1]];

						bIsError = true;
					}
					break;
			}

			if (($('input[name="' + field.name + '"]') != undefined || $('textarea[name="' + field.name + '"]') != undefined || $('select[name="' + field.name + '"]').length != undefined) && bIsError == true) {
				if ($('input[name="' + field.name + '"]').length != 0) {
					$('input[name="' + field.name + '"]').parent().addClass('has-error has-feedback');
					$('input[name="' + field.name + '"]').append('<span class="icon-remove-sign"></span>');
				}
				if ($('textarea[name="' + field.name + '"]').length != 0) {
					$('textarea[name="' + field.name + '"]').parent().addClass('has-error has-feedback');
					$('textarea[name="' + field.name + '"]').append('<span class="icon-remove-sign"></span>');
				}
				if ($('select[name="' + field.name + '"]').length != 0) {
					$('select[name="' + field.name + '"]').parent().addClass('has-error has-feedback');
					$('select[name="' + field.name + '"]').append('<span class="icon-remove-sign"></span>');
				}
				++iCounter;
			}
		});

		// use case - no errors in form
		if (oThis.aError.length == 0 && !bIsError) {
			// use case - Ajax request
			if (bSubmit == undefined || bSubmit == null || !bSubmit) {
				if (sLoadBar && sToHide != null) {
					oThis.hide(sToHide, true);
				}

				// format object of fields in string to execute Ajax request
				var sFormParams = $.param(fields);

				if (sRequestParam != null && sRequestParam != '') {
					sFormParams = sRequestParam + '&' + sFormParams;
				}

				// execute Ajax request
				this.ajax(sURI, sFormParams, sToDisplay, sToHide, bFancyBox, null, sLoadBar, sScrollTo, oCallBack, iStepUpdate);

				return true;
			}
			// use case - send form
			else {
				// hide loading bar
				if (sLoadBar) {
					$('#'+sLoadBar).hide();
				}
				document.forms[sForm].submit();
				return true;
			}
		}

		// display errors
		this.displayError(sErrorType);

		// set loading bar
		if (sLoadBar) {
			$('#'+sLoadBar).hide();
		}

		return false;
	};


	/**
	 * ajax() method execute XHR
	 *
	 * @param string sURI : query params used for XHR
	 * @param string sParams :
	 * @param string sToShow :
	 * @param string sToHide :
	 * @param bool bFancyBox : used only for fancybox in xhr
	 * @param bool bFancyBoxActivity : used only for fancybox in xhr
	 * @param string sLoadBar : used only for loading
	 * @param string sScrollTo : used only for scrolling
	 * @param obj oCallBack : used only for callback to execute as ajax request
	 * @param int iStepUpdate : used only for callback to execute as ajax request
	 * @return string : HTML returned by smarty
	 */
	this.ajax = function(sURI, sParams, sToShow, sToHide, bFancyBox, bFancyBoxActivity, sLoadBar, sScrollTo, oCallBack ,iStepUpdate) {
		sParams = 'sMode=xhr' + ((sParams == null || sParams == undefined) ? '' : '&' + sParams) ;

		// configure XHR
		$.ajax({
			type : 'POST',
			url : sURI,
			data : sParams,
			dataType : 'html',
			success: function(data) {
				// hide loading bar
				if (sLoadBar) {
					$('#'+sLoadBar).hide();
				}

				if(iStepUpdate) {
					oBtUpdateStep.updateProgressState(iStepUpdate, 'update');
				}

				if (bFancyBox) {
					// update fancybox content
					$.fancybox(data);
				}
				else if (sToShow != null && sToHide != null) {
					// same hide and show
					if (sToShow == sToHide) {
						oThis.hide(sToHide);
						setTimeout('', 1000);
						oThis.show(sToShow, data);
					}
					else {
						oThis.hide(sToHide);
						setTimeout('', 1000);
						oThis.show(sToShow, data);
					}
				}
				else if (sToShow != null) {
					oThis.show(sToShow, data);
				}
				else if (sToHide != null) {
					oThis.hide(sToHide);
				}

				if (sScrollTo !== null && typeof sScrollTo !== 'undefined' && $(sScrollTo).length != 0) {
					var iPosTop = $(sScrollTo).offset().top-30;
					if(iPosTop < 0) iPosTop = 0;

					$(document).scrollTop(iPosTop);
				}

				// execute others ajax request if needed. In this case, we can update any other tab from the module at the same time
				if (oCallBack != null && oCallBack.length != 0) {
					for (var fx in oCallBack) {
						oThis.ajax(oCallBack[fx].url, oCallBack[fx].params, oCallBack[fx].toShow, oCallBack[fx].toHide, oCallBack[fx].bFancybox, oCallBack[fx].bFancyboxActivity, oCallBack[fx].sLoadbar, oCallBack[fx].sScrollTo , oCallBack[fx].oCallBack);
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$("#" + oThis.name + "FormError").addClass('alert alert-danger');
				oThis.show("#" + oThis.name + "FormError", '<h3>internal error</h3>');
			}
		});
	};

	/**
	 * displayError() method display errors
	 *
	 * @param string sType : type of container
	 * @return bool
	 */
	this.displayError = function(sType){
		if (oThis.aError.length != 0) {
			var sError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><ul class="list-unstyled">';
			for (var i = 0; i < oThis.aError.length;++i) {
				sError += '<li>' + oThis.aError[i] + '</li>';
			}
			sError += '</ul></div>'

			$("#" + oThis.name + sType + "Error").html(sError);
			$("#" + oThis.name + sType + "Error").slideDown();

			// flush errors
			oThis.aError = [];

			return false;
		}
	};


	/**
	 * changeSelect() method displays or hide related option form
	 *
	 * @param string sId : type of container
	 * @param mixed mDestId
	 * @param string sDestId2
	 * @param string sType of second dest id
	 * @param bool bForce
	 * @param bool mVal
	 */
	this.changeSelect = function(sId, mDestId, sDestId2, sDestIdToHide, bForce, mVal){
		if (bForce) {
			if (typeof mDestId == 'string') {
				mDestId = [mDestId];
			}

			for (var i = 0; i < mDestId.length; ++i) {
				if (mVal) {
					$("#" + mDestId[i]).fadeIn('fast', function() {$("#" + mDestId[i]).css('display', 'block')});
				}
				else {
					$("#" + mDestId[i]).fadeOut('fast');
				}
			}
		}
		else {
			$("#" + sId).bind('change', function (event){
				$("#" + sId + " input:checked").each(function (){
					switch ($(this).val()) {
						case 'true' :
							// display option features
							$("#" + sDestId).fadeIn('fast', function() {$("#" + sDestId).css('display', 'block')});
							break;
						default:
							// hide option features
							$("#" + sDestId).fadeOut('fast');

							// set to false
							if (sDestId2 && sDestIdToHide) {
								$("#" + sDestId2 + " input").each(function (){
										switch ($(this).val()) {
											case 'false' :
												$(this).attr('checked', 'checked');
												// hide option features
												$("#" + sDestIdToHide).fadeOut('fast');
												break;
											default:
												$(this).attr('checked', '');
												break;
										}
									}
								);
							}
							break;
					}
				});
			});
		}
	};

	/**
	 * selectAll() method select / deselect all checkbox
	 *
	 * @param string sId : type of container
	 * @param string sCible : all checkbox to process
	 */
	this.selectAll = function(sCible, sType){
		if (sType == 'check') {
			$(sCible).attr('checked', true);
		}
		else{
			$(sCible).attr('checked', false);
		}
	};


	/**
	 * initShow() method initialize each elt to show
	 *
	 * @param array aList
	 */
	this.initShow = function(aList) {
		if (aList.length > 0) {
			for (var i = 0; i < aList.length; ++i) {
				$(aList[i]).show();
			}
		}
	};


	/**
	 * initHide() method initialize each elt to hide
	 *
	 * @param array aList
	 */
	this.initHide = function(aList) {
		if (aList.length > 0) {
			for (var i = 0; i < aList.length; ++i) {
				$(aList[i]).hide();
			}
		}
	};

	/**
	 * change() method add "change" evt and manage values to hide or to show
	 *
	 * @param string sSelector
	 * @param string sDestination
	 */
	this.change = function(sSelector, sDestination) {
		$(sSelector).change(function() {
			$(this).val() == "0" ? $(sDestination).slideUp() : $(sDestination).slideDown();
		});
	};

	/**
	 * doSet() method set a new value to the selector
	 *
	 * @param string sSelector
	 * @param mixed mValue
	 */
	this.doSet = function(sSelector, mValue) {
		$(sSelector).val(mValue);
	};

	/**
	 * duplicateValue() method duplicate value to selector elt
	 *
	 * @param string sSelector
	 * @param mixed mValue
	 * @param bool
	 */
	this.duplicateFirstValue = function(sSelector, mValue) {
		$(sSelector).each(function(i, e) {
			$(this).val(mValue);
		});
		return true;
	};

	/**
	 * generateDataFeed() method generate the XML data feed
	 *
	 * @param array aParams
	 * @param json
	 */
	this.generateDataFeed = function(aParams) {
		var sURI = aParams.sURI;
		var sParams = aParams.sParams;
		var sFilename = aParams.sFilename;
		var iShopId = aParams.iShopId;
		var iLangId = aParams.iLangId;
		var sLangIso = aParams.sLangIso;
		var sCountryIso = aParams.sCountryIso;
		var sCurrencyIso = aParams.sCurrencyIso;
		var iStep = aParams.iStep;
		var iTotal = aParams.iTotal;
		var iProcess = aParams.iProcess;
		var sDisplayedCounter = aParams.sDisplayedCounter;
		var sDisplayedBlock = aParams.sDisplayedBlock;
		var sDisplaySuccess = aParams.sDisplaySuccess;
		var sDisplayTotal = aParams.sDisplayTotal;
		var sLoaderBar = aParams.sLoaderBar;
		var sErrorContainer = aParams.sErrorContainer;
		var bReporting = aParams.bReporting;
		var sDisplayReporting = aParams.sDisplayReporting;
		var sResultText = aParams.sResultText;

		if (iStep == 0) {
			$(sDisplayTotal).css('display','none');
			$(sDisplayTotal+'_'+sLangIso+'_'+sCountryIso).html('');
		}
		// hide
		$('#'+oThis.name+sErrorContainer+'Error').hide();

		// variable to control the XHR data feed
		oThis.bGenerateXmlFlag = true;

		$.ajax({
			type : 'POST',
			url : sURI,
			data : sParams + '&iShopId='+iShopId+'&sFilename='+sFilename+'&iLangId='+iLangId+'&sLangIso='+sLangIso+'&sCountryIso='+sCountryIso+'&sCurrencyIso='+sCurrencyIso+'&iFloor='+iStep+'&iTotal='+iTotal+'&iProcess='+iProcess+'&bReporting='+bReporting,
			dataType : 'json',
			async : true,
			success: function(data) {
				// use case - no error
				if (data.status == 'ok') {
					var iProcessedProduct = data.counter;
					// modify the displayed counter value
					$(sDisplayedCounter).val(iProcessedProduct);
					$(sLoaderBar).attr('width', parseInt(iProcessedProduct / iTotal * 200));

					// Manage the progress bar with boostrap
					var elem = document.getElementById(sLoaderBar);
					elem.style.width = Math.round((iProcessedProduct * 100)/iTotal) + '%';
					elem.innerHTML = Math.round((iProcessedProduct * 100)/iTotal) + '%';

					// use case - recursive ajax query
					if (iProcessedProduct < iTotal) {
						aParams.iStep = iProcessedProduct;
						aParams.iProcess = data.process;
						oThis.generateDataFeed(aParams);
					}
					// use case - finalize the recursive ajax query
					else {
						$(sDisplayedCounter).val(iTotal);
						$(sLoaderBar).attr('width', 1);
						$(sDisplayedBlock).hide();
						$(sDisplayedCounter).val(0);
						$(sDisplaySuccess+'_'+sLangIso+'_'+sCountryIso).removeClass('danger');
						$(sDisplaySuccess+'_'+sLangIso+'_'+sCountryIso).addClass('success');
						$(sDisplayTotal+'_'+sLangIso+'_'+sCountryIso).html(data.process + '&nbsp;' + sResultText);
						$(sDisplayTotal+'_'+sLangIso+'_'+sCountryIso).css('display', 'inline');

						// use case - display reporting
						if (bReporting) {
							$(sDisplayReporting).attr('href', $(sDisplayReporting).attr('href') + '&lang=' + sLangIso.toLowerCase() +'_'+sCountryIso.toUpperCase()+'&count='+data.process+'&sCurrencyIso='+sCurrencyIso);
							$(sDisplayReporting).click();
						}

						// variable to control the XHR data feed and reset all control params
						oThis.bGenerateXmlFlag = false;
						aParams.iStep = 0;
						aParams.iProcess = 0;
					}
				}
				// use case - errors
				else {
					oThis.bGenerateXmlFlag = false;
					$(sDisplaySuccess+'_'+sLangIso+'_'+sCountryIso).addClass('danger');
					for (key in data.error) {
						oThis.aError.push(data.error[key].msg);
					}
					oThis.displayError(sErrorContainer);
					// flush errors
					oThis.aError = [];
					setTimeout(function () {$(sDisplayedBlock).hide();}, 10000);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				oThis.bGenerateXmlFlag = false;
				$(sDisplaySuccess+'_'+sLangIso+'_'+sCountryIso).addClass('danger');
				oThis.aError[0] = 'Internal Ajax error';
				oThis.displayError(sErrorContainer);
				setTimeout(function () {$(sDisplayedBlock).hide();}, 10000);
			}
		});

	};

	/**
	 * autocomplete object execute autocomplete used for response list of products
	 *
	 * @param string sUri = URI to execute autocomplete
	 * @param string sSearchField = the field where we use the autocomplete
	 * @return json
	 */
	this.autocomplete = function(sURI, sSearchField) {
		$(sSearchField).autocomplete(sURI, {
			minChars: 2,
			autoFill: true,
			matchContains: true,
			mustMatch:true,
			scroll:true,
			cacheLength:0,
			formatItem: function(item) {
				return item[1] + ((item[2] != 0)?'(attr: '+item[2]+')' : '') + ' - ' + item[0];
			}
		}).result(this.addProduct);
		$(sSearchField).setOptions({ extraParams: { excludeIds : this.getProductIds()} });
	};

	/**
	 * addProduct() method add product in order to exclude product of response list
	 *
	 * @param event
	 * @param data : array returned by proxy php
	 * @param formatted : data formatted
	 */
	this.addProduct = function(event, data, formatted)
	{
		if (data == null) {
			return false;
		}
		var productId = data[1];
		var attrId = data[2];
		var productName = data[0];

		/* delete product from select + add product line to the div, input_name, input_ids elements */
		$(document).find('#bt_exclude-no-products').remove();
		$('#bt_excluded-products').append('<tr><td>' + productId + ((attrId != 0)?' (attr: ' + attrId + ')': '') + ' - ' + productName + '</td><td><span style="cursor:pointer;" class="icon-trash" onclick="oGmc.deleteProduct(\'' + productId + '¤' + attrId + '\');"></span></td></tr>');
		$('#hiddenProductNames').val($('#hiddenProductNames').val() + productName + '||');
		$('#hiddenProductIds').val($('#hiddenProductIds').val() + productId + '¤' + attrId + '-');

		$('#bt_search-p').val('');
		$('#bt_search-p').setOptions({
			extraParams: {excludeIds : oThis.getProductIds()}
		});
	}

	/**
	 * deleteProduct() method delete list of products and construct valid response list
	 *
	 * @param int id
	 */
	this.deleteProduct = function(id)
	{
		var div = getE('bt_excluded-products');
		var input = getE('hiddenProductIds');
		var name = getE('hiddenProductNames');

		// Cut hidden fields in array
		var inputCut = input.value.split('-');
		var nameCut = name.value.split('||');

		if (inputCut.length != nameCut.length) {
			return alert('Bad size');
		}

		// Reset all hidden fields
		input.value = '';
		name.value = '';
		div.innerHTML = '';

		for (i in inputCut) {
			// If empty, error, next
			if (!inputCut[i] || !nameCut[i]) {
				continue;
			}
			// Add to hidden fields no selected products OR add to select field selected product
			if (inputCut[i] != id) {
				var attrCut = inputCut[i].split('¤');
				input.value += inputCut[i] + '-';
				name.value += nameCut[i] + '||';
				div.innerHTML += '<tr><td>' + attrCut[0] + ((attrCut[1] != 0)? ' (attr: '+ attrCut[1]+')' : '') + ' - ' + nameCut[i] + '</td><td><span style="cursor:pointer;" class="icon-trash" onclick="oGmc.deleteProduct(\'' + inputCut[i] + '\');"></span></td></tr>'
				$(document).find('#bt_exclude-no-products').remove();
			}
			//else {
			//	div.innerHTML += '<option selected="selected" value="' + inputCut[i] + '-' + nameCut[i] + '">' + inputCut[i] + ' - ' + nameCut[i] + '</option>';
			//}
		}

		if (input.value == '') {
			div.innerHTML = '<tr id="bt_exclude-no-products"><td colspan="2">No products</td></tr>';
		}

		$('#bt_search-p').setOptions({
			extraParams: {excludeIds : oThis.getProductIds()}
		});
	}


	/**
	 * autocompleteFreeShipping object execute autocomplete used for response list of products
	 *
	 * @param string sUri = URI to execute autocomplete
	 * @param string sSearchField = the field where we use the autocomplete
	 * @return json
	 */
	this.autocompleteFreeShipping = function(sURI, sSearchField) {
		$(sSearchField).autocomplete(sURI, {
			minChars: 2,
			autoFill: true,
			max:20,
			matchContains: true,
			mustMatch:true,
			scroll:false,
			cacheLength:0,
			formatItem: function(item) {
				return item[1] + ((item[2] != 0)?'(attr: '+item[2]+')' : '') + ' - ' + item[0];
			}
		}).result(this.addProductFreeShipping);
		$(sSearchField).setOptions({ extraParams: { excludeIds : this.getProductIds()} });
	};

	/**
	 * addProductFreeShipping() method add product in order to exclude product of response list
	 *
	 * @param event
	 * @param data : array returned by proxy php
	 * @param formatted : data formatted
	 */
	this.addProductFreeShipping = function(event, data, formatted)
	{
		if (data == null) {
			return false;
		}
		var productId = data[1];
		var attrId = data[2];
		var productName = data[0];

		/* delete product from select + add product line to the div, input_name, input_ids elements */
		$(document).find('#bt_free-shipping-no-products').remove();
		$('#bt_free-shipping-products').append('<tr><td>' + productId + ((attrId != 0)?' (attr: ' + attrId + ')': '') + ' - ' + productName + '</td><td><span style="cursor:pointer;" class="icon-trash" onclick="oGmc.deleteProductFreeShipping(\'' + productId + '¤' + attrId + '\');"></span></td></tr>');
		$('#hiddenProductFreeShippingNames').val($('#hiddenProductFreeShippingNames').val() + productName + '||');
		$('#hiddenProductFreeShippingIds').val($('#hiddenProductFreeShippingIds').val() + productId + '¤' + attrId + '-');

		$('#bt_search-p-free-shipping').val('');
		$('#bt_search-p-free-shipping').setOptions({
			extraParams: {excludeIds : oThis.getProductIds()}
		});
	}

	/**
	 * deleteProductFreeShipping() method delete list of products and construct valid response list
	 *
	 * @param int id
	 */
	this.deleteProductFreeShipping = function(id)
	{
		var div = getE('bt_free-shipping-products');
		var input = getE('hiddenProductFreeShippingIds');
		var name = getE('hiddenProductFreeShippingNames');

		// Cut hidden fields in array
		var inputCut = input.value.split('-');
		var nameCut = name.value.split('||');

		if (inputCut.length != nameCut.length) {
			return alert('Bad size');
		}

		// Reset all hidden fields
		input.value = '';
		name.value = '';
		div.innerHTML = '';

		for (i in inputCut) {
			// If empty, error, next
			if (!inputCut[i] || !nameCut[i]) {
				continue;
			}
			// Add to hidden fields no selected products OR add to select field selected product
			if (inputCut[i] != id) {
				var attrCut = inputCut[i].split('¤');
				input.value += inputCut[i] + '-';
				name.value += nameCut[i] + '||';
				div.innerHTML += '<tr><td>' + attrCut[0] + ((attrCut[1] != 0)? ' (attr: '+ attrCut[1]+')' : '') + ' - ' + nameCut[i] + '</td><td><span style="cursor:pointer;" class="icon-trash" onclick="oGmc.deleteProductFreeShipping(\'' + inputCut[i] + '\');"></span></td></tr>'
				$(document).find('#bt_free-shipping-no-products').remove();
			}
			//else {
			//	div.innerHTML += '<option selected="selected" value="' + inputCut[i] + '-' + nameCut[i] + '">' + inputCut[i] + ' - ' + nameCut[i] + '</option>';
			//}
		}

		if (input.value == '') {
			div.innerHTML = '<tr id="bt_free-shipping-no-products"><td colspan="2">No products</td></tr>';
		}

		$('#bt_search-p-free-shipping').setOptions({
			extraParams: {excludeIds : oThis.getProductIds()}
		});
	}

	/**
	 * getProductIds() method used to return list of already chosen products
	 */
	this.getProductIds = function()
	{
		var ids = 0 + ',';
		ids += $('#hiddenProductIds').val().replace(/\-/g,',').replace(/\,$/,'');
		ids = ids.replace(/\,$/,'');

		return ids;
	}

	/**
	 * runMainFeed() method execute the code related to the feed option
	 */
	this.runMainFeed = function()
	{
		// initialize the list of elt to show and hide
		var aShow = [];
		var aHide = [];

		if ($('#bt_export').val() == "0") {
			aShow = ['#bt_categories','#alert_categorie'];
			aHide = ['#bt_brands'];
		}
		else {
			aShow = ['#bt_brands'];
			aHide = ['#bt_categories','#alert_categorie'];
		}
		this.initHide(aHide);
		oThis.initShow(aShow);

		//color
		switch ($("#inc_color").val()) {
			case '' :
				aHide.push('#div_color_opt_attr');
				aHide.push('#div_color_opt_feat');
				$("#color_opt_attr option:selected").removeAttr("selected");
				$("#color_opt_feat option:selected").removeAttr("selected");
				break;
			case 'attribute' :
				aHide.push('#div_color_opt_feat');
				aShow.push('#div_color_opt_attr');
				$("#color_opt_feat option:selected").removeAttr("selected");
				break;
			case 'feature' :
				aHide.push('#div_color_opt_attr');
				aShow.push('#div_color_opt_feat');
				$("#color_opt_attr option:selected").removeAttr("selected");
				break;
			default:
				aShow = ['#div_color_opt_attr','#div_color_opt_feat'];
				break;
		}

		oThis.initHide(aHide);
		oThis.initShow(aShow);

		// handle color display
		$("#inc_color").change(function() {
			aShow = [];
			aHide = [];
			switch ($(this).val()) {
				case '' :
					aHide = ['#div_color_opt_attr','#div_color_opt_feat'];
					$("#color_opt_attr option:selected").removeAttr("selected");
					$("#color_opt_feat option:selected").removeAttr("selected");
					break;
				case 'attribute' :
					aHide = ['#div_color_opt_feat'];
					aShow = ['#div_color_opt_attr'];
					$("#color_opt_feat option:selected").removeAttr("selected");
					break;
				case 'feature' :
					aHide = ['#div_color_opt_attr'];
					aShow = ['#div_color_opt_feat'];
					$("#color_opt_attr option:selected").removeAttr("selected");
					break;
				default:
					aShow = ['#div_color_opt_attr','#div_color_opt_feat'];
					break;
			}
			oThis.initHide(aHide);
			oThis.initShow(aShow);
		});

		// Size management
		switch ($("#inc_size").val()) {
			case '' :
				aHide.push('#div_size_opt_attr');
				aHide.push('#div_size_opt_feat');
				$("#size_opt_attr option:selected").removeAttr("selected");
				$("#size_opt_feat option:selected").removeAttr("selected");
				break;
			case 'attribute' :
				aHide.push('#div_size_opt_feat');
				aShow.push('#div_size_opt_attr');
				$("#size_opt_feat option:selected").removeAttr("selected");
				break;
			case 'feature' :
				aHide.push('#div_size_opt_attr');
				aShow.push('#div_size_opt_feat');
				$("#size_opt_attr option:selected").removeAttr("selected");
				break;
			default:
				aShow = ['#div_size_opt_attr','#div_size_opt_feat'];
				break;
		}

		oThis.initHide(aHide);
		oThis.initShow(aShow);

		$("#inc_size").change(function() {
			aShow = [];
			aHide = [];
			switch ($(this).val()) {
				case '' :
					aHide = ['#div_size_opt_attr','#div_sizeauto_opt_feat'];
					$("#size_opt_attr option:selected").removeAttr("selected");
					$("#size_opt_feat option:selected").removeAttr("selected");
					break;
				case 'attribute' :
					aHide = ['#div_size_opt_feat'];
					aShow = ['#div_size_opt_attr'];
					$("#size_opt_feat option:selected").removeAttr("selected");
					break;
				case 'feature' :
					aHide = ['#div_size_opt_attr'];
					aShow = ['#div_size_opt_feat'];
					$("#size_opt_attr option:selected").removeAttr("selected");
					break;
				default:
					aShow = ['#div_size_opt_attr','#div_size_opt_feat'];
					break;
			}
			oThis.initHide(aHide);
			oThis.initShow(aShow);
		});

		// manage change select event
		$("#bt_export").change(function() {
			aShow = [];
			aHide = [];
			if ($(this).val() == "0") {
				aShow = ['#bt_categories','#alert_categorie'];
				aHide = ['#bt_brands'];
			}
			else {
				aShow = ['#bt_brands'];
				aHide = ['#bt_categories','#alert_categorie'];
			}
			oThis.initHide(aHide);
			oThis.initShow(aShow);
		});

		$("a#handleTagAdult").fancybox({
			'hideOnContentClick' : false
		});
		$("a#handleTagPattern").fancybox({
			'hideOnContentClick' : false
		});
		$("a#handleTagMaterial").fancybox({
			'hideOnContentClick' : false
		});
		$("a#handleTagGender").fancybox({
			'hideOnContentClick' : false
		});
		$("a#handleTagAge").fancybox({
			'hideOnContentClick' : false
		});
	}

	/**
	 * runMainGoogle() method execute the code related to the google option
	 */
	this.runMainGoogle = function()
	{
		$("a#handleGoogleAdwords").fancybox({
			'hideOnContentClick' : false
		});
		$("a#handleGoogleAdwordsEdit").fancybox({
			'hideOnContentClick' : false
		});
	}

	/**
	 * runGsa() method execute the code related to the google option
	 */
	this.runGsa = function()
	{
		$("a#handleToken").fancybox({
			'hideOnContentClick' : false
		});
	}
};