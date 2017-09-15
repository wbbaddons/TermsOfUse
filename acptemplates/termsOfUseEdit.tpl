{include file='header' pageTitle='wcf.acp.termsOfUse.edit'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.termsOfUse.edit{/lang}</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='TermsOfUseRevisionList' application='wcf'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.acp.termsOfUse.revision.list{/lang}</span></a></li>
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.edit{/lang}</p>
{/if}

<form method="post" action="{link application='wcf' controller='TermsOfUseEdit'}{/link}">
	{foreach from=$availableLanguages item='language'}
		<div class="section">
			<h2 class="sectionTitle">{$language}</h2>
			
			<dl class="wide">
				<dt><label for="content{$language->languageID}">TODO</label></dt>
				<dd>
					<textarea name="content[{$language->languageID}]" id="content{$language->languageID}" class="wysiwygTextarea" data-disable-attachments="true" data-autosave="be.bastelstu.termsOfUse-{$language->languageID}">
					</textarea>
					
					{if $errorField == 'content'|concat:$language->languageID}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wcf.acp.termsOfUse.error.{$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>

			{include file='wysiwyg' wysiwygSelector='content'|concat:$language->languageID}
		</div>
	{/foreach}

	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}

