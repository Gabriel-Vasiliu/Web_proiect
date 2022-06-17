<?php

use App\Core\App;

 require 'partials/header.php'; ?>

<h1> Search page. </h1>
<h2>All Bottles:</h2>
<form>
    <label>Type</label>
    <input type="text" name="type" id="_type">
    <label>Value</label>
    <input type="text" name="value" id="_value">
    <label>Country</label>
    <input type="text" name="country" id="_country">
    <input type="button" value="Search" id="send-button">
</form>
<p id="qp"> QueryParams </p>
<div id="content">
<?php if (!empty($bottles)) : ?>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Image</th>
                <th>Value</th>
                <th>Country</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php foreach($bottles as $bottle): ?>
            <tr>
                <td><?= $bottle->type ?></td>
                <td><img src="/public/<?= App::$user->username ?>/<?= $bottle->image ?>" alt="<?= $bottle->image ?>" style="width: 6rem; height: 6rem;"/></td>
                <td><?= $bottle->value?></td>
                <td><?= $bottle->country ?></td>
            </tr>
           
            <?php endforeach ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No data here...</p>
    <?php endif; ?>
</div>
<script>
    document.getElementById('send-button').addEventListener('click', function() {
        var type = document.getElementById('_type').value
        var value = document.getElementById('_value').value
        var country = document.getElementById('_country').value
        //console.log(ttype)
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function(response) {
            console.log(this)
            if(this.readyState === 4 && this.status === 200)
            {
                var data = JSON.parse(this.response)
                document.getElementById("qp").innerHTML = this.response
                if(data.length == 0){
                    document.getElementById("content").innerHTML = 'No data here...';
                } else {
                    table = '<table>'
                    table = table + "<thead>"
                    let first = true;
                    table = table + "<tr>"
                    for(let key in data[0]){
                        if(key == 'id'){
                            continue
                        }
                        key = key.charAt(0).toUpperCase() + key.slice(1);
                        table = table + `<th> ${key} </th>`
                    }
                    table = table + "</tr>"
                    table = table + "</thead>"
                    table = table + "<tbody>"
                    for(let rowIndex in data){
                            table = table + "<tr>"
                            for(let key in data[rowIndex]){
                                if(key == 'id'){
                                    continue
                                } else if(key == 'image'){
                                    table = table + `<td><img src="/public/<?= App::$user->username ?>/${data[rowIndex][key]}" alt="${data[rowIndex][key]}" style="width: 6rem; height: 6rem;"/></td>`
                                }
                                table = table + `<td> ${data[rowIndex][key]} </td>`
                            }
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
        xhttp.open("GET", `/bottles/search?type=${type}&value=${value}&country=${country}`);
        xhttp.send();
    })
</script>

<?php require 'partials/footer.php'; ?>