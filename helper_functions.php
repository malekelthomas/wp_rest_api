<?php
function update($table_name, $title, $description, $breadcrumb_title){ //updates specified yoast seo table with title & description
    
    $result = $GLOBALS['wpdb']->update( //returns number of rows updated or false;
        "{$table_name}",
        [
            'title' => $title,
            'description' => $description
        ],
        [
            'breadcrumb_title'=>$breadcrumb_title //page that you want to update
        ]
        );

    return $result;
    
}

function check_header($header){

    

    $should_be = 
    [
        'Page on Site',
        'Focus Key Words ',
        'URL',
        'Meta_Title',
        '# of Characters',
        'Alt Titles (sub 60 characters)',
        'Meta_Description',
        'Complete'
    ];

    if(count($header) == 8){
        foreach($should_be as $val){
            if(!in_array($val,$header, true)){
                return $val;
            }
            else{
                return true;
            }
        }
    }
    else{
        return false;
    }

}

?>