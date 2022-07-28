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
class qtype_codeplayground_renderer extends qtype_renderer
{
    public function formulation_and_controls(
        question_attempt $qa,
        question_display_options $options
    ) {

        $question = $qa->get_question();

        //HTML editor
        $currentanswerHTML = $qa->get_last_qt_var('answer');
        $inputHTML = $qa->get_qt_field_name('answer');
        $inputattributesHTML = array(
            'name' => $inputHTML,
            'value' => $currentanswerHTML,
            'id' => 'cp_html',
            'class' => 'language-html'
        );

        //FORM CSS
        $currentanswerCSS = $qa->get_last_qt_var('answerCSS');
        $inputCSS = $qa->get_qt_field_name('answerCSS');
        $inputattributesCSS = array(
            'name' => $inputCSS,
            'value' => $currentanswerCSS,
            'id' => 'cp_css',
            'class' => 'language-css'
        );

        //FORM JS
        $currentanswerJS = $qa->get_last_qt_var('answerJS');
        $inputJS = $qa->get_qt_field_name('answerJS');
        $inputattributesJS = array(
            'name' => $inputJS,
            'value' => $currentanswerJS,
            'id' => 'cp_js',
            'class' => 'language-js'
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

        $buildTestsScript = $this->buildTests($qa);

        //createHTML
        $result = <<<EOF
        <div class="cp_all">
        <div class="qtext">$questiontext</div>
        <div class="cp_wrapper">
          <div class="cp_editor">
            <div class="cp_tab_links">
              <button data-tabid="cp_tab_html" class="cp_tab_btn active">
                <img src="/assets/img/html.svg" />
                index.html
              </button>
              <button data-tabid="cp_tab_css" class="cp_tab_btn">
                <img src="/assets/img/css.svg" />style.css
              </button>
              <button
                class="cp_tab_btn cp_tab_btn_no-border"
                data-tabid="cp_tab_js"
              >
                <img src="/assets/img/javascript.svg" />
                script.js
              </button>
  
              <a class="cp_tab_toggle_theme cp_tab_btn_conf">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M12 15q1.25 0 2.125-.875T15 12q0-1.25-.875-2.125T12 9q-1.25 0-2.125.875T9 12q0 1.25.875 2.125T12 15Zm0 1.5q-1.875 0-3.188-1.312Q7.5 13.875 7.5 12q0-1.875 1.312-3.188Q10.125 7.5 12 7.5q1.875 0 3.188 1.312Q16.5 10.125 16.5 12q0 1.875-1.312 3.188Q13.875 16.5 12 16.5ZM2 12.75q-.325 0-.538-.213-.212-.212-.212-.537 0-.325.212-.538.213-.212.538-.212h2.25q.325 0 .537.212Q5 11.675 5 12q0 .325-.213.537-.212.213-.537.213Zm17.75 0q-.325 0-.538-.213Q19 12.325 19 12q0-.325.212-.538.213-.212.538-.212H22q.325 0 .538.212.212.213.212.538 0 .325-.212.537-.213.213-.538.213ZM12 5q-.325 0-.537-.213-.213-.212-.213-.537V2q0-.325.213-.538.212-.212.537-.212.325 0 .538.212.212.213.212.538v2.25q0 .325-.212.537Q12.325 5 12 5Zm0 17.75q-.325 0-.537-.212-.213-.213-.213-.538v-2.25q0-.325.213-.538Q11.675 19 12 19q.325 0 .538.212.212.213.212.538V22q0 .325-.212.538-.213.212-.538.212ZM6 7.05 4.75 5.825q-.225-.225-.213-.538.013-.312.213-.537.225-.225.538-.225.312 0 .537.225L7.05 6q.225.225.225.525 0 .3-.225.525-.2.225-.5.212-.3-.012-.55-.212Zm12.175 12.2L16.95 18q-.225-.225-.225-.525 0-.3.225-.525.2-.225.5-.212.3.012.55.212l1.25 1.225q.225.225.213.537-.013.313-.213.538-.225.225-.537.225-.313 0-.538-.225ZM16.95 7.05q-.225-.2-.212-.5.012-.3.212-.55l1.225-1.25q.225-.225.538-.213.312.013.537.213.225.225.225.537 0 .313-.225.538L18 7.05q-.225.225-.525.225-.3 0-.525-.225Zm-12.2 12.2q-.225-.225-.225-.538 0-.312.225-.537L6 16.95q.225-.225.525-.225.3 0 .525.225.225.2.213.5-.013.3-.213.55l-1.225 1.25q-.225.225-.537.225-.313 0-.538-.225ZM12 12Z"/></svg>
  
  
                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" style="display: none"><path d="M12.025 20.5q-3.55 0-6.025-2.475Q3.525 15.55 3.525 12q0-3.4 2.3-5.825 2.3-2.425 5.65-2.625.2 0 .413.012.212.013.412.038-.775.725-1.225 1.737-.45 1.013-.45 2.163 0 2.45 1.725 4.175 1.725 1.725 4.175 1.725 1.175 0 2.175-.45 1-.45 1.7-1.225.05.2.063.412.012.213.012.413-.2 3.35-2.625 5.65-2.425 2.3-5.825 2.3Zm0-1.5q2.2 0 3.95-1.212 1.75-1.213 2.55-3.163-.5.125-1 .2-.5.075-1 .075-3.075 0-5.237-2.162Q9.125 10.575 9.125 7.5q0-.5.075-1t.2-1q-1.95.8-3.162 2.55Q5.025 9.8 5.025 12q0 2.9 2.05 4.95Q9.125 19 12.025 19Zm-.25-6.75Z"/></svg>
  
              </a>
              <a class="cp_tab_toggle_wrap cp_tab_btn_conf">              
                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24"><path d="M14.7 20.35 11.35 17l3.35-3.35 1.05 1.075-1.525 1.525h3.025q.825 0 1.413-.588.587-.587.587-1.412t-.587-1.413q-.588-.587-1.413-.587h-13v-1.5h13q1.45 0 2.475 1.025Q20.75 12.8 20.75 14.25q0 1.45-1.025 2.475Q18.7 17.75 17.25 17.75h-3.025l1.525 1.525Zm-10.45-2.6v-1.5h4.5v1.5Zm0-11v-1.5h15.5v1.5Z"/></svg>
  
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none" height="24" width="24"><path d="M3.25 17.625v-1.5h11.5v1.5Zm0-4.875v-1.5h17.5v1.5Zm0-4.875v-1.5h17.5v1.5Z"/></svg>
              </a>
            </div>
  
            <div class="cp_tabs">
              <div class="cp_tab" id="cp_tab_html">
                <textarea
                  name="$inputHTML"
                  id="cp_html"
                  data-mode="htmlmixed"
                  class="language-html"
                  $readonly
                >$currentanswerHTML</textarea
                >
              </div>
  
              <div class="cp_tab" id="cp_tab_css">
                <textarea
                  name="$inputCSS"
                  id="cp_css"
                  data-mode="css"
                  class="language-css"
                  $readonly
                >
  $currentanswerCSS</textarea
                >
              </div>
              <div class="cp_tab" id="cp_tab_js">
                <textarea
                  name="$inputJS"
                  id="cp_js"
                  class="language-js"
                  data-mode="javascript"
                  $readonly
                >
  $currentanswerJS</textarea
                >
              </div>
            </div>
          </div>
  
          <div class="browser-mockup">
            <div class="cp_preview_loading">Loading...</div>
            <iframe id="cp_preview"> </iframe>
          </div>
        </div>

        $buildTestsScript

      </div>
EOF;
        $this->page->requires->js_call_amd('qtype_codeplayground/codeplayground', 'init');
        return $result;
    }

    public function buildTests($qa) {

        $question = $qa->get_question();
        $answerTestResult = $qa->get_qt_field_name('answerTestResult');
        $generalfeedback = $question->generalfeedback;
        $test = str_replace(array('<pre>', '</pre>'), '', $generalfeedback);

        if(empty($test)) {
            return "";
        }

        // echo "<h1 style='margin-top:100px'><pre>";
        // print_r($qa);
        // echo "</pre></h1>";

        $result = <<<EOF
        
        <!--CONF Testes -->
        
        <input type="text" id="tests_results" name="$answerTestResult">

        <div id="mocha"></div>
        <script src="https://unpkg.com/chai/chai.js"></script>
        <script src="https://unpkg.com/mocha/mocha.js"></script>
        <script>
        window.addEventListener("DOMContentLoaded", function () {
            //TESTES
            console.log("mocha loaded");
        
            mocha.setup("bdd");
            mocha.cleanReferencesAfterRun(false);
        
            let failures = [];
            let successes = [];
        
            describe("test mocha with chai in Browser", function () {
              const _document =
                document.querySelector("#cp_preview").contentWindow.document;
        
              after(function () {
                console.log(
                  "successes/failures",
                  successes.length,
                  failures.length
                );
        
                let result = successes.length;
                result += '/';
                result += failures.length;
                document.querySelector("#tests_results").value = result;
        
                failures = [];
                successes = [];
        
                const btnSubmit = document.querySelector("#mod_quiz-next-nav");
                btnSubmit.removeAttribute('disabled');
              });
        
              afterEach(function () {
                const title = this.currentTest.title;
                const state = this.currentTest.state;
                if (state === "passed") {
                  successes.push(title);
                } else if (state === "failed") {
                  failures.push(title);
                }
              });
        
              //aqui vem os testes
              $test

            });
          });
        </script>
EOF;

        return $result;
    }

    public function specific_feedback(question_attempt $qa)
    {
        global $DB, $CFG;

        // get feedback from the database
        $record = $DB->get_record('qtype_codeplay_feedback', array(
            'questionattemptid' => $qa->get_database_id()
        ), 'feedback');

        if ($record === false) {
            $feedback = '';
        } else {
            $feedback = $record->feedback;
        }

        return html_writer::tag('div', $feedback, array('class' => 'feedback'));
    }

    public function correct_response(question_attempt $qa)
    {
        // TODO.
        return '';
    }
}
