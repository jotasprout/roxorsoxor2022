import json
import artistList

dataDate = '04-23-19'
ratiosStr = 'ratios_'

inputDataFolder = 'data/'
outputDataFolder = 'data/misc_data/'
artist = ''
ext = '.json'

def makeInputFilename(artistAndType):
    filename = inputDataFolder + artistAndType + dataDate + ext
    return filename

def makeArtist(prettyName):
    artist = {}
    artist['name'] = prettyName
    artist['stats'] = {}

artistsRatios = []

def getAndSaveRatios():
    for artist in artistList.artistList:
        artistAndType = artist
        filename = makeInputFilename(artistAndType)
        
        with open(filename, 'r') as a:
            artist = {}
            artistJSON = json.load(a)
            artist['name'] = artistJSON['name']
            prettyName = artist['name']
            artist['stats'] = {}
            artist['stats']['listeners'] = int(artistJSON['stats']['listeners'])
            artist['stats']['playcount'] = int(artistJSON['stats']['playcount'])
            artistPlaycount = artist['stats']['playcount']
            artistListeners = artist['stats']['listeners']
            playsFloat = round(artistPlaycount/artistListeners)
            playsStr = str(playsFloat)
            print (prettyName + " Listener-to-Plays ratio is 1:" + playsStr + " (or " + playsStr + " Plays-per-Listener).\n")
            ratioStr = "1:" + playsStr
            artist['stats']['ratio'] = ratioStr
            artistsRatios.append(artist)

def makeOutPutJSON (artistsRatios):
    outputFilename = outputDataFolder + ratiosStr + dataDate + ext
    ratiosOutputJSON = json.dumps(artistsRatios, indent=4)
    f = open (outputFilename, 'w')
    f.write (ratiosOutputJSON)
    f.close()
    print("Ratios file written")    

getAndSaveRatios()   
makeOutPutJSON(artistsRatios)

