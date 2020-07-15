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

        //HTML editor
        $currentanswerHTML = $qa->get_last_qt_var('answer');
        $inputHTML = $qa->get_qt_field_name('answer');
        $inputattributesHTML = array(
            'name' => $inputHTML,
            'value' => $currentanswerHTML,
            'id' => 'cp_html',
            'class'=>'language-html'
        );

        //FORM CSS
        $currentanswerCSS = $qa->get_last_qt_var('answerCSS');
        $inputCSS = $qa->get_qt_field_name('answerCSS');
        $inputattributesCSS = array(
            'name' => $inputCSS,
            'value' => $currentanswerCSS,
            'id' => 'cp_css',
            'class'=>'language-css'
        );

        //FORM JS
        $currentanswerJS = $qa->get_last_qt_var('answerJS');
        $inputJS = $qa->get_qt_field_name('answerJS');
        $inputattributesJS = array(
            'name' => $inputJS,
            'value' => $currentanswerJS,
            'id' => 'cp_js',
            'class'=>'language-js'
        );

        if ($options->readonly) {
            $inputattributesHTML['readonly'] = 'readonly';
            $inputattributesCSS['readonly'] = 'readonly';
            $inputattributesJS['readonly'] = 'readonly';
        }

        $questiontext = $question->format_questiontext($qa);
        $placeholder = false;
        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
        }

        $result = html_writer::start_div('cp_all');
        $result .= html_writer::tag('div', $questiontext, array('class' => 'qtext'));
        $result .= html_writer::tag('iframe','',array('id'=>'cp_preview'));


        if ($placeholder) {
            //$questiontext = substr_replace($questiontext, $input, strpos($questiontext, $placeholder), strlen($placeholder));
        }else {
            //formHTML
            $result .= html_writer::start_tag('div', array('class' => 'cp_editor'));
            $result .= html_writer::tag('textarea', s($currentanswerHTML), $inputattributesHTML);
            $result .= html_writer::tag('textarea', s($currentanswerCSS), $inputattributesCSS);
            $result .= html_writer::tag('textarea', s($currentanswerJS), $inputattributesJS);
            $result .= html_writer::end_tag('div');

        }


        /*
        if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                $question->get_validation_error(array('answer' => $currentanswer)),
                array('class' => 'validationerror'));
        }
        */


        $result .= html_writer::end_div();
        //$this->page->requires->js('qtype_codeplayground/codeplayground/prism.js', true);
        //$this->page->requires->css('qtype_codeplayground/css/codemirror.css', true);
        $this->page->requires->js_call_amd('qtype_codeplayground/codeplayground', 'init');
        return $result;
    }

    public function specific_feedback(question_attempt $qa) {

        //var_dump('Hellow from specific_feedback');
        // TODO.
        $question = $qa->get_question();

        $question->grade_response(array('fraction'=>0.2));

        $result = html_writer::tag('div', 'I am the feeedbacksss', array('class' => 'feedback'));


        return $result;
    }

    public function correct_response(question_attempt $qa) {
        // TODO.
        return '';
    }


}
