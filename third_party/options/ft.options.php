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
    // Do we have the required settings?
    if ( ! array_key_exists('field_name', $this->settings)
      OR ! array_key_exists('options_control_type', $this->settings)
      OR ! array_key_exists('options_global_source', $this->settings)
      OR ! array_key_exists('options_manual_source', $this->settings)
      OR ! array_key_exists('options_source_type', $this->settings)
      OR ! $this->settings['field_name']
      OR ! $this->settings['options_control_type']
      OR ! $this->settings['options_source_type']
    )
    {
      return '';
    }

    $data = is_string($data) ? explode('|', $data) : array();
    $field_name = $this->settings['field_name'];

    try
    {

      // @TODO : Use constants for data source types.

      switch ($this->settings['options_source_type'])
      {
        case 'global':
          $data_source = $this->_ft_model->get_global_data_source_by_id(
            $this->settings['options_global_source']);

          if ( ! $data_source OR ! $data_source->is_populated())
          {
            throw new Exception('Invalid data source in ' .__METHOD__);
          }

          if ($data_source->type == Options_data_source::TYPE_FILE)
          {
            // Load the data from a file.
            $options = $this->_ft_model->load_options_data_from_file(
              $data_source->location);
          }
          elseif ($data_source->type == Options_data_source::TYPE_URL)
          {
            // Load the data from a URL.
            $options = $this->_ft_model->load_options_data_from_url(
              $data_source->location);
          }
          else
          {
            // Express our outrage.
            throw new Exception('Invalid data source type in ' .__METHOD__);
          }

          break;

        case 'manual':
          $options = $this->_ft_model->load_options_data_from_string(
            $this->settings['options_manual_source']);
          break;

        default:
          throw new Exception('Invalid data source in ' .__METHOD__);
          break;
      }
    }
    catch (Exception $e)
    {
      // @TODO: Log the error.
      return '';
    }

    // Create the form controls.
    switch ($this->settings['options_control_type'])
    {
      case Options_control_type::CHECKBOX:
      case Options_control_type::RADIO:
        $output = $this->_display_field_checkboxes_and_radio_buttons(
          $field_name, $this->settings['options_control_type'], $options,
          $data);
        break;

      case Options_control_type::MULTI_SELECT:
        $output = form_multiselect($field_name .'[]', $options, $data);
        break;

      case Options_control_type::SELECT:
      default:

        /**
         * TRICKY:
         * CodeIgniter tries to be too clever for its own good sometimes. If we 
         * pass more than one "selected" item to the form_dropdown method, CI 
         * automatically sets the "multiple" property.
         *
         * The only time we'd encounter this problem IRL is if the user switched 
         * from using a "multiple selection" option (such as checkboxes) to a 
         * "single selection" option (such as our drop-down). Still, it's 
         * something we have to deal with.
         */

        $data   = $data ? $data[0] : $data;
        $output = form_dropdown($field_name, $options, $data);
        break;
    }

    return $output;
  }


  /**
   * Displays the global settings page.
   *
   * @access  public
   * @return  string
   */
  public function display_global_settings()
  {
    $this->EE->load->library('table');

    // Retrieve the theme URL.
    $theme_url = $this->_ft_model->get_package_theme_url();

    // Add the JavaScript.
    $this->EE->cp->add_to_foot('<script type="text/javascript" src="'
      .$theme_url .'js/libs/jquery.roland.js"></script>');

    $this->EE->cp->add_to_foot('<script type="text/javascript" src="'
      .$theme_url .'js/common.js"></script>');

    $this->EE->cp->add_to_foot('<script type="text/javascript" src="'
      .$theme_url .'js/ft.js"></script>');

    $this->EE->javascript->compile();

    // Add the CSS.
    $this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'
      .$theme_url .'css/common.css" />');

    $this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'
      .$theme_url .'css/ft.css" />');

    // Prepare the view data.
    $view_data = array(
      'data_sources'  => $this->_ft_model->get_global_data_sources(),
      'formats'       => $this->_ft_model->get_global_data_source_formats(),
      'theme_url'     => $theme_url,
      'types'         => $this->_ft_model->get_global_data_source_types()
    );

    return $this->EE->load->view('global_settings', $view_data, TRUE);
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
    $this->EE->load->library('table');

    // Restore any previously-saved data.
    $current_settings = $this->_ft_model->update_array_from_input(
      $this->_ft_model->get_default_fieldtype_settings(),
      $settings
    );

    // Retrieve the global data sources, and prep. for use in a drop-down.
    $data_sources     = $this->_ft_model->get_global_data_sources();
    $dd_data_sources  = array();

    foreach ($data_sources AS $data_source)
    {
      $dd_data_sources[$data_source->id] = $data_source->title;
    }

    // Gather the view data.
    $view_data = array(
      'current_settings'      => $current_settings,
      'global_data_sources'   => $dd_data_sources,
      'options_control_types' => $this->_ft_model->get_control_types(),
      'options_source_types'  => $this->_ft_model->get_field_data_source_types()
    );

    /**
     * Somewhat confusing. We don't need to actually return the view string, or
     * even echo it out. The view uses the table->add_row method, and that is
     * apparently enough. Obviously.
     */

    $this->EE->load->view('settings', $view_data);
  }


  /**
   * Performs additional fieldtype installation actions.
   *
   * @access  public
   * @return  array
   */
  public function install()
  {
    $this->_ft_model->create_fieldtype_tables();
    return parent::install();
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
    if (is_array($data))
    {
      $data = array_shift($data);
    }

    try
    {

      // @TODO : Use constants for data source types.
      // @TODO : Refactor this method and display_field.

      switch ($this->settings['options_source_type'])
      {
        case 'global':
          $data_source = $this->_ft_model->get_global_data_source_by_id(
            $this->settings['options_global_source']);

          if ( ! $data_source OR ! $data_source->is_populated())
          {
            throw new Exception('Invalid data source in ' .__METHOD__);
          }

          if ($data_source->type == Options_data_source::TYPE_FILE)
          {
            // Load the data from a file.
            $options = $this->_ft_model->load_options_data_from_file(
              $data_source->location);
          }
          elseif ($data_source->type == Options_data_source::TYPE_URL)
          {
            // Load the data from a URL.
            $options = $this->_ft_model->load_options_data_from_url(
              $data_source->location);
          }
          else
          {
            // Express our outrage.
            throw new Exception('Invalid data source type in ' .__METHOD__);
          }

          break;

        case 'manual':
          $options = $this->_ft_model->load_options_data_from_string(
            $this->settings['options_manual_source']);
          break;

        default:
          throw new Exception('Invalid data source in ' .__METHOD__);
          break;
      }
    }
    catch (Exception $e)
    {
      // @TODO: Log the error.
      return FALSE;
    }

    return $this->_find_option_by_value($data, $options);
  }


  /**
   * Displays the field value in a template.
   *
   * @access public
   * @param  string $data    The saved field data.
   * @param  array  $params  The tag parameters.
   * @param  string $tagdata The tag data (for tag pairs).
   * @return string The modified tagdata.
   */
  public function replace_tag($data, Array $params = array(), $tagdata = '')
  {
    if ( ! $data)
    {
      return '';
    }

    return $data['value'];
  }


  /**
   * Displays the field 'value label' in a template.
   *
   * @access public
   * @param  string $data    The saved field data.
   * @param  array  $params  The tag parameters.
   * @param  string $tagdata The tag data (for tag pairs).
   * @return string The modified tagdata.
   */
  public function replace_label($data, Array $params = array(), $tagdata = '')
  {
    if ( ! $data)
    {
      return '';
    }

    return $data['label'];
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
    $data_sources = array();

    if ( ! $post_sources = $this->EE->input->post('data_source', TRUE)
      OR ! is_array($post_sources)
    )
    {
      // Delete any existing global data sources, and return.
      $this->_ft_model->delete_global_data_sources();
      return array();
    }

    foreach ($post_sources AS $post_source)
    {
      if ( ! is_array($post_source))
      {
        continue;
      }

      // Manually specify the format, as we currently only support YAML.
      $post_source['format'] = Options_data_source::FORMAT_YAML;

      $temp_source = new Options_data_source($post_source);

      if ( ! $temp_source->is_populated(FALSE))
      {
        continue;
      }

      $data_sources[] = $temp_source;
    }

    // Save the global data, and return.
    $this->_ft_model->save_global_data_sources($data_sources);
    return array();
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


  /**
   * Uninstalls the fieldtype.
   *
   * @access  public
   * @return  void
   */
  public function uninstall()
  {
    $this->_ft_model->destroy_fieldtype_tables();
    return parent::uninstall();
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
    // We only need to check for 'null' data if the field is required.
    if ( ! array_key_exists('field_required', $this->settings)
      OR $this->settings['field_required'] == 'n'
    )
    {
      return TRUE;
    }

    if (is_string($data))
    {
      $data = array($data);
    }

    foreach ($data AS $item)
    {
      if (strtolower($item) == 'null')
      {
        return $this->EE->lang->line('error_invalid_selection');
      }
    }

    return TRUE;
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

    if ($type == Options_control_type::CHECKBOX)
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


  /**
   * Retrieves an 'option', given the option value.
   *
   * @access  private
   * @param   string  $search_val   The option value.
   * @param   array   $options      An array of option to search.
   * @return  array
   */
  private function _find_option_by_value($search_val = '', Array $options)
  {
    foreach ($options AS $option_val => $option_label)
    {
      if (is_array($option_label)
        && ($sub = $this->_find_option_by_value($search_val, $option_label))
      )
      {
        return $sub;
      }

      if ($option_val === $search_val)
      {
        return array(
          'value' => $option_val,
          'label' => $option_label
        );
      }
    }

    return FALSE;
  }


}


/* End of file      : ft.options.php */
/* File location    : third_party/options/ft.options.php */
