{*<!--/************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/-->*}

{strip}

<div class="detailViewContainer" id="open_email_con" name="open_email_con"style="margin-top:20px">
    <div class="detailViewInfo row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div>
                    <h3 id="_mailopen_subject">{$MAIL->subject()}</h3>
                </div>
            </div>
            <br>
            <div class="row-fluid">
                <div class="btn-toolbar span8">
                        <button class="btn pull-left" onclick="MailManager.mail_close();" href='javascript:void(0);'><strong>&#171; {$FOLDER->name()}</strong></button>

            
                </div>
				<div class="span4">
					<span class=" pull-right">
						<button class="btn"
							{if $MAIL->msgno() < $FOLDER->count()}
								onclick="MailManager.mail_open( '{$FOLDER->name()}', {$MAIL->msgno(1)});"
							{else}
								disabled="disabled"
							{/if}>
							<span class="fa fa-chevron-left"></span>
						</button>
						<button class="btn"
							{if $MAIL->msgno() > 1}
								onclick="MailManager.mail_open( '{$FOLDER->name()}', {$MAIL->msgno(-1)});"
							{else}
								disabled="disabled"
							{/if}>
							<span class="fa fa-chevron-right"></span>
						</button>
					</span>
				</div>
            </div>
        </div>
    </div>

    <div class="detailViewInfo row-fluid">
        <div class="span12 details">
            <div class="contents" style="padding-right: 2.2%;">
                <div class="row-fluid mycfluid">
                    <div class="summaryView mailmanager span6">
                        <div class="btn-group  actionImages actions tooltips">
              <a class="btn dropdown-toggle tooltips actionsdd" data-toggle="dropdown" onclick="">
Actions <i class="fa fa-chevron-down"></i>

              </a>
              <ul class="dropdown-menu">
                <li> <a onclick="MailManager.mail_reply(true);"><i class="fa fa-reply-all"></i>&nbsp;<strong>{vtranslate('LBL_Reply_All',$MODULE)}</strong></a></li>
                        <li><a onclick="MailManager.mail_reply(false);"><i class="fa fa-reply"></i>&nbsp;<strong>{vtranslate('LBL_Reply',$MODULE)}</strong></a></li>
                        <li><a onclick="MailManager.mail_forward({$MAIL->msgno()});"><i class="fa fa-share"></i>&nbsp;<strong>{vtranslate('LBL_Forward',$MODULE)}</strong></a></li>
                        <li><a onclick="MailManager.mail_mark_unread('{$FOLDER->name()}', {$MAIL->msgno()});"><i class="fa fa-envelope"></i>&nbsp;<strong>{vtranslate('LBL_Mark_As_Unread',$MODULE)}</strong></a></li>          
                        <li class="divider"></li>
                        <li><a onclick="MailManager.mail_print();"><i class="fa fa-print"></i>&nbsp;<strong>{vtranslate('LBL_Print',$MODULE)}</strong></a></li>
                        <li><a id = 'mail_delete_dtlview' onclick="MailManager.maildelete('{$FOLDER->name()}',{$MAIL->msgno()},true);"><i class="fa fa-trash"></i>&nbsp;<strong>{vtranslate('LBL_Delete',$MODULE)}</strong></a></li>
              </ul>
              <button class="btn btn-success btn-xs" onclick="MailManager.mail_print();"><i class="fa fa-print"></i>&nbsp;<strong>{vtranslate('LBL_Print',$MODULE)}</strong></button>
                
                        <button class="btn btn-xs btn-danger" id = 'mail_delete_dtlview' onclick="MailManager.maildelete('{$FOLDER->name()}',{$MAIL->msgno()},true);"><i class="fa fa-trash"></i>&nbsp;<strong>{vtranslate('LBL_Delete',$MODULE)}</strong></button>
            </div>
                         
                    <br>
                        <span id="_mailopen_msgid_" style="display:none;">{$MAIL->_uniqueid|@escape:'UTF-8'}</span>

                        <label class="displayInlineBlock"><i class="mazzy-ico fa fa-user"></i>&nbsp;<strong>{vtranslate('LBL_FROM', $MODULE)} :&nbsp;</strong></label>
                        <span id="_mailopen_from">
                            {foreach item=SENDER from=$MAIL->from()}
                                {$SENDER}
                            {/foreach}
                        </span><br>

                        {if $MAIL->to()}
                            <label class="displayInlineBlock"><i class="mazzy-ico fa fa-users"></i>&nbsp;<strong>{vtranslate('LBL_TO',$MODULE)} :&nbsp;</strong></label>
                            <span id="_mailopen_to">
                                {foreach item=RECEPIENT from=$MAIL->to() name="TO"}
                                {if $smarty.foreach.TO.index > 0}, {/if}{$RECEPIENT}
                            {/foreach}
                        </span><br>
                    {/if}

                    {if $MAIL->cc()}
                        <label class="displayInlineBlock"><i class="mazzy-ico fa fa-user-secret"></i>&nbsp;<strong>{vtranslate('LBL_CC',$MODULE)} :&nbsp;</strong></label>
                        <span id="_mailopen_cc">
                            {foreach item=CC from=$MAIL->cc() name="CC"}
                            {if $smarty.foreach.CC.index > 0}, {/if}{$CC}
                        {/foreach}
                    </span><br>
                {/if}

                {if $MAIL->bcc()}
                    <label class="displayInlineBlock"><i class="mazzy-ico fa fa-user-secret"></i>&nbsp;<strong>{vtranslate('LBL_BCC',$MODULE)} :&nbsp;</strong></label>
                    <span id="_mailopen_cc">
                        {foreach item=BCC from=$MAIL->bcc() name="BCC"}
                        {if $smarty.foreach.BCC.index > 0}, {/if}{$BCC}
                    {/foreach}
                </span><br>
            {/if}

            <label class="displayInlineBlock"><i class="mazzy-ico fa fa-calendar"></i>&nbsp;<strong>{vtranslate('LBL_Date',$MODULE)} :&nbsp;</strong></label>
            <span id="_mailopen_date">{$MAIL->date()}</span><br>

            {if $ATTACHMENTS}
                <label class="displayInlineBlock"><i class="mazzy-ico fa fa-paperclip"></i>&nbsp;<strong>{vtranslate('LBL_Attachments',$MODULE)} :&nbsp;</strong></label>
                <span>
                    {foreach item=ATTACHVALUE key=ATTACHNAME from=$ATTACHMENTS name="attach"}
                        {if $INLINE_ATT[$ATTACHNAME] eq null}
                            <img border=0 src="{'attachments.gif'|@vimage_path}">
                            <a href="index.php?module={$MODULE}&view=Index&_operation=mail&_operationarg=attachment_dld&_muid={$MAIL->muid()}&_atname={$ATTACHNAME|@escape:'htmlall':'UTF-8'}">{$ATTACHNAME}</a>
                            &nbsp;
                        {/if}
                    {/foreach}
                    <input type="hidden" id="_mail_attachmentcount_" value="{$smarty.foreach.attach.total}" >
                </span><br>
            {/if}
        </div>

        <div class="summaryView span5">
            <div>
               <button class="btn btn-mazzy"><i class="fa fa-paperclip"></i>&nbsp;<strong>{vtranslate('LBL_RELATED_RECORDS',$MODULE)}</strong></button>
                <button class="small" id="_mailrecord_findrel_btn_" onclick="MailManager.mail_find_relationship();">{vtranslate('JSLBL_Find_Relation_Now',$MODULE)}</button>
                <div id="_mailrecord_relationshipdiv_"></div>
            </div>
        </div>
    </div>
	<hr>
    <br>
    <div class="summaryView mailmanager row-fluid">
        <div class='mm_body' id="_mailopen_body">
            {$MAIL->body()}
        </div>
    </div>
    </div>
    </div>
    </div>
{/strip}