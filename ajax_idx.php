<?php
	$board_id = 1;
	require('config.php');

	require('engine/engine.php');
	$act = $_POST['act'];
	switch ($act)
	{
		case 'setModuleEditMode':
		{
			$m_name = $_POST['m_name'];
			modules::switchToEditMode($m_name);
		} break;
		case 'saveModuleForm':
		{
			$m_name = $_POST['m_name'];
			modules::saveModuleForm($m_name);
		} break;
        case 'refreshModule':
		{
			$m_name = $_POST['m_name'];
			modules::getContent($m_name);
		} break;
		case 'moduleAction':
		{			$m_name = $_POST['m_name'];
			modules::onAction($m_name);
		}
	}
?>
