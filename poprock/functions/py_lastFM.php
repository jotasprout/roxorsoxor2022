<?php

# LastFM variables
$LastFM_baseURL = 'http://ws.audioscrobbler.com/2.0/?method=';

# Part of URL for getting LastFM artist info
$LastFM_artistInfo = 'artist.getinfo&mbid=';

# Part of URL for getting LastFM album info
$LastFM_albumInfo = 'album.getinfo&mbid=';

$LastFM_albumMBID = '' # item in list of MusicBrainz_releaseMBID 

# Part of URL for getting LastFM track info
$LastFM_trackInfo = 'track.getinfo&mbid=';

$LastFM_trackMBID = ''; # item in list of MusicBrainz_recordingMBID 

# LastFM API key
$LastFM_apiKey = '&api_key=333a292213e03c10f38269019b5f3985';

# LastFM response format
$LastFM_jsonFormat = '&format=json';

# Get artist stats from LastFM
function makeGetArtistInfoFromLastFM_URL($LastFM_artistMBID){
    $get_artist_info_from_LastFM = $LastFM_baseURL . $LastFM_artistInfo . $LastFM_artistMBID . $LastFM_apiKey . $LastFM_jsonFormat;
    echo $get_artist_info_from_LastFM; 
};


function makeLastFM_albumCheckURL($LastFM_albumMBID){
    $LastFM_albumCheckURL = $LastFM_baseURL . $LastFM_albumInfo . $LastFM_albumMBID . $LastFM_apiKey . $LastFM_jsonFormat;
    echo $LastFM_albumCheckURL;    
};


function getLastFM_trackURL ($LastFM_trackMBID){
    $LastFM_trackURL = $LastFM_baseURL . $LastFM_trackInfo . $LastFM_trackMBID . $LastFM_apiKey . $LastFM_jsonFormat;
    echo $LastFM_trackURL;        
};


$test = 'http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&mbid=ee58c59f-8e7f-4430-b8ca-236c4d3745ae&api_key=333a292213e03c10f38269019b5f3985&format=json';  
        
?>        