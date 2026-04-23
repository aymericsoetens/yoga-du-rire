/**
 * Fichier principal - Initialise tous les modules
 */

import { initMobileMenu } from './modules/menu.js';
import { initContactForm, initFormValidation } from './modules/form.js';
import { loadInstagramEmbeds, observeInstagramEmbeds, refreshInstagramEmbeds } from './modules/instagram.js';
import { initScrollReveal, initVideoAnimation, initCardHoverEffects } from './modules/animations.js';
/*import { log } from './modules/utils.js';

/* Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', () => {
  /*  log('DOM chargé - Initialisation des modules');*/
    
    // Initialiser tous les modules
    initMobileMenu();
    initScrollReveal();
    initVideoAnimation();
    initCardHoverEffects();
    initContactForm();
    initFormValidation();
    
    // Instagram - charger après un petit délai
    setTimeout(() => {
        loadInstagramEmbeds();
        observeInstagramEmbeds();
    }, 500);


/* Recharger les embeds Instagram après chargement complet de la page
window.addEventListener('load', () => {
    log('Page complètement chargée');*/
    
    // Forcer le rafraîchissement des embeds Instagram
    setTimeout(() => {
        refreshInstagramEmbeds();
    }, 1000);
//});

/* Gérer les erreurs globales
window.addEventListener('error', (e) => {
    log(`Erreur globale: ${e.message}`, 'error');
});*/

// Gérer la désactivation du bouton de soumission en double-clic
document.addEventListener('submit', (e) => {
    const form = e.target;
    if (form.id === 'contact-form') {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && submitBtn.disabled) {
            e.preventDefault();
        }
    }
});