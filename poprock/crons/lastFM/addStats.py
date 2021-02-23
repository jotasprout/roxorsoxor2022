import json
import time

dataDate = '05-12-19'
#dataDate = '05-13-19'
#dataDate = '05-14-19'
#dataDate = '05-15-19'

#dateFor_file_name = time.strftime("%m-%d-%y")

#dataDate = dateFor_file_name

person = '/home/roxorsox/public_html/poprock/crons/lastFM/data/JoanJett_Person_'
group = '/home/roxorsox/public_html/poprock/crons/lastFM/data/JoanJettandtheBlackhearts_Group_'
combined = '/home/roxorsox/public_html/poprock/crons/lastFM/data/JoanJett_Combined_'
ext = '.json'

personFile = person + dataDate + ext
groupFile = group + dataDate + ext
combinedFile = combined + dataDate + ext

# Open file from which I will take data ("g" for "group")
with open(groupFile, 'r') as g:
    blackheartsJSON = json.load(g)

with open(personFile, 'r') as p:
    joanjettJSON = json.load(p)

with open(combinedFile, 'r') as c:
    combinedJSON = json.load(c)

joanjettListeners = int(joanjettJSON['stats']['listeners'])
print ('JJ has ' + str(joanjettListeners) + ' listeners')

joanjettPlays = int(joanjettJSON['stats']['playcount'])
print ('JJ has ' + str(joanjettPlays) + ' plays')

blackheartsListeners = int(blackheartsJSON['stats']['listeners'])
print ('Blackhearts have ' + str(blackheartsListeners) + ' listeners')

blackheartsPlays = int(blackheartsJSON['stats']['playcount'])
print ('Blackhearts have ' + str(blackheartsPlays) + ' plays')

# Location of combined file stats
#combinedListeners = combinedJSON['stats']['listeners']
#combinedPlays = combinedJSON['stats']['playcount']

# Add person listeners + group listeners
newcombinedListeners = joanjettListeners + blackheartsListeners
newcombinedPlays = joanjettPlays + blackheartsPlays

# Change combined file totals to new sums
#combinedListeners = newcombinedListeners
#combinedPlays = newcombinedPlays
combinedJSON['stats']['listeners'] = newcombinedListeners
combinedJSON['stats']['playcount'] = newcombinedPlays

print ('Combined Listeners is ' + str(newcombinedListeners) + ' total listeners')
print ('Combined Playcount is ' + str(newcombinedPlays) + ' total plays')

with open(combinedFile, 'w') as f:
    f.write(json.dumps(combinedJSON))

f.close
print('File written.')

