{*<!--/************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/-->*}

{strip}
{if $FOLDERS}
    <div id="foldersList" class="row-fluid">
        <div class="span10">
            <table class="recordlist">
                              {foreach item=FOLDER from=$FOLDERS}
<tr><td style="padding:5px 6px!important">
                    <i class="mazzy-ico fa fa-folder-open"></i><a class="mm_folder" id='_mailfolder_{$FOLDER->name()}' href='#{$FOLDER->name()}' onclick="MailManager.clearSearchString(); MailManager.folder_open('{$FOLDER->name()}'); ">{if $FOLDER->unreadCount()}<b>{$FOLDER->name()} ({$FOLDER->unreadCount()})</b>{else}{$FOLDER->name()}{/if}</a>
</td></tr>
                                 {/foreach}
            </table>   
        </div>
    </div>
{/if}
{/strip}