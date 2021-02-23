import requests
import json
import pprint
import artistsData

# MusicBrainz variables
MusicBrainz_artistArtistRelsPrefix = 'https://www.musicbrainz.org/ws/2/artist/'

# Part of URL for getting MusicBrainz artist's artist relationships
MusicBrainz_artistArtistRelsSuffix = '?inc=artist-rels&fmt=json'

# Get just artist artist-rels from MusicBrainz

def makeArtistRelsURL(MusicBrainz_artistMBID):
    getArtist_totalURL = MusicBrainz_artistArtistRelsPrefix + MusicBrainz_artistMBID + MusicBrainz_artistArtistRelsSuffix
    return getArtist_totalURL

x_anchorArtists = [
    'ee58c59f-8e7f-4430-b8ca-236c4d3745ae', # Alice Cooper person   
    '4d7928cd-7ed2-4282-8c29-c0c9f966f1bd', # Alice Cooper band 
    'fc4953aa-6bf4-4f1f-8e47-5ac79ca428e2', # Ronnie James Dio 
    '5182c1d9-c7d2-4dad-afa0-ccfeada921a8', # Black Sabbath  
    '79491354-3d83-40e3-9d8e-7592d58d790a' # deepPurple    
]

def get_artists_rels(artistVar):

    MusicBrainz_artistMBID = artistVar

    getArtistRelsURL = makeArtistRelsURL(MusicBrainz_artistMBID)

    ArtistRelsFromMB = requests.get(getArtistRelsURL)

    artistRelsMB = json.loads(ArtistRelsFromMB.text)

    # Make anchor artist
    artist = {}
    artist['name'] = artistRelsMB['name']
    artistName = artist['name']
    artist['mbid'] = artistRelsMB['id']
    artist['type'] = artistRelsMB['type']
    artistType = artist['type']
    if artistType == None:
        artistType = 'None'
    artist['relations'] = []
    thisArtistRelations = artist['relations']

    # Get Relations for Artist from musicBrainz
    relations = artistRelsMB['relations']

    for relation in relations:
        # Get only relation info that I want

        relTypeID = relation['type-id']
        # Do not want Tribute bands
        # tribute = 'a6f62641-2f58-470e-b02b-88d7b984dc9f'
        member = '5be4c609-9afa-4ea0-910b-12ffb71e3821'
        if relTypeID == member:
            aRelation = {}
            aRelation['type'] = relation['type']
            aRelation['mbid'] = relation['artist']['id']
            aRelation['name'] = relation['artist']['name']
            aRelation['attributes'] = relation['attributes']
            #thisArtistRelations = thisArtistRelations + [aRelation]
            thisArtistRelations.append(aRelation)

    print (artist)

    artistRelsJSON = json.dumps(artist, indent=4)

    # Write artist to file
    artistNameFor_file_name = artistName.replace(' ', '')
    artistTypeFor_file_name = artistType

    # absPathFor_file_name = '/home/roxorsox/public_html/poprock/data_text/'
    # newFilename = absPathFor_file_name + artistNameFor_file_name + '_' + artistTypeFor_file_name  + '_Rels.json'

    newFilename = artistNameFor_file_name + '_' + artistTypeFor_file_name  + '_Rels.json'

    encodedFilename = newFilename.encode('utf-8')

    f = open (encodedFilename, 'w')
    f.write (artistRelsJSON)
    f.close()

    print("File written with name " + newFilename)  
    #pprint.pprint(artist)

anchorArtists = artistsData.mbidArrayJust

for anchorArtist in anchorArtists:
    artistVar = anchorArtist
    get_artists_rels(artistVar)