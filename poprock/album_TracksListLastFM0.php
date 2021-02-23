<?php

$artistMBID = $_GET['artistMBID'];
$artistSpotID = $_GET['artistSpotID'];
$albumMBID = $_GET['albumMBID'];

require_once 'rockdb.php';
require_once 'page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
};
/*
$liveEvil_albumSpotID = '1Uq7JKrKGGYCkg6l79gkoa';
$crossPurposes_albumMBID = '5d2e8936-8c36-3ccd-8e8f-916e3b771d49';
$thirteen_SpotID = '46fDgOnY2RavytWwL88x5M';
$thirteen_MBID = '7dbf4b1f-d3e9-47bc-9194-d15b31017bd6';
*/
$albumArtMBFilePath = "https://www.roxorsoxor.com/poprock/cover-art/";

$getAlbumTracksAndAssocArt = "SELECT d.trackMBID, d.trackNameMB, d.trackNumber, d.albumNameMB, d.trackListeners, d.trackPlaycount, d.trackRatio AS trackRatio, max(d.dataDate) AS MaxDataDate, d.albumArtMBFilename
FROM (
    SELECT k.trackMBID, k.trackNameMB, k.trackNumber, h.albumNameMB, h.albumArtMBFilename, fm.dataDate, fm.trackListeners, fm.trackPlaycount, fm.trackRatio
        FROM (
            SELECT m.trackMBID, m.trackNameMB, m.trackNumber, m.albumMBID
                FROM tracksMB m
                WHERE m.albumMBID = '$albumMBID'
        ) k
        INNER JOIN albumsMB h
            ON h.albumMBID = k.albumMBID
        JOIN tracksLastFM fm
            ON fm.trackMBID = k.trackMBID
) d
GROUP BY d.trackMBID
ORDER BY d.trackNumber ASC";

$getit2 = $connekt->query( $getAlbumTracksAndAssocArt );

