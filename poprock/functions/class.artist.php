<?php

// this needs methods for storing:
//     artistPop
//     artistListeners
//     artistPlaycount

    class artist {

        // Do I need these first three lines?
        var $artistSpotID;
        var $artistNameSpot;
        var $artistPop;

        function __construct ($artistSpotID, $artistNameSpot) {
            $this -> artistSpotID = $artistSpotID;
            $this -> artistNameSpot = $artistNameSpot;
        }

        function setArtistSpotID ($newArtistSpotID) {
            $this -> artistSpotID = $newArtistSpotID;
        }

        function getArtistSpotID () {
            return $this -> artistSpotID;
        }

        function setArtistNameSpot ($newArtistNameSpot) {
            $this -> artistNameSpot = $newArtistNameSpot;
        }

        function getArtistNameSpot () {
            return $this -> artistNameSpot;
        }
		
		function setArtistPop ($newArtistPop) {
            $this -> artistPop = $newArtistPop;
        }

        function getArtistPop () {
            return $this -> artistPop;
        }

    }

?>