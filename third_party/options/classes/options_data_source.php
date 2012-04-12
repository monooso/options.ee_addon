<?php

/**
 * Options Data Source datatype.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once dirname(__FILE__) .'/EI_datatype.php';

class Options_data_source extends EI_datatype
{
  const FORMAT_YAML = 'yaml';
  const TYPE_FILE   = 'file';
  const TYPE_URL    = 'url';


  /* --------------------------------------------------------------
   * PUBLIC METHODS
   * ------------------------------------------------------------ */

  /**
   * Constructor.
   *
   * @access  public
   * @param   array    $props    Associative array of property names and values.
   * @return  void
   */
  public function __construct(Array $props = array())
  {
    parent::__construct($props);
  }


  /**
   * Magic 'setter' method.
   *
   * @access  public
   * @param   string    $prop_name    The property name.
   * @param   mixed    $prop_value    The property value.
   * @return  void
   */
  public function __set($prop_name, $prop_value)
  {
    if (  ! $this->_is_valid_property($prop_name))
    {
      return;
    }

    // Validate.
    switch ($prop_name)
    {
      case 'format':
        if ($this->_is_valid_data_source_format($prop_value))
        {
          $this->_set_string_property($prop_name, $prop_value);
        }
        break;

      case 'type':
        if ($this->_is_valid_data_source_type($prop_value))
        {
          $this->_set_string_property($prop_name, $prop_value);
        }
        break;

      case 'id':
        $this->_set_int_property($prop_name, $prop_value, 1);
        break;

      default:
        $this->_set_string_property($prop_name, $prop_value);
        break;
    }
  }


  /**
   * Determines whether the instance is populated.
   *
   * @access  public
   * @param   bool  $require_id   Is the 'id' property required?
   * @return  bool
   */
  public function is_populated($require_id = TRUE)
  {
    return $this->format
      && $this->location
      && $this->type
      && $this->title
      && ($require_id === FALSE ? TRUE : $this->id);
  }


  /**
   * Resets the instance properties.
   *
   * @access  public
   * @return  Options_data_source
   */
  public function reset()
  {
    $this->_props = array(
      'format'    => '',
      'location'  => '',
      'type'      => '',
      'title'     => '',
      'id'        => 0
    );

    return $this;
  }


  /* --------------------------------------------------------------
   * PRIVATE METHODS
   * ------------------------------------------------------------ */
  
  /**
   * Determines whether the given value is a valid data source format.
   *
   * @access  private
   * @param   string    $format    The format to check.
   * @return  bool
   */
  private function _is_valid_data_source_format($format)
  {
    return ($format === self::FORMAT_YAML);
  }


  /**
   * Determines whether the given value is a valid data source type.
   *
   * @access  private
   * @param   string    $type    The type to check.
   * @return  bool
   */
  private function _is_valid_data_source_type($type)
  {
    return (in_array($type, array(self::TYPE_FILE, self::TYPE_URL)));
  }


}


/* End of file      : options_data_source.php */
/* File location    : third_party/options/classes/options_data_source.php */
