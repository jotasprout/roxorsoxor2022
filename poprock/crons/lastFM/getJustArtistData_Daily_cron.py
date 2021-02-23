#!/usr/bin/python

import requests
import json
import pprint
import time
import lastFM
import musicBrainz
import artistsData
import subprocess

pL = pP = gL = gP = cL = cP = 0

jj = 'f376828a-b438-4fda-bb2e-dcd5fbe81f83'
bh = '46e63d3b-d91b-4791-bb73-e9f638a45ea0'

date = time.strftime("%Y-%m-%d")
statsToday = {}
statsToday['date'] = date
artistsFrom = []
artistsFrom = artistsData.myArtistDicts
artistsTo = []

def getArtistListFromJSON ():
    global artistsFrom
    with open('myArtistDicts.json', 'r') as a:
        artistInfo = json.load(a)
    artistsFrom = artistInfo
    a.close()

def get_artists_data(artistVar):
    
    # Get artist genres from MusicBrainz

    MusicBrainz_artistMBID = artistVar

    getArtistInfoFromMusicBrainz = musicBrainz.makeArtistGenresURL(MusicBrainz_artistMBID)

    artistInfoFromMB = requests.get(getArtistInfoFromMusicBrainz)

    artistMB = json.loads(artistInfoFromMB.text)

    # Get artist info (inc Release-Groups) from MusicBrainz
    LastFM_artistMBID = artistVar

    get_artist_info_from_LastFM = lastFM.makeGetArtistInfoFromLastFM_URL(LastFM_artistMBID)

    artist_info_from_LastFM = requests.get(get_artist_info_from_LastFM)

    artistToday = json.loads(artist_info_from_LastFM.text)

    # BUILD ARTIST DICTIONARY
    artist = {}

    artist['name'] = artistMB['name']
    # removed ['artist'] from above due to errors in cron emails
    artist['mbid'] = LastFM_artistMBID 
    artist['MBgenres'] = artistMB['genres']

    # Get Listeners and Playcount for Artist from LastFM
    artist['stats'] = {}
    LastFM_artistListeners = artistToday['artist']['stats']['listeners']
    LastFM_artistPlaycount = artistToday['artist']['stats']['playcount']
    artist['stats']['listeners'] = LastFM_artistListeners
    artist['stats']['playcount'] = LastFM_artistPlaycount

    print (artist)

    # Add this artist to myArtists list
    artistsTo.append(artist)

def putAllInArtistsTo ():
    for artist in artistsFrom:
        artistMBID = artist['mbid'] 
        get_artists_data(artistMBID)

def getStats():
    global artistsTo, pL, pP, gL, gP
    for artist in artistsTo:
        artistMBID = artist['mbid']
        if (artistMBID == jj):
            pL = int(artist['stats']['listeners'])
            pP = int(artist['stats']['playcount'])
            break
    for artist in artistsTo:
        artistMBID = artist['mbid']
        if (artistMBID == bh):
            gL = int(artist['stats']['listeners'])
            gP = int(artist['stats']['playcount'])
            break
    print ('JJ has ' + str(pL) + ' listeners')
    print ('JJ has ' + str(pP) + ' plays')
    print ('BH have ' + str(gL) + ' listeners')
    print ('BH have ' + str(gP) + ' plays')

def addStatsDaily ():
    global pL, pP, gL, gP, cL, cP
    print ('pL = ' + str(pL))
    print ('pP = ' + str(pP))
    print ('gL = ' + str(gL))
    print ('gP = ' + str(gP))
    cL = pL + gL
    cP = pP + gP

def updateJJstats():
    global artistsTo, cL, cP
    for artist in artistsTo:
        artistMBID = artist['mbid']
        if (artistMBID == jj):
            artist['stats']['listeners'] = cL
            artist['stats']['playcount'] = cP
            break
    print ('cL = ' + str(cL) + ' listeners')
    print ('cP = ' + str(cP) + ' plays')
    print (artist)

def deleteBH_old():
    global artistsTo
    for artist in artistsTo:
        artistMBID = artist['mbid']
        if (artistMBID == bh):
            del artist
            break

def deleteBH():
    global artistsTo, bh
    for i in range(len(artistsTo)):
        if (artistsTo[i]['mbid'] == bh):
            del artistsTo[i]
            break

putAllInArtistsTo()

print(len(artistsTo))
getStats()
addStatsDaily()
updateJJstats()
deleteBH()
print(len(artistsTo))

statsToday['myArtists'] = artistsTo
  
dateFor_file_name = time.strftime("%m-%d-%y")

artistsStatsJSON = json.dumps(statsToday, indent=4)

absPathForFileName = '/home/roxorsox/public_html/poprock/crons/lastFM/data/justDaily_'

newFilename = absPathForFileName + dateFor_file_name + '.json'

f = open (newFilename, 'w')
f.write (artistsStatsJSON)
f.close()

print("File written with name " + newFilename)    

subprocess.call(["/usr/local/bin/php" , "/home/roxorsox/public_html/poprock/crons/lastFM/insertJustArtistData_Daily_cron.php"])