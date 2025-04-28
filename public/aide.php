<?php
session_start();

// Vérifiez si l'utilisateur est connecté, sinon redirigez vers la page de connexion
if (!isset($_SESSION['login'])) {
    header('Location: identification.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Stage BTS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

<div class="sidebar">
        <a href="accueil.php"  class="<?php echo (basename($_SERVER['PHP_SELF']) == 'accueil.php') ? '' : ''; ?>">
        <img src="icons/home.png" alt="Accueil" class="icon"> Accueil
        </a>
        <a href="entreprise.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'entreprise.php') ? '' : ''; ?>">
        <img src="icons/entreprise.png" alt="Entreprise" class="icon"> Entreprise
        </a>
        
        <a href="liste_etudiants.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'liste_etudiants.php') ? '' : ''; ?>">
        <img src="icons/stage.png" alt="Stagiaire" class="icon">Stagiaire
        </a>
        <a href="inscription.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'inscription.php') ? '' : ''; ?>">
        <img src="icons/inscrire.png" alt="Inscription" class="icon"> Inscription
        </a>
        <a href="aide.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'aide.php') ? 'active' : ''; ?>">
        <img src="icons/aide.png" alt="Aide" class="icon"> Aide
        </a>
        <a href="identification.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'identification.php') ? '' : ''; ?>">
        <img src="icons/deconnexion.png" alt="Deconnexion" class="icon"> Deconnexion
        </a>
        <br></br>
        <br></br>
        <br></br>
        
        <div class="mt-auto">
            <a href="#" id="expandSidebar">
                <img src="icons/droite.png" alt="Développer" class="icon"> Développer
            </a>
            <a href="#" id="collapseSidebar">
                <img src="icons/gauche.png" alt="Réduire" class="icon"> Réduire
            </a>
        </div>
    </div>
        <script>
            const sidebar = document.querySelector('.sidebar');
            const expandButton = document.getElementById('expandSidebar');
            const collapseButton = document.getElementById('collapseSidebar');
            const links = document.querySelectorAll('.sidebar a');

            // Fonction pour développer la barre
            expandButton.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le rechargement de la page
                sidebar.style.width = '200px'; // Largeur normale
                links.forEach(link => {
                    link.style.display = 'flex'; // Affiche tous les liens
                });
            });

            // Fonction pour réduire la barre
            collapseButton.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le rechargement de la page
                sidebar.style.width = '50px'; // Réduit la barre
                links.forEach(link => {
                    if (!link.querySelector('img[alt="Développer"]') && !link.querySelector('img[alt="Réduire"]')) {
                        link.style.display = 'none'; // Cache les autres liens
                    }
                });
            });
        </script>


    <article>

<div class="container">
    
                <h1>Aide</h1>
                <p>Bienvenue sur la FAQ</p>
            
            <!-- Ajoutez un élément pour nettoyer les flottants après .droite -->

            <hr>

            <div>
        <h2> <span style="color: darkblue;">Entreprise </span> </h2>

    <h3><span style="color: darkblue;"> Comment rechercher une entreprise ?</span> </h3>
<p > Si vous voulez rechercher une entreprise, vous devez aller sur la page " Entreprise ", pour cliquer sur le bouton " Rechercher une entreprise ". Il vous est alors fourni trois critères. Utilisez-les afin de pouvoir trouver les entreprises qui correspondent à vos choix.</p>

<h3><span style="color: darkblue;">Comment ajouter une entreprise ?</span></h3>
<p >Pour ajouter une entreprise, rendez-vous sur la page " Entreprise ", où vous devez cliquer sur le bouton " Ajouter une entreprise ". Vous devrez ensuite ajouter les informations concernant l’entreprise. Toutes les informations ne sont pas obligatoires, mais il est conseillé d’en fournir un maximum pour renseigner les futurs stagiaires sur les entreprises référencées.</p>

<h3><span style="color: darkblue;">Comment afficher ou enlever une information concernant l'entreprise ?</span></h3>
<p>En allant sur la page " Entreprise ", vous pouvez voir les entreprises déjà référencées. Vous pouvez alors remarquer que certaines informations concernant l'entreprise sont absentes. Vous pouvez cependant les afficher grâce à la liste déroulante : choisissez l'information que vous voulez afficher puis cliquez sur le bouton " Ajouter ". Si vous voulez enlever une information, il vous suffit de cliquer sur le moins situé à l'entête de la colonne représentant l'information concerné.</p>

<h3><span style="color: darkblue;">N'y a-t-il pas une autre solution pour voir ces informations ? </span></h3>
<p>Bien sûr, vous pouvez cliquer sur l’icône <img src="icons/voir.png" alt="Voir"width="10" height="10" class='button-container'> pour voir toutes les informations concernant l'entreprise que vous avez sélectionné.</p>

<h3><span style="color: darkblue;">Comment puis-je supprimer une entreprise ?</span></h3>
<p>Rien de plus simple, il vous suffit de cliquer sur l'icône <img src='icons/supprimer.png' alt='Supprimer' width="10" height="10" class='button-container'> qui se situe sur la deuxième colonne " Opération ".</p>
<b><span style="color: darkblue;">Faites bien attention de ne pas vous tromper de ligne !</span></b>

<h3><span style="color: darkblue;">Et si je veux modifier une information fausse ?</span></h3>
<p>Cliquez sur l’icône <img src="icons/modifier.png" alt="Voir" width="10" height="10" class='button-container'>, puis changer le(s) information(s) que vous voulez. Vous pourrez par la même occasion renseigner une information manquante si vous en avez la possibilité.</p>
    </div>

    <h2> <span style="color: darkblue;">Stagiaire </span></h2>
    <h3><span style="color: darkblue;">Comment rechercher un stagiaire ?</span> </h3>
    <p class="section">Tout d'abord, dirigez-vous sur la page " Stagiaire ". Cliquez ensuite sur le bouton  " Rechercher un stagiaire existant ". Vous aurez alors quatre listes déroulantes. Vous pourrez alors choisir, pour chaque champ, l'information voulue.</p>
<h3><span style="color: darkblue;">Comment inscrire un étudiant à un stage ?</span></h3>
<p class="section">Pour cela, vous devez vous rendre sur la page " Inscription ". Ensuite, vous devrez remplir un formulaire contenant diverses informations concernant le stage de l’étudiant, comme par exemple l’entreprise ou encore le professeur qui s’occupera du stage de l’étudiant. Vous pouvez aussi le faire à partir de la page " Entreprise " : cliquez sur la poignée de main située sur la première colonne " Opération ", et le formulaire d'inscription s'affichera avec le nom de l'entreprise pré-rentré. </p>
<h3><span style="color: darkblue;">Comment peut-on voir les informations des stagiaires ?</span></h3>
<p class="section">Sur la liste qui s'affiche sur la page " Stagiaire ", ou en cliquant sur l’icône <img src="icons/voir.png" alt="Voir" width="10" height="10" class='button-container'>.</p>
<h3><span style="color: darkblue;">Comment peut-on supprimer un stagiaire ?</span></h3>
<p class="section">Comme pour une entreprise : cliquez sur l'icône <img src="icons/supprimer.png" alt="Voir" width="10" height="10" class='button-container'> présente sur la page " Stagiaire ".</p>
<h3><span style="color: darkblue;">Et pour modifier le contenu d'un champ, pareil que pour les entreprises ?</span></h3>
<p class="section">Tout juste ! </p>

</div>
</div>

</body>
</html>
