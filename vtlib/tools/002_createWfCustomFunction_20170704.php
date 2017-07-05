<?php
// danzi.tn#9 - 20170705 - custom function for project creation based on template
   
chdir(dirname(__FILE__) . '/../..');

require_once 'include/utils/utils.php';
require 'modules/com_vtiger_workflow/VTEntityMethodManager.inc';
$emm = new VTEntityMethodManager($adb); 
$emm->addEntityMethod("Project", "Update Project from Template", "modules/com_vtiger_workflow/custom_wf_projects.inc.php", "customProjectFromTemplate");

?>
