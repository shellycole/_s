<?php
/**
 * Custom shortcodes
 *
 * @package _s
 */

/**
 * [_s_email]
 * Use this shortcode to display an anti-spambot email address.
 * "to" is the email address you want to link to. (if left blank, it will use
 *      the email address set in the options page
 * "name" is the text you want to be linked. If left empty, it will use the 
 *        email address from the "to" field.
 * "image" is the image you want linked with the email address. You need to use
 *         the full path to the image file.
 * @param type $atts
 * @return string
 */
function _s_protect_email_addresses($atts) {
	$options = get_option( _S_OPTIONS );
	extract(shortcode_atts(array(
		'to' => '',
		'name' => '',
		'image' => ''
	), $atts));
	
        if(!empty($options['email']))
            $address = $options['email'];
        else 
            $address = $atts['to'];
        
	$name = $atts['name'];
	$img = $atts['image'];

	if($address) {
	  $link = '<a href="mailto:' . antispambot($address) . '">';
		
	  if($name)
	    $link .= $name;
	  elseif($img) 	
  		$link .= $img;
	  else
	    $link .= antispambot($address);
	  
	  $link .= '</a>';
	}
	
	return $link;
}
_s_add_shortcode('email', 'protect_email_addresses');