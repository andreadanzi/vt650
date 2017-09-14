{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}
{strip}
    <!-- danzi.tn#20 - 20170914 - added related Email Widget on vlayouts -->
	<div class="row-fluid">		
		<span class="span3">
			<strong>{vtranslate('LBL_SUBJECT',$MODULE_NAME)}</strong>
		</span>
		<span class="span4">
			<strong>{vtranslate('LBL_FROM',$MODULE_NAME)}</strong>
		</span>
		<span class="span3 horizontalLeftSpacingForSummaryWidgetContents">
			<span class="pull-right"><strong>{vtranslate('Date & Time Sent',$MODULE_NAME)}</strong></span>
		</span>
	</div>
	{foreach item=RELATED_RECORD from=$RELATED_RECORDS}
		<div class="recentActivitiesContainer">
			<ul class="unstyled">
				<li>
					<div class="row-fluid" name="emailsRelatedRecord" data-id="{$RELATED_RECORD->get('id')}" style="cursor:pointer;">
						<span class="span3 textOverflowEllipsis">
                            {$RELATED_RECORD->getDisplayValue('subject')}
						</span>
						<span class="span4">
							{$RELATED_RECORD->getFromEmailAddress() }
						</span>	
						<span class="span3 horizontalLeftSpacingForSummaryWidgetContents">
							<span class="pull-right">{$RELATED_RECORD->getRelatedListDisplayValue('date_start')}</span>
						</span>					
					</div>
				</li>
			</ul>
		</div>
	{/foreach}
	{assign var=NUMBER_OF_RECORDS value=count($RELATED_RECORDS)}
	{if $NUMBER_OF_RECORDS eq 5}
		<div class="row-fluid">
			<div class="pull-right">
				<a class="moreRecentContacts cursorPointer">{vtranslate('LBL_MORE',$MODULE_NAME)}</a>
			</div>
		</div>
	{/if}
	<!-- danzi.tn#20 - end -->
{/strip}
