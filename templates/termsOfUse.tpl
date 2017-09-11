{include file='header'}
{if $success|isset}
	<p class="success">{lang}{$success}{/lang}</p>
{/if}
{include file='formError'}
{if $__wcf->user->userID && !$hasAccepted}
	<form method="post" action="{link controller='TermsOfUse'}{/link}">
		<section class="section">
			{@$revision->getContent($__wcf->language)}
		</section>
		<div class="formSubmit">
			<button type="submit" name="accept" value="{$revision->revisionID}">{lang}wcf.termsOfUse.accept{/lang}</button>
			<button type="submit" name="reject" value="{$revision->revisionID}">{lang}wcf.termsOfUse.reject{/lang}</button>
			{@SECURITY_TOKEN_INPUT_TAG}
		</div>
	</form>
{else}
	<section class="section">
		{@$revision->getContent($__wcf->language)}
	</section>
{/if}
{include file='footer'}
