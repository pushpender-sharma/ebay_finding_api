<head>
    <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style>
        .glyphicon { margin-right:5px; }
        .thumbnail
        {
            margin-bottom: 20px;
            padding: 0px;
            -webkit-border-radius: 0px;
            -moz-border-radius: 0px;
            border-radius: 0px;
        }

        .item.list-group-item
        {
            float: none;
            width: 100%;
            background-color: #fff;
            margin-bottom: 10px;
        }
        .item.list-group-item:nth-of-type(odd):hover,.item.list-group-item:hover
        {
            background: #428bca;
        }

        .item.list-group-item .list-group-image
        {
            margin-right: 10px;
        }
        .item.list-group-item .thumbnail
        {
            margin-bottom: 0px;
        }
        .item.list-group-item .caption
        {
            padding: 9px 9px 0px 9px;
        }
        .item.list-group-item:nth-of-type(odd)
        {
            background: #eeeeee;
        }

        .item.list-group-item:before, .item.list-group-item:after
        {
            display: table;
            content: " ";
        }

        .item.list-group-item img
        {
            float: left;
        }
        .item.list-group-item:after
        {
            clear: both;
        }
        .list-group-item-text
        {
            margin: 0 0 11px;
        }
    </style>
</head>
<?php
include_once 'mysqli.php';

function pr($d) {
    echo "<pre>";
    print_r($d);
    echo "</pre>";
}

