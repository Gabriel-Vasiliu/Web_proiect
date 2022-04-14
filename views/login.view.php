<?php require 'partials/header.php'; ?>

<?php
    /** @var $model \App\Models\User */    
?>

<?php $form = \App\Core\Form\Form::begin('/login', 'post'); ?>
    <?php echo $form->field($model, 'username'); ?>
    <?php echo $form->field($model, 'password')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?php echo \App\Core\Form\Form::end(); ?>

<?php require 'partials/footer.php'; ?>