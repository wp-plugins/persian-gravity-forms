<?php
class GFIRLivePreview {
    var $post_type = 'gf_live_preview';
    function __construct( $args = array() ) {
        if( ! property_exists( 'GFCommon', 'version' ) || ! version_compare( GFCommon::$version, '1.8', '>=' ) )
            return;
    	$this->_args = wp_parse_args( $args, array( 
    		'id' => 0,
    		'title' => true,
    		'description' => true,
    		'ajax' => true
    	) );
    	add_action( 'init', array( $this, 'register_preview_post_type' ) );
    	add_action( 'wp', array( $this, 'maybe_load_preview_functionality' ) );
        add_action( 'admin_footer', array( $this, 'display_preview_link' ) );
    }
    function register_preview_post_type() {
		$args = array(
			'label' => __( 'پیشنمایش فرم' , 'Persian_Gravityforms_By_HANNANStd'),
			'description' => __( 'اضافه کردن پیشنمایش فرم در فرانت اند سایت' , 'Persian_Gravityforms_By_HANNANStd'),
			'public' => false,
			'publicly_queryable' => true,
			'has_archive' => true,
			'can_export' => false,
			'supports' => false
		);
		register_post_type( $this->post_type, $args );		
		$preview_post = get_posts( array( 'post_type' => $this->post_type ) );
		if( empty( $preview_post ) ) {
			$post_id = wp_insert_post( array( 
				'post_type' => $this->post_type,
				'post_title' => __( 'پیشنمایش فرم' ),
				'post_status' => 'publish'
			) );
		}
    }
    function maybe_load_preview_functionality() {
    	global $wp_query;
		if( ! $this->is_live_preview() )
			return;
		$this->live_preview_hooks();
		foreach( $wp_query->posts as &$post ) {
			$post->post_content = $this->get_shortcode();
		}	
    }
    function live_preview_hooks() {
	    add_filter( 'template_include', array( $this, 'load_preview_template' ) );
		add_filter( 'the_content', array( $this, 'modify_preview_post_content' ) );
    }
    function display_preview_link() {    
        if( ! $this->is_applicable_page() )
            return;
        $form_id = rgget( 'id' );
        $url = get_bloginfo("wpurl") . '/?post_type=gf_live_preview&id=' . $form_id;
        ?>
        <script type="text/javascript">
        (function($){
            $(  '<li class="gf_form_toolbar_preview"><a style="position:relative" id="gf-live-preview" href="<?php echo $url; ?>" target="_blank">' +
            		'<i class="fa fa-eye" style="position: absolute; text-shadow: 0px 0px 5px rgb(255, 255, 255); z-index: 99; line-height: 7px; left: 0px font-size: 9px; top: 6px; background-color: rgb(243, 243, 243);"></i>' +
            		'<i class="fa fa-file-o" style="margin-left: 5px; line-height: 12px; font-size: 18px; position: relative; top: 2px;"></i>' +
            		'<?php _e( 'Live Preview' ); ?>' +
            	'</a></li>' )
                .insertAfter( 'li.gf_form_toolbar_preview' );
        })(jQuery);
        </script>
        <?php
    }
    function is_applicable_page() {
        return in_array( rgget( 'page' ), array( 'gf_edit_forms', 'gf_entries' ) ) && rgget( 'id' );
    }
	function load_preview_template( $template ) {
	    return get_page_template();
	}
    function modify_preview_post_content( $content ) {
		return $this->get_shortcode();
    }
    function get_shortcode( $args = array() ) {	
    	if( !is_user_logged_in() )
    		return '<p>' . __( 'برای مشاهده پیشنمایش فرم باید در سایت وارد شوید .' , 'Persian_Gravityforms_By_HANNANStd' ) . '</p>' . wp_login_form( array( 'echo' => false ) );
    	if( !GFCommon::current_user_can_any( 'gravityforms_preview_forms' ) )
    		return __( 'متاسفانه شما سطح دسترسی لازم برای مشاهده پیشنمایش فرم را ندارید .' , 'Persian_Gravityforms_By_HANNANStd' );
    	if( empty( $args ) )
    		$args = $this->get_shortcode_parameters_from_query_string();
    	extract( wp_parse_args( $args, $this->_args ) );
    	$title = $title === true ? 'true' : 'false';
    	$description = $description === true ? 'true' : 'false';
    	$ajax = $ajax === true ? 'true' : 'false';	
		return "[gravityform id='$id' title='$title' description='$description' ajax='$ajax']";
    }
    function get_shortcode_parameters_from_query_string() {
		return array_filter( array(
			'id'          => rgget( 'id' ),
			'title'       => rgget( 'title' ),
			'description' => rgget( 'description' ),
			'ajax'        => rgget( 'ajax' )
		) );
    }
    function is_live_preview() {
		return is_post_type_archive( $this->post_type );
    }
}
new GFIRLivePreview( array( 'title' => true, 'description' => true,'ajax' => true) );