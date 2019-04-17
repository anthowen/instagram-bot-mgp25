<?php
	
	function followUser($source, $dest){
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

		$rankToken = \InstagramAPI\Signatures::generateUUID();

		try {
			$dstId = $ig->people->getUserIdForName($dest);
			$response = $ig->people->follow($dstId);
			// echo 'Status : ' . $response->getStatus() . '\n';
		} catch (\Exception $e) {
		    echo 'Following ( ' . $source . ' --> ' . $dest . ' ) failed: '.$e->getMessage()."\n";
		    return false;
		}
		return true;
	}
	