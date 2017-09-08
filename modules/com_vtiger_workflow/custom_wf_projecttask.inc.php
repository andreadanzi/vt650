<?php
require_once('include/database/PearDatabase.php');
require_once 'include/utils/utils.php';
require_once 'modules/Emails/mail.php';

// danzi.tn#19 - 20170907 - custom function for notification on task completion

function notifyProjectTask($entity){
    global $adb, $log;
        
    $id = $entity->data['id'];
    $id_splitted = explode('x',$id);
    $projectTaskId = $id_splitted[1];
    $log->debug("Entering notifyProjectTask projectTaskId=".$projectTaskId);
    $taskName = $entity->data['projecttaskname'];
    $log->debug("\tnotifyProjectTask projectname=".$taskName);
    $taskStatus = $entity->data['projecttaskstatus'];
    $log->debug("\tnotifyProjectTask taskStatus=".$taskStatus);

    $log->debug("notifyProjectTask status is Completed!");
    $sQuery = "SELECT 
                            vtiger_project.projectname, 
                            vtiger_project.projectid,
                            vtiger_projecttask.projecttaskname,
                            vtiger_projecttask.projecttaskid,
                            vtiger_account.accountname, 
                            vtiger_account.accountid , 
                            vtiger_account.email1 , 
                            vtiger_contactdetails.contactid, 
                            vtiger_contactdetails.firstname, 
                            vtiger_contactdetails.lastname, 
                            vtiger_contactdetails.email
                    FROM vtiger_project 
                        JOIN vtiger_crmentity 
                            ON vtiger_crmentity.crmid=vtiger_project.projectid AND deleted=0 
                        JOIN vtiger_projecttask 
                            ON vtiger_projecttask.projectid = vtiger_project.projectid
                        LEFT JOIN vtiger_account
                            ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts
                        LEFT JOIN vtiger_contactdetails
                            ON vtiger_contactdetails.contactid = vtiger_project.linktoaccountscontacts
                    WHERE 
                        vtiger_projecttask.projecttaskid = ?";
                        
    $log->debug("notifyProjectTask sQuery ".$sQuery);
    $projectres = $adb->pquery( $sQuery, array($projectTaskId));
    $noOfRows = $adb->num_rows($projectres);
    for($i=0; $i<$noOfRows; ++$i) {
	    $row = $adb->query_result_rowdata($projectres, $i);
	    
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$rootDirectory =  vglobal('root_directory');
	    $fromEmail = $currentUserModel->get('email1');
	    $userName = $currentUserModel->getName();
        $log->debug("userName=".$userName);
        $log->debug("fromEmail=".$fromEmail);
        
	    if( !empty($row['accountid'] ) ){
		    $log->debug("notifyProjectTask accountid is " . $row['accountid']);
		    
		    $templsql = "SELECT subject, body FROM vtiger_emailtemplates WHERE templatename = 'ProjectTask Notify Account'";
            $templres = $adb->query($templsql);
            if($adb->num_rows($templres) > 0) {
            
                $subject = $adb->query_result($templres, 0, 'subject');
                $body = $adb->query_result($templres, 0, 'body');
                

                $body = getMergedDescription($body, $row['accountid'], 'Accounts');
                $body = getMergedDescription($body, $row['projectid'], 'Project');
                $body = getMergedDescription($body, $row['projecttaskid'], 'ProjectTask');
                
                
                $subject = getMergedDescription($subject, $row['accountid'], 'Accounts');
                $subject = getMergedDescription($subject, $row['projectid'], 'Project');
                $subject = getMergedDescription($subject, $row['projecttaskid'], 'ProjectTask');
                
                $status = send_mail("Accounts",$row["email1"],$userName,$fromEmail,$subject,$body,'',$fromEmail );
                if( $status ) {
                    $entityId = $row['accountid'];
                    $to_email = $row["email1"];
                    $moduleName = 'Emails';
	        		$userId =  $currentUserModel->get('id');
        			$emailFocus = CRMEntity::getInstance($moduleName);
        			$emailFieldValues = array(
					        'assigned_user_id' => $userId,
					        'subject' => $subject,
					        'description' => $body,
					        'from_email' => $fromEmail,
					        'saved_toid' => $to_email,
					        'parent_id' => $entityId."@$userId|",
					        'email_flag' => 'SENT',
					        'activitytype' => $moduleName,
					        'date_start' => date('Y-m-d'),
					        'time_start' => date('H:i:s'),
					        'mode' => '',
					        'id' => ''
			        );
			        $emailFocus->column_fields = $emailFieldValues;
			        $emailFocus->save($moduleName);
                    $emailId = $emailFocus->id;
                    
                   	if(!empty($emailId)) {
	        			$emailFocus->setEmailAccessCountValue($emailId);
        			}		
    			}                
                $log->debug("notifyProjectTask send mail to Accounts status is " . $status);
            }
		    
	    }
	    if( !empty($row['contactid'] ) ){
		    $log->debug("notifyProjectTask contactid is " . $row['contactid']);
		    
		    $templsql = "SELECT subject,body FROM vtiger_emailtemplates WHERE templatename = 'ProjectTask Notify Contact'";
            $templres = $adb->query($templsql);
            if($adb->num_rows($templres) > 0) {
            
                $subject = $adb->query_result($templres, 0, 'subject');
                $body = $adb->query_result($templres, 0, 'body');
                
                $body = getMergedDescription($body, $row['contactid'], 'Contacts');
                $body = getMergedDescription($body, $row['projectid'], 'Project');
                $body = getMergedDescription($body, $row['projecttaskid'], 'ProjectTask');
                
                $subject = getMergedDescription($subject, $row['contactid'], 'Contacts');
                $subject = getMergedDescription($subject, $row['projectid'], 'Project');
                $subject = getMergedDescription($subject, $row['projecttaskid'], 'ProjectTask');
                
                $status = send_mail("Contacts",$row["email"],$userName,$fromEmail,$subject,$body,'',$fromEmail );
                if( $status ) {
                    $entityId = $row['contactid'];
                    $to_email = $row["email"];
                    $moduleName = 'Emails';
	        		$userId =  $currentUserModel->get('id');
        			$emailFocus = CRMEntity::getInstance($moduleName);
        			$emailFieldValues = array(
					        'assigned_user_id' => $userId,
					        'subject' => $subject,
					        'description' => $body,
					        'from_email' => $fromEmail,
					        'saved_toid' => $to_email,
					        'parent_id' => $entityId."@$userId|",
					        'email_flag' => 'SENT',
					        'activitytype' => $moduleName,
					        'date_start' => date('Y-m-d'),
					        'time_start' => date('H:i:s'),
					        'mode' => '',
					        'id' => ''
			        );
			        $emailFocus->column_fields = $emailFieldValues;
			        $emailFocus->save($moduleName);
                    $emailId = $emailFocus->id;
                    
                   	if(!empty($emailId)) {
	        			$emailFocus->setEmailAccessCountValue($emailId);
        			}		
    			}                
                
                $log->debug("notifyProjectTask send mail to Contacts status is " . $status);
            }
	    }
    }
    $log->debug("notifyProjectTask terminated!");
}
?>
