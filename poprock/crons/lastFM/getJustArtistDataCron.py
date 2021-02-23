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

#print ("Getting Artist info and RELEASE GROUPS from MusicBrainz")
#print (" ")

def get_artists_data(artistVar):

    artistStart = time.time()

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

    #print ("Getting Artist stats from LastFM")
    #print (" ")

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

    # Write artist to file
    artistNameFor_file_name = artistName.replace(' ', '')

    artistTypeFor_file_name = artistType
    
    dateFor_file_name = time.strftime("%m-%d-%y")

    artistEnd = time.time()
    duration = artistEnd - artistStart
    artist['taskDuration'] = {}
    artist['taskDuration']['Task Start'] = artistStart
    artist['taskDuration']['Task End'] = artistEnd
    artist['taskDuration']['Task Duration'] = duration

    artistJSON = json.dumps(artist, indent=4)

    absPathFor_file_name = '/home/roxorsox/public_html/poprock/crons/lastFM/data/'

    newFilename = absPathFor_file_name + artistNameFor_file_name + '_' + artistTypeFor_file_name  + '_' + dateFor_file_name + '.json'

    f = open (newFilename, 'w')
    f.write (artistJSON)
    f.close()

    print("File written")
    #pprint.pprint(artist)

for mbid in artistsData.mbid_array:
    get_artists_data(mbid)