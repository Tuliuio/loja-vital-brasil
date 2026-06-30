</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <img src="<?= e(config('logo_claro')) ?>" alt="<?= e(config('site_nome')) ?>" class="footer-logo-img"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
            <div class="logo-text footer-logo" style="display:none;">Vital<strong>Brasil</strong></div>
            <p><?= e(config('site_slogan')) ?></p>
        </div>
        <div>
            <h4>Contato</h4>
            <p><?= e(config('endereco')) ?></p>
            <p>Tel: <?= e(config('telefone')) ?></p>
            <p><?= e(config('horario')) ?></p>
            <p><a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener">Fale no WhatsApp</a></p>
        </div>
        <div>
            <h4>Links</h4>
            <p><a href="index.php">Todos os combos</a></p>
            <p><a href="<?= e(config('site_url')) ?>" target="_blank" rel="noopener">Site oficial</a></p>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            &copy; <?= date('Y') ?> <?= e(config('site_nome')) ?>. Os exames seguem orientação médica.
        </div>
    </div>
</footer>
<script src="assets/js/main.js"></script>
</body>
</html>
