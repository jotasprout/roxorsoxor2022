<?php

require_once 'page_pieces/navbar_rock.php';
require_once 'page_pieces/stylesAndScripts.php';

?>

<!doctype html>
<html>
<head>
    <title>Choose Group of Artists to Compare</title>
    <meta charset='UTF-8'> 
    <?php echo $stylesAndSuch; ?>
</head>

<body>

<div class = "container">
<div id="fluidCon">
</div> <!-- end of fluidCon -->
    <form class="form-horizontal" id="addalbums" action="theseArtists_comparePop.php" method="post">
        <fieldset>
                <legend>Choose Group of Artists to Compare</legend>
            <div class="form-group">

                <div class="col-lg-4">
                    <select class="form-control" id="group" name="group">
                        <option value="">--Choose--</option>
                        <option value="rap">Rap</option>
                        <option value="thrashetc">Thrash, etc.</option>
                        <option value="christian">Christian</option>
                    </select>
                </div>   
            </div><!-- /Row 1 -->

<div class="form-group"> <!-- Row 2 -->
    <div class="col-lg-4 col-lg-offset-2">
        <button class="btn btn-primary" type="submit" name="submit">Get Wicked Graph</button>
    </div>
</div><!-- /Row 2 -->
        </fieldset>
    </form>

</div> <!-- end of Container -->
<?php echo $scriptsAndSuch; ?>
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>

</html>

