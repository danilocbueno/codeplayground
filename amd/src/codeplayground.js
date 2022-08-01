import CodeMirror from "qtype_codeplayground/lib/codemirror";
import "qtype_codeplayground/mode/javascript/javascript";
import "qtype_codeplayground/mode/css/css";
import "qtype_codeplayground/mode/htmlmixed/htmlmixed";

let editors = [];
let timer; //deal with keypress

document.querySelectorAll(".cp_all").forEach((e, index) => {
  let codeMirrors = [];
  const iframePreview = e.querySelector(".browser-mockup");
  const cmName = "cm" + index;

  e.querySelectorAll("textarea").forEach((e) => {
    const modeName = e.getAttribute("data-mode");
    let cm = CodeMirror.fromTextArea(e, {
      lineNumbers: true,
      styleActiveLine: true,
      mode: modeName,
      readOnly: e.getAttribute("readonly") != null,
      lineWrapping: true,
    });

    cm.on("changes", (e) => {
      window.clearTimeout(timer);
      //console.log("typing...");
    });

    cm.on("changes", (e) => {
      window.clearTimeout(timer);
      timer = window.setTimeout(() => {
        render(cmName);
      }, 1000);
    });

    cm.setSize("100%", "100%");
    codeMirrors.push({ [modeName]: cm });
  });

  editors[cmName] = {
    cms: Object.assign(...codeMirrors),
    preview: iframePreview,
  };

  render(cmName);
});

function toggleTheme() {
  //TODO FIX the theme change using localstorage
  document.querySelectorAll(".cp_all").forEach((e) => {
    e.classList.toggle("dark");
  });

  document.querySelectorAll(".cp_tab_toggle_theme svg").forEach((e) => {
    toggleShowHide(e);
  });

  const theme = editors.cm0?.cms.htmlmixed.getOption("theme");
  let toogle = theme == "default" ? "dracula" : "default";

  for (const cmName in editors) {
    for (const cm in editors[cmName].cms) {
      editors[cmName].cms[cm].setOption("theme", toogle);
    }
  }
}

//IFRAME Visualizar
function toggleWrap() {
  const lineWrapping = editors.cm0?.cms.htmlmixed.getOption("lineWrapping");

  for (const cmName in editors) {
    for (const cm in editors[cmName].cms) {
      editors[cmName].cms[cm].setOption("lineWrapping", !lineWrapping);
    }
  }

  document.querySelectorAll(".cp_tab_toggle_wrap svg").forEach((e) => {
    toggleShowHide(e);
  });
}

function toggleShowHide(e) {
  if (e.classList.contains("cp_show")) {
    e.classList.remove("cp_show");
    e.classList.add("cp_hide");
  } else {
    e.classList.add("cp_show");
    e.classList.remove("cp_hide");
  }
}

function render(cmName) {
  const editor = editors[cmName];
  if (!editor) return;

  const cms = editor.cms;
  const cp_preview_loading = editor.preview.querySelector(
    ".cp_preview_loading"
  );
  const iframeComponent =
    editor.preview.querySelector(".cp_preview").contentWindow.document;
  // const btnSubmit = document.querySelector("#mod_quiz-next-nav");
  // btnSubmit.setAttribute("disabled", "disabled");

  let cssCode = cms.css.getValue() ?? "";
  let jsCode = cms.javascript.getValue() ?? "";

  cp_preview_loading.classList.add("show");

  iframeComponent.open();
  iframeComponent.writeln(`
              ${cms.htmlmixed.getValue()}
              <style>${cssCode}</style>
              <script>${jsCode}</script>`);

  iframeComponent.close();

  cp_preview_loading.classList.remove("show");

  if (typeof mocha !== 'undefined' && !document.querySelector('.specificfeedback')) { //TODO Fix this
    mocha.run();
  }
}

function openTab(e) {
  e.preventDefault();
  //deal with tabs tiltes
  Array.from(e.target.parentNode.querySelectorAll(".cp_tab_btn")).forEach((e) =>
    e.classList.remove("active")
  );
  e.target.classList.add("active");

  //deal with tabs content
  const tabid = e.target.dataset.tabid;
  const editor = e.target.parentNode.parentNode;
  editor.querySelectorAll(".cp_tab").forEach((e) => {
    e.style.visibility = e.getAttribute("id") == tabid ? "visible" : "hidden";
  });
}

//event listeners
document.querySelectorAll(".cp_tab_toggle_theme").forEach((e) => {
  e.addEventListener("click", toggleTheme);
});

document.querySelectorAll(".cp_tab_toggle_wrap").forEach((e) => {
  e.addEventListener("click", toggleWrap);
});

document.querySelectorAll(".cp_tab_links .cp_tab_btn").forEach((e) => {
  e.addEventListener("click", openTab.bind(this));
});

export const init = () => {
  console.log("codeplayground init");
};
