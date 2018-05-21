{capture assign='contentDescription'}{lang}wcf.termsOfUse.lastChange{/lang}{/capture}

{include file='header'}
{if $revision->isOutdated()}
	<p class="warning">{lang}wcf.termsOfUse.outdated{/lang}</p>
{/if}
{assign var='acceptedAt' value=$revision->hasAccepted($__wcf->user)}
{if $acceptedAt !== false}
	<p class="info">{lang acceptedAt=$acceptedAt}wcf.termsOfUse.accepted{/lang}</p>
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
	
	{if ($__wcf->user->userID && !$revision->isOutdated() && $acceptedAt === false) || (!$__wcf->user->userID && $__wcf->session->getVar('termsOfUseRegister'))}
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
