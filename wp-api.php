<?php 
/**
 * @wordpress-plugin
 * Plugin Name:       myapi
 */
function bulk(WP_REST_Request $request){
    $param = $request->get_body_params();
    $csv = array_map('str_getcsv', file($param['file']));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv);
    $str = null;

    global $wpdb;

    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "yoast_indexable WHERE breadcrumb_title='{$csv[0]['Page on Site']}'");
    $result = $wpdb->update(
        "{$wpdb->prefix}yoast_indexable",
        [
            'title' => $csv[0]['Meta_Title'],
            'description' => $csv[0]['Meta_Description']
        ],
        [
            'breadcrumb_title'=>$csv[0]['Page on Site']
        ]
        );
    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "yoast_indexable WHERE breadcrumb_title='{$csv[0]['Page on Site']}'");

    
    /* for($i= 0; $i < count($csv); $i++ ){
        $str.=$csv[$i]['Page on Site'];
    } */
    return $result;
}

add_action('rest_api_init', function(){
    register_rest_route( 'myRest/v1', 'seo', [
        'methods' => 'POST',
        'callback' => 'bulk'
    ]);

});



?>