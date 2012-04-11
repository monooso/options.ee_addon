<?php if ( ! defined('BASEPATH')) exit('Direct script access not allowed');

/**
 * Options 'Package' model.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once dirname(__FILE__) .'/../config.php';
require_once dirname(__FILE__) .'/../classes/control_type.php';
require_once dirname(__FILE__) .'/../libraries/spyc/spyc.php';

class Options_model extends CI_Model {

  protected $EE;
  protected $_namespace;
  protected $_package_name;
  protected $_package_title;
  protected $_package_version;
  protected $_site_id;


  /* --------------------------------------------------------------
   * PUBLIC METHODS
   * ------------------------------------------------------------ */

  /**
   * Constructor.
   *
   * @access  public
   * @param   string    $package_name       Package name. Used for testing.
   * @param   string    $package_title      Package title. Used for testing.
   * @param   string    $package_version    Package version. Used for testing.
   * @param   string    $namespace          Session namespace. Used for testing.
   * @return  void
   */
  public function __construct($package_name = '', $package_title = '',
    $package_version = '', $namespace = ''
  )
  {
    parent::__construct();

    $this->EE =& get_instance();

    // Load the OmniLogger class.
    if (file_exists(PATH_THIRD .'omnilog/classes/omnilogger.php'))
    {
      include_once PATH_THIRD .'omnilog/classes/omnilogger.php';
    }

    $this->_namespace = $namespace ? strtolower($namespace) : 'experience';

    $this->_package_name = $package_name
      ? strtolower($package_name) : strtolower(OPTIONS_NAME);

    $this->_package_title = $package_title
      ? $package_title : OPTIONS_TITLE;

    $this->_package_version = $package_version
      ? $package_version : OPTIONS_VERSION;

    // Initialise the add-on cache.
    if ( ! array_key_exists($this->_namespace, $this->EE->session->cache))
    {
      $this->EE->session->cache[$this->_namespace] = array();
    }

    if ( ! array_key_exists($this->_package_name,
      $this->EE->session->cache[$this->_namespace]))
    {
      $this->EE->session->cache[$this->_namespace]
        [$this->_package_name] = array();
    }
  }


  /**
   * Returns an associative array of the supported form control types.
   *
   * @access public
   * @return array
   */
  public function get_control_types()
  {
    $this->EE->lang->loadfile('options_ft', 'options');

    return array(
      Control_type::SELECT    => lang('options_control_type__select'),
      Control_type::CHECKBOX  => lang('options_control_type__checkbox'),
      Control_type::RADIO     => lang('options_control_type__radio'),
      Control_type::MULTI_SELECT => lang('options_control_type__multi_select')
    );
  }


  /**
   * Returns an associative array of the supported 'field' data source types.
   *
   * @access public
   * @return array
   */
  public function get_field_data_source_types()
  {
    $this->EE->lang->loadfile('options_ft', 'options');

    return array(
      'global'  => lang('data_source_global'),
      'manual'  => lang('data_source_manual')
    );
  }


  /**
   * Returns an associative array of the supported 'global' data source formats.
   *
   * @access  public
   * @return  array
   */
  public function get_global_data_source_formats()
  {
    $this->EE->lang->loadfile('options_ft', 'options');

    return array(
      'php_array' => lang('data_format_php_array'),
      'yaml'      => lang('data_format_yaml')
    );
  }


  /**
   * Returns an associative array of the supported 'global' data source types.
   *
   * @access  public
   * @return  array
   */
  public function get_global_data_source_types()
  {
    $this->EE->lang->loadfile('options_ft', 'options');

    return array(
      'file'  => lang('data_source_file'),
      'url'   => lang('data_source_url')
    );
  }


  /**
   * Returns an associative array of fieldtype settings.
   *
   * @access public
   * @return array
   */
  public function get_default_fieldtype_settings()
  {
    return array(
      'options_control_type'  => 'select',
      'options_global_source' => '',
      'options_manual_source' => '',
      'options_source_type'   => 'manual'
    );
  }


  /**
   * Returns the package name.
   *
   * @access  public
   * @return  string
   */
  public function get_package_name()
  {
    return $this->_package_name;
  }


  /**
   * Returns the package theme URL.
   *
   * @access  public
   * @return  string
   */
  public function get_package_theme_url()
  {
    // Much easier as of EE 2.4.0.
    if (defined('URL_THIRD_THEMES'))
    {
      return URL_THIRD_THEMES .$this->get_package_name() .'/';
    }

    return $this->EE->config->slash_item('theme_folder_url')
      .'third_party/' .$this->get_package_name() .'/';
  }


  /**
   * Returns the package title.
   *
   * @access  public
   * @return  string
   */
  public function get_package_title()
  {
    return $this->_package_title;
  }


  /**
   * Returns the package version.
   *
   * @access  public
   * @return  string
   */
  public function get_package_version()
  {
    return $this->_package_version;
  }


  /**
   * Returns the site ID.
   *
   * @access  public
   * @return  int
   */
  public function get_site_id()
  {
    if ( ! $this->_site_id)
    {
      $this->_site_id = (int) $this->EE->config->item('site_id');
    }

    return $this->_site_id;
  }


  /**
   * Loads the 'options' data from the given file.
   *
   * @access  public
   * @param   string  $file   The file.
   * @return  array
   */
  public function load_options_data_from_file($file)
  {
    $message = 'Unable to load options data in ' .__CLASS__ .'::' .__METHOD__;

    // Works in exactly the same way as loading a string.
    try
    {
      return $this->load_options_data_from_string($file);
    }
    catch (Exception $e)
    {
      throw new Exception($message);
    }
  }


  /**
   * Parses the 'options' data from the given string.
   *
   * @access  public
   * @param   string  $input  The data string.
   * @return  array
   */
  public function load_options_data_from_string($input)
  {
    $message = 'Unable to load options data in ' .__CLASS__ .'::' .__METHOD__;

    if ( ! $options = Spyc::YAMLLoad($input))
    {
      throw new Exception($message);
    }

    return $options;
  }


  /**
   * Loads the 'options' data from the given URL.
   *
   * @access  public
   * @param   string  $url  The URL.
   * @return  array
   */
  public function load_options_data_from_url($url)
  {
    $message = 'Unable to load options data in ' .__CLASS__ .'::' .__METHOD__;

    if ( ! is_string($url) OR ! $url)
    {
      throw new Exception($message);
    }

    // @TODO: Perform some data cleansing on the URL before passing to 
    // file_get_contents. Note that we can't simple urlencode it all. Thanks, 
    // PHP.

    // Let's keep this simple, and avoid cURL for now.
    if ( ! $input = file_get_contents($url))
    {
      throw new Exception($message);
    }

    // Parse the URL data.
    try
    {
      return $this->load_options_data_from_string($input);
    }
    catch (Exception $e)
    {
      throw new Exception($message);
    }
  }


  /**
   * Logs a message to OmniLog.
   *
   * @access  public
   * @param   string      $message        The log entry message.
   * @param   int         $severity       The log entry 'level'.
   * @return  void
   */
  public function log_message($message, $severity = 1)
  {
    if (class_exists('Omnilog_entry') && class_exists('Omnilogger'))
    {
      switch ($severity)
      {
        case 3:
          $notify = TRUE;
          $type   = Omnilog_entry::ERROR;
          break;

        case 2:
          $notify = FALSE;
          $type   = Omnilog_entry::WARNING;
          break;

        case 1:
        default:
          $notify = FALSE;
          $type   = Omnilog_entry::NOTICE;
          break;
      }

      $omnilog_entry = new Omnilog_entry(array(
        'addon_name'    => 'Options',
        'date'          => time(),
        'message'       => $message,
        'notify_admin'  => $notify,
        'type'          => $type
      ));

      Omnilogger::log($omnilog_entry);
    }
  }


  /**
   * Updates a 'base' array with data contained in an 'update' array. Both
   * arrays are assumed to be associative.
   *
   * - Elements that exist in both the base array and the update array are
   *   updated to use the 'update' data.
   * - Elements that exist in the update array but not the base array are
   *   ignored.
   * - Elements that exist in the base array but not the update array are
   *   preserved.
   *
   * @access public
   * @param  array  $base   The 'base' array.
   * @param  array  $update The 'update' array.
   * @return array
   */
  public function update_array_from_input(Array $base, Array $update)
  {
    return array_merge($base, array_intersect_key($update, $base));
  }


  /* --------------------------------------------------------------
   * PRIVATE METHODS
   * ------------------------------------------------------------ */

  /**
   * Returns a references to the package cache. Should be called
   * as follows: $cache =& $this->_get_package_cache();
   *
   * @access  private
   * @return  array
   */
  protected function &_get_package_cache()
  {
    return $this->EE->session->cache[$this->_namespace][$this->_package_name];
  }


}


/* End of file      : options_model.php */
/* File location    : third_party/options/models/options_model.php */
