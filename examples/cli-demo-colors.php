<?php
/*
 * 
 * C L I  :: D E M O
 * This is an example of your future cli tool with php
 * 
 */
require_once(dirname(__DIR__)."/classes/cli.class.php");

// ----------------------------------------------------------------------
// color definitions
// ----------------------------------------------------------------------

$aColor=array(
	'reset' => array('reset', null),
	'head' => array('cyan', null),
	'input' => array('light green', null),
	'cli' => array('light blue', null),

	'ok' => array('black', 'green'),
	'info' => array('black', 'yellow'),
	'warning' => array('black', 'yellow'),
	'error' => array('white', 'red', null),
);

// ----------------------------------------------------------------------
// function
// ----------------------------------------------------------------------

function colortest(){
		global $aColor;
		global $oCli;
		foreach (array_keys($aColor) as $sKey){
				$oCli->color($sKey, 'test message with $oCli->color("'.$sKey.'");');
				echo "\n";
		}
}

// ----------------------------------------------------------------------
// main
// ----------------------------------------------------------------------

// init the class with the config array
$oCli=new axelhahn\cli();

echo "
_______________________________________________________________________________

COLOR TEST ....
_______________________________________________________________________________

with the color method \$oCli->color([type]) you can set a color based on the 
type of information. It is useful to handle light and dark terminal backgrounds
or customize 


Let's start with default colors...
\n";

// show a headline
colortest();
echo "\n
And now set a new theme.
\$oCli->addTheme(\$aColor, 'mytheme');

And try again:
 
";

$oCli->addTheme($aColor, 'mytheme');
colortest();

// ----------------------------------------------------------------------
