<?php
/**
 * tokoder
 *
 * An Open-source online ordering and management system for store
 *
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link			https://github.com/tokoder/tokoder
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

add_filter( 'after_scripts', function ( $output ) {
	$output .= <<<EOT
	<script>
	$(document).ready(function(){
		$(document).on('click', '#refresh_captcha', function(e) {
			e.preventDefault();

			$.ajax({
				type: "GET",
				url: cg.config.site_url + 'ajax/gamelang/captcha',
				beforeSend: function(xhr) {
					$('#refresh_captcha')
						.find('.fa')
						.addClass('fa-spin-pulse');
				},
				success: function(result, status, xhr) {
					$('#captcha').attr('src', result.results);
				},
				complete: function(xhr, status) {
					$('#captcha-input').focus();
					$('#refresh_captcha')
						.find('.fa')
						.removeClass('fa-spin-pulse');
				}
			});
		});
	});
	</script>
	EOT;
	return $output;
});

if (get_option('use_captcha', false) === true) :
	$captcha = generate_captcha();
	echo '<div class="form-group mb-3">';
	if (get_option('use_recaptcha', false) === true && ! empty(get_option('recaptcha_site_key', null))) {
		echo $captcha['captcha'];
	} else {
		echo
		'<div class="input-group">
			<div class="input-group-text pos-rel">'
				.$captcha['image'].
				html_tag('a', array(
					'class' => 'btn p-0 pos-abs',
					'id' => 'refresh_captcha',
				), fa_icon('rotate')),
			'</div>',
			print_input($captcha['captcha'], array(
				'class' => 'form-control fs-6'.(has_error('captcha') ? ' is-invalid' : ''),
			)),
			form_error('captcha', '<div class="invalid-feedback">', '</div>'),
		'</div>';
	}
	echo '</div>';
endif;