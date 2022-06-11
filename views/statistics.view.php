<?php require 'partials/header.php'; ?>

<h2> The most expensive bottle: </h2>

<?php if ($bestBottle->type != '') : ?>
<table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Image</th>
            <th>Value</th>
            <th>Country</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $bestBottle->type ?></td>
            <td><?= $bestBottle->image ?></td>
            <td><?= $bestBottle->value?></td>
            <td><?= $bestBottle->country ?></td>
        </tr>       
    </tbody>
</table>
<?php else: ?>
    <p>No data here...</p>
<?php endif; ?>

<h2> Number of bottles: <?= $nrBottles ?></h2>

<?php if (!empty($types)) : ?>
    <table id="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Number</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($types as $type=>$t): ?>
            <tr>
                <td><?= $type ?></td>
                <td><?= $t ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <input type="button" onclick="generate()" value="Export To PDF" />
<?php else: ?>
    <p>No data here...</p>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>

<script>
function generate() {
    var doc = new jsPDF('p', 'pt', 'letter');
    var htmlstring = '';
    var tempVarToCheckPageHeight = 0;
    var pageHeight = 0;
    pageHeight = doc.internal.pageSize.height;
    specialElementHandlers = {  
        '#bypassme': function (element, renderer) {  
            return true
        }
    };
    margins = {
        top: 150,
        bottom: 60,
        left: 40,
        right: 40,
        width: 600
    };
    var y = 20;
    doc.setLineWidth(2);
    doc.text(200, y = y + 30, "STATISTICS");
    doc.autoTable({
        html: '#table',
        startY: 70,
        theme: 'grid',
        columnStyles: {
            0: {
                cellWidth: 100,
            },
            1: {
                cellWidth: 100,
            }
        },
        styles: {
            minCellHeight: 20
        }
    })
    doc.save('statistics.pdf');
}
</script>

<?php require 'partials/footer.php'; ?>