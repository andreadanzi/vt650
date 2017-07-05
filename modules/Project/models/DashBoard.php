<?php
// danzi.tn#8 - 20170705 - dashboards for project module
class Project_DashBoard_Model extends Vtiger_DashBoard_Model {
	public function getDefaultWidgets() {
		$moduleModel = $this->getModule();
		$parentWidgets = parent::getDefaultWidgets();

		$widgets[] = array(
			'contentType' => 'json',
			'title' => 'Project by status',
			'mode' => 'open',
			'url' => 'module='. $moduleModel->getName().'&view=ShowWidget&mode=ProjectsByStatus'
		);

		foreach($widgets as $widget) {
			$widgetList[] = Vtiger_Widget_Model::getInstanceFromValues($widget);
		}

		return $widgetList;
	}
}
