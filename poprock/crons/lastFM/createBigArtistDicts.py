#!/usr/bin/python

import json
import artistsData

basePath = '../Pop-Rock-PHP/crons/lastFM/'

myArtists = []

filenames2 = ['data/EvilStig_Group_09-28-19.json']

filenames = [
    'data/AliceCooper_Combined_09-28-19.json',
    'data/TheAmboyDukes_Group_09-22-19.json',
    'data/EvilStig_Group_09-28-19.json', 
    'data/JoanJett_Combined_09-28-19.json', 
	'data/TheRunaways_Group_09-28-19.json',
    'data/TedNugent_Person_09-22-19.json', 
    'data/DavidBowie_Person_09-22-19.json',
    'data/JanetJackson_Person_09-22-19.json',
    'data/12thTribe_Group_09-22-19.json',
    'data/ArgylePark_Group_09-22-19.json',
    'data/BarrenCross_Group_09-22-19.json',
    'data/Believer_Group_09-22-19.json',
    'data/Anvil_Group_09-23-19.json',
    'data/LindseyBuckingham_Person_09-23-19.json',
    'data/TheCure_Group_09-23-19.json',
    'data/Eminem_Person_09-23-19.json',
    'data/FleetwoodMac_Group_09-23-19.json',
    'data/StevieNicks_Person_09-23-19.json',
    'data/Radiohead_Group_09-23-19.json',
    'data/Bloodgood_Group_09-23-19.json',
    'data/BobDylan_Person_09-23-19.json',
    'data/Bride_Group_09-23-19.json',
    'data/CannibalCorpse_Group_09-23-19.json',
    'data/ChagallGuevara_Group_09-23-19.json',
    'data/CircleofDust_Group_09-23-19.json',
    'data/Crashdog_Group_09-23-19.json',
    'data/Deitiphobia_Group_09-23-19.json',
    'data/Deliverance_Group_09-23-19.json',
    'data/BlackSabbath_Group_09-24-19.json',
    'data/Dio_Group_09-24-19.json', 
    'data/Elf_Group_09-24-19.json', 
    'data/TheElectricElves_Group_09-24-19.json', 
    'data/Heaven&Hell_Group_09-24-19.json', 
    'data/OzzyOsbourne_Person_09-24-19.json', 
	'data/Rainbow_Group_09-24-19.json',
    'data/RonnieDioandtheProphets_Group_09-24-19.json', 
    'data/RonnieDioandtheRedCaps_Group_09-24-19.json',
    'data/DisciplesofChrist_Group_09-24-19.json',
    'data/BillyIdol_Person_09-24-19.json',
    'data/TheFirm_Group_09-25-19.json',
    'data/JimmyPage_Person_09-25-19.json',
    'data/JimmyPage&RobertPlant_Group_09-25-19.json',
    'data/LedZeppelin_Group_09-25-19.json',
    'data/RobertPlant_Person_09-25-19.json',
	'data/TheYardbirds_Group_09-25-19.json',
    'data/EltonJohn_Person_09-25-19.json', 
    'data/AceFrehley_Person_09-25-19.json',   
    'data/Jerusalem_Group_09-25-19.json', 
    'data/GlennKaiser_Person_09-25-19.json', 
    'data/ResurrectionBand_Group_09-25-19.json',         
    'data/LarryNorman_Person_09-25-19.json',  
    'data/LustControl_Group_09-25-19.json',
	'data/IggyandTheStooges_Group_09-26-19.json',
    'data/IggyPop_Person_09-26-19.json',
    'data/Journey_Group_09-26-19.json', 
    'data/MeatLoaf_Person_09-26-19.json', 
    'data/Stoney&Meatloaf_Group_09-26-19.json',
    'data/TheStooges_Group_09-26-19.json',
    'data/MadattheWorld_Group_09-26-19.json',  
    'data/Mortal_Group_09-26-19.json',  
    'data/Mortification_Group_09-26-19.json',      
    'data/NinetyPoundWuss_Group_09-26-19.json',  
    'data/OneBadPig_Group_09-26-19.json',      
    'data/P.I.D._Group_09-26-19.json',  
    'data/P.O.D._Group_09-26-19.json',    
    'data/SackclothFashion_Group_09-26-19.json',  
    'data/Saint_Group_09-26-19.json',  
    'data/SayWhat?_Group_09-26-19.json',
	'data/2Pac_Person_09-27-19.json',
    'data/DefLeppard_Group_09-27-19.json',
    'data/MotleyCrue_Group_09-27-19.json',
    'data/Queen_Group_09-27-19.json', 
    'data/QuietRiot_Group_09-27-19.json', 
    'data/ToddRundgren_Person_09-27-19.json',
	'data/Utopia_Group_09-27-19.json',
    'data/SteveTaylor_Person_09-27-19.json',  
    'data/TheCrucified_Group_09-27-19.json',     
    'data/TheVioletBurning_Group_09-27-19.json',  
    'data/TomPetty_Person_09-27-19.json',  
    'data/TomPettyandtheHeartbreakers_Group_09-27-19.json',
    'data/Cream_Group_09-28-19.json',
    'data/EricClapton_Person_09-28-19.json',
    'data/RoxyMusic_Group_09-28-19.json',
    'data/Saxon_Group_09-28-19.json', 
    'data/NeilYoung_Person_09-28-19.json',
	'data/TheZombies_Group_09-28-19.json'              
]

