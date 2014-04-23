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
| Config Settings
|--------------------------------------------------------------------------
*/

include_once('config.php');

$dbvalues = array(
    'new_site' => NEW_SITE_NAME,
    'old_site' => OLD_SITE_NAME
);

$db_prefix = DB_TABLE_PREFIX;

/*
|--------------------------------------------------------------------------
| DB Connect Function
|--------------------------------------------------------------------------
*/

function connect(){
    try {
        $conn = new PDO('mysql:host=localhost;dbname='.DATABASE_NAME, DATABASE_USER, DATABASE_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(Exception $e) {
        return false;
    }
}

//DB Query Function
function query($query, $bindings, $conn){
    $stmt = $conn->prepare($query);
    $stmt->execute($bindings);
    return;
    //$results = $stmt->fetchAll(PDO::FETCH_OBJ);
    //return $results ? $results : false;
}


/*
|--------------------------------------------------------------------------
| Connect To Database
|--------------------------------------------------------------------------
*/

$conn = connect();

echo "<h1>Connection</h1>";
print_r($conn);

/*
|--------------------------------------------------------------------------
| Run Queries
|--------------------------------------------------------------------------
*/

echo "<h1>Query</h1>";

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

var_dump($options);
var_dump($guid);
var_dump($post_content);
var_dump($post_meta);
var_dump($post_comments);
var_dump($post_pingbacks);
var_dump($delete_revisions);
var_dump($delete_comments);
var_dump($comment_settings_1);
var_dump($comment_settings_2);
var_dump($comment_settings_3);

?>