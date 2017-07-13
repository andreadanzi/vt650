<?php

/* * *******************************************************************************
 * The content of this file is subject to the MultiCompany4you license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class MultiCompany4you_CustomRecordNumbering_Model extends Vtiger_Module_Model {

    /**
     * Function to get focus of this object
     * @return <type>
     */
    public function getFocus() {
        if (!$this->focus) {
            $this->focus = CRMEntity::getInstance($this->getName());
        }
        return $this->focus;
    }

    /**
     * Function to get Instance of this module
     * @param <String> $moduleName
     * @return <MultiCompany4you_CustomRecordNumbering_Model> $moduleModel
     */
    public static function getInstance($moduleName, $tabId = false) {
        $moduleModel = new self();
        $moduleModel->name = $moduleName;
        if ($tabId) {
            $moduleModel->id = $tabId;
        }
        return $moduleModel;
    }

    /**
     * Function to ger Supported modules for Custom record numbering
     * @return <Array> list of supported modules <Vtiger_Module_Model>
     */
    public static function getSupportedModules() {
        $db = PearDatabase::getInstance();
        $modulesModels = Array();
        $sql = "SELECT tabid, name, tab_id FROM vtiger_tab LEFT JOIN its4you_multicompany4you_cn_modules ON tab_id=tabid WHERE isentitytype = ? AND presence = ? AND tabid IN (SELECT DISTINCT tabid FROM vtiger_field WHERE uitype = ?)";
        $result = $db->pquery($sql, array(1, 0, 4));
        //$numOfRows = $db->num_rows($result);
        while ($row = $db->fetchByAssoc($result)) {
            $modulesModels[$row['tabid']] = $row;
        }
        /* for ($i = 0; $i < $numOfRows; $i++) {
          $tabId = $db->query_result($result, $i, 'tabid');
          $modulesModels[$tabId] = MultiCompany4you_CustomRecordNumbering_Model::getInstance($db->query_result($result, $i, 'name'), $tabId);
          } */

        return $modulesModels;
    }

    /**
     * Function to ger Supported modules for Custom record numbering
     * @return <Array> list of supported modules <Vtiger_Module_Model>
     */
    public static function getAllowedModules() {
        $db = PearDatabase::getInstance();

        $sql = "SELECT tabid, name FROM its4you_multicompany4you_cn_modules INNER JOIN vtiger_tab ON tabid=tab_id WHERE isentitytype = ? AND presence = ?";
        $result = $db->pquery($sql, array(1, 0));
        $numOfRows = $db->num_rows($result);
        $modulesModels = Array();
        for ($i = 0; $i < $numOfRows; $i++) {
            $tabId = $db->query_result($result, $i, 'tabid');
            $modulesModels[$tabId] = MultiCompany4you_CustomRecordNumbering_Model::getInstance($db->query_result($result, $i, 'name'), $tabId);
        }

        return $modulesModels;
    }

    /**
     * Function to get module custom numbering data
     * @return <Array> data of custom numbering data
     */
    public function getModuleCustomNumberingData($companyid = '') {
        $adb = PearDatabase::getInstance();
        if ($companyid < 1) {
            $companyid = $_REQUEST['companyid'];
        }
        $result = $adb->pquery("SELECT cur_id, prefix FROM its4you_multicompany4you_cn WHERE tab_id=? AND companyid=? AND active = 1", array(getTabId($this->getName()), $companyid));
        return $adb->fetchByAssoc($result);
    }

    /**
     * Function to set Module sequence
     * @return <Array> result of success
     */
    public function setModuleSequence($companyid = '') {
        if ($companyid < 1) {
            $companyid = $_REQUEST['companyid'];
        }
        $moduleName = $this->getName();
        $prefix = $this->get('prefix');
        $sequenceNumber = $this->get('sequenceNumber');

        $status = $this->setModuleSeqNumber('configure', $moduleName, $prefix, $sequenceNumber, $companyid);

        $success = array('success' => $status);
        if (!$status) {
            $db = PearDatabase::getInstance();
            $result = $db->pquery("SELECT cur_id FROM its4you_multicompany4you_cn WHERE tab_id = ? AND companyid=? AND prefix = ?", array(getTabId($moduleName), $companyid, $prefix));
            $success['sequenceNumber'] = $db->query_result($result, 0, 'cur_id');
        }

        return $success;
    }

    /**
     * Function to update record sequences which are under this module
     * @return <Array> result of success
     */
    public function updateRecordsWithSequence($companyid) {
        //return $this->getFocus()->updateMissingSeqNumber($this->getName());
        return $this->updateMissingSeqNumber($this->getName(), $companyid);
    }

    public static function getCompanyRoleForUser($user_id = '') {
        if ($user_id == '') {
            global $current_user;
            $user_id = $current_user->id;
        }
        $adb = PearDatabase::getInstance();
        $foundrole = '';
        $roleres = $adb->pquery("SELECT parentrole FROM vtiger_user2role INNER JOIN vtiger_role ON vtiger_role.roleid=vtiger_user2role.roleid WHERE userid=?", array($user_id));
        if ($adb->num_rows($roleres) > 0) {
            $row = $adb->fetchByAssoc($roleres);
            $parentrole = $row['parentrole'];
            $UsedRoles = Array();
            $used_roles_res = @$adb->pquery("SELECT parentrole FROM vtiger_role WHERE roleid IN (SELECT role FROM its4you_multicompany4you WHERE role IS NOT NULL)");
            if ($used_roles_res !== false) {
                while ($used_roles_row = $adb->fetchByAssoc($used_roles_res)) {
                    $UsedRoles[] = $used_roles_row['parentrole'];
                }
            }
            $Exploded = explode('::', $parentrole);
            $nextrole = '';
            foreach ($Exploded as $addtorole) {
                if ($nextrole != '')
                    $nextrole .= '::';
                $nextrole .= $addtorole;
                if (in_array($nextrole, $UsedRoles)) {
                    $foundrole = $addtorole;
                }
            }
        }
        return $foundrole;
    }

    public static function getCompanyForRole($role_id) {
        $adb = PearDatabase::getInstance();
        $companyid = '';
        $res = $adb->pquery("SELECT companyid FROM its4you_multicompany4you WHERE role=?", array($role_id));
        if ($adb->num_rows($res) > 0) {
            $row = $adb->fetchByAssoc($res);
            $companyid = $row['companyid'];
        }
        return $companyid;
    }

    public static function getCompanyForUser($user_id) {
        $companyid = self::getCompanyForRole(self::getCompanyRoleForUser($user_id));
        return $companyid;
    }

    public static function getCompanyRole($companyid) {
        if (!$companyid) {
            return false;
        }
        $adb = PearDatabase::getInstance();
        $foundrole = '';
        $res = $adb->pquery("SELECT role FROM its4you_multicompany4you WHERE companyid=?", array($companyid));
        if ($adb->num_rows($res) > 0) {
            $row = $adb->fetchByAssoc($res);
            $foundrole = $row['role'];
        }
        return $foundrole;
    }

    public function setModuleSeqNumber($mode, $module, $req_str = '', $req_no = '', $companyid) {
        global $adb;
        $module = $this->getName();
        $tabid = getTabId($module);
        //when we configure the invoice number in Settings this will be used
        if ($mode == "configure" && $req_no != '') {
            $check = $adb->pquery("select cur_id from its4you_multicompany4you_cn where tab_id=? and prefix = ? AND companyid=?", array($tabid, $req_str, $companyid));
            if ($adb->num_rows($check) == 0) {
                $adb->pquery("UPDATE its4you_multicompany4you_cn SET active=0 where tab_id=? and active=1 AND companyid=?", array($tabid, $companyid));
                $adb->pquery("INSERT into its4you_multicompany4you_cn values(?,?,?,?,?,?)", array($companyid, $tabid, $req_str, $req_no, $req_no, 1));
                return true;
            } else if ($adb->num_rows($check) != 0) {
                $num_check = $adb->query_result($check, 0, 'cur_id');
                if ($req_no < $num_check) {
                    return false;
                } else {
                    $adb->pquery("UPDATE its4you_multicompany4you_cn SET active=0 where active=1 and tab_id=? AND companyid=?", array($tabid, $companyid));
                    $adb->pquery("UPDATE its4you_multicompany4you_cn SET cur_id=?, active = 1 where prefix=? and tab_id=? AND companyid=?", array($req_no, $req_str, $tabid, $companyid));
                    return true;
                }
            }
        } else if ($mode == "increment") {
            //when we save new invoice we will increment the invoice id and write
            $check = $adb->pquery("select cur_id, prefix from its4you_multicompany4you_cn where tab_id=? and active = 1 AND companyid=?", array($tabid, $companyid));
            $prefix = str_replace(array('$year$', '$month$', '$week$', '$day$'), array(date('Y'), date('m'), date('W'), date('d')), $adb->query_result($check, 0, 'prefix'));
            $curid = $adb->query_result($check, 0, 'cur_id');
            $prev_inv_no = $prefix . $curid;
            $strip = strlen($curid) - strlen($curid + 1);
            if ($strip < 0)
                $strip = 0;
            $temp = str_repeat("0", $strip);
            $req_no.= $temp . ($curid + 1);
            $adb->pquery("UPDATE its4you_multicompany4you_cn SET cur_id=? where cur_id=? and active=1 AND tab_id=? AND companyid=?", array($req_no, $curid, $tabid, $companyid));
            return decode_html($prev_inv_no);
        }
    }

    private function isModuleSequenceConfigured($module, $companyid) {
        $adb = PearDatabase::getInstance();
        $result = $adb->pquery('SELECT 1 FROM its4you_multicompany4you_cn WHERE tab_id = ? AND active = 1 AND companyid=?', array(getTabId($module), $companyid));
        if ($result && $adb->num_rows($result) > 0) {
            return true;
        }
        return false;
    }

    private function getModuleSeqInfo($module, $companyid) {
        global $adb;
        $check = $adb->pquery("select cur_id, prefix from its4you_multicompany4you_cn where tab_id=? and active = 1 AND companyid=?", array(getTabId($module), $companyid));
        $prefix = $adb->query_result($check, 0, 'prefix');
        $curid = $adb->query_result($check, 0, 'cur_id');
        return array($prefix, $curid);
    }

    private function updateMissingSeqNumber($module, $companyid) {
        global $log, $adb;

        vtlib_setup_modulevars($module, $this);

        if (!$this->isModuleSequenceConfigured($module, $companyid))
            return;

        $tabid = getTabid($module);
        $fieldinfo = $adb->pquery("SELECT * FROM vtiger_field WHERE tabid = ? AND uitype = 4", Array($tabid));

        $returninfo = Array();

        if ($fieldinfo && $adb->num_rows($fieldinfo)) {
            // TODO: We assume the following for module sequencing field
            // 1. There will be only field per module
            // 2. This field is linked to module base table column
            $fld_table = $adb->query_result($fieldinfo, 0, 'tablename');
            $fld_column = $adb->query_result($fieldinfo, 0, 'columnname');

            if ($fld_table == $this->table_name) {
                $records = $adb->query("SELECT $this->table_index AS recordid FROM $this->table_name " .
                        "WHERE $fld_column = '' OR $fld_column is NULL");

                if ($records && $adb->num_rows($records)) {
                    $returninfo['totalrecords'] = $adb->num_rows($records);
                    $returninfo['updatedrecords'] = 0;

                    $modseqinfo = $this->getModuleSeqInfo($module, $companyid);
                    $prefix = str_replace(array('$year$', '$month$', '$week$', '$day$'), array(date('Y'), date('m'), date('W'), date('d')), $modseqinfo[0]);
                    $cur_id = $modseqinfo[1];

                    $old_cur_id = $cur_id;
                    while ($recordinfo = $adb->fetch_array($records)) {
                        $value = "$prefix" . "$cur_id";
                        $adb->pquery("UPDATE $fld_table SET $fld_column = ? WHERE $this->table_index = ?", Array($value, $recordinfo['recordid']));
                        $cur_id += 1;
                        $returninfo['updatedrecords'] = $returninfo['updatedrecords'] + 1;
                    }
                    if ($old_cur_id != $cur_id) {
                        $adb->pquery("UPDATE its4you_multicompany4you_cn set cur_id=? where tab_id=? and active=1 AND companyid=?", Array($cur_id, getTabId($module), $companyid));
                    }
                }
            } else {
                $log->fatal("Updating Missing Sequence Number FAILED! REASON: Field table and module table mismatching.");
            }
        }
        return $returninfo;
    }

    public function decrementStandardNumbering($moduleName) {
        $adb = PearDatabase::getInstance();
        $res = $adb->pquery("SELECT cur_id FROM vtiger_modentity_num WHERE semodule=? AND active='1'", array($moduleName));
        $row = $adb->fetchByAssoc($res);
        $old_id_strlen = strlen($row['cur_id']);
        $cur_id = $row['cur_id'] - 1;
        $cur_id = str_repeat('0', $old_id_strlen - strlen($cur_id)).$cur_id;
        $adb->pquery("UPDATE vtiger_modentity_num SET cur_id='$cur_id' WHERE semodule=? AND active='1'", array($moduleName));
    }

}
