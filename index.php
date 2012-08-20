<html>
<head><title>Nybyggarna</title></head>
<body>
<?php
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :: HEX.PHP
// ::
// :: Author:  
// ::    Tim Holt, tim.m.holt@gmail.com
// :: Description:  
// ::    Generates a random hex map from a set of terrain types, then
// ::    outputs HTML to display the map.  Also outputs Javascript
// ::    to handle mouse clicks on the map.  When a mouse click is
// ::    detected, the hex cell clicked is determined, and then the
// ::    cell is highlighted.
// :: Usage Restrictions:  
// ::    Available for any use.
// :: Notes:
// ::    Some content (where noted) copied and/or derived from other 
// ::    sources.
// ::    Images used in this example are from the game Wesnoth.
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

// --- Turn up error reporting in PHP
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// --- Define some constants
$MAP_WIDTH = 7;
$MAP_HEIGHT = 7;
$HEX_HEIGHT = 72;

// --- Use this to scale the hexes smaller or larger than the actual graphics
$HEX_SCALED_HEIGHT = $HEX_HEIGHT * 1.0;
$HEX_SIDE = $HEX_SCALED_HEIGHT / 2;
?>
<html>
    <head>
        <title>Hex Map Demo</title>
        <!-- Stylesheet to define map boundary area and hex style -->
        <style type="text/css">
        body {
            /* 
            margin: 0;
            padding: 0;
            */
        }

        .hexmap {
            width: <?php echo $MAP_WIDTH * $HEX_SIDE * 1.5 + $HEX_SIDE/2; ?>px;
            height: <?php echo $MAP_HEIGHT * $HEX_SCALED_HEIGHT + $HEX_SIDE; ?>px;
            position: relative;
            background: #000;
        }

        .hex-key-element {
            width: <?php echo $HEX_HEIGHT * 1.5; ?>px;
            height: <?php echo $HEX_HEIGHT * 1.5; ?>px;
            border: 1px solid #fff;
            float: left;
            text-align: center;
        }

        .hex {
            position: absolute;
            width: <?php echo $HEX_SCALED_HEIGHT ?>;
            height: <?php echo $HEX_SCALED_HEIGHT ?>;
        }
        </style>
    </head>
    <body>
    <script>

function handle_map_click(event) {
    // ----------------------------------------------------------------------
    // --- This function gets a mouse click on the map, converts the click to
    // --- hex map coordinates, then moves the highlight image to be over the
    // --- clicked on hex.
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // --- Determine coordinate of map div as we want the click coordinate as
    // --- we want the mouse click relative to this div.
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // --- Code based on http://www.quirksmode.org/js/events_properties.html
    // ----------------------------------------------------------------------
    var posx = 0;
    var posy = 0;
    if (event.pageX || event.pageY) {
        posx = event.pageX;
        posy = event.pageY;
    } else if (event.clientX || e.clientY) {
        posx = event.clientX + document.body.scrollLeft
            + document.documentElement.scrollLeft;
        posy = event.clientY + document.body.scrollTop
            + document.documentElement.scrollTop;
    }
    // --- Apply offset for the map div
    var map = document.getElementById('hexmap');
    posx = posx - map.offsetLeft;
    posy = posy - map.offsetTop;
    //console.log ("posx = " + posx + ", posy = " + posy);

    // ----------------------------------------------------------------------
    // --- Convert mouse click to hex grid coordinate
    // --- Code is from http://www-cs-students.stanford.edu/~amitp/Articles/GridToHex.html
    // ----------------------------------------------------------------------
    var hex_height = <?php echo $HEX_SCALED_HEIGHT; ?>;
    x = (posx - (hex_height/2)) / (hex_height * 0.75);
    y = (posy - (hex_height/2)) / hex_height;
    z = -0.5 * x - y;
    y = -0.5 * x + y;

    ix = Math.floor(x+0.5);
    iy = Math.floor(y+0.5);
    iz = Math.floor(z+0.5);
    s = ix + iy + iz;
    if (s) {
        abs_dx = Math.abs(ix-x);
        abs_dy = Math.abs(iy-y);
        abs_dz = Math.abs(iz-z);
        if (abs_dx >= abs_dy && abs_dx >= abs_dz) {
            ix -= s;
        } else if (abs_dy >= abs_dx && abs_dy >= abs_dz) {
            iy -= s;
        } else {
            iz -= s;
        }
    }

    // ----------------------------------------------------------------------
    // --- map_x and map_y are the map coordinates of the click
    // ----------------------------------------------------------------------
    map_x = ix;
    map_y = (iy - iz + (1 - ix %2 ) ) / 2 - 0.5;

    // ----------------------------------------------------------------------
    // --- Calculate coordinates of this hex.  We will use this
    // --- to place the highlight image.
    // ----------------------------------------------------------------------
    tx = map_x * <?php echo $HEX_SIDE ?> * 1.5;
    ty = map_y * <?php echo $HEX_SCALED_HEIGHT ?> + (map_x % 2) * (<?php echo $HEX_SCALED_HEIGHT ?> / 2);

    // ----------------------------------------------------------------------
    // --- Get the highlight image by ID
    // ----------------------------------------------------------------------
    var highlight = document.getElementById('highlight');

    // ----------------------------------------------------------------------
    // --- Set position to be over the clicked on hex
    // ----------------------------------------------------------------------
    highlight.style.left = tx + 'px';
    highlight.style.top = ty + 'px';
}
</script>
<?php

