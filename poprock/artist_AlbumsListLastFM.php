<?php

$artistMBID = $_GET['artistMBID'];
$artistSpotID = $_GET['artistSpotID'];
$source = $_GET['source'];
require_once 'rockdb.php';
require_once 'page_pieces/stylesAndScripts.php';

$albumArtMBFilePath = "https://www.roxorsoxor.com/poprock/cover-art/";

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo '<p>Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
};

$blackScabies = "SELECT b.albumNameMB, b.albumMBID, b.yearReleased, z.artistNameMB, f1.dataDate, f1.albumListeners, f1.albumPlaycount, f1.albumRatio, b.albumArtMBFilename
					FROM (SELECT mb.albumNameMB, mb.albumMBID, mb.yearReleased, mb.artistMBID, mb.albumArtMBFilename
						FROM albumsMB mb 
						WHERE mb.artistMBID='$artistMBID') b 
					JOIN artistsMB z ON z.artistMBID = b.artistMBID
					#LEFT JOIN albumsMB x ON b.albumMBID = x.albumMBID
					LEFT JOIN (SELECT f.*
							FROM albumsLastFM f
							INNER JOIN (SELECT albumMBID, albumListeners, albumPlaycount, albumRatio, max(dataDate) AS MaxDataDate
							FROM albumsLastFM
							GROUP BY albumMBID) groupedf
							ON f.albumMBID = groupedf.albumMBID
							AND f.dataDate = groupedf.MaxDataDate) f1
					ON b.albumMBID = f1.albumMBID	
					ORDER BY f1.albumPlaycount DESC;";

$getit = $connekt->query($blackScabies);

if(!$getit){
	echo '<p>Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
}

?>

<!doctype html>
<html>
	
<head>
	<meta charset="UTF-8">
	<title>This Artist's Albums</title>
	<?php echo $stylesAndSuch; ?>
</head>
	
<body>

<div class="container-fluid">

<div id="fluidCon">
</div> <!-- end of fluidCon -->
<div class="panel panel-primary">

	<div class="panel-heading">
		<h3 id="panelTitle" class="panel-title">This Artist's Albums</h3>
	</div>
	<div class="panel-body"> 
		
		<!-- Panel Content --> 

		<?php if(!empty($getit)) { ?>
		
<table class="table table-striped table-hover" id="recordCollection">

<thead>
<tr>
	<th>Cover Art</th>
	<!--  -->
	<th onClick="sortColumn('albumNameMB', 'ASC', '<?php echo $artistMBID; ?>', '<?php echo $source ?>')"><div class="pointyHead">Album Name</div></th>
    <th class="popStyle">Released</div></th>
    <th class="popStyle">LastFM<br>Data Date</th>
	<!--  -->
    <th>Album MBID</th>	
	<th onClick="sortColumn('albumListeners', 'unsorted', '<?php echo $artistMBID; ?>', '<?php echo $source ?>')"><div class="pointyHead rightNum">LastFM<br>Listeners</div></th>
	<th onClick="sortColumn('albumPlaycount', 'unsorted', '<?php echo $artistMBID; ?>', '<?php echo $source ?>')"><div class="pointyHead rightNum">LastFM<br>Playcount</div></th>
	<th onClick="sortColumn('albumRatio', 'unsorted')"><div class="pointyHead popStyle">LastFM<br>Ratio</div></th>
</tr>
</thead>

<tbody>
					
<?php

	while ($row = mysqli_fetch_array($getit)) {
		$artistNameMB = $row['artistNameMB'];
		$albumMBID = $row['albumMBID'];
        $albumNameMB = $row['albumNameMB'];	
        $yearReleased = $row['yearReleased'];	
        $albumArtMBFilename = $row['albumArtMBFilename'];
        $coverArt = $albumArtMBFilePath . $albumArtMBFilename;
		$lastFMDate = $row[ "dataDate" ];
		$albumListenersNum = $row[ "albumListeners"];
		$albumListeners = number_format ($albumListenersNum);
		$albumPlaycountNum = $row[ "albumPlaycount"];
		$albumPlaycount = number_format ($albumPlaycountNum);
        /**/
        $ppl = $row["albumRatio"];
        //$albumRatio = "1:" . $ppl;
        
        if (!$albumPlaycount > 0) {
            $albumPlaycount = "n/a";
            $albumRatio = "n/a";
            $lastFMDate = "n/a";
        };
?>
					
<tr>
<td><img src='<?php echo $coverArt ?>' height='64' width='64'></td>
<td><a href='https://www.roxorsoxor.com/poprock/album_TracksListLastFM.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>&albumMBID=<?php echo $albumMBID ?>&source=musicbrainz'><?php echo $albumNameMB ?></a></td>
<td class="popStyle"><?php echo $yearReleased ?></td>
<td class="popStyle"><?php echo $lastFMDate ?></td>
<!--  -->
<td><?php echo $albumMBID ?></td>
<td class="rightNum"><?php echo $albumListeners ?></td>
<td class="rightNum"><?php echo $albumPlaycount ?></td>
<td class="popStyle">1:<?php echo $ppl ?></td>
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
    </div> <!-- closing container -->
	
<?php echo $scriptsAndSuch; ?>
<!-- -->
<script>
	const artistNameMB = '<?php echo $artistNameMB; ?>';
	const panelTitleText = 'Last.fm stats for all albums by ' + artistNameMB;
	const panelTitle = document.getElementById('panelTitle');
	const docTitleText = 'All ' + artistNameMB + ' albums Last.fm Stats';
	$(document).ready(function(){
		panelTitle.innerHTML = panelTitleText;
		document.title = docTitleText;
	});
</script>
<script>
	const artistSpotID = '<?php echo $artistSpotID ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>

<script src="https://www.roxorsoxor.com/poprock/functions/sort_artistAlbumsLastFM.js"></script>
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>
	
</html>