<?php
// Configuration
$config = [
    'to_email' => 'astrid@yogadurire.fr', // Remplacez par votre email
    'site_name' => 'Yoga du Rire - Astrid Soetens',
    'honeypot_field' => 'website' // Champ anti-spam caché
];

$response = ['success' => false, 'message' => '', 'errors' => []];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Vérification anti-spam (honeypot)
    if (!empty($_POST[$config['honeypot_field']])) {
        $response['message'] = 'Formulaire invalide';
        echo json_encode($response);
        exit;
    }
    
    // Récupération et nettoyage des données
    $name = trim(strip_tags($_POST['name'] ?? ''));
    $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $phone = trim(strip_tags($_POST['phone'] ?? ''));
    $subject_option = trim(strip_tags($_POST['subject'] ?? ''));
    $message = trim(strip_tags($_POST['message'] ?? ''));
    
    // Validation
    if (empty($name)) {
        $response['errors']['name'] = 'Veuillez indiquer votre nom et prénom';
    } elseif (strlen($name) < 2) {
        $response['errors']['name'] = 'Nom trop court';
    }
    
    if (empty($email)) {
        $response['errors']['email'] = 'Veuillez indiquer votre email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'Email invalide';
    }
    
    if (empty($subject_option)) {
        $response['errors']['subject'] = 'Veuillez sélectionner un sujet';
    }
    
    if (empty($message)) {
        $response['errors']['message'] = 'Veuillez écrire votre message';
    } elseif (strlen($message) < 10) {
        $response['errors']['message'] = 'Message trop court (minimum 10 caractères)';
    }
    
    // Mapping des sujets
    $subjects = [
        'seance' => 'Réserver une séance hebdomadaire',
        'atelier' => 'Demander un atelier ponctuel',
        'entreprise' => 'Animation entreprise / association',
        'info' => 'Demande d\'information'
    ];
    $subject_label = $subjects[$subject_option] ?? 'Demande de contact';
    
    // Si pas d'erreurs, envoi de l'email
    if (empty($response['errors'])) {
        
        // Construction du message email
        $email_subject = "[Yoga du Rire] $subject_label - $name";
        
        $email_message = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Message de $name</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f3813a; color: white; padding: 15px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 10px 10px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #f3813a; }
                .footer { text-align: center; padding-top: 20px; font-size: 12px; color: #999; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Nouveau message du site</h2>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='label'>Nom :</span> $name
                    </div>
                    <div class='field'>
                        <span class='label'>Email :</span> $email
                    </div>
                    <div class='field'>
                        <span class='label'>Téléphone :</span> " . (!empty($phone) ? $phone : 'Non renseigné') . "
                    </div>
                    <div class='field'>
                        <span class='label'>Sujet :</span> $subject_label
                    </div>
                    <div class='field'>
                        <span class='label'>Message :</span><br>
                        " . nl2br($message) . "
                    </div>
                </div>
                <div class='footer'>
                    Message envoyé depuis le site Yoga du Rire
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Headers pour l'email
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $config['site_name'] . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>',
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion()
        ];
        
        // Envoi
        $mail_sent = mail($config['to_email'], $email_subject, $email_message, implode("\r\n", $headers));
        
        // Email de confirmation à l'utilisateur
        if ($mail_sent) {
            $confirm_subject = "Confirmation de votre message - Yoga du Rire";
            $confirm_message = "
            <!DOCTYPE html>
            <html>
            <head><meta charset='UTF-8'></head>
            <body style='font-family: Arial, sans-serif;'>
                <div style='max-width: 500px; margin: 0 auto; padding: 20px;'>
                    <h2 style='color: #f3813a;'>Bonjour $name,</h2>
                    <p>Je vous remercie pour votre message. Je le traiterai dans les meilleurs délais et vous répondrai personnellement.</p>
                    <p><strong>Rappel de votre demande :</strong></p>
                    <p><em>" . nl2br($message) . "</em></p>
                    <hr>
                    <p style='font-size: 12px; color: #999;'>Bien chaleureusement,<br>Astrid Soetens</p>
                </div>
            </body>
            </html>
            ";
            
            mail($email, $confirm_subject, $confirm_message, implode("\r\n", $headers));
            
            $response['success'] = true;
            $response['message'] = 'Votre message a bien été envoyé ! Je vous répondrai dans les meilleurs délais.';
        } else {
            $response['message'] = 'Une erreur technique est survenue. Veuillez réessayer ou m\'appeler directement.';
        }
    } else {
        $response['message'] = 'Veuillez corriger les erreurs ci-dessous.';
    }
    
    // Si requête AJAX, retourner JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contactez Astrid pour réserver une séance de yoga du rire, poser une question ou organiser un atelier en Haute-Savoie.">
    <meta name="keywords" content="contact yoga du rire, réservation, Haute-Savoie, Astrid Soetens">
    <title>Contact et réservation - Yoga du Rire avec Astrid Soetens</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster+Two:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
      
    </style>
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="logo"><a href="index.html">
                    <img src="img/logo.png" alt="Logo Yoga du Rire Astrid Soetens" class="logo-yoga">
                </a></div>
            <nav class="navbar">
                <ul class="nav-menu">
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="yoga-du-rire.html">Le Yoga du Rire</a></li>
                    <li><a href="seances.html">Séances & Ateliers</a></li>
                    <li><a href="entreprises.html">Entreprises</a></li>
                    <li><a href="a-propos.html">A propos de moi</a></li>
                    <li><a href="contact.php" class="active btn-nav">Contact</a></li>
                </ul>
                <div class="hamburger"><span></span><span></span><span></span></div>
            </nav>
        </div>
    </header>

    <section class="page-hero">
        <div class="container">
            <h1>Contact & Réservation</h1>
            <p>Une question ? Une envie de rire ? Je vous réponds dans les meilleurs délais.</p>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-form-container">
                    <h2><i class="fas fa-envelope-open-text"></i> Envoyez-moi un message</h2>
                    
                    <!-- Zone d'affichage des messages -->
                    <div id="form-status"></div>
                    
                    <form id="contact-form" class="contact-form" method="POST" action="">
                        <!-- Champ honeypot anti-spam (caché) -->
                        <div class="honeypot">
                            <label for="website">Laissez ce champ vide</label>
                            <input type="text" id="website" name="website">
                        </div>
                        
                        <div class="form-group" id="group-name">
                            <label for="name">Nom et prénom *</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            <span class="error-message" id="error-name"></span>
                        </div>
                        
                        <div class="form-group" id="group-email">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            <span class="error-message" id="error-email"></span>
                        </div>
                        
                        <div class="form-group" id="group-phone">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                            <span class="error-message" id="error-phone"></span>
                        </div>
                        
                        <div class="form-group" id="group-subject">
                            <label for="subject">Sujet *</label>
                            <select id="subject" name="subject" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="seance" <?php echo (isset($subject_option) && $subject_option == 'seance') ? 'selected' : ''; ?>>Réserver une séance hebdomadaire</option>
                                <option value="atelier" <?php echo (isset($subject_option) && $subject_option == 'atelier') ? 'selected' : ''; ?>>Demander un atelier ponctuel</option>
                                <option value="entreprise" <?php echo (isset($subject_option) && $subject_option == 'entreprise') ? 'selected' : ''; ?>>Animation entreprise / association</option>
                                <option value="info" <?php echo (isset($subject_option) && $subject_option == 'info') ? 'selected' : ''; ?>>Demande d'information</option>
                            </select>
                            <span class="error-message" id="error-subject"></span>
                        </div>
                        
                        <div class="form-group" id="group-message">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            <span class="error-message" id="error-message"></span>
                        </div>
                        
                        <button type="submit" id="submit-btn" class="btn btn-secondary btn-large">
                            <i class="fas fa-paper-plane"></i> Envoyer ma demande
                        </button>
                        <p class="form-note">* Champs obligatoires. Je m'engage à répondre sous 48h.</p>
                    </form>
                </div>
                
                <div class="contact-info-container">
                    <div class="contact-card">
                        <h3><i class="fas fa-phone-alt"></i> Téléphone</h3>
                        <p><a href="tel:+33630145756">06 30 14 57 56</a></p>
                    </div>
                    <div class="contact-card">
                        <h3><i class="fas fa-map-marker-alt"></i> Lieux des séances</h3>
                        <p>La Roche sur Foron<br>Et ponctuellement dans d'autres communes de Haute-Savoie.</p>
                    </div>
                    <div class="contact-card">
                        <h3><i class="fas fa-clock"></i> Horaires indicatifs</h3>
                        <p>Tous les lundis matin à 10h30<br>et 2 mercredis par mois à 19h</p>
                    </div>
                    <div class="contact-card social-card">
                        <h3><i class="fas fa-share-alt"></i> Suivez-moi</h3>
                        <div class="social-links">
                            <a href="https://www.facebook.com/YogaRireAstridSoetens"><i class="fab fa-facebook-f"></i> Facebook</a>
                            <a href="https://www.instagram.com/astridyogadurire/"><i class="fab fa-instagram"></i> Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-logo"> <img src="img/logo.png" alt="Logo Yoga du Rire Astrid Soetens" class="logo-yoga"></div>
                    <h4>Suivez-moi</h4>
                    <div class="social-link"><a href="https://www.facebook.com/YogaRireAstridSoetens">Facebook</a></div> 
                    <div class="social-link"><a href="https://www.instagram.com/astridyogadurire/">Instagram</a></div>
                </div>
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="index.html">Accueil</a></li>
                        <li><a href="yoga-du-rire.html">Le Yoga du Rire</a></li>
                        <li><a href="seances.html">Séances & Ateliers</a></li>
                        <li><a href="entreprises.html">Entreprises</a></li>
                        <li><a href="a-propos.html">A propos de moi</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul class="contact-info">
                        <li><i class="fas fa-phone-alt"></i> <a href="tel:+33630145756">06 30 14 57 56</a></li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:astrid@yogadurire.fr">astrid@yogadurire.fr</a></li>
                        <li><i class="fas fa-map-marker-alt"></i> La Roche sur Foron & Haute-Savoie</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Infos pratiques</h4>
                    <p>Séances ouvertes à tous<br>Tenue confortable recommandée<br>Aucun prérequis nécessaire</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Yoga du Rire – Astrid Soetens. Tous droits réservés.</p>
                <p class="credit-photo">Crédit photo : Fabrice Loizeau</p>
            </div>
        </div>
    </footer>

    <script>
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        if (hamburger && navMenu) {
            hamburger.addEventListener('click', () => { hamburger.classList.toggle('active'); navMenu.classList.toggle('active'); });
            document.querySelectorAll('.nav-menu a').forEach(n => n.addEventListener('click', () => { hamburger.classList.remove('active'); navMenu.classList.remove('active'); }));
        }
        
        // Gestion AJAX du formulaire
        const form = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit-btn');
        const statusDiv = document.getElementById('form-status');
        
        function clearErrors() {
            document.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        }
        
        function showErrors(errors) {
            for (const [field, message] of Object.entries(errors)) {
                const input = document.getElementById(field);
                if (input) input.classList.add('field-error');
                const errorSpan = document.getElementById(`error-${field}`);
                if (errorSpan) errorSpan.textContent = message;
            }
        }
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();
            
            // Désactiver le bouton pendant l'envoi
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');
            form.classList.add('loading');
            
            const formData = new FormData(form);
            
            try {
                const response = await fetch('contact.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    statusDiv.innerHTML = `<div class="form-success"><i class="fas fa-check-circle"></i> ${data.message}</div>`;
                    form.reset();
                    setTimeout(() => {
                        statusDiv.innerHTML = '';
                    }, 5000);
                } else {
                    if (data.errors) {
                        showErrors(data.errors);
                    }
                    statusDiv.innerHTML = `<div class="form-error"><i class="fas fa-exclamation-triangle"></i> ${data.message}</div>`;
                }
            } catch (error) {
                statusDiv.innerHTML = `<div class="form-error"><i class="fas fa-exclamation-triangle"></i> Une erreur réseau est survenue. Veuillez réessayer.</div>`;
            } finally {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-loading');
                form.classList.remove('loading');
            }
        });
    </script>
</body>
</html>