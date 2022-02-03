<?php $api = new IntegrationCenterRestApi($campaign_id, null, null); ?>

<!-- Modal -->
<div class="modal" id="add_mapping_field_modal" tabindex="-1" role="dialog" aria-labelledby="add_mapping_field_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="add_mapping_field_modal_label">
          <?php _e('Add / Edit mapping field', 'appq-integration-center-azure-devops-addon'); ?>
        </h4>
      </div>
      <form id="azure-devops_mapping_field">
        <div class="modal-body px-4">
          <div class="row">
            <div class="col-sm-8">
              <div class="form-group">
                <label for="custom_mapping_name"><?= __('Name', 'appq-integration-center-azure-devops-addon'); ?></label>
                <input type="text" class="form-control" name="name" id="custom_mapping_name" placeholder="<?= __('/fields/System.Title', 'appq-integration-center-azure-devops-addon'); ?>">
              </div>
              <div class="form-group">
                <label for="custom_mapping_content"><?= __('Target field', 'allow media upload'); ?></label>
                <textarea class="form-control" name="value" id="custom_mapping_content" placeholder="<?= __('*Type*: {Bug.type} ...', 'appq-integration-center-azure-devops-addon'); ?>"></textarea>
              </div>
            </div>
            <div class="col-sm-4" style="max-height:350px;">
              <h6 class="text-center"><?= __('Click to copy', 'appq-integration-center-azure-devops-addon'); ?></h6>
              <ul class="list divider-full-bleed scroll height-6">
                <?php foreach ($api->mappings as $key => $value) : ?>
                  <li class="tile" title="<?= esc_attr($value['description']) ?>">
                    <a class="tile-content ink-reaction copy-to-clipboard" data-clipboard-text="<?= $key ?>">
                      <div class="tile-text" style="font-size: 13px;">
                        <?= $key ?>
                      </div>
                    </a>
                    <a class="btn btn-flat ink-reaction copy-to-clipboard btn-sm" data-clipboard-text="<?= $key ?>">
                      <i class="fa fa-copy"></i>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div><!-- END .modal-body -->
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-dismiss="modal">
            <?= __('Cancel', 'appq-integration-center-azure-devops-addon') ?>
          </button>
          <button type="submit" id="add_new_mapping_field" class="btn btn-primary">
            <?= __('Save field', 'appq-integration-center-azure-devops-addon'); ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>