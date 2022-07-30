Code Playground - Question Type Moodle Plugin
----------------------
The Code Playground question type allows real-time editing and viewing of HTML, CSS and JavaScript codes. So the student can insert his code and see the result immediately in the browser.

In addition to real-time visualization, Code Playground checks the code submitted by students through the W3C API for HTML and CSS.

From the results of the API's, the plugin calculates the score of the question by making a simple calculation removing 1% for each failure committed.


## How to install

Copy the folder into `moodle/question/type`

You have to bundle the dependicies, for that, install grunt globaly with `npm install -g grunt-cli` and then run grunt from the amd directory plugin.

## Useful links
https://docs.moodle.org/dev/Javascript_Modules
https://moodledev.io/docs
https://dev.to/imiahazel/pure-html-css-tabs-2p60
https://yoksel.github.io/url-encoder/


## Load modules
The new module load JS work but load the js several times...