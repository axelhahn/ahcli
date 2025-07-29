<?php
/*
 * 
 * C L I  :: D E M O
 * This is an example of your future cli tool with php
 * 
 */
require_once(dirname(__DIR__)."/classes/cli.class.php");
$oCli=new axelhahn\cli();

echo "
_______________________________________________________________________________

TAB COMPLETION TEST ....
_______________________________________________________________________________

With the color method \$oCli->setCompletions(<ARRAY>) you can define a set of
values that will be displayed when the user presses tab.

";
$oCli->setCompletions(['apple', 'banana', 'orange']);
$s=$oCli->_cliInput('what is your favorite fruit?', false);
echo "Value was: '$s'\n";

// check if tab completion was removed:
$s=$oCli->_cliInput('Enter somethin else', false);
echo "Value was: '$s'\n";
