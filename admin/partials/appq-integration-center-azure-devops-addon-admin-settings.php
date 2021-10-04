<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://bitbucket.org/%7B1c7dab51-4872-4f3e-96ac-11f21c44fd4b%7D/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Azure_Devops_Addon
 * @subpackage Appq_Integration_Center_Azure_Devops_Addon/admin/partials
 */
?>
<form id="azure_devops_settings" class="container-fluid">
	<h3> <?= __('Azure DevOps Integration Settings', 'appq-integration-center-azure-devops-addon'); ?></h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group row">
                <label for="azure_devops_endpoint" class="col-sm-2 col-form-label"><?= __('Endpoint', 'appq-integration-center-azure-devops-addon'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="azure_devops_endpoint" id="azure_devops_endpoint" value="<?= !empty($config) ? $config->endpoint : ''?>" placeholder="https://dev.azure.com/{organization}/{project}/_apis">
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="form-group row">
                <label for="azure_devops_pat" class="col-sm-3 col-form-label"><?= _x('Personal Access Token', 'integration setting', 'appq-integration-center-azure-devops-addon'); ?></label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="azure_devops_apikey" id="azure_devops_apikey" value="<?= !empty($config) ? $config->apikey : ''?>"  placeholder="••••••••••">
                </div>
            </div>
        </div>
    </div>
	<?php 
	$field_mapping = !empty($config) ? json_decode($config->field_mapping,true) : array();
	if (empty($field_mapping)) {
		$field_mapping = array();
	}
	$this->partial('settings/field-mapping', array(
		'field_mapping' => $field_mapping,
		'campaign_id' => $campaign_id
	)) ?>
	<div class="row">
		<button type="button" class="save col-sm-2 offset-sm-10 btn btn-primary"><?= _x('Save', 'save integration settings', 'appq-integration-center-azure-devops-addon'); ?></button>
	</div>
</form>
