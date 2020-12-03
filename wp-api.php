<?php 
/**
 * @wordpress-plugin
 * Plugin Name:       myapi
 */


include 'helper_functions.php';
function bulk_seo_update(WP_REST_Request $request){
    $param = $request->get_body_params();
    $csv = array_map('str_getcsv', file($param['file'])); //convert csv file to an array of arrays
    array_walk($csv, function(&$a) use ($csv) { //creates an associate array of arrays with header row as keys
      $a = array_combine($csv[0], $a);
    });

    $header = array_shift($csv); //removes header row from array and returns it
    
    $result=null;
    $num_updated=0;
    $error_msgs = [];
    $HEADER_ERROR = "Header Error";
    $UPDATE_ERROR = "Update Error";

    $header_check = check_header($header);
    if(!$header_check){
        array_push($error_msgs,["{$HEADER_ERROR}" => "Header Invalid"]);
    }
    else{
        for($i= 0; $i < count($csv); $i++){
            $result=update(
                "{$GLOBALS['wpdb']->prefix}yoast_indexable", 
                $csv[$i]['Meta_Title'], $csv[$i]['Meta_Description'],
                $csv[$i]['Page on Site']
            );
            if($result == false){
                $csv_row = $i+2;
                if(!array_key_exists("{$UPDATE_ERROR}", $error_msgs)){
                    $error_msgs["{$UPDATE_ERROR}"] = ["Update Failed at Row {$csv_row}, Check your csv file"];
                    break;
                }
            }
            else{
                $num_updated+=$result;
            }
        }
    }
    
    return [
        "Rows Updated" =>$num_updated,
        "Errors" => $error_msgs
    ];
}

add_action('rest_api_init', function(){
    register_rest_route( 'myRest/v1', 'seo', [
        'methods' => 'POST',
        'callback' => 'bulk_seo_update'
    ]);

});



?>