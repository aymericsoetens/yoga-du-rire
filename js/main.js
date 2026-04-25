import {initContactForm, initFormValidation} from './modules/form.js';
import {loadInstagramEmbeds, observeInstagramEmbeds, refreshInstagramEmbeds} from './modules/instagram.js';
import {initScrollReveal, initVideoAnimation, initCardHoverEffects} from './modules/animations.js';
import {initMobileMenu} from './modules/menu.js';

// Initialisation du menu hamburger
initMobileMenu();

// Initialisation des animations
initScrollReveal();
initVideoAnimation();
initCardHoverEffects();

// Initialisation du formulaire de contact (si présent)
initContactForm();
initFormValidation();

// Chargement des vidéos Instagram
loadInstagramEmbeds();
observeInstagramEmbeds();

// Gestion de la soumission des formulaires (désactivation double envoi)
document.addEventListener('submit', (e) => {
    const form = e.target;
    if (form.id === 'contact-form') {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && submitBtn.disabled) {
            e.preventDefault();
        }
    }
});