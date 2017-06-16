<?php
// danzi.tn#4 - 20170615 - accounts related list for ProjectTask
   
chdir(dirname(__FILE__) . '/../..');
include_once 'vtlib/Vtiger/Module.php';
include_once 'vtlib/Vtiger/Package.php';
include_once 'includes/main/WebUI.php';

include_once 'include/Webservices/Utils.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'ProjectTask';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance || file_exists('modules/'.$MODULENAME)) {
    $tabid = $moduleInstance->id;
    echo "\nModule ". $MODULENAME . " is present\n";

	/** n:n relations with Accounts**/
	$relModule = Vtiger_Module::getInstance('Accounts');
	$moduleInstance->unsetRelatedList($relModule,'Accounts');
	$moduleInstance->setRelatedList($relModule, 'Accounts',Array('ADD','SELECT'));
	$relModule->unsetRelatedList($moduleInstance,$MODULENAME);
	$relModule->setRelatedList($moduleInstance, $MODULENAME,Array('ADD','SELECT'));
} else {
    echo "Module ". $MODULENAME . " is not present\n";
}

?>
