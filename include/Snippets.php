<?php
/**
* Post Permalink Merge Tag
* http://GravityForms.ir
*/
class GFIR_PostPermalink {
    function __construct() {
        add_filter('gform_custom_merge_tags', array($this, 'add_custom_merge_tag'), 10, 4);
        add_filter('gform_replace_merge_tags', array($this, 'replace_merge_tag'), 10, 3);
    }
    function add_custom_merge_tag($merge_tags, $form_id, $fields, $element_id) {   
        if(!GFCommon::has_post_field($fields))
            return $merge_tags;
        $merge_tags[] = array('label' => 'لینک پست', 'tag' => '{post_permalink}');
        return $merge_tags;
    }
    function replace_merge_tag($text, $form, $entry) {   
        $custom_merge_tag = '{post_permalink}';
        if(strpos($text, $custom_merge_tag) === false || !rgar($entry, 'post_id'))
            return $text;
        $post_permalink = get_permalink(rgar($entry, 'post_id'));
        $text = str_replace($custom_merge_tag, $post_permalink, $text);
        return $text;
    }
}