<?php
/*
 * @Author: Davide Bizzi <clochard>
 * @Date:   26/05/2020
 * @Filename: delete_bugs.php
 * @Last modified by:   clochard
 * @Last modified time: 2020-06-08T10:34:25+02:00
 */




function appq_azure_devops_delete_bugs($cp_id, $bugtracker_id){
	$api = new AzureDevOpsRestApi($cp_id);

	return $api->delete_issue($bugtracker_id);
}
