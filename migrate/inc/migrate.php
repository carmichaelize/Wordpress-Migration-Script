<?php

/*
|--------------------------------------------------------------------------
| Display PHP Errors
|--------------------------------------------------------------------------
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| Validation Variables
|--------------------------------------------------------------------------
*/

$error = false;
$alert_template = "<div class='alert %s'>%s</div>";

/*
|--------------------------------------------------------------------------
| Process Config Settings
|--------------------------------------------------------------------------
*/

$db_name = $_POST['db_name'] ? $_POST['db_name'] : false ;
$db_user = $_POST['db_user'] ? $_POST['db_user'] : false ;
$db_password = $_POST['db_password'] ? $_POST['db_password'] : false ;

$dbvalues = array(
    'new_site' => $_POST['new_site_url'] ? $_POST['new_site_url'] : false,
    'old_site' => $_POST['old_site_url'] ? $_POST['old_site_url'] : false
);

$db_prefix = $_POST['db_prefix'] ? $_POST['db_prefix'] : false ;

$error = !$db_name || !$db_user || !$db_password || !$db_prefix || !$dbvalues['new_site'] || !$dbvalues['old_site'] ? true : false ;

if( !$error ){
    echo sprintf($alert_template, 'alert-success', '<strong>Input Success:</strong> All fields submitted OK.');
} else {
    echo sprintf($alert_template, 'alert-danger', '<strong>Input Failure:</strong> Please provide all fields and try again.');
}

/*
|--------------------------------------------------------------------------
| DB Connect Function
|--------------------------------------------------------------------------
*/

function connect(){
    global $db_name, $db_user, $db_password;
    try {
        //$conn = new PDO('mysql:host=localhost;dbname='.DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
        $conn = new PDO("mysql:host=localhost;dbname=".$db_name, $db_user, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(Exception $e) {
        return false;
    }
}

//DB Query Function
function query($query, $bindings, $conn){
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($bindings);
        return true;
        //$results = $stmt->fetchAll(PDO::FETCH_OBJ);
        //return $results ? $results : false;
    } catch(Exception $e) {
        return false;
    }
}


/*
|--------------------------------------------------------------------------
| Connect To Database
|--------------------------------------------------------------------------
*/

if( $conn = connect() ){
    echo sprintf($alert_template, 'alert-success', '<strong>Connection Successful:</strong> Database, user and password OK.');
} else {
    echo sprintf($alert_template, 'alert-danger', '<strong>Connection Failure:</strong> Check the values set in config.php are correct and try again.');
    $error = true;
}

/*
|--------------------------------------------------------------------------
| Run Queries
|--------------------------------------------------------------------------
*/

if( $conn && !$error ){

    //Update Absolute URLs
    $options      = query( "UPDATE {$db_prefix}_options SET option_value = replace(option_value, :old_site, :new_site) WHERE option_name = 'home' OR option_name = 'siteurl'", $dbvalues, $conn);
    $guid         = query( "UPDATE {$db_prefix}_posts SET guid = REPLACE (guid, :old_site, :new_site)", $dbvalues, $conn );
    $post_content = query( "UPDATE {$db_prefix}_posts SET post_content = REPLACE (post_content, :old_site, :new_site)", $dbvalues, $conn );
    $post_meta    = query( "UPDATE {$db_prefix}_postmeta SET meta_value = REPLACE (meta_value, :old_site, :new_site)", $dbvalues, $conn );

    //Turn Off Post Discussion Options
    $post_comments  = query( "UPDATE {$db_prefix}_posts SET comment_status='closed'", array(), $conn );
    $post_pingbacks = query( "UPDATE {$db_prefix}_posts SET ping_status='closed'", array(), $conn );

    //Delete Revisions
    $delete_revisions = query( "DELETE a,b,c FROM {$db_prefix}_posts a LEFT JOIN {$db_prefix}_term_relationships b ON (a.ID = b.object_id) LEFT JOIN {$db_prefix}_postmeta c ON (a.ID = c.post_id) WHERE a.post_type = 'revision'", array(), $conn );

    //Turn Off Global Discussion Options
    $delete_comments    = query( "DELETE FROM {$db_prefix}_comments", array(), $conn );
    $comment_settings_1 = query( "UPDATE {$db_prefix}_options SET option_value = '0' WHERE option_name = 'default_pingback_flag'", array(), $conn );
    $comment_settings_2 = query( "UPDATE {$db_prefix}_options SET option_value = 'closed' WHERE option_name = 'default_ping_status' OR option_name = 'default_comment_status'", array(), $conn );
    $comment_settings_3 = query( "UPDATE {$db_prefix}_options SET option_value = '1' WHERE option_name = 'close_comments_for_old_posts' OR option_name = 'comment_registration'", array(), $conn );

    if( $options && $guid && $post_content && $post_meta && $post_comments && $post_pingbacks && $delete_revisions && $delete_comments && $comment_settings_1 && $comment_settings_2 && $comment_settings_3 ){
        echo sprintf($alert_template, 'alert-success', '<strong>Migration Successful:</strong> DB updated OK, return to <a href="/">updated website</a>?');
    } else {
        echo sprintf($alert_template, 'alert-danger', '<strong>Migration Failure:</strong> Check the values set in config.php are correct and try again.');
        $error = true;
    }

}

?>