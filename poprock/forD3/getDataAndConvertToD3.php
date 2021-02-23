<?php

require_once 'rockdb.php';

function artistsD3 () {

	$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
	
	$artistInfoAll = "SELECT a.artistID, a.artistName, b.pop, b.date 
		FROM artists a
			INNER JOIN popArtists b ON a.artistID = b.artistID
		ORDER BY a.artistName ASC";

	$artistInfoRecent = "SELECT a.artistID, a.artistName, b.pop, b.date 
		FROM artists a
			INNER JOIN popArtists b ON a.artistID = b.artistID
				WHERE b.date = (select max(b2.date)
								FROM popArtists b2)
		ORDER BY b.pop ASC";

	

	$getit = $connekt->query($artistInfoRecent);

	for ($row = mysqli_fetch_array($getit)) {
		$jsonData[] = mysqli_fetch_assoc($getit);
	}

	echo json_encode($jsonData);
}

artistsD3();

// A MYSQLI EXAMPLE
  // Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql    = "SELECT * FROM cpu";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $rows = array();
    while ($row = mysqli_fetch_array($result)) {

        $rows[] = $row;
    }

    echo json_encode($rows);
} else {
    echo "no results found";
}

mysqli_close($conn);

// FROM PSYCHO CODES

$host = "localhost";
$db = "psychocodes";
$user = "your username";  //enter your database username
$pass = "your password";  //enter your database password
$conn = new mysqli($host,$user,$pass,$db); 
$rows = array();

$sql = "SELECT * FROM data";
$result = $conn->query($sql) or die("cannot write");
while($row = $result->fetch_assoc()){
	$rows[] = $row;
}

echo "<pre>";
print json_encode(array('serverres'=>$rows));
echo "</pre>";

// A PDO EXAMPLE

$host    = 'localhost';
$db      = 'sanpham';
$user    = 'root';
$pass    = '';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        ];

$dbh = new PDO($dsn, $user, $pass, $opt);

$sql  = $dbh->query("SELECT * FROM cpu");
$rows = array();
while ($row = $sql->fetchall()) {
    $rows[] = $row;
}
echo json_encode($rows);

?>