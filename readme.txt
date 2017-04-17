----------------------------------------------------------------------

  Axels CLI helper class
  It helps to handle command line arguments.
  
  version 1.1
  GNU GPL v 3.0
----------------------------------------------------------------------


ABOUT

  The cli class was written to simplify the handling of arguments in the
  command line. Other goodies are: it can generate a small help and
  interactively read a value until the value matches a pattern.
  
  - short and long cli argument parameter will be handled like one parameter
    i.e. you never again need to care about "-h" or "--help" was given 
  - generate a short help
  - verify a parameter value by given pattern (optional)
  - interactive input to read a missing var (with pattern check; optional)

  
LICENSE
  GNU GPL v 3.0


REQUIREMENTS
  PHP 5 (and higher)


INSTALL
  put the file "cli.class.php" somewhere


USAGE

  require_once("[path]/cli.class.php");
  $oCli=new axelhahn\cli($aParamDefs);
  
  $aParamDefs is a config array.
  
  first level subkeys:
  
    marked items with (*) are required
  
    'label'       - string - name of your tool (*)
    'description' - string - short description
    'params'      - array  - definition of all needed variables (*)
  
  Put your variables as subkeys below "params".
  Each variable has the following keys:
  
    'short'      - char    - name of the short parameter (*)
    'value'      - integer - flag for information if a value is required or not
                             you should use these constants
                               CLIVALUE_REQUIRED
                               CLIVALUE_OPTIONAL
                               CLIVALUE_NONE
    'pattern'    - string  - optional regex ... if a value is given it will be
                             checked against it (see examples below).
                             The class will stop on invalid values.
    'shortinfo'   - string - short description for this variable (*)
    'description' - string - short description for this variable (*)
    
  example patterns:
  
    letters only - case insensitive
    '/^[a-z]*$/i'

    one of required names case sensitive
    '/^(index|updateindex)$/'

    integer values
    '/^[0-9]*$/'


  public methods
  
    getlabel() (string)
      get label and description (from config) to display a header for your tool
      
    getopt() (array)
      get fetched vars and its values as key-value hash.
    
    getvalue([varname]) (string)
      get the value of a variable. [varname] will be filled with the long
      and the short parameter version. Only the long name is valid.
      see output of getopt()

    read([varname]) (string)
	  Let the user enter a value for a (missing) parameter.
	  The [varname] must be an existing key below 'params'.
	  If you leave a pattern the input can be finished only if it matches
	  this pattern.

    showhelp() (string)
      get pre generated help with all parameters and explainations from config 
	  array
	  
    setvalue([varname], [value])
	  Set or override a value of one of the params.
	  The [varname] must be an existing key below 'params'.

----------------------------------------------------------------------