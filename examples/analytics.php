<?php

use Hborras\TwitterAdsSDK\TwitterAds;
use Hborras\TwitterAdsSDK\TwitterAds\Account;
use Hborras\TwitterAdsSDK\TwitterAds\Analytics;
use Hborras\TwitterAdsSDK\TwitterAds\Campaign\LineItem;

require '../autoload.php';

const CONSUMER_KEY = 'your consumer key';
const CONSUMER_SECRET = 'your consumer secret';
const ACCESS_TOKEN = 'your access token';
const ACCESS_TOKEN_SECRET = 'your access token secret';
const ACCOUNT_ID = 'account id';

// Create Twitter Ads Api Instance
$api = TwitterAds::init(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

$accounts = $api->getAccounts();

// load up the account instance, campaign and line item
$account = new Account(ACCOUNT_ID);

$account->read();
// Limit request count and grab the first 10 line items from Cursor
$lineItems = $account->getLineItems("", ['count' => 10]);

// The list of metrics we want to fetch, for a full list of possible metrics
$metrics = [Analytics::ANALYTICS_METRIC_GROUPS_ENGAGEMENT, Analytics::ANALYTICS_METRIC_GROUPS_BILLING];

// Fetching stats on the instance
/** @var LineItem $lineItem */
$lineItem = $lineItems->first();
$stats = $lineItem->stats($metrics);

// Fetching stats for multiple line items
$ids = array_map(
    function($o) {
        return $o->getId();
    },
    $lineItems->getCollection()
);
$stats = (new LineItem())->all_stats($ids, $metrics);

print_r($stats);