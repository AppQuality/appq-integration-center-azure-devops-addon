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
	<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#azure-devops_add_mapping_field_modal"><?php _e('Add new field mapping', $this->plugin_name); ?></button>
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
<?php foreach ($field_mapping as $key => $value){ ?>
    <div class="row mb-2" data-row="<?= $key ?>">
        <?php
        printf(
            '<div class="col-3">%s</div><div class="col-7">%s</div><div class="col-2 text-right actions">%s</div>',
            $key,
			nl2br($value),
			'<button data-toggle="modal" data-target="#azure-devops_add_mapping_field_modal" type="button" class="btn btn-secondary mr-1 azure-devops-edit-mapping-field" data-key="'.esc_attr($key).'" data-content="'.(isset($value) ? esc_attr($value) : '').'"><i class="fa fa-pencil"></i></button>
			<button data-toggle="modal" data-target="#azure-devops_delete_mapping_field_modal" type="button" class="btn btn-secondary azure-devops-delete-mapping-field" data-key="'.esc_attr($key).'"><i class="fa fa-trash"></i></button>'
        );
        ?>
    </div>
<?php } ?>
</div>

<?php
$this->partial('settings/edit-mapping-field-modal', array('campaign_id' => $campaign_id));
$this->partial('settings/delete-mapping-field-modal', array());
?>
