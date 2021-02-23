<?php

require_once '../../rockdb.php';

function assembleURL ($artistForURL) {
    $baseURL = 'data/';
    $today = date("m/d/y");
    $endURL = '.json';
	$artistURL = $baseURL . $artistForURL . "_" . $today . $endURL;
	echo "<p>" . $artistURL . "</p>";
};

$artistNames = array (
    '2Pac_Person',
    'AliceCooper_Combined',
    'Anvil_Group',
    'BlackSabbath_Group',
    'DavidBowie_Person',
    'LindseyBuckingham_Person',
    'Eminem_Person',
    'Cream_Group',
    'DefLeppard_Group',
    'Dio_Group', 
    'Elf_Group', 
    'EricClapton_Person',
    'EvilStig_Group', 
    'FleetwoodMac_Group',
    'Heaven&Hell_Group', 
    'IggyandTheStooges_Group',
    'JanetJackson_Person', 
    'JimmyPage_Person',
    'JimmyPage&RobertPlant_Group',
    'JoanJett_Combined', 
    'Journey_Group', 
    'LedZeppelin_Group',
    'MeatLoaf_Person', 
    'MötleyCrüe_Group', 
    'NeilYoung_Person',
    'StevieNicks_Person',
    'OzzyOsbourne_Person', 
    'RobertPlant_Person',
    'IggyPop_Person',
    'Queen_Group', 
    'QuietRiot_Group', 
    'Radiohead_Group',
    'Rainbow_Group', 
    'RonnieDioandtheProphets_Group', 
    'RonnieDioandtheRedCaps_Group', 
    'RoxyMusic_Group',
    'Saxon_Group', 
    'Stoney&Meatloaf_Group',
    'TedNugent_Person', 
    'TheAmboyDukes_Group',
    'TheCure_Group',
    'TheElectricElves_Group', 
    'TheFirm_Group',
    'TheRunaways_Group',
    'TheStooges_Group',
    'TheYardbirds_Group',
    'TheZombies_Group',
    'ToddRundgren_Person',
    'Utopia_Group'
);

$filenames_01 = array (	
    'data/AliceCooper_Combined_12-08-19.json',
    'data/TheAmboyDukes_Group_12-08-19.json',
    'data/EvilStig_Group_12-08-19.json', 
    'data/JoanJett_Combined_12-08-19.json', 
	'data/TheRunaways_Group_12-08-19.json',
    'data/TedNugent_Person_12-08-19.json', 
    'data/DavidBowie_Person_12-08-19.json',
    'data/JanetJackson_Person_12-08-19.json',
    'data/12thTribe_Group_12-08-19.json',
    'data/ArgylePark_Group_12-08-19.json',
    'data/BarrenCross_Group_12-08-19.json',
    'data/Believer_Group_12-08-19.json',
    'data/GeorgeClinton_Person_12-08-19.json',
    'data/Funkadelic_Group_12-08-19.json',
    'data/Parliament-Funkadelic_Group_12-08-19.json',
    'data/Parliament_Group_12-08-19.json',
    'data/GraceJones_Person_12-08-19.json',
    'data/JudasPriest_Group_12-08-19.json',
    'data/DepecheMode_Group_12-08-19.json',
    'data/Motörhead_Group_12-08-19.json',
    'data/T.Rex_Group_12-08-19.json',
    'data/Kraftwerk_Group_12-08-19.json',
    'data/Rufus_Group_12-08-19.json',
    'data/MC5_Group_12-08-19.json',
    'data/DaveMatthewsBand_Group_12-08-19.json',
    'data/PatBenatar_Person_12-08-19.json',
    'data/DoobieBrothers_Group_12-08-19.json',
    'data/Soundgarden_Group_12-08-19.json',
    'data/WhitneyHouston_Person_12-08-19.json',
    'data/ThinLizzy_Group_12-08-19.json',
    'data/NineInchNails_Group_12-08-19.json',
    'data/NotoriousB.I.G._Person_12-08-19.json',
    'data/DeepPurple_Group_12-08-19.json'	
);

