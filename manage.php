<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version information
 *
 * @package    local_dcmspartner
 * @copyright  2023 Tarekul Islam
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once('./lib.php');
global $DB, $PAGE, $OUTPUT;


$PAGE->set_context(\context_system::instance());
$PAGE->set_title("Partners Information");
$PAGE->set_url(new moodle_url("/local/dcmspartner/manage.php"));


echo $OUTPUT->header();

$sql = "SELECT ld.id,ldp.id, ld.partnername,ldp.picurl FROM {local_dcms_partners} as ld INNER JOIN {local_dcms_partners_picurl} as ldp ON ld.tabid=ldp.tabid";
$data = $DB->get_records_sql($sql);
$val = "add New";
$back = "back";

$textContext = (object) [

  'message' => array_values($data),
  'editpage' => new moodle_url("/local/dcmspartner/inputForm.php"),
  'val' => $val,
  'back' => $back,
  'backurl' => new moodle_url('https://moodle.test/?redirect=0')

];

echo $OUTPUT->render_from_template('local_dcmspartner/manage', $textContext);

$PAGE->requires->js_call_amd('dcmspartner/index', null, array());

echo $OUTPUT->footer();