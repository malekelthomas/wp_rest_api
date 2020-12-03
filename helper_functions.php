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
        'Alt Titles (≤ 60 characters)',
        'Meta_Description',
        'Complete'
    ];

    if(array_keys($header) == $should_be){
        return true;
    }
    else{
        return false;
    }

}

?>