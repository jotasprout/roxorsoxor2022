# LastFM variables
LastFM_baseURL = 'http://ws.audioscrobbler.com/2.0/?method='

# Part of URL for getting LastFM artist info
LastFM_artistInfo = 'artist.getinfo&mbid='

# Part of URL for getting LastFM album info
LastFM_albumInfo = 'album.getinfo&mbid='

LastFM_albumMBID = '' # item in list of MusicBrainz_releaseMBID 

# Part of URL for getting LastFM track info
LastFM_trackInfo = 'track.getinfo&mbid='

LastFM_trackMBID = '' # item in list of MusicBrainz_recordingMBID 

# LastFM API key
LastFM_apiKey = '&api_key=333a292213e03c10f38269019b5f3985'

# LastFM response format
LastFM_jsonFormat = '&format=json'

# Next used on line 51 in getArtistDataCron_0X to get artist stats from LastFM
def makeGetArtistInfoFromLastFM_URL(LastFM_artistMBID):
    get_artist_info_from_LastFM = LastFM_baseURL + LastFM_artistInfo + LastFM_artistMBID + LastFM_apiKey + LastFM_jsonFormat
    return get_artist_info_from_LastFM

# Example of result from line 109 using Black Sabbath. 
# Filename = data/BS_debugging/result_getReleases_totalURL

 #get_artist_info_from_LastFM = 'http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&mbid=5182c1d9-c7d2-4dad-afa0-ccfeada921a8&api_key=333a292213e03c10f38269019b5f3985&format=json'

####
####
####
####

# Next used on 137 in getArtistDataCron_0X 

def makeLastFM_albumCheckURL(LastFM_albumMBID):
    LastFM_albumCheckURL = LastFM_baseURL + LastFM_albumInfo + LastFM_albumMBID + LastFM_apiKey + LastFM_jsonFormat
    return LastFM_albumCheckURL

# Example of result from line 137 using 

#LastFM_albumCheckURL = 'http://ws.audioscrobbler.com/2.0/?method=album.getinfo&mbid=d7c44a20-2c64-3c3a-8e6f-e865671cb56c&api_key=333a292213e03c10f38269019b5f3985&format=json'


####
####
####
####

# Next used on line 184 in getArtistDataCron_0X

def getLastFM_trackURL (LastFM_trackMBID):
    LastFM_trackURL = LastFM_baseURL + LastFM_trackInfo + LastFM_trackMBID + LastFM_apiKey + LastFM_jsonFormat
    return LastFM_trackURL

# Example of result from line 184

#LastFM_trackURL = 'http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&mbid=ee58c59f-8e7f-4430-b8ca-236c4d3745ae&api_key=333a292213e03c10f38269019b5f3985&format=json'    