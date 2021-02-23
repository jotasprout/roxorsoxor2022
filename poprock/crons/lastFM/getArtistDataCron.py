#!/usr/bin/python

import requests
import json
import pprint
import time
import artistsData
import musicBrainz
import lastFM

date = time.strftime("%Y-%m-%d")
 
# ARTIST INFO
# Get artist info from MusicBrainz

print ("Getting Artist info and RELEASE GROUPS from MusicBrainz")
print (" ")

def get_artists_data(artistVar):

    # Get artist info (inc Release-Groups) from MusicBrainz
    MusicBrainz_artistMBID = artistVar

    getReleaseGroups_totalURL = musicBrainz.makeReleaseGroupsURL(MusicBrainz_artistMBID)

    #musicBrainz.makeReleaseGroupsURL(MusicBrainz_artistMBID)

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

    print ("Getting Artist stats from LastFM")
    print (" ")

    LastFM_artistMBID = MusicBrainz_artistMBID

    get_artist_info_from_LastFM = lastFM.makeGetArtistInfoFromLastFM_URL(LastFM_artistMBID)

    artist_info_from_LastFM = requests.get(get_artist_info_from_LastFM)

    artistData = json.loads(artist_info_from_LastFM.text)

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

    print ("Stored artist genres using Tags from LastFM")
    print (" ")

    # MAKE SURE ARTIST GETS GENRES FROM MusicBrainz AND TAGS FROM LastFM

    # GATHER MBID FOR RELEASE GROUPS
    # Store MBID for each Release-Group in a list
    releaseGroupsList = []
    print ("Getting only the properties I want for each Release-Group")
    print (" ")

    for releaseGroup in releaseGroupsJSON['release-groups']:
        aReleaseGroup = {}
        aReleaseGroup['mbid'] = releaseGroup['id']
        aReleaseGroup['title'] = releaseGroup['title']
        aReleaseGroup['releases'] = []
        releaseGroupsList = releaseGroupsList + [aReleaseGroup]

    print ("I have a list of Release-Groups.")
    rg = len(releaseGroupsList)
    print ("There are " + str(rg) + " Release-Groups in my list.")
    print (" ")

    print ("Getting Releases from each Release-Group")
    # Get Releases of a Release-Group from MusicBrainz
    for release_group in releaseGroupsList:
        MusicBrainz_releasegroupMBID = release_group['mbid']
        MusicBrainz_releasegroupTitle = release_group['title']
        release_group['releases'] = []
        print ("Getting releases for " + MusicBrainz_releasegroupTitle)
        MusicBrainz_releasegroupMBID = MusicBrainz_releasegroupMBID
        getReleases_totalURL = musicBrainz.makeGetReleases_totalURL(MusicBrainz_releasegroupMBID)
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
        print (release_group['title'] + " has " + str(rr) + " total releases")
        print (" ")
        validAlbumsForThisReleaseGroup = []

        for release in release_group_all_Releases:
            LastFM_albumMBID = release['mbid']
            LastFM_albumTitle = release['title']
            LastFM_albumCountry = release['country']
            LastFM_albumDate = release['date']
            LastFM_albumCheckURL = lastFM.makeLastFM_albumCheckURL(LastFM_albumMBID)
            responseCheck = requests.get(LastFM_albumCheckURL)
            albumData = json.loads(responseCheck.text)
            if "error" in albumData:
                print (LastFM_albumTitle + " on " + LastFM_albumDate + " from " + LastFM_albumCountry + " does not exist in LastFM")
                print (" ")
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
                print (thisAlbum['name'] + " on " + thisAlbum['date'] + " from " + thisAlbum['country'] + " exists in LastFM and stored in valid albums")
                print (" ")
            print (" ")

        print (release_group['title'] + " has " + str(len(validAlbumsForThisReleaseGroup)) + " total VALID releases")
        print (" ")
        release_group['releases'] = release_group['releases'] + validAlbumsForThisReleaseGroup
        # For each release, get MBID for recordings on that release from MusicBrainz
        for validAlbum in release_group['releases']:
            validAlbum['artistName'] = artist['name']
            validAlbum['artistMBID'] = artist['mbid']
            validAlbum['tracks'] = []
            MusicBrainz_releaseMBID = validAlbum['mbid']
            MusicBrainz_releaseTitle = validAlbum['name']
            print ("Getting " + MusicBrainz_releaseTitle + " tracks info from MusicBrainz")
            print (" ")
            getRecordings_totalURL = musicBrainz.makeGetRecordings_totalURL(MusicBrainz_releaseMBID)
            responseRecordings = requests.get(getRecordings_totalURL)
            recordingsFromRelease = json.loads(responseRecordings.text)
            for track in recordingsFromRelease['media'][0]['tracks']:
                aRecording = {}
                aRecording['mbid'] = track['recording']['id']
                LastFM_trackMBID = aRecording['mbid']
                aRecording['title'] = track['recording']['title']
                LastFM_trackTitle = aRecording['title']
                print ("Getting " + LastFM_trackTitle + " track stats from LastFM")
                print (" ")
                LastFM_trackURL = lastFM.getLastFM_trackURL (LastFM_trackMBID)
                responseTrack = requests.get(LastFM_trackURL)
                trackData = json.loads(responseTrack.text)
                # Get Listeners and Playcount for each Track (using Recording MBID) on an Album from LastFM
                if "error" in trackData:
                    print (LastFM_trackTitle + " does not exist in LastFM")
                    print (" ")
                else:
                    aRecording['stats'] = {}
                    aRecording['stats']['listeners'] = trackData['track']['listeners']
                    aRecording['stats']['playcount'] = trackData['track']['playcount']
                    trackName = aRecording['title']
                    aRecording['artistName'] = artist['name']
                    aRecording['artistMBID'] = artist['mbid']
                    trackListeners = aRecording['stats']['listeners']
                    trackPlaycount = aRecording['stats']['playcount']
                    print(trackName + ' has ' + trackListeners + ' listeners and ' + trackPlaycount + ' plays.')
                    validAlbum['tracks'] = validAlbum['tracks'] + [aRecording]
                    print (" ")
            print (MusicBrainz_releaseTitle + " has " + str(len(validAlbum['tracks'])) + " tracks.")
            print (" ")

    print ("Done with all albums and tracks. Now writing to file.")
    print (" ")
    artist['albums'] = releaseGroupsList

    # Write artist to file
    artistNameFor_file_name = artistName.replace(' ', '')

    artistTypeFor_file_name = artistType
    
    dateFor_file_name = time.strftime("%m-%d-%y")

    artistJSON = json.dumps(artist, indent=4)

    f = open ('data/' + artistNameFor_file_name + '_' + artistTypeFor_file_name  + '_' + dateFor_file_name + '.json', 'w')
    f.write (artistJSON)
    f.close()

    print("File written")
    #pprint.pprint(artist)

for mbid in artistsData.mbid_arrayZ:
    get_artists_data(mbid)

# Questions to ask 
## Which artists, albums, tracks, have a lower listener-to-play ratio?
# Highest and lowest of each genre