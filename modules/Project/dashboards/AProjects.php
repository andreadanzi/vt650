<?php
/*+***********************************************************************************
 danzi.tn#8 - 20170705 - dashboards for project module
 *************************************************************************************/

class Project_AProjects_Dashboard extends Vtiger_IndexAjax_View {


    public function process(Vtiger_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$linkId = $request->get('linkid');
		
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		
		$data = array();
		
		$widget = Vtiger_Widget_Model::getInstance($linkId, $currentUser->getId());
		
		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('DATA', $data);
		
		$viewer->assign('CURRENTUSER', $currentUser);
		
		$viewer->view('dashboards/AProjects.tpl', $moduleName);		
		
		
	}
}
