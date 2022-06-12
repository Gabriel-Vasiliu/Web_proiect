<?php require 'partials/header.php'; ?>

<h1> Manage page. </h1>

<h2>Add bottle:</h2>
<form>
    <label>Type</label>
    <input type="text" name="type" id="_type">
    <label>Image</label>
    <input type="text" name="image" id="_image">
    <label>Value</label>
    <input type="text" name="value" id="_value">
    <label>Country</label>
    <input type="text" name="country" id="_country">
    <input type="button" value="Add" id="add-button">
</form>

<h2>My bottles:</h2>
<div id="content">
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
        <input type="button" value="Export to CSV" onclick="tableToCSV()">
    <?php else: ?>
        <p>No data here...</p>
    <?php endif; ?>
</div>
<script>
    document.getElementById('add-button').addEventListener('click', function() {
        var type = document.getElementById('_type').value
        var value = document.getElementById('_value').value
        var image = document.getElementById('_image').value
        var country = document.getElementById('_country').value
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function(response) {
            //console.log(this)
            if(this.readyState === 4 && this.status === 200)
            {
                console.log(this.response);
                var data = JSON.parse(this.response)
                //document.getElementById("qp").innerHTML = this.response
                if(data.length == 0){
                    document.getElementById("content").innerHTML = 'No data here...';
                } else {
                    table = '<table>'
                    table = table + "<thead>"
                    let first = true;
                    table = table + "<tr>"
                    for(let key in data[0]){
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
                    for(let rowIndex in data){
                            table = table + "<tr>"
                            for(let key in data[rowIndex]){
                                // if(key == 'id'){
                                //     continue
                                // }
                                table = table + `<td> ${data[rowIndex][key]} </td>`
                            }
                            table = table + `<td>                         <div class="options-update-delete">
                            <button class="option-update">Update</button>
                            <button class="option-delete">Delete</button>
                        </div> </td>`
                            table = table + "</tr>"
                    }
                    table = table + "</tbody> </table>"
                    document.getElementById("content").innerHTML = table;
                    console.log(table)
                }
                
            }
            else{
                document.getElementById("myp").innerHTML = 'No data here...';
            }
        }
        xhttp.open("POST", `/bottles/manage?type=${type}&value=${value}&image=${image}&country=${country}`);
        xhttp.send();
    })
</script>

<script type="text/javascript">
    function tableToCSV(){
        var data = [];
        var rows = document.getElementsByTagName('tr');
        for(var index = 0; index < rows.length; index++){
            var cols = rows[index].querySelectorAll('td,th');
            var row = [];
            for(var i = 0; i < cols.length - 1; i++){
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
        link.download = "exportData.csv";
        var url = window.URL.createObjectURL(CSVFile);
        link.href = url;
        link.style.display = "none";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<?php require 'partials/footer.php'; ?>