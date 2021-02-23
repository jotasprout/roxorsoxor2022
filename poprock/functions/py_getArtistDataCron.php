<?php

require 'py_lastFM.php';
require 'py_musicBrainz.php';
 
# ARTIST INFO
# Get artist info from MusicBrainz

echo "<p>Getting Artist info and RELEASE GROUPS from </p>";

$releaseGroupsList = array ();
$releaseGroupsJSON = '';

function get_artistReleaseGroups ($thisArtist) {
    # Get artist info (inc Release-Groups) from MusicBrainz
    $MusicBrainz_artistMBID = $thisArtist;

    $getReleaseGroups_totalURL = makeReleaseGroupsURL($MusicBrainz_artistMBID);

    #makeReleaseGroupsURL(MusicBrainz_artistMBID)

    $responseReleaseGroups = requests.get($getReleaseGroups_totalURL);
    
    # BELOW is from elsewhere that I might be able to use
    
    $jsonFile = $artistURL;
    $fileContents = file_get_contents($jsonFile);
    $artistData = json_decode($fileContents,true);

    $artistMBID = $artistData['mbid'];
    $artistNameMB = $artistData['name'];
    
    # ABOVE is from elsewhere that I might be able to use

    $releaseGroupsJSON = $responseReleaseGroups.json();
    
    echo $releaseGroupsJSON;

     # GATHER MBID FOR RELEASE GROUPS
    # Store MBID for each Release-Group in a list

    echo "Getting only the properties I want for each Release-Group";
    
    for releaseGroup in releaseGroupsJSON['release-groups']:
        aReleaseGroup = {}
        aReleaseGroup['mbid'] = releaseGroup['id']
        aReleaseGroup['title'] = releaseGroup['title']
        aReleaseGroup['releases'] = []
        releaseGroupsList = releaseGroupsList + [aReleaseGroup]

    echo ("I have a list of Release-Groups.")
    rg = len(releaseGroupsList)
    echo ("There are " + str(rg) + " Release-Groups in my list.")
            
    echo $aReleaseGroup;

};

function get_artistInfo ($releaseGroupsJSON) {
    # Get artist info (inc Release-Groups) from MusicBrainz

    # START BUILDING ARTIST DICTIONARY
    $artistName = $releaseGroupsJSON['name'];
    $artistType = $releaseGroupsJSON['type'];

    #create artist instance
    $artist = {}
    $artist['name'] = $artistName;
    $artist['type'] = $artistType;
    $artist['mbid'] = $MusicBrainz_artistMBID;

    # Artist birthday from MusicBrainz
    $artistBirthday = $releaseGroupsJSON['life-span']['begin'];
    $artist['birthday'] = $artistBirthday;

};

function get_artistLastFMStats ($MusicBrainz_artistMBID) {
    
    echo ("Getting Artist stats from LastFM");

    $LastFM_artistMBID = $MusicBrainz_artistMBID;

    $get_artist_info_from_LastFM = makeGetArtistInfoFromLastFM_URL($LastFM_artistMBID);

    $artist_info_from_LastFM = requests.get($get_artist_info_from_LastFM);

    $artistData = json.loads(artist_info_from_text);

    # Get Listeners and Playcount for Artist from LastFM
    $artist['stats'] = {}
    $LastFM_artistListeners = $artistData['artist']['stats']['listeners'];
    $LastFM_artistPlaycount = $artistData['artist']['stats']['playcount'];
    $artist['stats']['listeners'] = $LastFM_artistListeners;
    $artist['stats']['playcount'] = $LastFM_artistPlaycount;

    #These tags are from LastFM
    $genres = [];

    #def makeGenres():
    $tags = $artistData['artist']['tags']['tag'];
    foreach ($tags as $tag){
        $genre = $tag['name'];
        $genres = $genres + [genre];        
    };

    $artist['genres'] = $genres;

    echo "Stored artist genres using Tags from LastFM"; 
};


    # MAKE SURE ARTIST GETS GENRES FROM MusicBrainz AND TAGS FROM LastFM