// ----------------------------------------------------------------------
// --- This is a list of possible terrain types and the
// --- image to use to render the hex.
// ----------------------------------------------------------------------
    $terrain_images = array("grass"    => "grass-r1.png",
                            "dirt"     => "dirt.png",
                            "water"    => "coast.png",
                            "path"     => "stone-path.png",
                            "swamp"    => "water-tile.png",
                            "desert"   => "desert.png",
                            "oasis"    => "desert-oasis-tile.png",
                            "forest"   => "forested-mixed-summer-hills-tile.png",
                            "hills"    => "hills-variation3.png",
                            "mountain" => "mountain-tile.png",
							"field"	   => "savanna2.png",
							"plain"    => "vete.png",
							"black"    => "black.png");

    // ==================================================================
    function generate_map_data() {
        // -------------------------------------------------------------
        // --- Fill the $map array with values identifying the terrain
        // --- type in each hex.  This example simply randomizes the
        // --- contents of each hex.  Your code could actually load the
        // --- values from a file or from a database.
        // -------------------------------------------------------------
        global $MAP_WIDTH, $MAP_HEIGHT;
        global $map, $terrain_images;
        //for ($x=0; $x<$MAP_WIDTH; $x++) {
            //for ($y=0; $y<$MAP_HEIGHT; $y++) {
                // --- Randomly choose a terrain type from the terrain
                // --- images array and assign to this coordinate.
				//$map[$x][$y] = array_rand($terrain_images);
				//$map[1][1] = $terrain_images["water"];
				$map[0][0] = "water";
				$map[0][1] = "water";
				$map[0][2] = "water";
				$map[0][3] = "water";
				$map[0][4] = "water";
				$map[0][5] = "water";
				$map[0][6] = "water";

				$map[1][0] = "water";
				$map[1][1] = "water";
				$map[1][2] = "oasis";
				$map[1][3] = "oasis";
				$map[1][4] = "forest";
				$map[1][5] = "water";
				$map[1][6] = "water";
				
				$map[2][0] = "water";
				$map[2][1] = "water";
				$map[2][2] = "forest";
				$map[2][3] = "plain";
				$map[2][4] = "mountain";
				$map[2][5] = "plain";
				$map[2][6] = "water";
				
				$map[3][0] = "water";
				$map[3][1] = "plain";
				$map[3][2] = "mountain";
				$map[3][3] = "desert";
				$map[3][4] = "dirt";
				$map[3][5] = "forest";
				$map[3][6] = "water";
				
				$map[4][0] = "water";
				$map[4][1] = "water";
				$map[4][2] = "oasis";
				$map[4][3] = "forest";
				$map[4][4] = "mountain";
				$map[4][5] = "plain";
				$map[4][6] = "water";
				
				$map[5][0] = "water";
				$map[5][1] = "water";
				$map[5][2] = "dirt";
				$map[5][3] = "oasis";
				$map[5][4] = "dirt";
				$map[5][5] = "water";
				$map[5][6] = "water";
				
				$map[6][0] = "water";
				$map[6][1] = "water";
				$map[6][2] = "water";
				$map[6][3] = "water";
				$map[6][4] = "water";
				$map[6][5] = "water";
				$map[6][6] = "water";


				//$map[1][1] = array_rand($terrain_images);
			//}	
        //}
    }

    // ==================================================================

    function render_map_to_html() {
        // -------------------------------------------------------------
        // --- This function renders the map to HTML.  It uses the $map
        // --- array to determine what is in each hex, and the 
        // --- $terrain_images array to determine what type of image to
        // --- draw in each cell.
        // -------------------------------------------------------------
        global $MAP_WIDTH, $MAP_HEIGHT;
        global $HEX_HEIGHT, $HEX_SCALED_HEIGHT, $HEX_SIDE;
        global $map, $terrain_images;

        // -------------------------------------------------------------
        // --- Draw each hex in the map
        // -------------------------------------------------------------
        for ($x=0; $x<$MAP_WIDTH; $x++) {
            for ($y=0; $y<$MAP_HEIGHT; $y++) {
                // --- Terrain type in this hex
                $terrain = $map[$x][$y];

                // --- Image to draw
                $img = $terrain_images[$terrain];

                // --- Coordinates to place hex on the screen
                $tx = $x * $HEX_SIDE * 1.5;
                $ty = $y * $HEX_SCALED_HEIGHT + ($x % 2) * $HEX_SCALED_HEIGHT / 2;

                // --- Style values to position hex image in the right location
                $style = sprintf("left:%dpx;top:%dpx", $tx, $ty);

                // --- Output the image tag for this hex
                print "<img src='$img' alt='$terrain' class='hex' style='zindex:99;$style'>\n";
            }
        }
    }

    // -----------------------------------------------------------------
    // --- Generate the map data
    // -----------------------------------------------------------------
    generate_map_data();
    ?>

<!--    <h1>Hex Map Example</h1>
    <a href='index.phps'>View page source</a><br/>
    <a href='hexmap.zip'>Download source and all images</a>-->

    <!-- Render the hex map inside of a div block -->
    <div id='hexmap' class='hexmap' onclick='handle_map_click(event);'>
        <?php render_map_to_html(); ?>
        <img id='highlight' class='hex' src='hex-highlight.png' style='zindex:100;'>
    </div>

    <!--- output a list of all terrain types -->
    <br/>
  <?php 
        //reset ($terrain_images);
        //while (list($type, $img) = each($terrain_images)) {
        //    print "<div class='hex-key-element'><img src='$img' alt='$type'><br/>$type</div>";
        //}
?>
</body>
</html>