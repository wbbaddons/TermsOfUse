{include file='header' pageTitle='wcf.acp.termsOfUse.revision.show'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.termsOfUse.revision.show{/lang}</h1>
	</div>

	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='TermsOfUseRevisionList' application='wcf'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.acp.termsOfUse.revision.list{/lang}</span></a></li>

			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{foreach from=$availableLanguages item='language'}
	<section class="section">
		<h2 class="sectionTitle">{$language}</h2>
		<pre>{$revision->getContent($language, true)}</pre>
	</section>
{/foreach}

<footer class="contentFooter">
	<nav class="contentFooterNavigation">
		<ul>
			<li><a href="{link controller='TermsOfUseRevisionList' application='wcf'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.acp.termsOfUse.revision.list{/lang}</span></a></li>

			{event name='contentFooterNavigation'}
		</ul>
	</nav>
</footer>

{include file='footer'}

