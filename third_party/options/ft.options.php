<?php if ( ! defined('BASEPATH')) exit('Direct script access not allowed');

/**
 * Options fieldtype.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once dirname(__FILE__) .'/config.php';

class Options_ft extends EE_Fieldtype {

  private $_ft_model;
  private $_sf_loader;

  /**
   * Stupid EE forces us to do this here, rather than calling the appropriate
   * model methods from the Constructor.
   */

  public $info = array(
    'name'    => OPTIONS_TITLE,
    'version' => OPTIONS_VERSION
  );


  /* --------------------------------------------------------------
   * PUBLIC METHODS
   * ------------------------------------------------------------ */

  /**
   * Constructor.
   *
   * @access  public
   * @param   mixed     $settings     Extension settings.
   * @return  void
   */
  public function __construct()
  {
    $this->EE =& get_instance();

    $this->EE->load->add_package_path(PATH_THIRD .'options/');

    // Load the language file.
    $this->EE->lang->loadfile('options_ft', 'options');

    // Load the model.
    $this->EE->load->model('options_fieldtype_model');
    $this->_ft_model = $this->EE->options_fieldtype_model;

    // Load our libraries and helpers.
    $this->EE->load->helper('form');
    $this->EE->load->library('spyc/spyc');
  }


  /**
   * Tidies up after one or more entries are deleted.
   *
   * @access public
   * @param  array $entry_ids The IDs of the deleted entries.
   * @return void
   */
  public function delete(Array $entry_ids)
  {

  }


  /**
   * Displays the fieldtype on the Publish / Edit page.
   *
   * @access public
   * @param  string $data Previously saved field data.
   * @return string
   */
  public function display_field($data = '')
  {
    $saved      = explode('|', $data);
    $field_name = $this->settings['field_name'];
    $options    = Spyc::YAMLLoad($this->settings['options_manual_source']);

    switch ($this->settings['options_control_type'])
    {
      case Control_type::CHECKBOX:
      case Control_type::RADIO:
        $output = $this->_display_field_checkboxes_and_radio_buttons(
          $field_name, $this->settings['options_control_type'], $options,
          $saved);
        break;

      case Control_type::MULTI_SELECT:
        $output = form_multiselect($field_name .'[]', $options, $saved);
        break;

      case Control_type::SELECT:
      default:
        $output = form_dropdown($field_name, $options, $saved);
    }

    return $output;
  }


  /**
   * Displays the fieldtype settings form.
   *
   * @access public
   * @param  array $settings Previously-saved settings.
   * @return string
   */
  public function display_settings(Array $settings = array())
  {
    // Satan's little helpers.
    $this->EE->load->library('table');

    // Restore any previously-saved data.
    $current_settings = $this->_ft_model->update_array_from_input(
      $this->_ft_model->get_default_fieldtype_settings(),
      $settings
    );

    // Gather the view data.
    $view_data = array(
      'current_settings'      => $current_settings,
      'options_control_types' => $this->_ft_model->get_control_types(),
      'options_source_types'  => $this->_ft_model->get_data_source_types()
    );

    /**
     * Somewhat confusing. We don't need to actually return the view string, or
     * even echo it out. The view uses the table->add_row method, and that is
     * apparently enough. Obviously.
     */

    $this->EE->load->view('settings', $view_data);
  }


  /**
   * Processes the field data in preparation for the "replace tag" method(s).
   * Performing the prep work here minimises the overhead when a template
   * contains multiple fieldtype tags.
   *
   * @access public
   * @param  string $data The fieldtype data.
   * @return mixed  The prepped data.
   */
  public function pre_process($data)
  {

  }


  /**
   * Displays the fieldtype in a template.
   *
   * @access public
   * @param  string $data    The saved field data.
   * @param  array  $params  The tag parameters.
   * @param  string $tagdata The tag data (for tag pairs).
   * @return string The modified tagdata.
   */
  public function replace_tag($data, Array $params = array(), $tagdata = '')
  {

  }


  /**
   * Output the available options as checkboxes.
   *
   * @access public
   * @param  string $data    The saved field data.
   * @param  array  $params  The tag parameters.
   * @param  string $tagdata The tag data (for tag pairs).
   * @return string The modified tagdata.
   */
  public function replace_checkbox($data, Array $params = array(), $tagdata = '')
  {

  }


  /**
   * Output the available options as radio buttons.
   *
   * @access public
   * @param  string $data    The saved field data.
   * @param  array  $params  The tag parameters.
   * @param  string $tagdata The tag data (for tag pairs).
   * @return string The modified tagdata.
   */
  public function replace_radio($data, Array $params = array(), $tagdata = '')
  {

  }


  /**
   * Output the available options as a drop-down list.
   *
   * @access public
   * @param  string $data    The saved field data.
   * @param  array  $params  The tag parameters.
   * @param  string $tagdata The tag data (for tag pairs).
   * @return string The modified tagdata.
   */
  public function replace_select($data, Array $params = array(), $tagdata = '')
  {

  }


  /**
   * Preps. the field data for saving.
   *
   * @access public
   * @param  mixed $data The submitted field data.
   * @return string
   */
  public function save($data)
  {
    return is_array($data) ? implode('|', $data) : $data;
  }


  /**
   * Saves the global fieldtype settings.
   *
   * @access public
   * @return array
   */
  public function save_global_settings()
  {

  }


  /**
   * Saves the fieldtype settings.
   *
   * @access public
   * @param  array $settings The submitted settings.
   * @return array
   */
  public function save_settings(Array $settings = array())
  {
    return $this->_ft_model->update_array_from_input(
      $this->_ft_model->get_default_fieldtype_settings(),
      $settings
    );
  }


  /* --------------------------------------------------------------
   * LOW VARIABLES
   * ------------------------------------------------------------ */

  /**
   * Displays the input field on the Low Variables home page.
   *
   * @access public
   * @param  string $var_data The current variable data.
   * @return string The input field HTML.
   */
  public function display_var_field($var_data)
  {

  }


  /**
   * Displays the Low Variables fieldtype settings form.
   *
   * @access public
   * @param  array  $var_settings Previously saved settings.
   * @return array  An array containing the name / label, and the form elements.
   */
  public function display_var_settings(Array $var_settings = array())
  {

  }


  /**
   * Displays the Low Variable in a template.
   *
   * @access public
   * @param  string $var_data The Low Variable field data.
   * @param  Array  $params   The tag parameters.
   * @param  string $tagdata  The tag data (for tag pairs).
   * @return string The modified tag data.
   */
  public function display_var_tag($var_data, Array $params, $tagdata)
  {

  }


  /**
   * Modifies the Low Variables field data before it is saved to the database.
   *
   * @access public
   * @param  string $var_data The submitted Low Variable field data.
   * @return string The field data to save to the database.
   */
  public function save_var_field($var_data)
  {

  }


  /**
   * Modifies the Low Variables settings data before it is saved to the
   * database.
   *
   * @access public
   * @param  array  $var_settings The submitted Low Variable settings.
   * @return array  The settings data to be saved to the database.
   */
  public function save_var_settings(Array $var_settings = array())
  {

  }


  /* --------------------------------------------------------------
   * MATRIX
   * ------------------------------------------------------------ */


  /* --------------------------------------------------------------
   * PRIVATE METHODS
   * ------------------------------------------------------------ */

  /**
   * Constructs the 'display_field' HTML for checkboxes and radio buttons.
   *
   * @access private
   * @param  string $name     The field name.
   * @param  string $type     The field type (checkbox or radio).
   * @param  array  $options  An array of options to display.
   * @param  array  $saved    An array of previously-saved options to restore.
   * @return string
   */
  private function _display_field_checkboxes_and_radio_buttons($name, $type,
    Array $options, Array $saved
  )
  {
    /**
     * As this is a private method, and we are inside the circle of trust, no
     * attempt is made to check that a valid control type has been supplied.
     */

    if ($type == Control_type::CHECKBOX)
    {
      $full_name = $name .'[]';
      $helper_function = 'form_checkbox';
    }
    else
    {
      $full_name = $name;
      $helper_function = 'form_radio';
    }

    $return = '';

    foreach ($options AS $key => $val)
    {
      if (is_array($val))
      {
        $return .= form_fieldset($key)
          .$this->_display_field_checkboxes_and_radio_buttons(
            $name, $type, $val, $saved)
          .form_fieldset_close();
      }
      else
      {
        $return .= '<label>'
          .$helper_function($full_name, $key, in_array($key, $saved)) .' '
          .$val .'</label>';
      }
    }

    return $return;
  }



}


/* End of file      : ft.options.php */
/* File location    : third_party/options/ft.options.php */