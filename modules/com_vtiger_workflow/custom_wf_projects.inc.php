<?php
require_once('include/database/PearDatabase.php');
require_once 'include/utils/utils.php';
// danzi.tn#9 - 20170705 - custom function for project creation based on template

/*
TABID
-- 42 Project
-- 40 ProjectMilestones
-- 41 ProjectTask
*/
function customProjectFromTemplate($entity){
    include('dnz.config.php');
	global $adb, $log;
    

    
	$id = $entity->data['id'];
	$id_splitted = explode('x',$id);
	$projectId = $id_splitted[1];
    $log->debug("Entering customProjectFromTemplate potentialsid=".$projectId);
    
	$projectname = $entity->data['projectname'];
    $log->debug("\tcustomProjectFromTemplate projectname=".$projectname);
	
	$projecttype = $entity->data['projecttype'];
	if( array_key_exists($projecttype, $dnzProjectTypeMap) ) {
	    $projectName = $dnzProjectTypeMap[$projecttype];
	    $log->debug("customProjectFromTemplate: OK project type is ".$projecttype. " associated to template ". $projectName);
	    $projectres = $adb->pquery( 'SELECT projectid FROM vtiger_project JOIN vtiger_crmentity ON crmid=projectid AND deleted=0 WHERE projectname=?', array($projectName));
	    if($adb->num_rows($projectres) > 0) {
	    

	    	$currentFocus = CRMEntity::getInstance('Project');
		    $currentFocus->id = $projectId;
		    $currentFocus->retrieve_entity_info($projectId, 'Project');
		    
    	    $templateId = $adb->query_result($projectres, 0, 'projectid');		    
	    	$templateFocus = CRMEntity::getInstance('Project');
		    $templateFocus->id = $projectId;
		    $templateFocus->retrieve_entity_info($templateId, 'Project');
		
	        
		    $column_names = array();
    		foreach($templateFocus->column_fields as $fieldName=>$fieldValue) {
	
			        if( !empty($templateFocus->column_fields[$fieldName] ) &&
			            empty($currentFocus->column_fields[$fieldName] ) && 
			            ! in_array($fieldName, array('projectname','assigned_user_id','projecttype','createdtime','modifiedtime','linktoaccountscontacts') )  ) {
				        // $currentRecordModel->set($fieldName, $templateFieldModel->getDBInsertValue($fieldValue));
				        $currentFocus->column_fields[$fieldName] = $fieldValue;
				        $log->debug("customProjectFromTemplate.templateFieldList field: ".$fieldName." set to " .$fieldValue);
				        $column_names[$fieldName] = $fieldValue;
			        } else {
			            $log->debug("customProjectFromTemplate.templateFieldList non editable field: ".$fieldName);
                    }
			       		       
		    }
		    // set dates accordingly to..
		    
		    $projectStartdate = $currentFocus->column_fields["startdate"];
		    $templateStartdate = $templateFocus->column_fields["startdate"];
		    $log->debug("customProjectFromTemplate: templateStartdate=".$templateStartdate." type is ".gettype($templateStartdate));
		    $pStart = strtotime($projectStartdate);
		    $tStart = strtotime($templateStartdate);
		    $log->debug("customProjectFromTemplate.templateFieldList column_names = ".print_r($column_names,True));
		    $relatedQuery = "SELECT
                                vtiger_crmentityrel.relcrmid,
                                vtiger_crmentityrel.relmodule
                                FROM
                                vtiger_project
                                JOIN
                                vtiger_crmentity on vtiger_crmentity.crmid = vtiger_project.projectid AND vtiger_crmentity.deleted = 0
                                JOIN
                                vtiger_crmentityrel on vtiger_crmentityrel.crmid  = vtiger_project.projectid 
                                WHERE
                                vtiger_crmentityrel.relmodule IN ('ProjectTask','ProjectMilestone')
                                AND vtiger_project.projectid = ?";
		    $relatedRes = $adb->pquery($relatedQuery , array($templateId));
    		$noOfRows = $adb->num_rows($relatedRes);

		    for($i=0; $i<$noOfRows; ++$i) {
			    $row = $adb->query_result_rowdata($relatedRes, $i);
                $log->debug("customProjectFromTemplate.templateFieldList relatedRes: ".print_r($row,True));
                $relatedFocus = CRMEntity::getInstance($row['relmodule']);
		        $relatedFocus->id = $row['relcrmid'];
		        $relatedFocus->retrieve_entity_info($row['relcrmid'], $row['relmodule']);
		        
		        $newRelated = CRMEntity::getInstance($row['relmodule']);
	            vtlib_setup_modulevars($row['relmodule'],$newRelated);
	            foreach($relatedFocus->column_fields as $fieldName=>$fieldValue) {
	                $newRelated->column_fields[$fieldName] = $fieldValue;
	            }
	            $newRelated->column_fields['assigned_user_id'] = $currentFocus->column_fields['assigned_user_id'];
		        $newRelated->column_fields['createdtime'] = $currentFocus->column_fields['createdtime'];
        		$newRelated->column_fields['modifiedtime'] = $currentFocus->column_fields['modifiedtime'];
        		$newRelated->column_fields['modifiedby'] = $currentFocus->column_fields['modifiedby'];
        		$newRelated->column_fields['projectid'] = $projectId;
        		// ProjectTask startdate, enddate
        		if($row['relmodule']=='ProjectTask') {
                
        		    $relStartDate = $relatedFocus->column_fields['startdate'];
        		    if( !empty($relStartDate) && !empty($templateStartdate) ) {
		                $rStart = strtotime($relStartDate);
		                $newStart = $pStart + ( $tStart - $rStart );
            		    $newRelated->column_fields['startdate'] = date("Y-m-d",$newStart);
        		    }
        		    $relEndDate = $relatedFocus->column_fields['enddate'];
                    if( !empty($relEndDate) && !empty($templateStartdate) ) {
		                $rEnd = strtotime($relEndDate);
       		            $newEnd = $pStart + ( $rEnd - $rStart );
            		    $newRelated->column_fields['enddate'] = date("Y-m-d",$newEnd);
        		    }
        		    $log->debug("customProjectFromTemplate: relStartDate=".$relStartDate." type is ".gettype($relStartDate));
                }
        		// ProjectMilestone projectmilestonedate
        		if($row['relmodule']=='ProjectMilestone') {
        		    $relMilestonedate = $newRelated->column_fields['projectmilestonedate'];
        		    if( !empty($relMilestonedate) && !empty($templateStartdate)) {
		                $mEnd = strtotime($relMilestonedate);
       		            $newEnd = $pStart + ( $rEnd - $mEnd );
            		    $newRelated->column_fields['projectmilestonedate'] = date("Y-m-d",$newEnd);
        		    }
                }
        		$newRelated->save($module_name=$row['relmodule']);
        		$currentFocus->save_related_module('Project',$projectId,$row['relmodule'],$newRelated->id);
		    }
		    
	    	$currentFocus->mode = 'edit';
		    $currentFocus->save($module_name='Project');
		} else {
    		$log->error("customProjectFromTemplate: project with project name ".$projectName. " does not exists!");
		}
	    
    } else {
        $log->error("customProjectFromTemplate: KO project type ".$projecttype. " without template mapping");
    }
    $log->debug("customProjectFromTemplate terminated!");
}
?>
