<?php require 'partials/header.php'; ?>

<h1> Manage page. </h1>

<h2>Add bottle:</h2>
<form method="POST" action="">
    <label>Type:</label>
    <input type="text" name="type">
    <label>image:</label>
    <input type="text" name="image">
    <label>Value:</label>
    <input type="text" name="value">
    <label>Country:</label>
    <input type="text" name="country">
    <input type="submit" value="Add">
</form>

<h2>My bottles:</h2>

<?php if (!empty($userBottles)) : ?>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Type</th>
                <th>Image</th>
                <th>Value</th>
                <th>Country</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($userBottles as $bottle): ?>
            <tr>
                <td><?= $bottle->id ?></td>
                <td><?= $bottle->type ?></td>
                <td><?= $bottle->image ?></td>
                <td><?= $bottle->value?></td>
                <td><?= $bottle->country ?></td>
                <td>
                    <div class="options-update-delete">
                        <button class="option-update">Update</button>
                        <button class="option-delete">Delete</button>
                    </div>
                </td>
            </tr>
           
            <?php endforeach ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No data here...</p>
<?php endif; ?>

<?php require 'partials/footer.php'; ?>