<div class="um-admin-metabox">

	<?php
    $user_fields = array();
    foreach ( UM()->builtin()->all_user_fields() as $key => $arr ) {
        $user_fields[$key] = isset( $arr['title'] ) ? $arr['title'] : '';
    }

	$post_id = get_the_ID();
	$_um_tagline_fields = get_post_meta( $post_id, '_um_tagline_fields', true );
	$_um_reveal_fields = get_post_meta( $post_id, '_um_reveal_fields', true );

    UM()->admin_forms( array(
		'class'		=> 'um-member-directory-profile um-half-column',
		'prefix_id'	=> 'um_metadata',
		'fields' => array(
			array(
				'id'		=> '_um_profile_photo',
				'type'		=> 'checkbox',
				'name'		=> '_um_profile_photo',
				'label'		=> __( 'Enable Profile Photo', 'ultimatemember' ),
				'value'		=> UM()->query()->get_meta_value( '_um_profile_photo', null, 1 ),
			),
			array(
				'id'		=> '_um_cover_photos',
				'type'		=> 'checkbox',
				'name'		=> '_um_cover_photos',
				'label'		=> __( 'Enable Cover Photo', 'ultimatemember' ),
				'tooltip'	=> __( 'If turned on, the users cover photo will appear in the directory', 'ultimatemember' ),
				'value'		=> UM()->query()->get_meta_value( '_um_cover_photos', null, 1 ),
			),
			array(
				'id'		=> '_um_show_name',
				'type'		=> 'checkbox',
				'name'		=> '_um_show_name',
				'label'		=> __( 'Show display name', 'ultimatemember' ),
				'value'		=> UM()->query()->get_meta_value( '_um_show_name', null, 1 ),
			),
			array(
				'id'		=> '_um_show_tagline',
				'type'		=> 'checkbox',
				'name'		=> '_um_show_tagline',
				'label'		=> __( 'Show tagline below profile name', 'ultimatemember' ),
				'value'		=> UM()->query()->get_meta_value( '_um_show_tagline' ),
			),
			array(
				'id'		=> '_um_tagline_fields',
				'type'		=> 'multi_selects',
				'name'		=> '_um_tagline_fields',
				'label'		=> __( 'Choose field(s) to display in tagline', 'ultimatemember' ),
				'value'		=> $_um_tagline_fields,
				'conditional'   => array( '_um_show_tagline', '=', 1 ),
				'add_text'		=> __( 'Add New Custom Field','ultimatemember' ),
				'options'		=> $user_fields,
				'show_default_number'	=> 1,
			),
			array(
				'id'		=> '_um_show_userinfo',
				'type'		=> 'checkbox',
				'name'		=> '_um_show_userinfo',
				'label'		=> __( 'Show extra user information below tagline?', 'ultimatemember' ),
				'value'		=> UM()->query()->get_meta_value( '_um_show_userinfo' ),
			),
			array(
				'id'		=> '_um_userinfo_animate',
				'type'		=> 'checkbox',
				'name'		=> '_um_userinfo_animate',
				'label'		=> __( 'Enable reveal section transition by default', 'ultimatemember' ),
				'value'		=> UM()->query()->get_meta_value( '_um_userinfo_animate' ),
				'conditional'   => array( '_um_show_userinfo', '=', 1 )
			),
			array(
				'id'		=> '_um_reveal_fields',
				'type'		=> 'multi_selects',
				'name'		=> '_um_reveal_fields',
				'label'		=> __( 'Choose field(s) to display in reveal section', 'ultimatemember' ),
				'value'		=> $_um_reveal_fields,
				'add_text'		=> __( 'Add New Custom Field', 'ultimatemember' ),
				'conditional'   => array( '_um_show_userinfo', '=', 1 ),
                'options'		=> $user_fields,
                'show_default_number'	=> 1,
			),
			array(
				'id'		=> '_um_show_social',
				'type'		=> 'checkbox',
				'name'		=> '_um_show_social',
				'label'		=> __( 'Show social connect icons', 'ultimatemember' ),
				'value'		=> UM()->query()->get_meta_value( '_um_show_social' ),
				'conditional'   => array( '_um_show_userinfo', '=', 1 )
			)
		)
	) )->render_form(); ?>
	
	<div class="um-admin-clear"></div>
</div>