function getReleases ($aReleaseGroup) {
    echo "Getting Releases from a Release-Group";
    # Get Releases of a Release-Group from MusicBrainz
    for release_group in releaseGroupsList:
        MusicBrainz_releasegroupMBID = release_group['mbid']
        MusicBrainz_releasegroupTitle = release_group['title']
        release_group['releases'] = []
        echo ("Getting releases for " + MusicBrainz_releasegroupTitle)
        MusicBrainz_releasegroupMBID = MusicBrainz_releasegroupMBID
        getReleases_totalURL = makeGetReleases_totalURL(MusicBrainz_releasegroupMBID)
        responseReleases = requests.get(getReleases_totalURL)
        releasesJSON = responseReleases.json()
        release_group_all_Releases = [];
    
    for release in releasesJSON['releases']:
        get_aRelease ();
        release_group_all_Releases = release_group_all_Releases + [aRelease]
            
    rr = len(release_group_all_Releases)
    echo (release_group['title'] + " has " + str(rr) + " total releases")
};

function get_aRelease (){
                aRelease = {}
            aRelease['mbid'] = release['id']
            aRelease['title'] = release['title']
            aRelease['date'] = str(release.get('date', ''))
            aRelease['country'] = str(release.get('country', ''))
            aRelease['disambiguation'] = release['disambiguation']
            aRelease['packaging'] = release['packaging']
};
 
validAlbumsForThisReleaseGroup = [];

function getValidAlbums ($release_group_all_Releases) {
        for release in release_group_all_Releases:
            LastFM_albumMBID = release['mbid']
            LastFM_albumTitle = release['title']
            LastFM_albumCountry = release['country']
            LastFM_albumDate = release['date']
            LastFM_albumCheckURL = makeLastFM_albumCheckURL(LastFM_albumMBID)
            responseCheck = requests.get(LastFM_albumCheckURL)
            albumData = json.loads(responseCheck.text)
            if "error" in albumData:
                echo (LastFM_albumTitle + " on " + LastFM_albumDate + " from " + LastFM_albumCountry + " does not exist in LastFM")
                echo (" ")
            else:
                thisAlbum = {}
                thisAlbum['name'] = albumData['album']['name']
                thisAlbum['mbid'] = albumData['album']['mbid']
                thisAlbum['listeners'] = albumData['album']['listeners']
                thisAlbum['playcount'] = albumData['album']['playcount']
                thisAlbum['date'] = release['date']
                thisAlbum['country'] = release['country']
                thisAlbum['disambiguation'] = release['disambiguation']
                thisAlbum['packaging'] = release['packaging']
                validAlbumsForThisReleaseGroup = validAlbumsForThisReleaseGroup + [thisAlbum]
                echo (thisAlbum['name'] + " on " + thisAlbum['date'] + " from " + thisAlbum['country'] + " exists in LastFM and stored in valid albums")
                echo (" ")
            echo (" ")

        echo (release_group['title'] + " has " + str(len(validAlbumsForThisReleaseGroup)) + " total VALID releases")

        release_group['releases'] = release_group['releases'] + validAlbumsForThisReleaseGroup    
};
        

        



        


        # For each release, get MBID for recordings on that release from MusicBrainz
        for validAlbum in release_group['releases']:
            validAlbum['artistName'] = artist['name']
            validAlbum['artistMBID'] = artist['mbid']
            validAlbum['tracks'] = []
            MusicBrainz_releaseMBID = validAlbum['mbid']
            MusicBrainz_releaseTitle = validAlbum['name']
            echo ("Getting " + MusicBrainz_releaseTitle + " tracks info from MusicBrainz")

            getRecordings_totalURL = makeGetRecordings_totalURL(MusicBrainz_releaseMBID)
            responseRecordings = requests.get(getRecordings_totalURL)
            recordingsFromRelease = json.loads(responseRecordings.text)
            for track in recordingsFromRelease['media'][0]['tracks']:
                aRecording = {}
                aRecording['mbid'] = track['recording']['id']
                LastFM_trackMBID = aRecording['mbid']
                aRecording['title'] = track['recording']['title']
                LastFM_trackTitle = aRecording['title']
                echo ("Getting " + LastFM_trackTitle + " track stats from LastFM")
                echo (" ")
                LastFM_trackURL = getLastFM_trackURL (LastFM_trackMBID)
                responseTrack = requests.get(LastFM_trackURL)
                trackData = json.loads(responseTrack.text)
                # Get Listeners and Playcount for each Track (using Recording MBID) on an Album from LastFM
                if "error" in trackData:
                    echo (LastFM_trackTitle + " does not exist in LastFM")

                else:
                    aRecording['stats'] = {}
                    aRecording['stats']['listeners'] = trackData['track']['listeners']
                    aRecording['stats']['playcount'] = trackData['track']['playcount']
                    trackName = aRecording['title']
                    aRecording['artistName'] = artist['name']
                    aRecording['artistMBID'] = artist['mbid']
                    trackListeners = aRecording['stats']['listeners']
                    trackPlaycount = aRecording['stats']['playcount']
                    echo(trackName + ' has ' + trackListeners + ' listeners and ' + trackPlaycount + ' plays.')
                    validAlbum['tracks'] = validAlbum['tracks'] + [aRecording]
                    echo (" ")
            echo (MusicBrainz_releaseTitle + " has " + str(len(validAlbum['tracks'])) + " tracks.")
            echo (" ")

    echo ("Done with all albums and tracks. Now writing to file.")
    echo (" ")
    artist['albums'] = releaseGroupsList

    # Write artist to file
    artistNameFor_file_name = artistName.replace(' ', '')

    artistTypeFor_file_name = artistType
    
    dateFor_file_name = time.strftime("%m-%d-%y")

    artistJSON = json.dumps(artist, indent=4)

    f = open ('data/' + artistNameFor_file_name + '_' + artistTypeFor_file_name  + '_' + dateFor_file_name + '.json', 'w')
    f.write (artistJSON)
    f.close()

    echo("File written")



