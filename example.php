<?php

require_once("finding.php");
require_once 'mysqli.php';

function pr($d) {
    echo "<pre>";
    print_r($d);
    echo "</pre>";
}

$ebay = new ebay();

//search by keyword - keywords can be space separated list of searches
//pr($ebay->findProduct('findItemsByKeywords', 'Dresses Pants', 2));
//search by category - dresses = 63861, pants = 63863
$details = $ebay->findProduct('findItemsByCategory', '92084', 2);
$results_array = $details['findItemsByCategoryResponse'][0];
//pr($results_array);die("***");
$success = $results_array['ack'][0];

if ($success) {
    $count = 100;
    $total_pages = $results_array['paginationOutput'][0]['totalPages'][0];
    for ($page_number = 1; $page_number <= $total_pages; $page_number++) {
        $counting = $count * $page_number;
        $query = "INSERT IGNORE INTO products(`item_id`,`product_title`,`price`,`shipping_price`,`image_link`,`status`,`product_link`,`seller_name`,`feedback_score`,`listing_type`,`listing_date`) VALUES ";
//        if($page_number == 101)
        $data_per_page = $ebay->findProduct('findItemsByCategory', '92084', 100, $page_number);
        foreach ($data_per_page['findItemsByCategoryResponse'][0]['searchResult'][0]['item'] as $single_item) {
//            $query = "INSERT INTO products(`item_id`,`product_title`,`price`,`shipping_price`,`image_link`,`status`,`product_link`,`seller_name`,`listing_type`,`listing_date`) VALUES ";
//            pr($single_item);
//            die("item");
            $item_id = $single_item['itemId'][0];
            $item_title = str_ireplace("'", "''", $single_item['title'][0]);
            $item_price = $single_item['sellingStatus'][0]['currentPrice'][0]['__value__'];
            $item_shipping_price = 0;
            $product_url = $single_item['viewItemURL'][0];
            $image_url = $single_item['galleryURL'][0];
            $seller_name = $single_item['sellerInfo'][0]['sellerUserName'][0];
            $feedback_score = $single_item['sellerInfo'][0]['feedbackScore'][0];
            $status = get_status($seller_name);
            $listing_date = $single_item['listingInfo'][0]['startTime'][0];
            $listing_type = $single_item['listingInfo'][0]['listingType'][0];
            $query .= '(' . "'$item_id'" . ',' . "'$item_title'" . ',' . "'$item_price'" . ',' . "'$item_shipping_price'" . ',' . "'$image_url'" . ',' . "'$status'" . ',' . "'$product_url'" . ',' . "'$seller_name'" . ',' . "'$feedback_score'" . ',' . "'$listing_type'" . ',' . "'$listing_date'" . '),';
        }
        pr($counting . ' products added.');
        $final_query = rtrim($query, ',');
//        pr($page_number . ' => ' . $final_query);

        $conn->query($final_query);

        if ($page_number == 100) {
            exit("Almost 10000 products added. Limit Exceeded.");
        }
    }
    exit("All products added.");
//    pr($total_pages);
//    die('test');
}

//search by product id - little mermaid = 53039031
//print_r($ebay->findProduct('findItemsByProduct', '53039031'));
//get histogram data by category
//print_r($ebay->getHistograms('63861'));
//get keyword search recommendations
//print_r($ebay->getKeywordRecommendations('acordian'));

function get_status($seller_name) {
    switch ($seller_name) {
        case 'superiormachinetools':
            $status = 'Archived';
            break;
        case 'dbmimports':
            $status = 'Archived';
            break;
        case 'joepuuri':
            $status = 'High';
            break;
        case 'toolprecision':
            $status = 'High';
            break;

        default:
            $status = 'Medium';
            break;
    }

    return $status;
}

?>