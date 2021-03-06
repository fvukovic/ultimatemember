<?php
namespace um\admin\core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Admin_Forms' ) ) {
    class Admin_Forms {

        var $form_data;

        /**
         * Admin_Forms constructor.
         * @param bool $form_data
         */
        function __construct( $form_data = false ) {

            if ( $form_data )
                $this->form_data = $form_data;

        }


        /**
         * Render form
         *
         *
         * @param bool $echo
         * @return string
         */
        function render_form( $echo = true ) {

            if ( empty( $this->form_data['fields'] ) )
                return '';

            $class = 'form-table um-form-table ' . ( ! empty( $this->form_data['class'] ) ? $this->form_data['class'] : '' );
            $class_attr = ' class="' . $class . '" ';

            ob_start();

            foreach ( $this->form_data['fields'] as $field_data ) {
                if ( isset( $field_data['type'] ) && 'hidden' == $field_data['type'] )
                    echo $this->render_form_row( $field_data );
            }


            if ( empty( $this->form_data['without_wrapper'] ) ) { ?>

                <table <?php echo $class_attr ?>>
                    <tbody>

            <?php }

            foreach ( $this->form_data['fields'] as $field_data ) {
                if ( isset( $field_data['type'] ) && 'hidden' != $field_data['type'] )
                    echo $this->render_form_row( $field_data );
            }

            if ( empty( $this->form_data['without_wrapper'] ) ) { ?>

                    </tbody>
                </table>

            <?php }

            if ( $echo ) {
                ob_get_flush();
                return '';
            } else {
                return ob_get_clean();
            }
        }


        function render_form_row( $data ) {

            if ( empty( $data['type'] ) )
                return '';

            $conditional = ! empty( $data['conditional'] ) ? 'data-conditional="' . esc_attr( json_encode( $data['conditional'] ) ) . '"' : '';
            $prefix_attr = ! empty( $this->form_data['prefix_id'] ) ? ' data-prefix="' . $this->form_data['prefix_id'] . '" ' : '';

            $type_attr = ' data-field_type="' . $data['type'] . '" ';

            $html = '';
            if ( $data['type'] != 'hidden' ) {

                if ( ! empty( $this->form_data['div_line'] ) ) {

                    if ( strpos( $this->form_data['class'], 'um-top-label' ) !== false ) {

                        $html .= '<div class="form-field um-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>' . $this->render_field_label( $data );

                        if ( method_exists( $this, 'render_' . $data['type'] ) ) {

                            $html .= call_user_func( array( &$this, 'render_' . $data['type'] ), $data );

                        } else {

                            $html .= apply_filters( 'um_render_field_type_' . $data['type'], '', $data, $this->form_data );

                        }

                        if ( ! empty( $data['description'] ) )
                            $html .= '<p class="description">' . $data['description'] . '</p>';

                        $html .= '</div>';

                    } else {

                        if ( ! empty( $data['without_label'] ) ) {

                            $html .= '<div class="form-field um-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>';

                            if ( method_exists( $this, 'render_' . $data['type'] ) ) {

                                $html .= call_user_func( array( &$this, 'render_' . $data['type'] ), $data );

                            } else {

                                $html .= apply_filters( 'um_render_field_type_' . $data['type'], '', $data, $this->form_data );

                            }

                            if ( ! empty( $data['description'] ) )
                                $html .= '<p class="description">' . $data['description'] . '</p>';

                            $html .= '</div>';

                        } else {

                            $html .= '<div class="form-field um-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>' . $this->render_field_label( $data );

                            if ( method_exists( $this, 'render_' . $data['type'] ) ) {

                                $html .= call_user_func( array( &$this, 'render_' . $data['type'] ), $data );

                            } else {

                                $html .= apply_filters( 'um_render_field_type_' . $data['type'], '', $data, $this->form_data );

                            }

                            if ( ! empty( $data['description'] ) )
                                $html .= '<p class="description">' . $data['description'] . '</p>';

                            $html .= '</div>';

                        }
                    }

                } else {
                    if ( strpos( $this->form_data['class'], 'um-top-label' ) !== false ) {

                        $html .= '<tr class="um-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>
                        <td>' . $this->render_field_label( $data );

                        if ( method_exists( $this, 'render_' . $data['type'] ) ) {

                            $html .= call_user_func( array( &$this, 'render_' . $data['type'] ), $data );

                        } else {

                            $html .= apply_filters( 'um_render_field_type_' . $data['type'], '', $data, $this->form_data );

                        }

                        if ( ! empty( $data['description'] ) )
                            $html .= '<div class="um-admin-clear"></div><p class="description">' . $data['description'] . '</p>';

                        $html .= '</td></tr>';

                    } else {

                        if ( ! empty( $data['without_label'] ) ) {

                            $html .= '<tr class="um-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>
                            <td colspan="2">';

                            if ( method_exists( $this, 'render_' . $data['type'] ) ) {

                                $html .= call_user_func( array( &$this, 'render_' . $data['type'] ), $data );

                            } else {

                                $html .= apply_filters( 'um_render_field_type_' . $data['type'], '', $data, $this->form_data );

                            }

                            if ( ! empty( $data['description'] ) )
                                $html .= '<div class="um-admin-clear"></div><p class="description">' . $data['description'] . '</p>';

                            $html .= '</td></tr>';

                        } else {

                            $html .= '<tr class="um-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>
                            <th>' . $this->render_field_label( $data ) . '</th>
                            <td>';

                            if ( method_exists( $this, 'render_' . $data['type'] ) ) {

                                $html .= call_user_func( array( &$this, 'render_' . $data['type'] ), $data );

                            } else {

                                $html .= apply_filters( 'um_render_field_type_' . $data['type'], '', $data, $this->form_data );

                            }

                            if ( ! empty( $data['description'] ) )
                                $html .= '<div class="um-admin-clear"></div><p class="description">' . $data['description'] . '</p>';

                            $html .= '</td></tr>';

                        }
                    }
                }

            } else {
                if ( method_exists( $this, 'render_' . $data['type'] ) ) {

                    $html .= call_user_func( array( &$this, 'render_' . $data['type'] ), $data );

                } else {

                    $html .= apply_filters( 'um_render_field_type_' . $data['type'], '', $data, $this->form_data );

                }
            }

            return $html;
        }


        function render_field_label( $data ) {
            if ( empty( $data['label'] ) )
                return false;

            $id = ! empty( $data['id1'] ) ? $data['id1'] : $data['id'];
            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $id;
            $for_attr = ' for="' . $id . '" ';

            $label = $data['label'];
            $tooltip = ! empty( $data['tooltip'] ) ? UM()->tooltip( $data['tooltip'], false, false ) : '';

            return "<label $for_attr>$label $tooltip</label>";
        }


        function render_hidden( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
            $id_attr = ' id="' . $id . '" ';

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id']
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = isset( $field_data['value'] ) ? $field_data['value'] : $default;
            $value_attr = ' value="' . $value . '" ';

            $html = "<input type=\"hidden\" $id_attr $class_attr $name_attr $data_attr $value_attr />";

            return $html;
        }


        function render_text( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
            $id_attr = ' id="' . $id . '" ';

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? 'um-' . $field_data['size'] . '-field' : 'um-long-field';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id']
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $placeholder_attr = ! empty( $field_data['placeholder'] ) ? ' placeholder="' . $field_data['placeholder'] . '"' : '';

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = isset( $field_data['value'] ) ? $field_data['value'] : $default;
            $value_attr = ' value="' . $value . '" ';

            $html = "<input type=\"text\" $id_attr $class_attr $name_attr $data_attr $value_attr $placeholder_attr />";

            return $html;
        }


        function render_color( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
            $id_attr = ' id="' . $id . '" ';

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? ' um-' . $field_data['size'] . '-field ' : ' um-long-field ';
            $class .= ' um-admin-colorpicker ';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id']
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $placeholder_attr = ! empty( $field_data['placeholder'] ) ? ' placeholder="' . $field_data['placeholder'] . '"' : '';

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = isset( $field_data['value'] ) ? $field_data['value'] : $default;
            $value_attr = ' value="' . $value . '" ';

            $html = "<input type=\"text\" $id_attr $class_attr $name_attr $data_attr $value_attr $placeholder_attr />";

            return $html;
        }


        function render_icon( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
            $id_attr = ' id="' . $id . '" ';

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = isset( $field_data['value'] ) ? $field_data['value'] : $default;
            $value_attr = ' value="' . $value . '" ';

            $html = '<a href="#" class="button" data-modal="UM_fonticons" data-modal-size="normal" data-dynamic-content="um_admin_fonticon_selector" data-arg1="" data-arg2="" data-back="">' . __( 'Choose Icon', 'ultimate-member' ) . '</a>
                <span class="um-admin-icon-value">';

            if ( ! empty( $value ) ) {
                $html .= '<i class="' . $value . '"></i>';
            } else {
                $html .= __( 'No Icon', 'ultimate-member' );
            }

            $html .= '</span><input type="hidden" ' . $name_attr . ' ' . $id_attr . ' ' . $value_attr . ' />';

            if ( get_post_meta( get_the_ID(), '_um_icon', true ) ) {
                $html .= '<span class="um-admin-icon-clear show"><i class="um-icon-android-cancel"></i></span>';
            } else {
                $html .= '<span class="um-admin-icon-clear"><i class="um-icon-android-cancel"></i></span>';
            }

            $html .= '</span>';

            return $html;
        }


        function render_datepicker( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
            $id_attr = ' id="' . $id . '" ';

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? 'um-' . $field_data['size'] . '-field' : 'um-long-field';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id']
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $placeholder_attr = ! empty( $field_data['placeholder'] ) ? ' placeholder="' . $field_data['placeholder'] . '"' : '';

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = isset( $field_data['value'] ) ? $field_data['value'] : $default;
            $value_attr = ' value="' . $value . '" ';

            $html = "<input type=\"date\" $id_attr $class_attr $name_attr $data_attr $value_attr $placeholder_attr />";

            return $html;
        }


        function render_inline_texts( $field_data ) {

            if ( empty( $field_data['id1'] ) )
                return false;


            $i = 1;
            $fields = array();
            while( ! empty( $field_data['id' . $i] ) ) {
                $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'. $i];
                $id_attr = ' id="' . $id . '" ';

                $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
                $class .= ! empty( $field_data['size'] ) ? 'um-' . $field_data['size'] . '-field' : 'um-long-field';
                $class_attr = ' class="um-forms-field ' . $class . '" ';

                $data = array(
                    'field_id' => $field_data['id'. $i]
                );

                $data_attr = '';
                foreach ( $data as $key => $value ) {
                    $data_attr .= " data-{$key}=\"{$value}\" ";
                }

                $placeholder_attr = ! empty( $field_data['placeholder'] ) ? ' placeholder="' . $field_data['placeholder'] . '"' : '';

                $name = $field_data['id'. $i];
                $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
                $name_attr = ' name="' . $name . '" ';

                $default = isset( $field_data['default'. $i] ) ? $field_data['default'. $i] : '';
                $value = isset( $field_data['value'. $i] ) ? $field_data['value'. $i] : $default;
                $value_attr = ' value="' . $value . '" ';

                $fields[$i] = "<input type=\"text\" $id_attr $class_attr $name_attr $data_attr $value_attr $placeholder_attr style=\"display:inline;\"/>";

                $i++;
            }

            $html = vsprintf( $field_data['mask'], $fields );

            return $html;
        }


        function render_textarea( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
            $id_attr = ' id="' . $id . '" ';

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'um-long-field';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id']
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $rows = ! empty( $field_data['args']['textarea_rows'] ) ? ' rows="' . $field_data['args']['textarea_rows'] . '" ' : '';

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = isset( $field_data['value'] ) ? $field_data['value'] : $default;
            $html = "<textarea $id_attr $class_attr $name_attr $data_attr $rows>$value</textarea>";

            return $html;
        }


        function render_wp_editor( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'um-long-field';

            $data = array(
                'field_id' => $field_data['id']
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = isset( $field_data['value'] ) ? $field_data['value'] : $default;

            ob_start();
            wp_editor( $value,
                $id,
                array(
                    'textarea_name' => $name,
                    'textarea_rows' => 20,
                    'editor_height' => 425,
                    'wpautop'       => false,
                    'media_buttons' => false,
                    'editor_class'  => $class
                )
            );

            $html = ob_get_clean();

            return $html;
        }


        function render_checkbox( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
            $id_attr = ' id="' . $id . '" ';
            $id_attr_hidden = ' id="' . $id . '_hidden" ';

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'um-long-field';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id']
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = ( '' !== $field_data['value'] ) ? $field_data['value'] : $default;
            /*$value = isset( $field_data['value'] ) ? $field_data['value'] : $default;
            $value = ! empty( $field_data['value'] ) ? $field_data['value'] : 0;
            */

            $html = "<input type=\"hidden\" $id_attr_hidden $name_attr value=\"0\" />
            <input type=\"checkbox\" $id_attr $class_attr $name_attr $data_attr " . checked( $value, true, false ) . " value=\"1\" />";


            return $html;
        }


        function render_select( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $multiple = ! empty( $field_data['multi'] ) ? 'multiple' : '';

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
            $id_attr = ' id="' . $id . '" ';

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? 'um-' . $field_data['size'] . '-field' : 'um-long-field';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id']
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name = $name . ( ! empty( $field_data['multi'] ) ? '[]' : '' );
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = isset( $field_data['value'] ) ? $field_data['value'] : $default;

            $options = '';
            foreach ( $field_data['options'] as $key=>$option ) {
                if ( ! empty( $field_data['multi'] ) ) {

                    if ( ! is_array( $value ) || empty( $value ) )
                        $value = array();

                    $options .= '<option value="' . $key . '" ' . selected( in_array( $key, $value ), true, false ) . '>' . $option . '</option>';
                } else {
                    $options .= '<option value="' . $key . '" ' . selected( (string)$key == $value, true, false ) . '>' . $option . '</option>';
                }
            }

            $html = "<select $multiple $id_attr $name_attr $class_attr $data_attr>$options</select>";

            return $html;
        }


        function render_multi_selects( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'um-long-field';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id'],
                'id_attr' => $id
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name = "{$name}[]";
            $name_attr = ' name="' . $name . '" ';

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $values = isset( $field_data['value'] ) ? $field_data['value'] : $default;

            $options = '';
            foreach ( $field_data['options'] as $key=>$option ) {
                $options .= '<option value="' . $key . '">' . $option . '</option>';
            }

            $html = "<select class=\"um-hidden-multi-selects\" $data_attr>$options</select>";
            $html .= "<ul class=\"um-multi-selects-list\" $data_attr>";

            if ( ! empty( $values ) ) {
                foreach ( $values as $k=>$value ) {

                    $id_attr = ' id="' . $id . '-' . $k . '" ';

                    $options = '';
                    foreach ( $field_data['options'] as $key=>$option ) {
                        $options .= '<option value="' . $key . '" ' . selected( $key == $value, true, false ) . '>' . $option . '</option>';
                    }

                    $html .= "<li class=\"um-multi-selects-option-line\"><span class=\"um-field-wrapper\">
                        <select $id_attr $name_attr $class_attr $data_attr>$options</select></span>
                        <span class=\"um-field-control\"><a href=\"javascript:void(0);\" class=\"um-select-delete\">" . __( 'Remove', 'ultimate-member' ) . "</a></span></li>";
                }
            } elseif ( ! empty( $field_data['show_default_number'] ) && is_numeric( $field_data['show_default_number'] ) && $field_data['show_default_number'] > 0 ) {
                $i = 0;
                while( $i < $field_data['show_default_number'] ) {
                    $id_attr = ' id="' . $id . '-' . $i . '" ';

                    $options = '';
                    foreach ( $field_data['options'] as $key=>$option ) {
                        $options .= '<option value="' . $key . '">' . $option . '</option>';
                    }

                    $html .= "<li class=\"um-multi-selects-option-line\"><span class=\"um-field-wrapper\">
                        <select $id_attr $name_attr $class_attr $data_attr>$options</select></span>
                        <span class=\"um-field-control\"><a href=\"javascript:void(0);\" class=\"um-select-delete\">" . __( 'Remove', 'ultimate-member' ) . "</a></span></li>";

                    $i++;
                }
            }

            $html .= "</ul><a href=\"javascript:void(0);\" class=\"button button-primary um-multi-selects-add-option\" data-name=\"$name\">{$field_data['add_text']}</a>";

            return $html;
        }


        function render_multi_checkbox( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'um-long-field';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;

            $default = isset( $field_data['default'] ) ? $field_data['default'] : array();
            $values = ( isset( $field_data['value'] ) && '' !== $field_data['value'] ) ? $field_data['value'] : $default;

            $i = 0;
            $html = '';

            $columns = ( ! empty( $field_data['columns'] ) && is_numeric( $field_data['columns'] ) ) ? $field_data['columns'] : 1;
            while ( $i < $columns ) {
                $per_page = ceil( count( $field_data['options'] ) / $columns );
                $section_fields_per_page = array_slice( $field_data['options'], $i*$per_page, $per_page );
                $html .= '<span class="um-form-fields-section" style="width:' . floor( 100 / $columns ) . '% !important;">';

                foreach ( $section_fields_per_page as $k => $title ) {
                    $id_attr = ' id="' . $id . '_' . $k . '" ';
                    $for_attr = ' for="' . $id . '_' . $k . '" ';
                    $name_attr = ' name="' . $name . '[' . $k . ']" ';

                    $html .= "<label $for_attr>
                        <input type=\"checkbox\" " . checked( in_array( $k, $values ), true, false ) . "$id_attr $name_attr value=\"1\" $class_attr>
                        <span>$title</span>
                    </label>";
                }
                
                $html .= '</span>';
                $i++;
            }

            return $html;
        }


        function render_multi_text( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];

            $size = ! empty( $field_data['size'] ) ? 'um-' . $field_data['size'] . '-field' : 'um-long-field';

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class_attr = ' class="um-forms-field ' . $class . '" ';

            $data = array(
                'field_id' => $field_data['id'],
                'id_attr' => $id
            );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $name = "{$name}[]";
            $name_attr = ' name="' . $name . '" ';

            //$values = ! empty( $field_data['value'] ) ? $field_data['value'] : ( ! empty( $field_data['default'] ) ? $field_data['default'] : '' );

            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $values = isset( $field_data['value'] ) ? $field_data['value'] : $default;

            $html = "<input type=\"text\" class=\"um-hidden-multi-text\" $data_attr />";
            $html .= "<ul class=\"um-multi-text-list\" $data_attr>";

            if ( ! empty( $values ) ) {
                foreach ( $values as $k=>$value ) {

                    $id_attr = ' id="' . $id . '-' . $k . '" ';

                    $html .= "<li class=\"um-multi-text-option-line {$size}\"><span class=\"um-field-wrapper\">
                        <input type=\"text\" $id_attr $name_attr $class_attr $data_attr value=\"$value\" /></span>
                        <span class=\"um-field-control\"><a href=\"javascript:void(0);\" class=\"um-text-delete\">" . __( 'Remove', 'ultimate-member' ) . "</a></span></li>";
                }
            } elseif ( ! empty( $field_data['show_default_number'] ) && is_numeric( $field_data['show_default_number'] ) && $field_data['show_default_number'] > 0 ) {
                $i = 0;
                while( $i < $field_data['show_default_number'] ) {
                    $id_attr = ' id="' . $id . '-' . $i . '" ';

                    $html .= "<li class=\"um-multi-text-option-line {$size}\"><span class=\"um-field-wrapper\">
                         <input type=\"text\" $id_attr $name_attr $class_attr $data_attr value=\"\" /></span>
                        <span class=\"um-field-control\"><a href=\"javascript:void(0);\" class=\"um-text-delete\">" . __( 'Remove', 'ultimate-member' ) . "</a></span></li>";

                    $i++;
                }
            }

            $html .= "</ul><a href=\"javascript:void(0);\" class=\"button button-primary um-multi-text-add-option\" data-name=\"$name\">{$field_data['add_text']}</a>";

            return $html;
        }


        function render_media( $field_data ) {

            if ( empty( $field_data['id'] ) )
                return false;

            $id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];

            $class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
            $class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'um-long-field';
            $class_attr = ' class="um-forms-field um-media-upload-data-url ' . $class . '"';

            $data = array(
                'field_id' => $field_data['id'],
            );

            if ( ! empty( $field_data['default']['url'] ) )
                $data['default'] = esc_attr( $field_data['default']['url'] );

            $data_attr = '';
            foreach ( $data as $key => $value ) {
                $data_attr .= " data-{$key}=\"{$value}\" ";
            }

            $name = $field_data['id'];
            $name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
            $default = isset( $field_data['default'] ) ? $field_data['default'] : '';
            $value = ! empty( $field_data['value'] ) ? $field_data['value'] : $default;

            $upload_frame_title = ! empty( $field_data['upload_frame_title'] ) ? $field_data['upload_frame_title'] : __( 'Select media', 'ultimate-member' );

            $image_id = ! empty( $value['id'] ) ? $value['id'] : '';
            $image_width = ! empty( $value['width'] ) ? $value['width'] : '';
            $image_height = ! empty( $value['height'] ) ? $value['height'] : '';
            $image_thumbnail = ! empty( $value['thumbnail'] ) ? $value['thumbnail'] : '';
            $image_url = ! empty( $value['url'] ) ? $value['url'] : '';

            $html = "<div class=\"um-media-upload\">" .
                "<input type=\"hidden\" class=\"um-media-upload-data-id\" name=\"{$name}[id]\" id=\"{$id}_id\" value=\"$image_id\">" .
                "<input type=\"hidden\" class=\"um-media-upload-data-width\" name=\"{$name}[width]\" id=\"{$id}_width\" value=\"$image_width\">" .
                "<input type=\"hidden\" class=\"um-media-upload-data-height\" name=\"{$name}[height]\" id=\"{$id}_height\" value=\"$image_height\">" .
                "<input type=\"hidden\" class=\"um-media-upload-data-thumbnail\" name=\"{$name}[thumbnail]\" id=\"{$id}_thumbnail\" value=\"$image_thumbnail\">" .
                "<input type=\"hidden\" $class_attr name=\"{$name}[url]\" id=\"{$id}_url\" value=\"$image_url\" $data_attr>";

            if ( ! isset( $field_data['preview'] ) || $field_data['preview'] !== false ) {
                $html .= '<img src="' . $image_url . '" alt="" class="icon_preview"><div style="clear:both;"></div>';
            }

            if ( ! empty( $field_data['url'] ) ) {
                $html .= '<input type="text" class="um-media-upload-url" readonly value="' . $image_url . '" /><div style="clear:both;"></div>';
            }

            $html .= '<input type="button" class="um-set-image button button-primary" value="' . __( 'Select', 'ultimate-member' ) . '" data-upload_frame="' . $upload_frame_title . '" />
                    <input type="button" class="um-clear-image button" value="' . __( 'Clear', 'ultimate-member' ) . '" /></div>';

            return $html;
        }

    }
}