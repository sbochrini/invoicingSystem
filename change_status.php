<?php
require_once "db_connection.php";

global $connection;

if(isset($_POST['id'])){
    $id=$_POST['id'];
    $status="";
    $stmt = $connection->prepare('SELECT  invoice_status FROM invoices WHERE id =?');
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $invoice = $stmt->get_result();
    $invoice_status=$invoice->fetch_row();
    $status=($invoice_status[0]=="paid"?"unpaid":"paid");
    $sql = $connection->prepare('UPDATE invoices SET invoice_status=? WHERE id =?');
    $sql->bind_param("si",$status, $id);
    $sql->execute();
    $sql->close();
    //$invoices = $stmt->get_result();
    $data['id']=$id;
    $data['status']=$status;
    echo json_encode($data);
}else{
    echo "An error has occurred";
}


