<?php

use App\Core\App;

 require 'partials/header.php'; ?>

<h2> The most expensive bottle: </h2>

<?php if ($bestBottle->type != '') : ?>
<table id="table2">
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
            <td><img src="/public/<?= App::$user->username ?>/<?= $bestBottle->image ?>" alt="<?= $bestBottle->image ?>" style="width: 6rem; height: 6rem;"/></td>
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
    <table id="table1">
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
    <input type="button" value="Export to CSV" onclick="generateCSV()">
<?php else: ?>
    <p>No data here...</p>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>

<script>

function generateCSV(){
    var data = [];
    var rows = document.getElementsByTagName('tr');
    for(var index = 0; index < rows.length; index++){
        var cols = rows[index].querySelectorAll('td,th');
        var row = [];
        for(var i = 0; i < cols.length; i++){
            row.push(cols[i].innerHTML);
        }
        data.push(row.join(","));
    }
    data = data.join('\n');
    downloadCSV(data);
}

function downloadCSV(data){
    CSVFile = new Blob([data], {type: "text/csv"});
    var link = document.createElement('a');
    link.download = "statistici.csv";
    var url = window.URL.createObjectURL(CSVFile);
    link.href = url;
    link.style.display = "none";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

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
    doc.text(250, y = y + 30, "STATISTICS");
    doc.text(70, y = y + 50, "The most expensive bottle");
    doc.autoTable({
        html: '#table2',
        startY: 110,
        theme: 'grid',
        columnStyles: {
            0: {
                cellWidth: 100,
            },
            1: {
                cellWidth: 100,
            },
            2: {
                cellWidth: 50,
            },
            3: {
                cellWidth: 100,
            }
        },
        styles: {
            minCellHeight: 20
        }
    })

    doc.text(70, y = y + 90, "Number of bottles: " + <?= $nrBottles ?>);
    doc.autoTable({
        html: '#table1',
        startY: 200,
        theme: 'grid',
        columnStyles: {
            0: {
                cellWidth: 100,
            },
            1: {
                cellWidth: 50,
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