<?php
/**
 * Include the slider captcha on any place
 */
function slider_captcha($slider_name = 'general', $container = 'p', $settings = null) {
	global $sliderCaptcha;

	//If using the deprecated function
	if(is_array($container))
		return _deprectated_slider_captcha($slider_name,$container); 

	if($settings == null)
		$settings = $sliderCaptcha->get_slider($slider_name);
	else
		$settings = array_replace_recursive($sliderCaptcha->get_slider($slider_name), $settings);

	$container_class = (isset($settings['containerClass']) && $settings['containerClass']!=NULL 
		? 'class="' . $settings['containerClass'] . '"' : '');

	?>
		<<?php echo $container?> <?php echo $container_class?> id="slidercaptcha-<?php echo $slider_name?>"> </<?php echo $container?>>
		<script type="text/javascript">
		jQuery(function($) {
			$( document ).ready(function() {
				//Load the slider captcha
				$("#slidercaptcha-<?php echo $slider_name?>").sliderCaptcha(<?php echo json_encode($settings)?>);
			});
		});
		</script>
	<?php

}

/**
 * This function is deprectated and its here to maintain the compatibilty for 0.5 -> 1.0 upgrade.
 **/
function _deprectated_slider_captcha($container = 'p', $settings = null) {
	global $sliderCaptcha;

	if($settings == null)
		$settings = array_replace_recursive($sliderCaptcha->js_settings, $sliderCaptcha->settings);
	else
		$settings = array_replace_recursive($sliderCaptcha->js_settings, $sliderCaptcha->settings, $settings);

	$container_class = (isset($settings['containerClass']) && $settings['containerClass']!=NULL 
		? 'class="' . $settings['containerClass'] . '"' : '');

	$number=rand(0,23541);
	?>
		<<?php echo $container?> <?php echo $container_class?> id="slidercaptcha<?php echo $number?>"> </<?php echo $container?>>
		<script type="text/javascript">
		jQuery(function($) {
			$( document ).ready(function() {
				//Load the slider captcha
				$("#slidercaptcha<?php echo $number?>").sliderCaptcha(<?php echo json_encode($settings)?>);
			});
		});
		</script>
	<?php

}

/**
 * Get the database settings, without a merge with the general slider
 */
function slider_get_raw_slider_options($slider_name) {
	global $sliderCaptcha;
	$sliders = $sliderCaptcha->get_sliders();
	return (isset($sliders[$slider_name]) ? $sliders[$slider_name] : false);
}

function slider_get_slider_options($slider_name) {
	global $sliderCaptcha;
	return $sliderCaptcha->get_slider($slider_name);
}

/**
 * Update a slider with a $slider_name.
 * If doesn't exist, create a new slide
 */
function slider_update_slider($slider_name, array $options) {
	global $sliderCaptcha;
	return $sliderCaptcha->update_slider($slider_name, $options);
}


function _slider_draw_fontface_options($field='',$options) {
	include SLIDER_CAPTCHA_PATH . 'font_face_options.php';
}

function _slider_array_filter_recursive($array) {
   foreach ($array as $key => &$value) {
      if (empty($value)) {
         unset($array[$key]);
      }
      else {
         if (is_array($value)) {
            $value = _slider_array_filter_recursive($value);
            if (empty($value)) {
               unset($array[$key]);
            }
         }
      }
   }

   return $array;
}


/**
 * array_replace_recursive has been added to php on 5.3.0
 * this will make sure that the function always exists.
 */

if(!function_exists('array_replace_recursive')) {
	function array_replace_recursive($base, $replacements) { 
        foreach (array_slice(func_get_args(), 1) as $replacements) { 
            $bref_stack = array(&$base); 
            $head_stack = array($replacements); 

            do { 
                end($bref_stack); 

                $bref = &$bref_stack[key($bref_stack)]; 
                $head = array_pop($head_stack); 

                unset($bref_stack[key($bref_stack)]); 

                foreach (array_keys($head) as $key) { 
                    if (isset($key, $bref) && is_array($bref[$key]) && is_array($head[$key])) { 
                        $bref_stack[] = &$bref[$key]; 
                        $head_stack[] = $head[$key]; 
                    } else { 
                        $bref[$key] = $head[$key]; 
                    } 
                } 
            } while(count($head_stack)); 
        } 

        return $base; 
    } 
}