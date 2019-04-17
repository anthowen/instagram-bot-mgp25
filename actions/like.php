<?php
	
	function likeTimelineFeeds($source, $dest, $counts){
		global $users;
		
		/////// CONFIG ///////
		$debug = true;
		$truncatedDebug = false;

		// Retrieve User Crediential
		if(!array_key_exists($source, $users)){
			echo 'Aborting ...   ' . $source . ' not found in the user table\n';
			return false;
		}
		$srcId = '';
		$srcPwd = $users[$source];
		
		//////////////////////
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
		    $ig->login($source, $srcPwd);
		    $srcId = $ig->account_id;
		} catch (\Exception $e) {
		    echo 'Login failed with ' . $source . ' : '.$e->getMessage()."\n";
		    return false;
		}

		try {
			$dstId = $ig->people->getUserIdForName($dest);

	        $maxId = null;
	        $feeds = 0;
		    do {
		        // Request the page corresponding to maxId.
		        $response = $ig->timeline->getUserFeed($dstId, $maxId);
		        
		        foreach ($response->getItems() as $item) {
		        	if( $feeds >= $counts ){
		        		return true;
		        	}
		        	try {
		        		$ig->media->like($item->getId());
		        	} catch (Exception $e) {
		        		echo "Liking ( dest , " . "https://instagram.com/p/" . $item->getCode() .") failed: " . $e->getMessage() . "\n";
		        	}
		            
		            $feeds ++;
		        }
		        $maxId = $response->getNextMaxId();

		        echo "Sleeping for 5s...\n";
		        sleep(5);

		    } while ($maxId !== null); 

		} catch (\Exception $e) {
		    echo 'Getting User Feed failed: '.$e->getMessage()."\n";
		    return false;
		}
		
		return $feeds == 0 ? false : true;
	}
	