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

// danzi.tn#6 - 20170718 - set Calendar related list to Project
$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance || file_exists('modules/'.$MODULENAME)) {
    $tabid = $moduleInstance->id;
    echo "\nModule ". $MODULENAME . " is present\n";
    /* Related List for Calendar */   
    $moduleInstance->setRelatedList(Vtiger_Module::getInstance('Emails'), 'Emails',Array('add','select'),'get_emails');
    
} else {
    echo "Module ". $MODULENAME . " is not present\n";
}

?>
