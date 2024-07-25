<?php

/**
 * Plugin Name:       Pantone Color Option for NBDesigner Advanced
 * Plugin URI:        https://cmsmart.net
 * Description:       Add Pantone color picker in printing option field Color
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.1
 * Author:            Hoang
 * Author URI:        https://cmsmart.net
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pantone-color-option-for-nbdesigner-advanced
 * Domain Path:       /languages
 */

define('NB_CUSTOM_PC_URL', plugin_dir_url(__FILE__));
define('NB_CUSTOM_PC_DIR', plugin_dir_path(__FILE__));

//add_action('nbd_extra_css','custom_option_cmyk_style'); jQuery.ui.version,jQuery.fn.jquery
function custom_option_cmyk_style(){
    ?>
        <link rel="stylesheet" href="<?= plugin_dir_url(__FILE__) . 'assets/css/jquery.colorpicker.css'; ?>">
    <?php
}

//add_action('nbd_extra_js','custom_option_cmyk_js');
function custom_option_cmyk_js() {
    ?>
        <script type="text/javascript" src="<?= plugin_dir_url(__FILE__) . 'assets/js/jquery.colorpicker.js'; ?>"></script>
    <?php
}

//nbd_save_customer_design

add_action('wp_enqueue_scripts', 'nbod_pantone_nbdesigner_js', 31, 1);
function nbod_pantone_nbdesigner_js()
{
    // wp_localize_script('nbdesigner', 'nbod_pantone_option', true);
    wp_add_inline_script('nbdesigner', 'var nbod_pantone_option = true');
    wp_register_style('pantone_color', NB_CUSTOM_PC_URL . 'pantone.css', array(), '1.0.0');
    wp_enqueue_style('pantone_color');
    //cmyk
    wp_register_style('pantone_color_cmyk', NB_CUSTOM_PC_URL . 'assets/css/jquery.colorpicker.css', array(), '1.0.0');
    wp_enqueue_style('pantone_color_cmyk');
    wp_register_script('pantone_color_cmyk', NB_CUSTOM_PC_URL . 'assets/js/jquery.colorpicker.js', array('jquery', 'jquery-ui'), '1.0.0', true);
    wp_enqueue_script('pantone_color_cmyk');

    wp_deregister_script('jquery-ui');
    wp_register_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array('jquery'), '1.12.1', true);
    wp_enqueue_script('jquery-ui');
    //end cmyk
    wp_register_script('pantone_color', NB_CUSTOM_PC_URL . 'pantone.js', array('jquery'), '1.0.0');
    wp_enqueue_script('pantone_color');
}

add_action('admin_enqueue_scripts', 'nbod_admin_pantone_nbdesigner_js', 31, 1);
function nbod_admin_pantone_nbdesigner_js()
{
    wp_add_inline_script('admin_nbdesigner', 'var nbod_pantone_option = true');
    wp_add_inline_script('nbd_options', 'var nbod_pantone_option = true');
    // wp_localize_script('admin_nbdesigner', 'nbod_pantone_option', true);
    // wp_localize_script('nbd_options', 'nbod_pantone_option', true);
}

add_action('nbd_js_config', 'nbod_pantone_frontend_js');
function nbod_pantone_frontend_js($product_id)
{
    echo 'var nbod_pantone_option = true;';
    echo 'var nbod_pantone_color_cmyk = true;';
    $check_cmyk = get_post_meta($product_id,'_nbo_color_cmyk_pantone',true) ? get_post_meta($product_id,'_nbo_color_cmyk_pantone',true) : 0;
    $check_multi_cl = get_post_meta($product_id,'_nbo_color_multi_pantone',true) ? get_post_meta($product_id,'_nbo_color_multi_pantone',true) : 0;
    ?>
        NBDESIGNCONFIG['check_cmyk'] = "<?php echo $check_cmyk; ?>";
        NBDESIGNCONFIG['check_multi_cl'] = "<?php echo $check_multi_cl; ?>";
    <?php
}

add_filter('nbdesigner_printing_options_settings', 'enable_pantone_options');
function enable_pantone_options($array)
{
    $pantone_arr = array(
        'title'         => esc_html__('Enable Pantone Option', 'web-to-print-online-designer'),
        'description'   => esc_html__('Add Pantone Color option.', 'web-to-print-online-designer'),
        'id'            => 'nbdesigner_pantone_option',
        'default'       => 'no',
        'type'          => 'radio',
        'options'       => array(
            'yes'   => esc_html__('Yes', 'web-to-print-online-designer'),
            'no'    => esc_html__('No', 'web-to-print-online-designer')
        )
    );
    array_push($array['general'], $pantone_arr);
    return $array;
}
add_filter('nbd_update_default_field', 'update_default_field');
function update_default_field($array)
{
    $array['general']['pantone_color'] = null;
    return $array;
}

