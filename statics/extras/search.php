<?php 
/*----------------------------------------------------------------------*/
/* This file should handle the search request
/* must return a JSON Object with the results
/* Format:
/* {
	"href" : "link to target page",
	"text" : "text which is shown in the results",
	"img" : "a URL to a 50x50 pixel image which is displayd with the result (optional)"
/* }
/*----------------------------------------------------------------------*/

	ob_start();

	sleep(1);

	//only AJAX request is allowed
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		
		$searchterm = trim($_POST['term']);
		
		//this is how the result should look like
		$result = array(
			array(
				'href' => 'http://google.com',
				'text' => 'Google is a searchengine',
				'img' => 'http://random.example.com/50x50.jpg'
			),
			array(
				'href' => 'http://themeforest.net',
				'text' => 'Buy templates at Themeforest',
				'img' => 'http://random.example.com/50x50.jpg'
			),
			array(
				'href' => 'http://facebook.com',
				'text' => 'Share your information at facebook'
			)
			
		);
		
		//for the demo I use themeforest results
		$result = getResultsThemeForest($searchterm);
		
		//or results from Wikipedia
		// $result = getResultsFromWikipedia($searchterm);
		
		
		//header('Content-type: application/json');
		echo json_encode($result);
	}else{
		die('not allowed');
	}




/*----------------------------------------------------------------------*/
/* Helper functions
/*----------------------------------------------------------------------*/


	function getResultsThemeForest($searchterm){
		
		$searchterm = urlencode($searchterm);
		//if chached version is available
		if(file_exists('_searches/'.$searchterm.'.json')){
			$json = file_get_contents('_searches/'.$searchterm.'.json');
			
		//no cached version available - get it
		}else{
		
			$url = 'http://marketplace.envato.com/api/v3/search:themeforest,,'.str_replace('+', '|',$searchterm).'.json';
			
			$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$json = curl_exec($ch);
			curl_close($ch);
			
			// we reached the API limit - switch to wikipedia
			if(empty($json)){
				return getResultsFromWikipedia($searchterm);
			}
			file_put_contents('_searches/'.$searchterm.'.json', $json);
		}
		
		$obj = json_decode($json);
		
		$results = array();
		$i = 0;
		foreach($obj->search as $item){
			if(++$i > 10) continue;
			$array = array(
					'href' => $item->url.'?ref=Stammi',
					'title' => $item->description,
					'descr' => 'Price: $' . intval($item->item_info->cost),
					'img' => $item->item_info->thumbnail
			);
			
			array_push($results, $array);
		}
		
		return $results;
	}
	
	
	function getResultsFromWikipedia($searchterm){
		
		$searchterm = urlencode($searchterm);
		//if chached version is available
		if(file_exists('_searches/'.$searchterm.'.xml')){
			$xml = file_get_contents('_searches/'.$searchterm.'.xml');
			
		//no cached version available - get it
		}else{
		
			$url = 'http://en.wikipedia.org/w/api.php?action=opensearch&format=xml&limit=8&search='.$searchterm;
			
			$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$xml = curl_exec($ch);
			curl_close($ch);
			
			file_put_contents('_searches/'.$searchterm.'.xml', $xml);
		}
			
		
		$xmlobj = new SimpleXMLElement($xml);
		
		$results = array();
		
		foreach($xmlobj->Section->Item as $node){
			$array = array(
					'href' => (string) $node->Url,
					'title' => (string) $node->Text,
					'descr' => (string) $node->Description
			);
			
			if(isset($node->Image)){
				$array['img'] = (string) @$node->Image[0]->attributes();
			}
			
			array_push($results, $array);
		}

		return $results;
	}
	
	ob_end_flush();
?>