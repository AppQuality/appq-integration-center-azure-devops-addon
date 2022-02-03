<?php 
$api = new AzureDevOpsRestApi($campaign_id);
foreach ($api->basic_configuration as $key => $value) {
	if (!in_array($key,array_keys($field_mapping))) {
		$field_mapping[$key] = $value;
	}
}
?>
<div class="row">
	<div class="col-sm-6 col-md-4">
		<h4 class="text-primary py-3"><?=  __('Field mapping', 'appq-integration-center') ?></h4>	
	</div>
	<div class="col-sm-6 col-md-8 text-right actions">
		<div class="btn-group">
			<button type="button" class="btn btn-primary-light" data-toggle="modal" data-target="#add_mapping_field_modal"><?= __('Add new field mapping', 'appq-integration-center-jira-addon'); ?></button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-banded">
			<thead>
				<tr>
					<th>
						<?= __("Name", 'appq-integration-center-jira-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('Azure DevOps field name. Use slash for subfields (e.g. /fields/System.State)', 'appq-integration-center-azure-devops-addon') ?>"></i>
					</th>
					<th class="text-center">
						<?= __("Content", 'appq-integration-center-jira-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('The content you want to set the azure devops field to. {Bug.*} fields will be replaced with the bug data', 'appq-integration-center-azure-devops-addon') ?>"></i>
					</th>
					<th></th>
				</tr>
			</thead>
			<tbody class="fields-list">
				<?php foreach ($field_mapping as $key => $item) {
					$content = esc_attr($item);
					$this->partial('settings/field-mapping-row',array(
						'_key' => esc_attr($key),  
						'item' => array(
							'value' => $content
						)
					));
				}
				?>
			</tbody>
		</table>
	</div>
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
