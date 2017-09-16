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
	<dl class="wide">
		<dt>{lang}wcf.termsOfUse.terms{/lang}</dt>
		<dd>
			<div class="container htmlContent">{@$revision->getContent($__wcf->language)}</div>
			{if $errorField == 'accept' || $errorField == 'reject'}
				<small class="innerError">
					{if $errorType == 'empty'}
						{lang}wcf.global.form.error.empty{/lang}
					{else}
						{lang}wcf.termsOfUse.error.{$errorType}{/lang}
					{/if}
				</small>
			{/if}
		</dd>
	</dl>
	
	{if ($__wcf->user->userID && !$revision->isOutdated() && !$revision->hasAccepted($__wcf->user)) || (!$__wcf->user->userID && $__wcf->session->getVar('termsOfUseRegister'))}
		<div class="formSubmit">
			<form method="post" action="{link controller='TermsOfUse'}{/link}">
				<button type="submit" class="buttonPrimary" name="accept" value="{$revision->revisionID}">{lang}wcf.termsOfUse.accept{/lang}</button>
				<button type="submit" name="reject" value="{$revision->revisionID}">{lang}wcf.termsOfUse.reject{/lang}</button>
				{@SECURITY_TOKEN_INPUT_TAG}
			</form>
		</div>
	{/if}
</section>

{include file='footer'}
