<?php
require_once "db_connection.php";

global $connection;
$query="Select id,client,invoice_amount from invoices";
$result = $connection->query($query);

if($result->num_rows > 0){
    $delimiter = ";";
    $filename = "transactions_".date('Y-m-d').".csv";
    $transactions = array();
    for ($i=0; $i<$result->num_rows; $i++) {
        $row= mysqli_fetch_assoc($result);
        $transactions[$i]['id']= $row['id'];
        $transactions[$i]['client']= $row['client'];
        $transactions[$i]['invoice_amount']= number_format($row['invoice_amount'],5,',',"");
    }
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$filename);
    $file = fopen('php://output', 'w');
    if($file){
        fputcsv($file, array('Invoice ID','Company Name','Invoice Amount'),$delimiter);
        if (count($transactions) > 0) {
            foreach ($transactions as $row) {
                fputcsv($file, $row,$delimiter,' ');
            }
        }
    }
    fclose($file);
}else{
    echo "There are no records in the database to export.";
}

