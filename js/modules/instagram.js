/**
 * Chargement et gestion des vidéos Instagram
 */

export function loadInstagramEmbeds() {
    // Vérifier si le script Instagram est déjà présent
    if (typeof window.instgrm !== 'undefined') {
        processInstagramEmbeds();
        return;
    }
    
    // Attendre que le script se charge
    const script = document.createElement('script');
    script.src = "//www.instagram.com/embed.js";
    script.async = true;
    script.defer = true;
    
    script.onload = () => {
        processInstagramEmbeds();
    };
    
    script.onerror = () => {
        console.warn('Instagram embed script could not be loaded');
        showInstagramFallback();
    };
    
    document.body.appendChild(script);
}

function processInstagramEmbeds() {
    if (window.instgrm && window.instgrm.Embeds) {
        window.instgrm.Embeds.process();
    }
}

function showInstagramFallback() {
    const containers = document.querySelectorAll('.instagram-cropped');
    containers.forEach(container => {
        const fallbackHtml = `
            <div class="instagram-fallback" style="padding: 40px; text-align: center; background: #f5f5f5; border-radius: 12px;">
                <i class="fab fa-instagram" style="font-size: 2rem; color: #E4405F;"></i>
                <p style="margin-top: 12px;">
                    <a href="${container.querySelector('a')?.href || 'https://www.instagram.com/astridyogadurire/'}" target="_blank" style="color: #f3813a;">
                        Voir la vidéo sur Instagram
                    </a>
                </p>
            </div>
        `;
        container.innerHTML = fallbackHtml;
    });
}

// Forcer le rafraîchissement des embeds après chargement dynamique
export function refreshInstagramEmbeds() {
    if (window.instgrm && window.instgrm.Embeds) {
        window.instgrm.Embeds.process();
    }
}

// Observer les changements dans le DOM pour recharger les embeds
export function observeInstagramEmbeds() {
    const observer = new MutationObserver((mutations) => {
        let shouldRefresh = false;
        
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1 && node.querySelector && 
                        (node.querySelector('.instagram-media') || node.classList?.contains('instagram-media'))) {
                        shouldRefresh = true;
                    }
                });
            }
        });
        
        if (shouldRefresh) {
            setTimeout(() => refreshInstagramEmbeds(), 500);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}