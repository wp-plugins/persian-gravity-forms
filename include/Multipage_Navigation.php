<?php
class GFIR_MultipageNavigation {

    public $_args = array();

    private static $script_displayed;
    private static $non_global_forms = array();

    function __construct( $args = array() ) {

        // set our default arguments, parse against the provided arguments, and store for use throughout the class
        $this->_args = wp_parse_args( $args, array(
            'form_id' => false,
            'form_ids' => false,
            'activate_on_last_page' => true
        ) );

        if( $this->_args['form_ids'] ) {
            $form_ids = $this->_args['form_ids'];
        } else if( $this->_args['form_id'] ) {
            $form_ids = $this->_args['form_id'];
        } else {
            $form_ids = array();
        }

        $this->_args['form_ids'] = is_array( $form_ids ) ? $form_ids : array( $form_ids );

        if( ! empty( $this->_args['form_ids'] ) )
            self::$non_global_forms = array_merge( self::$non_global_forms, $this->_args['form_ids'] );

        add_filter( 'gform_pre_render', array( $this, 'output_navigation_script' ), 10, 2 );

    }

    function output_navigation_script( $form, $is_ajax ) {

        // only apply this to multi-page forms
        if( count($form['pagination']['pages']) <= 1 )
            return $form;

        if( ! $this->is_applicable_form( $form['id'] ) )
            return $form;

        $this->register_script( $form );

        if( ! $this->_args['activate_on_last_page'] || $this->is_last_page( $form ) || $this->is_last_page_reached() ) {
            $input = '<input id="gfir_last_page_reached" name="gfir_last_page_reached" value="1" type="hidden" />';
            add_filter( "gform_form_tag_{$form['id']}", create_function('$a', 'return $a . \'' . $input . '\';' ) );
        }

        // only output the gfirmpn object once regardless of how many forms are being displayed
        // also do not output again on ajax submissions
        if( self::$script_displayed || ( $is_ajax && rgpost('gform_submit') ))
            return $form;

        ?>

        <script type="text/javascript">

            (function($){

                window.gfirmpnObj = function( args ) {

                    this.formId = args.formId;
                    this.formElem = jQuery('form#gform_' + this.formId);
                    this.currentPage = args.currentPage;
                    this.lastPage = args.lastPage;
                    this.activateOnLastPage = args.activateOnLastPage;
                    this.labels = args.labels;

                    this.init = function() {

                        // if this form is ajax-enabled, we'll need to get the current page via JS
                        if( this.isAjax() )
                            this.currentPage = this.getCurrentPage();

                        if( !this.isLastPage() && !this.isLastPageReached() )
                            return;

                        var gfirmpn = this;
                        var steps = $('form#gform_' + this.formId + ' .gf_step');

                        steps.each(function(){

                            var stepNumber = parseInt( $(this).find('span.gf_step_number').text() );

                            if( stepNumber != gfirmpn.currentPage ) {
                                $(this).html( gfirmpn.createPageLink( stepNumber, $(this).html() ) )
                                    .addClass('gfir-step-linked');
                            } else {
                                $(this).addClass('gfir-step-current');
                            }

                        });

                        if( !this.isLastPage() && this.activateOnLastPage )
                            this.addBackToLastPageButton();

                        $(document).on('click', '#gform_' + this.formId + ' a.gfirmpn-page-link', function(event){
                            event.preventDefault();

                            var hrefArray = $(this).attr('href').split('#');
                            if( hrefArray.length >= 2 ) {
                                var pageNumber = hrefArray.pop();
                                gfirmpn.postToPage( pageNumber, ! $( this ).hasClass( 'gfirmp-default' ) );
                            }

                        });

                    }

                    this.createPageLink = function( stepNumber, HTML ) {
                        return '<a href="#' + stepNumber + '" class="gfirmpn-page-link gfirmpn-default">' + HTML + '</a>';
                    }

                    this.postToPage = function( page ) {
                        this.formElem.append('<input type="hidden" name="gfir_page_change" value="1" />');
                        this.formElem.find( 'input[name="gform_target_page_number_' + this.formId + '"]' ).val( page );
                        this.formElem.submit();
                    }

                    this.addBackToLastPageButton = function() {
                        this.formElem.find('#gform_page_' + this.formId + '_' + this.currentPage + ' .gform_page_footer')
                            .append('<input type="button" onclick="gfirmpn.postToPage(' + this.lastPage + ')" value="' + this.labels.lastPageButton + '" class="button gform_last_page_button">');
                    }

                    this.getCurrentPage = function() {
                        return this.formElem.find( 'input#gform_source_page_number_' + this.formId ).val();
                    }

                    this.isLastPage = function() {
                        return this.currentPage >= this.lastPage;
                    }

                    this.isLastPageReached = function() {
                        return this.formElem.find('input[name="gfir_last_page_reached"]').val() == true;
                    }

                    this.isAjax = function() {
                        return this.formElem.attr('target') == 'gform_ajax_frame_' + this.formId;
                    }

                    this.init();

                }

            })(jQuery);

        </script>

        <?php
        self::$script_displayed = true;
        return $form;
    }

    function register_script( $form ) {

        $page_number = GFFormDisplay::get_current_page($form['id']);
        $last_page = count($form['pagination']['pages']);

        $args = array(
            'formId' => $form['id'],
            'currentPage' => $page_number,
            'lastPage' => $last_page,
            'activateOnLastPage' => $this->_args['activate_on_last_page'],
            'labels' => array(
                'lastPageButton' => __( 'Back to Last Page' )
            )
        );

        $script = "window.gfirmpn = new gfirmpnObj(" . json_encode( $args ) . ");";
        GFFormDisplay::add_init_script( $form['id'], 'gfirmpn', GFFormDisplay::ON_PAGE_RENDER, $script );

    }

    function is_last_page( $form ) {

        $page_number = GFFormDisplay::get_current_page($form['id']);
        $last_page = count($form['pagination']['pages']);

        return $page_number >= $last_page;
    }

    function is_last_page_reached() {
        return rgpost('gfir_last_page_reached');
    }

    function is_applicable_form( $form_id ) {

        $is_global_form = ! in_array( $form_id, self::$non_global_forms );
        $is_current_non_global_form = ! $is_global_form && in_array( $form_id, $this->_args['form_ids'] );

        return $is_global_form || $is_current_non_global_form;
    }

}