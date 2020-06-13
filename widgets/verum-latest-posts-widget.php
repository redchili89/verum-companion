<?php

// Adds widget: Verum Latest Posts
class Verumlatestposts_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'verumlatestposts_widget',
            esc_html__('Verum Latest Posts', 'verum'),
            array('description' => esc_html__('Verum Latest Posts', 'verum'),) // Args
        );
    }

    private $widget_fields = array(
        array(
            'label'   => 'Number of Posts',
            'id'      => 'verum_no_posts',
            'default' => '3',
            'type'    => 'number',
        ),
        array(
            'label'   => 'Display Thumbnail',
            'id'      => 'verum_display_thumbnail',
            'default' => 'true',
            'type'    => 'checkbox',
        ),
        array(
            'label'   => 'Display Date',
            'id'      => 'verum_display_date',
            'default' => 'true',
            'type'    => 'checkbox',
        ),
    );

    public function widget($args, $instance)
    {
        echo wp_kses_post($args['before_widget']);

        if (!empty($instance['title'])) {
            echo wp_kses_post($args['before_title']) . apply_filters('widget_title', $instance['title']) . wp_kses_post($args['after_title']);
        }

        // Output generated fields
        $posts_args = array(
            'posts_per_page' => $instance['verum_no_posts'],
            'post_type'      => 'post',
            'post_status'    => 'publish'
        );
        $verum_latest_posts = new WP_Query($posts_args);
        while ($verum_latest_posts->have_posts()) {
            $verum_latest_posts->the_post();
            ?>
            <div class="media">
                <?php
                if ($instance['verum_display_thumbnail']):
                    ?>
                    <a href="<?php the_permalink(); ?> ">
                        <?php the_post_thumbnail('verum-sidebar-thumbnail', array('class' => 'mr-3')); ?>
                    </a>
                    <?php
                endif;
                ?>
                <div class="media-body align-self-center">
                    <h6 class="mt-0"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                    <?php
                    if ($instance['verum_display_date']):
                        ?>
                        <p class="text-muted"><?php echo get_the_date('F j, Y'); ?> </p>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
            <?php
        }
        echo wp_kses_post($args['after_widget']);
    }

    public function field_generator($instance)
    {
        $output = '';
        foreach ($this->widget_fields as $widget_field) {
            $default = '';
            if (isset($widget_field['default'])) {
                $default = $widget_field['default'];
            }
            $widget_value = !empty($instance[$widget_field['id']]) ? $instance[$widget_field['id']] : esc_html__($default, 'verum');
            switch ($widget_field['type']) {
                case 'checkbox':
                    $output .= '<p>';
                    $output .= '<input class="checkbox" type="checkbox" ' . checked($widget_value, true, false) . ' id="' . esc_attr($this->get_field_id($widget_field['id'])) . '" name="' . esc_attr($this->get_field_name($widget_field['id'])) . '" value="1">';
                    $output .= '<label for="' . esc_attr($this->get_field_id($widget_field['id'])) . '">' . esc_attr($widget_field['label'], 'verum') . '</label>';
                    $output .= '</p>';
                    break;
                default:
                    $output .= '<p>';
                    $output .= '<label for="' . esc_attr($this->get_field_id($widget_field['id'])) . '">' . esc_attr($widget_field['label'], 'verum') . ':</label> ';
                    $output .= '<input class="widefat" id="' . esc_attr($this->get_field_id($widget_field['id'])) . '" name="' . esc_attr($this->get_field_name($widget_field['id'])) . '" type="' . $widget_field['type'] . '" value="' . esc_attr($widget_value) . '">';
                    $output .= '</p>';
            }
        }
        echo $output;
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('', 'verum');
        ?>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'verum'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
        $this->field_generator($instance);
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        foreach ($this->widget_fields as $widget_field) {
            switch ($widget_field['type']) {
                default:
                    $instance[$widget_field['id']] = (!empty($new_instance[$widget_field['id']])) ? strip_tags($new_instance[$widget_field['id']]) : '';
            }
        }
        return $instance;
    }
}

function register_verumlatestposts_widget()
{
    register_widget('Verumlatestposts_Widget');
}

add_action('widgets_init', 'register_verumlatestposts_widget');