</main>
<!-- Botão flutuante de WhatsApp (o carrinho fica acima dele) -->
<a class="vb-wpp-float" href="<?= e(whatsapp_link('Olá! Vim pela loja online e gostaria de mais informações.')) ?>" target="_blank" rel="noopener" aria-label="Falar no WhatsApp">
  <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3C9.4 3 4 8.4 4 15c0 2.1.6 4.1 1.6 5.9L4 29l8.3-1.6c1.7.9 3.6 1.4 5.7 1.4 6.6 0 12-5.4 12-12S22.6 3 16 3zm0 21.8c-1.8 0-3.5-.5-5-1.4l-.4-.2-4.9 1 1-4.8-.2-.4c-1-1.6-1.5-3.4-1.5-5.3C5 9.5 9.9 4.9 16 4.9S27 9.5 27 15 22.1 24.8 16 24.8zm5.6-7.3c-.3-.2-1.8-.9-2.1-1s-.5-.2-.7.2-.8 1-.9 1.2-.3.2-.6.1c-1.8-.9-3-1.6-4.2-3.6-.3-.5.3-.5.8-1.6.1-.2 0-.4 0-.5s-.7-1.7-1-2.3c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.3 5.2 4.6 2.9 1.2 2.9.8 3.5.8.5 0 1.8-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.2-.6-.4z"/></svg>
</a>
<!-- ===== Footer unificado (idêntico ao site institucional) ===== -->
<footer class="vb-footer">
  <div class="vb-footer__inner">
    <p class="vb-footer__copy">Todos os direitos reservados &copy; 2025 Por <a href="https://somosmira.com.br/" target="_blank" rel="noopener">mira comunica&ccedil;&atilde;o digital</a><span class="vb-footer__tuliu">Digital Infrastructure by <a href="https://tuliu.io" target="_blank" rel="noopener">Tuliu</a></span></p>
    <a href="/" class="vb-footer__logo" aria-label="Laborat&oacute;rio Vital Brasil">
      <img src="/wp-content/uploads/2024/04/Marca_VitalBrasil_1-1024x165.png" alt="Laborat&oacute;rio Vital Brasil" width="260" height="42">
    </a>
  </div>
</footer>
<script>window.VB_WPP = <?= json_encode(preg_replace('/\D/', '', (string) config('whatsapp'))) ?>;</script>
<script src="assets/js/main.js"></script>
<script src="assets/js/carrinho.js" defer></script>
</body>
</html>
