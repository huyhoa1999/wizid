<?php if (!defined('ABSPATH')) exit; ?>
<?php echo '<script type="text/ng-template" id="field_body_pantone_option">'; ?>
    <div class="nbd-field-info" > 
        <div class="nbd-field-info-1">
            <div><label><b><?php _e('Color picker type', 'web-to-print-online-designer'); ?></b></label></div>
        </div>
        <div class="nbd-field-info-2">
            <div>
                <select name="options[fields][{{fieldIndex}}][general][pantone_color]" ng-model="field.general.pantone_color.value">
                    <option ng-repeat="op in field.general.pantone_color.options" value="{{op.key}}">{{op.text}}</option>
                </select>
            </div>
        </div>
    </div>
<?php echo '</script>';