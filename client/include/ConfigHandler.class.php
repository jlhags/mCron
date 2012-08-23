<?php
/**
* Read and Write INI files
* @author Joshua Hagofsky
* @package RockingPortal
* @subpackage includes
* @copyright 2011 Joshua Hagofsky
*/
class ConfigHandler
{
  private $mConfig;
  private $mFile;

  /**
  * Default Constructor
  *
  * @param string $file INI files to read/write
  */
  public function __construct($file)
  {
    $this->mConfig = parse_ini_file($file, true);
    $this->mFile = $file;
  }
  /**
  * Retrieve value from INI file
  *
  * @param string $section the section of the INI file
  * @param string $var name of the option to get
  * @return mixed the specified value
  */
  public function GetValue($section,$var)
  {
    return $this->mConfig[$section][$var];
  }
/**
  * Retrieve value from INI file
  *
  * @param string $section the section of the INI file
  * @param string $var name of the option to get
  * @return mixed the specified value
  */
  public function GetValue($section,$var)
  {
    return $this->mConfig[$section][$var];
  }

  /**
  * Set value for INI file
  *
  * @param string $section the section of the INI file
  * @param string $var name of the option to get
  * @return mixed the specified value
  */
  public function SetValue($section,$var,$value)
  {
    $this->mConfig[$section][$var] = $value;
  }
 * Write changes to file
  *
  */
  public function SaveConfig()
  {
    $handle = fopen($this->mFile,"w");
    foreach($this->mConfig as $sname=>$section)
    {
      fwrite($handle,"[".$sname . "]\n");
      foreach($section as $name=>$value)
      {
        fprintf($handle, "%s = \"%s\"\n",$name,$value);
      }
    }
  }

  /**
  * Get associative array on config file values
  *
  * @return array the values
  */
  public function GetRaw()
  {
    return $this->mConfig;
  }
}
