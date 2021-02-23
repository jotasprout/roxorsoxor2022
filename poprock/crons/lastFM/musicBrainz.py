MusicBrainz_artistMBID = ''

# MusicBrainz variables
MusicBrainz_baseURL = 'https://www.musicbrainz.org/ws/2/'

# Part of URL for using artist MBID
MusicBrainz_artistMethod = 'artist/'

# Part of URL for getting MusicBrainz Release Groups info
MusicBrainz_getReleaseGroups = '?inc=release-groups'

# Part of URL for using Release Groups MBID to get Releases
MusicBrainz_releasegroupMethod = 'release-group/'

# Part of URL for getting MusicBrainz Releases info
MusicBrainz_releases = '?inc=releases'

# Part of URL for using Release MBID
MusicBrainz_releaseMethod = 'release/'

# Part of URL for getting MusicBrainz Recordings info
MusicBrainz_recordings = '?inc=recordings'

# Part of URL for getting MusicBrainz artist genres
MusicBrainz_genres = '?inc=genres'

# MusicBrainz response format
MusicBrainz_jsonFormat = '&fmt=json'


# Get just artist info from MusicBrainz
# Replaced by makeArtistGenresURL

def makeArtistURL(MusicBrainz_artistMBID):
    getArtist_totalURL = MusicBrainz_baseURL + MusicBrainz_artistMethod + MusicBrainz_artistMBID + MusicBrainz_jsonFormat
    return getArtist_totalURL

# Example using Alice Cooper

#getArtistInfoFromMusicBrainz = 'https://www.musicbrainz.org/ws/2/artist/ee58c59f-8e7f-4430-b8ca-236c4d3745ae?fmt=json'


####


# Get just artist info with genres from MusicBrainz (different sources have different genres)

def makeArtistGenresURL(MusicBrainz_artistMBID):
    getArtistGenres_totalURL = MusicBrainz_baseURL + MusicBrainz_artistMethod + MusicBrainz_artistMBID + MusicBrainz_genres + MusicBrainz_jsonFormat
    return getArtistGenres_totalURL

# Example using Black Sabbath

#getArtistInfoFromMusicBrainz = 'https://www.musicbrainz.org/ws/2/artist/5182c1d9-c7d2-4dad-afa0-ccfeada921a8?inc=genres&fmt=json'


####

# Below gets artist info (including Release-Groups) from MusicBrainz
def makeReleaseGroupsURL(MusicBrainz_artistMBID):
    getReleaseGroups_totalURL = MusicBrainz_baseURL + MusicBrainz_artistMethod + MusicBrainz_artistMBID + MusicBrainz_getReleaseGroups + MusicBrainz_jsonFormat
    return getReleaseGroups_totalURL

# Example of above
# getReleaseGroups_totalURL = 'https://www.musicbrainz.org/ws/2/artist/5182c1d9-c7d2-4dad-afa0-ccfeada921a8?inc=release-groups&fmt=json'

# Next gets releases of release group
def makeGetReleases_totalURL(MusicBrainz_releasegroupMBID):
    getReleases_totalURL = MusicBrainz_baseURL + MusicBrainz_releasegroupMethod + MusicBrainz_releasegroupMBID + MusicBrainz_releases + MusicBrainz_jsonFormat
    return getReleases_totalURL

# Example using 
#rels = 'https://www.musicbrainz.org/ws/2/release-group/43ac82b5-eceb-3909-a153-3c00ea9c7b9a?inc=releases&fmt=json'

# Example using David Bowie
#rels = 'https://www.musicbrainz.org/ws/2/artist/5441c29d-3602-4898-b1a1-b77fa23b8e50?inc=releases&fmt=json'


#### 


# Used on line 172 of getArtistDataCron_0X in 'for validAlbum in release_group' function

def makeGetRecordings_totalURL(MusicBrainz_releaseMBID):
    getRecordings_totalURL = MusicBrainz_baseURL + MusicBrainz_releaseMethod + MusicBrainz_releaseMBID + MusicBrainz_recordings + MusicBrainz_jsonFormat
    return getRecordings_totalURL

# Below is get recordings
# rec = 'https://www.musicbrainz.org/ws/2/release/d7c44a20-2c64-3c3a-8e6f-e865671cb56c?inc=recordings&fmt=json'