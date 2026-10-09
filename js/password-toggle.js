document.querySelectorAll('.password-toggle').forEach(function(botao) {
    const campo = document.getElementById(botao.getAttribute('aria-controls'));
    if (!campo) {
        return;
    }

    botao.addEventListener('click', function() {
        const mostrarSenha = campo.type === 'password';
        campo.type = mostrarSenha ? 'text' : 'password';
        botao.setAttribute('aria-pressed', String(mostrarSenha));
        botao.setAttribute('aria-label', mostrarSenha ? 'Ocultar senha' : 'Mostrar senha');
    });
});
