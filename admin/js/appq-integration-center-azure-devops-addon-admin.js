(function($) {
	'use strict';

	$(document).ready(function() {
		$('#azure_devops_settings .field_mapping .remove').click(function(){
			$(this).parent().remove()
		});
		$('#azure_devops_settings .add_field_mapping').click(function(){
			var button = $(this)
			button.attr('disabled','disabled')
			var proto = $('<div><input type="text" placeholder="key" name="key"><input type="text" placeholder="value" name="value"><button class="btn btn-primary">OK</button></div>')
			proto.find('button').click(function(e){
				e.preventDefault()
				var key = $(this).parent().find('[name="key"]').val()
				var value = $(this).parent().find('[name="value"]').val()
				
				var new_input = $(`
				<div class="form-group row">
					<label class="col-sm-2"></label>
					<textarea class="col-sm-9 form-control" placeholder="Title: {Bug.title}"></textarea>
					<button class="col-sm-1 remove btn btn-danger">-</button>
				</div>`)
				new_input.find('label').attr('for','field_mapping['+key+']').text(key)
				new_input.find('textarea').attr('name','field_mapping['+key+']').val(value)
				new_input.find('.remove').click(function(){
					$(this).parent().remove()
				})
				
				new_input.insertBefore(button)
				$(this).parent().remove()
				button.removeAttr('disabled')
			})
			proto.insertBefore($(this))
		});
		$('#azure-devops_tracker_settings').submit(function(e){
			e.preventDefault();
			var cp_id = $('#campaign_id').val()
			var data = $('#azure-devops_tracker_settings').serializeArray();
			data.push({
				'name' : 'action',
				'value': 'appq_azure_devops_edit_settings'
			});
			data.push({
				'name' : 'cp_id',
				'value': cp_id
			});
			data.push({
			  name: "nonce",
			  value: appq_ajax.nonce,
			});
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: appq_ajax.url,
				data: data,
				success: function(msg) {
					toastr.success('Tracker settings updated!');
					location.reload();
				}
			});
		});
		$('#azure-devops_mapping_field').submit(function(e){
			e.preventDefault();
			var field_list_wrap = $('.fields-list');
			var cp_id = $('#campaign_id').val()
			var data = $('#azure-devops_mapping_field').serializeArray();
			
			var submit_btn = $(this).find('[type="submit"]');
			var submit_btn_html = submit_btn.html();
			submit_btn.html('<i class="fa fa-spinner fa-spin"></i>');
			data.push({
				'name' : 'action',
				'value': 'appq_azure_edit_mapping_fields'
			});
			data.push({
				'name' : 'cp_id',
				'value': cp_id
			});
			data.push({
			  name: "nonce",
			  value: appq_ajax.nonce,
			});
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: appq_ajax.url,
				data: data,
				success: function(msg) {
					
					toastr.success('Field added!');
					submit_btn.html(submit_btn_html);
					var template = wp.template("field_mapping_row");
					var output = template(msg.data);
					if ($('[data-row="'+msg.data.key+'"]').length) {
						$('[data-row="'+msg.data.key+'"]').replaceWith(output)
					} else {
						field_list_wrap.prepend(output);
					}
					$('#add_mapping_field_modal').modal('hide');
				}
			});
		});
		$(document).on('click', '[data-target="#azure-devops_add_mapping_field_modal"]', function(){
			var modal_id = $(this).attr('data-target');
			var input_name = $(modal_id).find('input[name="name"]');
			var input_value = $(modal_id).find('textarea[name="value"]');

			input_name.val('');
			input_value.val('');

			var key = $(this).attr('data-key');
			if(!key) return;
			var content = $(this).attr('data-content');

			input_name.val(key);
			input_value.val(content);
		});
		$(document).on('click', '#azure-devops_fields_settings .delete-mapping-field', function(){
			var key = $(this).attr('data-key');
			var modal_id = $(this).attr('data-target');
			$(modal_id).find('input[name="field_key"]').val(key);
		});
		$('#azure-devops_delete_field').submit(function(e){
			e.preventDefault();
			var field_list_wrap = $('.fields-list');
			var cp_id = $('#campaign_id').val()
			var data = $('#azure-devops_delete_field').serializeArray();
			var submit_btn = $(this).find('[type="submit"]');
			var submit_btn_html = submit_btn.html();
			submit_btn.html('<i class="fa fa-spinner fa-spin"></i>');
			data.push({
				'name' : 'action',
				'value': 'appq_azure_delete_mapping_fields'
			});
			data.push({
				'name' : 'cp_id',
				'value': cp_id
			});
			data.push({
			  name: "nonce",
			  value: appq_ajax.nonce,
			});
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: appq_ajax.url,
				data: data,
				success: function(msg) {
					toastr.success('Field deleted!');
					submit_btn.html(submit_btn_html);
					field_list_wrap.find(`[data-row="${msg.data}"]`).remove();
					$('#delete_mapping_field_modal').modal('toggle');
				}
			});
		});
	});
})(jQuery);
