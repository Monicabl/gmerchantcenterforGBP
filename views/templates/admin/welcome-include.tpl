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
<div class="col-xs-12 col-lg-12 pull-left">

	<div class="clr_10"></div>

	<div id="modulenewsletterblock">
		<h3>&nbsp;&nbsp;{l s='Stay in touch with us' mod='gmerchantcenter'}</h3>

		<p class="alert alert-info">{l s='Sign up to our newsletter and stay up-to-date on new modules, important updates and more !' mod='gmerchantcenter'}</p>

		<div class="form-group">
			<label class="control-label col-xs-12 col-lg-1">
			</label>
			<div class="col-xs-12 col-lg-5 input-group">
				<span class="input-group-addon" id="basic-addon1">{l s='Your e-mail ' mod='gmerchantcenter'}</span>
				<input type="text" name="newsletter_email" id="newsletter_email" size="50" />
				<input type="hidden" name="newsletter_language" id="newsletter_language" value="{$sCurrentLang}" />
			</div>
		</div>
		<label class="control-label col-xs-12 col-lg-1"></label>
		<div class="col-xs-12 col-lg-5">
			<button class="btn btn-info pull-right" name="dosignup" id="dosignup">{l s='Sign up !' mod='gmerchantcenter'}</button>
		</div>

		<div class="clr_10"></div>

		<div id="box_msg_ok" class="alert alert-success" style="display:none;"></div>
		<div id="box_msg_error" class="alert alert-danger" style="display:none;"></div>
	</div>

	<div class="clr_10"></div>
	<h3>&nbsp;&nbsp;{l s='Follow Us' mod='gmerchantcenter'}</h3>
	<div class="clr_10"></div>

	<div class="col-xs-12 col-lg-12">
		<div class="fb-like" data-href="https://www.facebook.com/businesstech.fr/?fref=ts" data-layout="box_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
		<a class="twitter-follow-button" href="https://twitter.com/_businesstech_"  data-size="default">	Follow @BusinessTech</a>
	</div>



</div>

<div class="clr_100"></div>

{*Facebook Like*}
<div id="fb-root"></div>
<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.7&appId=892585157542155";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>

{*Twitter Follow*}
<script>window.twttr = (function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0],
				t = window.twttr || {};
		if (d.getElementById(id)) return t;
		js = d.createElement(s);
		js.id = id;
		js.src = "https://platform.twitter.com/widgets.js";
		fjs.parentNode.insertBefore(js, fjs);

		t._e = [];
		t.ready = function(f) {
			t._e.push(f);
		};

		return t;
	}(document, "script", "twitter-wjs"));</script>

{literal}
<script type="text/javascript">

	function display_confirmation_message(data)
	{
		console.log(data.response);

		var msg_invalid_email = {/literal}'{l s="Sorry... The e-mail you have entered is invalid. Please double-check it." mod="gmerchantcenter"}'{literal};
		var msg_invalid_language = {/literal}'{l s="Sorry... The language parameter is invalid. Please reload this page, try again and contact us if this problem persists." mod="gmerchantcenter"}'{literal};
		var msg_duplicate = {/literal}'{l s="Your e-mail was already in our list, no need to enter it again. If you have unsubscribed directly from MailChimp, please contact us so we can add you there again manually" mod="gmerchantcenter"}'{literal};
		var msg_unknown_error = {/literal}'{l s="Sorry... There was an unexpected error. Please reload this page, try again and contact us if this problem persists." mod="gmerchantcenter"}'{literal};
		var msg_unknown_error_ajax = {/literal}'{l s="Sorry... There was an unexpected network error. Please reload this page, try again and contact us if this problem persists." mod="gmerchantcenter"}'{literal};
		var msg_ok = {/literal}'{l s="Thank you ! You have been successfully added to our newsletter list." mod="gmerchantcenter"}'{literal};

		$("#box_msg_ok").empty().hide();
		$("#box_msg_error").empty().hide();

		if (data.response == 'invalid_email') {
			$("#box_msg_error").append('<p>'+msg_invalid_email+'</p>').show();
		}
		else if (data.response == 'invalid_language') {
			$("#box_msg_error").append('<p>'+msg_invalid_language+'</p>').show();
		}
		else if (data.response == 'duplicate') {
			$("#box_msg_error").append('<p>'+msg_duplicate+'</p>').show();
		}
		else if (data.response == 'unknown_error') {
			$("#box_msg_error").append('<p>'+msg_unknown_error+'</p>').show();
		}
		else if (data.response == 'ok') {
			$("#box_msg_ok").append('<p>'+msg_ok+'</p>').show();
		}
		else {
			$("#box_msg_error").append('<p>'+msg_unknown_error_ajax+'</p>').show();
		}

		return false;
	}

	$(document).ready(function()
	{
		$("#dosignup").click(function(e)
		{
			var email = $("#newsletter_email").val();
			var language = $("#newsletter_language").val();

			$.ajax({
				type: "GET",
				url: "https://www.businesstech.fr/api_newsletter.php",
				data: "email="+email+"&language="+language,
				dataType: "jsonp",
				crossDomain: true,
				async : true,
				jsonpCallback: "display_confirmation_message"
			});
		});
	});
</script>
{/literal}