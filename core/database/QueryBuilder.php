<?php

use App\Core\App;
use App\Models\Bottle;

class QueryBuilder {
    protected $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function selectAllData($table, $class) {
        $statement = $this->pdo->prepare("SELECT * FROM {$table}");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, $class);
    }

    protected function getUserIdByUsername($username){
        $sql = "SELECT id FROM users WHERE username=:username";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getBottleById($id){
        $sql = "SELECT type, image, value, country FROM bottles WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUsersIdsWithBottles(){
        $users = $this->getDistinctUsersNamesWithIdFromRequests();
        $bottlesRequests = $this->getNewBottlesRequests();
        $usersWithBottlesRows = [];
        foreach($users as $user){
            foreach($user as $userName=>$userId){
                foreach($bottlesRequests[$userId] as $index=>$bottleId){
                    $sql = "SELECT type, image, value, country FROM bottles WHERE id=:bottleId";
                    $statement = $this->pdo->prepare($sql);
                    $statement->bindParam(':bottleId', $bottleId);
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_OBJ);
                    $usersWithBottlesRows[$userId][] = $result;
                }
            }
        }
        return $usersWithBottlesRows;
    }

    public function acceptBottlesFromTransferBottlesTable($requestBody){
        $idUsernameFrom = $requestBody['userId'];
        $bottlesIds = json_decode($requestBody['rows']);
        //die(var_dump($rows));
        $idUsernameTo = App::$user->id;
        foreach($bottlesIds as $bottleId){
            try{
            $sql = "SELECT type, image, value, country FROM bottles WHERE id=:bottleId";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':bottleId', $bottleId);
            $statement->execute();
            $bottleData = $statement->fetchAll(PDO::FETCH_OBJ);
            $bottleData = $bottleData[0];
            
            $sql = "INSERT INTO bottles VALUES(0, :type, :image, :value, :country)";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':type', $bottleData->type);
            $statement->bindParam(':image', $bottleData->image);
            $statement->bindParam(':value', $bottleData->value);
            $statement->bindParam(':country', $bottleData->country);
            $statement->execute();
            
            $sql = "DELETE FROM bottles WHERE id=:bottleId";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':bottleId', $bottleId);
            $statement->execute();
            
            $lastBottleId =  $this->getBottleId();
            $sql = "INSERT INTO user_bottles VALUES(:idUsernameTo, :lastBottleId)";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':idUsernameTo', $idUsernameTo);
            $statement->bindParam(':lastBottleId', $lastBottleId[0]->id);
            $statement->execute();
        }catch(\Exception $e)
        {
            die(var_dump($e));
        }
        }
    }

    public function getNewBottlesRequests(){
        $requests = [];
        $username = App::$user->username;
        $usernameId = App::$user->id;

        $sql = "SELECT id_username_from, id_bottle FROM transfer_bottles WHERE id_username_to=:usernameId";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':usernameId', $usernameId);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_OBJ);
        foreach($rows as $row){
            //die(var_dump($row->id_username_from));
            $requests[$row->id_username_from][] = $row->id_bottle;
        }
        //die(var_dump($usernameId));
        return $requests;
    }

    public function getUsersNamesFromRequests(){
        $users = [];
        $usernameId = App::$user->id;
        $sql = "SELECT id_username_from FROM transfer_bottles WHERE id_username_to=:usernameId";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':usernameId', $usernameId);
        $statement->execute();

        $ids = $statement->fetchAll(PDO::FETCH_OBJ);

        foreach($ids as $id){
            $sql = "SELECT username FROM users WHERE id=:id";

            $statement = $this->pdo->prepare($sql);
            // die(var_dump($sql));
            $statement->bindParam(':id', $id->id_username_from);
            $statement->execute();

            $username = $statement->fetchAll(PDO::FETCH_OBJ);

            //die(var_dump($username[0]->username));

            $users[] = $username[0]->username;
        }
        //die(var_dump($users));
        return $users;
    }

    public function getDistinctUsersNamesWithIdFromRequests(){
        $users = [];
        $usernameId = App::$user->id;
        $sql = "SELECT DISTINCT id_username_from FROM transfer_bottles WHERE id_username_to=:usernameId";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':usernameId', $usernameId);
        $statement->execute();

        $ids = $statement->fetchAll(PDO::FETCH_OBJ);
        foreach($ids as $id){
            $sql = "SELECT username FROM users WHERE id=:id";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':id', $id->id_username_from);
            $statement->execute();

            $username = $statement->fetchAll(PDO::FETCH_OBJ);
            //die(var_dump($username[0]->username));
            $users[] = [$username[0]->username => $id->id_username_from];
        }
        return $users;
    }

    public function insertInTransferBottlesTable($requestBody){
        $idUsernameFrom = $this->getUserIdByUsername(App::$user->username)[0]->id;
        $idUsernameTo = ($this->getUserIdByUsername($requestBody['username']))[0]->id;
        $ids = json_decode($requestBody['ids']);
        foreach($ids as $idBottle){
            $sql = "INSERT INTO transfer_bottles VALUES(:idUsernameFrom, :idUsernameTo, :idBottle)";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':idUsernameFrom', $idUsernameFrom);
            $statement->bindParam(':idUsernameTo', $idUsernameTo);
            $statement->bindParam(':idBottle', $idBottle);
            $statement->execute();
            
        }
    }

    public function deleteBottle($id){
        $sql = "DELETE FROM bottles WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->execute();
    }

    public function updateBottle($data){
        $image = '';
        if($data['image'] == 'undefined'){
            $sql = "SELECT image FROM bottles WHERE id=:id";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':id', $data['id']);
            $statement->execute();
            $image = ($statement->fetchAll(PDO::FETCH_OBJ))[0]->image;
            //die(var_dump($image));
        } else {
            if(isset($_FILES['image'])){
                $extension = explode('.', $_FILES['image']['name'])[1];
                $image = substr(sha1($_FILES['image']['name']), 0, 15) . '.' . $extension;
                move_uploaded_file($_FILES['image']['tmp_name'], './public/' . App::$user->username . '/' . $image);
            }
        }
        $sql = "UPDATE bottles SET type=:type, image=:image, value=:value, country=:country WHERE id=:id";
        //die(var_dump($sql));
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':type', $data['type']);
        $statement->bindParam(':image', $image);
        $statement->bindParam(':value', $data['value']);
        $statement->bindParam(':country', $data['country']);
        $statement->bindParam(':id', $data['id']);
        $statement->execute();
    }

    public function getBottleId(){
        $sql = "SELECT max(id) as id from bottles";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function insertBottleInUserBottlesTable(){
        $userId = $this->getUserIdByUsername(App::$user->username)[0]->id;
        $bottleId = $this->getBottleId()[0]->id;
        $sql = "INSERT INTO user_bottles VALUES(:userId, :bottleId)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':userId', $userId);
        $statement->bindParam(':bottleId', $bottleId);
        $statement->execute();
    }

    public function selectBottlesFilteredData($table, $type = '', $value = '', $country = '', $params = []){
        $optionsArr = [];
        if($type != '' && $type != "%27%27"){
            $optionsArr['type'] = $type;
        }
        if($value != '' && $value != "%27%27"){
            $optionsArr['value'] = $value;
        }
        if($country != '' && $country != "%27%27"){
            $optionsArr['country'] = $country;
        }
        $sql = '';
        if(empty($optionsArr)){
            $sql = "SELECT * FROM {$table}";
            $statement = $this->pdo->prepare($sql);
        } else {
            $sql = "SELECT * FROM {$table} WHERE ";
            $first=true;
            foreach($optionsArr as $key => $value){
                if($first == true){
                    $sql = $sql . "{$key}" . '=' . ":{$key}";
                    $first = false;
                } else {
                    $sql = $sql . ' AND ' . "{$key}" . '=' . ":{$key}";
                }
            }
            $statement = $this->pdo->prepare($sql);
            // die(var_dump($statement));
        }
        if(!empty($optionsArr)){
            $first=true;
            foreach($optionsArr as $key => $value){
                //die(var_dump("{$value}"));
                $statement->bindValue(":{$key}", $value);
            // var_dump("{$value}");
            }
        }
        // die(var_dump($statement->debugDumpParams()));
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_CLASS, Bottle::class);
        // die(var_dump($rows));
        return $rows;
    }
    protected function existsValues($params){
        if(empty($params)){
            return false;
        }
        foreach($params as $key => $value){
            if($value != ''){
                return true;
            }
        }
        return false;
    }
    public function selectUserBottles($username, $bottlesTable, $userBottlesTable, $usersTable) {
        $sql = "SELECT * FROM {$userBottlesTable} WHERE user_id=(SELECT id FROM $usersTable WHERE username= :username);";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':username', $username);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_OBJ);
        $bottleIdArr = [];
        foreach($rows as $row){
            $bottleIdArr[] = $row->bottle_id;
        }
        $any = '';
        foreach($bottleIdArr as $id){
            $any = $any . $id . ', ';
        }
        if(empty($bottleIdArr)) return [];
        $any = substr($any, 0, -2);


        $sql = "SELECT * FROM {$bottlesTable} WHERE id in ({$any});";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_OBJ);
        //die(var_dump($rows));
        //die(var_dump($rows));
        return $rows;
    }
// select b.type, b.image, b.value, b.country, u.username from bottles b, users u, user_bottles ub 
// where b.id = ub.bottle_id and ub.user_id = u.id order by value desc limit 10;
    public function selectMostValuableBottles($bottlesTable, $userBottlesTable, $usersTable){
        $sql = "SELECT b.id, b.type, b.image, b.value, b.country, u.username FROM {$bottlesTable} b, {$usersTable} u, {$userBottlesTable} ub WHERE b.id = ub.bottle_id and ub.user_id = u.id ORDER BY value DESC LIMIT 10;";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_CLASS, Bottle::class);
        
        return $rows;
    }

    public function executeStatementSQL($sql){
        try{
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        } catch(PDOException $e){
            var_dump($sql);
            var_dump($e->getMessage());
        }
       // var_dump($statement);
    }

    public function getPdo(){
        return $this->pdo;
    }
}