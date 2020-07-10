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
 * codeplayground question renderer class.
 *
 * @package    qtype
 * @subpackage codeplayground
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Generates the output for codeplayground questions.
 *
 * @copyright  THEYEAR YOURNAME (YOURCONTACTINFO)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_codeplayground_renderer extends qtype_renderer {
    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {

        $question = $qa->get_question();
        $currentanswer = $qa->get_last_qt_var('answer');
        $inputname = $qa->get_qt_field_name('answer');

        $inputname2 = $qa->get_qt_field_name('answer2');
        $currentanswer2 = $qa->get_last_qt_var('answer2');

        $inputname3 = $qa->get_qt_field_name('answer3');
        $currentanswer3 = $qa->get_last_qt_var('answer3');


        $inputattributes2 = array(
            'type' => 'text',
            'name' => $inputname2,
            'value' => $currentanswer2,
            'id' => $inputname2,
            'size' => 80,
            'class' => 'form-control d-inline',
        );

        $inputattributes = array(
            'type' => 'text',
            'name' => $inputname,
            'value' => $currentanswer,
            'id' => $inputname,
            'size' => 80,
            'class' => 'form-control d-inline',
        );

        if ($options->readonly) {
            $inputattributes['readonly'] = 'readonly';
            $inputattributes2['readonly'] = 'readonly';
        }

        $questiontext = $question->format_questiontext($qa);
        $placeholder = false;
        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
        }
        $input = html_writer::empty_tag('input', $inputattributes);
        $input2 = html_writer::empty_tag('input', $inputattributes2);

        $result = html_writer::tag('div', $questiontext, array('class' => 'qtext'));


        if ($placeholder) {
            $questiontext = substr_replace($questiontext, $input,
                    strpos($questiontext, $placeholder), strlen($placeholder));
        }else {
            //form 1
            $result .= html_writer::start_tag('div', array('class' => 'ablock form-inline'));
            $result .= html_writer::tag('label', get_string('answer', 'qtype_shortanswer',
                html_writer::tag('span', $input, array('class' => 'answer'))),
                array('for' => $inputattributes['id']));
            $result .= html_writer::end_tag('div');

            //form2
            $result .= html_writer::start_tag('div', array('class' => 'ablock form-inline'));
            $result .= html_writer::tag('label', get_string('answer', 'qtype_shortanswer',
                html_writer::tag('span', $input2, array('class' => 'answer'))),
                array('for' => $inputattributes2['id']));
            $result .= html_writer::end_tag('div');

            //form3
            $result .= html_writer::tag('div', html_writer::tag('textarea', s($currentanswer3),
                array('id' => $inputname3, 'name' => $inputname3)));

        }


        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                $question->get_validation_error(array('answer' => $currentanswer)),
                array('class' => 'validationerror'));
        }
        return $result;
    }

    public function specific_feedback(question_attempt $qa) {
        // TODO.
        $question = $qa->get_question();


        return '';
    }

    public function correct_response(question_attempt $qa) {
        // TODO.
        return '';
    }
}
