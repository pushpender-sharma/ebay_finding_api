<?php

class ebay {

    //variable instantiation
    private $uri_finding = "http://svcs.ebay.com/services/search/FindingService/v1";
    //test app credentials
//    private $appid = "JoePuuri-SMTListi-SBX-c2466ad0e-96a6ac96";
    //live app credentials
    private $appid = "JoePuuri-SMTListi-PRD-82466ad44-41d581b1";
    private $version;
    private $format = "JSON";

    function pr($d) {
        echo "<pre>";
        print_r($d);
        echo "</pre>";
    }

    /**
     * Constructor
     *
     * Sets the eBay version to the current API version
     * 
     */
    public function __construct() {
        $this->version = $this->getCurrentVersion();
    }

    /**
     * Get Current Version
     *
     * Returns a string of the current eBay Finding API version
     * 
     */
    private function getCurrentVersion() {
        $uri = sprintf("%s?OPERATION-NAME=getVersion&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s", $this->uri_finding, $this->appid, $this->format);

        $response = $this->curl($uri);
        return json_decode($response->getVersionResponse[0]->version[0]);
    }

    /**
     * Find Products
     *
     * Allows you to search for eBay products based on keyword, product id or
     * keywords (default).  Available values for search_type include
     * findItemsByKeywords, findItemsByCategory, and findItemsByProduct
     * 
     */
    public function findProduct($search_type = 'findItemsByKeywords', $search_value = '10181', $entries_per_page = 3, $page_number = 1) {
        //determine how to structure the search query parameter based on search type
        $search_field = "";
        
        //time filter used for last hour only items for case : findItemsByCategory
        $date_time = date('Y-m-d\TH:i:s.u', strtotime('-1 hour')) . 'Z';
//        $this->pr(date('Y-m-d\TH:i:s.u'));
//        $this->pr($date_time);die("sd");
        switch ($search_type) {
            case 'findItemsByCategory': $search_field = "categoryId=$search_value&outputSelector=SellerInfo&itemFilter.name=StartTimeFrom&itemFilter.value=$date_time";
                break;
            case 'findItemsByProduct': $search_field = "productId.@type=ReferenceID&productId=$search_value";
                break;
            case 'findItemsByKeywords':
            default: $search_field = "keywords=" . urlencode($search_value);
                break;
        }

        //build query uri
        $uri = sprintf("%s?OPERATION-NAME=%s&SERVICE-VERSION=%s&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s&REST-PAYLOAD&%s&paginationInput.entriesPerPage=%s&paginationInput.pageNumber=%d", $this->uri_finding, $search_type, $this->version, $this->appid, $this->format, $search_field, $entries_per_page, $page_number);

        return json_decode($this->curl($uri), 1);
    }

    /**
     * Get Histograms
     *
     * Obtains histogram data about a provided category id
     * 
     */
    public function getHistograms($cat = '63861') {
        $uri = sprintf("%s?OPERATION-NAME=getHistograms&SERVICE-VERSION=%s&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s&REST-PAYLOAD&categoryId=%s", $this->uri_finding, $this->version, $this->appid, $this->format, $cat);

        return json_decode($this->curl($uri), 1);
    }

    /**
     * Get keyword recommendations
     *
     * Returns a series of common keyword recommendations for a search keyword.
     * This is useful when an incorrect search term is provided.
     * 
     */
    public function getKeywordRecommendations($keywords) {
        $uri = sprintf("%s?OPERATION-NAME=getSearchKeywordsRecommendation&SERVICE-VERSION=%s&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s&REST-PAYLOAD&keywords=%s", $this->uri_finding, $this->version, $this->appid, $this->format, $keywords);

        return json_decode($this->curl($uri));
    }

    /**
     * cURL
     *
     * Standard cURL function to run GET & POST requests
     * 
     */
    private function curl($url, $method = 'GET', $headers = null, $postvals = null) {
        $ch = curl_init($url);

        if ($method == 'GET') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        } else {
            $options = array(
                CURLOPT_HEADER => true,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_VERBOSE => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $postvals,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_TIMEOUT => 3
            );
            curl_setopt_array($ch, $options);
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}

?>