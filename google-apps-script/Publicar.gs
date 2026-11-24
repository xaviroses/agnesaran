/**
 * Publicació automàtica de agnesaran.com des del Google Sheet.
 *
 * INSTAL·LACIÓ (un sol cop, ho fa en Xavi):
 *   1. Al full: Extensions → Apps Script. Enganxa aquest codi i desa.
 *   2. Torna al full i recarrega'l. Apareix el menú "Web".
 *   3. Web → Configurar el token de GitHub (un token "fine-grained" només per al
 *      repositori xaviroses/agnesaran, amb el permís "Contents: Read and write").
 *   4. Web → Activar la publicació automàtica (Google demanarà permisos: accepta'ls).
 *
 * FUNCIONAMENT:
 *   - Cada canvi al full queda apuntat.
 *   - Cada 5 minuts, si hi ha hagut canvis, es demana a GitHub que regeneri la web (1–2 min).
 *   - Web → Publicar els canvis ara: publica a l'instant.
 *   El token es guarda a les propietats de l'script, mai dins del full.
 */

var REPO = 'xaviroses/agnesaran';

function onOpen() {
  SpreadsheetApp.getUi()
    .createMenu('Web')
    .addItem('Publicar els canvis ara', 'publicarAra')
    .addSeparator()
    .addItem('Activar la publicació automàtica', 'activarPublicacioAutomatica')
    .addItem('Configurar el token de GitHub', 'configurarToken')
    .addToUi();
}

/** Un canvi al full: l'apuntem perquè es publiqui a la propera revisió. */
function marcarCanvi(e) {
  PropertiesService.getScriptProperties().setProperty('PENDENT', String(Date.now()));
}

/** S'executa cada 5 minuts: si hi ha canvis pendents, publica. */
function publicarSiCal() {
  var props = PropertiesService.getScriptProperties();
  if (!props.getProperty('PENDENT')) return;
  if (enviarAGithub_()) props.deleteProperty('PENDENT');
}

function publicarAra() {
  var ok = enviarAGithub_();
  if (ok) PropertiesService.getScriptProperties().deleteProperty('PENDENT');
  SpreadsheetApp.getUi().alert(ok
    ? 'Fet! La web s\'actualitzarà en 1 o 2 minuts.'
    : 'No s\'ha pogut publicar. Avisa en Xavi.');
}

function activarPublicacioAutomatica() {
  var ss = SpreadsheetApp.getActive();
  ScriptApp.getProjectTriggers().forEach(function (t) { ScriptApp.deleteTrigger(t); });
  ScriptApp.newTrigger('marcarCanvi').forSpreadsheet(ss).onChange().create();
  ScriptApp.newTrigger('publicarSiCal').timeBased().everyMinutes(5).create();
  SpreadsheetApp.getUi().alert('Publicació automàtica activada: els canvis sortiran a la web en uns minuts.');
}

function configurarToken() {
  var ui = SpreadsheetApp.getUi();
  var r = ui.prompt('Token de GitHub', 'Enganxa el token (fine-grained, només per a ' + REPO + '):', ui.ButtonSet.OK_CANCEL);
  if (r.getSelectedButton() !== ui.Button.OK) return;
  PropertiesService.getScriptProperties().setProperty('GITHUB_TOKEN', r.getResponseText().trim());
  ui.alert('Token desat.');
}

function enviarAGithub_() {
  var token = PropertiesService.getScriptProperties().getProperty('GITHUB_TOKEN');
  if (!token) return false;
  var res = UrlFetchApp.fetch('https://api.github.com/repos/' + REPO + '/dispatches', {
    method: 'post',
    contentType: 'application/json',
    headers: {
      Authorization: 'Bearer ' + token,
      Accept: 'application/vnd.github+json'
    },
    payload: JSON.stringify({ event_type: 'sheet-updated' }),
    muteHttpExceptions: true
  });
  var code = res.getResponseCode();
  if (code !== 204) console.error('GitHub ha respost ' + code + ': ' + res.getContentText());
  return code === 204;
}
