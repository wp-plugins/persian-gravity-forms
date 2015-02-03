<?php
/*
Plugin Name: Persian Gravity Forms
Plugin URI: https://wordpress.org/plugins/persian-gravity-forms/
Description: Gravity Forms for Iranian 
Version: 1.3.2
Requires at least: 3.5
Author: HANNAN Ebrahimi Setoode
Author URI: http://www.gravityforms.ir/
Text Domain: Persian_Gravityforms_By_HANNANStd
Domain Path: /languages/
License: GPL 2
*/
require_once("include/wp-session.php");
class GravityFormsPersian {
	private $file;
	private $language;
	private $is_persian;
	public function __construct( $file ) {
		$this->file = $file;
		//actions
		add_action('init', array( $this, 'init' ), 8 );		
		add_action('gform_post_status_options', array( $this, 'Add_Private_Post_Status_By_HANNANStd'));
		add_action('activated_plugin', array( $this, 'Activated_Plugin_By_HANNANStd' ) );
		add_action('gform_admin_pre_render', array( $this, 'Add_Merge_Tags_To_List_By_HANNANStd'));
		add_action('wp_dashboard_setup', array( $this, 'Persian_GravityForms_Dashboard_By_HANNANStd'));
        add_action('admin_print_scripts', array( $this, 'GravityForms_Admin_CSS_By_HANNANStd' ));
		add_action('gform_field_standard_settings', array( $this, 'Add_Jalali_Active_Standard_Settings'), 10, 2);
		add_action('gform_editor_js', array( $this, 'Editor_Script_By_HANNANStd'));
		add_action('gform_enqueue_scripts', array( $this, 'Add_Jalali_Front_End_On_Off_Switch_By_HANNANStd'), 10 , 2 );
		add_action('gform_field_input', array( $this,'Add_Melli_Cart_Field_Input_By_HANNANStd'), 10, 5);
		add_action('gform_field_css_class', array( $this, 'Add_Melli_Cart_Field_Class_By_HANNANStd'), 10, 3);
		add_action('gform_field_advanced_settings', array( $this, 'Add_Melli_Cart_Field_Setting_By_HANNANStd'), 10, 2);
		add_action('gform_entries_first_column', array($this ,'First_Column_Actions_By_HANNANStd'), 10, 5);	
		add_action('gform_entry_post_save', array($this ,'Update_Lead_No_Gateway_By_HANNANStd'), 10, 2);
		//filters
		add_filter('update_footer', array( $this, 'GravityForms_Footer_Left_By_HANNANStd'), 11); 
		add_filter('load_textdomain_mofile', array( $this, 'Load_Textdomain_Mo_File_By_HANNANStd'), 10, 2 );
		add_filter('gform_currencies', array( $this, 'Update_Currency_By_HANNANStd' ) );
		add_filter('gform_address_types', array( $this, 'Gform_IRAN_By_HANNANStd' ) );
		add_filter('gform_replace_merge_tags', array( $this, 'GformReplaceMergeTags_By_HANNANStd'), 10, 7);
        add_filter('gform_print_styles', array( $this, 'Add_Styles_Print_By_HANNANStd'), 10, 2);
        add_filter('gform_predefined_choices', array( $this, 'Add_Iran_States_Predefined_Choice_By_HANNANStd' ),1);	
        add_filter('gform_predefined_choices', array( $this, 'Add_Iran_Months_Predefined_Choice_By_HANNANStd' ),1);		
		add_filter('gform_tooltips', array( $this, 'Add_Encryption_tooltips_By_HANNANStd'));
		add_filter('gform_add_field_buttons', array( $this, 'Add_Melli_Cart_Field_By_HANNANStd'));
		add_filter('gform_field_type_title', array( $this,'Add_Melli_Cart_Field_Title_By_HANNANStd'));
		add_filter('gform_editor_js_set_default_values', array( $this, 'Add_Melli_Cart_Field_Label_By_HANNANStd'));
		add_filter('gform_field_content', array( $this, 'Add_Melli_Cart_Field_JavaScript_Checker_By_HANNANStd'), 10, 5);	
		add_filter('gform_field_validation', array( $this, 'Input_Valid_Checker_By_HANNANStd'), 10, 4);	
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
	public function init(){
        require_once("include/Jalali.php");
        require_once("include/Post_Content_Merge_Tags.php");
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
	protected static function get_base_path(){
        $folder = basename(dirname(__FILE__));
        return WP_PLUGIN_DIR . "/" . $folder;
    }
	public function Load_Textdomain_Mo_File_By_HANNANStd( $mo_file, $domain ) {
		if ( strpos( $mo_file, 'fa_IR.mo' ) !== false ) {		
			$domains = array(
				'gravityforms'                 => array(
					'languages/gravityforms-fa_IR.mo'                 => 'gravityforms1.8/fa_IR.mo'
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
				),
				'sticky-list'        => array(
					'languages/sticky-list-fa_IR.mo'        => 'gravityformsstickylist/fa_IR.mo'
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
	public function Add_Jalali_Active_Standard_Settings($position, $form_id){
		if($position == 25){
			?>
			<li class="Jalali_setting field_setting">
				<input type="checkbox" id="check_jalali" onclick="SetFieldProperty('check_jalali', jQuery(this).is(':checked') ? 1 : 0);"/> 
				<label class="inline gfield_value_label" for="field_admin_label">
				<?php _e( 'فعالسازی تاریخ شمسی', 'Persian_Gravityforms_By_HANNANStd' ); ?>
				<?php gform_tooltip("form_check_jalali") ?>
				</label>
			</li>
			<?php
		}
	}
	public function Editor_Script_By_HANNANStd(){
    ?>
		<script type='text/javascript'>
			fieldSettings["date"] += ", .Jalali_setting";
			fieldSettings["mellicart"] = ".conditional_logic_field_setting, .label_setting, .admin_label_setting, .size_setting, .rules_setting, .visibility_setting, .duplicate_setting, .default_value_setting, .description_setting, .css_class_setting, .mellicart_setting";
			jQuery(document).bind("gform_load_field_settings", function(event, field, form){
				jQuery("#check_jalali").attr("checked", field["check_jalali"] == true);
				jQuery("#field_mellicart").attr("checked", field["field_mellicart"] == true);
				jQuery("#field_mellicart_sp").attr("checked", field["field_mellicart_sp"] == true);
				jQuery("#field_mellicart_sp1").val(field["field_mellicart_sp1"]);
				jQuery("#field_mellicart_sp2").val(field["field_mellicart_sp2"]);
				jQuery("#field_mellicart_sp3").val(field["field_mellicart_sp3"]);
				jQuery("#field_mellicart_sp4").val(field["field_mellicart_sp4"]);
			});
		</script>
    <?php
	}
	public function Add_Encryption_tooltips_By_HANNANStd($tooltips){
		$tooltips["form_check_jalali"] = "<h6>فعالسازی تاریخ شمسی</h6>در صورتی که از چند فیلد تاریخ استفاده میکنید ، فعالسازی تاریخ شمسی یکی از فیلدها کفایت میکند .<br/>تذکر : با توجه به آزمایشی بودن این قسمت ممکن است تداخل توابع سبب ناسازگاری با برخی قالب ها شود.";
		$tooltips["form_field_mellicart"] = "<h6>نمایش لحظه ای شهر از روی کد ملی </h6>نمایش شهر و پیغام زیر فیلد کد ملی بعد از پر شدن فیلد . تذکر : در صورتی که این گزینه را فعال نمایید ،ممکن است فراخوانی شهر های ایران با توجه به زیاد بودن آنها سبب سنگین شدن صفحه گردد.";
		$tooltips["form_field_mellicart_sp"] = "<h6>جدا سازی ارقام</h6>در صورتی که این گزینه را فعال نمایید ، پس از پر شدن فیلد ،  <strong>در صورتی که کد ملی وارد شده صحیح تشخصی داده شود</strong> ؛ کد ملی به صورت زیر در خواهد آمد :<br/>xxx-xxxxxx-x";
		$tooltips["form_field_mellicart_header"] = "<h6>پیغام خطا</h6>در صورتی که کاربر فیلد کد ملی را به صورت صحیح وارد نکند ؛ پیغام خطا را مشاهده میکند که میتوانید این پیغام ها را مدیریت نمایید . در صورتی که مقادیر زیر را خالی بگذارید پیغام پیشفرض نمایش داده خواهد شد.";
		$tooltips["form_field_mellicart_sp1"] = "<h6>پیغام پیشفرض</h6>با توجه به اینکه کد ملی فقط باید به صورت عدد باشد ، در صورتی که کاراکتری غیر از عدد وارد شده باشد پیغام خطا نمایش داده خواهد شد .<br/>پیغام پیشفرض : کد ملی فقط باید به صورت عدد وارد شود . ";
		$tooltips["form_field_mellicart_sp2"] = "<h6>پیغام پیشفرض</h6>با توجه به اینکه کد ملی می بایست 10 رقمی باشد اگر تعداد رقم وارد شده ، اشتباه باشد پیغام خطا نمایش داده خواهد شد .<br>پیغام پیشفرض : کد ملی می بایست 10 رقمی باشد . تنها در صورتی مجاز به استفاده از کد های 8 یا 9 رقمی هستید که ارقام سمت چپ 0 باشند . ";
		$tooltips["form_field_mellicart_sp3"] = "<h6>پیغام پیشفرض</h6>در صورتی که از تب وِیژگی تیک گزینه بدون تکرار را زده باشید ؛ بعد از پر شدن فرم و زدن دکمه ارسال پیغامی مبتنی بر تکراری بودن کد ملی نمایش داده خواهد شد . <br/>پیغام پیشفرض : این کد ملی توسط فرد دیگری ثبت شده است .";
		$tooltips["form_field_mellicart_sp4"] = "<h6>پیغام پیشفرض</h6>در صورتی که کد ملی وارد شده مطابق با الگوریتم کشور نباشد پیغام خطا نمایش داده خواهد شد .<br/>پیغام پیشفرض : کد ملی وارد شده مطابق با استانداردهای کشور نمی باشد .";	
		return $tooltips;
	}
	function Add_Jalali_Front_End_On_Off_Switch_By_HANNANStd( $form, $ajax ) {
		foreach ( $form['fields'] as $field ) {
			if ( ( $field['type'] == 'date' ) ) {
				if(rgget("check_jalali", $field)){
					add_filter('gform_date_min_year', array( $this, 'Set_Min_Year_By_HANNANStd' ) );
					add_filter('gform_date_max_year', array( $this, 'Set_Max_Year_By_HANNANStd' ) );
					if (!IS_ADMIN)
					{
						wp_deregister_script('gform_datepicker_init');
						wp_deregister_script('jquery-ui-datepicker');
						wp_register_script('gform_datepicker_init',plugins_url ( '/assets/js/date-picker.js', __FILE__), array( 'jquery', 'jquery-ui-core' ), GFCommon::$version, true );
					}
				}
			}
		}
	}
	public function Set_Min_Year_By_HANNANStd($min_year){
		$min_year = GF_gregorian_to_jalali($min_year,03,21);
		return $min_year[0]+1;
	}
	public function Set_Max_Year_By_HANNANStd($max_year){
		$max_year = GF_gregorian_to_jalali($max_year,03,21);
		return $max_year[0]+20;
	}
    public function GravityForms_Footer_Left_By_HANNANStd($text) {
		$text = sprintf(__("%sGravity Forms%s for WordPress is a full featured contact form plugin .", "Persian_Gravityforms_By_HANNANStd"), '<a href="http://gravityforms.ir" target="_blank">', "</a>");return $text;
	}
    public function Add_Private_Post_Status_By_HANNANStd($post_status_options) {
		$post_status_options['private'] = __("خصوصی", "Persian_Gravityforms_By_HANNANStd");
		return $post_status_options;
	}
    public function Update_Currency_By_HANNANStd($currencies) {
		$currencies['IRR'] = array("name" => __("ریال ایران", "Persian_Gravityforms_By_HANNANStd"), "symbol_left" => '', "symbol_right" => " ریال ", "symbol_padding" =>  "", "thousand_separator" => ',', "decimal_separator" => '.', "decimals" => 0);
		$currencies['IRT'] = array("name" => __("تومان", "Persian_Gravityforms_By_HANNANStd"), "symbol_left" => '', "symbol_right" => " تومان ", "symbol_padding" => " ", "thousand_separator" => ',', "decimal_separator" => '.', "decimals" => 0);
		return $currencies;
	}
    public function Gform_IRAN_By_HANNANStd( $address_types ) {
		$address_types['persian'] = array(
			'label'       => __( 'ایران', 'Persian_Gravityforms_By_HANNANStd' ),
			'country'     => __( 'ایران', 'Persian_Gravityforms_By_HANNANStd' ),
			'zip_label'   => __( 'کد پستی', 'Persian_Gravityforms_By_HANNANStd' ),
			'state_label' => __( 'استان', 'Persian_Gravityforms_By_HANNANStd' ),
			'states'      => array( '',
            __( 'آذربایجان شرقی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'آذربایجان غربی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'اردبیل', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'اصفهان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'البرز', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'ایلام', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'بوشهر', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'تهران', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'چهارمحال و بختیاری', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خراسان شمالی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خراسان رضوی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خراسان جنوبی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خوزستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'زنجان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'سمنان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'سیستان و بلوچستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'فارس', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'قزوين', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'قم', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'کردستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'کرمان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'کرمانشاه', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'کهگیلویه و بویراحمد', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'گلستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'گیلان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'لرستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'مازندران', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'مرکزی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'هرمزگان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'همدان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'یزد', 'Persian_Gravityforms_By_HANNANStd' )   
			)
		);
		return $address_types;
	}
	public function Add_Iran_States_Predefined_Choice_By_HANNANStd($choices){
			$choices[__( 'استانهای ایران', 'Persian_Gravityforms_By_HANNANStd' )] = array(
			__( 'آذربایجان شرقی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'آذربایجان غربی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'اردبیل', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'اصفهان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'البرز', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'ایلام', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'بوشهر', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'تهران', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'چهارمحال و بختیاری', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خراسان شمالی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خراسان رضوی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خراسان جنوبی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خوزستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'زنجان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'سمنان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'سیستان و بلوچستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'فارس', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'قزوين', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'قم', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'کردستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'کرمان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'کرمانشاه', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'کهگیلویه و بویراحمد', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'گلستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'گیلان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'لرستان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'مازندران', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'مرکزی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'هرمزگان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'همدان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'یزد', 'Persian_Gravityforms_By_HANNANStd' )   
		);   return $choices;
	}
	public function Add_Iran_Months_Predefined_Choice_By_HANNANStd($choices){
			$choices[__( 'ماه های ایران', 'Persian_Gravityforms_By_HANNANStd' )] = array(__( 'فروردین', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'اردیبهشت', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'خرداد', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'تیر', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'مرداد', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'شهریور', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'مهر', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'آبان', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'آذر', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'دی', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'بهمن', 'Persian_Gravityforms_By_HANNANStd' ),
			__( 'اسفند', 'Persian_Gravityforms_By_HANNANStd' )
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
				mergeTags["custom"].tags.push({ tag: '{rtl_start}', label: '<?php _e("RTL Start", "Persian_Gravityforms_By_HANNANStd") ?>' });
				mergeTags["custom"].tags.push({ tag: '{rtl_end}', label: '<?php _e("RTL End", "Persian_Gravityforms_By_HANNANStd") ?>' });
			return mergeTags;
			}
		</script>
	<?php 
		return $form; 
	}
	function First_Column_Actions_By_HANNANStd($form_id, $field_id, $value, $lead, $query_string) {
		$url = get_bloginfo("wpurl") . "/wp-admin/admin.php?page=gf_entries&view=entries&id=" . $form_id;
		$gateway = gform_get_meta($lead["id"], "payment_gateway");
		if($lead["payment_status"] == 'Active') {
			$color = '#008000';
			$stat = "موفق";
		}
		if($lead["payment_status"] == 'Paid') {
			$color = '#008000';
			$stat = "موفق";
		}
		if($lead["payment_status"] == 'Failed') {
			$color = '#FF0000';
			$stat = "ناموفق";
		}
		if($lead["payment_status"] == 'Cancelled') {
			$color = '#FFA500';
			$stat = "منصرف شده";
		}
		if($lead["payment_status"] == 'Processing') {
			$color = '#3399FF';
			$stat = "معلق";
		}	
		if ($gateway)
			echo '<a  class="stat" href="'.$url.'&sort=0&dir=DESC&s=Processing&field_id=payment_status&operator=is" style="color:'.$color.';"> '.$stat.' </a> - <a class="stat" href="'.$url.'&sort=0&dir=DESC&s='.$gateway.'&field_id=payment_gateway&operator=is" style="color:#000000;"> '.$gateway.' </a>';
		else if ($lead["payment_status"])
			echo '<a  class="stat" href="'.$url.'&sort=0&dir=DESC&s=Processing&field_id=payment_status&operator=is" style="color:'.$color.';"> موفق </a>';
	}
	public function Update_Lead_No_Gateway_By_HANNANStd($lead, $form) {
		$gateway = gform_get_meta($lead['id'], 'payment_gateway');
		$method = $lead['payment_method'];
		$product = self::get_product_price($form, $lead);
		if (!isset($method) && !$gateway && !isset($lead["transaction_id"]) ) {
			$lead["transaction_id"] = rand(100000000000000,999999999999999);
			$lead["is_fulfilled"] = 0;
		}
		if ( ($product["yes"]==2) && !isset($method) && !$gateway ) {
			$lead["payment_amount"] = $product["total"];
			$lead["payment_date"] = gmdate('Y-m-d H:i:s');
			$lead["is_fulfilled"] = 1;
			$lead["payment_status"] = 'Paid';
		}
		$wp_session = WP_Session::get_instance();
		wp_session_unset();
		$wp_session['refid'] = $form["id"].$lead["id"];
		@session_start();
		$_SESSION["refid"] = $form["id"].$lead["id"];
		RGFormsModel::update_lead($lead);
		return $lead;
	}
	public function GformReplaceMergeTags_By_HANNANStd($text, $form, $lead, $url_encode, $esc_html, $nl2br, $format){
	$gateway = gform_get_meta($lead['id'], 'payment_gateway');
	if ($lead['payment_status']=="Active" || $lead['payment_status']=="Paid")
		$payment_status = __("Paid", "Persian_Gravityforms_By_HANNANStd");
	if ($lead['payment_status']=="Failed")
		$payment_status = __("Failed", "Persian_Gravityforms_By_HANNANStd");
	if ($lead['payment_status']=="Cancelled")
		$payment_status = __("Cancelled", "Persian_Gravityforms_By_HANNANStd");
	$tags = array(
		'{payment_gateway}',
		'{transaction_id}',
		'{payment_status}',
		'{payment_gateway_css}',
		'{transaction_id_css}',
		'{payment_status_css}',
		'{payment_pack}',
		'{rtl_start}',
		'{rtl_end}',
	);
	$values = array (
        $gateway ? $gateway : '',			
        isset($lead['transaction_id']) ? $lead['transaction_id'] : '',
		isset($lead['payment_status']) ? $payment_status : '',	
		$gateway ? '
		<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA" style="border:1px solid #e9e9e9!important;">
			<tr bgcolor="#EAF2FA">
				<td colspan="2" style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Payment Gateway', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td  style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">'.$gateway.'</font>
				</td>
			</tr>
		</table>' : '',
		isset($lead['transaction_id']) ? '
		<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA" style="border:1px solid #e9e9e9!important;">
			<tr bgcolor="#EAF2FA">
				<td colspan="2" style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Transaction ID', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td  style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px">'.$lead['transaction_id'].'</font>
					</td>
				</tr>
		</table>' : '',
		isset($lead['payment_status']) ? '
		<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA" style="border:1px solid #e9e9e9!important;">
			<tr bgcolor="#EAF2FA">
				<td colspan="2" style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Payment Status', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td  style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px">'.$payment_status.'</font>
				</td>
			</tr>
		</table>' : '',
		(isset($lead['transaction_id']) && $gateway && isset($lead['payment_status']) ) ? '
		<table width="99%" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA" style="border:1px solid #e9e9e9!important;">
			<tr>
				<td style="font-size:14px;font-weight:bold;background-color:#eee;border-bottom:1px solid #dfdfdf;padding:7px 7px" colspan="2">
					'.__( 'Payment Information', 'Persian_Gravityforms_By_HANNANStd' ).'
				</td>
			</tr>
			<tr bgcolor="#EAF2FA">
				<td colspan="2" style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Payment Gateway', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td  style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px">'.$gateway.'</font>
				</td>
			</tr>
			<tr bgcolor="#EAF2FA">
				<td colspan="2" style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Payment Status', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td  style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px">'.$payment_status.'</font>
				</td>
			</tr>
			<tr bgcolor="#EAF2FA">
				<td colspan="2" style="padding:5px !important">
					<font style="font-family:sans-serif;font-size:12px"><strong>'.__( 'Transaction ID', 'Persian_Gravityforms_By_HANNANStd' ).'</strong></font>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td  style="padding:5px !important"><font style="font-family:sans-serif;font-size:12px">'.$lead['transaction_id'].'</font>
				</td>
			</tr>
		</table>' : '',	
		
		'<div style="text-align: right !important; direction: rtl !important;">',
		'</div>',
	);
	$text = str_replace($tags, $values, $text);
	return $text;
	}
    public function Add_Styles_Print_By_HANNANStd($value, $form){	
		if( is_rtl() ) {
			wp_register_style('print_entry', plugins_url ( '/assets/css/printer.css', __FILE__, true ) );
			return array('print_entry');
		}
    }
    public function GravityForms_Admin_CSS_By_HANNANStd() {
		if(!class_exists('GFForms')){
			return;
		}
		$current_page = trim(strtolower(RGForms::get("page")));
		$page_prefix = explode("_", $current_page);
		if (is_rtl() && ($page_prefix[0]=="gf" || $_SERVER['REQUEST_URI'] == '/wp-admin/' || $_SERVER['REQUEST_URI'] == '/wp-admin' || $_SERVER['REQUEST_URI'] == '/wp-admin/index.php' || $_SERVER['REQUEST_URI'] == '/wp-admin/index.php/')) {
			wp_enqueue_style('Persian_GravityForms', plugins_url ( '/assets/css/persiangravity.css', __FILE__, null, GFCommon::$version ) );
			wp_print_styles('gform_tooltip','Persian_GravityForms' );
			wp_dequeue_script('jquery-ui-datepicker');
			wp_enqueue_script(array("jquery-ui-datepicker"));
			wp_deregister_script('jquery-ui-datepicker');
			wp_deregister_script(array("jquery-ui-datepicker"));
			wp_deregister_script('gform_datepicker_init');
			wp_enqueue_script('gform_datepicker_init', plugins_url ( '/assets/js/wp-admin-datepicker.js', __FILE__), array( 'jquery', 'jquery-ui-core' ), true );
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
					$colors[] = esc_attr( $html_color );
				}
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
		$rss = fetch_feed( "http://gravityforms.ir/feed/" );
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
	public static function get_product_price($form, $entry){
		$currency = GFCommon::get_currency();
        $products = GFCommon::get_product_fields($form, $entry, true);
        $product_index = 1;
        $total = 0;
        $discount = 0;
        foreach($products["products"] as $product){
            $option_fields = "";
            $price = GFCommon::to_number($product["price"]);
            if(is_array(rgar($product,"options"))){
                $option_index = 1;
                foreach($product["options"] as $option){
                    $field_label = urlencode($option["field_label"]);
                    $option_name = urlencode($option["option_name"]);
                    $option_fields .= "&on{$option_index}_{$product_index}={$field_label}&os{$option_index}_{$product_index}={$option_name}";
                    $price += GFCommon::to_number($option["price"]);
                    $option_index++;	
                }
            }
            $name = urlencode($product["name"]);
            if($price > 0)
            {
                $total += $price * $product['quantity'];
                $product_index++;
            }
            else{
                $discount += abs($price) * $product['quantity'];
            }
        }
		if($price) $yes = 2;
					else $yes = 1;
        if(!empty($products["shipping"]["price"])) {
        $total += floatval($products["shipping"]["price"]);
		}
		if($discount > 0){
		    if($discount < $total) {
		    $total = $total-$discount;
			}
			else {
			$total = 0;
			}
		}
		else {
		$total = $total;
		}		
		return array("total" => $total, "yes" => $yes);
	}	 
	public static function get_mysql_tz_offset(){
		$tz = get_option('gmt_offset'); 
		if ( intval($tz) < 0) 
			$pf = "-";
		else 
			$pf = "+";
		$tz = abs($tz) * 3600;
		$tz = gmdate("H:i", $tz);
		$tz = $pf.$tz;
        $today = date('Y-m-d H:i:s');
		$date = new DateTime($today);
		$tzb = get_option('gmt_offset'); 
		$tzn = abs($tzb) * 3600;
		$tzh = intval(gmdate("H", $tzn));
		$tzm = intval(gmdate("i", $tzn));
		if ( intval($tzb) < 0) {
			$date->sub(new DateInterval('P0DT'.$tzh.'H'.$tzm.'M'));
		}
		else
		{
			$date->add(new DateInterval('P0DT'.$tzh.'H'.$tzm.'M'));}
			$today = $date->format('Y-m-d H:i:s');
			$today = strtotime ($today);
			return array("tz" => $tz, "today" => $today);
	}
	public static function get_base_url(){
		return plugins_url( '', __FILE__ );
	}
	public function version(){
		return '1.3.2';
	}
	public function Add_Melli_Cart_Field_By_HANNANStd( $field_groups ) {
		foreach( $field_groups as &$group ){
			if( $group["name"] == "advanced_fields" ){
				$group["fields"][] = array(
					"class"=>"button",
					"value" => "کد ملی",
					"onclick" => "StartAddField('mellicart');"
				);
			break;
			}
		}
		return $field_groups;
	}
	public function Add_Melli_Cart_Field_Title_By_HANNANStd($type) {
		if ($type == 'mellicart') {
			return 'کد ملی';
		}
	}
	public function Add_Melli_Cart_Field_Label_By_HANNANStd(){
		?>
		case "mellicart" :
		field.label = 'کد ملی';
		break;
		<?php
	}	
	public function Add_Melli_Cart_Field_Input_By_HANNANStd($input, $field, $value, $lead_id, $form_id ){
		if ( $field["type"] == "mellicart" ) {		
			$id = $field["id"];
			$field_id = IS_ADMIN || $form_id == 0 ? "input_$id" : "input_" . $form_id . "_$id";
			$form_id = IS_ADMIN && empty($form_id) ? rgget("id") : $form_id;
			$size = rgar($field, "size");
			$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "disabled='disabled'" : "";
			$class_suffix = RG_CURRENT_VIEW == "entry" ? "_admin" : "";
			$class = $size . $class_suffix;
			$currency = "";
			if(RG_CURRENT_VIEW == "entry")
			{
				$lead = RGFormsModel::get_lead($lead_id);
				$post_id = $lead["post_id"];
				$post_link = "";
				if(is_numeric($post_id) && GFCommon::is_post_field($field))
				{
					$post_link = "You can <a href='post.php?action=edit&post=$post_id'>edit this post</a> from the post page.";
				}
				$currency = $lead["currency"];
			}
			$max_length = "";
			$html5_attributes = "";
			if(empty($html_input_type))
				$html_input_type = "text";
			$max_length = "maxlength=10";
			$tabindex = GFCommon::get_tabindex();
			return sprintf("<div class='ginput_container'><input onblur='MelliCard_Checker_Javascript_By_HANNANStd_%d(this);' name='input_%d' id='%s' type='%s' value='%s' class='melli_cart %s' $max_length $tabindex $html5_attributes %s/></div><p class='city mellicart' id='city_%d'></p>", $id, $id, $field_id, $html_input_type, esc_attr($value), esc_attr($class), $disabled_text,	$id);
		}
	return $input;
	}
	public function Add_Melli_Cart_Field_Setting_By_HANNANStd( $position, $form_id ){
		if( $position == 50 ){
		?>
			<li class="mellicart_setting field_setting">
				<hr/>
				<input type="checkbox" id="field_mellicart" onclick="SetFieldProperty('field_mellicart', this.checked);" />
				<label for="field_mellicart" class="inline">
					<?php _e("نمایش زیر نویس فیلد", "Persian_Gravityforms_By_HANNANStd"); ?>
					<?php gform_tooltip("form_field_mellicart"); ?>
				</label>
				<br/>
				<input type="checkbox" id="field_mellicart_sp" onclick="SetFieldProperty('field_mellicart_sp', this.checked);" />
				<label for="field_mellicart_sp" class="inline">
					<?php _e("جدا سازی خودکار ارقام توسط خط فاصله", "Persian_Gravityforms_By_HANNANStd"); ?>
					<?php gform_tooltip("form_field_mellicart_sp"); ?>
				</label>
				<br/>
				<hr/>
				<label class="inline">
					متن پیغام خطا هنگامی که موارد زیر رخ دهد
					<?php gform_tooltip("form_field_mellicart_header"); ?>
				</label>
				<br/>
				<br/>
				<label for="field_mellicart_sp1" class="inline">
					<?php _e("مقدار وارد شده شامل کاراکتر غیر عددی باشد", "Persian_Gravityforms_By_HANNANStd"); ?>
					<?php gform_tooltip("form_field_mellicart_sp1"); ?>
				</label>
				<br/>
				<input type="text" id="field_mellicart_sp1" size="35" onkeyup="SetFieldProperty('field_mellicart_sp1', this.value);" />
				<br/>
				<label for="field_mellicart_sp2" class="inline">
					<?php _e("تعداد ارقام وارد شده استاندارد نباشد", "Persian_Gravityforms_By_HANNANStd"); ?>
					<?php gform_tooltip("form_field_mellicart_sp2"); ?>
				</label>
				<br/>
				<input type="text" id="field_mellicart_sp2" size="35" onkeyup="SetFieldProperty('field_mellicart_sp2', this.value);" />
				<br/>
				<label for="field_mellicart_sp3" class="inline">
					<?php _e("کد ملی وارد شده قبلا ثبت شده باشد", "Persian_Gravityforms_By_HANNANStd"); ?>
					<?php gform_tooltip("form_field_mellicart_sp3"); ?>
				</label>
				<br/>
				<input type="text" id="field_mellicart_sp3" size="35" onkeyup="SetFieldProperty('field_mellicart_sp3', this.value);" />
				<br/>
				<label for="field_mellicart_sp4" class="inline">
					<?php _e("کد ملی وارد شده مطابق با الگوی ملی نباشد", "Persian_Gravityforms_By_HANNANStd"); ?>
					<?php gform_tooltip("form_field_mellicart_sp4"); ?>
				</label>
				<br/>
				<input type="text" id="field_mellicart_sp4" size="35" onkeyup="SetFieldProperty('field_mellicart_sp4', this.value);" />
				<hr/>
			</li>
			<?php
		}
	}
	public function Add_Melli_Cart_Field_Class_By_HANNANStd($classes, $field, $form){
		if( $field["type"] == "mellicart" ){
			$classes .= " gform_mellicart";
		}
		return $classes;
	}
	public function Add_Melli_Cart_PHP_Checker_By_HANNANStd($meli_code='',$setting){	
		if (!empty($meli_code)) {
		if ($setting == 1) {
			$meli_code = str_replace('-','',$meli_code);
		}
		if($meli_code == '1111111111' ||
        $meli_code == '0000000000' ||
        $meli_code == '2222222222' ||
        $meli_code == '3333333333' ||
        $meli_code == '4444444444' ||
        $meli_code == '5555555555' ||
        $meli_code == '6666666666' ||
        $meli_code == '7777777777' ||
        $meli_code == '8888888888' ||
		$meli_code == '9999999999') 
		{
			return 2;
		}
		if(!is_numeric($meli_code)) 
			return 4;
		$meli_code = (string) preg_replace('/[^0-9]/','',$meli_code);
		if(strlen($meli_code)>10 or strlen($meli_code)<8)
			return 3;
		if(strlen($meli_code)==8)
			$meli_code = "00".$meli_code;
		if(strlen($meli_code)==9)
			$meli_code = "0".$meli_code;
		$list_code = str_split($meli_code);
		$last = (int) $list_code[9];
		unset($list_code[9]);
		$i = 10;
		$sum = 0;
		foreach($list_code as $key=>$_)
		{
			$sum += intval($_) * $i;$i--;
		}
		$mod =(int) $sum % 11;
		if($mod >= 2)
			$mod = 11 - $mod;
		if ($mod != $last)
			return 2;
		else
			return 1;
		}
		return;
	}
	public function Add_Melli_Cart_Field_JavaScript_Checker_By_HANNANStd($content, $field, $value, $lead_id, $form_id){
		if ( $field["type"] == "mellicart" && (rgget("field_mellicart", $field) || rgget("field_mellicart_sp", $field) ) && !IS_ADMIN ) 
		{
			$pm1= rgget("field_mellicart_sp1", $field);
			$pm2= rgget("field_mellicart_sp2", $field);
			$pm3= rgget("field_mellicart_sp4", $field);
		?>
		<script>
		function MelliCard_Checker_Javascript_By_HANNANStd_<?php echo $field["id"]; ?>(meli_code) {
			if (meli_code.value == '')
			{
				return false;
			}		
		<?php 
	if(rgget("field_mellicart_sp", $field)) { ?>	
		meli_code.value = meli_code.value.replace("-", "").replace("-", "");
		<?php 
	}
	if(rgget("field_mellicart", $field)) {?>
		if (isNaN(meli_code.value)) {												
		<?php 
		if ($pm1) 
		{ 
		?>
			var pm1 = "<?php echo $pm1 ?>";
		<?php 	
		} 
		else 
		{ ?>
			var pm1 = "کد ملی فقط باید به صورت عدد وارد شود . ";
		<?php 	
		} ?>
		document.getElementById("city_<?php echo $field["id"]; ?>").innerHTML = pm1;
		return false;
		}
		if (meli_code.value == '0000000000' ||
		meli_code.value == '1111111111' ||
		meli_code.value == '2222222222' ||
		meli_code.value == '3333333333' ||
		meli_code.value == '4444444444' ||
		meli_code.value == '5555555555' ||
		meli_code.value == '6666666666' ||
		meli_code.value == '7777777777' ||
		meli_code.value == '8888888888' ||
		meli_code.value == '9999999999') {
		<?php 
		if ($pm3) 
		{
		?>
			var pm3 = "<?php echo $pm3 ?>";
		<?php 
		} 
		else 
		{ 
		?>
			var pm3 = 'کد ملی وارد شده مطابق با استانداردهای کشور نمی باشد .';
		<?php 
		} 
		?>
		document.getElementById("city_<?php echo $field["id"]; ?>").innerHTML = pm3;
		return false;
		}
		if (meli_code.value.length > 10 || meli_code.value.length < 8)
		{
			<?php 
			if ($pm2)
			{ 
			?>
				var pm2 = "<?php echo $pm2 ?>";
			<?php 
			} 
			else 
			{ 
			?>
				var pm2 = 'کد ملی می بایست 10 رقمی باشد . تنها در صورتی مجاز به استفاده از کد های 8 یا 9 رقمی هستید که ارقام سمت چپ 0 باشند . ';
			<?php 
			} 
			?>
			document.getElementById("city_<?php echo $field["id"]; ?>").innerHTML = pm2;
			return false;
		}
		<?php 
		} 
		?>
		if (meli_code.value.length == 8) {
			meli_code.value = "00"+meli_code.value;
		}
		if (meli_code.value.length == 9) {
			meli_code.value = "0"+meli_code.value;
		}			
		city = meli_code.value.substring(0,3);
		c = parseInt(meli_code.value.charAt(9));
		n = parseInt(meli_code.value.charAt(0)) * 10 +
		parseInt(meli_code.value.charAt(1)) * 9 +
		parseInt(meli_code.value.charAt(2)) * 8 +
		parseInt(meli_code.value.charAt(3)) * 7 +
		parseInt(meli_code.value.charAt(4)) * 6 +
		parseInt(meli_code.value.charAt(5)) * 5 +
		parseInt(meli_code.value.charAt(6)) * 4 +
		parseInt(meli_code.value.charAt(7)) * 3 +
		parseInt(meli_code.value.charAt(8)) * 2;
		r = n - parseInt(n / 11) * 11;
		if ((r == 0 && r == c) || (r == 1 && c == 1) || (r > 1 && c == 11 - r)) {
		<?php 
		if(rgget("field_mellicart", $field)) 
		{
		?>
			if(city=="169")cityN="استان آذربايجان شرقي - شهر آذر شهر";if(city=="170")cityN="استان آذربايجان شرقي - شهر اسکو";if(city=="149"||city=="150")cityN="استان آذربايجان شرقي - شهر اهر";if(city=="171")cityN="استان آذربايجان شرقي - شهر بستان آباد";if(city=="168")cityN="استان آذربايجان شرقي - شهر بناب";if(city=="136"||city=="137"||city=="138")cityN="استان آذربايجان شرقي - شهر تبريز";if(city=="545")cityN="استان آذربايجان شرقي - شهر ترکمانچاي";if(city=="505")cityN="استان آذربايجان شرقي - شهر جلفا";if(city=="636")cityN="استان آذربايجان شرقي - شهر چاروايماق";if(city=="164"||city=="165")cityN="استان آذربايجان شرقي - شهر سراب";if(city=="172")cityN="استان آذربايجان شرقي - شهر شبستر";if(city=="623")cityN="استان آذربايجان شرقي - شهر صوفيان";if(city=="506")cityN="استان آذربايجان شرقي - شهر عجب شير";if(city=="519")cityN="استان آذربايجان شرقي - شهر کليبر";if(city=="154"||city=="155")cityN="استان آذربايجان شرقي - شهر مراغه";if(city=="567")cityN="استان آذربايجان شرقي - شهر ورزقان";if(city=="173")cityN="استان آذربايجان شرقي - شهر هريس";if(city=="159"||city=="160")cityN="استان آذربايجان شرقي - شهر هشترود";if(city=="604")cityN="استان آذربايجان شرقي - شهر هوراند";if(city=="274"||city=="275")cityN="استان آذربايجان غربي - شهر اروميه";if(city=="295")cityN="استان آذربايجان غربي - شهر اشنويه";if(city=="637")cityN="استان آذربايجان غربي - شهر انزل";if(city=="292")cityN="استان آذربايجان غربي - شهر بوکان";if(city=="492")cityN="استان آذربايجان غربي - شهر پلدشت";if(city=="289")cityN="استان آذربايجان غربي - شهر پيرانشهر";if(city=="677")cityN="استان آذربايجان غربي - شهر  تخت سليمان";if(city=="294")cityN="استان آذربايجان غربي - شهر تکاب";if(city=="493")cityN="استان آذربايجان غربي - شهر چايپاره";if(city=="279"||city=="280")cityN="استان آذربايجان غربي - شهر خوي";if(city=="288")cityN="استان آذربايجان غربي - شهر سردشت";if(city=="284"||city=="285")cityN="استان آذربايجان غربي - شهر سلماس";if(city=="638")cityN="استان آذربايجان غربي - شهر سيلوانه";if(city=="291")cityN="استان آذربايجان غربي - شهر سيه چشمه(چالدران)";if(city=="640")cityN="استان آذربايجان غربي - شهر شوط";if(city=="293")cityN="استان آذربايجان غربي - شهر  شاهين دژ";if(city=="675")cityN="استان آذربايجان غربي - شهر کشاورز";if(city=="282"||city=="283")cityN="استان آذربايجان غربي - شهر ماکو";if(city=="286"||city=="287")cityN="استان آذربايجان غربي - شهر مهاباد";if(city=="296"||city=="297")cityN="استان آذربايجان غربي - شهر مياندوآب";if(city=="290")cityN="استان آذربايجان غربي - شهر نقده";if(city=="400"||city=="401")cityN="استان همدان - شهر اسدآباد";if(city=="404"||city=="405")cityN="استان همدان - شهر بهار";if(city=="397")cityN="استان همدان - شهر تويسرکان";if(city=="398"||city=="399")cityN="استان همدان - شهر رزن";if(city=="647")cityN="استان همدان - شهر شراء و پيشخوار";if(city=="502")cityN="استان همدان - شهر فامنين";if(city=="584")cityN="استان همدان - شهر قلقل رود";if(city=="402"||city=="403")cityN="استان همدان - شهر کبودرآهنگ";if(city=="392"||city=="393")cityN="استان همدان - شهر ملاير";if(city=="395"||city=="396")cityN="استان همدان - شهر نهاوند";if(city=="386"||city=="387")cityN="استان همدان - شهر همدان";if(city=="503")cityN="استان يزد - شهر ابرکوه";if(city=="444")cityN="استان يزد - شهر اردکان";if(city=="551")cityN="استان يزد - شهر اشکذر";if(city=="447")cityN="استان يزد - شهر بافق";if(city=="561")cityN="استان يزد - شهر بهاباد";if(city=="445")cityN="استان يزد - شهر تفت";if(city=="718")cityN="استان يزد - شهر دستگردان";if(city=="083")cityN="استان يزد - شهر طبس";if(city=="446")cityN="استان يزد - شهر مهريز";if(city=="448")cityN="استان يزد - شهر ميبد";if(city=="552")cityN="استان يزد - شهر نير";if(city=="543")cityN="استان يزد - شهر هرات و مروست";if(city=="442"||city=="443")cityN="استان يزد - شهر يزد";if(city=="051")cityN="استان مرکزي - شهر آشتيان";if(city=="052"||city=="053")cityN="استان مرکزي - شهر اراک";if(city=="058")cityN="استان مرکزي - شهر تفرش";if(city=="055")cityN="استان مرکزي - شهر خمين";if(city=="617")cityN="استان مرکزي - شهر خنداب";if(city=="057")cityN="استان مرکزي - شهر دليجان";if(city=="618")cityN="استان مرکزي - شهر  زرند مرکزي";if(city=="059"||city=="060")cityN="استان مرکزي - شهر  ساوه";if(city=="061"||city=="062")cityN="استان مرکزي - شهر سربند";if(city=="544")cityN="استان مرکزي - شهر فراهان";if(city=="056")cityN="استان مرکزي - شهر محلات";if(city=="571")cityN="استان مرکزي - شهر وفس";if(city=="593")cityN="استان مرکزي - شهر هندودر";if(city=="667")cityN="استان هرمزگان - شهر ابوموسي";if(city=="348")cityN="استان هرمزگان - شهر بستک";if(city=="586")cityN="استان هرمزگان - شهر بشاگرد";if(city=="338"||city=="339")cityN="استان هرمزگان - شهر بندرعباس";if(city=="343"||city=="344")cityN="استان هرمزگان - شهر بندرلنگه";if(city=="346")cityN="استان هرمزگان - شهر جاسک";if(city=="337")cityN="استان هرمزگان - شهر  حاجي آباد";if(city=="554")cityN="استان هرمزگان - شهر خمير";if(city=="469")cityN="استان هرمزگان - شهر رودان";if(city=="537")cityN="استان هرمزگان - شهر فين";if(city=="345")cityN="استان هرمزگان - شهر قشم";if(city=="470")cityN="استان هرمزگان - شهر گاوبندي";if(city=="341"||city=="342")cityN="استان هرمزگان - شهر ميناب";if(city=="483"||city=="484")cityN="استان لرستان - شهر ازنا";if(city=="557")cityN="استان لرستان - شهر  اشترينان";if(city=="418")cityN="استان لرستان - شهر الشتر";if(city=="416"||city=="417")cityN="استان لرستان - شهر اليگودرز";if(city=="412"||city=="413")cityN="استان لرستان - شهر بروجرد";if(city=="592")cityN="استان لرستان - شهر پاپي";if(city=="612")cityN="استان لرستان - شهر چغلوندي";if(city=="613")cityN="استان لرستان - شهر چگني";if(city=="406"||city=="407")cityN="استان لرستان - شهر خرم آباد";if(city=="421")cityN="استان لرستان - شهر دورود";if(city=="598")cityN="استان لرستان - شهر  رومشکان";if(city=="419")cityN="استان لرستان - شهر کوهدشت";if(city=="385")cityN="استان لرستان - شهر  ملاوي(پلدختر)";if(city=="420")cityN="استان لرستان - شهر  نورآباد(دلفان)";if(city=="528")cityN="استان لرستان - شهر ويسيان";if(city=="213"||city=="214")cityN="استان مازندران - شهر آمل";if(city=="205"||city=="206")cityN="استان مازندران - شهر بابل";if(city=="498")cityN="استان مازندران - شهر بابل";if(city=="568")cityN="استان مازندران - شهر بندپي";if(city=="711")cityN="استان مازندران - شهر بندپي شرقي";if(city=="217"||city=="218")cityN="استان مازندران - شهر بهشهر";if(city=="221")cityN="استان مازندران - شهر تنکابن";if(city=="582")cityN="استان مازندران - شهر جويبار";if(city=="483")cityN="استان مازندران - شهر چالوس";if(city=="625")cityN="استان مازندران - شهر چمستان";if(city=="576")cityN="استان مازندران - شهر چهاردانگه";if(city=="578")cityN="استان مازندران - شهر دودانگه";if(city=="227")cityN="استان مازندران - شهر رامسر";if(city=="208"||city=="209")cityN="استان مازندران - شهر ساري";if(city=="225")cityN="استان مازندران - شهر سوادکوه";if(city=="577")cityN="استان مازندران - شهر شيرگاه";if(city=="712")cityN="استان مازندران - شهر  عباس آباد";if(city=="215"||city=="216")cityN="استان مازندران - شهر قائمشهر";if(city=="626")cityN="استان مازندران - شهر کجور";if(city=="627")cityN="استان مازندران - شهر کلاردشت";if(city=="579")cityN="استان مازندران - شهر گلوگاه";if(city=="713")cityN="استان مازندران - شهر مياندورود";if(city=="499")cityN="استان مازندران - شهر نکاء";if(city=="222")cityN="استان مازندران - شهر نور";if(city=="219"||city=="220")cityN="استان مازندران - شهر نوشهر";if(city=="500"||city=="501")cityN="استان مازندران - شهر  هراز و محمودآباد";if(city=="623")cityN="استان گلستان - شهر آزادشهر";if(city=="497")cityN="استان گلستان - شهر  آق قلا";if(city=="223")cityN="استان گلستان - شهر بندرترکمن";if(city=="689")cityN="استان گلستان - شهر بندرگز";if(city=="487")cityN="استان گلستان - شهر راميان";if(city=="226")cityN="استان گلستان - شهر  علي آباد";if(city=="224")cityN="استان گلستان - شهر کردکوي";if(city=="386")cityN="استان گلستان - شهر کلاله";if(city=="211"||city=="212")cityN="استان گلستان - شهر گرگان";if(city=="628")cityN="استان گلستان - شهر گميشان";if(city=="202"||city=="203")cityN="استان گلستان - شهر  گنبد کاووس";if(city=="531")cityN="استان گلستان - شهر  مراوه تپه";if(city=="288")cityN="استان گلستان - شهر مينودشت";if(city=="261")cityN="استان گيلان - شهر آستارا";if(city=="273")cityN="استان گيلان - شهر آستانه";if(city=="630")cityN="استان گيلان - شهر املش";if(city=="264")cityN="استان گيلان - شهر  بندرانزلي";if(city=="518")cityN="استان گيلان - شهر خمام";if(city=="631")cityN="استان گيلان - شهر  رحيم آباد";if(city=="258"||city=="259")cityN="استان گيلان - شهر رشت";if(city=="570")cityN="استان گيلان - شهر رضوانشهر";if(city=="265")cityN="استان گيلان - شهر رودبار";if(city=="268"||city=="269")cityN="استان گيلان - شهر رودسر";if(city=="653")cityN="استان گيلان - شهر سنگر";if(city=="517")cityN="استان گيلان - شهر سياهکل";if(city=="569")cityN="استان گيلان - شهر شفت";if(city=="267")cityN="استان گيلان - شهر  صومعه سرا";if(city=="262"||city=="263")cityN="استان گيلان - شهر طالش";if(city=="593")cityN="استان گيلان - شهر عمارلو";if(city=="266")cityN="استان گيلان - شهر فومن";if(city=="693")cityN="استان گيلان - شهر  کوچصفهان";if(city=="271"||city=="272")cityN="استان گيلان - شهر لاهيجان";if(city=="694")cityN="استان گيلان - شهر  لشت نشاء";if(city=="270")cityN="استان گيلان - شهر لنگرود";if(city=="516")cityN="استان گيلان - شهر  ماسال و شاندرمن";if(city=="333"||city=="334")cityN="استان کرمانشاه - شهر اسلام آباد";if(city=="691")cityN="استان کرمانشاه - شهر باينگان";if(city=="322"||city=="323")cityN="استان کرمانشاه - شهر پاوه";if(city=="595")cityN="استان کرمانشاه - شهر ثلاث باباجاني";if(city=="395")cityN="استان کرمانشاه - شهر جوانرود";if(city=="641")cityN="استان کرمانشاه - شهر حميل";if(city=="596")cityN="استان کرمانشاه - شهر روانسر";if(city=="336")cityN="استان کرمانشاه - شهر سرپل ذهاب";if(city=="335")cityN="استان کرمانشاه - شهر سنقر";if(city=="496")cityN="استان کرمانشاه - شهر صحنه";if(city=="337")cityN="استان کرمانشاه - شهر قصرشيرين";if(city=="324"||city=="325")cityN="استان کرمانشاه - شهر کرمانشاه";if(city=="394")cityN="استان کرمانشاه - شهر کرند";if(city=="330")cityN="استان کرمانشاه - شهر کنگاور";if(city=="332")cityN="استان کرمانشاه - شهر گيلانغرب";if(city=="331")cityN="استان کرمانشاه - شهر هرسين";if(city=="687")cityN="استان کهکيلويه و بويراحمد - شهر باشت";if(city=="422"||city=="423")cityN="استان کهکيلويه و بويراحمد - شهر  بويراحمد(ياسوج)";if(city=="599")cityN="استان کهکيلويه و بويراحمد - شهر بهمني";if(city=="600")cityN="استان کهکيلويه و بويراحمد - شهر چاروسا";if(city=="688")cityN="استان کهکيلويه و بويراحمد - شهر دروهان";if(city=="424"||city=="425")cityN="استان کهکيلويه و بويراحمد - شهر  کهکيلويه(دهدشت)";if(city=="426")cityN="استان کهکيلويه و بويراحمد - شهر  گچساران(دوگنبدان)";if(city=="550")cityN="استان کهکيلويه و بويراحمد - شهر لنده";if(city=="697")cityN="استان کهکيلويه و بويراحمد - شهر  مارگون";if(city=="384")cityN="استان کردستان - شهر بانه";if(city=="377"||city=="378")cityN="استان کردستان - شهر بيجار";if(city=="558")cityN="استان کردستان - شهر دهگلان";if(city=="385")cityN="استان کردستان - شهر ديواندره";if(city=="646")cityN="استان کردستان - شهر سروآباد";if(city=="375"||city=="376")cityN="استان کردستان - شهر سقز";if(city=="372"||city=="373")cityN="استان کردستان - شهر سنندج";if(city=="379"||city=="380")cityN="استان کردستان - شهر قروه";if(city=="383")cityN="استان کردستان - شهر کامياران";if(city=="674")cityN="استان کردستان - شهر کراني";if(city=="381"||city=="382")cityN="استان کردستان - شهر مريوان";if(city=="676")cityN="استان کردستان - شهر نمشير";if(city=="722")cityN="استان کرمان - شهر ارزونيه";if(city=="542")cityN="استان کرمان - شهر انار";if(city=="312"||city=="313")cityN="استان کرمان - شهر بافت";if(city=="317")cityN="استان کرمان - شهر بردسير";if(city=="310"||city=="311")cityN="استان کرمان - شهر بم";if(city=="302"||city=="303")cityN="استان کرمان - شهر جيرفت";if(city=="583")cityN="استان کرمان - شهر رابر";if(city=="321")cityN="استان کرمان - شهر راور";if(city=="382")cityN="استان کرمان - شهر راين";if(city=="304"||city=="305")cityN="استان کرمان - شهر رفسنجان";if(city=="536")cityN="استان کرمان - شهر  رودبار کهنوج";if(city=="605")cityN="استان کرمان - شهر ريگان";if(city=="308"||city=="309")cityN="استان کرمان - شهر زرند";if(city=="306"||city=="307")cityN="استان کرمان - شهر سيرجان";if(city=="319")cityN="استان کرمان - شهر شهداد";if(city=="313"||city=="314")cityN="استان کرمان - شهر شهربابک";if(city=="606")cityN="استان کرمان - شهر عنبرآباد";if(city=="320")cityN="استان کرمان - شهر فهرج";if(city=="698")cityN="استان کرمان - شهر قلعه گنج";if(city=="298"||city=="299")cityN="استان کرمان - شهر کرمان";if(city=="535")cityN="استان کرمان - شهر کوهبنان";if(city=="315"||city=="316")cityN="استان کرمان - شهر کهنوج";if(city=="318")cityN="استان کرمان - شهر گلباف";if(city=="607")cityN="استان کرمان - شهر ماهان";if(city=="608")cityN="استان کرمان - شهر منوجان";if(city=="508")cityN="استان قزوين - شهر آبيک";if(city=="538")cityN="استان قزوين - شهر آوج";if(city=="728")cityN="استان قزوين - شهر البرز";if(city=="509")cityN="استان قزوين - شهر بوئين زهرا";if(city=="438"||city=="439")cityN="استان قزوين - شهر تاکستان";if(city=="580")cityN="استان قزوين - شهر رودبار الموت";if(city=="590")cityN="استان قزوين - شهر رودبار شهرستان";if(city=="559")cityN="استان قزوين - شهر ضياءآباد";if(city=="588")cityN="استان قزوين - شهر طارم سفلي";if(city=="431"||city=="432")cityN="استان قزوين - شهر قزوين";if(city=="037"||city=="038")cityN="استان قم - شهر قم";if(city=="702")cityN="استان قم - شهر کهک";if(city=="240"||city=="241")cityN="استان فارس - شهر آباده";if(city=="670")cityN="استان فارس - شهر  آباده طشک";if(city=="648")cityN="استان فارس - شهر  ارسنجان";if(city=="252")cityN="استان فارس - شهر استهبان";if(city=="678")cityN="استان فارس - شهر  اشکنان";if(city=="253")cityN="استان فارس - شهر اقليد";if(city=="649")cityN="استان فارس - شهر اوز";if(city=="513")cityN="استان فارس - شهر بوانات";if(city=="546")cityN="استان فارس - شهر بيضا";if(city=="671")cityN="استان فارس - شهر جويم";if(city=="246"||city=="247")cityN="استان فارس - شهر جهرم";if(city=="654")cityN="استان فارس - شهر  حاجي آباد(زرين دشت)";if(city=="548")cityN="استان فارس - شهر خرامه";if(city=="547")cityN="استان فارس - شهر  خشت و کمارج";if(city=="655")cityN="استان فارس - شهر خفر";if(city=="248"||city=="249")cityN="استان فارس - شهر داراب";if(city=="253")cityN="استان فارس - شهر سپيدان";if(city=="514")cityN="استان فارس - شهر سروستان";if(city=="665")cityN="استان فارس - شهر  سعادت آباد";if(city=="673")cityN="استان فارس - شهر شيبکوه";if(city=="228"||city=="229"||city=="230")cityN="استان فارس - شهر شيراز";if(city=="679")cityN="استان فارس - شهر فراشبند";if(city=="256"||city=="257")cityN="استان فارس - شهر فسا";if(city=="244"||city=="245")cityN="استان فارس - شهر  فيروزآباد";if(city=="681")cityN="استان فارس - شهر  قنقري(خرم بيد)";if(city=="723")cityN="استان فارس - شهر قيروکارزين";if(city=="236"||city=="237")cityN="استان فارس - شهر کازرون";if(city=="683")cityN="استان فارس - شهر کوار";if(city=="656")cityN="استان فارس - شهر کراش";if(city=="250"||city=="251")cityN="استان فارس - شهر لارستان";if(city=="515")cityN="استان فارس - شهر لامرد";if(city=="242"||city=="243")cityN="استان فارس - شهر مرودشت";if(city=="238"||city=="239")cityN="استان فارس - شهر ممسني";if(city=="657")cityN="استان فارس - شهر مهر";if(city=="255")cityN="استان فارس - شهر ني ريز";if(city=="684")cityN="استان سمنان - شهر ايوانکي";if(city=="700")cityN="استان سمنان - شهر بسطام";if(city=="642")cityN="استان سمنان - شهر بيارجمند";if(city=="457")cityN="استان سمنان - شهر  دامغان";if(city=="456")cityN="استان سمنان - شهر سمنان";if(city=="458"||city=="459")cityN="استان سمنان - شهر شاهرود";if(city=="460")cityN="استان سمنان - شهر گرمسار";if(city=="530")cityN="استان سمنان - شهر مهديشهر";if(city=="520")cityN="استان سمنان - شهر ميامي";if(city=="358"||city=="359")cityN="استان  سيستان و بلوچستان - شهر ايرانشهر";if(city=="682")cityN="استان  سيستان و بلوچستان - شهر بزمان";if(city=="703")cityN="استان  سيستان و بلوچستان - شهر بمپور";if(city=="364"||city=="365")cityN="استان  سيستان و بلوچستان - شهر چابهار";if(city=="371")cityN="استان  سيستان و بلوچستان - شهر خاش";if(city=="701")cityN="استان  سيستان و بلوچستان - شهر دشتياري";if(city=="720")cityN="استان  سيستان و بلوچستان - شهر راسک";if(city=="366"||city=="367")cityN="استان  سيستان و بلوچستان - شهر زابل";if(city=="704")cityN="استان  سيستان و بلوچستان - شهر زابلي";if(city=="361"||city=="362")cityN="استان  سيستان و بلوچستان - شهر زاهدان";if(city=="369"||city=="370")cityN="استان  سيستان و بلوچستان - شهر سراوان";if(city=="635")cityN="استان  سيستان و بلوچستان - شهر سرباز";if(city=="668")cityN="استان  سيستان و بلوچستان - شهر  سيب و سوران";if(city=="533")cityN="استان  سيستان و بلوچستان - شهر  شهرکي و ناروئي(زهک)";if(city=="705")cityN="استان  سيستان و بلوچستان - شهر  شيب آب";if(city=="699")cityN="استان  سيستان و بلوچستان - شهر فنوج";if(city=="669")cityN="استان  سيستان و بلوچستان - شهر قصرقند";if(city=="725")cityN="استان  سيستان و بلوچستان - شهر کنارک";if(city=="597")cityN="استان  سيستان و بلوچستان - شهر  لاشار(اسپکه)";if(city=="611")cityN="استان  سيستان و بلوچستان - شهر ميرجاوه";if(city=="525")cityN="استان  سيستان و بلوچستان - شهر نيک شهر";if(city=="181")cityN="استان خوزستان - شهر آبادان";if(city=="527")cityN="استان خوزستان - شهر آغاجاري";if(city=="585")cityN="استان خوزستان - شهر اروندکنار";if(city=="685")cityN="استان خوزستان - شهر اميديه";if(city=="663")cityN="استان خوزستان - شهر انديکا";if(city=="192"||city=="193")cityN="استان خوزستان - شهر انديمشک";if(city=="174"||city=="175")cityN="استان خوزستان - شهر اهواز";if(city=="183"||city=="184")cityN="استان خوزستان - شهر ايذه";if(city=="481")cityN="استان خوزستان - شهر  باغ ملک";if(city=="706")cityN="استان خوزستان - شهر  بندر امام خميني";if(city=="194"||city=="195")cityN="استان خوزستان - شهر بندرماهشهر";if(city=="185"||city=="186")cityN="استان خوزستان - شهر بهبهان";if(city=="182")cityN="استان خوزستان - شهر خرمشهر";if(city=="199"||city=="200")cityN="استان خوزستان - شهر دزفول";if(city=="198")cityN="استان خوزستان - شهر  دشت آزادگان";if(city=="662")cityN="استان خوزستان - شهر  رامشير";if(city=="190"||city=="191")cityN="استان خوزستان - شهر رامهرمز";if(city=="692")cityN="استان خوزستان - شهر سردشت";if(city=="189")cityN="استان خوزستان - شهر شادگان";if(city=="707")cityN="استان خوزستان - شهر شاوور";if(city=="526")cityN="استان خوزستان - شهر شوش";if(city=="187"||city=="188")cityN="استان خوزستان - شهر شوشتر";if(city=="729")cityN="استان خوزستان - شهر گتوند";if(city=="730")cityN="استان خوزستان - شهر لالي";if(city=="196"||city=="197")cityN="استان خوزستان - شهر مسجدسليمان";if(city=="661")cityN="استان خوزستان - شهر هنديجان";if(city=="680")cityN="استان خوزستان - شهر هويزه";if(city=="643")cityN="استان خراسان رضوي - شهر  احمدآباد";if(city=="562")cityN="استان خراسان رضوي - شهر بجستان";if(city=="572")cityN="استان خراسان رضوي - شهر بردسکن";if(city=="074")cityN="استان خراسان رضوي - شهر تايباد";if(city=="644")cityN="استان خراسان رضوي - شهر  تخت جلگه";if(city=="072"||city=="073")cityN="استان خراسان رضوي - شهر تربت جام";if(city=="069"||city=="070")cityN="استان خراسان رضوي - شهر تربت حيدريه";if(city=="521")cityN="استان خراسان رضوي - شهر جغتاي";if(city=="573")cityN="استان خراسان رضوي - شهر جوين";if(city=="522")cityN="استان خراسان رضوي - شهر چناران";if(city=="724")cityN="استان خراسان رضوي - شهر  خليل آباد";if(city=="076")cityN="استان خراسان رضوي - شهر خواف";if(city=="077")cityN="استان خراسان رضوي - شهر درگز";if(city=="650")cityN="استان خراسان رضوي - شهر رشتخوار";if(city=="574")cityN="استان خراسان رضوي - شهر زبرخان";if(city=="078"||city=="079")cityN="استان خراسان رضوي - شهر سبزوار";if(city=="081")cityN="استان خراسان رضوي - شهر سرخس";if(city=="084")cityN="استان خراسان رضوي - شهر فريمان";if(city=="651")cityN="استان خراسان رضوي - شهر  فيض آباد";if(city=="086"||city=="087")cityN="استان خراسان رضوي - شهر قوچان";if(city=="089"||city=="090")cityN="استان خراسان رضوي - شهر کاشمر";if(city=="553")cityN="استان خراسان رضوي - شهر کلات";if(city=="091")cityN="استان خراسان رضوي - شهر گناباد";if(city=="092"||city=="093"||city=="094")cityN="استان خراسان رضوي - شهر مشهد";if(city=="097")cityN="استان خراسان رضوي - شهر  مشهد منطقه2";if(city=="098")cityN="استان خراسان رضوي - شهر  مشهد منطقه3";if(city=="096")cityN="استان خراسان رضوي - شهر  مشهد منطقه1";if(city=="105"||city=="106")cityN="استان خراسان رضوي - شهر نيشابور";if(city=="063")cityN="استان خراسان شمالي - شهر اسفراين";if(city=="067"||city=="068")cityN="استان خراسان شمالي - شهر  بجنورد";if(city=="075")cityN="استان خراسان شمالي - شهر جاجرم";if(city=="591")cityN="استان خراسان شمالي - شهر رازوجرکلان";if(city=="082")cityN="استان خراسان شمالي - شهر شيروان";if(city=="635")cityN="استان خراسان شمالي - شهر فاروج";if(city=="524")cityN="استان خراسان شمالي - شهر مانه و سملقان";if(city=="468")cityN="استان چهارمحال و بختياري - شهر اردل";if(city=="465")cityN="استان چهارمحال و بختياري - شهر بروجن";if(city=="461"||city=="462")cityN="استان چهارمحال و بختياري - شهر شهرکرد";if(city=="467")cityN="استان چهارمحال و بختياري - شهر فارسان";if(city=="555")cityN="استان چهارمحال و بختياري - شهر کوهرنگ";if(city=="633")cityN="استان چهارمحال و بختياري - شهر کيار";if(city=="629")cityN="استان چهارمحال و بختياري - شهر گندمان";if(city=="466")cityN="استان چهارمحال و بختياري - شهر لردگان";if(city=="696")cityN="استان چهارمحال و بختياري - شهر ميانکوه";if(city=="721")cityN="استان خراسان جنوبي - شهر  بشرويه";if(city=="064"||city=="065")cityN="استان خراسان جنوبي - شهر بيرجند";if(city=="523")cityN="استان خراسان جنوبي - شهر درميان";if(city=="652")cityN="استان خراسان جنوبي - شهر زيرکوه";if(city=="719")cityN="استان خراسان جنوبي - شهر سرايان";if(city=="716")cityN="استان خراسان جنوبي - شهر سربيشه";if(city=="085")cityN="استان خراسان جنوبي - شهر فردوس";if(city=="088")cityN="استان خراسان جنوبي - شهر قائنات";if(city=="563")cityN="استان خراسان جنوبي - شهر نهبندان";if(city=="529")cityN="استان بوشهر - شهر بندر ديلم";if(city=="353")cityN="استان بوشهر - شهر بندر گناوه";if(city=="349"||city=="350")cityN="استان بوشهر - شهر بوشهر";if(city=="355")cityN="استان بوشهر - شهر تنگستان";if(city=="609")cityN="استان بوشهر - شهر جم";if(city=="351"||city=="352")cityN="استان بوشهر - شهر  دشتستان";if(city=="354")cityN="استان بوشهر - شهر دشتي";if(city=="732")cityN="استان بوشهر - شهر دلوار";if(city=="357")cityN="استان بوشهر - شهر دير";if(city=="532")cityN="استان بوشهر - شهر  سعد آباد";if(city=="610")cityN="استان بوشهر - شهر شبانکاره";if(city=="356")cityN="استان بوشهر - شهر کنگان";if(city=="556")cityN="استان تهران - شهر اسلامشهر";if(city=="658")cityN="استان تهران - شهر پاکدشت";if(city=="001"||city=="002"||city=="003"||city=="004"||city=="005"||city=="006"||city=="007"||city=="008")cityN="استان تهران - شهر تهران مرکزي";if(city=="011")cityN="استان تهران - شهر تهران جنوب";if(city=="020")cityN="استان تهران - شهر تهران شرق";if(city=="025")cityN="استان تهران - شهر تهرانشمال";if(city=="015")cityN="استان تهران - شهر تهران غرب";if(city=="043")cityN="استان تهران - شهر دماوند";if(city=="666")cityN="استان تهران - شهر رباط کريم";if(city=="489")cityN="استان تهران - شهر ساوجبلاغ";if(city=="044"||city=="045")cityN="استان تهران - شهر شميران";if(city=="048"||city=="049")cityN="استان تهران - شهر شهرري";if(city=="490"||city=="491")cityN="استان تهران - شهر  شهريار";if(city=="695")cityN="استان تهران - شهر طالقان";if(city=="659")cityN="استان تهران - شهر فيروزکوه";if(city=="031"||city=="032")cityN="استان تهران - شهر کرج";if(city=="664")cityN="استان تهران - شهر کهريزک";if(city=="717")cityN="استان تهران - شهر نظرآباد";if(city=="041"||city=="042")cityN="استان تهران - شهر ورامين";if(city=="471"||city=="472")cityN=" امور خارجه -  امور خارجه";if(city=="454")cityN="استان ايلام - شهر آبدانان";if(city=="581")cityN="استان ايلام - شهر  ارکوازي(ملکشاهي)";if(city=="449"||city=="450")cityN="استان ايلام - شهر ايلام";if(city=="616")cityN="استان ايلام - شهر ايوان";if(city=="534")cityN="استان ايلام - شهر بدره";if(city=="455")cityN="استان ايلام - شهر  دره شهر";if(city=="451")cityN="استان ايلام - شهر دهلران";if(city=="726")cityN="استان ايلام - شهر زرين آباد";if(city=="634")cityN="استان ايلام - شهر شيروان لومار";if(city=="453")cityN="استان ايلام - شهر شيروان و چرداول";if(city=="727")cityN="استان ايلام - شهر موسيان";if(city=="452")cityN="استان ايلام - شهر مهران";if(city=="145"||city=="146")cityN="استان اردبيل - شهر اردبيل";if(city=="731")cityN="استان اردبيل - شهر ارشق";if(city=="690")cityN="استان اردبيل - شهر انگوت";if(city=="601")cityN="استان اردبيل - شهر بيله سوار";if(city=="504")cityN="استان اردبيل - شهر پارس آباد";if(city=="163")cityN="استان اردبيل - شهر خلخال";if(city=="714")cityN="استان اردبيل - شهر خورش رستم";if(city=="715")cityN="استان اردبيل - شهر سرعين";if(city=="566")cityN="استان اردبيل - شهر  سنجبد(کوثر)";if(city=="166"||city=="167")cityN="استان اردبيل - شهر مشکين شهر";if(city=="161"||city=="162")cityN="استان اردبيل - شهر مغان";if(city=="686")cityN="استان اردبيل - شهر نمين";if(city=="603")cityN="استان اردبيل - شهر نير";if(city=="619")cityN="استان اصفهان - شهر  آران و بيدگل";if(city=="118")cityN="استان اصفهان - شهر اردستان";if(city=="127"||city=="128"||city=="129")cityN="استان اصفهان - شهر اصفهان";if(city=="620")cityN="استان اصفهان - شهر باغ بهادران";if(city=="621")cityN="استان اصفهان - شهر بوئين و مياندشت";if(city=="549")cityN="استان اصفهان - شهر تيران و کرون";if(city=="564")cityN="استان اصفهان - شهر جرقويه";if(city=="575")cityN="استان اصفهان - شهر چادگان";if(city=="113"||city=="114")cityN="استان اصفهان - شهر  خميني شهر";if(city=="122")cityN="استان اصفهان - شهر خوانسار";if(city=="540")cityN="استان اصفهان - شهر خور و بيابانک";if(city=="660")cityN="استان اصفهان - شهر دولت آباد";if(city=="120")cityN="استان اصفهان - شهر سميرم";if(city=="512")cityN="استان اصفهان - شهر سميرم سفلي (دهاقان)";if(city=="510"||city=="511")cityN="استان اصفهان - شهر شاهين شهر";if(city=="119")cityN="استان اصفهان - شهر شهرضا";if(city=="115")cityN="استان اصفهان - شهر فريدن";if(city=="112")cityN="استان اصفهان - شهر فريدونشهر";if(city=="110"||city=="111")cityN="استان اصفهان - شهر فلاورجان";if(city=="125"||city=="126")cityN="استان اصفهان - شهر کاشان";if(city=="565")cityN="استان اصفهان - شهر  کوهپايه";if(city=="121")cityN="استان اصفهان - شهر گلپايگان";if(city=="116"||city=="117")cityN="استان اصفهان - شهر  لنجان(زرينشهر)";if(city=="541")cityN="استان اصفهان - شهر مبارکه";if(city=="622")cityN="استان اصفهان - شهر ميمه";if(city=="124")cityN="استان اصفهان - شهر نائين";if(city=="108"||city=="109")cityN="استان اصفهان - شهر  نجف آباد";if(city=="123")cityN="استان اصفهان - شهر نطنز";if(city=="427"||city=="428")cityN="استان زنجان - شهر زنجان";if(city=="507")cityN="استان آذربايجان شرقي - شهر ملکان";if(city=="158")cityN="استان آذربايجان شرقي - شهر مرند";if(city=="152"||city=="153")cityN="استان آذربايجان شرقي - شهر ميانه";if(city=="615")cityN="استان قزوين - شهر ابهر و خرمدره"
			document.getElementById("city_<?php echo $field["id"]; ?>").innerHTML = cityN;
		<?php 
		}  
		if(rgget("field_mellicart_sp", $field)) {
		?>
			meli_code.value = meli_code.value.substring(0,3)+"-"+meli_code.value.substring(3,9)+"-"+meli_code.value.substring(9,10);
		<?php 
		} 
		?>
		return true;
		} 
		<?php 
		if(rgget("field_mellicart", $field)) 
		{
		?>
		else 
		{
		<?php 
		if ($pm3) 
		{ 
		?>
		var pm3 = "<?php echo $pm3 ?>";
		<?php 
		} 
		else 
		{ 
		?>
			var pm3 = 'کد ملی وارد شده مطابق با استانداردهای کشور نمی باشد .';
		<?php 
		} 
		?>
			document.getElementById("city_<?php echo $field["id"]; ?>").innerHTML = pm3;
			return false;
		}
		<?php 
		} 
		?>
		}
		</script>
		<?php
		}
			return $content;
	}
	public static function checkdate($month, $day, $year){
        if(empty($month) || !is_numeric($month) || empty($day) || !is_numeric($day) || empty($year) || !is_numeric($year) || strlen($year) != 4)
            return false;
        return checkdate($month, $day, $year);
    }
	public function Input_Valid_Checker_By_HANNANStd($result, $value, $form, $field){	
		//shamsi date formtat validator
		if ( $field["type"] == "date" ) 
		{
			if (rgget("check_jalali", $field)) 
			{
				if(is_array($value) && rgempty(0, $value) && rgempty(1, $value)&& rgempty(2, $value))
					$value = null;

				if(!empty($value))
				{
					$format = empty($field["dateFormat"]) ? "mdy" : $field["dateFormat"];
					$date = GFCommon::parse_date($value, $format);
					if (!empty($date) )
					{
						if ( intval($date["month"]) >= 1 && intval($date["month"]) <=12 ) 
						{
							$min = 1;
							if ( intval($date["month"]) >= 1 && intval($date["month"]) <=6  )
								$max = 31;
							
							if ( intval($date["month"]) >= 7 && intval($date["month"]) <=12 ) 
								$max = 30;
							
							if ( intval($date["month"]) == 12 && intval($date["day"]) >= 1 && intval($date["day"]) <= 30 ) {
								$j_g = GF_jalali_to_gregorian($date["year"],$date["month"],$date["day"]);
								$day = $j_g[2];
								$month = $j_g[1];
								$year = $j_g[0];
								$target = new DateTime("$year-$month-$day 09:00:00");
								$target = $target->format('Y-m-d H:i:s');
								$target = strtotime ($target);
								$leap_year = GF_jdate('L',$target,'','','en');
								if ( $leap_year != 1 )
									$max = 29;
							}
						
							if ( intval($date["day"]) >= $min && intval($date["day"]) <= $max  ) {
								$j_g = GF_jalali_to_gregorian($date["year"],$date["month"],$date["day"]);
								$day = $j_g[2];
								$month = $j_g[1];
								$year = $j_g[0];
								$result["is_valid"] = self::checkdate($month, $day, $year);
							}
							else 
								$result["is_valid"] = false;
						}
						else 
							$result["is_valid"] = false;
					}
					else 
						$result["is_valid"] = false;
					
					if(empty($date) || !$result["is_valid"] )
					{
							$format_name = "";
							switch($format)
							{
								case "mdy" :
									$format_name = "mm/dd/yyyy";
								break;
								case "dmy" :
									$format_name = "dd/mm/yyyy";
								break;
								case "dmy_dash" :
									$format_name = "dd-mm-yyyy";
								break;
								case "dmy_dot" :
									$format_name = "dd.mm.yyyy";
								break;
								case "ymd_slash" :
									$format_name = "yyyy/mm/dd";
								break;
								case "ymd_dash" :
									$format_name = "yyyy-mm-dd";
								break;
								case "ymd_dot" :
									$format_name = "yyyy.mm.dd";
								break;
							}
						
						$result["is_valid"] = false;
						$message = $field["dateType"] == "datepicker" ? sprintf(__("Please enter a valid date in the format (%s).", "gravityforms"), $format_name) : __("Please enter a valid date.", "gravityforms");
						$result["message"] = empty($field["errorMessage"]) ? $message : $field["errorMessage"];
					}
					else
						$result["is_valid"] = true;
				}		
			}
		}
		//melli cart validator
		if ( $field["type"] == "mellicart" ) 
		{
			$pm1= rgget("field_mellicart_sp1", $field);
			$pm2= rgget("field_mellicart_sp2", $field);
			$pm3= rgget("field_mellicart_sp3", $field);
			$pm4= rgget("field_mellicart_sp4", $field);
			if(rgget("field_mellicart_sp", $field))
				$setting = 1;
			else
				$setting = 0;
			if (self::Add_Melli_Cart_PHP_Checker_By_HANNANStd($value,$setting) == 4) 
			{
				$result["is_valid"] = false;
				if ($pm1)
					$result["message"] = $pm1;
				else 
					$result["message"] = "کد ملی فقط باید به صورت عدد وارد شود . ";
			}		
			if (self::Add_Melli_Cart_PHP_Checker_By_HANNANStd($value,$setting) == 3)
			{
				$result["is_valid"] = false;
				if ($pm2)
					$result["message"] = $pm2;
				else 
					$result["message"] = 'کد ملی می بایست 10 رقمی باشد . تنها در صورتی مجاز به استفاده از کد های 8 یا 9 رقمی هستید که ارقام سمت چپ 0 باشند . ';
			}
			if (self::Add_Melli_Cart_PHP_Checker_By_HANNANStd($value,$setting) == 2) 
			{
				$result["is_valid"] = false;
				if ($pm4)
					$result["message"] = $pm4;
				else 
					$result["message"] = 'کد ملی وارد شده مطابق با استانداردهای کشور نمی باشد .';
			}
			if ($field["noDuplicates"] && RGFormsModel::is_duplicate($form["id"], $field, $value))
			{
				$result["is_valid"] = false;
				if ($pm3)
					$result["message"] = $pm3;
				else 
					$result["message"] = 'این کد ملی توسط فرد دیگری ثبت شده است .';
			}		
		}
		//else returne results
		return $result;
	}
}
global $Persian_Gravityforms_By_HANNANStd_plugin;
$Persian_Gravityforms_By_HANNANStd_plugin = new GravityFormsPersian( __FILE__ );
?>