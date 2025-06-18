<?php
/**
 * call demo "cli-demo-parameters.php" with different parameters and show its output
 */

// ----------------------------------------------------------------------
// config
// ----------------------------------------------------------------------
$sDemoscript='cli-demo-parameters.php';
$aSets=[
	["-h"                , "call help"],
	["-a index"          , "set action to value index"],
	["--action index"    , "set action to value index with long parameter style"],
	["-a invalidaction"  , "set action to invalid value"],
	["-a index --id 3"   , "set action to value index and an additional id"],
];


// ----------------------------------------------------------------------
// output
// ----------------------------------------------------------------------
echo "\n===== Calls to the demo-cli with different parameters =====\n\n";

$i=0;
foreach ($aSets as $aSet){
	$sCmd='php ./'.$sDemoscript." ".$aSet[0]."\n";
	$i++;
	if ($i>1){
		sleep(3);
	}
	echo "\n". $i.' of '.count($aSets).": " . $aSet[1]."\n"
		. 'parameters : ' . $aSet[0]."\n"
		."\n"
		;
	
	system($sCmd);
	
	echo "\n______________________________________________________________________\n\n";
	
}
echo "end.\n";
