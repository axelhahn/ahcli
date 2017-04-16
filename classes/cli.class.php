<?php

namespace axelhahn;

define("CLIVALUE_REQUIRED", 1);
define("CLIVALUE_OPTIONAL", 2);
define("CLIVALUE_NONE", 3);

/**
 * C L I handler class
 * 
 * Class to handle command line argments. 
 * In a config array you define long and short parameters and pattern for 
 * needed values.
 * 
 * specialties:
 * - short parameter will be merged to long version value.
 * - parameters can be checked by given pattern
 * - a simple help is generated
 * - interactive input for a variable (with optional pattern check)
 * 
 * @package cli
 * @version 1.0
 * @author Axel Hahn (https://www.axel-hahn.de/)
 * @license GNU GPL v 3.0
 * @link https://github.com/axelhahn/ahcli
 */
class cli {

    // ----------------------------------------------------------------------
    // CONFIG
    // ----------------------------------------------------------------------
    /**
     * current config array
     * @var array
     */
    protected $_aConfig = array();

    /**
     * current variables and values from cli and interactive input
     * @var array
     */
    protected $_aValues = array();

    // ----------------------------------------------------------------------

    /**
     * create cli helper object
     * 
     * @param array  $aArgs  config array
     * @return boolean
     */
    public function __construct($aArgs = false) {
        if ($aArgs) {
            $this->setArgs($aArgs);
        }
        return true;
    }

    // ----------------------------------------------------------------------
    // PRIVATE FUNCTIONS (helper)
    // ----------------------------------------------------------------------

    /**
     * helper: check a variable ... if a pattern was defined return the result 
     * of match of value against pre defined pattern
     * 
     * @see read()
     * @see getopt()
     * @param string  $sVar  variable name (a key below 'params')
     * @param string  $sValue   value that will b verified
     * @return boolean
     */
    protected function _checkPattern($sVar, $sValue) {

        $aData = $this->_aConfig['params'][$sVar];
        if (array_key_exists('pattern', $aData)){
            if ($sValue===false || !preg_match($aData['pattern'], $sValue)) {
                echo 'ERROR: parameter "' . $sVar . '" (' . $aData['shortinfo'] . ') - it has a wrong value.' . "\n"
                . '"' . $sValue . '" does not match ' . $aData['pattern'] . '.' . "\n";
                return false;
            }
        }
            
        return true;
    }

    /**
     * helper: cli input to enter a value
     * 
     * @see read()
     * @param string $sPrefix  prefix/ login prompt
     * @param type   $default  default value if no value was given
     * @return string
     */
    protected function _cliInput($sPrefix, $default = false) {
        echo $sPrefix ? $sPrefix : '> ';
        if (PHP_OS == 'WINNT') {
            $sReturn = stream_get_line(STDIN, 1024, PHP_EOL);
        } else {
            $sReturn = readline('');
        }
        return $sReturn ? $sReturn : $default;
    }

    /**
     * helper: generate the short and long option parameters for PHP getopts() 
     * function by given parameters.
     * 
     * @see getopts()
     * @return array
     */
    protected function _getGetoptParams() {
        $sShort = '';
        $aOptions = array();
        foreach ($this->_aConfig['params'] as $sParam => $aData) {
            foreach (array('short', 'value', 'shortinfo') as $sKey) {
                if (!array_key_exists($sKey, $aData)) {
                    die(__CLASS__ . ':: ERROR in cli config: missing key [params]->[' . $sParam . ']->[' . $sKey . '] in [array].');
                }
            }
            $sDots = ''
                    . ($aData['value'] === CLIVALUE_REQUIRED ? ':' : '')
                    . ($aData['value'] === CLIVALUE_OPTIONAL ? '::' : '')
            ;
            $sShort.=$aData['short'] . $sDots;
            $aOptions[] = $sParam . $sDots;
        }
        return array(
            'short' => $sShort,
            'long' => $aOptions,
        );
    }

    // ----------------------------------------------------------------------
    // SETTER
    // ----------------------------------------------------------------------

