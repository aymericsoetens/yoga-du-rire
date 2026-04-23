/**
 * Fonctions utilitaires
 */

// Afficher un message dans la console uniquement en développement
/*export function log(message, type = 'info') {
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        const prefix = type === 'error' ? '❌' : type === 'warn' ? '⚠️' : '📢';
        console.log(`${prefix} ${message}`);
    }
}*/

// Attendre qu'un élément existe dans le DOM
export function waitForElement(selector, timeout = 5000) {
    return new Promise((resolve, reject) => {
        const startTime = Date.now();
        
        const checkInterval = setInterval(() => {
            const element = document.querySelector(selector);
            if (element) {
                clearInterval(checkInterval);
                resolve(element);
            } else if (Date.now() - startTime > timeout) {
                clearInterval(checkInterval);
                reject(new Error(`Element "${selector}" not found after ${timeout}ms`));
            }
        }, 100);
    });
}

// Debounce pour éviter les appels trop fréquents
export function debounce(func, delay = 100) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}