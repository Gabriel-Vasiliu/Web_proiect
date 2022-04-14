<?php require 'partials/header.php'; ?>

<h1 style="text-align:center; margin-top:80px; margin-bottom:80px">Collecting Bottles</h1>

<p class="descriere"> 
    Oamenii care colecționează sticle sunt adesea atrași de frumusețea sticlelor, 
    de istoria din spatele lor și de faptul că sunt mici și nu ocupă atât de mult spațiu. Sticlele 
    de sticlă nu se degradează în timp, 
    așa că cele care au fost îngropate cu ani în urmă sunt încă acolo. Sticlele sunt, de asemenea, 
    de colecție, deoarece există multe tipuri, așa că un colecționar este sigur că 
    va găsi un anumit tip pe care dorește să se concentreze, cum ar fi sticlele medicinale, 
    sticlele de cola, sticlele de parfum sau sticlele de cerneală. Unele sticle sunt valoroase 
    pentru că sunt rare, dar altele au valoare mică sau deloc. Când începeți o colecție de sticle, 
    este important să aflați care sticle sunt considerate de colecție, unde să le găsiți, cum să 
    le identificați și cum să le determinați valoarea.
</p>

<div style="text-align:center">
<img class="home-image" src="./images/butelca.jpg" alt="sticla" width="300" height="300">
<img class="home-image" src="./images/butelca_1.jpg" alt="sticla" width="300" height="300">
<img class="home-image" src="./images/butelca_2.jpg" alt="sticla" width="300" height="300">
<img class="home-image" src="./images/sticla_cu_mesaj.jpg" alt="sticla" width="300" height="300">
</div>

<div style="text-align:center">
<img class="home-image" src="./images/Original.jpg" alt="sticla" width="300" height="300">
<img class="home-image" src="./images/pepsi-cola.jpg" alt="sticla" width="300" height="300">
<img class="home-image" src="./images/ship-in-bottle-2.jpg" alt="sticla" width="300" height="300">
<img class="home-image" src="./images/lightbulb.jpg" alt="sticla" width="300" height="300">
</div>

<?php if(App\Core\App::get('session')->getFlash('success')): ?>
    <div class="alert alert-success">
        <?php echo App\Core\App::get('session')->getFlash('success'); ?>
    </div> 
<?php endif; ?>
<?php require 'partials/footer.php'; ?>