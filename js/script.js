// ============================================================
// MathPlay Solutions — Script Global (script.js)
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
    // Animação suave para cards
    const cards = document.querySelectorAll('.stat-card, .jogo-card, .card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(15px)';
        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 60 * index);
    });
});

