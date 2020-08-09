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

        $readonly = $options->readonly ? "readonly='readonly'" : '';
        $result = <<<EOF
        <div class="cp_all">
            <div class="qtext">$questiontext</div>
            <div class="cp_wrapper">
                <div class="cp_editor">
    
                    <div class="cp_tab_links">
                        <button data-tabid="cp_tab_html" class="cp_tab_btn active">HTML</button>
                        <button data-tabid="cp_tab_css" class="cp_tab_btn" >CSS</button>
                        <button class="cp_tab_btn cp_tab_btn_no-border" data-tabid="cp_tab_js">JavaScript</button>
                    </div>
    
                    <div class="cp_tabs">
                        <div class="cp_tab" id="cp_tab_html">
                            <textarea name="$inputHTML" id="cp_html" class="language-html" $readonly >$currentanswerHTML</textarea>
                        </div>
    
                        <div class="cp_tab" id="cp_tab_css">
                            <textarea name="$inputCSS" id="cp_css" class="language-css" $readonly >$currentanswerCSS</textarea>
                        </div>
                        <div class="cp_tab" id="cp_tab_js">
                            <textarea name="$inputJS" id="cp_js" class="language-js" $readonly >$currentanswerJS</textarea>
                        </div>
                    </div>
                </div>
    
                <div class="browser-mockup">
                    <iframe id="cp_preview"></iframe>
                </div>
                
            </div>
        </div>
EOF;
        //$this->page->requires->js('qtype_codeplayground/codeplayground/prism.js', true);
        //$this->page->requires->css('qtype_codeplayground/css/codemirror.css', true);
        $this->page->requires->js_call_amd('qtype_codeplayground/codeplayground', 'init');
        return $result;
    }

    public function specific_feedback(question_attempt $qa) {
        global $DB, $CFG;

        // get feedback from the database
        $record = $DB->get_record ( 'qtype_codeplay_feedback', array (
            'questionattemptid' => $qa->get_database_id ()
        ), 'feedback' );

        if ( $record === false ) {
            $feedback = '';
        } else {
            $feedback = $record->feedback;
        }

        return html_writer::tag('div', $feedback, array('class' => 'feedback'));
    }

    public function correct_response(question_attempt $qa) {
        // TODO.
        return '';
    }


}
