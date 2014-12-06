<?php
/*
Plugin Name: Persian Gravity Forms
Plugin URI: http://www.gravityforms.ir
Description: Gravity Forms for Iranian 
Version: 1.0.1
Requires at least: 3.0
Author: HANNAN Ebrahimi Setoode
Author URI: http://www.webforest.ir
Text Domain: Persian_Gravityforms_By_HANNANStd
Domain Path: /languages/
License: GPL 2
*/
class GravityFormsPersian {
	private $file;
	private $language;
	private $is_persian;
	public function __construct( $file ) {
		$this->file = $file;
		add_action('init', array( $this, 'init' ), 8 );		
		add_filter('update_footer', array( $this, 'GravityForms_Footer_Left_By_HANNANStd'), 11); 
		add_action('gform_post_status_options', array( $this, 'Add_Private_Post_Status_By_HANNANStd'));
		add_filter('load_textdomain_mofile', array( $this, 'Load_Textdomain_Mo_File_By_HANNANStd'), 10, 2 );
		add_filter('gform_currencies', array( $this, 'Update_Currency_By_HANNANStd' ) );
		add_filter('gform_address_types', array( $this, 'Gform_IRAN_By_HANNANStd' ) );
		add_action('activated_plugin', array( $this, 'Activated_Plugin_By_HANNANStd' ) );
		add_action('gform_admin_pre_render', array( $this, 'Add_Merge_Tags_To_List_By_HANNANStd'));
		add_filter('gform_replace_merge_tags', array( $this, 'GformReplaceMergeTags_CSS_By_HANNANStd'), 10, 7);
		add_filter('gform_replace_merge_tags', array( $this, 'GformReplaceMergeTags_Simple_By_HANNANStd'), 10, 7);
		add_filter('gform_replace_merge_tags', array( $this, 'GformReplaceMergeTags_Pack_By_HANNANStd'), 10, 7);
		add_action('wp_dashboard_setup', array( $this, 'Persian_GravityForms_Dashboard_By_HANNANStd'));
        add_filter('gform_print_styles', array( $this, 'Add_Styles_Print_By_HANNANStd'), 10, 2);
        add_action('admin_print_scripts', array( $this, 'GravityForms_Admin_CSS_By_HANNANStd' ));
        add_filter('gform_predefined_choices', array( $this, 'Add_Iran_Predefined_Choice_By_HANNANStd' ),1);
        }
        public function Activated_Plugin_By_HANNANStd() {
		$path = str_replace( WP_PLUGIN_DIR . '/', '', $this->file );
		if ( $plugins = get_option( 'active_plugins' ) ) {
			if ( $key = array_search( $path, $plugins ) ) {
				array_splice( $plugins, $key, 1 );
				array_unshift( $plugins, $path );
				update_option( 'active_plugins', $plugins );
			}
		}
		if ( $plugins = get_site_option( 'active_sitewide_plugins' ) ) {
			if ( $key = array_search( $path, $plugins ) ) {
				array_splice( $plugins, $key, 1 );
				array_unshift( $plugins, $path );
				update_site_option( 'active_sitewide_plugins', $plugins );
			}
		}
	}
	public function init() {
		$rel_path = dirname( plugin_basename( $this->file ) ) . '/languages/';
		if ( $this->language == null ) {
			$this->language = get_option( 'WPLANG', WPLANG );
			$this->is_persian = ( $this->language == 'fa' || $this->language == 'fa_IR' );
		}
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$this->is_persian = ( ICL_LANGUAGE_CODE == 'fa' );
		}
		load_plugin_textdomain( 'Persian_Gravityforms_By_HANNANStd', false, $rel_path );
		load_plugin_textdomain( 'gravityformsuserregistration', false, $rel_path );
	}
	public function Load_Textdomain_Mo_File_By_HANNANStd( $mo_file, $domain ) {
		if ( strpos( $mo_file, 'fa_IR.mo' ) !== false ) {
			$domains = array(
				'gravityforms'                 => array(
					'languages/gravityforms-fa_IR.mo'                 => 'gravityforms/fa_IR.mo'
				),
				'gravityformscoupons'  => array(
					'languages/gravityformscoupons-fa_IR.mo'  => 'gravityformscoupons/fa_IR.mo'
				),
				'gravityformsmailchimp'        => array(
					'languages/gravityformsmailchimp-fa_IR.mo'        => 'gravityformsmailchimp/fa_IR.mo'
				),
				'gravityformspolls'           => array(
					'languages/gravityformspolls-fa_IR.mo'           => 'gravityformspolls/fa_IR.mo'
				),
				'gravityformsquiz'            => array(
					'languages/gravityformsquiz-fa_IR.mo'            => 'gravityformsquiz/fa_IR.mo'
				),
				'gravityformssignature'        => array(
					'languages/gravityformssignature-fa_IR.mo'        => 'gravityformssignature/fa_IR.mo'
				),
				'gravityformssurvey' => array(
					'languages/gravityformssurvey-fa_IR.mo' => 'gravityformssurvey/fa_IR.mo'
				),
				'gravityformsuserregistration'        => array(
					'languages/gravityformsuserregistration-fa_IR.mo'        => 'gravityformsuserregistration/fa_IR.mo'
				),
				'gravityformsauthorizenet'  => array(
					'languages/gravityformsauthorizenet-fa_IR.mo'  => 'gravityformsauthorizenet/fa_IR.mo'
				),
				'gravityformsaweber'        => array(
					'languages/gravityformsaweber-fa_IR.mo'        => 'gravityformsaweber/fa_IR.mo'
				),
				'gravityformscampaignmonitor'           => array(
					'languages/gravityformscampaignmonitor-fa_IR.mo'           => 'gravityformscampaignmonitor/fa_IR.mo'
				),
				'gravityformsfreshbooks'            => array(
					'languages/gravityformsfreshbooks-fa_IR.mo'            => 'gravityformsfreshbooks/fa_IR.mo'
				),
				'gravityformspaypal'        => array(
					'languages/gravityformspaypal-fa_IR.mo'        => 'gravityformspaypal/fa_IR.mo'
				),
				'gravityformspaypalpro'        => array(
					'languages/gravityformspaypalpro-fa_IR.mo'        => 'gravityformspaypalpro/fa_IR.mo'
				),
				'gravityformspaypalpaymentspro' => array(
					'languages/gravityformspaypalpaymentspro-fa_IR.mo' => 'gravityformspaypalpaymentspro/fa_IR.mo'
				),
				'gravityformstwilio' => array(
					'languages/gravityformstwilio-fa_IR.mo' => 'gravityformstwilio/fa_IR.mo'
				),
				'gravityformsstripe' => array(
					'languages/gravityformsstripe-fa_IR.mo' => 'gravityformsstripe/fa_IR.mo'
				),
				'gravityformszapier'        => array(
					'languages/gravityformszapier-fa_IR.mo'        => 'gravityformszapier/fa_IR.mo'
				)
			);
			if ( isset( $domains[$domain] ) ) {
				$paths = $domains[$domain];
				foreach ( $paths as $path => $file ) {
					if ( substr( $mo_file, -strlen( $path ) ) == $path ) {
						$new_file = dirname( $this->file ) . '/languages/' . $file;
						if ( is_readable( $new_file ) ) {
							$mo_file = $new_file;
						}
					}
				}
			}
		}
		return $mo_file;
	}

    public function GravityForms_Footer_Left_By_HANNANStd($text) {
		$text = sprintf(__("%sGravity Forms%s for WordPress is a full featured contact form plugin .", "Persian_Gravityforms_By_HANNANStd"), '<a href="http://gravityforms.ir" target="_blank">', "</a>");return $text;
	}
    public function Add_Private_Post_Status_By_HANNANStd($post_status_options) {
		$post_status_options['private'] = __("Private", "Persian_Gravityforms_By_HANNANStd");
		return $post_status_options;
	}	
    public function Update_Currency_By_HANNANStd($currencies) {
		$currencies['IRR'] = array("name" => __("Iranian Rial", "Persian_Gravityforms_By_HANNANStd"), "symbol_left" => '', "symbol_right" => " ریال ", "symbol_padding" =>  "", "thousand_separator" => ',', "decimal_separator" => '.', "decimals" => 0);
		$currencies['IRT'] = array("name" => __("Toman", "Persian_Gravityforms_By_HANNANStd"), "symbol_left" => '', "symbol_right" => " تومان ", "symbol_padding" => " ", "thousand_separator" => ',', "decimal_separator" => '.', "decimals" => 0);
		return $currencies;
	}
    public function Gform_IRAN_By_HANNANStd( $address_types ) {
		$address_types['persian'] = array(
			'label'       => __( 'IRAN', 'Persian_Gravityforms_By_HANNANStd' ),
			'country'     => __( 'IRAN', 'Persian_Gravityforms_By_HANNANStd' ),
			'zip_label'   => __( 'Postal Code', 'Persian_Gravityforms_By_HANNANStd' ),
			'state_label' => __( 'Province', 'Persian_Gravityforms_By_HANNANStd' ),
			'states'      => array( '',
                        __( 'Azarbaijan - East', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Azarbaijan - West', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Ardabil', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Isfahan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Alborz', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Ilam', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Bushehr', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Tehran', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Chahar Mahaal and Bakhtiari', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Khorasan - South', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Khorasan - Razavi', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Khorasan - North', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Khuzestan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Zanjan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Semnan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Sistan and Baluchistan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Fars', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Qazvin', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Qom', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Kurdistan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Kerman', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Kermanshah', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Kohgiluyeh and Boyer-Ahmad', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Golestan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Gilan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Lorestan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Mazandaran', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Markazi', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Hormozgān', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Hamadan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Yazd', 'Persian_Gravityforms_By_HANNANStd' )   
			)
		);
		return $address_types;
	}
	public function Add_Iran_Predefined_Choice_By_HANNANStd($choices){
			$choices[__( 'Provinces of Iran', 'Persian_Gravityforms_By_HANNANStd' )] = array(__( 'Azarbaijan - East', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Azarbaijan - West', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Ardabil', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Isfahan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Alborz', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Ilam', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Bushehr', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Tehran', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Chahar Mahaal and Bakhtiari', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Khorasan - South', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Khorasan - Razavi', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Khorasan - North', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Khuzestan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Zanjan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Semnan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Sistan and Baluchistan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Fars', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Qazvin', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Qom', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Kurdistan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Kerman', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Kermanshah', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Kohgiluyeh and Boyer-Ahmad', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Golestan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Gilan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Lorestan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Mazandaran', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Markazi', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Hormozgān', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Hamadan', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'Yazd', 'Persian_Gravityforms_By_HANNANStd' )
		);   return $choices;
	}
	public function Add_Merge_Tags_To_List_By_HANNANStd($form){ ?>
	<script type="text/javascript">
	gform.addFilter("gform_merge_tags", "add_merge_tags");
	function add_merge_tags(mergeTags, elementId, hideAllFields, excludeFieldTypes, isPrepop, option){
        mergeTags["custom"].tags.push({ tag: '{payment_gateway}', label: '<?php _e("Simple Payment Gateway", "Persian_Gravityforms_By_HANNANStd") ?>' });
		mergeTags["custom"].tags.push({ tag: '{payment_status}', label: '<?php _e("Simple Payment Status", "Persian_Gravityforms_By_HANNANStd") ?>' });
		mergeTags["custom"].tags.push({ tag: '{transaction_id}', label: '<?php _e("Simple Transaction ID", "Persian_Gravityforms_By_HANNANStd") ?>' });
        mergeTags["custom"].tags.push({ tag: '{payment_gateway_css}', label: '<?php _e("Styled Payment Gateway", "Persian_Gravityforms_By_HANNANStd") ?>' });
		mergeTags["custom"].tags.push({ tag: '{payment_status_css}', label: '<?php _e("Styled Payment Status", "Persian_Gravityforms_By_HANNANStd") ?>' });
		mergeTags["custom"].tags.push({ tag: '{transaction_id_css}', label: '<?php _e("Styled Transaction ID", "Persian_Gravityforms_By_HANNANStd") ?>' });
		mergeTags["custom"].tags.push({ tag: '{payment_pack}', label: '<?php _e("Styled Payment Pack", "Persian_Gravityforms_By_HANNANStd") ?>' });
		return mergeTags;}
	</script>
	<?php return $form; }
	public function GformReplaceMergeTags_Simple_By_HANNANStd($text, $form, $lead, $url_encode, $esc_html, $nl2br, $format){
	$price = $lead['payment_amount'];
	$gateway = gform_get_meta($lead['id'], 'payment_gateway');
	if ($lead['payment_status']=="Active" || $lead['payment_status']=="Paid")
	$payment_status = __("Paid", "Persian_Gravityforms_By_HANNANStd");
	if ($lead['payment_status']=="Failed")
	$payment_status = __("Failed", "Persian_Gravityforms_By_HANNANStd");
	if ($lead['payment_status']=="Cancelled")
	$payment_status = __("Cancelled", "Persian_Gravityforms_By_HANNANStd");
	$tags = array (
		'{payment_gateway}',
		'{transaction_id}',
		'{payment_status}',
	);
	if ( ( $price < 0 ) || !isset($price) || !isset($gateway) ) {
	$values = array (
		'',
		'',
		'',
	);} 
	else {
	$values = array (
        isset($gateway) ? $gateway : '',			
        isset($lead['transaction_id']) ? $lead['transaction_id'] : '',
		isset($lead['payment_status']) ? $payment_status : '',	
	);
	}
	$text = str_replace($tags, $values, $text);
	return $text;
	}
	public function GformReplaceMergeTags_CSS_By_HANNANStd($text, $form, $lead, $url_encode, $esc_html, $nl2br, $format){
	$price = $lead['payment_amount'];
    $gateway = gform_get_meta($lead['id'], 'payment_gateway');
	if ($lead['payment_status']=="Active" || $lead['payment_status']=="Paid")
	$payment_status = __("Paid", "Persian_Gravityforms_By_HANNANStd");
	if ($lead['payment_status']=="Failed")
	$payment_status = __("Failed", "Persian_Gravityforms_By_HANNANStd");
	if ($lead['payment_status']=="Cancelled")
	$payment_status = __("Cancelled", "Persian_Gravityforms_By_HANNANStd");
	$tags = array (
		'{payment_gateway_css}',
		'{transaction_id_css}',
		'{payment_status_css}',
	);
	if ( ( $price < 0 ) || !isset($price) || !isset($gateway)  ) {
	$values = array (
		'',
		'',
		'',
	);}
	else {
	$values = array (
	isset($gateway) ? '<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA" style="border:1px solid #e9e9e9!important;">
	<tr bgcolor="#EAF2FA">
	<td colspan="2" style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Payment Gateway', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font></td>
	</tr>
	<tr bgcolor="#FFFFFF">
	<td  style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">'.$gateway.'</font></td>
	</tr></table>' : '',
	isset($lead['transaction_id']) ? '<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA" style="border:1px solid #e9e9e9!important;">
	<tr bgcolor="#EAF2FA">
	<td colspan="2" style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">
	<strong>'.__( 'Transaction ID', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font></td>
	</tr>
	<tr bgcolor="#FFFFFF">
	<td  style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">'.$lead['transaction_id'].'</font></td></tr>
	</table>' : '',
	isset($lead['payment_status']) ? '<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA" style="border:1px solid #e9e9e9!important;"><tr bgcolor="#EAF2FA">
	<td colspan="2" style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Payment Status', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font></td>
	</tr>
	<tr bgcolor="#FFFFFF">
	<td  style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">'.$payment_status.'</font></td></tr>
	</table>' : '',
	);
	}
	$text = str_replace($tags, $values, $text);
	return $text;
	}
	function GformReplaceMergeTags_Pack_By_HANNANStd($text, $form, $lead, $url_encode, $esc_html, $nl2br, $format){
    $price = $lead['payment_amount'];
    $gateway = gform_get_meta($lead['id'], 'payment_gateway');
	if ($lead['payment_status']=="Active" || $lead['payment_status']=="Paid")
	$payment_status = __("Paid", "Persian_Gravityforms_By_HANNANStd");
	if ($lead['payment_status']=="Failed")
	$payment_status = __("Failed", "Persian_Gravityforms_By_HANNANStd");
	if ($lead['payment_status']=="Cancelled")
	$payment_status = __("Cancelled", "Persian_Gravityforms_By_HANNANStd");
	$tags = array (
		'{payment_pack}',
	); 
	if ( ( $price < 0 ) || !isset($price) || !isset($gateway) ) {
	$values = array (
		'',
	);}
	else {
	$values = array (
	(isset($lead['transaction_id']) && isset($gateway) && isset($lead['payment_status']) ) ? '<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA" style="border:1px solid #e9e9e9!important;">
	<tr>
	<td style="font-size:14px;font-weight:bold;background-color:#eee;border-bottom:1px solid #dfdfdf;padding:7px 7px" colspan="2">'.__( 'Payment Information', 'Persian_Gravityforms_By_HANNANStd' ).'</td>
	</tr>
	<tr bgcolor="#EAF2FA">
	<td colspan="2" style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Payment Gateway', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font></td>
	</tr>
	<tr bgcolor="#FFFFFF">
	<td  style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">'.$gateway.'</font></td>
	</tr>
	<tr bgcolor="#EAF2FA">
	<td colspan="2" style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Payment Status', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font></td>
	</tr>
	<tr bgcolor="#FFFFFF">
	<td  style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">'.$payment_status.'</font></td>
	</tr>
	<tr bgcolor="#EAF2FA">
	<td colspan="2" style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Transaction ID', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font></td>
	</tr>
	<tr bgcolor="#FFFFFF">
	<td  style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">'.$lead['transaction_id'].'</font></td></tr>
	</table>' : '',			
	);
	}
	$text = str_replace($tags, $values, $text);
	return $text;
	}
        public function Add_Styles_Print_By_HANNANStd($value, $form){	
        wp_register_style('print_entry', plugins_url ( '/assets/css/printer.css', __FILE__, true ) );
	return array('print_entry');
        }
        public function GravityForms_Admin_CSS_By_HANNANStd() {
        if(!class_exists('GFForms')){
        return;
        }
        $current_page = trim(strtolower(RGForms::get("page")));
        $page_prefix = explode("_", $current_page);
        if ($page_prefix[0]=="gf" || $_SERVER['REQUEST_URI'] == '/wp-admin/' || $_SERVER['REQUEST_URI'] == '/wp-admin' || $_SERVER['REQUEST_URI'] == '/wp-admin/index.php' || $_SERVER['REQUEST_URI'] == '/wp-admin/index.php/') {
        wp_enqueue_style('Persian_GravityForms', plugins_url ( '/assets/css/persiangravity.css', __FILE__, null, GFCommon::$version ) );
        wp_print_styles( 'gform_tooltip','Persian_GravityForms' );
        }
        }
        public function Persian_GravityForms_Dashboard_By_HANNANStd() {
		if ( !current_user_can('manage_options') ) 
		return;
		global $wp_meta_boxes;
		wp_add_dashboard_widget('persiangf_wd_hannanstd', __( 'Persian Gravity Forms Dashboard', 'Persian_Gravityforms_By_HANNANStd' ) , array( $this, 'Persian_GravityForms_Widget_By_HANNANStd'));
		}
	public static function Persian_GravityForms_Widget_By_HANNANStd() {
	global $_wp_admin_css_colors;
	$current_color = get_user_option( 'admin_color' );
	$colors = array();
	foreach ( $_wp_admin_css_colors as $color => $color_info ) {
	if ($color == $current_color){
	foreach ( $color_info->colors as $html_color ) {
	$colors[] = esc_attr( $html_color ); }
	}
	}
	if (get_bloginfo('version')>=3.8) {
	?>
        <style>
        #persiangf_wd_hannanstd h3{font-family:byekan !important;background:<?php echo $colors[1] ?> !important;color:#fff !important;}
	#persiangf_wd_hannanstd .handlediv{color:#fff !important;}
        #persiangf_wd_hannanstd .a1{font-family:byekan !important;} 
        #persiangf_wd_hannanstd .a2{font-family:byekan !important;font-size:12px !important;}
        </style>
        <?php
		}
	$rss = fetch_feed( "http://gravityforms.ir/rss" );
	if ( is_wp_error($rss) ) {
        if ( is_admin() || current_user_can('manage_options') ) {
        printf(__('<strong>RSS Error</strong>', 'Persian_Gravityforms_By_HANNANStd'));
        }
     return;
}
if ( !$rss->get_item_quantity() ) {
	 printf(__( 'Apparently, There are no updates to show!', 'Persian_Gravityforms_By_HANNANStd' ));
     $rss->__destruct();
     unset($rss);
     return;
}
echo "<ul>"; 
if ( !isset($items) )
     $items = 5;
	 $i=1;
     foreach ( $rss->get_items(0, $items) as $item ) {
          $publisher = '';
          $site_link = '';
          $link = '';
          $content = '';
          $date = '';
          $link = esc_url( strip_tags( $item->get_link() ) );
          $title = esc_html( $item->get_title() );
          $content = $item->get_content();
          $content = wp_html_excerpt($content, 250) . ' ...';
		  echo "<li>";
		  if ($i==1)
		  echo "<a class='rsswidget a1' href='$link'>$title</a><div class='rssSummary'>$content</div><hr/>";
		  else
		  echo "<a class='rsswidget a2' href='$link'>$title</a>";
		  echo "<li>";
		  $i++;
}
echo "</ul>";
$rss->__destruct();
unset($rss);
}
}
global $Persian_Gravityforms_By_HANNANStd_plugin;
$Persian_Gravityforms_By_HANNANStd_plugin = new GravityFormsPersian( __FILE__ );