add_filter('nbod_option_pantone', 'nbod_option_pantone', 10, 4);
function nbod_option_pantone($options_fields, $tab, $key, $f)
{
    if ($tab == 'general' && $key == 'pantone_color') {
        //print_r($f['pantone_color']);
        
        if (!empty($f['pantone_color'])) {
            $options_fields['pantone_color'] = $f['pantone_color'];
        }
    }
    return $options_fields;
}

add_action('nbod_extra_field_admin_options', 'extra_field_admin');
function extra_field_admin()
{
    include 'field-body/pantone-option.php';
}
add_action('nbod_before_attributes_printing_option', 'add_field_pantone');
function add_field_pantone()
{
    if (nbdesigner_get_option('nbdesigner_pantone_option') == 'yes') :
?>
        <ng-include src="'field_body_pantone_option'"></ng-include>
    <?php
    endif;
}
add_filter('change_color_field_template', 'change_color_field_template', 10, 3);
function change_color_field_template($template, $current_field, $class)
{
    if (isset($current_field['nbd_type']) && $current_field['nbd_type'] == 'color') {
        if ($current_field['general']['pantone_color'] == 'p') {
            $currentDir = realpath(dirname(__FILE__));
            $template = $currentDir . '/options-builder/pantone-field.php';
        }
    }
    return $template;
}
add_action('nbd_push_colors_to_editor', 'colors_to_editor', 10, 1);
function colors_to_editor($product_id)
{
    $nbd_settings = unserialize(get_post_meta($product_id, '_designer_setting', true));
    ?>
    /*nbdesigner advanced*/
    if(typeof nbod_pantone_option != 'undefined'){
    var current_bg = "<?php echo $nbd_settings[0]['bg_type']; ?>",
    current_bg_color = "<?php echo $nbd_settings[0]['bg_color_value']; ?>";
    if (angular.isDefined(field.color_str)){
    if (field.color_str.length > 0){
    nbOption.odOption.color = {
    bg_type: 'c',
    bg_color: current_bg_color != '' ? current_bg_color :'#ffffff',
    bg_image: angular.isDefined(option_color) ? option_color.bg_image_url : '',
    color_count: field.color_count,
    color_mode: field.color_mode,
    color_str: field.color_str,
    };
    nbOption.odOption.color.bottle = angular.isDefined(option_color) ? arig[1] : '';
    nbOption.odOption.color.lid = angular.isDefined(option_color) ? arig[0] : '';
    } else {
    nbOption.odOption.color = {
    bg_type: 'c',
    bg_color: current_bg_color != '' ? current_bg_color :'#ffffff',
    bg_image: angular.isDefined(option_color) ? option_color.bg_image_url : '',
    color_count: 1,
    color_mode: 'sc',
    color_str: [],
    };
    nbOption.odOption.color.bottle = angular.isDefined(option_color) ? arig[1] : '';
    nbOption.odOption.color.lid = angular.isDefined(option_color) ? arig[0] : '';
    }
    } else {
    nbOption.odOption.color = {
    bg_type: origin_field.general.attributes.bg_type,
    bg_color: angular.isDefined(option_color) ? option_color.bg_color : '',
    bg_image: angular.isDefined(option_color) ? option_color.bg_image_url : '',
    };
    nbOption.odOption.color.bottle = angular.isDefined(option_color) ? arig[1] : '';
    nbOption.odOption.color.lid = angular.isDefined(option_color) ? arig[0] : '';
    }
    }
<?php
}
add_filter('stage_background', 'stage_background', 10, 1);
function stage_background($html)
{
    ob_start();
?>
    <div class="stage-background cccccc" ng-style="{'background-color': stage.config.bgType == 'image' ? '#ffffff' : (( stage.config.bgType == 'color' && ( ( stage.config.show_overlay == '1' && stage.config.img_overlay != '' ) || !areaDesignShapes[$index] ) )  ? stage.config.bgColor : 'transparent')}" ng-show="stage.config.gardient_bg == false || stage.config.gardient_bg == null">
        <img ng-if="stage.config.bgType == 'image'" ng-src='{{stage.config.bgImage}}' />
    </div>
    <div class="stage-background" ng-style="{'background': stage.config.bgColor }" ng-show="stage.config.gardient_bg == true">
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}
add_filter('stage_background_visual', 'stage_background_visual', 10, 1);
function stage_background_visual($html)
{
    //,'background-color': stage.config.bgType == 'image' ? '#fff' : (stage.config.bgType == 'color' ? stage.config.bgColor : 'transparent')
    $_nbo_preview_land  = get_post_meta(get_the_ID(), '_nbo_preview_land', true);
?>
    <div class="stage-background nbd-shadow xxxxxx" ng-style="{'left' :  calcStyle(stage.config.left * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'top' :  calcStyle(stage.config.top * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'height' : calcStyle(stage.config.height * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'width' : calcStyle(stage.config.width * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'background-color': stage.config.bgType == 'image' ? '#fff' : (stage.config.bgType == 'color' ? stage.config.bgColor : 'transparent')}">
        <?php if ($_nbo_preview_land == 0) { ?>
            <img ng-show="stage.config.gardient_bg == false" class="side_thumbnail huy1" ng-style="{'height' : calcStyle(stage.config.height * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'width' : calcStyle(stage.config.width * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'background-color': stage.config.bgType == 'image' ? '#fff' : (stage.config.bgType == 'color' ? stage.config.bgColor : 'transparent')}" title="{{stage.config.name}}" ng-src="{{stage.stage_thumbnail}}" style="z-index: 9;display:block;position: absolute;left: 100%;">
            <img ng-show="stage.config.gardient_bg == true" class="side_thumbnail huy2" ng-style="{'background': stage.config.bgColor,'height' : calcStyle(stage.config.height * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'width' : calcStyle(stage.config.width * stage.states.scaleRange[stage.states.currentScaleIndex].ratio) }" title="{{stage.config.name}}" ng-src="{{stage.stage_thumbnail}}" style="z-index: 9;display:block;position: absolute;left: 100%;">
            <img ng-show="!stage.config.lid && !stage.config.bottle" class="side_thumbnail huy3" ng-style="{'background': stage.config.bgColor,'height' : calcStyle(stage.config.height * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'width' : calcStyle(stage.config.width * stage.states.scaleRange[stage.states.currentScaleIndex].ratio) }" title="{{stage.config.name}}" ng-src="{{stage.stage_thumbnail}}" style="z-index: 9;display:block;position: absolute;left: 100%;">
        <?php } ?>
    </div>

    <div class="gr-img">
        <img ng-show="!stage.config.lid && !stage.config.bottle" class="gr-img_lid" ng-src='{{stage.config.bgImage}}' />
        <img ng-show="stage.config.lid" class="gr-img_lid" ng-src='{{stage.config.lid}}' style="position: absolute;left: 0;" />
        <img ng-show="stage.config.bottle" class="gr-img_bottle" style="position: absolute;left: 0;" ng-src='{{stage.config.bottle}}' />
    </div>
    <div class="stage-background" ng-style="{'height' : calcStyle(stage.config.height * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'width' : calcStyle(stage.config.width * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),'background': stage.config.bgColor,'top' :  calcStyle(stage.config.top * stage.states.scaleRange[stage.states.currentScaleIndex].ratio),
                        'left' :  calcStyle(stage.config.left * stage.states.scaleRange[stage.states.currentScaleIndex].ratio) }" ng-show="stage.config.gardient_bg == true"></div>
