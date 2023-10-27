import stylizedJson from 'stylized-json/index.js';

let jsonDocs = document.querySelector(".json-doc");
if (jsonDocs !== null) {
    let stylized = stylizedJson.prettyPrint(JSON.parse(jsonDocs.innerHTML));
    jsonDocs.innerHTML = stylized;
    jsonDocs.classList.remove('hidden');
}
