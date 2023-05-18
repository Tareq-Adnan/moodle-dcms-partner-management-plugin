<?php
/**
 *
 * @package    local_dcmspartner
 * @copyright  2023 Tarekul Islam
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
    'local_dcms_parter_info' => array(
        'classname' => 'local_dcmspartner_external',
        'methodname' => 'get_partner_data',
        'classpath' => '/local/dcmspartner/external.php',
        'description' => 'Fetch all data from database',
        'type' => 'read',
        'ajax' => true,
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
    )

);