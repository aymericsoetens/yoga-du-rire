/**
 * Animations au scroll
 */

export function initScrollReveal() {
    // Animation des cartes bienfaits
    const benefitCards = document.querySelectorAll('.benefit-card');
    const sectionHeaders = document.querySelectorAll('.section-header');
    const enterpriseCards = document.querySelectorAll('.enterprise-card');
    const sessionCards = document.querySelectorAll('.session-card');
    
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -20px 0px'
    };
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                revealObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Appliquer les styles initiaux et observer
    const elementsToAnimate = [...benefitCards, ...enterpriseCards, ...sessionCards];
    
    elementsToAnimate.forEach((element, index) => {
        if (element) {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            // element.style.transition = `opacity 0.6s ease-out ${index * 0.1}s, transform 0.6s ease-out ${index * 0.1}s`;
            revealObserver.observe(element);
        }
    });
    
    sectionHeaders.forEach(header => {
        if (header) {
           // header.style.opacity = '0';
            header.style.transform = 'translateY(20px)';
           // header.style.transition = 'opacity 0.5s ease-out 0.1s, transform 0.5s ease-out 0.1s';
            revealObserver.observe(header);
        }
    });
}

// Animation d'apparition de la vidéo
export function initVideoAnimation() {
    const videoCard = document.querySelector('.video-card');
    
    if (videoCard) {
        // La classe CSS gère déjà l'animation
        // On s'assure juste qu'elle se déclenche
       // videoCard.style.animation = 'videoReveal 0.7s cubic-bezier(0.2, 0.9, 0.4, 1.1) forwards';
        videoCard.style.animationDelay = '0.2s';
        
        // Animation du contenu Instagram
        setTimeout(() => {
            const instaContent = document.querySelector('.instagram-cropped');
            if (instaContent) {
                //instaContent.style.animation = 'contentFadeIn 0.5s ease-out forwards';
            }
        }, 100);
    }
}

// Animation des cartes au survol (amélioration)
export function initCardHoverEffects() {
    const cards = document.querySelectorAll('.benefit-card, .enterprise-card, .session-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
           // card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
        });
    });
}