######################

function get_artistAlbums($thisArtist):

    # Get artist info (inc Release-Groups) from MusicBrainz
    MusicBrainz_artistMBID = $thisArtist;

    getReleaseGroups_totalURL = makeReleaseGroupsURL(MusicBrainz_artistMBID)

    #makeReleaseGroupsURL(MusicBrainz_artistMBID)

    responseReleaseGroups = requests.get(getReleaseGroups_totalURL)

    releaseGroupsJSON = responseReleaseGroups.json()

    # START BUILDING ARTIST DICTIONARY
    artistName = releaseGroupsJSON['name']
    artistType = releaseGroupsJSON['type']

    #create artist instance
    artist = {}
    artist['date'] = date
    artist['name'] = artistName
    artist['type'] = artistType
    artist['mbid'] = MusicBrainz_artistMBID

    echo ("Getting Artist stats from LastFM")
    echo (" ")

    LastFM_artistMBID = MusicBrainz_artistMBID

    get_artist_info_from_LastFM = makeGetArtistInfoFromLastFM_URL(LastFM_artistMBID)

    artist_info_from_LastFM = requests.get(get_artist_info_from_LastFM)

    artistData = json.loads(artist_info_from_text)

    # Get Listeners and Playcount for Artist from LastFM
    artist['stats'] = {}
    LastFM_artistListeners = artistData['artist']['stats']['listeners']
    LastFM_artistPlaycount = artistData['artist']['stats']['playcount']
    artist['stats']['listeners'] = LastFM_artistListeners
    artist['stats']['playcount'] = LastFM_artistPlaycount

    # Artist birthday from MusicBrainz
    artistBirthday = releaseGroupsJSON['life-span']['begin']
    artist['birthday'] = artistBirthday

    #These tags are from LastFM
    genres = []

    #def makeGenres():
    tags = artistData['artist']['tags']['tag']
    for tag in tags:
        genre = tag['name']
        genres = genres + [genre]
    artist['genres'] = genres

    echo ("Stored artist genres using Tags from LastFM")
    echo (" ")

    # MAKE SURE ARTIST GETS GENRES FROM MusicBrainz AND TAGS FROM LastFM

    # GATHER MBID FOR RELEASE GROUPS
    # Store MBID for each Release-Group in a list
    releaseGroupsList = []
    echo ("Getting only the properties I want for each Release-Group")
    echo (" ")

    for releaseGroup in releaseGroupsJSON['release-groups']:
        aReleaseGroup = {}
        aReleaseGroup['mbid'] = releaseGroup['id']
        aReleaseGroup['title'] = releaseGroup['title']
        aReleaseGroup['releases'] = []
        releaseGroupsList = releaseGroupsList + [aReleaseGroup]

    echo ("I have a list of Release-Groups.")
    rg = len(releaseGroupsList)
    echo ("There are " + str(rg) + " Release-Groups in my list.")
    echo (" ")

    echo ("Getting Releases from each Release-Group")
    # Get Releases of a Release-Group from MusicBrainz
    for release_group in releaseGroupsList:
        MusicBrainz_releasegroupMBID = release_group['mbid']
        MusicBrainz_releasegroupTitle = release_group['title']
        release_group['releases'] = []
        echo ("Getting releases for " + MusicBrainz_releasegroupTitle)
        MusicBrainz_releasegroupMBID = MusicBrainz_releasegroupMBID
        getReleases_totalURL = makeGetReleases_totalURL(MusicBrainz_releasegroupMBID)
        responseReleases = requests.get(getReleases_totalURL)
        releasesJSON = responseReleases.json()
        release_group_all_Releases = []
        for release in releasesJSON['releases']:
            aRelease = {}
            aRelease['mbid'] = release['id']
            aRelease['title'] = release['title']
            aRelease['date'] = str(release.get('date', ''))
            aRelease['country'] = str(release.get('country', ''))
            aRelease['disambiguation'] = release['disambiguation']
            aRelease['packaging'] = release['packaging']
            release_group_all_Releases = release_group_all_Releases + [aRelease]

        rr = len(release_group_all_Releases)
        echo (release_group['title'] + " has " + str(rr) + " total releases")
        echo (" ")
        validAlbumsForThisReleaseGroup = []

        for release in release_group_all_Releases:
            LastFM_albumMBID = release['mbid']
            LastFM_albumTitle = release['title']
            LastFM_albumCountry = release['country']
            LastFM_albumDate = release['date']
            LastFM_albumCheckURL = makeLastFM_albumCheckURL(LastFM_albumMBID)
            responseCheck = requests.get(LastFM_albumCheckURL)
            albumData = json.loads(responseCheck.text)
            if "error" in albumData:
                echo (LastFM_albumTitle + " on " + LastFM_albumDate + " from " + LastFM_albumCountry + " does not exist in LastFM")
                echo (" ")
            else:
                thisAlbum = {}
                thisAlbum['name'] = albumData['album']['name']
                thisAlbum['mbid'] = albumData['album']['mbid']
                thisAlbum['listeners'] = albumData['album']['listeners']
                thisAlbum['playcount'] = albumData['album']['playcount']
                thisAlbum['date'] = release['date']
                thisAlbum['country'] = release['country']
                thisAlbum['disambiguation'] = release['disambiguation']
                thisAlbum['packaging'] = release['packaging']
                validAlbumsForThisReleaseGroup = validAlbumsForThisReleaseGroup + [thisAlbum]
                echo (thisAlbum['name'] + " on " + thisAlbum['date'] + " from " + thisAlbum['country'] + " exists in LastFM and stored in valid albums")
                echo (" ")
            echo (" ")

        echo (release_group['title'] + " has " + str(len(validAlbumsForThisReleaseGroup)) + " total VALID releases")
        echo (" ")
        release_group['releases'] = release_group['releases'] + validAlbumsForThisReleaseGroup
        # For each release, get MBID for recordings on that release from MusicBrainz
        for validAlbum in release_group['releases']:
            validAlbum['artistName'] = artist['name']
            validAlbum['artistMBID'] = artist['mbid']
            validAlbum['tracks'] = []
            MusicBrainz_releaseMBID = validAlbum['mbid']
            MusicBrainz_releaseTitle = validAlbum['name']
            echo ("Getting " + MusicBrainz_releaseTitle + " tracks info from MusicBrainz")
            echo (" ")
            getRecordings_totalURL = makeGetRecordings_totalURL(MusicBrainz_releaseMBID)
            responseRecordings = requests.get(getRecordings_totalURL)
            recordingsFromRelease = json.loads(responseRecordings.text)
            for track in recordingsFromRelease['media'][0]['tracks']:
                aRecording = {}
                aRecording['mbid'] = track['recording']['id']
                LastFM_trackMBID = aRecording['mbid']
                aRecording['title'] = track['recording']['title']
                LastFM_trackTitle = aRecording['title']
                echo ("Getting " + LastFM_trackTitle + " track stats from LastFM")
                echo (" ")
                LastFM_trackURL = getLastFM_trackURL (LastFM_trackMBID)
                responseTrack = requests.get(LastFM_trackURL)
                trackData = json.loads(responseTrack.text)
                # Get Listeners and Playcount for each Track (using Recording MBID) on an Album from LastFM
                if "error" in trackData:
                    echo (LastFM_trackTitle + " does not exist in LastFM")
                    echo (" ")
                else:
                    aRecording['stats'] = {}
                    aRecording['stats']['listeners'] = trackData['track']['listeners']
                    aRecording['stats']['playcount'] = trackData['track']['playcount']
                    trackName = aRecording['title']
                    aRecording['artistName'] = artist['name']
                    aRecording['artistMBID'] = artist['mbid']
                    trackListeners = aRecording['stats']['listeners']
                    trackPlaycount = aRecording['stats']['playcount']
                    echo(trackName + ' has ' + trackListeners + ' listeners and ' + trackPlaycount + ' plays.')
                    validAlbum['tracks'] = validAlbum['tracks'] + [aRecording]
                    echo (" ")
            echo (MusicBrainz_releaseTitle + " has " + str(len(validAlbum['tracks'])) + " tracks.")
            echo (" ")

    echo ("Done with all albums and tracks. Now writing to file.")
    echo (" ")
    artist['albums'] = releaseGroupsList

    # Write artist to file
    artistNameFor_file_name = artistName.replace(' ', '')

    artistTypeFor_file_name = artistType
    
    dateFor_file_name = time.strftime("%m-%d-%y")

    artistJSON = json.dumps(artist, indent=4)

    f = open ('data/' + artistNameFor_file_name + '_' + artistTypeFor_file_name  + '_' + dateFor_file_name + '.json', 'w')
    f.write (artistJSON)
    f.close()

    echo("File written")



