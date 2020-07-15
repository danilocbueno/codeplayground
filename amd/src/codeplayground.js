// Put this file in path/to/plugin/amd/src
// You can call it anything you like
/*
window.requirejs.config({
    packages: [{
        name: "codemirror",
        location: "qtype_codeplayground",
        main: "lib/codemirror"
    }]
});
*/

console.log('hellow from here');

define(['jquery','qtype_codeplayground/lib/codemirror',
        'qtype_codeplayground/mode/javascript/javascript',
        'qtype_codeplayground/mode/css/css',
        'qtype_codeplayground/mode/htmlmixed/htmlmixed'
        ], function($, CodeMirror) {


    const cpAll = document.querySelector(".cp_all");
    const htmlTextArea = document.getElementById("cp_html");
    const cssTextArea = document.getElementById("cp_css");
    const jsTextArea = document.getElementById("cp_js");
    const preview = document.getElementById("cp_preview");

    let htmlCodeMirror = CodeMirror.fromTextArea(htmlTextArea, {
        lineNumbers: true,
        mode: "htmlmixed",
        readOnly: htmlTextArea.getAttribute("readonly") != null
    });

    let cssCodeMirror = CodeMirror.fromTextArea(cssTextArea, {
        lineNumbers: true,
        mode: "css",
        value: cssTextArea.value,
        readOnly: cssTextArea.getAttribute("readonly") != null
    });

    let jsCodeMirror = CodeMirror.fromTextArea(jsTextArea, {
        lineNumbers: true,
        mode: "javascript",
        readOnly: jsTextArea.getAttribute("readonly") != null
    });



    function render() {
        let iframeComponent = preview.contentWindow.document;

        iframeComponent.open();

        iframeComponent.writeln(`
            ${htmlCodeMirror.getValue()}
            <style>${cssCodeMirror.getValue()}</style>
            <script>${jsCodeMirror.getValue()}</script>`);

        iframeComponent.close();
    }


    // Init a timeout variable to be used below
    let timeout = null;

    // Listen for keystroke events
    cpAll.addEventListener('keyup', function (e) {
        // Clear the timeout if it has already been set.
        // This will prevent the previous task from executing
        // if it has been less than <MILLISECONDS>
        clearTimeout(timeout);

        // Make a new timeout set to go off in 1000ms (1 second)
        timeout = setTimeout(function () {
            render();
        }, 500);
    });

    return {
        init: function() {
            console.log('hellow from codeplayground!');
        }
    };
});

