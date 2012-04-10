<?php if ( ! defined('BASEPATH')) exit('Direct script access not allowed');

/**
 * Options fieldtype model.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once dirname(__FILE__) .'/options_model.php';

class Options_extension_model extends Options_model {

  /* --------------------------------------------------------------
   * PUBLIC METHODS
   * ------------------------------------------------------------ */

  /**
   * Constructor.
   *
   * @access  public
   * @param   string  $package_name     Package name. Used for testing.
   * @param   string  $package_title    Package title. Used for testing.
   * @param   string  $package_version  Package version. Used for testing.
   * @param   string  $namespace        Session namespace. Used for testing.
   * @return  void
   */
  public function __construct($package_name = '', $package_title = '',
    $package_version = '', $namespace = ''
  )
  {
    parent::__construct($package_name, $package_title, $package_version,
      $namespace);
  }


}


/* End of file      : options_fieldtype_model.php */
/* File location    : third_party/options/models/options_fieldtype_model.php */