<?php
}

add_filter('merge_img_bottle', 'mergeBottle', 10, 4);
function mergeBottle($path_img_src, $val, $key, $path_src)
{
    $path_lid = $val["lid"];
    $path_bottle = $val["bottle"];
    if($path_lid != '' && $path_bottle != '') {
        $dest = imagecreatefrompng($path_lid);
        list($width, $height)  = getimagesize($path_lid);
        $src = imagecreatefrompng($path_bottle);
        $src = NBD_Image::nbdesigner_resize_imagepng($path_bottle, $width, $height);
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        imagecopymerge($dest, $src, 0, 0, 0, 0, $width, $height / 4.5, 100);
        $color = imagecolorallocatealpha($dest, 255, 255, 255, 127);
        imagefill($dest, 0, 0, $color);
        imagepng($dest, $path_src . '/imgMerge_' . $key . '.png');
        imagedestroy($dest);
        imagedestroy($src);
        $path_img_src  = file_exists(Nbdesigner_IO::convert_url_to_path($path_src . '/imgMerge_' . $key . '.png')) ? Nbdesigner_IO::convert_url_to_path($path_src . '/imgMerge_' . $key . '.png') : Nbdesigner_IO::convert_url_to_path($val["img_src"]);
    }
    return $path_img_src;
}

