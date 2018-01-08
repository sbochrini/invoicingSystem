<?php
require_once "db_connection.php";
require_once "Pagination.php";

global $connection;

$limit=5;
$query="Select * from invoices";
$total_invoices = $connection->query($query);
$total_records = $total_invoices->num_rows;
$pagination= new Pagination($limit,$total_records);
$pagination->setPageOffset();
$stmt = $connection->prepare("SELECT * FROM invoices LIMIT ?,?");
$stmt->bind_param("ii", $pagination->offset, $pagination->limit);

$stmt->execute();
$invoices = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoicing system</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</head>

<body>
<div class="container">
    <div class="row">
        <h3 class="display-4">Invoicing System</h3>
    </div>
    <div class="row">
        <?php    if ($total_records > 0) { ?>
        <table class="table table-hover">
            <thead class="table-primary">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Client</th>
                <th scope="col">Invoice amount</th>
                <th scope="col">Invoice amount plus vat</th>
                <th scope="col">Vat rate</th>
                <th scope="col">Invoice status</th>
                <th scope="col">invoice date</th>
                <th></th>
            </tr>
            </thead>
            <?php $class="";
            while ($invoice = $invoices->fetch_assoc()) {
                if($invoice['invoice_status']=="paid"){
                    $class="badge badge-success";
                }else{
                    $class="badge badge-danger";
                }?>
                <tr>
                    <td><?= $invoice["id"] ?> </td>
                    <td><?= $invoice["client"]?></td>
                    <td><?= $invoice["invoice_amount"] ?></td>
                    <td><?= $invoice["invoice_amount_plus_vat"] ?></td>
                    <td><?= $invoice["vat_rate"] ?></td>
                    <td>
                        <span id="span_<?= $invoice['id']?>" class="<?= $class ?>"><?= $invoice["invoice_status"] ?></span>
                    </td>
                    <td><?= $invoice["invoice_date"] ?></td>
                    <td>
                        <button id="<?= $invoice['id'] ?>" type="button" class="btn btn-info btn-sm ch_status">Change status</button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $pagination->previous_page ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>

            <?php for($p=1;$p<=$pagination->total_pages;$p++){
                echo '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$p.'">'.$p.'</a></li>';
            }?>
            <li class="page-item">
                <a class="page-link" href = "<?= $_SERVER['PHP_SELF'] ?>?page=<?= $pagination->next_page ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php }else{
        echo '<div class="alert alert-danger" role="alert">There are no records.</div>';
    }
    ?>

    <div class="">
        <a class="btn btn-outline-info" href="export_transaction.php" role="button" target="_blank">Export Transactions</a>
        <a class="btn btn-outline-secondary" href="customer_report.php" role="button" target="_blank">Customer Report</a>
    </div>

</div>
</body>

</html>
<script>
    $(".ch_status").click(function () {
        var id=this.id;
        $.ajax({
            url: 'change_status.php',
            data: {id: id},
            type: 'post',
            success: function(data) {
                var result= JSON.parse(data);
                $("#span_"+result.id).toggleClass("badge-success badge-danger").text(result.status);
            },
            error: function(msg) {
                alert(msg);
            }
        });
    });
</script>