    /**
     * apply a config; used by __constructor ... and can be called separately
     * 
     * @param type $aArgs
     * @return boolean
     */
    public function setArgs($aArgs) {
        foreach (array('label', 'params') as $sKey) {
            if (!array_key_exists($sKey, $aArgs)) {
                die(__CLASS__ . ':: ERROR in cli config: missing key [' . $sKey . '] in [array].');
            }
        }
        $this->_aConfig = $aArgs;
        $this->getopt();
        return true;
    }

    /**
     * interactive action; read a value and stor as value; the variable must 
     * exist in config; if a pattern was given the input will be verified 
     * against it.
     * 
     * @param string  $sVar  variable name (a key below 'params')
     * @return string
     */
    public function read($sVar) {
        if (!array_key_exists($sVar, $this->_aConfig['params'])) {
            die(__CLASS__ . ':: ERROR in cli config: missing key [params]->[' . $sVar . '] in [array].');
        }
        // remark ... check of this key was done in _getGetoptParams already
        echo $this->_aConfig['params'][$sVar]['shortinfo'] . "\n";

        $bOK = false;
        while (!$bOK) {
            $sValue = $this->_cliInput($sVar . '> ');

            if ($this->_checkPattern($sVar, $sValue)) {
                // echo "Thank you.\n";
                $bOK = true;
            }
        }
        // put value to the value store too
        $this->_aValues[$sVar] = $sValue;
        return $sValue;
    }

    // ----------------------------------------------------------------------
    // GETTER
    // ----------------------------------------------------------------------

    /**
     * get label and descriptioon to display as header
     * 
     * @param boolean  $bLong  show description too (if available); default: false
     * @return string
     */
    public function getlabel($bLong = false) {
        return "\n" . '===== ' . $this->_aConfig['label'] . ' =====' . "\n\n"
                . (($bLong && array_key_exists('description', $this->_aConfig) && $this->_aConfig['description']) ? $this->_aConfig['description'] . "\n" : '')
        ;
    }

    /**
     * get all params and values from cli parameters
     * 
     * @return array
     */
    public function getopt() {
        $aParamdef = $this->_getGetoptParams();
        $aOptions = getopt($aParamdef['short'], $aParamdef['long']);
        $this->_aValues = array();

        foreach ($aOptions as $sVar => $sValue) {
            foreach ($this->_aConfig['params'] as $sParam => $aData) {
                if ($sParam == $sVar || $aData['short'] == $sVar) {
                    if (!$this->_checkPattern($sParam, $sValue)) {
                        die();
                    }
                    $this->_aValues[$sParam] = ($sValue === false && $aData['value'] !== CLIVALUE_REQUIRED) ? true : $sValue;
                }
            }
        }
        return $this->_aValues;
    }

    /**
     * get the value based on variable
     * 
     * @param string  $sKey  name of variable
     * @return string
     */
    public function getvalue($sKey) {
        if (!array_key_exists($sKey, $this->_aConfig['params'])) {
            die(__CLASS__ . ':: ERROR in cli config: a parameter variable [' . $sKey . '] was not defined.');
        }
        if (array_key_exists($sKey, $this->_aValues)) {
            return $this->_aValues[$sKey];
        }
        return false;
    }

    /**
     * get generated text for help to explain all valid parameters
     * 
     * @return string
     */
    public function showhelp() {
        $sReturn = "HELP:\n"
                . ($this->_aConfig['description'] ? $this->_aConfig['description'] . "\n" : '')
                . "\n"
                . "PARAMETERS:\n"
        ;
        foreach ($this->_aConfig['params'] as $sParam => $aData) {
            $sReturn.='  --' . $sParam . ' (or -' . $aData['short'] . ') '
                    . ($aData['value'] === CLIVALUE_REQUIRED ? 'value (required)' : '')
                    . ($aData['value'] === CLIVALUE_OPTIONAL ? '[value] (optional)' : '')
                    . "\n"
                    . '    ' . $aData['shortinfo'] . "\n"
                    . (array_key_exists('description', $aData) ? '    ' . $aData['description'] . "\n" : '')
                    . "\n"
            ;
        }
        return $sReturn;
    }

}
