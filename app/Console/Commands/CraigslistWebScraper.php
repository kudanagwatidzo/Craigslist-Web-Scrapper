<?php

namespace App\Console\Commands;

require 'vendor/autoload.php';

use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CraigslistWebScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craigslist:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape craigslist for results';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $client = new Client();

        $domainBeginning = 'https://newjersey.craigslist.org/search/tla?s=';
        $domainEnding = '&hasPic=1&sort=date';
        $MAX_NUM_OF_ENTRIES = 1000;
        $MAX_NUM_OF_ENTRIES_PER_PAGE = 120;
        $scrapLength = 0;
        $domainMiddle = 0;

        if(Schema::hasTable('product_data')) 
            Schema:: drop('product_data');
        
        Schema::create('product_data', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('Title');
            $table->string('ImageLink');
            $table->string('Price');
            $table->string('LocationLink');
            $table->text('Description');
            $table->bigInteger('CraigslistID');
            $table->timestamp('PostedDate');
        });

        for ($entries = 0; $entries < $MAX_NUM_OF_ENTRIES;) {
            $mainPageCrawler = $client->request('GET', $domainBeginning . $domainMiddle . $domainEnding, array(
                'timeout' => 0,
                'connect_timeout' => 0
            ));
            $pageLinkList = $mainPageCrawler->filter('a.result-title')->each(function ($node){
                $nodeLink = $node->link();
                return $nodeLink->getUri();
            });

            if ($MAX_NUM_OF_ENTRIES_PER_PAGE > $MAX_NUM_OF_ENTRIES - $domainMiddle)
                $scrapLength = $MAX_NUM_OF_ENTRIES - $domainMiddle;
            else $scrapLength = $MAX_NUM_OF_ENTRIES_PER_PAGE;

            for ($currentEntries = 0; $currentEntries < $scrapLength; $currentEntries++, $entries++){
                $productPageCrawler = $client->request('GET', $pageLinkList[$currentEntries]);
                $productTitle = null;
                $productImageLink = null;
                $productPrice = null;
                $productLocation = null;
                $productDescription = null;
                $productID = null;
                $productPostedDate = null;
                $productTitle = $productPageCrawler->filter('span#titletextonly')->text();
                $productImageLink = $productPageCrawler->filter('div.gallery img')->attr('src');
                try {
                    $productPrice = $productPageCrawler->filter('span.price')->text();
                }
                catch (\InvalidArgumentException $iae) {
                    $productPrice = "N/A";
                }
                try {
                    $productLocation = $productPageCrawler->filter('p.mapaddress a')->attr('href');
                }
                catch (\InvalidArgumentException $iae) {
                    $productLocation = "N/A";
                }
                $unfilteredProductDescription = $productPageCrawler->filter('section#postingbody')->text();
                $productDescription = str_replace('QR Code Link to This Post ', null, $unfilteredProductDescription);
                $unfilteredProductID = $productPageCrawler->filter('div.postinginfos > p')->eq(0)->text();
                $productID = str_replace('post id: ', null, $unfilteredProductID);
                $unfilteredProductPostedDate = $productPageCrawler->filter('div.postinginfos > p')->eq(1)->text();
                $productPostedDate = str_replace('posted: ', null, $unfilteredProductPostedDate);
                
                DB::insert('insert into product_data (Title, ImageLink, Price, LocationLink, Description, CraigslistID, PostedDate) values (?, ?, ?, ?, ?, ?, ?)', 
                    [$productTitle, $productImageLink, $productPrice, $productLocation, $productDescription, $productID, $productPostedDate]);
            }
            $domainMiddle += $MAX_NUM_OF_ENTRIES_PER_PAGE;
        }
        $this->info('Cron task was run');
    }
}
