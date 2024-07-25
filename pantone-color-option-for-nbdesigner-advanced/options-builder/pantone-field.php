<?php if (!defined('ABSPATH')) exit; ?>
<div class="nbd-option-field nbd-field-dropdown-wrap">
	<div class="nbd-field-header">
		<label for='nbd-field-<?php echo $field['id']; ?>'>
			<?php echo $field['general']['title']; ?>
			<?php if ($field['general']['required'] == 'y') : ?>
				<span class="nbd-required">*</span>
			<?php endif; ?>
		</label>
		<?php if ($field['general']['description'] != '') : ?>
			<span data-position="<?php echo $tooltip_position; ?>" data-tip="<?php echo html_entity_decode($field['general']['description']); ?>" class="nbd-help-tip"></span>
		<?php endif; ?>
	</div>
	<div class="nbd-field-content">
		<div class="__nbd-dropdown-wrap">
			<select ng-change="check_valid();updateMapOptions('<?php echo $field['id']; ?>')" name="nbd-field[<?php echo $field['id']; ?>]{{nbd_fields['<?php echo $field['id']; ?>'].form_name}}" class="nbo-dropdown" ng-model="nbd_fields['<?php echo $field['id']; ?>']['color_mode']">
				<option value="sc">
					<?php _e('Solid Colour', 'web-to-print-online-designer'); ?>
				</option>
				<option value="mc">
					<?php _e('Stripes', 'web-to-print-online-designer'); ?>
				</option>
			</select>
			<div class="single_opt" ng-show="nbd_fields['<?php echo $field['id']; ?>']['color_mode'] == 'sc'">
				<div class="color-picker-trigger band-colour-picker-button selected" title="" ng-click="openPicker('single')">
					<input id="color_solid1" type="hidden" name="color_solid1" value="">
				</div>
			</div>
			<div class="multi_opt" ng-show="nbd_fields['<?php echo $field['id']; ?>']['color_mode'] == 'mc'">
				<select id="multi_opt" class="nbo-dropdown" name="pantone_multi_field" ng-change="check_valid()" ng-model="nbd_fields['<?php echo $field['id']; ?>']['color_count']">
					<option value="2" selected class="selected"><?php _e('2 Colours', 'web-to-print-online-designer'); ?></option>
					<option value="3"><?php _e('3 Colours', 'web-to-print-online-designer'); ?></option>
					<option value="4"><?php _e('4 Colours', 'web-to-print-online-designer'); ?></option>
					<option value="5"><?php _e('5 Colours', 'web-to-print-online-designer'); ?></option>
				</select>

				<div class="color-wrap">
					<div class="clearfix">
						<div class="stripe-1 multi-color-trigger band-colour-picker-button" ng-click="openPicker('stripe1')">
							<input id="color_strip1" type="hidden" name="color_stripes[0]" value="">
						</div>
						<div class="stripe-2 multi-color-trigger band-colour-picker-button" ng-click="openPicker('stripe2')">
							<input id="color_strip2" type="hidden" name="color_stripes[1]" value="">
						</div>
						<div class="stripe-3 multi-color-trigger band-colour-picker-button default" ng-click="openPicker('stripe3')">
							<input id="color_strip3" type="hidden" name="color_stripes[2]" value="">
						</div>
						<div class="stripe-4 multi-color-trigger band-colour-picker-button default" ng-click="openPicker('stripe4')">
							<input id="color_strip4" type="hidden" name="color_stripes[3]" value="">
						</div>
						<div class="stripe-5 multi-color-trigger band-colour-picker-button default" ng-click="openPicker('stripe5')">
							<input id="color_strip5" type="hidden" name="color_stripes[4]" value="">
						</div>
					</div>
				</div>
			</div>
			<?php include NB_CUSTOM_PC_DIR . '/options-builder/color-picker.php' ?>
			
			<div class="solid-stripe-notice">
				<p>
					Click here to select your colour or Colour Options
				</p>
			</div>
			
		</div>
	</div>
</div>


<!-- loadTemplateAfterRenderCanvas -->