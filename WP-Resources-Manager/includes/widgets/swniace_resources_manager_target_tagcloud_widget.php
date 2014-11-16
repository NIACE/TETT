<?php 
// use widgets_init action hook to execute custom function
add_action( 'widgets_init', 'swniace_resources_target_tagcloud_widget_register_widgets' );

 //register our widget
function swniace_resources_target_tagcloud_widget_register_widgets() {
    register_widget( 'swniace_resources_target_tagcloud_widget_widget' );
}

//boj_widget_my_info class
class swniace_resources_target_tagcloud_widget_widget extends WP_Widget {

    //process the new widget
    function swniace_resources_target_tagcloud_widget_widget() {
        $widget_ops = array( 
			'classname' => 'swniace_resources_target_tagcloud_widget_widget_class', 
			'description' => 'Display Target Tag Cloud' 
			); 
        $this->WP_Widget( 'swniace_resources_target_tagcloud_widget_widget', 'NIACE Resources Equality and Diversity Specific Tag Cloud', $widget_ops );
    }
 
     //build the widget settings form
    function form($instance) {
        $defaults = array( 'title' => 'Equality and Diversity Tags', 'description' => '', 'maxtags'=> 45 );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = $instance['title'];
        $description = $instance['description'];
        $maxtags = $instance['maxtags'];
        ?>
            <p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"  type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
            <p>Description: <textarea class="widefat" name="<?php echo $this->get_field_name( 'description' ); ?>" /><?php echo esc_attr( $description ); ?></textarea></p>
            <p>Maximum Tags To Display: <input id='target_maxtags' name="<?php echo $this->get_field_name( 'maxtags' ); ?>" size='6' type="text" value="<?php echo esc_attr( $maxtags ); ?>" /></p>
        <?php
    }
 
    //save the widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );
        $instance['maxtags'] = strip_tags( $new_instance['maxtags'] );
        return $instance;
    }
 
    //display the widget
    function widget($args, $instance) {
        extract($args);
        echo $before_widget;
        $title = apply_filters( 'widget_title', $instance['title'] );
        $description = empty( $instance['description'] ) ? '&nbsp;' : $instance['description']; 
        $maxtags = empty( $instance['maxtags'] ) ? 45 : $instance['maxtags']; 
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
        if ( !empty( $description ) ) {echo '<p><em>' . $description . '</em></p>'; };
        $args = array(
            'smallest'                  => 11, 
            'largest'                   => 22,
            'unit'                      => 'pt', 
            'number'                    => $maxtags,  
            'format'                    => 'flat',
            'separator'                 => "\n",
            'orderby'                   => 'name', 
            'order'                     => 'ASC',
            'exclude'                   => null, 
            'include'                   => null, 
            'topic_count_text_callback' => default_topic_count_text,
            'link'                      => 'view', 
            'taxonomy'                  => 'resource_target_category', 
            'echo'                      => true,
            'child_of'                   => null
        );
        wp_tag_cloud( $args );
        echo $after_widget;
    }
}
?>