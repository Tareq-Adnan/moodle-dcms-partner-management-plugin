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
defined('MOODLE_INTERNAL') || die;

//get all records from databse
function local_dcms_parter_info()
{
    global $DB;

    try {
        // $sql="SELECT pt.partnername, pti.image FROM {local_dcms_partners} pt JOIN {local_dcms_partners_picurl} pti ON pt.draftid=pti.draftid ";

        $info = $DB->get_records('local_dcms_partners_picurl');
        //  $info = $DB->get_records_sql('upcommingcourse');
        return $info;

    } catch (Exception $e) {
        throw new moodle_exception($e);
    }
}
function get_partners() {
    global $DB;
    $SQL_partners = 'SELECT sb.id, sb.partnername as partner_name, sbu.picurl as partner_image
                        FROM {local_dcms_partners} sb
                        JOIN {local_dcms_partners_picurl} sbu';

    $partner = $DB->get_records_sql($SQL_partners);
    return $partner;
}
//insert records into database
function insert_partner_record(stdClass $formData)
{
    global $DB;

    $partnerInfo = new stdClass();
    $partnerInfo->tabid = $formData->tabid;
    $partnerInfo->partnername = $formData->partnername;
    $partnerInfo->timemodified = time();

    $DB->insert_record('local_dcms_partners', $partnerInfo, false);

}
function insert_partner_img_record(stdClass $formData)
{
    global $DB;
    $image = local_dcms_convert_image_url($formData);
    $partnerInfo = new stdClass();
    $partnerInfo->tabid = $formData->tabid;
    $partnerInfo->picurl = $image;
    $partnerInfo->timemodified = time();

    $DB->insert_record('local_dcms_partners_picurl', $partnerInfo);

}

//convert uploaded image as link 
function  local_dcms_convert_image_url (stdClass $fromform) {
    $image = '';
    $context = context_system::instance();

    $filemanageropts = array('subdirs' => 0, 'maxbytes' => '0', 'maxfiles' => 50, 'context' => $context);
    //adding a new feature
    file_save_draft_area_files( $fromform->tabid, $context->id, 'local_dcmspartner', 'attachment',  $fromform->tabid, $filemanageropts);

    if ($fromform->tabid) {
        $fs = get_file_storage();

        if ($files = $fs->get_area_files($context->id, 'local_dcmspartner', 'attachment',  $fromform->tabid, 'sortorder', false)) {
            // Look through each file being managed
            foreach ($files as $file) {

                // Build the File URL. Long process! But extremely accurate.
                $fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
                // Display the image

                $download_url = $fileurl->get_port() ? $fileurl->get_scheme() . '://' . $fileurl->get_host() . ':' . $fileurl->get_port() . $fileurl->get_path() : $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path();
                $image = $download_url;

            }
        }
    }
    return $image;
}

//update Partner information
function update_records_partner(stdclass $formData, int $id)
{
    global $DB;
    $data = new stdClass();
    $data->id = $id;
    $data->tabid = $formData->tabid;
    $data->partnername = $formData->partnername;
    $data->timemodified = time();
    $DB->update_record('local_dcms_partner', $data, ['id' => $id]);
}
function update_record_partner_picurl(stdclass $formData, int $id)
{
    global $DB;
    $data = new stdClass();
    $image=local_dcms_convert_image_url($formData);
    $data->id=$formData->id;
    $pirurl=$image;
    $data->tabid=$formData->tabid;
    $data->timemodified=time();
    $DB->update_record('local_dcms_partners_picurl', $data, ['id'=>$id]);

}