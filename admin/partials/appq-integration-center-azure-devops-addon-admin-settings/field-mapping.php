<?php 
$api = new AzureDevOpsRestApi($campaign_id);
foreach ($api->basic_configuration as $key => $value) {
	if (!in_array($key,array_keys($field_mapping))) {
		$field_mapping[$key] = $value;
	}
}
?>
<h3> Field Mapping</h3>
<div class="row">
	<div class="col-sm-9 field_mapping">
		<?php foreach ($field_mapping as $key => $value) : ?>
			<div class="form-group row">
				<label style="word-break: break-all;" for="field_mapping[<?= $key ?>]" class="col-sm-2 align-self-center text-center"><?= $key ?></label>
				<textarea name="field_mapping[<?= $key ?>]" class="col-sm-9 form-control" placeholder="Title: {Bug.title}"><?= $value ?></textarea>
				<button class="col-sm-1 remove btn btn-danger"><span class="fa fa-minus"></span></button>
			</div>
		<?php endforeach ?>
		<button type="button" class="add_field_mapping col-sm-12 btn btn-primary">+</button>
	</div>
	<div class="col-sm-3">
		<div class="row">
			<h4 class="col-sm-12"> Add fields </h4>
			<div class="col-sm-12">
				<?php foreach ($api->mappings as $map => $data) : ?>
					<p> <?= $map ?> - <?= $data['description'] ?> </p> 
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>
