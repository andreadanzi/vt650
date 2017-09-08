<?php
// danzi.tn#19 - 20170907 - custom function for notification on task completion
   
chdir(dirname(__FILE__) . '/../..');

require_once 'include/utils/utils.php';
require 'modules/com_vtiger_workflow/VTEntityMethodManager.inc';
$emm = new VTEntityMethodManager($adb); 
$emm->addEntityMethod("ProjectTask", "Notify Project Task", "modules/com_vtiger_workflow/custom_wf_projecttask.inc.php", "notifyProjectTask");

?>
