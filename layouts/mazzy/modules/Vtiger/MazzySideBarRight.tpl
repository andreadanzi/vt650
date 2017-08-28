{strip}
{assign var=LEFTPANELHIDE value=$CURRENT_USER_MODEL->get('leftpanelhide')}

<div id="mazzy-sidebar-right" class="{if $LEFTPANELHIDE neq '1' and $smarty.get.parent neq "Settings"}hide{/if}">

	


		<div class="mazzy-info">
	

						{foreach key=index item=obj from=$HEADER_LINKS|@array_reverse}
							{assign var="src" value=$obj->getIconPath()}
							{assign var="icon" value=$obj->getIcon()}
							{assign var="title" value=$obj->getLabel()}
							{assign var="childLinks" value=$obj->getChildLinks()}
							
							
							
								
								{if !empty($src)}
								<div class="smartbtn">
								<div class="mazzy-menu-cat">
									<a id="menubar_item_right_{$title}" class="mazzy-show-child-modules btn-profile" mazzy-childmodules="{$title}" href="#"><i style="color: #fff;" class="fa fa-{$title}"></i>{vtranslate($title,$MODULE)} 
									</a>
									
									{else}
									
									<div class="mazzy-menu-cat rx">
										{assign var=title value=$USER_MODEL->get('first_name')}

										{assign var=useremail value=$USER_MODEL->get('email1')}
										{assign var=userelastname value=$USER_MODEL->get('last_name')}
										{if empty($title)}
											{assign var=title value=$USER_MODEL->get('last_name')}
											
										{/if}
										
										
							
                                        <a id="menubar_item_right_{$title}"  class="userName textOverflowEllipsis mazzy-show-child-modules" mazzy-childmodules="{$title}"  title="{$title}">
          {foreach key=ITER item=IMAGE_INFO from=$USER_MODEL->getImageDetails()}
										{if !empty($IMAGE_INFO.path) && !empty($IMAGE_INFO.orgname)}
											<img class="mazzy-profile pull-left" src="{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" data-image-id="{$IMAGE_INFO.id}">
										{/if}
										{/foreach}                              
                                        
                                        
                                        <span  style="font-size:13px;"><b>{$title} {$userelastname}</b><br>{$useremail}</span></a>
					<!-- danzi.tn@20170828 -->	
					<input type="hidden" id="current_user_xname" value="{$useremail}"/>
                                        
									{/if}
								</div>
								
									{if !empty($childLinks)}
									<div class="mazzy-child-modules {if $title eq "LBL_CRM_SETTINGS" || $title eq "LBL_FEEDBACK" || $title eq "themeroller" }hide{/if}"  style="width:100%" id="mazzy-child-modules-{$title}">
									
										{foreach key=index item=obj from=$childLinks}
											
											{if $obj->getLabel() eq NULL}
												
												{else}
													{assign var="id" value=$obj->getId()}
													{assign var="href" value=$obj->getUrl()}
													{assign var="label" value=$obj->getLabel()}
													{assign var="onclick" value=""}
													{if stripos($obj->getUrl(), 'javascript:') === 0}
														{assign var="onclick" value="onclick="|cat:$href}
														{assign var="href" value="javascript:;"}
													{/if}
												
														<div class="mazzy-module-link {if $MODULE eq $moduleName} selected {/if}" ><a target="{$obj->target}" id="menubar_item_right_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($label)}" {if $label=='Switch to old look'}switchLook{/if} href="{$href}" {$onclick}>
															<i class="fa fa-{$moduleName}"></i>{vtranslate($label,$MODULE)}</a></div>
												
											{/if}
											
										{/foreach}
										{if $USER_MODEL->isAdminUser() && $title=="LBL_CRM_SETTINGS"}
										
												<a id="menubar_item_moduleManager" href="index.php?module=MenuEditor&parent=Settings&view=Index"><i style="margin-left:20px;" class="mazzy-ico fa fa-dot-circle-o"></i> {vtranslate('LBL_CUSTOMIZE_MAIN_MENU',$MODULE)}</a>
												<a id="menubar_item_moduleManager" href="index.php?module=ModuleManager&parent=Settings&view=List"><i style="margin-left:20px;" class="mazzy-ico fa fa-dot-circle-o"></i> {vtranslate('LBL_ADD_MANAGE_MODULES',$MODULE)}</a>
											
										{/if}
										
									</div>
								{/if}
								{if !empty($src)}
								</div>
								{/if}

						{/foreach}