$artistNames_01 = array (
	'AliceCooper_Combined',
	'EvilStig_Group',
	'JoanJett_Combined',
	'TheRunaways_Group',
    'TheAmboyDukes_Group',
	'TedNugent_Person', 
    'JanetJackson_Person',
    '12thTribe_Group',
    'ArgylePark_Group',
    'BarrenCross_Group',
	'Believer_Group',
	'GeorgeClinton_Person',
	'Funkadelic_Group',
	'Parliament-Funkadelic_Group',
	'Parliament_Group',
    'GraceJones_Person',
    'JudasPriest_Group',
    'DepecheMode_Group',
    'Motörhead_Group',
    'T.Rex_Group',
    'Kraftwerk_Group',
    'Rufus_Group',
    'MC5_Group',
    'DaveMatthewsBand_Group',
    'PatBenatar_Person',
    'DoobieBrothers_Group',
    'Soundgarden_Group',
    'WhitneyHouston_Person',
    'ThinLizzy_Group',
    'NineInchNails_Group',
    'NotoriousB.I.G._Person',
    'DeepPurple_Group'
);

$filenames_02 = array (	
    'data/Anvil_Group_12-09-19.json',
    'data/LindseyBuckingham_Person_12-09-19.json',
    'data/TheCure_Group_12-09-19.json',
    'data/Eminem_Person_12-09-19.json',
    'data/FleetwoodMac_Group_12-09-19.json',
    'data/StevieNicks_Person_12-09-19.json',
    'data/Radiohead_Group_12-09-19.json',
    'data/BuckinghamNicks_Group_12-09-19.json',
    'data/Bloodgood_Group_12-09-19.json',
    'data/BobDylan_Person_12-09-19.json',
    'data/Bride_Group_12-09-19.json',
    'data/CannibalCorpse_Group_12-09-19.json',
    'data/ChagallGuevara_Group_12-09-19.json',
    'data/CircleofDust_Group_12-09-19.json',
    'data/Crashdog_Group_12-09-19.json',
    'data/Deitiphobia_Group_12-09-19.json',
    'data/Deliverance_Group_12-09-19.json'
);

$artistNames_02 = array (
    'Anvil_Group',
    'LindseyBuckingham_Person',
    'BuckinghamNicks_Group',
	'TheCure_Group',
    'Eminem_Person',
    'FleetwoodMac_Group',
    'StevieNicks_Person',
    'Radiohead_Group',
    'Bloodgood_Group',
    'BobDylan_Person',
    'Bride_Group',
    'CannibalCorpse_Group',
    'ChagallGuevara_Group',
    'CircleofDust_Group',
    'Crashdog_Group',
    'Deitiphobia_Group',
    'Deliverance_Group'
);

$filenames_03 = array (
    'data/BlackSabbath_Group_12-10-19.json',
    'data/Dio_Group_12-10-19.json', 
    'data/Elf_Group_12-10-19.json', 
    'data/TheElectricElves_Group_12-10-19.json', 
    'data/Heaven&Hell_Group_12-10-19.json', 
    'data/OzzyOsbourne_Person_12-10-19.json', 
	'data/Rainbow_Group_12-10-19.json',
    'data/RonnieDioandtheProphets_Group_12-10-19.json', 
    'data/RonnieDioandtheRedCaps_Group_12-10-19.json',
    'data/RonnieJamesDio_Person_12-10-19.json',
	'data/RogerGlover_Person_12-10-19.json',
    'data/KerryLivgren_Person_12-10-19.json',
    'data/DisciplesofChrist_Group_12-10-19.json',
    'data/E-Roc_Person_12-10-19.json',
    'data/BillyIdol_Person_12-10-19.json'	
);

$artistNames_03 = array (
    'Dio_Group', 
    'RonnieDioandtheProphets_Group',
    'RonnieDioandtheRedCaps_Group',
    'TheElectricElves_Group',
    'Elf_Group',
    'Heaven&Hell_Group',
    'Rainbow_Group',
	'RonnieJamesDio_Person',
	'RogerGlover_Person',
	'KerryLivgren_Person',
    'OzzyOsbourne_Person',
    'BlackSabbath_Group',
    'DisciplesofChrist_Group',
    'E-Roc_Person',
    'BillyIdol_Person'
);


