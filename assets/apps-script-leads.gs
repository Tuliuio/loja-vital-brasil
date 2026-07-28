/* ==========================================================================
   Vital Brasil — Captura de leads → Google Sheets (sem backend)
   --------------------------------------------------------------------------
   COMO USAR:
   1. Crie uma planilha nova no Google Sheets.
   2. Extensões → Apps Script. Apague o conteúdo e cole ESTE arquivo.
   3. Implantar → Nova implantação → tipo "App da Web":
        - Executar como:  Eu (sua conta)
        - Quem tem acesso: Qualquer pessoa
   4. Copie a URL do Web App (termina em /exec) e cole em LEAD_ENDPOINT
      no arquivo assets/vb-agendar.js.
   Cada lead vira uma linha na aba "Leads" (o cabeçalho é criado sozinho).
   ========================================================================== */

var SHEET_NAME = 'Leads';
var COLUMNS = [
  'timestamp', 'exame', 'data_preferida', 'periodo', 'local_coleta', 'nome',
  'origem', 'pagina', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term',
  'utm_content', 'gclid', 'fbclid', 'referrer'
];

function doPost(e) {
  try {
    var lock = LockService.getScriptLock();
    lock.waitLock(30000);
    var ss = SpreadsheetApp.getActiveSpreadsheet();
    var sh = ss.getSheetByName(SHEET_NAME) || ss.insertSheet(SHEET_NAME);
    if (sh.getLastRow() === 0) sh.appendRow(COLUMNS);
    var d = JSON.parse((e && e.postData && e.postData.contents) || '{}');
    sh.appendRow(COLUMNS.map(function (k) { return d[k] != null ? d[k] : ''; }));
    lock.releaseLock();
    return ContentService.createTextOutput(JSON.stringify({ ok: true }))
      .setMimeType(ContentService.MimeType.JSON);
  } catch (err) {
    return ContentService.createTextOutput(JSON.stringify({ ok: false, error: String(err) }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

function doGet() {
  return ContentService.createTextOutput('Vital Brasil leads OK');
}
