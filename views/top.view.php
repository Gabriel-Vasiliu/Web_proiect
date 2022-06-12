<?php require 'partials/header.php'; ?>

<?php if (!empty($bottles)) : ?>
    <table id="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Image</th>
                <th>Value</th>
                <th>Country</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($bottles as $bottle): ?>
            <tr>
                <td><?= $bottle->type ?></td>
                <td><?= $bottle->image ?></td>
                <td><?= $bottle->value?></td>
                <td><?= $bottle->country ?></td>
                <td><?= $bottle->username ?></td>
            </tr>
           
            <?php endforeach ?>
        </tbody>
    </table>

    <?php file_put_contents("rss.xml", $rss); ?>

    <input type="button" onclick="generate()" value="Export To PDF" />
    <a href="../rss.xml">RSS</a>
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
    doc.text(200, y = y + 30, "TOP");
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
            },
            2: {
                cellWidth: 100,
            },
            3: {
                cellWidth: 100,
            },
            4: {
                cellWidth: 100,
            }
        },
        styles: {
            minCellHeight: 20
        }
    })
    doc.save('top.pdf');
}
</script>

<?php require 'partials/footer.php'; ?>