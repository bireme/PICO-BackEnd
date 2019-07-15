export function translate(content) {
    let langDB = JSON.parse($('#LangTransContainer').text());
    return langDB[content];
}
