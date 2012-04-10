<?php

/**
 * Options NSM Add-on Updater information.
 *
 * @author          Stephen Lewis (http://github.com/experience/)
 * @copyright       Experience Internet
 * @package         Options
 * @version         0.1.0
 */

if ( ! defined('OPTIONS_NAME'))
{
  define('OPTIONS_NAME', 'Options');
  define('OPTIONS_TITLE', 'Options');
  define('OPTIONS_VERSION', '0.1.0');
}

$config['name']     = OPTIONS_NAME;
$config['version']  = OPTIONS_VERSION;
$config['nsm_addon_updater']['versions_xml']
  = 'http://experienceinternet.co.uk/software/feeds/options';

/* End of file      : config.php */
/* File location    : third_party/options/config.php */