######################

function get_artistAlbums($thisArtist):

    # Get artist info (inc Release-Groups) from MusicBrainz
    MusicBrainz_artistMBID = $thisArtist;

    getReleaseGroups_totalURL = makeReleaseGroupsURL(MusicBrainz_artistMBID)

    #makeReleaseGroupsURL(MusicBrainz_artistMBID)

    responseReleaseGroups = requests.get(getReleaseGroups_totalURL)

    releaseGroupsJSON = responseReleaseGroups.json()

    # START BUILDING ARTIST DICTIONARY
    artistName = releaseGroupsJSON['name']
    artistType = releaseGroupsJSON['type']

    #create artist instance
    artist = {}
    artist['date'] = date
    artist['name'] = artistName
    artist['type'] = artistType
    artist['mbid'] = MusicBrainz_artistMBID

    echo ("Getting Artist stats from LastFM")
    echo (" ")

    LastFM_artistMBID = MusicBrainz_artistMBID

    get_artist_info_from_LastFM = makeGetArtistInfoFromLastFM_URL(LastFM_artistMBID)

    artist_info_from_LastFM = requests.get(get_artist_info_from_LastFM)

    artistData = json.loads(artist_info_from_text)

    # Get Listeners and Playcount for Artist from LastFM
    artist['stats'] = {}
    LastFM_artistListeners = artistData['artist']['stats']['listeners']
    LastFM_artistPlaycount = artistData['artist']['stats']['playcount']
    artist['stats']['listeners'] = LastFM_artistListeners
    artist['stats']['playcount'] = LastFM_artistPlaycount

    # Artist birthday from MusicBrainz
    artistBirthday = releaseGroupsJSON['life-span']['begin']
    artist['birthday'] = artistBirthday

    #These tags are from LastFM
    genres = []

    #def makeGenres():
    tags = artistData['artist']['tags']['tag']
    for tag in tags:
        genre = tag['name']
        genres = genres + [genre]
    artist['genres'] = genres

    echo ("Stored artist genres using Tags from LastFM")
    echo (" ")

    # MAKE SURE ARTIST GETS GENRES FROM MusicBrainz AND TAGS FROM LastFM

    # GATHER MBID FOR RELEASE GROUPS
    # Store MBID for each Release-Group in a list
    releaseGroupsList = []
    echo ("Getting only the properties I want for each Release-Group")
    echo (" ")

    for releaseGroup in releaseGroupsJSON['release-groups']:
        aReleaseGroup = {}
        aReleaseGroup['mbid'] = releaseGroup['id']
        aReleaseGroup['title'] = releaseGroup['title']
        aReleaseGroup['releases'] = []
        releaseGroupsList = releaseGroupsList + [aReleaseGroup]

    echo ("I have a list of Release-Groups.")
    rg = len(releaseGroupsList)
    echo ("There are " + str(rg) + " Release-Groups in my list.")
    echo (" ")

    echo ("Getting Releases from each Release-Group")
    # Get Releases of a Release-Group from MusicBrainz
    for release_group in releaseGroupsList:
        MusicBrainz_releasegroupMBID = release_group['mbid']
        MusicBrainz_releasegroupTitle = release_group['title']
        release_group['releases'] = []
        echo ("Getting releases for " + MusicBrainz_releasegroupTitle)
        MusicBrainz_releasegroupMBID = MusicBrainz_releasegroupMBID
        getReleases_totalURL = makeGetReleases_totalURL(MusicBrainz_releasegroupMBID)
        responseReleases = requests.get(getReleases_totalURL)
        releasesJSON = responseReleases.json()
        release_group_all_Releases = []
        for release in releasesJSON['releases']:
            aRelease = {}
            aRelease['mbid'] = release['id']
            aRelease['title'] = release['title']
            aRelease['date'] = str(release.get('date', ''))
            aRelease['country'] = str(release.get('country', ''))
            aRelease['disambiguation'] = release['disambiguation']
            aRelease['packaging'] = release['packaging']
            release_group_all_Releases = release_group_all_Releases + [aRelease]

        rr = len(release_group_all_Releases)
        echo (release_group['title'] + " has " + str(rr) + " total releases")
        echo (" ")
        validAlbumsForThisReleaseGroup = []

        for release in release_group_all_Releases:
            LastFM_albumMBID = release['mbid']
            LastFM_albumTitle = release['title']
            LastFM_albumCountry = release['country']
            LastFM_albumDate = release['date']
            LastFM_albumCheckURL = makeLastFM_albumCheckURL(LastFM_albumMBID)
            responseCheck = requests.get(LastFM_albumCheckURL)
            albumData = json.loads(responseCheck.text)
            if "error" in albumData:
                echo (LastFM_albumTitle + " on " + LastFM_albumDate + " from " + LastFM_albumCountry + " does not exist in LastFM")
                echo (" ")
            else:
                thisAlbum = {}
                thisAlbum['name'] = albumData['album']['name']
                thisAlbum['mbid'] = albumData['album']['mbid']
                thisAlbum['listeners'] = albumData['album']['listeners']
                thisAlbum['playcount'] = albumData['album']['playcount']
                thisAlbum['date'] = release['date']
                thisAlbum['country'] = release['country']
                thisAlbum['disambiguation'] = release['disambiguation']
                thisAlbum['packaging'] = release['packaging']
                validAlbumsForThisReleaseGroup = validAlbumsForThisReleaseGroup + [thisAlbum]
                echo (thisAlbum['name'] + " on " + thisAlbum['date'] + " from " + thisAlbum['country'] + " exists in LastFM and stored in valid albums")
                echo (" ")
            echo (" ")

        echo (release_group['title'] + " has " + str(len(validAlbumsForThisReleaseGroup)) + " total VALID releases")
        echo (" ")
        release_group['releases'] = release_group['releases'] + validAlbumsForThisReleaseGroup
        # For each release, get MBID for recordings on that release from MusicBrainz
        for validAlbum in release_group['releases']:
            validAlbum['artistName'] = artist['name']
            validAlbum['artistMBID'] = artist['mbid']
            validAlbum['tracks'] = []
            MusicBrainz_releaseMBID = validAlbum['mbid']
            MusicBrainz_releaseTitle = validAlbum['name']
            echo ("Getting " + MusicBrainz_releaseTitle + " tracks info from MusicBrainz")
            echo (" ")
            getRecordings_totalURL = makeGetRecordings_totalURL(MusicBrainz_releaseMBID)
            responseRecordings = requests.get(getRecordings_totalURL)
            recordingsFromRelease = json.loads(responseRecordings.text)
            for track in recordingsFromRelease['media'][0]['tracks']:
                aRecording = {}
                aRecording['mbid'] = track['recording']['id']
                LastFM_trackMBID = aRecording['mbid']
                aRecording['title'] = track['recording']['title']
                LastFM_trackTitle = aRecording['title']
                echo ("Getting " + LastFM_trackTitle + " track stats from LastFM")
                echo (" ")
                LastFM_trackURL = getLastFM_trackURL (LastFM_trackMBID)
                responseTrack = requests.get(LastFM_trackURL)
                trackData = json.loads(responseTrack.text)
                # Get Listeners and Playcount for each Track (using Recording MBID) on an Album from LastFM
                if "error" in trackData:
                    echo (LastFM_trackTitle + " does not exist in LastFM")
                    echo (" ")
                else:
                    aRecording['stats'] = {}
                    aRecording['stats']['listeners'] = trackData['track']['listeners']
                    aRecording['stats']['playcount'] = trackData['track']['playcount']
                    trackName = aRecording['title']
                    aRecording['artistName'] = artist['name']
                    aRecording['artistMBID'] = artist['mbid']
                    trackListeners = aRecording['stats']['listeners']
                    trackPlaycount = aRecording['stats']['playcount']
                    echo(trackName + ' has ' + trackListeners + ' listeners and ' + trackPlaycount + ' plays.')
                    validAlbum['tracks'] = validAlbum['tracks'] + [aRecording]
                    echo (" ")
            echo (MusicBrainz_releaseTitle + " has " + str(len(validAlbum['tracks'])) + " tracks.")
            echo (" ")

    echo ("Done with all albums and tracks. Now writing to file.")
    echo (" ")
    artist['albums'] = releaseGroupsList

    # Write artist to file
    artistNameFor_file_name = artistName.replace(' ', '')

    artistTypeFor_file_name = artistType
    
    dateFor_file_name = time.strftime("%m-%d-%y")

    artistJSON = json.dumps(artist, indent=4)

    f = open ('data/' + artistNameFor_file_name + '_' + artistTypeFor_file_name  + '_' + dateFor_file_name + '.json', 'w')
    f.write (artistJSON)
    f.close()

    echo("File written");
    echo($artist);

for mbid in artistsData.mbid_array:
    get_artists_data(mbid)

        
?>