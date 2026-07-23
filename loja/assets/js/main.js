// Scroll suave para âncoras internas (ex.: "Ver combos")
document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
        var alvo = document.querySelector(this.getAttribute('href'));
        if (alvo) {
            e.preventDefault();
            alvo.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
