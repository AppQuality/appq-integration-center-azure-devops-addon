<tr class="mapping-role" data-row="<?= $_key ?>">
	<td><?= $_key ?></td>
	<td>
		<?= array_key_exists('value', $item) ? $item['value'] : '' ?>
	</td>
	
	<td>
		<button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn btn-primary btn-icon-toggle edit-mapping-field" 
				data-key="<?= esc_attr($_key) ?>" 
                data-content="<?= isset($item['value']) ? esc_attr($item['value']) : '' ?>" >
			<i class="fa fa-pencil"></i>
		</button>
		<button data-toggle="modal" data-target="#delete_mapping_field_modal" type="button" class="btn btn-danger btn-icon-toggle delete-mapping-field" data-key="<?= $_key ?>">
			<i class="fa fa-trash"></i>
		</button>
	</td>
</tr>