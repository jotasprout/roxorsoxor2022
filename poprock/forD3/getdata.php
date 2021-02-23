<?php
    $username = "******"; 
    $password = "******";   
    $host = "******";
    $database="***dbase_name***";

    $server = mysql_connect($host, $user, $password);
    $connection = mysql_select_db($database, $server);

    $myquery = "
    SELECT `dateTimeTaken`, `reading` FROM `tablename`
    ";

    $query = mysql_query($myquery);

    if ( ! $query ) {
        echo mysql_error();
        die;
    }

    $data = array();

    for ($x = 0; $x < mysql_num_rows($query); $x++) {
        $data[] = mysql_fetch_assoc($query);
    }

    echo json_encode($data);     

    mysql_close($server);
?>

// then elsewhere
// d3.json("getdata.php", function(error, data) {