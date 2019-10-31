<?php

function appq_azure_devops_edit_settings()
{
	global $wpdb;
	$cp_id = array_key_exists('cp_id', $_POST) ? intval($_POST['cp_id']) : false;
	$endpoint = array_key_exists('azure_devops_endpoint', $_POST) ? $_POST['azure_devops_endpoint'] : '';
	$apikey = array_key_exists('azure_devops_apikey', $_POST) ? $_POST['azure_devops_apikey'] : '';
	$field_mapping = array_key_exists('field_mapping', $_POST) ? $_POST['field_mapping'] : new stdClass();
	foreach ($field_mapping as $key => $value) {
		$field_mapping[$key] = stripslashes($value);
	}
	$field_mapping = (json_encode($field_mapping));
	
	$has_value = intval($wpdb->get_var(
		$wpdb->prepare('SELECT COUNT(*) FROM ' .$wpdb->prefix .'appq_integration_center_config WHERE integration = "azure-devops" AND campaign_id = %d', $cp_id)
	));
	if ($has_value === 0) {
		$wpdb->insert($wpdb->prefix .'appq_integration_center_config', array(
			'integration' => 'azure-devops',
			'campaign_id' => $cp_id,
		));
	}
	$wpdb->update($wpdb->prefix .'appq_integration_center_config', array(
		'endpoint' => $endpoint,
		'apikey' => $apikey,
		'field_mapping' => $field_mapping,
	), array(
		'integration' => 'azure-devops',
		'campaign_id' => $cp_id,
	));
	wp_send_json_success('ok');
}

add_action('wp_ajax_appq_azure_devops_edit_settings', 'appq_azure_devops_edit_settings');