add_filter('change_path_bg', 'changePathbg', 10, 3);
function changePathbg($path_bg, $nbd_item_key, $key)
{
    $path_bg = Nbdesigner_IO::convert_url_to_path(NBDESIGNER_CUSTOMER_DIR . '/' . $nbd_item_key . '/imgMerge_' . $key . '.png');
    return $path_bg;
}


//---------------------------------------------------------------------------------------------------

add_filter('add_id_custom_file','add_id_custom_file_fs');
function add_id_custom_file_fs() {
    ?>
        <div class="form-upload nbd-dnd-file" nbd-dnd-file="uploadFile(files)">
            <i class="nbd-icon-vista nbd-icon-vista-cloud-upload"></i>
            <span><?php _e('Click or drop images here', 'web-to-print-online-designer'); ?></span>
            <input id="ct-fileh" type="file" accept="image/*" style="display: none;" />
        </div>
    <?php
    return '';
}

add_filter('nbod_add_function_open_file','nbod_add_function_open_file_fs');
function nbod_add_function_open_file_fs() {
    ?>  
        <li
            ng-class="stages[currentStage].states.elementUpload ? 'active' : ''" 
            ng-click="customFile();setLayerAttribute('elementUpload', true)"
            class="item"
            title="<?php _e('Replace image','web-to-print-online-designer'); ?>">
            <div class="v-asset"><i class="nbd-icon-vista nbd-icon-vista-replace-image"></i></div>
            <span class="v-asset-title"><?php _e('replace','web-to-print-online-designer'); ?></span>
        </li>
    <?php
    return;
}

//---------------------------------------------------------------------------------------------------

add_action('nbd_add_enable_check_spine','add_nbd_add_enable_check_color_cmyk_pantone');
function add_nbd_add_enable_check_color_cmyk_pantone(){
    $enable_cmyk = get_post_meta($_GET['post'], '_nbo_color_cmyk_pantone', true);
    ?> 
        <p class="nbo-form-field">
            <label for="_nbo_color_cmyk_pantone"><?php _e('Enable CMYK option', 'web-to-print-online-designer'); ?></label>
            <span class="nbo-option-val">
                <input type="hidden" value="0" name="_nbo_color_cmyk_pantone"/>
                <input type="checkbox" value="1" name="_nbo_color_cmyk_pantone" id="_nbo_color_cmyk_pantone" <?php checked($enable_cmyk); ?> class="short" />
            </span>
        </p>
    <?php
}

add_action('nbo_save_options','add_nbo_save_cmyk_options',10,2);
function add_nbo_save_cmyk_options($post_id,$POST){
    if(isset( $POST['_nbo_color_cmyk_pantone'] )){
        update_post_meta($post_id, '_nbo_color_cmyk_pantone', $POST['_nbo_color_cmyk_pantone']);
    }

    if(isset( $POST['_nbo_color_multi_pantone'] )){
        update_post_meta($post_id, '_nbo_color_multi_pantone', $POST['_nbo_color_multi_pantone']);
    }
}

