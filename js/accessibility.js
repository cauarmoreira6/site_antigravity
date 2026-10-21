(() => {
    const textSizeToggle = document.getElementById('textSizeToggle');
    const contrastToggle = document.getElementById('contrastToggle');
    const readPageToggle = document.getElementById('readPageToggle');
    const stopReading = document.getElementById('stopReading');
    const accessibilityStatus = document.getElementById('accessibilityStatus');
    const root = document.documentElement;

    if (!textSizeToggle || !contrastToggle || !readPageToggle || !stopReading || !accessibilityStatus) return;

    let leituraIniciada = false;
    let falaAtual;

    function atualizarControlesDeLeitura(ativa, pausada = false) {
        leituraIniciada = ativa;
        readPageToggle.setAttribute('aria-pressed', String(ativa));
        readPageToggle.textContent = ativa ? (pausada ? 'Continuar leitura' : 'Pausar leitura') : 'Ouvir esta página';
        stopReading.disabled = !ativa;
    }

    readPageToggle.addEventListener('click', () => {
        if (!('speechSynthesis' in window) || !('SpeechSynthesisUtterance' in window)) {
            accessibilityStatus.textContent = 'A leitura em voz alta não está disponível neste navegador. Use o leitor de tela do seu dispositivo.';
            return;
        }

        if (leituraIniciada && window.speechSynthesis.paused) {
            window.speechSynthesis.resume();
            atualizarControlesDeLeitura(true);
            accessibilityStatus.textContent = 'Leitura retomada.';
            return;
        }

        if (leituraIniciada) {
            window.speechSynthesis.pause();
            atualizarControlesDeLeitura(true, true);
            accessibilityStatus.textContent = 'Leitura pausada.';
            return;
        }

        const conteudo = document.getElementById('main-content');
        const texto = conteudo?.innerText.trim();
        if (!texto) {
            accessibilityStatus.textContent = 'Não encontrei conteúdo para ler nesta página.';
            return;
        }

        const fala = new SpeechSynthesisUtterance(texto);
        falaAtual = fala;
        fala.lang = 'pt-BR';
        fala.onstart = () => {
            atualizarControlesDeLeitura(true);
            accessibilityStatus.textContent = 'Iniciando a leitura desta página.';
        };
        fala.onpause = () => atualizarControlesDeLeitura(true, true);
        fala.onresume = () => atualizarControlesDeLeitura(true);
        fala.onend = () => {
            falaAtual = null;
            atualizarControlesDeLeitura(false);
            accessibilityStatus.textContent = 'Leitura da página concluída.';
        };
        fala.onerror = (evento) => {
            falaAtual = null;
            atualizarControlesDeLeitura(false);
            accessibilityStatus.textContent = `Não foi possível ler esta página: ${evento.error}.`;
        };

        atualizarControlesDeLeitura(true);
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(fala);
    });

    stopReading.addEventListener('click', () => {
        if (!('speechSynthesis' in window)) return;
        if (falaAtual) {
            falaAtual.onstart = null;
            falaAtual.onpause = null;
            falaAtual.onresume = null;
            falaAtual.onend = null;
            falaAtual.onerror = null;
            falaAtual = null;
        }
        window.speechSynthesis.cancel();
        atualizarControlesDeLeitura(false);
        accessibilityStatus.textContent = 'Leitura interrompida.';
    });

    function lerPreferencia(chave) {
        try {
            return localStorage.getItem(chave) === 'true';
        } catch (erro) {
            console.error('Não foi possível carregar uma preferência de acessibilidade.', erro);
            return false;
        }
    }

    function salvarPreferencia(chave, ativa) {
        try {
            localStorage.setItem(chave, String(ativa));
        } catch (erro) {
            console.error('Não foi possível salvar uma preferência de acessibilidade.', erro);
        }
    }

    const textoAmpliado = lerPreferencia('mathplay-texto-ampliado');
    const altoContraste = lerPreferencia('mathplay-alto-contraste');
    root.classList.toggle('accessibility-large-text', textoAmpliado);
    document.body.classList.toggle('accessibility-high-contrast', altoContraste);
    textSizeToggle.setAttribute('aria-pressed', String(textoAmpliado));
    contrastToggle.setAttribute('aria-pressed', String(altoContraste));
    textSizeToggle.textContent = textoAmpliado ? 'Texto normal' : 'Aumentar texto';
    contrastToggle.textContent = altoContraste ? 'Contraste normal' : 'Alto contraste';

    textSizeToggle.addEventListener('click', () => {
        const enabled = root.classList.toggle('accessibility-large-text');
        salvarPreferencia('mathplay-texto-ampliado', enabled);
        textSizeToggle.setAttribute('aria-pressed', String(enabled));
        textSizeToggle.textContent = enabled ? 'Texto normal' : 'Aumentar texto';
    });

    contrastToggle.addEventListener('click', () => {
        const enabled = document.body.classList.toggle('accessibility-high-contrast');
        salvarPreferencia('mathplay-alto-contraste', enabled);
        contrastToggle.setAttribute('aria-pressed', String(enabled));
        contrastToggle.textContent = enabled ? 'Contraste normal' : 'Alto contraste';
    });
})();
