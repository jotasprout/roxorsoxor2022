/*
blackSabbath = "5M52tdBnJaKSvOpJGz8mfZ";
*/

/* THIS WORKS AWESOME */

SELECT b.albumName, b.albumMBID, b.albumSpotID, b.artistSpotID, a.year, a.albumArtSpot, a.tracksTotal, z.artistName, p1.pop, p1.date, f1.dataDate, f1.albumListeners, f1.albumPlaycount, x.albumArtMB
FROM (SELECT sp.albumName, sp.albumMBID, sp.albumSpotID, sp.artistSpotID
	FROM albums sp
	WHERE sp.artistSpotID='5M52tdBnJaKSvOpJGz8mfZ'
UNION
SELECT mb.albumName, mb.albumMBID, mb.albumSpotID, mb.artistSpotID
	FROM albumsMB mb 
	WHERE mb.artistSpotID='5M52tdBnJaKSvOpJGz8mfZ') b 
LEFT JOIN albums a ON b.albumSpotID = a.albumSpotID	
JOIN artists z ON z.artistSpotID = b.artistSpotID
LEFT JOIN (SELECT p.*
		FROM popAlbums p
		INNER JOIN (SELECT albumSpotID, pop, max(date) AS MaxDate
					FROM popAlbums  
					GROUP BY albumSpotID) groupedp
		ON p.albumSpotID = groupedp.albumSpotID
		AND p.date = groupedp.MaxDate) p1 
ON a.albumSpotID = p1.albumSpotID
LEFT JOIN albumsMB x ON b.albumMBID = x.albumMBID
LEFT JOIN (SELECT f.*
		FROM albumsLastFM f
		INNER JOIN (SELECT albumMBID, albumListeners, albumPlaycount, max(dataDate) AS MaxDataDate
		FROM albumsLastFM
		GROUP BY albumMBID) groupedf
		ON f.albumMBID = groupedf.albumMBID
		AND f.dataDate = groupedf.MaxDataDate) f1
ON b.albumMBID = f1.albumMBID	
ORDER BY b.albumName ASC;

/* THIS might be better */

SELECT b.albumName, b.albumMBID, b.albumSpotID, b.artistSpotID, a.year, a.albumArtSpot, a.tracksTotal, z.artistName, p1.pop, p1.date, f1.dataDate, f1.albumListeners, f1.albumPlaycount, x.albumArtMB
FROM (SELECT sp.albumName, sp.albumMBID, sp.albumSpotID, sp.artistSpotID
	FROM albums sp
	WHERE sp.artistSpotID='$artistSpotID'
UNION
SELECT mb.albumName, mb.albumMBID, mb.albumSpotID, mb.artistSpotID
	FROM albumsMB mb 
	WHERE mb.artistSpotID='$artistSpotID') b 
LEFT JOIN albums a ON b.albumSpotID = a.albumSpotID	
JOIN artists z ON z.artistSpotID = b.artistSpotID
LEFT JOIN (SELECT p.*
		FROM popAlbums p
		INNER JOIN (SELECT albumSpotID, pop, max(date) AS MaxDate
					FROM popAlbums  
					GROUP BY albumSpotID) groupedp
		ON p.albumSpotID = groupedp.albumSpotID
		AND p.date = groupedp.MaxDate) p1 
ON a.albumSpotID = p1.albumSpotID
LEFT JOIN albumsMB x ON b.albumMBID = x.albumMBID
LEFT JOIN (SELECT f.*
		FROM albumsLastFM f
		INNER JOIN (SELECT albumMBID, albumListeners, albumPlaycount, max(dataDate) AS MaxDataDate
		FROM albumsLastFM
		GROUP BY albumMBID) groupedf
		ON f.albumMBID = groupedf.albumMBID
		AND f.dataDate = groupedf.MaxDataDate) f1
ON b.albumMBID = f1.albumMBID	
ORDER BY b.albumName ASC;
/*
Live Evil = "6AOClmLV3vaZ83kjqXtwrq"
*/
	
/*
THIS ONE WORKS
*/
SELECT t.trackSpotID, t.trackName, a.albumName, a.artistSpotID, p1.pop, p1.date, f1.dataDate, f1.trackListeners, f1.trackPlaycount
	FROM tracks t
	INNER JOIN albums a ON a.albumSpotID = t.albumSpotID
	JOIN (SELECT p.* FROM popTracks p
			INNER JOIN (SELECT trackSpotID, pop, max(date) AS MaxDate
						FROM popTracks  
						GROUP BY trackSpotID) groupedp
			ON p.trackSpotID = groupedp.trackSpotID
			AND p.date = groupedp.MaxDate) p1 
	ON t.trackSpotID = p1.trackSpotID
	LEFT JOIN (SELECT f.*
			FROM tracksLastFM f
			INNER JOIN (SELECT trackMBID, trackListeners, trackPlaycount, max(dataDate) AS MaxDataDate
						FROM tracksLastFM  
						GROUP BY trackMBID) groupedf
			ON f.trackMBID = groupedf.trackMBID
			AND f.dataDate = groupedf.MaxDataDate) f1
	ON t.trackMBID = f1.trackMBID
	WHERE a.albumSpotID = '6AOClmLV3vaZ83kjqXtwrq'
	ORDER BY p1.pop DESC		
	
/*
How about this?
*/

SELECT t.trackSpotID, t.trackName, a.albumName, a.artistSpotID, p1.pop, p1.date, f1.dataDate, f1.trackListeners, f1.trackPlaycount
	FROM tracks t
	INNER JOIN albums a ON a.albumSpotID = t.albumSpotID
	JOIN (SELECT p.* FROM popTracks p
			INNER JOIN (SELECT trackSpotID, pop, max(date) AS MaxDate
						FROM popTracks  
						GROUP BY trackSpotID) groupedp
			ON p.trackSpotID = groupedp.trackSpotID
			AND p.date = groupedp.MaxDate) p1 
	ON t.trackSpotID = p1.trackSpotID
		LEFT JOIN (SELECT f.*
			FROM tracksLastFM f
			INNER JOIN (SELECT trackMBID, trackListeners, trackPlaycount, max(dataDate) AS MaxDataDate
						FROM tracksLastFM  
						GROUP BY trackMBID) groupedf
			ON f.trackMBID = groupedf.trackMBID
			AND f.dataDate = groupedf.MaxDataDate) f1
	ON t.trackMBID = f1.trackMBID
	WHERE a.artistSpotID = '$artistSpotID'
	ORDER BY t.trackName ASC	