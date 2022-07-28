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

define(['jquery', 'qtype_codeplayground/lib/codemirror',
    'qtype_codeplayground/mode/javascript/javascript',
    'qtype_codeplayground/mode/css/css',
    'qtype_codeplayground/mode/htmlmixed/htmlmixed'
], function ($, CodeMirror) {

    var codeMirros = [];

    document.querySelectorAll(".cp_all textarea").forEach((e) => {
        const modeName = e.getAttribute("data-mode");
        let codeMirror = CodeMirror.fromTextArea(e, {
            lineNumbers: true,
            styleActiveLine: true,
            mode: modeName,
            readOnly: e.getAttribute("readonly") != null,
            lineWrapping: true,
        });

        codeMirror.setSize("100%", "100%");
        codeMirros.push({ [modeName]: codeMirror });
    });

    codeMirros = Object.assign(...codeMirros);

    window.codes = codeMirros;

    //elements
    const cpAll = document.querySelector(".cp_all");
    const preview = document.getElementById("cp_preview");

    function toggleTheme() {
        document.querySelector(".cp_all").classList.toggle("dark");

        const theme = codeMirros.htmlmixed?.getOption("theme");
        let toogle = theme == "default" ? "dracula" : "default";

        for (const property in codeMirros) {
            codeMirros[property].setOption("theme", toogle);
        }
    }

    //IFRAME Visualizar
    function toggleWrap() {
        const lineWrapping = codeMirros.htmlmixed?.getOption("lineWrapping");
        for (const property in codeMirros) {
            codeMirros[property].setOption("lineWrapping", !lineWrapping);
        }
    }

    function render() {
        //disable submit
        const btnSubmit = document.querySelector("#mod_quiz-next-nav");
        if(btnSubmit){ 
            btnSubmit.setAttribute('disabled', 'disabled');
        }
        
        //loading preview
        const cp_preview_loading = document.querySelectorAll(".cp_preview_loading");
        cp_preview_loading.forEach(e => e.classList.add('show'))

        let cssCode = codeMirros.css.getValue() ?? '';
        let jsCode = codeMirros.javascript.getValue() ?? '';

        let iframeComponent = preview.contentWindow.document;
        iframeComponent.open();
        iframeComponent.writeln(`
                      ${codeMirros.htmlmixed.getValue()}
                      <style>${cssCode}</style>
                      <script>${jsCode}</script>`);

        iframeComponent.close();
        cp_preview_loading.forEach(e => e.classList.remove('show'))

        runTests();
    }

    function runTests() {
        document.querySelector("#mocha").innerHTML = "";
        mocha.run();
    }

    // Listen for keystroke events
    let timeout = null;
    cpAll.addEventListener("keyup", function (e) {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            render();
        }, 500);
    });

    function openTab(e) {
        e.preventDefault();
        //remove all active classes
        Array.from(e.target.parentNode.children).forEach((e) =>
            e.classList.remove("active")
        );
        e.target.classList.add("active");

        e.target.parentNode.parentNode.querySelectorAll(".cp_tab").forEach((e) => {
            e.style.visibility = "hidden";
        });

        document.querySelector(`#${e.target.dataset.tabid}`).style.visibility =
            "visible";
    }

    //event listeners
    document
        .querySelector(".cp_tab_toggle_theme")
        .addEventListener("click", toggleTheme);

    document
        .querySelector(".cp_tab_toggle_wrap")
        .addEventListener("click", toggleWrap);

    document.querySelectorAll(".cp_tab_links .cp_tab_btn").forEach((e) => {
        //console.log(e);
        e.addEventListener("click", openTab.bind(this));
    });


    return {
        init: function () {
            render();
        }
    };
});

