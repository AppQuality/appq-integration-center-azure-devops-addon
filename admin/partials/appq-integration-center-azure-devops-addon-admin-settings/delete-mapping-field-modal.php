<!-- Modal -->
<div class="modal" id="delete_mapping_field_modal" tabindex="-1" role="dialog" aria-labelledby="reset_tracker_settings" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?= __('Delete this field?', 'appq-integration-center-azure-devops-addon') ?></h4>
            </div>
            <div class="modal-footer">
                <form id="azure-devops_delete_field">
                    <input type="hidden" name="field_key">
                    <button type="button" class="btn btn-link" data-dismiss="modal">
                        <?= __('Cancel', 'appq-integration-center-azure-devops-addon') ?>
                    </button>
                    <button type="submit" id="azure-devops_delete_mapping_field" class="btn btn-primary confirm">
                        <?= __('Delete field', 'appq-integration-center-azure-devops-addon') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>