<?php require 'partials/header.php'; ?>

<h1> Add bottle page. </h1>
<form method="POST" action="">
    <label>Type:</label>
    <input type="text" name="type">
    <label>image:</label>
    <input type="text" name="image">
    <label>Value:</label>
    <input type="text" name="value">
    <label>Country:</label>
    <input type="text" name="country">
    <input type="submit" value="Submit">
</form>
<?php require 'partials/footer.php'; ?>