<div class="smartbtn">						
<div class="mazzy-menu-cat">

<a class="mazzy-show-child-modules btn-profile" mazzy-childmodules="themeroller"  href="#">
						<i style="color: #fff;" class="fa fa-paint-brush"></i>{vtranslate("Skins",$MODULE)}
							</a>
</div>
<div class="mazzy-child-modules hide" style="position: relative; width: 100%;" id="mazzy-child-modules-themeroller">
						<hr>
									{assign var=COUNTER value=0}
									{assign var=SEPARATOR value=0}
									{assign var=THEMES_LIST value=Vtiger_Theme::getAllSkins()}
									{foreach key=SKIN_NAME item=SKIN_COLOR from=$THEMES_LIST|@array_reverse}
									
									{if $SKIN_NAME|strstr:"dandy" || $SKIN_NAME|strstr:"mazzy"} 
									{elseif $SEPARATOR eq 0}
									<h4 style="color:#fff!important; margin-left:10px;">Skins</h4>
									{assign var=SEPARATOR value=1}
									{/if}
										
										<div class="  {if $USER_MODEL->get('theme') eq $SKIN_NAME}themeSelected{/if}" title="{ucfirst($SKIN_NAME)}" style="width:100%; cursor:pointer;" ><div class="themeElement" style="background:{$SKIN_COLOR};padding:5px;margin:5px;" data-skin-name="{$SKIN_NAME}"><span style="color: #fff; " >{$SKIN_NAME}</span>{if $USER_MODEL->get('theme') eq $SKIN_NAME}<i style="color: #fff;margin:3px" class="fa fa-check pull-right"></i>{/if}</div></div>
									
									{/foreach}
								<hr>	
								<div id="progressDiv"></div>
</div>
</div>
</div>

<div class="clearfix" style="border-bottom:1px solid #ddd;"></div>
	{if $smarty.get.parent neq "Settings" && $MODULE neq "Home"}
	{include file='SideBarLinks.tpl'|@vtemplate_path:$MODULE}
	<div class="clearfix"></div>

	{include file='SideBarWidgets.tpl'|@vtemplate_path:$MODULE}
	{/if}
	{if $smarty.get.parent eq "Settings"}
	{include file='SideBarSettings.tpl'|@vtemplate_path:$MODULE}
	{/if}
	
	
	{if $MODULE eq "MailManager"}
	<div id="_quicklinks_mainuidiv_" class="quickWidgetContainer accordion">
		{include file="MainuiQuickLinks.tpl"|@vtemplate_path:$MODULE}

		<div class="clearfix">&nbsp;
			<input type="hidden" id="isMailBoxExists" value="{if $MAILBOX->exists()}1{else}0{/if}"/>
		</div>
		<div class="quickWidget">
		<div class="accordion-heading accordion-toggle quickWidgetHeader" onclick="MailManager.getFoldersList();">
			<span class="pull-left">
				
<img class="imageElement" data-rightimage="{vimage_path('rightArrowWhite.png')}" data-downimage="{vimage_path('downArrowWhite.png')}" src="{vimage_path('rightArrowWhite.png')}" />
			</span>&nbsp;
			<h5 class="title widgetTextOverflowEllipsis pull-right">{vtranslate('LBL_Folders',$MODULE)}</h5>
		</div>

		<div class="widgetContainer accordion-body collapse in" id="folders">
			<input type=hidden name="mm_selected_folder" id="mm_selected_folder">
			<input type="hidden" name="_folder" id="mailbox_folder">
		</div>
	</div>
		<div id="_mainfolderdiv_" class="quickWidgetContainer accordion"></div>
	</div>
	{/if}
	
	
	
<div class="clearfix"></div>	
<div class="mazzy-menu-cat" >
<a class="mazzy-show-child-modules" ><b style="text-transform: capitalize!important;">Keep Sidebar Open</b><br>
<div class="slideThree">	
	<input type="checkbox" class="slideThreeChk" value="None" id="slideThree" name="check" {if $LEFTPANELHIDE eq '1'}checked{/if}/>
	<label for="slideThree"></label>
</div>
</a>
</div>
	
</div>
{/strip}
