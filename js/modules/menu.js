/**
 * Menu Hamburger (responsive)
 */
export function initMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    if (!hamburger || !navMenu) return;

    // ARIA : indique que le bouton contrôle le menu
    hamburger.setAttribute('aria-expanded', 'false');
    hamburger.setAttribute('aria-label', 'Menu');
    hamburger.setAttribute('role', 'button');
    hamburger.setAttribute('tabindex', '0');

    const toggleMenu = () => {
        const isActive = hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
        // Met à jour l’état ARIA
        hamburger.setAttribute('aria-expanded', isActive);
    };

    hamburger.addEventListener('click', toggleMenu);

    // Permettre l’ouverture au clavier (Enter / Espace)
    hamburger.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleMenu();
        }
    });

    // Fermer le menu au clic sur un lien
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
        });
    });

    // Fermer le menu si on clique en dehors
    document.addEventListener('click', (event) => {
        const isClickInsideMenu = navMenu.contains(event.target);
        const isClickOnHamburger = hamburger.contains(event.target);
        if (!isClickInsideMenu && !isClickOnHamburger && navMenu.classList.contains('active')) {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            hamburger.setAttribute('aria-expanded', 'false');
        }
    });
}