add_filter('nbod_coler_text_cmyk_ct','nbod_coler_text_cmyk_ct_ft',10,2);
function nbod_coler_text_cmyk_ct_ft($html,$product_id) {
    $enable_cmyk = get_post_meta($product_id, '_nbo_color_cmyk_pantone', true);
    if($enable_cmyk == 1) { ?>
        <style type="text/css">
            .v-btn-cmyk .ui-colorpicker {
                top: 50px;
            }
            .toolbox-color-palette {
                width: 100%;
            }
        </style>
        <div class="toolbox-color-palette">
            <div class="v-dropdown">
                <span style="margin-left: 15px;">CMYK color:</span>
                <input class="v-btn-cmyk cp ui-button ui-corner-all ui-widget" cl="{{stages[currentStage].states.text.fill}}" ng-style="{'background': stages[currentStage].states.text.fill}" style="color: transparent;width: 80%;margin-left: 15px;cursor: pointer; /">
            </div>
        </div>
    <?php } else { ?>
        <div class="toolbox-color-palette">
            <div class="v-dropdown">
                <button class="v-btn btn-color v-btn-dropdown" ng-click="globalPicker.color = stages[currentStage].states.text.fill" title="<?php _e('Text color','web-to-print-online-designer'); ?>">
                    <span class="color-selected" ng-style="{'background-color': stages[currentStage].states.text.fill}"></span>
                    <i class="nbd-icon-vista nbd-icon-vista-expand-more v-dropdown-icon"></i>
                </button>
                <div class="v-dropdown-menu">
                    <?php include __DIR__ . '/color-palette.php'?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php
    return '';
}

//---------------------------------19/6--------------

add_filter('nbod_add_function_cmyk_path','nbod_add_function_cmyk_path_ft',10,2);
function nbod_add_function_cmyk_path_ft($html,$product_id) {
    $enable_cmyk = get_post_meta($product_id, '_nbo_color_cmyk_pantone', true);
    if($enable_cmyk == 1) { ?>
        <style type="text/css">
            .v-btn-cmyk .ui-colorpicker {
                top: 50px;
            }
            .toolbox-color-palette {
                width: 100%;
            }
        </style>
        <div class="cmyk-path" style="width: 50%;">
            <span style="">CMYK color:</span>
            <input class="v-btn-cmyk cp-path ui-button ui-corner-all ui-widget" cl="{{stages[currentStage].states.svg.groupPath[0].color}}" ng-style="{'background': stages[currentStage].states.svg.groupPath[0].color}" style="color: transparent;width: 80%;cursor: pointer; /">
        </div>
    <?php } else { ?>
        <li style="display: inline-block;margin-right: 5px;" class="item v-asset v-asset-margin item-color-palette nbdColorPalette" ng-click="stages[currentStage].states.svg.currentPath = $index" ng-repeat="path in stages[currentStage].states.svg.groupPath" end-repeat-color-picker>
            <span ng-click="currentColor = path.color" style="width: 40px; height: 40px;display: inline-block;border: 1px solid #04b591; border-radius: 4px;cursor: pointer;" ng-style="{'background': path.color}" class="color-fill nbd-color-picker-preview" title="<?php _e('Color','web-to-print-online-designer'); ?>"></span>
        </li>
    <?php } ?>
    <?php
    return '';
}

// Enable Multi Colour Print

add_filter('nbod_coler_muti_color_ct','nbod_coler_muti_color_ct_tc',10,2);
function nbod_coler_muti_color_ct_tc($html,$enable) {
    $enable_multi_color = get_post_meta($_GET['post'], '_nbo_color_multi_pantone', true);
    ?>
        <div class="" style="display: flex;align-items: center;gap: 33%;">
            <p class="nbo-form-field">
                <label for="_nbo_enable"><?php _e('Enable Printing option', 'web-to-print-online-designer'); ?></label>
                <span class="nbo-option-val">
                    <input type="hidden" value="0" name="_nbo_enable"/>
                    <input type="checkbox" value="1" name="_nbo_enable" id="_nbo_enable" <?php checked($enable); ?> class="short" />
                </span>
            </p>

            <p class="nbo-form-field">
                <label for="_nbo_enable"><?php _e('Enable Multi Colour Print', 'web-to-print-online-designer'); ?></label>
                <span class="nbo-option-val">
                    <input type="hidden" value="0" name="_nbo_color_multi_pantone"/>
                    <input type="checkbox" value="1" name="_nbo_color_multi_pantone" id="_nbo_color_multi_pantone" <?php checked($enable_multi_color); ?> class="short" />
                </span>
            </p>
        </div>
    <?php
    return '';
}


add_filter('paf_add_cart_item','paf_add_cart_item_ft');
function paf_add_cart_item_ft($cart_item_data) {
    if(isset($_POST['color_stripes'])) {
        $cart_item_data['key_stripes'] = $_POST['color_stripes'];
    }

    if(isset($_POST['color_solid1'])) {
        $cart_item_data['key_solid'] = $_POST['color_solid1'];
    }

    if(isset($_POST['colorsLayer'])) {
        $cart_item_data['multi_color'] = $_POST['colorsLayer'];
    }
    if(isset($_POST['colorLayer'])) {
        $cart_item_data['color_layer'] = $_POST['colorLayer'];
    }

    return $cart_item_data;
}

add_action( 'woocommerce_checkout_create_order_line_item', 'woocommerce_checkout_create_order_line_item_fc', 50, 3 );
function woocommerce_checkout_create_order_line_item_fc($item, $cart_item_key, $values) {
    if ( isset( $values['key_stripes'] ) ) {
        $filteredArray = array_filter($values['key_stripes'], function($value) {
            return !empty(trim($value));
        });
        $item->add_meta_data( __('Colour/stripes', 'web-to-print-online-designer'), implode(", ",$filteredArray) );
    }

    if ( isset( $values['key_solid'] ) ) {
        $item->add_meta_data( __('Colour/stripes', 'web-to-print-online-designer'), $values['key_solid']);
    }

    if ( isset( $values['multi_color'] ) ) {
        $item->add_meta_data( __('Layer colors', 'web-to-print-online-designer'), $values['multi_color']);
    }

    if ( isset( $values['color_layer'] ) ) {
        $item->add_meta_data( __('Layer color', 'web-to-print-online-designer'), $values['color_layer']);
    }

}