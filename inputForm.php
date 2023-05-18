<?php
// This file is part of Moodle Course Rollover Plugin
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
 * @package     local_dcmspartner
 * @author      Tarekul Islam
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



global $DB, $COURSE, $CFG, $PAGE, $OUTPUT;
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot.'/local/dcmspartner/lib.php');
require_once($CFG->dirroot.'/local/dcmspartner/classes/form/inputPartner.php');


$context = context_system::instance();

$id = optional_param('id', 0, PARAM_INT);
$delete = optional_param('del', 0, PARAM_INT);

$PAGE->set_url(new moodle_url('/local/dcmspartner/inputForm.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('partner Info');

// Delete the record.
if ($delete == 1) {
    $DB->delete_records('local_dcms_partners', ['id' => $id]);
    $DB->delete_records('local_dcms_partners_picurl', ['id' => $id]); 
    redirect($CFG->wwwroot . '/local/dcmspartner/manage.php', "User Deleted");
}

if($id != NULL) {
    $form = new partner_input_form($CFG->wwwroot . '/local/dcmspartner/inputForm.php?id='.$id);
} else {
    $form = new partner_input_form($CFG->wwwroot . '/local/dcmspartner/inputForm.php');
}

if ($form->is_cancelled()) {
    // Go back to partnermanage.php page
    redirect($CFG->wwwroot . '/local/dcmspartner/manage.php',"Form Cancelled!");

} else if ($fromform = $form->get_data()) {
   
    if ( $id != NULL) {
        // We are updating an existing record.
        global $DB;

        update_records_partner($fromform, $id);
        update_record_partner_picurl ($fromform, $id);
        redirect($CFG->wwwroot . '/local/dcmspartner/manage.php', 'Info Updated');
        }

        try {
            // Insert data into tables.
            insert_partner_record($fromform);
            insert_partner_img_record($fromform);

        } catch  (dml_exception $e) {
            return false;
        }

    // Go back to partnermanage.php page.
    redirect($CFG->wwwroot . '/local/dcmspartner/manage.php', "Partner Added");
}

if ($id !=0) {
    // Add extra data to the form.
    global $DB;
    $manager = $DB->get_record('local_dcms_partners', ['id' => $id]);
    //$message = $manager->get_partner($id);

    if (!$id) {
        throw new invalid_parameter_exception('Message not found');
    }
    $form->set_data($manager);
}

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();
