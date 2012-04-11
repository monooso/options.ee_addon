<?php if ( ! defined('BASEPATH')) exit('Invalid file request');

/**
 * Options fieldtype tests.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once PATH_FT .'EE_Fieldtype.php';
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

    $this->EE->options_fieldtype_model = $this->_get_mock('fieldtype_model');

    $this->_ft_model  = $this->EE->options_fieldtype_model;
    $this->_subject   = new Options_ft();
  }


  public function test__display_field__returns_empty_string_if_missing_or_invalid_data()
  {
    $s = $this->_subject;

    $s->settings = array(
      'field_name'            => 'my_lovely_field',
      'options_control_type'  => 'select',
      'options_source_type'   => 'manual',
      'options_file_source'   => 'sample.yml',
      'options_manual_source' => 'manual_source',
      'options_url_source'    => 'http://myurl.com/sample.yml'
    );

    $this->_ft_model->expectNever('load_options_data_from_file');
    $this->_ft_model->expectNever('load_options_data_from_string');
    $this->_ft_model->expectNever('load_options_data_from_url');

    $this->assertIdentical('', $s->display_field(array()));
    $this->assertIdentical('', $s->display_field(new StdClass()));
  }


  public function test__display_field__returns_empty_string_if_missing_field_name()
  {
    $this->_subject->settings = array(
      'options_control_type'  => 'select',
      'options_source_type'   => 'manual',
      'options_file_source'   => 'sample.yml',
      'options_manual_source' => 'manual_source',
      'options_url_source'    => 'http://myurl.com/sample.yml'
    );

    $this->_ft_model->expectNever('load_options_data_from_file');
    $this->_ft_model->expectNever('load_options_data_from_string');
    $this->_ft_model->expectNever('load_options_data_from_url');

    $data = 'Saved Data';

    $this->assertIdentical('', $this->_subject->display_field($data));
  }


  public function test__display_field__returns_empty_string_if_invalid_field_name()
  {
    $this->_subject->settings = array(
      'field_name'            => '',
      'options_control_type'  => 'select',
      'options_source_type'   => 'manual',
      'options_file_source'   => 'sample.yml',
      'options_manual_source' => 'manual_source',
      'options_url_source'    => 'http://myurl.com/sample.yml'
    );

    $this->_ft_model->expectNever('load_options_data_from_file');
    $this->_ft_model->expectNever('load_options_data_from_string');
    $this->_ft_model->expectNever('load_options_data_from_url');

    $data = 'Saved Data';

    $this->assertIdentical('', $this->_subject->display_field($data));
  }


  public function test__display_field__returns_empty_string_if_missing_control_type()
  {
    $this->_subject->settings = array(
      'field_name'            => 'my_lovely_field',
      'options_source_type'   => 'manual',
      'options_file_source'   => 'sample.yml',
      'options_manual_source' => 'manual_source',
      'options_url_source'    => 'http://myurl.com/sample.yml'
    );

    $this->_ft_model->expectNever('load_options_data_from_file');
    $this->_ft_model->expectNever('load_options_data_from_string');
    $this->_ft_model->expectNever('load_options_data_from_url');

    $data = 'Saved Data';

    $this->assertIdentical('', $this->_subject->display_field($data));
  }


  public function test__display_field__returns_empty_string_if_invalid_control_type()
  {
    $this->_subject->settings = array(
      'field_name'            => 'my_lovely_field',
      'options_control_type'  => '',
      'options_source_type'   => 'manual',
      'options_file_source'   => 'sample.yml',
      'options_manual_source' => 'manual_source',
      'options_url_source'    => 'http://myurl.com/sample.yml'
    );

    $this->_ft_model->expectNever('load_options_data_from_file');
    $this->_ft_model->expectNever('load_options_data_from_string');
    $this->_ft_model->expectNever('load_options_data_from_url');

    $data = 'Saved Data';

    $this->assertIdentical('', $this->_subject->display_field($data));
  }


  public function test__display_field__returns_empty_string_if_missing_source_type()
  {
    $this->_subject->settings = array(
      'field_name'            => 'my_lovely_field',
      'options_control_type'  => 'select',
      'options_file_source'   => 'sample.yml',
      'options_manual_source' => 'manual_source',
      'options_url_source'    => 'http://myurl.com/sample.yml'
    );

    $this->_ft_model->expectNever('load_options_data_from_file');
    $this->_ft_model->expectNever('load_options_data_from_string');
    $this->_ft_model->expectNever('load_options_data_from_url');

    $data = 'Saved Data';

    $this->assertIdentical('', $this->_subject->display_field($data));
  }


  public function test__display_field__returns_empty_string_if_invalid_source_type()
  {
    $this->_subject->settings = array(
      'field_name'            => 'my_lovely_field',
      'options_control_type'  => 'select',
      'options_source_type'   => '',
      'options_file_source'   => 'sample.yml',
      'options_manual_source' => 'manual_source',
      'options_url_source'    => 'http://myurl.com/sample.yml'
    );

    $this->_ft_model->expectNever('load_options_data_from_file');
    $this->_ft_model->expectNever('load_options_data_from_string');
    $this->_ft_model->expectNever('load_options_data_from_url');

    $data = 'Saved Data';

    $this->assertIdentical('', $this->_subject->display_field($data));
  }


  public function test__install__creates_the_database_table()
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
  
    $this->_subject->install();
  }


  public function test__save_global_settings__returns_an_empty_array_of_data_sources_if_none_were_submitted()
  {
    $input_sources    = FALSE;
    $expected_result  = array('data_sources' => array());

    $this->EE->input->expectOnce('post', array('data_source', TRUE));
    $this->EE->input->returns('post', $input_sources);
  
    $this->assertIdentical($expected_result,
      $this->_subject->save_global_settings());
  }


  public function test__save_global_settings__returns_an_empty_array_of_data_sources_if_post_data_is_not_array()
  {
    $input_sources    = 'wibble';
    $expected_result  = array('data_sources' => array());

    $this->EE->input->expectOnce('post', array('data_source', TRUE));
    $this->EE->input->returns('post', $input_sources);
  
    $this->assertIdentical($expected_result,
      $this->_subject->save_global_settings());
  }


  public function test__save_global_settings__ignores_data_sources_with_missing_data()
  {
    $input_sources = array(
      array(
        'format'    => '',
        'location'  => '/path/to/file.yml',
        'name'      => 'Missing Format',
        'type'      => 'file'
      ),
      array(
        'format'    => 'yaml',
        'location'  => '',
        'name'      => 'Missing Location',
        'type'      => 'file'
      ),
      array(
        'format'    => 'yaml',
        'location'  => 'http://example.com/valid.yml',
        'name'      => 'Valid',
        'type'      => 'url'
      ),
      array(
        'format'    => 'yaml',
        'location'  => 'http://example.com/file.yml',
        'name'      => '',
        'type'      => 'url'
      ),
      array(
        'format'    => 'yaml',
        'location'  => 'http://example.com/file.yml',
        'name'      => 'Missing Type',
        'type'      => ''
      )
    );

    $expected_result = array(
      'data_sources' => array(
        array(
          'format'    => 'yaml',
          'location'  => 'http://example.com/valid.yml',
          'name'      => 'Valid',
          'type'      => 'url'
        )
      )
    );

    $this->EE->input->expectOnce('post', array('data_source', TRUE));
    $this->EE->input->returns('post', $input_sources);
  
    $this->assertIdentical($expected_result,
      $this->_subject->save_global_settings());
  }


  public function test__uninstall__drops_database_table()
  {
    $this->EE->load->expectOnce('dbforge');
    $this->EE->dbforge->expectOnce('drop_table', array('options_data_sources'));

    $this->_subject->uninstall();
  }
  


  public function test__validate__returns_true_if_data_is_string_and_not_null()
  {
    $this->_subject->settings['field_required'] = 'y';

    $data = 'okay';

    $this->EE->lang->expectNever('line');
    $this->assertIdentical(TRUE, $this->_subject->validate($data));
  }


  public function test__validate__returns_false_if_data_is_string_and_null()
  {
    $this->_subject->settings['field_required'] = 'y';

    $data = 'null';
    $message  = 'Epic Fail!';

    $this->EE->lang->expectOnce('line', array('*'));
    $this->EE->lang->returns('line', $message);

    $this->assertIdentical($message, $this->_subject->validate($data));
  }


  public function test__validate__returns_true_if_data_is_array_and_does_not_contain_null()
  {
    $this->_subject->settings['field_required'] = 'y';

    $data = array('okay', 'good', 'valid');

    $this->EE->lang->expectNever('line');
    $this->assertIdentical(TRUE, $this->_subject->validate($data));
  }
  
  
  public function test__validate__returns_false_if_data_is_array_and_contains_null()
  {
    $this->_subject->settings['field_required'] = 'y';

    $data = array('okay', 'null', 'valid');
    $message  = 'Epic Fail!';

    $this->EE->lang->expectOnce('line', array('*'));
    $this->EE->lang->returns('line', $message);

    $this->assertIdentical($message, $this->_subject->validate($data));
  }


  public function test__validate__allows_null_if_field_is_not_required()
  {
    $this->_subject->settings['field_required'] = 'n';

    $data = 'null';

    $this->EE->lang->expectNever('line');
    $this->assertIdentical(TRUE, $this->_subject->validate($data));
  }
  
  
  
}


/* End of file      : test.ft_options.php */
/* File location    : third_party/options/tests/test.ft_options.php */
