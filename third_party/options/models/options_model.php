<?php if ( ! defined('BASEPATH')) exit('Direct script access not allowed');

/**
 * Options 'Package' model.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 */

require_once dirname(__FILE__) .'/../config.php';
require_once dirname(__FILE__) .'/../classes/options_control_type.php';
require_once dirname(__FILE__) .'/../classes/options_data_source.php';
require_once dirname(__FILE__) .'/../libraries/spyc/spyc.php';

class Options_model extends CI_Model {

  protected $EE;
  protected $_data_sources_table;
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

    // Saves hard-coding this everywhere.
    $this->_data_sources_table = 'options_data_sources';

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
   * Deletes all the global data sources for the current site.
   *
   * @access  public
   * @return  void
   */
  public function delete_global_data_sources()
  {
    
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
      Options_control_type::SELECT => lang('options_control_type__select'),
      Options_control_type::CHECKBOX => lang('options_control_type__checkbox'),
      Options_control_type::RADIO => lang('options_control_type__radio'),
      Options_control_type::MULTI_SELECT
        => lang('options_control_type__multi_select')
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
   * Returns a global data source object with the given ID, or FALSE.
   *
   * @access  public
   * @param   int|string    $id    The ID.
   * @return  Options_data_source|FALSE
   */
  public function get_global_data_source_by_id($id)
  {
    if ( ! valid_int($id, 1))
    {
      throw new Exception('Invalid ID passed to ' .__METHOD__);
    }

    $fields = array('data_source_id', 'data_source_format',
      'data_source_location', 'data_source_title', 'data_source_type');

    $db_source = $this->EE->db
      ->select(implode(', ', $fields))
      ->get_where($this->_data_sources_table,
          array('data_source_id' => $id), 1);

    return $db_source->num_rows()
      ? $this->_convert_db_row_to_data_source_object($db_source->row_array())
      : FALSE;
  }


  /**
   * Returns an array of previously-saved 'global' data sources.
   *
   * @access  public
   * @return  array
   */
  public function get_global_data_sources()
  {
    $sources = array();

    $fields = array('data_source_id', 'data_source_format',
      'data_source_location', 'data_source_title', 'data_source_type');

    $db_sources = $this->EE->db
      ->select(implode(', ', $fields))
      ->get_where($this->_data_sources_table,
          array('site_id' => $this->get_site_id()));

    if ( ! $db_sources->num_rows())
    {
      return $sources;
    }

    foreach ($db_sources->result_array() AS $db_row)
    {
      $sources[] = $this->_convert_db_row_to_data_source_object($db_row);
    }

    return $sources;
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

    // Paths are relative to the "bootstrap" file (i.e. web root).
    if ( ! $file = realpath(FCPATH .ltrim($file, '/'))
      OR ! is_file($file)
    )
    {
      throw new Exception($message);
    }

    /**
     * In terms of the YAML library being used, this works in exactly the same
     * way as loading a string. This isn't as convenient as it sounds, because 
     * if the file cannot be found, the library just uses the filename as the 
     * data source. Not desired behaviour at all, so we do a 'file exists' check 
     * before handing over processing.
     */

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
   * Saves the supplied data sources to the database.
   *
   * @access  public
   * @param   array   $data_sources    An array of Options_data_source objects.
   * @return  void
   */
  public function save_global_data_sources(Array $data_sources)
  {
    $inserts = $updates = $source_ids = array();

    // Validate the data.
    foreach ($data_sources AS $data_source)
    {
      if ( ! $data_source instanceof Options_data_source)
      {
        throw new Exception('Invalid data source.');
      }

      // @TODO : check the data source properties are populated.

      if ($data_source->id)
      {
        $updates[]    = $data_source;
        $source_ids[] = $data_source->id;
      }
      else
      {
        $inserts[] = $data_source;
      }
    }

    // Delete any obsolete data sources.
    $this->EE->db->where('site_id', $this->get_site_id());

    if ($source_ids)
    {
      $this->EE->db->where_not_in('data_source_id', $source_ids);
    }

    $this->EE->db->delete($this->_data_sources_table);

    // Do we have any more work to do?
    if ( ! $inserts && ! $updates)
    {
      return;
    }

    // Update any existing data sources.
    if ($updates)
    {
      foreach ($updates AS $update)
      {
        $update_data = $update->to_array('data_source_');
        $update_data['site_id'] = $this->get_site_id();

        unset($update_data['data_source_id']);

        $this->EE->db->update($this->_data_sources_table, $update_data,
          array('data_source_id' => $update->id));
      }
    }

    // Create any new data sources.
    if ($inserts)
    {
      $insert_batch_data = array();

      foreach ($inserts AS $insert)
      {
        $insert_data = $insert->to_array('data_source_');
        $insert_data['site_id'] = $this->get_site_id();

        unset($insert_data['data_source_id']);

        $insert_batch_data[] = $insert_data;
      }

      $this->EE->db->insert_batch($this->_data_sources_table,
        $insert_batch_data);
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
   * Converts the given database row array to a Options_data_source object.
   *
   * @access  private
   * @param   array    $db_row    The DB row, or equivalent associative array.
   * @return  Options_data_source
   */
  private function _convert_db_row_to_data_source_object(Array $db_row)
  {
    // Note: we don't attempt anything in the way of validation.
    $init_data = array();

    foreach ($db_row AS $key => $val)
    {
      $init_data[str_replace('data_source_', '', $key)] = $val;
    }

    return new Options_data_source($init_data);
  }


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
