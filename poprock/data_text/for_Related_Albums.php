<?php

$related = array (
    '1Fmb52lZ6Jv7FMWXXTPO3K', # Joan Jett
    '5eTq3PxbOh5vgeRXKNqPyV', # Runaways
    '5NhjPre67qjeeQP4KHDHpe', # Evil Stig
    '1inWec2E2UgfzMAhwjgTXe', # Generation X
    '7lzordPuZEXxwt9aoVZYmG', # Billy Idol
    '3k4YA0uPsWc2PuOQlJNpdH', # Hollywood Vampires  
    '3EhbVgyfGd7HkpsagwL9GS', # Alice Cooper 
    '0p9uD4WGPHqMicwXm3Kavk', # Chagall Guevara
    '0t1uzfQspxLvAifZLdmFe2', # Steve Taylor
    '5MQsxr7sbsewUTIEEYxauR', # Steve Taylor and the Perfect Foil
    '0PGxNwykt4KgnvSnNHVUSZ', # Steve Taylor and the Danielson Foil
    '21ysNsPzHdqYN2fQ75ZswG', # Ted Nugent
    '5cVLuEqb7aOHuzwssXHzWI', # Amboy Dukes 
    '6ZLTlhejhndI4Rh53vYhrY', # Ozzy
    '4UjiBRkTw9VmvDZiJZKPJ7', # Heaven & Hell
    '4CYeVo5iZbtYGBN4Isc3n6', # Dio
    '5M52tdBnJaKSvOpJGz8mfZ', # Black Sabbath
    '6SLAMfhOi7UJI0fMztaK0m', # Rainbow
    '2UZMlIwnkgAEDBsw1Rejkn', # Tom Petty
    '4tX2TplrkIP4v05BNC903e', # Tom Petty & the Heartbreakers
    '2hO4YtXUFJiUYS2uYFvHNK', # Traveling Wilburys
    '33EUXrFKGjpUSGacqEHhU4', # Iggy Pop
    '1l8grPt6eiOS4YlzjIs0LF', # Iggy Pop & James Williamson
    '4BFMTELQyWJU1SwqcXMBm3', # Stooges
    '2y8Jo9CKhJvtfeKOsYzRdT', # Rod Stewart 
    '3v4feUQnU3VEUqFrjmtekL', # Faces
    '20ge6LnG4KYzVj0Ecj7yDv', # Michael Knott
    '0yOXtSWTf2JZEbeBXD4wZa', # Mike Knott
    '4VCZkmckTZMDFU0WsaepBe', # L.S.U.
    '1p8SnxXwTByLcpQrCZ9c2J', # L.S.U. (Lifesavers Underground)
    '6sYiiBcFhVF76DR2BlRfcd', # Life Savers
    '0HdS9FxnRaiyuBjHBDo4vW'  # Aunt Betty's
);

$michaelKnottForRelated = '20ge6LnG4KYzVj0Ecj7yDv'; # Michael Knott
$steveTaylorForRelated = '0t1uzfQspxLvAifZLdmFe2'; # Steve Taylor
$iggyPopForRelated = '33EUXrFKGjpUSGacqEHhU4'; # Iggy Pop
$dioForRelated = '4CYeVo5iZbtYGBN4Isc3n6'; # Dio
$tomPettyForRelated = '2UZMlIwnkgAEDBsw1Rejkn'; # Tom Petty
$ozzyForRelated = '6ZLTlhejhndI4Rh53vYhrY'; # Ozzy
$tedNugentForRelated = '21ysNsPzHdqYN2fQ75ZswG'; # Ted Nugent
$joanJettForRelated = '1Fmb52lZ6Jv7FMWXXTPO3K'; # Joan Jett
$billyIdolForRelated = '7lzordPuZEXxwt9aoVZYmG'; # Billy Idol
$aliceCooperForRelated = '3EhbVgyfGd7HkpsagwL9GS'; # Alice Cooper

$allbutrelated = "UPDATE relatedAlbums SET relatedArtistID = '33EUXrFKGjpUSGacqEHhU4' WHERE artistID = '4BFMTELQyWJU1SwqcXMBm3'";


$allbutrelated = "DELETE FROM relatedAlbums WHERE artistID NOT IN ('1Fmb52lZ6Jv7FMWXXTPO3K', '5eTq3PxbOh5vgeRXKNqPyV', '5NhjPre67qjeeQP4KHDHpe', '1inWec2E2UgfzMAhwjgTXe', '7lzordPuZEXxwt9aoVZYmG', '3k4YA0uPsWc2PuOQlJNpdH', '3EhbVgyfGd7HkpsagwL9GS', '0p9uD4WGPHqMicwXm3Kavk', '0t1uzfQspxLvAifZLdmFe2', '5MQsxr7sbsewUTIEEYxauR', '0PGxNwykt4KgnvSnNHVUSZ', '21ysNsPzHdqYN2fQ75ZswG', '5cVLuEqb7aOHuzwssXHzWI', '6ZLTlhejhndI4Rh53vYhrY', '4UjiBRkTw9VmvDZiJZKPJ7', '4CYeVo5iZbtYGBN4Isc3n6', '5M52tdBnJaKSvOpJGz8mfZ', '6SLAMfhOi7UJI0fMztaK0m', '2UZMlIwnkgAEDBsw1Rejkn', '4tX2TplrkIP4v05BNC903e', '2hO4YtXUFJiUYS2uYFvHNK', '33EUXrFKGjpUSGacqEHhU4', '1l8grPt6eiOS4YlzjIs0LF', '4BFMTELQyWJU1SwqcXMBm3', '2y8Jo9CKhJvtfeKOsYzRdT', '3v4feUQnU3VEUqFrjmtekL', '20ge6LnG4KYzVj0Ecj7yDv', '0yOXtSWTf2JZEbeBXD4wZa', '4VCZkmckTZMDFU0WsaepBe', '1p8SnxXwTByLcpQrCZ9c2J', '6sYiiBcFhVF76DR2BlRfcd', '0HdS9FxnRaiyuBjHBDo4vW')";

?>