#!/usr/bin/python

import json
import time

pL = pP = gL = gP = cL = cP = 0

dateFor_file_name = time.strftime("%m-%d-%y")

dataDate = dateFor_file_name

#dataDate = '06-30-19'

dataFolder = '/home/roxorsox/public_html/poprock/crons/lastFM/data/'
fromArtistJSON = 'JoanJettandtheBlackhearts_Group_'
toArtistJSON = 'JoanJett_Person_'
ext = '.json'

fromFilename = dataFolder + fromArtistJSON + dataDate + ext
print (fromFilename)
toFilename = dataFolder + toArtistJSON + dataDate + ext
print (toFilename)

# Open file from which I will take data ("b" for "band")
with open(fromFilename, 'r') as b:
    artistFrom = json.load(b)

# Open file into which I will put data ("p" for "person")
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
    print (artistTo)

getStats()
addStats()
updateJJstats()

combinedArtistName = artistTo['name'].replace(' ', '')

combinedFileName = 'data/' + combinedArtistName + '_Combined_' + dataDate + '.json'

jj = open(combinedFileName, 'w')
jj.write(json.dumps(artistTo))
jj.close()

p.close()
b.close()

print('File written.')