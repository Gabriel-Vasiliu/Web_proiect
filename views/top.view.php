<?php

use App\Core\App;
use App\Models\Bottle;

 require 'partials/header.php'; ?>

<h1>Top</h1>

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
                <td><img src="/public/<?= $bottle->user()->username ?>/<?= $bottle->image ?>" alt="<?= $bottle->image ?>" style="width: 6rem; height: 6rem;"/></td>
                <td><?= $bottle->value?></td>
                <td><?= $bottle->country ?></td>
                <td><?= $bottle->username ?></td>
            </tr>
           
            <?php endforeach ?>
        </tbody>
    </table>

    <?php file_put_contents("rss.xml", $rss); ?>

    <input type="button" onclick="generate()" value="Export To PDF" />
    <input type="button" value="Export to CSV" onclick="tableToCSV()">
    <a href="../rss.xml"><img src="../images/rss.png" alt="RSS" class="rss"></a>
<?php else: ?>
    <p>No data here...</p>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>

<script>

function tableToCSV(){
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
    link.download = "top.csv";
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
    var res = doc.autoTableHtmlToJson(document.getElementById("table"));
    var columns = [res.columns[0], res.columns[2], res.columns[3], res.columns[4]];
    var y = 20;
    console.log("-------------");
    doc.setLineWidth(2);
    doc.text(250, y = y + 30, "TOP");
    for(let indexData = 0; indexData < res.data.length; indexData++){
        //console.log(res.data[indexData][1]);
        res.data[indexData].splice(1, 1)
    }
    console.log(res.data);
    doc.autoTable(columns, res.data, {
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