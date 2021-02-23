import json
import time

dataDate = '09-08-19'
#dataDate = '09-15-19'
#dataDate = '09-22-19'

dataFolder = '/home/roxorsox/public_html/poprock/crons/lastFM/data/'
fromArtistJSON = 'AliceCooper_Group_'
toArtistJSON = 'AliceCooper_Person_'
#fromArtistJSON = 'JoanJettandtheBlackhearts_Group_'
#toArtistJSON = 'JoanJett_Person_'
ext = '.json'

pL = pP = gL = gP = cL = cP = 0

jj = 'f376828a-b438-4fda-bb2e-dcd5fbe81f83'
bh = '46e63d3b-d91b-4791-bb73-e9f638a45ea0'

def getStats():
    global pL, pP, gL, gP, cL, cP
    pL = int(artistTo['stats']['listeners'])
    pP = int(artistTo['stats']['playcount'])
    gL = int(artistFrom['stats']['listeners'])
    gP = int(artistFrom['stats']['playcount'])
    print ('JJ has ' + str(pL) + ' listeners')
    print ('JJ has ' + str(pP) + ' plays')
    print ('BH have ' + str(gL) + ' listeners')
    print ('BH have ' + str(gP) + ' plays')

def addStats():
    global pL, pP, gL, gP, cL, cP
    print ('pL = ' + str(pL))
    print ('pP = ' + str(pP))
    print ('gL = ' + str(gL))
    print ('gP = ' + str(gP))
    cL = pL + gL
    cP = pP + gP

def updateJJstats():
    global artistTo, cL, cP
    artistTo['stats']['listeners'] = cL
    artistTo['stats']['playcount'] = cP
    print ('cL = ' + str(cL) + ' listeners')
    print ('cP = ' + str(cP) + ' plays')
    #print (artistTo)

def combineJJstats():
    artistMBID = artistTo['mbid']
    if (artistMBID == jj):
        getStats()
        addStats()
        updateJJstats()

fromFilename = dataFolder + fromArtistJSON + dataDate + ext
print fromFilename
toFilename = dataFolder + toArtistJSON + dataDate + ext
print toFilename

# Open file from which I will take data ("b" for "band")
with open(fromFilename, 'r') as b:
    artistFrom = json.load(b)

with open(toFilename, 'r') as p:
    artistTo = json.load(p)

artistFromAlbums = artistFrom['albums']
print ('Band has ' + str(len(artistFromAlbums)) + ' albums')

artistToAlbums = artistTo['albums']
print ('Person has ' + str(len(artistTo['albums'])) + ' albums')

artistToAlbums = artistToAlbums + artistFromAlbums
print ('There are ' + str(len(artistToAlbums)) + ' total combined albums')

artistTo['albums'] = artistToAlbums
print ('artistTo now has ' + str(len(artistTo['albums'])) + ' total albums')

newArtistToAlbums = artistTo['albums']
print ('artistTo really has ' + str(len(newArtistToAlbums)) + ' albums')

combineJJstats()

combinedArtistName = artistTo['name'].replace(' ', '')

with open('data/' + combinedArtistName + '_Combined_' + dataDate + '.json', 'w') as f:
    f.write(json.dumps(artistTo))

f.close
print('File written.')

#import addStats