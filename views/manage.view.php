<?php

use App\Core\App;

 require 'partials/header.php'; ?>

<h1> Manage page </h1>

<h2>Add bottle:</h2>
<form action="" enctype='multipart/form-data' method="POST" class="form1">
    <label>Type</label>
    <input type="text" name="type" id="_type">
    <label>Image</label>
    <input type="file" name="image" id="_image" accept="image/png, image/jpg, image/jpeg">
    <label>Value</label>
    <input type="number" name="value" id="_value" min="0">
    <label>Country</label>
    <input type="text" name="country" id="_country">
    <button type="button" value="Add" name="submit" id="add-button">Add </button>
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
                        <td><img src="/public/<?= App::$user->username ?>/<?= $bottle->image ?>" alt="<?= $bottle->image ?>" style="width: 6rem; height: 6rem;"/></td>
                        <td><?= $bottle->value ?></td>
                        <td><?= $bottle->country ?></td>
                        <td>
                            <div class="options-update-delete">
                                <button class="option-update update-button" data-id="<?= $index ?>">Update</button>
                                <button class="option-delete delete-button" data-id="<?= $index ?>">Delete</button>
                                <input class="check-box" type="checkbox" data-id="<?= $index ?>">
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <input type="button" value="Export to CSV" onclick="tableToCSV()">
        <input type="button" onclick="generatePDF()" value="Export To PDF" />
        <button id="send-button">Send bottles</button>
        <button id="show-new-request">New bottles request</button>
    <?php else: ?>
        <button id="show-new-request">New bottles request</button>
        <p>No data here...</p>
    <?php endif; ?>
</div>

<dialog class="update-dialog">
    <h3>Are you sure?</h3>

    <p id="dbRowId">id</p>
    <form id="dialog-form">
        <input type="hidden" name="id" class="dialog-input">
        <input type="text" name="type" class="dialog-input">
        <input type="text" name="value"  class="dialog-input">
        <input type="file" name="image"  class="dialog-input">
        <input type="text" name="country"  class="dialog-input">    
    </form>
    <form method="dialog">
        <button value="yes">Yes</button>
        <button value="no">No</button>
    </form>

</dialog>

<dialog class="send-bottles-dialog">
    <h3>Are you sure?</h3>
    <p>Enter the username:</p>
    <div id="send-bottles-form">
        <form id="send-bottles-form">
            <input type="text" name="username-to-send" class="send-dialog-input">
        </form>
    </div>
    <form method="dialog">
        <button value="yes">Yes</button>
        <button value="no">No</button>
    </form>
</dialog>

<dialog class="show-bottles-dialog">
    <div id="show-bottles-dialog-content">
        <h3>Users</h3>
        <p class="username-dialog" data-id="">Username</p>
        <button class="show-bottles-dialog" data-id="">Show Bottles</button>
        <form method="dialog">
            <button value="cancel">Cancel</button>
        </form>
    </div>
