{include file='header'}
{if $success|isset}
	<p class="success">{lang}{$success}{/lang}</p>
{/if}
{include file='formError'}

<section class="section">
	{@$revision->getContent($__wcf->language)}
</section>
{if $__wcf->user->userID && !$hasAccepted}
	<div class="formSubmit">
		<form method="post" action="{link controller='TermsOfUse'}{/link}">
			<button type="submit" class="buttonPrimary" name="accept" value="{$revision->revisionID}">{lang}wcf.termsOfUse.accept{/lang}</button>
			<button type="submit" name="reject" value="{$revision->revisionID}">{lang}wcf.termsOfUse.reject{/lang}</button>
			{@SECURITY_TOKEN_INPUT_TAG}
		</form>
	</div>
{/if}
{include file='footer'}
