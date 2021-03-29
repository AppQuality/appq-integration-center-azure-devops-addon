
				<div class="row py-2" data-row="<?= $_key ?>">
						<div class="col-3"><?= $_key ?></div>
						<div class="col-7" style="white-space:pre"><?= array_key_exists('value', $item) ? $item['value'] : '' ?></div>
						<div class="col-2 text-right actions">
								<button 
								data-toggle="modal" 
								data-target="#add_mapping_field_modal" 
								type="button" 
								class="btn btn-info btn-icon-toggle mr-1 edit-mapping-field"
								data-key="<?= $_key; ?>"
								data-content="<?= (isset($item['value']) ? ($item['value']) : '') ?>"
								>
								<i class="fa fa-pencil"></i>
								</button>
								<button data-toggle="modal" data-target="#delete_mapping_field_modal" type="button" class="btn btn-danger btn-icon-toggle delete-mapping-field"
												data-key="<?= $_key ?>">
										<i class="fa fa-trash"></i>
								</button>
						</div>
				</div>
