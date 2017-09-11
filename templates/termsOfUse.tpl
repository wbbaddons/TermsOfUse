{include file='header'}
{if $revision->isOutdated()}
	<p class="info">{lang}wcf.termsOfUse.outdated{/lang}</p>
{elseif $revision->hasAccepted($__wcf->user)}
	<p class="info">{lang}wcf.termsOfUse.accepted{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}{$success}{/lang}</p>
{/if}
{include file='formError'}

<section class="section">
	{@$revision->getContent($__wcf->language)}
</section>

{if $__wcf->user && !$revision->isOutdated() && !$revision->hasAccepted($__wcf->user)}
	<div class="formSubmit">
		<form method="post" action="{link controller='TermsOfUse'}{/link}">
			<button type="submit" class="buttonPrimary" name="accept" value="{$revision->revisionID}">{lang}wcf.termsOfUse.accept{/lang}</button>
			<button type="submit" name="reject" value="{$revision->revisionID}">{lang}wcf.termsOfUse.reject{/lang}</button>
			{@SECURITY_TOKEN_INPUT_TAG}
		</form>
	</div>
{/if}
{include file='footer'}
