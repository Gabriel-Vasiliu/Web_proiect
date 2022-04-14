<?php require 'partials/header.php'; ?>

<p class="descriere"> 
    Colectezi recipiente? Ai nevoie de un site in care sa iti gestionezi colectia ta favorita? Noi avem solutia!<br>
    <b>Collecting Bottles</b> este site-ul perfect pentru tine! <br> Acum poti sa iti editezi colectia 
    dupa bunul plac si sa o imparti cu ceilalti!
</p>

<img class="home-gif" src="./images/sticla1.gif" alt="sticla">

<?php if(App\Core\App::get('session')->getFlash('success')): ?>
    <div class="alert alert-success">
        <?php echo App\Core\App::get('session')->getFlash('success'); ?>
    </div> 
<?php endif; ?>
<?php require 'partials/footer.php'; ?>