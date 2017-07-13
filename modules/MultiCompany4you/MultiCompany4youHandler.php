<?php

/* * *******************************************************************************
     *** ***
 * ****************************************************************************** */
require_once('include/utils/utils.php');

class MultiCompany4youHandler extends VTEventHandler {

    public function handleEvent($handlerType, $entityData) {
        if ($entityData->focus->mode != 'edit') {
            $adb = PearDatabase::getInstance();
            $modulename = $entityData->getModuleName();
            $resTab = $adb->pquery("SELECT tabid FROM vtiger_tab INNER JOIN  its4you_multicompany4you_cn_modules ON tabid=tab_id WHERE name=?", array($modulename));
            if ($adb->num_rows($resTab) > 0) { 
                $rowTab = $adb->fetchByAssoc($resTab);
                $tabid = $rowTab['tabid'];
                $customNumberingInstance = MultiCompany4you_CustomRecordNumbering_Model::getInstance($modulename, $tabid);
                $companyId =  MultiCompany4you_CustomRecordNumbering_Model::getCompanyForUser($entityData->focus->column_fields['assigned_user_id']);
                // get companyname for filter
                $resCompany = $adb->pquery("SELECT companyname FROM its4you_multicompany4you WHERE companyid=?", array($companyId));
                $companyname = "";
                if ($adb->num_rows($resCompany) > 0) {
                    $rowCompany = $adb->fetchByAssoc($resCompany);
                    $companyname = $rowCompany['companyname'];
                }
                //
                $customRecordNumber = $customNumberingInstance->setModuleSeqNumber("increment", $modulename, '', '',$companyId);
                if ($customRecordNumber) {
                    $resColumn = $adb->pquery("SELECT columnname, tablename FROM vtiger_field WHERE tabid = ? AND uitype = 4", Array($tabid));
                    if ($adb->num_rows($resColumn) > 0) {
                        $rowCol = $adb->fetchByAssoc($resColumn);
                        $adb->query("UPDATE " .  $rowCol['tablename'] . "  SET " . $rowCol['columnname'] . "='" . $customRecordNumber . "' WHERE " . $entityData->focus->table_index . "=" . $entityData->focus->id);
                        $customNumberingInstance->decrementStandardNumbering($modulename);
                    }
		    // danzi.tn#13 - 20170713 - set Gruppo Interno based on MultiCompany
                    include('dnz.config.php');
		    global $log;
                    $resColumn = $adb->pquery("SELECT columnname , tablename FROM vtiger_field WHERE tabid = ? AND fieldlabel=?", Array($tabid, $dnzFieldLabel4MultiCompany));
                    if ($adb->num_rows($resColumn) > 0) {
                        $rowCol = $adb->fetchByAssoc($resColumn);
                        $sqlUpdate = "UPDATE " .  $rowCol['tablename'] . "  SET " . $rowCol['columnname'] . "='" . $companyname . "' WHERE " . $entityData->focus->table_index . "=" . $entityData->focus->id;
                        $log->debug("MultiCompany4youHandler SQL UPDATE ".$sqlUpdate);
                        $adb->query($sqlUpdate);
                    }
                    // danzi.tn#13 - end
                }
            }
        }
    }
    
}
