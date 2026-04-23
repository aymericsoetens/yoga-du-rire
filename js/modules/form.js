/**
 * Gestion des formulaires
 */

export function initContactForm() {
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const messageDiv = document.getElementById('form-message');
    
    if (!form) {
        return;
    }
    
    // Vérifier si on revient d'un envoi réussi
    checkFormSuccess();
    
    // Gérer la soumission du formulaire
    form.addEventListener('submit', function(e) {
        handleFormSubmit(e, submitBtn, messageDiv);
    });
}

function checkFormSuccess() {
    const messageDiv = document.getElementById('form-message');
    if (!messageDiv) return;
    
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('sent') === 'success') {
        messageDiv.className = 'form-message success';
        messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> ✅ Votre message a bien été envoyé ! Je vous répondrai dans les meilleurs délais.';
        messageDiv.style.display = 'block';
        window.history.replaceState({}, document.title, window.location.pathname);
        
        // Masquer après 5 secondes
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
}

function handleFormSubmit(e, submitBtn, messageDiv) {
    if (!submitBtn) return;
    
    // Désactiver le bouton pendant l'envoi
    submitBtn.disabled = true;
    submitBtn.classList.add('btn-loading');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
    
    // Réinitialiser le message
    if (messageDiv) {
        messageDiv.className = 'form-message';
        messageDiv.style.display = 'none';
    }
    
    // Le formulaire utilise action="https://formsubmit.co/..."
    // Il va se soumettre normalement, on ne fait que gérer l'UI
    // Si jamais il y a une erreur réseau, on peut réactiver
    setTimeout(() => {
        if (submitBtn.disabled) {
            // Si après 10 secondes le bouton est toujours désactivé,
            // c'est peut-être une erreur
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-loading');
            submitBtn.innerHTML = originalText;
            
            if (messageDiv) {
                messageDiv.className = 'form-message error';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Une erreur est survenue. Veuillez réessayer ou me contacter directement par email.';
                messageDiv.style.display = 'block';
            }
        }
    }, 10000);
}

// Validation en temps réel (optionnelle)
export function initFormValidation() {
    const form = document.getElementById('contact-form');
    if (!form) return;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', () => {
            validateInput(input);
        });
    });
}

function validateInput(input) {
    const isValid = input.checkValidity();
    
    if (!isValid) {
        input.style.borderColor = '#dc3545';
        input.style.backgroundColor = '#fff8f8';
        
        // Ajouter un message d'erreur s'il n'existe pas
        let errorMsg = input.parentElement.querySelector('.error-message');
        if (!errorMsg) {
            errorMsg = document.createElement('small');
            errorMsg.className = 'error-message';
            errorMsg.style.color = '#dc3545';
            errorMsg.style.fontSize = '0.75rem';
            errorMsg.style.marginTop = '4px';
            errorMsg.style.display = 'block';
            input.parentElement.appendChild(errorMsg);
        }
        
        if (input.type === 'email') {
            errorMsg.textContent = 'Veuillez entrer une adresse email valide.';
        } else {
            errorMsg.textContent = 'Ce champ est requis.';
        }
    } else {
        input.style.borderColor = '';
        input.style.backgroundColor = '';
        const errorMsg = input.parentElement.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.remove();
        }
    }
}