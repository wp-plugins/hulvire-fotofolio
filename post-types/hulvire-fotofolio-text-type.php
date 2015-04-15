<?php
function huu_register_init() { 
	
	$labels = array(
	'name'                => _x( 'hulvire_fotofolio', 'post type general name' ),
	'singular_name'       => _x( 'Fotofolio', 'post type singular name' ),
	'add_new'             => _x( 'Pridaj nový', 'fotofolio' ),
	'add_new_item'        => __( 'Pridaj nové fotofolio' ),
	'menu_name'           => __( 'Hulvire Fotofolio' ),
	'all_items'           => __( 'Všetky fotofolio' ),
	'view_item'           => __( 'View Fotofolio' ),
	'edit_item'           => __( 'Uprav Fotofolio' ),
	'not_found_in_trash'  => __('No Fotofolio found in Trash'),
	'parent_item_colon'   => '',
	    'menu_name' => __('Hulvire Fotofolio') 
	);
		
	
    $args = array( 
		'labels' => $labels,
			'public' => true,
		    'publicly_queryable' => true,
		    'show_ui' => true, 
		    'show_in_menu' => true, 
		    'query_var' => true,
		    'rewrite' => true,
		    'capability_type' => 'post',
		    'has_archive' => true, 
		    'hierarchical' => false, 
			'menu_position' => 5,
		'menu_icon' => plugin_dir_url( __FILE__ ). '../images/fotofolio-icon-a.png',
        'supports' => array('title','thumbnail') 
       ); 
   
    register_post_type('hulvire_fotofolio' , $args ); 
} 
add_action('init', 'huu_register_init');
add_theme_support('post-thumbnails', array('hulvire_fotofolio'));


/* Add Custom Columns */
function huu_edit_columns($columns)
{
	$columns = array(
		  "cb" => '<input type="checkbox" >',
		  "title" => __( 'Fotofolio Title' ),		  
		  /*"thumb" => __( 'Thumbnail' ),*/		  		 
		  "fotofolio_before_text" => __('Before text' ),
		  "fotofolio_after_text" => __('After text' ),
		  "date" => __( 'Date' )
	);
	
	return $columns;
}
add_filter("manage_hulvire_fotofolio_posts_columns", "huu_edit_columns");



function huu_custom_columns($column){
	global $post;
	switch ($column)
	{
		/*case 'thumb':
			if(has_post_thumbnail($post->ID))
			{
				the_post_thumbnail('fotofolio-img-thumb',array( 'style' => 'width:180px;height:auto' ) );                   
			}
			else
			{
				_e('No Fotofolio Image');
			}
			break;		*/
		case 'fotofolio_before_text':
			$fotofolio_before_text = get_post_meta($post->ID,'fotofolio_before_text',true);
			if(!empty($fotofolio_before_text))
			{
				$fotofolio_before_text = substr($fotofolio_before_text, 0, 200); echo $fotofolio_before_text." ...";
			}
			else
			{
				_e('NA');
			}		
			break;
		case 'fotofolio_after_text':
			$fotofolio_after_text = get_post_meta($post->ID,'fotofolio_after_text',true);
			if(!empty($fotofolio_after_text))
			{
				$fotofolio_after_text = substr($fotofolio_after_text, 0, 200); echo $fotofolio_after_text." ...";
			}
			else
			{
				_e('NA');
			}		
			break;
	}
}
add_action("manage_hulvire_fotofolio_posts_custom_column", "huu_custom_columns");



/*-----------------------------------------------------------------------------------*/
/*	Add Metabox to Fotofolio
/*-----------------------------------------------------------------------------------*/	


	function huu_add_meta_boxes() {
	    add_meta_box('huu_meta_id', 'Fotofolio post text', 'fotofolio_meta_box', 'hulvire_fotofolio', 'normal');
	}
	add_action('add_meta_boxes', 'huu_add_meta_boxes');
	
	function fotofolio_meta_box( $post )
	{
		$values = get_post_custom( $post->ID );
		
		$fotofolio_before_text = isset( $values['fotofolio_before_text'] ) ? esc_attr( $values['fotofolio_before_text'][0] ) : '';
		$fotofolio_after_text = isset( $values['fotofolio_after_text'] ) ? esc_attr( $values['fotofolio_after_text'][0] ) : '';
		
		wp_nonce_field( 'fotofolio_meta_box_nonce', 'meta_box_nonce_fotofolio' );
		?>
		<table style="width:100%;">			
        	<tr>
				<td style="width:25%;">
					<label for="fotofolio_before_text"><strong><?php _e('Before text','HuuA');?></strong></label>					
				</td>
				<td style="width:75%;">
					<textarea type="text" name="fotofolio_before_text" id="fotofolio_before_text" value="" style="width:60%; margin-right:4%;" ><?php echo $fotofolio_before_text; ?></textarea>
                    <span style="color:#999; display:block;"><?php _e('Toto je text pod nadpisom, ktory je zobrazeny stale','HuuA'); ?></span>
				</td>
			</tr>
        	<tr>
				<td style="width:25%;">
					<label for="fotofolio_after_text"><strong><?php _e('After text','HuuA');?></strong></label>					
				</td>
				<td style="width:75%;">
					<textarea type="text" name="fotofolio_after_text" id="fotofolio_after_text" value="" style="width:60%; margin-right:4%;" ><?php echo $fotofolio_after_text; ?></textarea>
                    <span style="color:#999; display:block;"><?php _e('Toto je text ktory sa zobrazi po stlaceni tlacitka Zobraz viacej','HuuA'); ?></span>
				</td>
			</tr>	
		</table>		        		
		<?php
	}
	
	
	//Pridaj viac fotografií METABOX
	 add_filter( 'rwmb_meta_boxes', 'hulvire_fotofolio_register_meta_boxes' );
	 function hulvire_fotofolio_register_meta_boxes( $meta_boxes )
	 {
	 	$meta_boxes[] = array(
			 'title' => __( 'Fotoalbum', 'meta-box' ),
			 'pages'    => array( 'hulvire_fotofolio' ),
			 'fields' => array(
				 // IMAGE ADVANCED (WP 3.5+)
				 array(
				 'name' => __( '...', 'meta-box' ),
				 'id' => "hulvire_fotofolio_imgadv",
				 'type' => 'image_advanced',
				 'size' => 'full',
				 'max_file_uploads' => 20,
				 ),
			 )
	 	);
	 	return $meta_boxes;
	}
	
	
	
	add_action( 'save_post', 'fotofolio_meta_box_save' );
	
	function fotofolio_meta_box_save( $post_id )
	{
		
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		if( !isset( $_POST['meta_box_nonce_fotofolio'] ) || !wp_verify_nonce( $_POST['meta_box_nonce_fotofolio'], 'fotofolio_meta_box_nonce' ) ) return;
		
		if( !current_user_can( 'edit_post' ) ) return;				
		
		if( isset( $_POST['fotofolio_before_text'] ) )
			update_post_meta( $post_id, 'fotofolio_before_text', $_POST['fotofolio_before_text']  );
		
		if( isset( $_POST['fotofolio_after_text'] ) )
			update_post_meta( $post_id, 'fotofolio_after_text', $_POST['fotofolio_after_text']  );
	

	}
	