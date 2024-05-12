<?php
if(!defined('_INCODE')) die('Access Denied...');

function query($sql, $data = [], $statementStatus = false){
    global $conn;
    $query = null;
    try{
        $statement = $conn->prepare($sql);
        if(empty($data)){
            $query = $statement->execute();
        }else{
            $query = $statement->execute($data);
        }
    }catch(Exception $e){
        require_once 'modules/errors/database.php';
        exit;
    }

    if($query && $statementStatus){
        return $statement;
    }
    return $query;
}

function insert($table, $data){
    $keyArr = array_keys($data);
    $fieldStr = implode(', ', $keyArr);
    $valueStr = ':'.implode(', :', $keyArr);
    $sql = "INSERT INTO `$table` ($fieldStr) VALUES ($valueStr)";
    return query($sql, $data);
}

function update($table, $data = [], $condition = ''){
    $updateStr = '';
    foreach($data as $key=>$value){
        $updateStr .= $key . '=:'. $key. ', ';
    }
    $updateStr = rtrim($updateStr, ', ');
    if(!empty($condition)){
        $sql = "UPDATE `$table` SET $updateStr WHERE $condition";
    }else{
        $sql = "UPDATE `$table` SET $updateStr";

    }
    return query($sql, $data);
}

function deleted($table, $condition= ''){
    $sql = "DELETE FROM `$table`";
    if(!empty($condition)){
        $sql.= " WHERE $condition";
    }
    return query($sql);
}

function getRaw($sql){
    $statement = query($sql, [], true);
    if(is_object($statement)){
        $fetchArr = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    return $fetchArr;
}

function firstRaw($sql){
    $statement = query($sql, [], true);
    if(is_object($statement)){
        $fetchArr = $statement->fetch(PDO::FETCH_ASSOC);
    }
    return $fetchArr;
}

function get($table, $field='*', $condition = ''){
    $sql = "SELECT $field FROM `$table`";
    if(!empty($condition)){
        $sql.= " WHERE $condition";
    }
    return getRaw($sql);
}

function first($table, $field='*', $condition = ''){
    $sql = "SELECT $field FROM `$table`";
    if(!empty($condition)){
        $sql.= " WHERE $condition";
    }
    return firstRaw($sql);
}

function getRows($sql){
    $statement = query($sql, [], true);
    if(is_object($statement)){
        $count = $statement->rowCount();
    }
    return $count;
}

function insertId(){
    global $conn;
    return $conn->lastInsertId();
}