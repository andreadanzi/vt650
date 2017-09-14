{*<!--/************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/-->*}

{strip}
<br>
<div class="quickWidget">
	<div class="accordion-heading accordion-toggle quickWidgetHeader">
	<h5 class="title pull-left widgetTextOverflowEllipsis">{vtranslate('LBL_Mailbox', 'MailManager')}</h5>
	<div class="box pull-right">
								
							
	</div>
	
		<div class="clearfix"></div>
	</div>
	<div class="defaultContainer {if $MAILBOX->exists() eq false}hide{/if}">
		<div class="widgetContainer accordion-body collapse in">
			<input type=hidden name="mm_selected_folder" id="mm_selected_folder">
			<input type="hidden" name="_folder" id="mailbox_folder">
			<div class="row-fluid">
				<div class="span12">
					<table>
				<tr> 
					<td style="padding:5px 6px!important;">&nbsp;</td>
					<td style="padding:5px 6px!important"><a class="" href='#Reload' id="_mailfolder_mm_reload" onclick="MailManager.reload_now();" title="Refresh" data-toggle="tooltip" data-placement="bottom" >
								<i style="color:#fff;font-size:20px;" alt="Refresh" title="Refresh" align="absmiddle" border="0" hspace="2" class="fa fa-refresh"></i>
							</a></td>

					<td style="padding:5px 6px!important;">
							<a class="" href='#Settings' id="_mailfolder_mm_settings" onclick="MailManager.open_settings_detail();"  title="Settings" data-toggle="tooltip" data-placement="bottom" >
								<i style="color:#fff;font-size:20px;" alt="Settings" title="Settings" align="absmiddle" border="0" hspace="2" class="fa fa-cog"></i>
							</a></td>
                    <td style="padding:5px 6px!important;">|</td>
				     <td style="padding:5px 6px!important;">
							<a href="javascript:void(0);" onclick="MailManager.mail_compose();" title="{vtranslate('LBL_Compose','MailManager')}" data-toggle="tooltip" data-placement="bottom"><i style="color:#fff;font-size:20px;" class="fa fa-pencil"></i></a>
						</td>

					<td style="padding:5px 6px!important">

							<a href="#Drafts" id="_mailfolder_mm_drafts" onclick="MailManager.folder_drafts(0);"  title="{vtranslate('LBL_Drafts','MailManager')}" data-toggle="tooltip" data-placement="bottom"><i  style="color:#fff;font-size:20px;" class="fa fa-file-text-o"></i></a>
						</td>

				</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
{/strip}
