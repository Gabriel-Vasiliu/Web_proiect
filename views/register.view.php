<?php require 'partials/header.php'; ?>

<?php $form = \App\Core\Form\Form::begin('register', 'post'); ?>
    <?php echo $form->field($model, 'username'); ?>
    <?php echo $form->field($model, 'password')->passwordField(); ?>
    <?php echo $form->field($model, 'confirmPassword')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php echo \App\Core\Form\Form::end(); ?>

<?php require 'partials/footer.php'; ?>