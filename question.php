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
 * codeplayground question definition class.
 *
 * @package    qtype
 * @subpackage codeplayground
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Represents a codeplayground question.
 *
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


class qtype_codeplayground_question extends question_graded_automatically_with_countback {


    public function get_expected_data() {
        $resposta =array('answer' => PARAM_RAW_TRIMMED,
            'answerCSS' => PARAM_RAW_TRIMMED,
            'answerJS' => PARAM_RAW_TRIMMED);
        return $resposta;
    }


    public function summarise_response(array $response) {
        // TODO.
        return null;
    }

    public function is_complete_response(array $response) {
        // TODO.
        return true;
    }

    public function get_validation_error(array $response) {
        // TODO.
        return '';
    }

    public function is_same_response(array $prevresponse, array $newresponse) {
        // TODO.
        return question_utils::arrays_same_at_key_missing_is_blank(
                $prevresponse, $newresponse, 'answer');
    }


    public function get_correct_response() {
        // TODO.
        return array();
    }


    public function check_file_access($qa, $options, $component, $filearea,
            $args, $forcedownload) {
        // TODO.
        if ($component == 'question' && $filearea == 'hint') {
            return $this->check_hint_file_access($qa, $options, $args);

        } else {
            return parent::check_file_access($qa, $options, $component, $filearea,
                    $args, $forcedownload);
        }
    }

    public function grade_response(array $response) {
        $fraction = 0;
        if(isset($response['fraction'])) {
            $fraction = floatval($response['fraction']);
        }
        $this->
        print_object($this);

        $_SESSION['cachemaldito'] = 'cachemaldito';

        return array($fraction, question_state::graded_state_for_fraction($fraction));
    }

    public function compute_final_grade($responses, $totaltries) {
        // TODO.
        return 0;
    }
}