$testing = FALSE;
$high_class = FALSE;
$filter = FALSE;
$archived_check = FALSE;
$low_check = FALSE;
$medium_check = FALSE;
$high_check = FALSE;
$items = array();
if (!$testing) {
    if (isset($_POST['multi_select']) && is_array($_POST['multi_select'])) {
        $status = $_POST['status'];
        foreach ($_POST['multi_select'] as $id) {
            $query = 'UPDATE `products` SET `status` =' . "'$status'" . ' WHERE id =' . $id;
            $conn->query($query);
        }
        unset($_POST);
    }

    if (isset($_POST['high'])) {
        $high_check = TRUE;
        $filter = TRUE;
        $high_result = $conn->query("SELECT * FROM products WHERE status = 'High'");
        $items = array_merge($items, $high_result->rows);
//    pr($items);
//    die("tre");
    }
    if (isset($_POST['medium'])) {
        $medium_check = TRUE;
        $filter = TRUE;
        $medium_result = $conn->query("SELECT * FROM products WHERE status = 'medium'");
        $items = array_merge($items, $medium_result->rows);
//    pr($items);
//    die("tre");
    }
    if (isset($_POST['low'])) {
        $low_check = TRUE;
        $filter = TRUE;
        $low_result = $conn->query("SELECT * FROM products WHERE status = 'low'");
        $items = array_merge($items, $low_result->rows);
//    pr($items);
//    die("tre");
    }

    if (isset($_POST['archived'])) {
        $archived_check = TRUE;
        $filter = TRUE;
        $archived_result = $conn->query("SELECT * FROM products WHERE status = 'archived'");
        $items = array_merge($items, $archived_result->rows);
//    pr($items);
//    die("tre");
    }
    if ($filter == FALSE) {
        $high_class = TRUE;
        $high_result = $conn->query("SELECT * FROM products WHERE status = 'high'");
        $items = array_merge($items, $high_result->rows);
        $medium_result = $conn->query("SELECT * FROM products WHERE status = 'medium'");
        $items = array_merge($items, $medium_result->rows);
//    $low_result = $conn->query("SELECT * FROM products WHERE status = 'low'");
//    $items = array_merge($items, $low_result->rows);
//    $archived_result = $conn->query("SELECT * FROM products WHERE status = 'archived'");
//    $items = array_merge($items, $archived_result->rows);
//    $result = $conn->query("SELECT * FROM products");
//    $items = $result->rows;
    }
} else {
    $archived_result = $conn->query("SELECT * FROM products WHERE status = 'archived'");
    $items = array_merge($items, $archived_result->rows);
}
//pr($result);
//die("test");
if (key_exists(0, $items)) {
    foreach ($items as $item_id) {
        $ids[] = $item_id['id'];
    }
//    $items_json = json_encode($ids);
//        if ($key < 1) {
    ?>
    <div class="container">
        <div class="well well-sm">
            <strong style="text-align: center;">Industrial Metalworking Tooling</strong>
        </div>
        <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label class="sr-only" for="exampleInputAmount">Filter By Status</label>
                <div class="input-group">
                    Filter By Status:
                    <?php if ($high_class) { ?>
                        <input  type="checkbox" checked="checked" name="high" value="High"> High
                        <input  type="checkbox" checked="checked" name="medium" value="Medium"> Medium
                        <?php
                    } else {
                        if (!$high_check) {
                            ?>
                            <input  type="checkbox" name="high" value="High"> High
                        <?php } else { ?> <input  type="checkbox" checked="checked" name="high" value="High"> High
                            <?php
                        }
                        if (!$medium_check) {
                            ?>
                            <input  type="checkbox" name="medium" value="Medium"> Medium
                        <?php } else { ?>
                            <input  type="checkbox" checked="checked" name="medium" value="Medium"> Medium
                            <?php
                        }
                    }
                    if (!$low_check) {
                        ?>
                        <input  type="checkbox" name="low" value="Low"> Low
                    <?php } else { ?>
                        <input  type="checkbox" checked="checked" name="low" value="Low"> Low
                        <?php
                    }
                    if (!$archived_check) {
                        ?>
                        <input  type="checkbox" name="archived" value="Archived"> Archived
                    <?php } else { ?>        
                        <input  type="checkbox" checked="checked" name="archived" value="Archived"> Archived
                    <?php } ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">View</button>
        </form>
        <!--<input type="hidden" data-scenario="scenario1" class="form-control notes" placeholder="Enter Notes"           value="<?php echo $ids; ?>">-->
        <button type="button" data-items=<?php echo json_encode($ids); ?> id="export_csv" class="btn btn-primary pull-right col-md-2 col-md-offset-1 col-xs-offset-3">Export CSV</button>

        <div id="products list-group-item" class="row list-group">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!--<form action="update.php" method="post" enctype="multipart/form-data">-->


                Apply Action: <select class="selectpicker" name="status">
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                    <option value="Archived">Archived</option>
                </select> 
                <button type="submit" name="status_change" value="true" class="btn btn-primary">Apply</button><br>
                <input class="selectall" type="checkbox" name="high" value="High">Select All/Select None
                <?php
                foreach ($items as $key => $item) {
//                        pr($item);die('123');
                    ?>

                    <div class="item list-group-item col-xs-4 col-lg-4">
                        <input class="multi_select" type="checkbox" name="multi_select[]" value="<?php echo $item['id']; ?>">
                        <div class="thumbnail">
                            <img class="img-responsive" src="<?php echo $item['image_link']; ?>" alt="" />
                            <div class="caption" style="padding-left: 20%;">
                                <div class="row">
                                    <a href="<?php echo $item['product_link']; ?>">
                                        <u><b class="group inner list-group-item-heading">
                                                <?php echo $item['product_title']; ?>
                                            </b></u></a> <span class="pull-right" style="padding-right: 2%;"><?php echo $item['seller_name'] . '(' . $item['feedback_score'] . ')'; ?></span><br>
                                    Price : <?php echo ' $' . $item['price']; ?><br>
                                    Shipping Price : <?php echo ' $' . $item['shipping_price']; ?><br>
                                    <?php echo $item['listing_type']; ?><br>
                                    <?php echo $item['listing_date']; ?><br>
                                    Status : <?php echo ' ' . $item['status']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } //echo $key;      ?>
            </form>
        </div>
    </div>
    <?php
//        }
} else {
    exit("No product found.");
}
?>

<script>
    $(".selectall").change(function () {  //"select all" change 
        var status = this.checked; // "select all" checked status
        $('.multi_select').each(function () { //iterate all listed checkbox items
            this.checked = status; //change ".checkbox" checked status
        });
    });

    $('.multi_select').change(function () { //".checkbox" change 
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (this.checked == false) { //if this item is unchecked
            $(".selectall")[0].checked = false; //change "select all" checked status to false
        }

        //check "select all" if all checkbox items are checked
        if ($('.multi_select:checked').length == $('.multi_select').length) {
            $(".selectall")[0].checked = true; //change "select all" checked status to true
        }
    });

    $("#export_csv").click(function (e) {
        e.preventDefault();
//        var data = $('input[data-scenario=scenario1]').val();
        var data = $(this).data('items');
//        console.log(data);
        $.ajax({
            type: "POST",
            url: "http://dev.techmarbles.com/Ecommerce/Ebay/export_csv.php",
            data: {'items': data},
            dataType: "json",
            success: function (response) {
                                window.location = 'export.csv';
            },
            error: function (response) {
                alert("Export not successful.");
            }
        });
    });
//    });

</script>
