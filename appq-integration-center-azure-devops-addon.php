<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/AppQuality/
 * @since             1.0.0
 * @package           Appq_Integration_Center_Azure_Devops_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Integration Center - Azure DevOps
 * Plugin URI:        bitbucket/appq-integration-center-azure-devops-addon
 * Description:       Extend Integration Center with Azure DevOps
* Version:           1.1.3
 * Author:            Unguess Team
 * Author URI:        https://github.com/AppQuality/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       appq-integration-center-azure-devops-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'APPQ_INTEGRATION_CENTER_AZURE_DEVOPS_ADDON_VERSION', '1.0.0' );

define('APPQ_INTEGRATION_CENTER_AZURE_DEVOPS_URL', plugin_dir_url( __FILE__ ));
define('APPQ_INTEGRATION_CENTER_AZURE_DEVOPS_PATH', plugin_dir_path( __FILE__ ));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-appq-integration-center-azure-devops-addon-activator.php
 */
function activate_appq_integration_center_azure_devops_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-appq-integration-center-azure-devops-addon-activator.php';
	Appq_Integration_Center_Azure_Devops_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-appq-integration-center-azure-devops-addon-deactivator.php
 */
function deactivate_appq_integration_center_azure_devops_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-appq-integration-center-azure-devops-addon-deactivator.php';
	Appq_Integration_Center_Azure_Devops_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_appq_integration_center_azure_devops_addon' );
register_deactivation_hook( __FILE__, 'deactivate_appq_integration_center_azure_devops_addon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-appq-integration-center-azure-devops-addon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_appq_integration_center_azure_devops_addon() {

	$plugin = new Appq_Integration_Center_Azure_Devops_Addon();
	$plugin->run();

}
run_appq_integration_center_azure_devops_addon();
