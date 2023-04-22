<?php

include 'init.php';

//initialization


if($maintenance){
    $ackdata = array(
        "Status" => "Failed",
        "MessageString" => "Servidor em Manutençao",
        "SubscriptionLeft" => "0"
    );
    PlainDie(tokenResponse($ackdata));
}

//Username Validator
$uname = $data["uname"];
if($uname == null || preg_match("([a-zA-Z0-9]+)", $uname) === 0){
    $ackdata = array(
        "Status" => "Failed",
        "MessageString" => "Usuario Invalido",
        "SubscriptionLeft" => "0"
    );
    PlainDie(tokenResponse($ackdata));
}

//Password Validator
$pass = $data["pass"];
if($pass == null || !preg_match("([a-zA-Z0-9]+)", $pass) === 0){
    $ackdata = array(
        "Status" => "Failed",
        "MessageString" => "Senha Invalida",
        "SubscriptionLeft" => "0"
    );
    PlainDie(tokenResponse($ackdata));
}



$query = $con->query("SELECT * FROM `users` WHERE `username` = '".$uname."' AND `password` = '".$pass."'");
if($query->num_rows < 1){
    $ackdata = array(
        "Status" => "Failed",
        "MessageString" => "Usuario ou senha incorretos!",
        "SubscriptionLeft" => "0"
    );
    PlainDie(tokenResponse($ackdata));
}

$res = $query->fetch_assoc();
if($res["registered"] == NULL){
    $query = $conn->query("UPDATE `users` SET `registered` = CURRENT_TIMESTAMP WHERE `username` = '".$uname."' AND `password` = '".$pass."'");
}

$uidup = $data["cs"];

if($res["UID"] == NULL){
    $query = $con->query("UPDATE `users` SET `UID` = '$uidup' WHERE `username` = '".$uname."' AND `password` = '".$pass."'");
}

else if($res["UID"] != $uidup) {
    $ackdata = array(
        "Status" => "Failed",
        "MessageString" => "Seu UID foi alterado!",
        "SubscriptionLeft" => "0"
    );
    PlainDie(tokenResponse($ackdata));
}

if($res["expired"] < $res["registered"]){
    $ackdata = array(
        "Status" => "Failed",
        "MessageString" => "Seu login expirou!",
        "SubscriptionLeft" => "0"
    );
    PlainDie(tokenResponse($ackdata));
}

$ackdata = array(
    "Status" => "Success",
    "MessageString" => "",
    "SubscriptionLeft" => $res["expired"],
    "Validade" => $res["expired"],
    "Title" => $title,
   "icon" => $icon,
   "isactive" => $isactive,
  "Username" => $res["username"],
    "Vendedor" => $res["reseller"],
    "RegisterDate" => $res["registered"],
    $database = date_create($res["expired"]),
$datadehoje = date_create(),
$resultado = date_diff($database, $datadehoje),
$dias = date_interval_format($resultado, '%a'),
"Dias" => " $dias days Trial"
);

echo tokenResponse($ackdata);
