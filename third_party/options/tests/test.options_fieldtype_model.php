<?php if ( ! defined('BASEPATH')) exit('Invalid file request');

/**
 * Options fieldtype model tests.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once PATH_THIRD .'options/models/options_fieldtype_model.php';

class Test_options_fieldtype_model extends Testee_unit_test_case {

  private $_namespace;
  private $_package_name;
  private $_package_title;
  private $_package_version;
  private $_subject;


  /* --------------------------------------------------------------
   * PUBLIC METHODS
   * ------------------------------------------------------------ */

  /**
   * Constructor.
   *
   * @access  public
   * @return  void
   */
  public function setUp()
  {
    parent::setUp();

    $this->_namespace       = 'com.google';
    $this->_package_name    = 'Example_package';
    $this->_package_title   = 'Example Package';
    $this->_package_version = '1.0.0';

    $this->_subject = new Options_fieldtype_model($this->_package_name,
      $this->_package_title, $this->_package_version, $this->_namespace);
  }


}


/* End of file      : test.options_fieldtype_model.php */
/* File location    : third_party/options/tests/test.options_fieldtype_model.php */