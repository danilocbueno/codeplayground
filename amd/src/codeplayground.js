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


    let htmlTextArea = document.getElementById("cp_html");
    let cssTextArea = document.getElementById("cp_css");
    let jsTextArea = document.getElementById("cp_js");

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


    const htmlField = document.getElementById("cp_html");
    const cssField = document.getElementById("cp_css");
    const jsField = document.getElementById("cp_js");
    const preview = document.getElementById("cp_preview");

    function render() {
        let iframeComponent = preview.contentWindow.document;

        iframeComponent.open();

        iframeComponent.writeln(`
            ${htmlCodeMirror.getValue()}
            <style>${cssCodeMirror.getValue()}</style>
            <script>${jsCodeMirror.getValue()}</script>`);

        iframeComponent.close();
    }

    function compile() {
        document.addEventListener('keyup', function() {
            render();
        });
    };

    return {
        init: function() {

            console.log("legal legal");
            compile();
            render();

            // Put whatever you like here. $ is available
            // to you as normal.
            $(".someclass").change(function() {
                alert("It changed!!");
            });
        }
    };
});

