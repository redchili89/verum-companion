<?php

// Adds widget: Verum Advertizement
class Verumadvertizement_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'verumadvertizement_widget',
            esc_html__('Verum Advertizement', 'textdomain')
        );
        add_action('admin_footer', array($this, 'media_fields'));
        add_action('customize_controls_print_footer_scripts', array($this, 'media_fields'));
    }

    private $widget_fields = array(
        array(
            'label' => 'URL',
            'id'    => 'verum_adv_url',
            'type'  => 'text',
        ),
        array(
            'label' => 'Verum Advertizement Image',
            'id'    => 'verum_adv_image',
            'type'  => 'media',
        ),
    );

    public function widget($args, $instance)
    {
        echo wp_kses_post($args['before_widget']);
        $verum_adv_image = wp_get_attachment_image_src($instance['verum_adv_image'], 'medium');
        // Output generated fields
        echo '<a target="_blank" href="' . esc_url($instance['verum_adv_url']) . '"><img class="img-fluid" src="' . esc_url($verum_adv_image[0]) . '" alt=""/></a>';

        echo wp_kses_post($args['after_widget']);
    }

    public function media_fields()
    {
        ?>
        <script>
            jQuery(document).ready(function ($) {
                if (typeof wp.media !== 'undefined') {
                    var _custom_media = true,
                        _orig_send_attachment = wp.media.editor.send.attachment;
                    $(document).on('click', '.custommedia', function (e) {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        var button = $(this);
                        var id = button.attr('id');
                        _custom_media = true;
                        wp.media.editor.send.attachment = function (props, attachment) {
                            if (_custom_media) {
                                $('input#' + id).val(attachment.id);
                                $('span#preview' + id).css('background-image', 'url(' + attachment.url + ')');
                                $('input#' + id).trigger('change');
                            } else {
                                return _orig_send_attachment.apply(this, [props, attachment]);
                            }
                            ;
                        }
                        wp.media.editor.open(button);
                        return false;
                    });
                    $('.add_media').on('click', function () {
                        _custom_media = false;
                    });
                    $(document).on('click', '.remove-media', function () {
                        var parent = $(this).parents('p');
                        parent.find('input[type="media"]').val('').trigger('change');
                        parent.find('span').css('background-image', 'url()');
                    });
                }
            });
        </script><?php
    }

    public function field_generator($instance)
    {
        $output = '';
        foreach ($this->widget_fields as $widget_field) {
            $default = '';
            if (isset($widget_field['default'])) {
                $default = $widget_field['default'];
            }
            $widget_value = !empty($instance[$widget_field['id']]) ? $instance[$widget_field['id']] : esc_html__($default, 'textdomain');
            switch ($widget_field['type']) {
                case 'media':
                    $media_url = '';
                    if ($widget_value) {
                        $media_url = wp_get_attachment_url($widget_value);
                    }
                    $output .= '<p>';
                    $output .= '<label for="' . esc_attr($this->get_field_id($widget_field['id'])) . '">' . esc_attr($widget_field['label'], 'textdomain') . ':</label> ';
                    $output .= '<input style="display:none;" class="widefat" id="' . esc_attr($this->get_field_id($widget_field['id'])) . '" name="' . esc_attr($this->get_field_name($widget_field['id'])) . '" type="' . $widget_field['type'] . '" value="' . $widget_value . '">';
                    $output .= '<span id="preview' . esc_attr($this->get_field_id($widget_field['id'])) . '" style="margin-right:10px;border:2px solid #eee;display:block;width: 100px;height:100px;background-image:url(' . $media_url . ');background-size:contain;background-repeat:no-repeat;"></span>';
                    $output .= '<button id="' . $this->get_field_id($widget_field['id']) . '" class="button select-media custommedia">Add Media</button>';
                    $output .= '<input style="width: 19%;" class="button remove-media" id="buttonremove" name="buttonremove" type="button" value="Clear" />';
                    $output .= '</p>';
                    break;
                default:
                    $output .= '<p>';
                    $output .= '<label for="' . esc_attr($this->get_field_id($widget_field['id'])) . '">' . esc_attr($widget_field['label'], 'textdomain') . ':</label> ';
                    $output .= '<input class="widefat" id="' . esc_attr($this->get_field_id($widget_field['id'])) . '" name="' . esc_attr($this->get_field_name($widget_field['id'])) . '" type="' . $widget_field['type'] . '" value="' . esc_attr($widget_value) . '">';
                    $output .= '</p>';
            }
        }
        echo $output;
    }

    public function form($instance)
    {
        $this->field_generator($instance);
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        foreach ($this->widget_fields as $widget_field) {
            switch ($widget_field['type']) {
                default:
                    $instance[$widget_field['id']] = (!empty($new_instance[$widget_field['id']])) ? strip_tags($new_instance[$widget_field['id']]) : '';
            }
        }
        return $instance;
    }
}

function register_verumadvertizement_widget()
{
    register_widget('Verumadvertizement_Widget');
}

add_action('widgets_init', 'register_verumadvertizement_widget');
