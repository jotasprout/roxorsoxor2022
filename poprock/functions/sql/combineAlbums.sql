SELECT albums.albumID, albums.albumName, albumsMB.albumName, albumsMB.albumMBID
FROM albums
LEFT JOIN albumsMB
ON albumsMB.artistSpotID = albums.artistID AND albums.albumName = albumsMB.albumName
# Above gets 5,742 rows (because it includes albums with no match?)
# Changing it to INNER JOIN gets 231 rows

UPDATE albumsMB
INNER JOIN albums ON albumsMB.artistSpotID = albums.artistID
SET albumsMB.artistSpotID = artists.artistID
WHERE albumsMB.artistMBID = artists.artistMBID

SELECT m.albumName, m.artistSpotID, a.artistName, a.artistID 
FROM `albumsMB` m 
JOIN artists a ON a.artistMBID = m.artistMBID 
ORDER BY `artistSpotID` ASC
# The above returns 486 rows
# There are 524 albums in albumsMB

SELECT albums.albumID, albums.albumName, albumsMB.albumName, albumsMB.albumMBID FROM albums LEFT JOIN albumsMB ON albums.albumName = albumsMB.albumName
# Above returns 5,842 rows

UPDATE albums
INNER JOIN albumsMB ON albums.artistID = albumsMB.artistSpotID AND albums.albumName = albumsMB.albumName
SET albums.albumMBID = albumsMB.albumMBID
WHERE albums.artistID = albumsMB.artistSpotID AND albums.albumName = albumsMB.albumName
# Above affected 231 rows


#########################################


UPDATE tracks 
INNER JOIN (SELECT tracksMB.trackMBID, tracksMB.trackName, tracksMB.albumMBID, albums.albumID
FROM tracksMB
JOIN albums ON tracksMB.albumMBID = albums.albumMBID) tmb ON tmb.trackName = tracks.trackName AND tmb.albumID = tracks.albumID
SET tracks.trackMBID = tmb.trackMBID
WHERE tmb.trackName = tracks.trackName AND tmb.albumID = tracks.albumID
# Above 1649 rows affected. I sure do hope they are correct.