</dialog>
<script>
    var users = JSON.parse('<?= json_encode($users) ?>')
    var newBottlesRequests = JSON.parse('<?= json_encode($newBottlesRequests) ?>')
    var usersWithId = JSON.parse('<?= json_encode($usersWithId) ?>')
    var usersIdWithBottles = JSON.parse('<?= json_encode($usersIdWithBottles) ?>')
    ///////////////////////////////////
    console.log("usersIdWithBottles")
    console.log(usersIdWithBottles[5])

    /////////////////////////////

    ///////////////////////////
    console.log("users")
    console.log(users)
    console.log("newBottlesRequests")
    console.log(newBottlesRequests)
    console.log("resutt")
    var data = JSON.parse('<?= json_encode($userBottles) ?>')
    console.log("users with id")
    console.log(usersWithId)
    for (let user in usersWithId){
        let dialogContent = `<p>${user}</p>`
        console.log(dialogContent)
    }
    console.log("-----------------------------------")
    console.log(data)
    
    function showUsersRequestsDialog(){
        console.log("in show bottles dialog")
        let dialogContent = '<h3>Users</h3>'
        for(let index=0; index < usersWithId.length; index++){
            for (let user in usersWithId[index]){
                dialogContent += `<p>${user}</p>`
                dialogContent += `<button value="show-bottles" class="show-bottles-from-user" data-id="${usersWithId[index][user]}">show bottles</button>`
            }
        }
        dialogContent += '<form method="dialog">'
        dialogContent += '<button value="cancel">Cancel</button>'
        dialogContent += '<form>'
        document.getElementById('show-bottles-dialog-content').innerHTML = dialogContent
        let dialog =  document.querySelector('.show-bottles-dialog') //('dialog')
        dialog.showModal()
        document.querySelectorAll('.show-bottles-from-user').forEach((el) => {
        el.addEventListener('click', (ev) => {
            showUserBottlesDialog(ev)
        })
    })
    }

    function showUserBottlesDialog(ev){
        console.log("in show user bottles")
        username = ''
        let button = ev.target
        let userId = button.getAttribute('data-id')
        for(let index=0; index<usersWithId.length; index++){
            for (let user in usersWithId[index]){
                // let dialogContent = `<p>${usersWithId[0][user]}</p>`
                // console.log(dialogContent)
                if (usersWithId[index][user] == userId){
                    username = user
                }
            }
        }
        let title = `<h3> ${username} </h3>`
        let first = true;
        for (let indexRow in usersIdWithBottles[userId]) {
            bottleData = usersIdWithBottles[userId][indexRow][0]
            if(first==true){
                table = '<table>'
                table = table + "<thead>"
                table = table + "<tr>"
                for(key in bottleData){
                    key = key.charAt(0).toUpperCase() + key.slice(1);
                    table = table + `<th> ${key} </th>`
                }
                table = table + "</tr>"
                table = table + "</thead>"
                table = table + "<tbody>"
                first = false
            }
            table = table + "<tr>"
            for (let key in bottleData) {
                if(key == 'image'){
                    table = table + `<td><img src="/public/<?= App::$user->username ?>/${bottleData[key]}" alt="${bottleData[key]}" style="width: 6rem; height: 6rem;"/></td>`
                } else {
                    table = table + `<td> ${bottleData[key]} </td>`
                }
                table = table + bottleData[key]
            }
            table = table + "</tr>"
        }

        table = table + "</tbody>"
        table = table + "</table>"

        let form = '<form method="dialog">'

        form += `<button value='accept' id="accept-button" class='accept-button' data-id='${userId}'>Accept bottles</button>`

        form += "<button value='cancel'>Cancel</button>"

        form += '</form>'

        let dialogContent = title + table + form
        
        document.getElementById('show-bottles-dialog-content').innerHTML = dialogContent
    }

    document.querySelector('.show-bottles-dialog').addEventListener('close', (ev) => {
        let dialog = ev.target
        closeValue = dialog.returnValue
        console.log("closeeed")
        if(closeValue.localeCompare('accept')==0){
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function(response) {
                if (this.readyState === 4 && this.status === 200) {
                        data = JSON.parse(this.response)
                        console.log("responseeeeeeeeeeeeee");
                        console.log(JSON.parse(this.response));
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
                                    if(key == 'image'){
                                        table = table + `<td><img src="/public/<?= App::$user->username ?>/${data[rowIndex][key]}" alt="${data[rowIndex][key]}" style="width: 6rem; height: 6rem;"/></td>`
                                    } else {
                                        table = table + `<td> ${data[rowIndex][key]} </td>`
                                    }
                                }
                                table = table + `<td>                         <div class="options-update-delete">
                                    <button class="option-update update-button" data-id="${rowIndex}">Update</button>
                                    <button class="option-delete delete-button" data-id="${rowIndex}">Delete</button>
                                    <input class="checkbox" type="checkbox" data-id="<?= $index ?>">
                                </div> </td>`
                                table = table + "</tr>"
                            }
                            table = table + "</tbody> </table>"
                            table = table + `<input type="button" value="Export to CSV" onclick="tableToCSV()">`
                            table = table + `<input type="button" onclick="generatePDF()" value="Export To PDF" />`
                            table = table + `<button id="send-button">Send bottles</button>`
                            table = table + `<button id="show-new-request">New bottles request</button>`
                            document.getElementById("content").innerHTML = table;
                            document.querySelectorAll('.update-button').forEach(updateFunction)
                            document.querySelectorAll('.delete-button').forEach((el) => {
                                el.addEventListener('click', (ev) => {
                                    deletee(ev)
                                })
                            })
                            if(data.length != 0){
                                document.getElementById('send-button').addEventListener('click', sendBottles)
                            }
                            document.getElementById('show-new-request').addEventListener('click', showUsersRequestsDialog)
                        }
                    }
            }
            console.log("dataset")
            acceptButton = dialog.querySelector(`#accept-button`)
            console.log(acceptButton.dataset.id)
            let formData = new FormData()
            formData.append('userId', acceptButton.dataset.id)
            formData.append('rows', JSON.stringify(newBottlesRequests[acceptButton.dataset.id]))
            xhttp.open("POST", `/bottles/manage/accept`);
            xhttp.send(formData);
        }
    })

    document.getElementById('show-new-request').addEventListener('click', showUsersRequestsDialog)

    function sendBottles(){
        let dialog =  document.querySelector('.send-bottles-dialog') //('dialog')
        dialog.showModal()
    }

    document.querySelectorAll('.show-bottles-from-user').forEach((el) => {
        el.addEventListener('click', (ev) => {
            console.log("hello")
            showUserBottlesDialog(ev)
        })
    })

    document.querySelector('.send-bottles-dialog').addEventListener('close', (ev) => {
        let dialog = ev.target
        closeValue = dialog.returnValue
        if(closeValue.localeCompare('yes')==0){
            const xhttp = new XMLHttpRequest();
                    xhttp.onload = function(response) {
                    if (this.readyState === 4 && this.status === 200) {
                        data = JSON.parse(this.response)
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
                                    if(key == 'image'){
                                        table = table + `<td><img src="/public/<?= App::$user->username ?>/${data[rowIndex][key]}" alt="${data[rowIndex][key]}" style="width: 6rem; height: 6rem;"/></td>`
                                    } else {
                                        table = table + `<td> ${data[rowIndex][key]} </td>`
                                    }
                                }
                                table = table + `<td>                         <div class="options-update-delete">
                                    <button class="option-update update-button" data-id="${rowIndex}">Update</button>
                                    <button class="option-delete delete-button" data-id="${rowIndex}">Delete</button>
                                    <input class="checkbox" type="checkbox" data-id="<?= $index ?>">
                                </div> </td>`
                                table = table + "</tr>"
                            }

                            table = table + "</tbody> </table>"
                            table = table + `<input type="button" value="Export to CSV" onclick="tableToCSV()">`
                            table = table + `<input type="button" onclick="generatePDF()" value="Export To PDF" />`
                            table = table + `<button id="send-button">Send bottles</button>`
                            table = table + `<button id="show-new-request">New bottles request</button>`
                            document.getElementById("content").innerHTML = table;
                            document.querySelectorAll('.update-button').forEach(updateFunction)
                            document.querySelectorAll('.delete-button').forEach((el) => {
                                el.addEventListener('click', (ev) => {
                                    deletee(ev)
                                })
                            })
                            if(data.length != 0){
                                document.getElementById('send-button').addEventListener('click', sendBottles)
                            }
                            document.getElementById('show-new-request').addEventListener('click', showUsersRequestsDialog)
                        }
                    }
                }
                        const idRows = [];
                document.querySelectorAll('.check-box').forEach((el) => {
                    if(el.checked){
                        let id = el.getAttribute('data-id');
                        idRows.push(data[id]['id']);
                    }
                })
                console.log(idRows);
                let formData = new FormData()
                    formData.append('username', dialog.querySelector(`#send-bottles-form .send-dialog-input[name=username-to-send]`).value)
                    formData.append('ids', JSON.stringify(idRows))
                    xhttp.open("POST", `/bottles/manage/send`);
                    xhttp.send(formData);
        }
    })
    if(data.length != 0){
        document.getElementById('send-button').addEventListener('click', sendBottles)
    }
    document.querySelector('.update-dialog').addEventListener('close', (ev) => {
        let dialog = ev.target
                alert('Closed. The user clicked the button with the value of ' + dialog.returnValue)

                closeValue = dialog.returnValue
                if(closeValue.localeCompare('yes')==0){
                    newInputId = dialog.querySelector(`#dialog-form .dialog-input[name=id]`).value
                    newInputType = dialog.querySelector(`#dialog-form .dialog-input[name=type]`).value
                    newInputImage = dialog.querySelector(`#dialog-form .dialog-input[name=image]`).files[0]
                    newInputValue = dialog.querySelector(`#dialog-form .dialog-input[name=value]`).value
                    newInputCountry = dialog.querySelector(`#dialog-form .dialog-input[name=country]`).value
                    console.log("New Input image ", newInputImage)
                    //console.log("salut, id=" + data[id]['id'])
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
                                    if(key == 'image'){
                                        table = table + `<td><img src="/public/<?= App::$user->username ?>/${data[rowIndex][key]}" alt="${data[rowIndex][key]}" style="width: 6rem; height: 6rem;"/></td>`
                                    } else {
                                        table = table + `<td> ${data[rowIndex][key]} </td>`
                                    }
                                }
                                table = table + `<td>                         <div class="options-update-delete">
                                    <button class="option-update update-button" data-id="${rowIndex}">Update</button>
                                    <button class="option-delete delete-button" data-id="${rowIndex}">Delete</button>
                                    <input class="checkbox" type="checkbox" data-id="<?= $index ?>">
                                </div> </td>`
                                table = table + "</tr>"
                            }

                            table = table + "</tbody> </table>"
                            table = table + `<input type="button" value="Export to CSV" onclick="tableToCSV()">`
                            table = table + `<input type="button" onclick="generatePDF()" value="Export To PDF" />`
                            table = table + `<button id="send-button">Send bottles</button>`
                            table = table + `<button id="show-new-request">New bottles request</button>`
                            document.getElementById("content").innerHTML = table;
                            document.querySelectorAll('.update-button').forEach(updateFunction)
                            document.querySelectorAll('.delete-button').forEach((el) => {
                                el.addEventListener('click', (ev) => {
                                    deletee(ev)
                                })
                            })
                            if(data.length != 0){
                                document.getElementById('send-button').addEventListener('click', sendBottles)
                            }
                            document.getElementById('show-new-request').addEventListener('click', showUsersRequestsDialog)
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

            let dialog =  document.querySelector('.update-dialog') //('dialog')
            let lookupTable = Object.keys(elData).slice()
            let rowIdFromDb = dialog.querySelector(`#dbRowId`)
            rowIdFromDb.innerHTML = "ID=" + elData['id']
            console.log(rowIdFromDb.innerHTML)
            console.log("#####################_elData:")
            console.log(elData)
            console.log("#####################_lookupTable:")
            console.log(lookupTable)
            lookupTable.forEach((el) => {
                //console.log("EELLL", el);
                if(el != 'image'){
                    let input = dialog.querySelector(`#dialog-form .dialog-input[name=${el}]`)
                    input.value = elData[el]
                }
            })
            
            dialog.showModal()
        })
    }
    //const arr
    document.getElementById('add-button').addEventListener('click', function() {
        data = JSON.parse('<?= json_encode($userBottles) ?>')
        var type = document.getElementById('_type').value
        var value = document.getElementById('_value').value
        var image = document.getElementById('_image').files[0]
        var country = document.getElementById('_country').value
        console.log("file::")
        console.log(image)
        //var image = document.querySelector("#_image")
        // const reader = new FileReader();
        // reader.addEventListener("load", ()=>{
        //     let upload_image = reader.result
        //     console.log("in load image listener::")
        //     console.log(upload_image)
        // })
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function(response) {
            if (this.readyState === 4 && this.status === 200) {
                console.log("DATA", this.response)
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
                            if(key == 'image'){
                                        table = table + `<td><img src="/public/<?= App::$user->username ?>/${data[rowIndex][key]}" alt="${data[rowIndex][key]}" style="width: 6rem; height: 6rem;"/></td>`
                                    } else {
                                        table = table + `<td> ${data[rowIndex][key]} </td>`
                                    }
                        }
                        table = table + `<td>                         <div class="options-update-delete">
                            <button class="option-update update-button" data-id="${rowIndex}">Update</button>
                            <button class="option-delete delete-button" data-id="${rowIndex}">Delete</button>
                            <input class="checkbox" type="checkbox" data-id="<?= $index ?>">
                        </div> </td>`
                        table = table + "</tr>"
                    }

                    table = table + "</tbody> </table>"
                            table = table + `<input type="button" value="Export to CSV" onclick="tableToCSV()">`
                            table = table + `<input type="button" onclick="generatePDF()" value="Export To PDF" />`
                            table = table + `<button id="send-button">Send bottles</button>`
                            table = table + `<button id="show-new-request">New bottles request</button>`
                    document.getElementById("content").innerHTML = table;
                    document.querySelectorAll('.update-button').forEach(updateFunction)
                    document.querySelectorAll('.delete-button').forEach((el) => {
                        el.addEventListener('click', (ev) => {
                            deletee(ev)
                        })
                    })
                    if(data.length != 0){
                                document.getElementById('send-button').addEventListener('click', sendBottles)
                            }
                            document.getElementById('show-new-request').addEventListener('click', showUsersRequestsDialog)
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
                            if(key == 'image'){
                                        table = table + `<td><img src="/public/<?= App::$user->username ?>/${data[rowIndex][key]}" alt="${data[rowIndex][key]}" style="width: 6rem; height: 6rem;"/></td>`
                                    } else {
                                        table = table + `<td> ${data[rowIndex][key]} </td>`
                                    }
                        }
                        table = table + `<td>                         <div class="options-update-delete">
                            <button class="option-update update-button" data-id="${rowIndex}">Update</button>
                            <button class="option-delete delete-button" data-id="${rowIndex}">Delete</button>
                            <input class="checkbox" type="checkbox" data-id="<?= $index ?>">
                        </div> </td>`
                        table = table + "</tr>"
                    }

                    table = table + "</tbody> </table>"
                            table = table + `<input type="button" value="Export to CSV" onclick="tableToCSV()">`
                            table = table + `<input type="button" onclick="generatePDF()" value="Export To PDF" />`
                            table = table + `<button id="send-button">Send bottles</button>`
                            table = table + `<button id="show-new-request">New bottles request</button>`
                    document.getElementById("content").innerHTML = table;
                    document.querySelectorAll('.update-button').forEach(updateFunction)
                    document.querySelectorAll('.delete-button').forEach((el) => {
                        el.addEventListener('click', (ev1) => {
                            deletee(ev1)
                        })
                    })
                    if(data.length != 0){
                                document.getElementById('send-button').addEventListener('click', sendBottles)
                            }
                            document.getElementById('show-new-request').addEventListener('click', showUsersRequestsDialog)
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