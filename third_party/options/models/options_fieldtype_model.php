<?php if ( ! defined('BASEPATH')) exit('Direct script access not allowed');

/**
 * Options fieldtype model.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once dirname(__FILE__) .'/options_model.php';

class Options_fieldtype_model extends Options_model {

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


  /**
   * Creates the fieldtype database table(s).
   *
   * @access  public
   * @return  void
   */
  public function create_fieldtype_tables()
  {
    $this->EE->load->dbforge();

    $fields = array(
      'data_source_id' => array(
        'auto_increment'  => TRUE,
        'constraint'      => 10,
        'type'            => 'INT',
        'unsigned'        => TRUE
      ),
      'site_id' => array(
        'constraint'  => 5,
        'type'        => 'INT',
        'unsigned'    => TRUE
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

    $this->EE->dbforge->add_field($fields);
    $this->EE->dbforge->add_key('data_source_id', TRUE);
    $this->EE->dbforge->create_table($this->_data_sources_table, TRUE);
  }


  /**
   * Destroys the fieldtype database table(s).
   *
   * @access  public
   * @return  void
   */
  public function destroy_fieldtype_tables()
  {
    $this->EE->load->dbforge();
    $this->EE->dbforge->drop_table($this->_data_sources_table);
  }


}


/* End of file      : options_fieldtype_model.php */
/* File location    : third_party/options/models/options_fieldtype_model.php */
