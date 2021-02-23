<?php

// this needs to access a database by requiring a config file of some sort

    class track {

        var $trackSpotID;
		var $trackAlbum;
        var $trackName;
		var $trackPop;

        function __construct ($track_id) {
            $this -> trackSpotID = $track_id;
        }

        function set_trackSpotID ($new_trackSpotID) {
            $this -> trackSpotID = $new_trackSpotID;
        }

        function get_trackSpotID () {
            return $this -> trackSpotID;
        }
		
        function set_trackAlbum ($new_trackAlbum) {
            $this -> trackAlbum = $new_trackAlbum;
        }

        function get_trackAlbum () {
            return $this -> trackAlbum;
        }		

        function set_trackName ($new_trackName) {
            $this -> trackName = $new_trackName;
        }

        function get_trackName () {
            return $this -> trackName;
        }
		
		function set_trackPop ($new_trackPop) {
            $this -> trackPop = $new_trackPop;
        }

        function get_trackPop () {
            return $this -> trackPop;
        }


    }

?>