if ( !$getit2 ) {
    echo '<p>Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Last FM Stats for This Album's Tracks</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>

<div class="container-fluid">
	
	<div id="fluidCon"></div> <!-- end of fluidCon -->

		<!-- main -->
    
    <a role="button" class="btn btn-warning btn-sm" href="forms/edit_AlbumMB.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>&albumMBID=<?php echo $albumMBID ?>&source=musicbrainz">Edit this Album</a>
    
    <a role="button" class="btn btn-success btn-sm" href="forms/add_albumAssocArtist.php?albumMBID=<?php echo $albumMBID ?>">Add Associated Artist</a>	

<!-- START OF ROW #1 COVER ART AND STATS SUMMARY -->
<div class="panel panel-primary">

    <div class="panel-heading">
        <h3 class="panel-title" id="albumPanelTitle">Current Stats for </h3>
    </div> <!-- close panel-heading -->
        
  <div class="panel-body">

       <div class="row">
<!-- Start of Column 1 -->
            <div class="col-md-2 popStyle">
                <img id="forArt">
            </div> <!-- End of Column 1 -->
<!-- Start of Column 2 -->
            <div class="col-md-3">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Popularity on Spotify
                        <span class="badge badge-primary badge-pill" id="forCurrentPopularity"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Listeners on LastFM
                        <span class="badge badge-primary badge-pill" id="forCurrentListeners">No data yet</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Playcount on LastFM
                        <span class="badge badge-primary badge-pill" id="forCurrentPlaycount">No data yet</span>
                    </li>
                </ul>
            </div> <!-- End of Column 2 -->

            <div class="col-md-7"></div>

        </div> <!-- End of row -->
        
  </div> <!-- End of Card Body -->
</div> <!-- End of Card -->
	
<!-- END OF ROW #1 COVER ART AND STATS SUMMARY -->
		
		
<!-- START OF ROW #2 WITH TRACK LIST -->		
		
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 id="tracksPanelTitle" class="panel-title">LastFM stats for tracks</h3>
	</div>
	<div class="panel-body">

		<?php if(!empty($getit2)) { ?>
				
<table class="table table-striped table-hover" id="tableotracks">
<thead>
<tr>
<th class="popStyle" onClick="sortColumn('trackNumber', 'ASC', '<?php echo $albumMBID ?>', 'musicbrainz')"><div class="pointyHead">Track #</div></th>
<th onClick="sortColumn('trackNameMB', 'DESC', '<?php echo $albumMBID ?>', 'musicbrainz')"><div class="pointyHead">Track Title</div></th>
<th class="popStyle" >LastFM<br>Data Date</th>
<th class="rightNum pointyHead" onClick="sortColumn('trackListeners', 'DESC', '<?php echo $albumMBID ?>', 'musicbrainz')">LastFM<br>Listeners</th>
<th class="rightNum pointyHead" onClick="sortColumn('trackPlaycount', 'DESC', '<?php echo $albumMBID ?>', 'musicbrainz')">LastFM<br>Playcount</th>
<th onClick="sortColumn('trackRatio', 'unsorted')"><div class="pointyHead popStyle">LastFM<br>Ratio</div></th>
</tr>
</thead>
	
	<tbody>
<?php
while ( $row = mysqli_fetch_array( $getit2 ) ) {
    $albumNameMB = $row[ "albumNameMB" ];
    $trackNameMB = $row[ "trackNameMB" ];
    $trackNumber = $row[ "trackNumber" ];
    $trackMBID = $row[ "trackMBID" ];
    $lastFMDate = $row[ "MaxDataDate" ];
    $trackListenersNum = $row[ "trackListeners"];
    $trackPlaycountNum = $row[ "trackPlaycount"];
    $trackListeners = number_format ($trackListenersNum);
    
    $albumArtMBFilename = $row[ "albumArtMBFilename" ];
    $coverArt = $albumArtMBFilePath . $albumArtMBFilename;
    $trackPlaycount = number_format ($trackPlaycountNum);
    $ppl = $row["trackRatio"];

    if (!$trackPlaycount > 0) {
        $trackPlaycount = "n/a";
        $trackRatio = "n/a";
        $lastFMDate = "n/a";
    } else {
        $trackRatio = "1:" . $ppl;
    };
?>
<tr>
<td class="popStyle"><?php echo $trackNumber ?></td>
<td><a href='https://www.roxorsoxor.com/poprock/track_ChartsLastFM.php?trackMBID=<?php echo $trackMBID ?>'><?php echo $trackNameMB ?></a></td>
<td class="popStyle"><?php echo $lastFMDate ?></td>
<td class="rightNum"><?php echo $trackListeners ?></td>
<td class="rightNum"><?php echo $trackPlaycount ?></td>
<td class="popStyle"><?php echo $trackRatio ?></td>
</tr>
			<?php 
				} // end of while
			?>
	</tbody>
</table>
<?php 
	} // end of if
?>
	</div> <!-- panel body -->
</div> <!-- panel panel-primary -->
		
<!-- END OF ROW #2 WITH TRACK LIST -->		
		
</div> <!-- closing CONTAINER-FLUID -->
	
<?php echo $scriptsAndSuch; ?>
<!-- closing CONTAINER-FLUID 
<script>
	const albumNameMB = '<?php //echo $albumNameMB ?>';
	const panelTitleText = 'LastFM stats for tracks from <em>' + albumNameMB + '</em> by ' + artistNameMB;
	const panelTitle = document.getElementById('panelTitle');
	$(document).ready(function(){
		panelTitle.innerHTML = panelTitleText;
	});
</script>
	-->
<script>

var dataset;

// b7d17108-0217-36e6-9110-b7f24ab6da8f
	
d3.json("functions/get_albumStats_LastFM.php?albumMBID=<?php echo $albumMBID; ?>", function(data) {
    
    console.log(data);
    
    var dataset = data;

    const artistNameMB = dataset[0].artistNameMB;
	const albumNameMB = dataset[0].albumNameMB;

    const nameInTitle = d3.select("title")
            .text("LastFM stats for " + albumNameMB + " by " + artistNameMB)
	
	const nameInAlbumPanelTitle = d3.select("#albumPanelTitle")
            .text("LastFM stats for " + albumNameMB + " by " + artistNameMB)

    const nameInAlbumTracksPanelTitle = d3.select("#tracksPanelTitle")
            .text("LastFM stats for tracks from " + albumNameMB + " by " + artistNameMB);   
	
    const dataAlbumListeners = dataset[0].albumListeners;
    let listeners = String(dataAlbumListeners).replace(/(.)(?=(\d{3})+$)/g,'$1,');	
    const currentAlbumListeners = d3.select("#forCurrentListeners")
            .text(listeners);               

    const dataPlaycount = dataset[0].albumPlaycount;
    let playcount = String(dataPlaycount).replace(/(.)(?=(\d{3})+$)/g,'$1,');
    const artistPlaycount = d3.select("#forCurrentPlaycount")
            .text(playcount);  

    const albumArtMBFilePath = "https://www.roxorsoxor.com/poprock/cover-art/";
    const albumArtMBFilename = dataset[0].albumArtMBFilename;
    const coverArt = albumArtMBFilePath + albumArtMBFilename;
    /**/
    d3.select("#forArt")
            .data(dataset)
            .attr("src", coverArt)
            .attr("height", 166);
            //.attr("width", auto)
});

</script>	
	
<script>
	const artistSpotID = '<?php echo $artistSpotID ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>
<script src="https://www.roxorsoxor.com/poprock/functions/sort_albumTracksLastFM.js"></script>
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>
	
</html>