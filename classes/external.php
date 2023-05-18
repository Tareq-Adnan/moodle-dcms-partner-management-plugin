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
 *
 * @package    local_dcmspartner
 * @copyright  2023 Tarekul Islam
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/local/dcmspartner/lib.php");


class local_dcmspartner_external extends external_api
{
    // fetch_all_data


    /**
     * Returns description of method parameters.
     * @return external_function_parameters
     */
    public static function get_partner_data_parameters(): external_function_parameters
    {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * @param int 
     * @return array
     * @throws moodle_exception
     */
    public static function get_partner_data(): array
    {

        $data = local_dcms_parter_info();

        $draftInfo = [];

        foreach ($data as $a) {
            array_push($draftInfo, $a);
        }

        $result = [];
        $result['Partnerinfo'] = $draftInfo;

        return $result;

    }

    /**
     * Returns description of method result value.
     * @return external_description
     */
    public static function get_partner_data_returns()
    {
        return new external_single_structure(
            array(
                'Partnerinfo' => new external_multiple_structure(self::get_data_structure(), 'Partnerinfo', VALUE_OPTIONAL),
            )
        );
    }

    static function get_data_structure()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_RAW, 'id', VALUE_OPTIONAL),
                'picurl' => new external_value(PARAM_RAW, 'picurl', VALUE_OPTIONAL),
                'tabtid' => new external_value(PARAM_RAW, 'tabid', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_RAW, 'timemodified', VALUE_OPTIONAL),
            )
        );
    }
}