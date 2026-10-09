const dificuldadesPorJogoESerie = {
    1: { 6: ['facil', 'facil', 'medio'], 7: ['facil', 'medio', 'medio'], 8: ['facil', 'medio', 'dificil'], 9: ['facil', 'medio', 'dificil'] },
    2: { 6: ['facil', 'facil', 'facil'], 7: ['facil', 'facil', 'medio'], 8: ['facil', 'medio', 'medio'], 9: ['facil', 'medio', 'dificil'] },
    3: { 6: ['facil', 'facil', 'medio'], 7: ['facil', 'medio', 'medio'], 8: ['facil', 'medio', 'dificil'], 9: ['facil', 'medio', 'dificil'] },
    4: { 6: ['facil', 'facil', 'medio'], 7: ['facil', 'medio', 'medio'], 8: ['facil', 'medio', 'dificil'], 9: ['facil', 'medio', 'dificil'] },
    5: { 6: ['facil', 'facil', 'medio'], 7: ['facil', 'medio', 'medio'], 8: ['facil', 'medio', 'dificil'], 9: ['facil', 'medio', 'dificil'] },
    6: { 6: ['facil', 'facil', 'medio'], 7: ['facil', 'facil', 'medio'], 8: ['facil', 'medio', 'dificil'], 9: ['facil', 'medio', 'dificil'] },
    7: { 6: ['facil', 'facil', 'medio'], 7: ['facil', 'medio', 'medio'], 8: ['facil', 'medio', 'dificil'], 9: ['facil', 'medio', 'dificil'] },
    8: { 6: ['facil', 'facil', 'medio'], 7: ['facil', 'medio', 'medio'], 8: ['facil', 'medio', 'dificil'], 9: ['facil', 'medio', 'dificil'] }
};

function dificuldadeDoAno(dificuldade, jogoId) {
    const indiceDificuldade = ['facil', 'medio', 'dificil'].indexOf(dificuldade);
    const dificuldadesDoJogo = dificuldadesPorJogoESerie[jogoId];
    const dificuldadesDaSerie = dificuldadesDoJogo ? dificuldadesDoJogo[ANO_ESCOLAR] : null;
    if (indiceDificuldade < 0 || !dificuldadesDaSerie) {
        throw new Error('Não foi possível identificar a dificuldade adequada à série.');
    }
    return dificuldadesDaSerie[indiceDificuldade];
}
