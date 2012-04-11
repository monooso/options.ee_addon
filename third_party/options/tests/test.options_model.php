<?php if ( ! defined('BASEPATH')) exit('Invalid file request');

/**
 * Options model tests.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once PATH_THIRD .'options/models/options_model.php';

class Test_options_model extends Testee_unit_test_case {

  private $_namespace;
  private $_package_name;
  private $_package_title;
  private $_package_version;
  private $_site_id;
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

    // Mock once.
    $this->_site_id = 123;
    $this->EE->config->returns('item', $this->_site_id, array('site_id'));

    $this->_subject = new Options_model($this->_package_name,
      $this->_package_title, $this->_package_version, $this->_namespace);
  }


  public function test__get_global_data_sources__queries_database_and_returns_array_of_data_source_objects()
  {
    $db_result = $this->_get_mock('db_query');

    $db_rows = array(
      array(
        'data_source_id'        => '10',
        'data_source_format'    => Options_data_source::FORMAT_YAML,
        'data_source_location'  => '/path/to/file.yml',
        'data_source_title'     => 'Example File',
        'data_source_type'      => Options_data_source::TYPE_FILE
      ),
      array(
        'data_source_id'        => '20',
        'data_source_format'    => Options_data_source::FORMAT_YAML,
        'data_source_location'  => 'http://example.com/data/',
        'data_source_title'     => 'Example URL',
        'data_source_type'      => Options_data_source::TYPE_URL
      )
    );

    $expected_result = array(
      new Options_data_source(array(
        'id'        => $db_rows[0]['data_source_id'],
        'format'    => $db_rows[0]['data_source_format'],
        'location'  => $db_rows[0]['data_source_location'],
        'title'     => $db_rows[0]['data_source_title'],
        'type'      => $db_rows[0]['data_source_type']
      )),
      new Options_data_source(array(
        'id'        => $db_rows[1]['data_source_id'],
        'format'    => $db_rows[1]['data_source_format'],
        'location'  => $db_rows[1]['data_source_location'],
        'title'     => $db_rows[1]['data_source_title'],
        'type'      => $db_rows[1]['data_source_type']
      ))
    );

    $fields = array('data_source_id', 'data_source_format',
      'data_source_location', 'data_source_title', 'data_source_type');

    $this->EE->db->expectOnce('select', array(implode(', ', $fields)));
    $this->EE->db->expectOnce('get_where', array('options_data_sources',
      array('site_id' => $this->_site_id)));

    $this->EE->db->returnsByReference('get_where', $db_result);

    $db_result->expectOnce('num_rows');
    $db_result->returns('num_rows', count($db_rows));

    $db_result->expectOnce('result_array');
    $db_result->returns('result_array', $db_rows);
  
    $this->assertIdentical($expected_result,
      $this->_subject->get_global_data_sources());
  }
  


  public function test__get_global_data_sources__returns_an_empty_array_if_no_data_sources_exist()
  {
    $db_result = $this->_get_mock('db_query');

    $this->EE->db->expectOnce('get_where');
    $this->EE->db->returnsByReference('get_where', $db_result);

    $db_result->expectOnce('num_rows');
    $db_result->returns('num_rows', 0);

    $db_result->expectNever('result_array');
  
    $this->assertIdentical(array(), $this->_subject->get_global_data_sources());
  }
  


  public function test__get_package_name__returns_correct_package_name_converted_to_lowercase()
  {
    $this->assertIdentical(strtolower($this->_package_name),
      $this->_subject->get_package_name());
  }


  public function test__get_package_theme_url__pre_240_works()
  {
    if (defined('URL_THIRD_THEMES'))
    {
      $this->pass();
      return;
    }

    $package    = strtolower($this->_package_name);
    $theme_url  = 'http://example.com/themes/';
    $full_url   = $theme_url .'third_party/' .$package .'/';

    $this->EE->config->expectOnce('slash_item', array('theme_folder_url'));
    $this->EE->config->setReturnValue('slash_item', $theme_url);

    $this->assertIdentical($full_url, $this->_subject->get_package_theme_url());
  }


  public function test__get_package_title__returns_correct_package_title()
  {
    $this->assertIdentical($this->_package_title,
      $this->_subject->get_package_title());
  }


  public function test__get_package_version__returns_correct_package_version()
  {
    $this->assertIdentical($this->_package_version,
      $this->_subject->get_package_version());
  }


  public function test__update_array_from_input__ignores_unknown_keys_and_updates_known_keys_and_preserves_unaltered_keys()
  {
    $base_array = array(
      'first_name'  => 'John',
      'last_name'   => 'Doe',
      'gender'      => 'Male',
      'occupation'  => 'Unknown'
    );

    $update_array = array(
      'dob'         => '1941-05-24',
      'first_name'  => 'Bob',
      'last_name'   => 'Dylan',
      'occupation'  => 'Writer'
    );

    $expected_result = array(
      'first_name'  => 'Bob',
      'last_name'   => 'Dylan',
      'gender'      => 'Male',
      'occupation'  => 'Writer'
    );

    $this->assertIdentical($expected_result,
      $this->_subject->update_array_from_input($base_array, $update_array));
  }


}


/* End of file      : test.options_model.php */
/* File location    : third_party/options/tests/test.options_model.php */
