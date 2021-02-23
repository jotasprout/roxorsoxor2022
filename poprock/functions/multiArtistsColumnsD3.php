<?php

require_once '../rockdb.php';
require( "class.artist.php" );

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect.';
};

// Rock en Espanol

$artistInfoRecentWithArt = "SELECT a.artistSpotID AS artistSpotID, a.artistArtSpot AS artistArtSpot, a.artistNameSpot AS artistNameSpot, p1.pop AS pop, p1.date AS date
    FROM artistsSpot a
    JOIN (SELECT p.*
			FROM popArtists p
			INNER JOIN (SELECT artistSpotID, pop, max(date) AS MaxDate
						FROM popArtists  
						GROUP BY artistSpotID) groupedp
			ON p.artistSpotID = groupedp.artistSpotID
			AND p.date = groupedp.MaxDate) p1
	ON a.artistSpotID = p1.artistSpotID
	WHERE a.artistSpotID IN ('2TI7qyDE0QfyOlnbtfDo7L', '43mhFhQ4JAknA7Ik1bOZuV', '39T6qqI0jDtSWWioX8eGJz', '5xUf6j4upBrXZPg6AI4MRK', '6XpaIBNiVzIetEPCWDvAFP', '6biWAmrHyiMkX49LkycGqQ', '0X380XXQSNBYuleKzav5UO', '5me0Irg2ANcsgc93uaYrpb', '1YLsqPcFg1rj7VvhfwnDWm', '762310PdDnwsDxAQxzQkfX', '0dmPX6ovclgOy8WWJaFEUU', '2tRsMl4eGxwoNabM08Dm4I', '4WquJweZPIK9qcfVFhTKvf', '1DFr97A9HnbV3SKTJFu62M', '0Lpr5wXzWLtDWm1SjNbpPb', '5xUf6j4upBrXZPg6AI4MRK', '3dBVyJ7JuOMt4GE9607Qin')    
    ORDER BY a.artistNameSpot ASC";
    

$result = mysqli_query($connekt, $artistInfoRecentWithArt);

if (mysqli_num_rows($result) > 0) {
	$rows = array();
	while ($row = mysqli_fetch_array($result)) {
		$rows[] = $row;
	}
	echo json_encode($rows);
}

else {
	echo "Nope. Nothing to see here.";
}

mysqli_close($connekt);

?>