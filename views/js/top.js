/*
 * 2003-2021 Business Tech
 *
 *  @author    Business Tech SARL <http://www.businesstech.fr/en/contact-us>
 *  @copyright 2003-2021 Business Tech SARL
 */
// declare the custom label js object
var btHeaderBar = function (sName) {

	/* @param obj iStepId : the id tu update
	* @param int bUpdateWay : the way activate or deactivate
	* @return string : HTML returned by smarty
	* */
	this.updateProgressState = function(iStepId, bUpdateWay) {

		if(bUpdateWay == 'update')
		{
			$('.step-' + iStepId).removeClass('disabled').addClass('complete');
			$('.btn-step-' + iStepId).hide();
		}
		else if (bUpdateWay == 'error') {
			$('.step-' + iStepId).removeClass('complete').addClass('disabled');
			$('.btn-step-' + iStepId).slideUp();
		}
	}
}