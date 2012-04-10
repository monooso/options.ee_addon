<?php if ( ! defined('BASEPATH')) exit('Direct script access not allowed');

/**
 * Options fieldtype.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

class Options_ft extends EE_Fieldtype {

  private $EE;
  private $_ft_model;

  public $info;


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

    // Still need to specify the package...
    $this->EE->lang->loadfile('options_ft', 'options');

    $this->EE->load->model('options_fieldtype_model');
    $this->_ft_model = $this->EE->options_fieldtype_model;

    // Set the fieldtype info.
    $this->info = array(
      'name'    => $this->_ft_model->get_package_title(),
      'version' => $this->_ft_model->get_package_version()
    );
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

  }


  /**
   * Displays the global settings form. The current global settings are
   * available via the $this->settings property.
   *
   * @access public
   * @return string
   */
  public function display_global_settings()
  {

  }


  /**
   * Displays the fieldtype settings form.
   *
   * @access public
   * @param  string $settings Previously-saved settings.
   * @return string
   */
  public function display_settings($settings = '')
  {

  }


  /**
   * Installs the fieldtype, and sets the default values.
   *
   * @access public
   * @return array
   */
  public function install()
  {

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
   * Prepares the field data for saving to the databasae.
   *
   * @access public
   * @param  string $data The submitted field data.
   * @return string The data to save.
   */
  public function save($data)
  {

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
   * @param  string $settings The submitted settings.
   * @return void
   */
  public function save_settings($settings)
  {

  }


  /**
   * Uninstalls the fieldtype.
   *
   * @access public
   * @return void
   */
  public function uninstall()
  {

  }


  /**
   * Validates the submitted field data.
   *
   * @access public
   * @param  string $data The submitted field data.
   * @return bool
   */
  public function validate($data)
  {

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
   * Performs additional processing after the Low Variable has been saved.
   *
   * @access public
   * @param  string $var_data The submitted Low Variable data.
   * @return void
   */
  public function post_save_var($var_data)
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


}


/* End of file      : ft.options.php */
/* File location    : third_party/options/ft.options.php */