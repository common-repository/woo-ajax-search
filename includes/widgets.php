<?php

add_action('widgets_init','woosearch_ajax_init_widget');

function woosearch_ajax_init_widget(){
	register_widget('woosearch_ajax_widget');
}

class woosearch_ajax_widget extends WP_Widget{

	function woosearch_ajax_widget(){
		parent::__construct( 'woosearch_ajax_widget','WooCommerce AJAX Search',array('description' => 'Show Widget of WooCommerce AJAX Search.'));
	}


	/*-------------------------------------------------------
	 *				Front-end display of widget
	 *-------------------------------------------------------*/

	function widget($args, $instance){

		extract($args);

		$title 			= apply_filters('widget_title', $instance['title'] );
		$placeholder 	= $instance['placeholder'];
		$button_text 	= $instance['button_text'];

		$output = '';

		if ( $title )
			echo $before_title . $title . $after_title;

		
		$hidecat = '';
		$product_num = esc_attr( get_option('woosearch_product_num','5') );
		$keypress = esc_attr( get_option('woosearch_keypress','2') );
		$redirect = esc_attr( get_option('woosearch_redirect','0') );

		// product_cat list
		$raw_data = array();
		$all_categories = get_categories( array( 'taxonomy'=>'product_cat','hide_empty' => true ) );
		if(is_array($all_categories)){
		    if(!empty($all_categories)){
		        foreach ($all_categories as $value) {
		            $var = array();
		            $var['slug'] = $value->slug;
		            $var['name'] = $value->name;
		            $raw_data[] = $var;
		        }
		    }
		}


	    $output .= '<div class="woosearch-search-warp">';
	        $output .= '<form id="woosearch-search" action="'.esc_url(home_url()).'" data-redirect="'.esc_attr($redirect).'" >';
	            $output .= '<div class="woosearch-title">'.esc_html($title).'</div>';
	            $output .= '<div class="woosearch-input-box hidecat">';
	                $output .= '<input type="text" name="s" class="woosearch-search-input" value="" placeholder="'.esc_html($placeholder).'" autocomplete="off" data-number="'.$product_num.'" data-keypress="'.$keypress.'">';
	            $output .= '<div><div class="woosearch-results"></div></div>';
	            $output .= '</div>';
	            $output .= '<span class="woosearch-button">';
	                $output .= '<button type="submit" class="woosearch-submit">';
	                    $output .= '<i class="search-icon"></i> ' . esc_html($button_text);
	                $output .= '</button>';
	            $output .= '</span>';
	        $output .= '</form> ';
	    $output .= '</div>';


		echo $before_widget.$output.$after_widget;

	}


	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['placeholder'] 	= strip_tags( $new_instance['placeholder'] );
		$instance['button_text'] 	= strip_tags( $new_instance['button_text'] );

		return $instance;
	}


	function form($instance){
		$defaults = array( 
			'title' 		=> 'Popular News',
			'placeholder' 	=> '',
			'button_text' 	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Widget Title', 'woo-ajax-search'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'placeholder' )); ?>"><?php esc_html_e('Placeholder', 'woo-ajax-search'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'placeholder' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'placeholder' )); ?>" value="<?php echo esc_attr($instance['placeholder']); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'button_text' )); ?>"><?php esc_html_e('Button Text', 'woo-ajax-search'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'button_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'button_text' )); ?>" value="<?php echo esc_attr($instance['button_text']); ?>" style="width:100%;" />
		</p>

	<?php
	}
}