<?php
require_once('include/database/PearDatabase.php');
require_once 'include/utils/utils.php';

// danzi.tn#9 - 20170705 - custom function for project creation based on template

function customProjectFromTemplate($entity){
	global $adb, $log;
    
	$id = $entity->data['id'];
	$id_splitted = explode('x',$id);
	$projectId = $id_splitted[1];
    $log->debug("Entering customProjectFromTemplate potentialsid=".$projectId);
    
	$projectname = $entity->data['projectname'];
    $log->debug("\tcustomProjectFromTemplate projectname=".$projectname);
	
	$projecttype = $entity->data['projecttype'];
	if( $projecttype=='Coflyer') {
	    $log->debug("customProjectFromTemplate: OK project type is Coflyer");
    } else {
        $log->error("customProjectFromTemplate: KO project type != Coflyer - projecttype=".$projecttype);
    }
    $log->debug("customProjectFromTemplate terminated!");
}
?>