$filenames_07 = array (
    'data/XLandDeathBeforeDishonor_None_12-07-19.json', 
    'data/Trytan_Group_12-07-19.json', 
    'data/VeilofAshes_Group_12-07-19.json', 
    'data/VengeanceRising_Group_12-07-19.json', 
    'data/VeniDomine_Group_12-07-19.json', 
    'data/Whitecross_Group_12-07-19.json', 
    'data/Cream_Group_12-07-19.json',
    'data/EricClapton_Person_12-07-19.json',
    'data/RoxyMusic_Group_12-07-19.json',
    'data/Saxon_Group_12-07-19.json', 
    'data/NeilYoung_Person_12-07-19.json',
    'data/Crosby,Stills,Nash&Young_Group_12-07-19.json',
    'data/BuffaloSpringfield_Group_12-07-19.json',
    'data/ScaterdFew_Group_12-07-19.json',  
    'data/SeventhAngel_Group_12-07-19.json', 
	'data/TheZombies_Group_12-07-19.json'
);

$artistNames_07 = array (
	'Cream_Group',
	'EricClapton_Person',
	'RoxyMusic_Group',
    'Saxon_Group',
	'NeilYoung_Person',
	'Crosby,Stills,Nash&Young_Group',
	'BuffaloSpringfield_Group',
	'TheZombies_Group',
    'Trytan_Group', 
    'VeilofAshes_Group', 
    'VengeanceRising_Group', 
    'VeniDomine_Group', 
    'Whitecross_Group', 
    'XLandDeathBeforeDishonor_None',     
    'ScaterdFew_Group',  
    'SeventhAngel_Group'  
);

$filenames = array (
    'data/Anvil_Group_12-09-19.json',
    'data/LindseyBuckingham_Person_12-09-19.json',
    'data/TheCure_Group_12-09-19.json',
    'data/Eminem_Person_12-09-19.json',
    'data/FleetwoodMac_Group_12-09-19.json',
    'data/StevieNicks_Person_12-09-19.json',
    'data/Radiohead_Group_12-09-19.json',
    'data/BuckinghamNicks_Group_12-09-19.json',
    'data/Bloodgood_Group_12-09-19.json',
    'data/BobDylan_Person_12-09-19.json',
    'data/Bride_Group_12-09-19.json',
    'data/CannibalCorpse_Group_12-09-19.json',
    'data/ChagallGuevara_Group_12-09-19.json',
    'data/CircleofDust_Group_12-09-19.json',
    'data/Crashdog_Group_12-09-19.json',
    'data/Deitiphobia_Group_12-09-19.json',
    'data/Deliverance_Group_12-09-19.json'	
);

$filenames = $filenames;

$x = ceil((count($filenames)));
/*
$y = ceil((count($artistNames)));

for ($j=0; $j<$y; ++$j){
	assembleURL ($artistNames[$j]);
};
*/
for ($i=0; $i<$x; ++$i) {

    $jsonFile = $filenames[$i];
    $fileContents = file_get_contents($jsonFile);
	
    $artistData = json_decode($fileContents,true);

    $artistMBID = $artistData['mbid'];
    $artistNameMB = $artistData['name'];

    $dataDate = $artistData['date'];

    $albums = $artistData['albums'];

    $albumsNum = ceil((count($albums)));

    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

    if(!$connekt){
        echo 'Fiddlesticks! Could not connect to database.<br>';
    } else {

        for ($j=0; $j<$albumsNum; ++$j) {
            $album = $albums[$j];
            $releases = $album['releases'];
            $releasesNum = ceil((count($releases)));
            if ($releasesNum > 0){
                $releaseMBID = $album['releases'][0]['mbid'];
                $releaseNameYucky = $album['releases'][0]['name'];
                $releaseName = mysqli_real_escape_string($connekt,$releaseNameYucky);
                $albumListeners = $album['releases'][0]['listeners'];
                $albumPlaycount = $album['releases'][0]['playcount'];
				
				$insertLastFMalbumData = "INSERT INTO albumsLastFM (
					albumMBID, 
					dataDate,
					albumListeners,
					albumPlaycount
					) 
					VALUES(
						'$releaseMBID',
						'$dataDate',
						'$albumListeners',
						'$albumPlaycount'
					)";	
				
				$insertReleaseStats = $connekt->query($insertLastFMalbumData);
    
                if(!$insertReleaseStats){
                    echo '<p>Shickety Brickety! Could not insert ' . $releaseName . ' stats.</p>';
                } else {
                    echo '<p>' . $releaseName . ' had ' . $albumListeners . ' listeners and ' . $albumPlaycount . ' plays on ' . $dataDate . '.</p>';
                }
				
            }
        };
    };
};

?>