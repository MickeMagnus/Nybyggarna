<?php

$MAP_WIDTH = 7;
$MAP_HEIGHT = 7;
$HEX_HEIGHT = 72;

$HEX_SCALED_HEIGHT = $HEX_HEIGHT * 1.0;
$HEX_SIDE = $HEX_SCALED_HEIGHT / 2;

$terrain_images = array("grass"    => "pictures/grass-r1.png",
						"dirt"     => "pictures/dirt.png",
						"water"    => "pictures/coast.png",
						"path"     => "pictures/stone-path.png",
						"swamp"    => "pictures/water-tile.png",
						"desert"   => "pictures/desert.png",
						"oasis"    => "pictures/desert-oasis-tile.png",
						"forest"   => "pictures/forested-mixed-summer-hills-tile.png",
						"hills"    => "pictures/hills-variation3.png",
						"mountain" => "pictures/mountain-tile.png",
						"field"	   => "pictures/savanna2.png",
						"plain"    => "pictures/vete.png",
						"black"    => "pictures/black.png");

function generate_map_data() {
	global $MAP_WIDTH, $MAP_HEIGHT;
	global $map, $terrain_images;
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
}

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

<html>
    <head>
        <title>Nybyggarna</title>
        <script src="jscore.js"></script>
        <LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>
    </head>
    <body>
    
    <div id='hexmap' class='hexmap' onclick='handle_map_click(event,<?php print $HEX_SCALED_HEIGHT ?>,<?php print $HEX_SIDE ?>);'>
        <?php render_map_to_html(); ?>
        <img id='highlight' class='hex' src='pictures/hex-highlight.png' style='zindex:100;'>
    </div>
</body>
</html>