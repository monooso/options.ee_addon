<?php if ( ! defined('BASEPATH')) exit('Invalid file request');

/**
 * Options fieldtype tests.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once PATH_THIRD .'options/ft.options.php';
require_once PATH_THIRD .'options/models/options_fieldtype_model.php';

class Test_options_ft extends Testee_unit_test_case {

  private $_ft_model;
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

    // Generate the mock model.
    Mock::generate('Options_fieldtype_model',
      get_class($this) .'_mock_fieldtype_model');

    /**
     * The subject loads the models using $this->EE->load->model().
     * Because the Loader class is mocked, that does nothing, so we
     * can just assign the mock models here.
     */

    $this->EE->options_fieldtype_model
      = $this->_get_mock('fieldtype_model');

    $this->_ft_model  = $this->EE->options_fieldtype_model;
    $this->_subject   = new Options();
  }


}


/* End of file      : test.ft_options.php */
/* File location    : third_party/options/tests/test.ft_options.php */