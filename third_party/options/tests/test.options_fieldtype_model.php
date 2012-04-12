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


  public function test__create_fieldtype_tables__creates_the_database_table()
  {
    $this->EE->load->expectOnce('dbforge');

    $fields = array(
      'data_source_id' => array(
        'auto_increment'  => TRUE,
        'constraint'      => 10,
        'type'            => 'INT',
        'unsigned'        => TRUE
      ),
      'site_id' => array(
        'constraint'      => 5,
        'type'            => 'INT',
        'unsigned'        => TRUE
      ),
      'data_source_format' => array(
        'constraint'  => 20,
        'type'        => 'VARCHAR'
      ),
      'data_source_location' => array(
        'constraint'  => 255,
        'type'        => 'VARCHAR'
      ),
      'data_source_title' => array(
        'constraint'  => 255,
        'type'        => 'VARCHAR'
      ),
      'data_source_type' => array(
        'constraint'  => 10,
        'type'        => 'VARCHAR'
      )
    );

    $this->EE->dbforge->expectOnce('add_field', array($fields));
    $this->EE->dbforge->expectOnce('add_key', array('data_source_id', TRUE));
    $this->EE->dbforge->expectOnce('create_table',
      array('options_data_sources', TRUE));
  
    $this->_subject->create_fieldtype_tables();
  }


  public function test__destroy_fieldtype_tables__drops_database_table()
  {
    $this->EE->load->expectOnce('dbforge');
    $this->EE->dbforge->expectOnce('drop_table', array('options_data_sources'));

    $this->_subject->destroy_fieldtype_tables();
  }


}


/* End of file      : test.options_fieldtype_model.php */
/* File location    : third_party/options/tests/test.options_fieldtype_model.php */
