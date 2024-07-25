<?php ?>
<div class="nbd-popup popup-color">
	<div class="overlay-popup"></div>
	<div class="main-popup">
		<div class="body" style="margin: 10px">
			<div class="main-body" id="nbo-options-wrap" style="display: inline-block;">
				<label style="font-size: 18px;font-weight: 600;color: #0051ba;">
					<?php _e('Popular Colours', 'web-to-print-online-designer'); ?>
					<i class="fa-solid fa-x" style="position: absolute;right: 10px;" ng-click="closePopup()">
						<svg enable-background="new 0 0 512 512" height="20" id="Layer_1" version="1.1" viewBox="0 0 512 512" width="20" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<path d="M255.997,460.351c112.685,0,204.355-91.668,204.355-204.348S368.682,51.648,255.997,51.648  c-112.68,0-204.348,91.676-204.348,204.355S143.317,460.351,255.997,460.351z M255.997,83.888  c94.906,0,172.123,77.209,172.123,172.115c0,94.898-77.217,172.117-172.123,172.117c-94.9,0-172.108-77.219-172.108-172.117  C83.888,161.097,161.096,83.888,255.997,83.888z" />
							<path d="M172.077,341.508c3.586,3.523,8.25,5.27,12.903,5.27c4.776,0,9.54-1.84,13.151-5.512l57.865-58.973l57.878,58.973  c3.609,3.672,8.375,5.512,13.146,5.512c4.658,0,9.316-1.746,12.902-5.27c7.264-7.125,7.369-18.793,0.242-26.051l-58.357-59.453  l58.357-59.461c7.127-7.258,7.021-18.92-0.242-26.047c-7.252-7.123-18.914-7.018-26.049,0.24l-57.878,58.971l-57.865-58.971  c-7.135-7.264-18.797-7.363-26.055-0.24c-7.258,7.127-7.369,18.789-0.236,26.047l58.351,59.461l-58.351,59.453  C164.708,322.715,164.819,334.383,172.077,341.508z" />
						</svg>
					</i>
				</label>
				<div id="color-picker-popular" class="clearfix">
					<label ng-repeat="popular in colorPopular" title="{{popular.name}}" ng-style="{'backgroundColor': '{{popular.value}}'}">
						<span>
							<input style="display: block;" type="radio" name="band-colour-popular" value="{{popular.value}}" ng-click="changeColor(nbd_fields['<?php echo $field['id']; ?>']['color_mode'],popular.value,nbd_fields['<?php echo $field['id']; ?>'])">
						</span>
					</label>
				</div>
				<div style="clear:both;"></div>
				<div id="color-picker-select-pms" class="clearfix">
					<label style="color:#000000;"> <?php _e('Or select PMS colour: ', 'web-to-print-online-designer'); ?></label>
					<select name="band_pms" ng-model="selectedItem" ng-change="changeColor(nbd_fields['<?php echo $field['id']; ?>']['color_mode'],selectedItem,nbd_fields['<?php echo $field['id']; ?>'])">
						<option ng-repeat="color in colorList" ng-value="color.value">
							{{color.name}}
						</option>
					</select>
				</div>
				<div id="color-picker-pms" class="clearfix">
					<ul class="main-color-palette" style="margin-bottom: 15px;">
						<li ng-repeat="color in colorList track by $index" ng-class="{'first-left': $first, 'last-right': $last, 'first-right': $index == 4,'last-left': $index == (color.length - 5)}" ng-click="changeColor(nbd_fields['<?php echo $field['id']; ?>']['color_mode'],color.value,'<?php echo $field['id']; ?>');" class="color-palette-item" data-color="#{{color.value}}" title="{{color.name}}" ng-style="{'backgroundColor': '#{{color.value}}'}" ng-show="$index > 0"><b>{{color.name}}</b></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>