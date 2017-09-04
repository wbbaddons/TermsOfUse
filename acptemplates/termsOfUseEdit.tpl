{include file='header' pageTitle='wcf.acp.termsOfUse.edit'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.termsOfUse.edit{/lang}</h1>
	</div>
	
	{hascontent}
	<nav class="contentHeaderNavigation">
		<ul>
			{content}
			{event name='contentHeaderNavigation'}
			{/content}
		</ul>
	</nav>
	{/hascontent}
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{link application='wcf' controller='TermsOfUseEdit'}{/link}">
	<div class="section">
	</div>

	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}

