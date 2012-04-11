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


  public function test__validate__returns_true_if_data_is_string_and_not_null()
  {
    $data = 'okay';
    $this->assertIdentical(TRUE, $this->_subject->validate($data));
  }


  public function test__validate__returns_false_if_data_is_string_and_null()
  {
    $data = 'null';
    $message  = 'Epic Fail!';

    $this->EE->lang->expectOnce('line', array('*'));
    $this->EE->lang->returns('line', $message);

    $this->assertIdentical($message, $this->_subject->validate($data));
  }


  public function test__validate__returns_true_if_data_is_array_and_does_not_contain_null()
  {
    $data = array('okay', 'good', 'valid');
    $this->assertIdentical(TRUE, $this->_subject->validate($data));
  }
  
  
  public function test__validate__returns_false_if_data_is_array_and_contains_null()
  {
    $data = array('okay', 'null', 'valid');
    $message  = 'Epic Fail!';

    $this->EE->lang->expectOnce('line', array('*'));
    $this->EE->lang->returns('line', $message);

    $this->assertIdentical($message, $this->_subject->validate($data));
  }
  
  
}


/* End of file      : test.ft_options.php */
/* File location    : third_party/options/tests/test.ft_options.php */
