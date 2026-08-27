{include file='header' pageTitle='wcf.acp.termsOfUse.revision.list'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.termsOfUse.revision.list{/lang}{if $items} <span class="badge badgeInverse">{#$items}</span>{/if}</h1>
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

{if $objects|count}
	<div class="section tabularBox">
		<table class="table jsObjectActionContainer" data-object-action-class-name="wcf\data\termsofuse\revision\TermsofuseRevisionAction">
			<thead>
				<tr>
					<th class="columnID columnRevisionID{if $sortField == 'revisionID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='TermsOfUseRevisionList' application='wcf'}pageNo={@$pageNo}&sortField=revisionID&sortOrder={if $sortField == 'revisionID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnCreatedAt{if $sortField == 'createdAt'} active {@$sortOrder}{/if}"><a href="{link controller='TermsOfUseRevisionList' application='wcf'}pageNo={@$pageNo}&sortField=createdAt&sortOrder={if $sortField == 'enabledAt' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.termsOfUse.createdAt{/lang}</a></th>
					<th class="columnEnabledAt{if $sortField == 'enabledAt'} active {@$sortOrder}{/if}"><a href="{link controller='TermsOfUseRevisionList' application='wcf'}pageNo={@$pageNo}&sortField=enabledAt&sortOrder={if $sortField == 'enabledAt' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.termsOfUse.enabledAt{/lang}</a></th>

					{event name='columnHeads'}
				</tr>
			</thead>

			<tbody>
				{foreach from=$objects item=revision}
					<tr class="jsRevisionRow jsObjectActionObject" data-object-id="{@$revision->getObjectID()}">
						<td class="columnIcon">
							<a href="{link controller='TermsOfUseRevisionShow' id=$revision->revisionID application='wcf'}{/link}" title="{lang}wcf.acp.termsOfUse.show{/lang}" class="jsTooltip">
								<span class="icon icon16 fa-search"></span>
							</a>

							<span
								class="
								icon icon16 {if $revision->enabledAt !== null}fa-check-square-o{else}fa-square-o{/if}
								{if $revision->isNewerThanActive()}jsObjectAction jsTooltip pointer{else}disabled{/if}
								"
								title="{lang}wcf.acp.termsOfUse.enable{/lang}"
								data-object-action="toggle"
								data-object-action-success="reload"
							></span>

							{event name='rowButtons'}
						</td>

						<td class="columnID">{$revision->getObjectID()}</td>
						<td class="columnCreatedAt">{$revision->createdAt|plainTime}</td>
						<td class="columnEnabledAt">
							{if $revision->enabledAt !== null}
								{$revision->enabledAt|plainTime}
							{else}
								{lang}wcf.acp.termsOfUse.draft{/lang}
							{/if}
						</td>

						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>

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
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
