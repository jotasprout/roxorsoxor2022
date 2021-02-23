<?php

    class album {

        public function insert_album($albumSpotID,$albumName,$albumReleased,$thisartistSpotID) {

            try {
                $stmt = $rock->conn->prepare("INSERT INTO albums (albumSpotID,albumName,albumReleased,thisartistSpotID) VALUES(:albumSpotID, :albumName, :albumReleased, :thisartistSpotID)");
                $stmt->bindparam(":albumSpotID",$albumSpotID);
                $stmt->bindparam(":albumName",$albumName);
                $stmt->bindparam(":albumReleased",$albumReleased);
                $stmt->bindparam(":thisartistSpotID",$thisartistSpotID);
                $stmt->execute();
                return $stmt;
            }
    
            // should not ex be exception like it is in user class? Make all of this consistent
            catch(PDOException $ex) {
                echo $ex->getMessage();
            }
        }
        
        // Do these variables need to be declared here? Or are below functions enough? What about variables in albums file? And that file should be combined with this, correct?
        var $albumSpotID;
		var $albumArtSpotist;
        var $albumName;
		var $albumReleased;
		var $albumPop;

        public function __construct () {
        }

//        public function __construct ($thisalbumSpotID) {
//            $this -> albumSpotID = $thisalbumSpotID;
//        }

        function set_albumSpotID ($new_albumSpotID) {
            $this -> albumSpotID = $new_albumSpotID;
        }

        function get_albumSpotID () {
            return $this -> albumSpotID;
        }

        function set_albumName ($new_albumName) {
            $this -> albumName = $new_albumName;
        }

        function get_albumName () {
            return $this -> albumName;
        }
		
		function set_albumPop ($new_albumPop) {
            $this -> albumPop = $new_albumPop;
        }

        function get_albumPop () {
            return $this -> albumPop;
        }
		
		function set_albumArtSpotist ($new_albumArtSpotist) {
            $this -> albumArtSpotist = $new_albumArtSpotist;
        }

        function get_albumArtSpotist () {
            return $this -> albumArtSpotist;
        }
		
		function set_albumReleased ($new_albumReleased) {
            $this -> albumalbumReleased = $new_albumReleased;
        }

        function get_albumReleased () {
            return $this -> albumReleased;
        }

    }

?>