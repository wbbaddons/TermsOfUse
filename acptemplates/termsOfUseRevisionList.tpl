{include file='header' pageTitle='wcf.acp.termsOfUse.revision.list'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.termsOfUse.revision.list{/lang}</h1>
	</div>

	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='TermsOfUseEdit' application='wcf'}{/link}" class="button"><span class="icon icon16 fa-pencil"></span> <span>{lang}wcf.acp.termsOfUse.edit{/lang}</span></a></li>

			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="TermsOfUseRevisionList" application="wcf" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
	</div>
{/hascontent}

{hascontent}
	<div class="section tabularBox">
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnTriggerID{if $sortField == 'revisionID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='TermsOfUseRevisionList' application='wcf'}pageNo={@$pageNo}&sortField=triggerID&sortOrder={if $sortField == 'revisionID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnCreatedAt{if $sortField == 'createdAt'} active {@$sortOrder}{/if}"><a href="{link controller='TermsOfUseRevisionList' application='wcf'}pageNo={@$pageNo}&sortField=createdAt&sortOrder={if $sortField == 'enabledAt' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.termsOfUse.createdAt{/lang}</a></th>
					<th class="columnEnabledAt{if $sortField == 'enabledAt'} active {@$sortOrder}{/if}"><a href="{link controller='TermsOfUseRevisionList' application='wcf'}pageNo={@$pageNo}&sortField=enabledAt&sortOrder={if $sortField == 'enabledAt' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.termsOfUse.enabledAt{/lang}</a></th>

					{event name='columnHeads'}
				</tr>
			</thead>

			<tbody>
			{content}
				{foreach from=$objects item=revision}
					<tr>
						<td class="columnIcon">
								<a href="{link controller='TermsOfUseRevisionShow' id=$revision->revisionID application='wcf'}{/link}" title="{lang}wcf.acp.termsOfUse.show{/lang}" class="jsTooltip"><span class="icon icon16 fa-search"></span></a>
							{if !$revision->isActive() && !$revision->isOutdated()}
								<a href="{link controller='TermsOfUseEnable' id=$revision->revisionID application='wcf'}{/link}" title="{lang}wcf.acp.termsOfUse.enable{/lang}" class="jsTooltip"><span class="icon icon16 fa-square-o"></span></a>
							{else}
								<span class="icon icon16 fa-{if $revision->enabledAt !== null}check-{/if}square-o disabled" title="{lang}wcf.acp.termsOfUse.enable{/lang}"></span>
							{/if}

							{event name='rowButtons'}
						</td>

						<td class="columnID">{@$revision->revisionID}</td>
						<td class="columnCreatedAt">{$revision->createdAt|plainTime}</td>
						<td class="columnEnabledAt">{if $revision->enabledAt !== null}{$revision->enabledAt|plainTime}{else}{lang}wcf.acp.termsOfUse.draft{/lang}{/if}</td>

						{event name='columns'}
					</tr>
				{/foreach}
			{/content}
			</tbody>
		</table>
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/hascontent}

<footer class="contentFooter">
	{hascontent}
		<div class="paginationBottom">
			{content}{@$pagesLinks}{/content}
		</div>
	{/hascontent}

	<nav class="contentFooterNavigation">
		<ul>
			<li><a href="{link controller='TermsOfUseEdit' application='wcf'}{/link}" class="button"><span class="icon icon16 fa-pencil"></span> <span>{lang}wcf.acp.termsOfUse.edit{/lang}</span></a></li>

			{event name='contentFooterNavigation'}
		</ul>
	</nav>
</footer>

{include file='footer'}

