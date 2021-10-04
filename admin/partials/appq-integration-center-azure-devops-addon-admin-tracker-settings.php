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
<form id="azure-devops_tracker_settings">
    <div class="form-group mt-5">
        <?php
        printf('<label for="azure_devops_endpoint">%s</label>', __('Endpoint', 'appq-integration-center-azure-devops-addon'));
        printf('<input type="text" class="form-control" name="azure_devops_endpoint" id="azure_devops_endpoint" placeholder="%s" value="%s">', __('https://yourcompanyname.atlassian.com', $this->plugin_name), !empty($config) ? $config->endpoint : '');
        ?>
    </div>
    <div class="form-group">
        <?php
        printf('<label for="azure_devops_apikey">%s</label>', __('Authentication', 'appq-integration-center-azure-devops-addon'));
        printf('<input type="text" class="form-control" name="azure_devops_apikey" id="azure_devops_apikey" placeholder="%s" value="%s">', __('PERSONAL ACCESS TOKEN', $this->plugin_name), !empty($config) ? $config->apikey : '');
        ?>
    </div>
</form>
