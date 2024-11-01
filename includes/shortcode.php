<?php
//[woosearch title="" placeholder="" button_text=""]
function woosearch_shortcode_handler( $atts, $content = null ) {
    extract(shortcode_atts( array(
        'title' 			=> '',
        'placeholder' 		=> '',
        'button_text' 		=> '',
    ), $atts ));
    $output = '';
    $hidecat = '';
    $product_num = esc_attr( get_option('woosearch_product_num','5') );
    $keypress = esc_attr( get_option('woosearch_keypress','2') );
    $category = esc_attr( get_option('woosearch_category','0') );
    if($category == 0){ $hidecat='hidecat'; }
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
            if($category == 1){
                $output .= '<div class="woosearch-select">';
                    $output .= '<select name="category_name" id="searchtype">';
                        $output .= '<option value=""> '.__('All','woo-ajax-search').'</option>';
                        if(!empty($raw_data)){
                            foreach ($raw_data as $value) {
                                $output .= '<option value="'.$value["slug"].'">'.$value["name"].'</option>';
                            }
                        }
                    $output .= '</select>';
                $output .= '</div>';
            }
            $output .= '<div class="woosearch-input-box '.esc_attr($hidecat).'">';
                $output .= '<input type="text" name="s" class="woosearch-search-input" value="" placeholder="'.esc_html($placeholder).'" autocomplete="off" data-number="'.esc_attr($product_num).'" data-keypress="'.esc_attr($keypress).'">';
            $output .= '<div><div class="woosearch-results"></div></div>';
            $output .= '</div>';

            $output .= '<span class="woosearch-button">';
                $output .= '<button type="submit" class="woosearch-submit">';
                    $output .= '<i class="search-icon"></i> ' . esc_html($button_text);
                $output .= '</button>';
            $output .= '</span>';
        $output .= '</form> ';
    $output .= '</div>';


    return $output;
}
add_shortcode( 'woosearch', 'woosearch_shortcode_handler' );