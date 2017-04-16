<?php
/*
 * 
 * C L I  :: D E M O
 * This is an example of your future cli tool with php
 * 
 */
require_once(dirname(__DIR__)."/classes/cli.class.php");

// ----------------------------------------------------------------------
// parameter definitions
// ----------------------------------------------------------------------

$aParamDefs=array(
    'label' => 'C L I - demo script',
    'description' => 'just show a bit the cli class',
    'params'=>array(
        'action'=>array(
            'short' => 'a',
            'value'=> CLIVALUE_REQUIRED,
            'pattern_'=>'/^[a-z]*$/i',
            'pattern'=>'/^(index|updateindex)$/i',
            'shortinfo' => 'name of action',
            'description' => 'The action is one of index | updateindex',
        ),
        'id'=>array(
            'short' => 'i',
            'value'=> CLIVALUE_REQUIRED,
            'pattern'=>'/^[0-9]*$/',
            'shortinfo' => 'profile id of the config',
            'description' => 'The id is an integer value to reference the project.',
        ),
        'help'=>array(
            'short' => 'h',
            'value'=> CLIVALUE_NONE,
            'shortinfo' => 'show help',
            'description' => '',
        ),
    ),
);

// init the class with the config array
$oCli=new axelhahn\cli($aParamDefs);

// show a headline
echo $oCli->getlabel();


// just to see the internals:
// ./demo-cli.php -a index -i 4
echo "----------------------------------------------------------------------\n";
echo "the options I found so far... \n";
$options = $oCli->getopt();
print_r($options);

echo "----------------------------------------------------------------------\n";

// with method getvalue you can check one of the parameters
// getvalue("help") is active if "-h" or "--help" was given
// because we defined params->help and below the help item is the key short
// with value "h"
// $ ./demo-cli.php -h
if ($oCli->getvalue("help")){
    echo $oCli->showhelp();
    exit(0);
}


// add logic for your tool below :-)


// lets try to get the "action". You need to start one of:
// $ ./demo-cli.php -a index
// $ ./demo-cli.php -a updateindex
// $ ./demo-cli.php --action index
// $ ./demo-cli.php --action updateindex
if ($oCli->getvalue("action")){
	echo "action is " . $oCli->getvalue("action") . "\n";
}
