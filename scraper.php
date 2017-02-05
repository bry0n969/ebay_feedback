<?php
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';  
    
    $pageurl = "http://feedback.ebay.com/ws/eBayISAPI.dll?ViewFeedback2&ftab=AllFeedback&userid=offroadbelts&iid=-1&de=off&interval=0&searchInterval=30&items=200&searchInterval=30";
    $html = scraperWiki::scrape($pageurl);                    
    $dom = new simple_html_dom();
    $dom->load($html);

    # Get number of pages
    
    $pages = 76;

    # Get first page of results
    
    $rows = $dom->find('.FbOuterYukon tr.bot');
    
    $numrows=count($rows);
    $numrow = $numrows;
    while ($numrow > $numrows/2-1){
            unset($rows[$numrow]);
            $numrow--;
            }

    unset($rows[0]);


        foreach ($rows as $row) {
            $cols = $row->find('td'); 
            $name = trim($cols[1]->plaintext);
            $price = trim($cols[2]->plaintext);
                        
           
            $prem = array (
                'name' => $name,
                'price' => $price
                );

            
             scraperwiki::save_sqlite(array('name'), $prem);
            

        
        }

    # Check if there's more than one page
    
    if ($pages > 1) {

        $page = 2;

   
        while ($page <= $pages) {

        # Load the next page via cURL

        $pageurl = "http://feedback.ebay.com/ws/eBayISAPI.dll?ViewFeedback2&ftab=AllFeedback&userid=offroadbelts&iid=-1&de=off&items=200&interval=0&searchInterval=30&mPg=172&page=".$page;
                        
        $html = scraperWiki::scrape($pageurl);                    
        $dom = new simple_html_dom();
        $dom->load($html);

        # Get the next lot of results

    $rows = $dom->find('.FbOuterYukon tr.bot');
    
    $numrows=count($rows);
    $numrow = $numrows;
    while ($numrow > $numrows/2-1){
            unset($rows[$numrow]);
            $numrow--;
            }

    unset($rows[0]);


        foreach ($rows as $row) {
            $cols = $row->find('td');
            $name = trim($cols[1]->plaintext);
            $price = trim($cols[2]->plaintext);
                       
           
            $prem = array (
                'name' => $name,
                'price' => $price
                );

            
             scraperwiki::save_sqlite(array('name'), $prem);
            

        

        
        }

        $page++;
        }
    
    }
?>
