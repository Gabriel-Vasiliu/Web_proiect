<?php require 'partials/header.php'; ?>

<h1>Home page.</h1>

<?php if(App\Core\App::get('session')->getFlash('success')): ?>
    <div class="alert alert-success">
        <?php echo App\Core\App::get('session')->getFlash('success'); ?>
    </div> 
<?php endif; ?>
<?php require 'partials/footer.php'; ?>