def get_artist_info(fromFileName):

    global myArtists

    fileName = fromFileName

    with open(fileName, 'r') as f:
        artistInfo = json.load(f)
        artist = {}
        artist['name'] = artistInfo['name']
        artist['mbid'] = artistInfo['mbid']  
        artist['type'] = artistInfo['type']
        artistType = artist['type']
        artist['stats'] = {}
        artist['stats']['listeners'] = ''
        artist['stats']['playcount'] = ''
        artist['genres'] = artistInfo['genres']
        artist['birthday'] = artistInfo['birthday']
        jsonAlbumsList = artistInfo['albums']
        artist['albums'] = []
        #albums = artist['albums']

        def get_album_info(thisRelease):
            albums = artist['albums']
            thisAlbum = {}
            thisAlbum['name'] = thisRelease['name']
            thisAlbum['mbid'] = thisRelease['mbid']
            thisAlbum['stats'] = {}
            thisAlbum['stats']['listeners'] = ''
            thisAlbum['stats']['playcount'] = ''
            thisAlbum['date'] = thisRelease['date']
            thisAlbum['country'] = thisRelease['country']
            thisAlbum['disambiguation'] = thisRelease['disambiguation']
            thisAlbum['packaging'] = thisRelease['packaging']
            thisAlbum['tracks'] = []
            thisAlbum['tracks'] = thisRelease['tracks']
            tracks = thisAlbum['tracks']
            for track in tracks:
                track['stats']['listeners'] = ''
                track['stats']['playcount'] = ''
            #print(thisAlbum)
            albums = albums + ['thisAlbum']
        
        for album in jsonAlbumsList:
            releasesList = []
            releasesList = album['releases']
            thisRelease = {}
            for jsonRelease in releasesList:
                if jsonRelease['country'] == 'US':
                    thisRelease = jsonRelease
                    get_album_info(thisRelease)
                    break
                else:
                    thisRelease = releasesList[0]
                    get_album_info(thisRelease)
            print(thisRelease)
            #get_album_info(thisRelease)
            
    # Write artist to file
    artistName = artist['name']
    artistNameFor_file_name = artistName.replace(' ', '')

    artistTypeFor_file_name = artistType

    artistJSON = json.dumps(artist, indent=4)

    absPathFor_file_name = '/home/roxorsox/public_html/poprock/crons/lastFM/templates/'

    newFilename = absPathFor_file_name + artistNameFor_file_name + '_' + artistTypeFor_file_name  + '_TEMPLATE' + '.json'

    encodedFilename = newFilename.encode('utf-8')

    f = open (encodedFilename, 'w')
    f.write (artistJSON)

    f.close()

for file in filenames2:
    get_artist_info(file)
    
#artistData = json.loads(artist_info_from_LastFM.text)

# Write dict to file
artistsInfoJSON = json.dumps(myArtists, indent=4)

newFilename = 'myBigArtistDicts' + '.json'

#newFilename = encodedFilename + '.json'

with open(newFilename, 'w') as n:
    n.write (artistsInfoJSON)
    n.close()

print("File written")
#pprint.pprint(artist)