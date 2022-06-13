<?php require 'partials/header.php'; ?>

<h1> Manage page </h1>

<h2>Add bottle:</h2>
<form>
    <label>Type</label>
    <input type="text" name="type" id="_type">
    <label>Image</label>
    <input type="text" name="image" id="_image">
    <label>Value</label>
    <input type="number" name="value" id="_value" min="0">
    <label>Country</label>
    <input type="text" name="country" id="_country">
    <input type="button" value="Add" id="add-button">
</form>

<h2>My bottles:</h2>
<div id="content">
    <?php if (!empty($userBottles)) : ?>
        <table id="table">
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
                <?php foreach ($userBottles as $index => $bottle) : ?>
                    <tr id="<?= $index; ?>">
                        <td>
                            <span><?= $bottle->id ?></span>
                            <input type="text" style="display: none;">
                        </td>
                        <td><?= $bottle->type ?></td>
                        <td><?= $bottle->image ?></td>
                        <td><?= $bottle->value ?></td>
                        <td><?= $bottle->country ?></td>
                        <td>
                            <div class="options-update-delete">
                                <button class="option-update update-button" data-id="<?= $index ?>">Update</button>
                                <button class="option-delete delete-button" data-id="<?= $index ?>">Delete</button>
                            </div>
                        </td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
        <input type="button" value="Export to CSV" onclick="tableToCSV()">
        <input type="button" onclick="generatePDF()" value="Export To PDF" />
    <?php else: ?>
        <p>No data here...</p>
    <?php endif; ?>
</div>

<dialog>
    <h3>Are you sure?</h3>

    <p id="dbRowId">id</p>
    <form id="dialog-form">
        <input type="hidden" name="id" class="dialog-input">
        <input type="text" name="type" class="dialog-input">
        <input type="text" name="value"  class="dialog-input">
        <input type="text" name="image"  class="dialog-input">
        <input type="text" name="country"  class="dialog-input">
    </form>
    <form method="dialog">
        <button value="yes">Yes</button>
        <button value="no">No</button>
    </form>

</dialog>

