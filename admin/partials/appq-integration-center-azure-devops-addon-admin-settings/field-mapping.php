<?php 
$api = new AzureDevOpsRestApi($campaign_id);
foreach ($api->basic_configuration as $key => $value) {
	if (!in_array($key,array_keys($field_mapping))) {
		$field_mapping[$key] = $value;
	}
}
?>
<div class="row">
	<div class="col-6"><?php printf('<h4 class="title py-3">%s</h4>', __('Field mapping', $this->plugin_name)); ?></div>
	<div class="col-6 text-right actions mt-2">
	<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#add_mapping_field_modal"><?php _e('Add new field mapping', $this->plugin_name); ?></button>
	</div>
</div>
<div class="row mb-2">
    <div class="col-3">
			<small>
				<strong><?= __('Name', $this->plugin_name); ?></strong>
				<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('Azure DevOps field name. Use slash for subfields (e.g. /fields/System.State)', $this->plugin_name) ?>"></i>
			</small>
    </div>
    <div class="col-7">
			<small>
				<strong><?= __('Content', $this->plugin_name); ?></strong>
				<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('The content you want to set the azure devops field to. {Bug.*} fields will be replaced with the bug data', $this->plugin_name) ?>"></i>
			</small>
    </div>
</div>
<div class="fields-list">
<?php 
foreach ($field_mapping as $key => $content) {
	$content = esc_attr($content);
	$this->partial('settings/field-mapping-row',array(
		'_key' => esc_attr($key),  
		'item' => array(
			'value' => $content
		)
	)); 
}
?>
</div>
<script type="text/html" id ="tmpl-field_mapping_row">
<?php $this->partial('settings/field-mapping-row',array(
	'_key' => '{{data.key}}',
	'item' => array(
		'value' => '{{data.content}}',
	)
)); ?>
</script>
<?php
$this->partial('settings/edit-mapping-field-modal', array('campaign_id' => $campaign_id));
$this->partial('settings/delete-mapping-field-modal', array());
?>
