<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
chdir(dirname(__FILE__) . '/../..');
include_once 'vtlib/Vtiger/Module.php';
include_once 'vtlib/Vtiger/Package.php';
include_once 'includes/main/WebUI.php';

include_once 'include/Webservices/Utils.php';

$Vtiger_Utils_Log = true;


$SINGLE_MODULENAME = 'Project';
$MODULENAME = $SINGLE_MODULENAME;

// danzi.tn#8 - 20170705 - dashboards for project module
$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance || file_exists('modules/'.$MODULENAME)) {
    $tabid = $moduleInstance->id;
    echo "\nModule ". $MODULENAME . " is present\n";
    /*Dashboard Widgets*/   
    $moduleInstance->addLink('DASHBOARDWIDGET', 'Project View [Type A]', 'index.php?module='.$MODULENAME.'&view=ShowWidget&name=AProjects','', '1');
    $moduleInstance->addLink('DASHBOARDWIDGET', 'Projects By Status', 'index.php?module='.$MODULENAME.'&view=ShowWidget&name=ProjectsByStatus','', '2');
    $home = Vtiger_Module::getInstance('Home');
    $home->addLink('DASHBOARDWIDGET', 'Project View [Type A]', 'index.php?module='.$MODULENAME.'&view=ShowWidget&name=AProjects','', '15');
    $home->addLink('DASHBOARDWIDGET', 'Projects By Status', 'index.php?module='.$MODULENAME.'&view=ShowWidget&name=ProjectsByStatus','', '16');
    
} else {
    echo "Module ". $MODULENAME . " is not present\n";
}

?>