<script>
    var data = JSON.parse('<?= json_encode($userBottles) ?>')
    console.log(data)
    document.querySelector('dialog').addEventListener('close', (ev) => {
        let dialog = ev.target
                //alert('Closed. The user clicked the button with the value of ' + dialog.returnValue)

                closeValue = dialog.returnValue
                if(closeValue.localeCompare('yes')==0){
                    newInputId = dialog.querySelector(`#dialog-form .dialog-input[name=id]`).value
                    newInputType = dialog.querySelector(`#dialog-form .dialog-input[name=type]`).value
                    newInputImage = dialog.querySelector(`#dialog-form .dialog-input[name=image]`).value
                    newInputValue = dialog.querySelector(`#dialog-form .dialog-input[name=value]`).value
                    newInputCountry = dialog.querySelector(`#dialog-form .dialog-input[name=country]`).value
                    
                    //console.log("salut, id=" + data[id]['id'])
                    const xhttp = new XMLHttpRequest();
                    xhttp.onload = function(response) {
                    if (this.readyState === 4 && this.status === 200) {
                        data = JSON.parse(this.response)
                        //document.getElementById("qp").innerHTML = this.response
                        if (data.length == 0) {
                            document.getElementById("content").innerHTML = 'No data here...';
                        } else {
                            table = '<table>'
                            table = table + "<thead>"
                            let first = true;
                            table = table + "<tr>"
                            for (let key in data[0]) {
                                // if(key == 'id'){
                                //     continue
                                // }
                                key = key.charAt(0).toUpperCase() + key.slice();
                                table = table + `<th> ${key} </th>`
                            }
                            table = table + `<th> Actions </th>`
                            table = table + "</tr>"
                            table = table + "</thead>"
                            table = table + "<tbody>"
                            let idRow = -1
                            for (let rowIndex in data) {
                                table = table + "<tr >"
                                for (let key in data[rowIndex]) {
                                    if (key == 'id') {
                                        idRow = data[rowIndex][key];
                                    }
                                    table = table + `<td> ${data[rowIndex][key]} </td>`
                                }
                                table = table + `<td>                         <div class="options-update-delete">
                                    <button class="option-update update-button" data-id="${rowIndex}">Update</button>
                                    <button class="option-delete delete-button" data-id="${rowIndex}">Delete</button>
                                </div> </td>`
                                table = table + "</tr>"
                            }

                            table = table + "</tbody> </table>"
                            table = table + "<input type='button' value='Export to CSV' onclick='tableToCSV()'>"
                            document.getElementById("content").innerHTML = table;
                            document.querySelectorAll('.update-button').forEach(updateFunction)
                            document.querySelectorAll('.delete-button').forEach((el) => {
                                el.addEventListener('click', (ev) => {
                                    deletee(ev)
                                })
                            })
                        }
                    }
                    }
                    let formData = new FormData()
                    formData.append('id', newInputId)
                    formData.append('type', newInputType)
                    formData.append('value', newInputValue)
                    formData.append('image', newInputImage)
                    formData.append('country', newInputCountry)
                    xhttp.open("POST", `/bottles/manage/update`);
                    xhttp.send(formData);
                }
            });
    function updateFunction(el) {
        el.addEventListener('click', (ev) => {
            let buttonElement = ev.target;
            let id = buttonElement.getAttribute('data-id')
            let elData = data[id]

            let dialog =  document.querySelector('dialog')
            let lookupTable = Object.keys(elData).slice()
            let rowIdFromDb = dialog.querySelector(`#dbRowId`)
            rowIdFromDb.innerHTML = "ID=" + elData['id']
            console.log(rowIdFromDb.innerHTML)
            lookupTable.forEach((el) => {
                let input = dialog.querySelector(`#dialog-form .dialog-input[name=${el}]`)
                input.value = elData[el]
            })
            
            dialog.showModal()
            el.style.color = "red"

            //
        })
    }
    //const arr
    document.getElementById('add-button').addEventListener('click', function() {
        data = JSON.parse('<?= json_encode($userBottles) ?>')
        var type = document.getElementById('_type').value
        var value = document.getElementById('_value').value
        var image = document.getElementById('_image').value
        var country = document.getElementById('_country').value
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function(response) {
            if (this.readyState === 4 && this.status === 200) {
                data = JSON.parse(this.response)
                //document.getElementById("qp").innerHTML = this.response
                if (data.length == 0) {
                    document.getElementById("content").innerHTML = 'No data here...';
                } else {
                    table = '<table id="table">'
                    table = table + "<thead>"
                    let first = true;
                    table = table + "<tr>"
                    for (let key in data[0]) {
                        // if(key == 'id'){
                        //     continue
                        // }
                        key = key.charAt(0).toUpperCase() + key.slice(1);
                        table = table + `<th> ${key} </th>`
                    }
                    table = table + `<th> Actions </th>`
                    table = table + "</tr>"
                    table = table + "</thead>"
                    table = table + "<tbody>"
                    let idRow = -1
                    for (let rowIndex in data) {
                        table = table + "<tr >"
                        for (let key in data[rowIndex]) {
                            if (key == 'id') {
                                idRow = data[rowIndex][key];
                            }
                            table = table + `<td> ${data[rowIndex][key]} </td>`
                        }
                        table = table + `<td>                         <div class="options-update-delete">
                            <button class="option-update update-button" data-id="${rowIndex}">Update</button>
                            <button class="option-delete delete-button" data-id="${rowIndex}">Delete</button>
                        </div> </td>`
                        table = table + "</tr>"
                    }

                    table = table + "</tbody> </table>"
                    table = table + "<input type='button' value='Export to CSV' onclick='tableToCSV()'>"
                    table = table + "<input type='button' onclick='generatePDF()' value='Export To PDF' />"
                    document.getElementById("content").innerHTML = table;
                    document.querySelectorAll('.update-button').forEach(updateFunction)
                    document.querySelectorAll('.delete-button').forEach((el) => {
                        el.addEventListener('click', (ev) => {
                            deletee(ev)
                        })
                    })
                }
            } else {
                document.getElementById("myp").innerHTML = 'No data here...';
            }
        }
        let formData = new FormData()
        formData.append('type', type)
        formData.append('value', value)
        formData.append('image', image)
        formData.append('country', country)
        xhttp.open("POST", `/bottles/manage/add`);
        xhttp.send(formData);
    })
    document.querySelectorAll('.update-button').forEach(updateFunction)

    //------------------------------------------------------------
    //Delete
    //------------------------------------------------------------
    let dbRowId = 0
    document.querySelectorAll('.delete-button').forEach((el) => {
        //data = JSON.parse
        el.addEventListener('click', (ev) => {
            deletee(ev)
        })
    })

    function deletee(ev) {
        let dbRowId = -1
        //data = JSON.parse
        let buttonElement = ev.target;
        let id = buttonElement.getAttribute('data-id')
        dbRowId = data[id]['id']
        const xhttp = new XMLHttpRequest();
        let formData = new FormData()
        formData.append('id', dbRowId)
        xhttp.open("POST", `/bottles/manage/delete`);
        xhttp.send(formData);
        xhttp.onload = function(response) {
            data = JSON.parse(this.response)
            if (this.readyState === 4 && this.status === 200) {
                //document.getElementById("qp").innerHTML = this.response
                if (data.length == 0) {
                    document.getElementById("content").innerHTML = 'No data here...';
                } else {
                    data = JSON.parse(this.response)
                    table = '<table id="table">'
                    table = table + "<thead>"
                    let first = true;
                    table = table + "<tr>"
                    for (let key in data[0]) {
                        // if(key == 'id'){
                        //     continue
                        // }
                        key = key.charAt(0).toUpperCase() + key.slice(1);
                        table = table + `<th> ${key} </th>`
                    }
                    table = table + `<th> Actions </th>`
                    table = table + "</tr>"
                    table = table + "</thead>"
                    table = table + "<tbody>"
                    let idRow = -1
                    data = JSON.parse(this.response)
                    for (let rowIndex in data) {
                        table = table + "<tr >"
                        for (let key in data[rowIndex]) {
                            if (key == 'id') {
                                idRow = data[rowIndex][key];
                            }
                            table = table + `<td> ${data[rowIndex][key]} </td>`
                        }
                        table = table + `<td>                         <div class="options-update-delete">
                            <button class="option-update update-button" data-id="${rowIndex}">Update</button>
                            <button class="option-delete delete-button" data-id="${rowIndex}">Delete</button>
                        </div> </td>`
                        table = table + "</tr>"
                    }

                    table = table + "</tbody> </table>"
                    table = table + "<input type='button' value='Export to CSV' onclick='tableToCSV()'>"
                    table = table + "<input type='button' onclick='generatePDF()' value='Export To PDF' />"
                    document.getElementById("content").innerHTML = table;
                    document.querySelectorAll('.update-button').forEach(updateFunction)
                    document.querySelectorAll('.delete-button').forEach((el) => {
                        el.addEventListener('click', (ev1) => {
                            deletee(ev1)
                        })
                    })
                }
            } else {
                document.getElementById("myp").innerHTML = 'No data here...';
            }
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>

<script type="text/javascript">
    function tableToCSV() {
        var data = [];
        var rows = document.getElementsByTagName('tr');
        for(var index = 1; index < rows.length; index++){
            var cols = rows[index].querySelectorAll('td,th');
            var row = [];
            for(var i = 1; i < cols.length - 1; i++){
                row.push(cols[i].innerHTML);
            }
            data.push(row.join(","));
        }
        data = data.join('\n');
        downloadCSV(data);
    }

    function downloadCSV(data) {
        CSVFile = new Blob([data], {
            type: "text/csv"
        });
        var link = document.createElement('a');
        link.download = "exportData.csv";
        var url = window.URL.createObjectURL(CSVFile);
        link.href = url;
        link.style.display = "none";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function generatePDF() {
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
    var columns = [res.columns[0], res.columns[1], res.columns[2], res.columns[3], res.columns[4]];
    var y = 20;
    doc.setLineWidth(2);
    doc.text(250, y = y + 30, "Bottles");
    doc.autoTable(columns, res.data, {
        startY: 70,
        theme: 'grid',
        columnStyles: {
            0: {
                cellWidth: 30,
            },
            1: {
                cellWidth: 100,
            },
            2: {
                cellWidth: 100,
            },
            3: {
                cellWidth: 50,
            },
            4: {
                cellWidth: 100,
            }
        },
        styles: {
            minCellHeight: 20
        }
    })
    doc.save('bottles.pdf');
}

</script>

<?php require 'partials/footer.php'; ?>