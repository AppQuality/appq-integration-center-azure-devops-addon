<?php

function appq_azure_devops_edit_settings()
{
	if(!check_ajax_referer('appq-ajax-nonce', 'nonce', false)){
        wp_send_json_error('You don\'t have the permission to do this');
	}
	global $wpdb;
	$cp_id = array_key_exists('cp_id', $_POST) ? intval($_POST['cp_id']) : false;
	$endpoint = array_key_exists('azure_devops_endpoint', $_POST) ? $_POST['azure_devops_endpoint'] : '';
	$apikey = array_key_exists('azure_devops_apikey', $_POST) ? $_POST['azure_devops_apikey'] : '';
	
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
        'is_active' => 1,
	), array(
		'integration' => 'azure-devops',
		'campaign_id' => $cp_id,
	));
	
	$sql = 'UPDATE '.$wpdb->prefix .'appq_integration_center_config
	SET is_active = 0
	WHERE campaign_id = %d AND integration != "azure-devops";';
	$sql = $wpdb->prepare($sql,$cp_id);
	
	$wpdb->query($sql);
	
	wp_send_json_success('ok');
}

add_action('wp_ajax_appq_azure_devops_edit_settings', 'appq_azure_devops_edit_settings');
