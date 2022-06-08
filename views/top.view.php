<?php require 'partials/header.php'; ?>

<h1> Top page. </h1>
<h2>All Bottles:</h2>

<?php if (!empty($bottles)) : ?>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Type</th>
                <th>Image</th>
                <th>Value</th>
                <th>Country</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($bottles as $bottle): ?>
            <tr>
                <td>1</td>
                <td><?= $bottle->type ?></td>
                <td><?= $bottle->image ?></td>
                <td><?= $bottle->value?></td>
                <td><?= $bottle->country ?></td>
            </tr>
           
            <?php endforeach ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No data here...</p>
<?php endif; ?>

<?php require 'partials/footer.php'; ?>