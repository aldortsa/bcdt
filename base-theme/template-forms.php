<?php

/**forms**/
function armorall_preprocess_find_retailer_form(&$vars) {
	if ( isset( $vars['node'] ) ) {
		$node = $vars['node'];
		global $base_url;
		$vars['node']->armorall_data->alias = $base_url . '/' .drupal_get_path_alias();
		$vars['node']->armorall_data->form_submitted = false;
		$vars['node']->armorall_data->zip = '';
		$vars['node']->armorall_data->dist = 10;

		if ( isset( $_GET['token'] ) ) {
			$token = trim( $_GET['token'] );
			if ( $token ) {
				if ( isset( $token) ) {
					$vars['node']->armorall_data->form_submitted = true;

					if ( isset( $_GET['zip'] ) && isset( $_GET['dist'] ) ) {
						$zip = trim( $_GET['zip'] );
						$dist = trim( $_GET['dist'] );

						if ( $zip && $dist ) {
							if ( preg_match('/^[0-9]{5}$/i', $zip) && preg_match('/^[\d]+$/i', $dist) ) {
								$vars['node']->armorall_data->zip = $zip;
								$vars['node']->armorall_data->dist = $dist;

								$geo = armorall_get_geocoded_data( $zip );

								if ( $geo['success'] ) {
									$record = $geo['record'];

									$result = armorall_haversine( $record->latitude, $record->longitude, $dist );

									$vars['node']->armorall_data->found_retailers = $result['success'];
									$vars['node']->armorall_data->retailers = $result['result'];
								}
							}
						}
					}
				}
			}
		}

    }
}

function armorall_get_geocoded_data($zip) {
	$ret = array( 'success' => false );
	$ch = curl_init();

	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$zip.'&country=usa&key=AIzaSyCo5Xu4jZALfW-wQaKT63Mv1wk7vAlAuX8';
	curl_setopt($ch,CURLOPT_URL, trim($url));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = json_decode(curl_exec($ch));
	curl_close($ch);
	
	if($result){
		$tmp_obj = new stdClass();
		$tmp_obj->latitude = $result->results[0]->geometry->location->lat;
		$tmp_obj->longitude = $result->results[0]->geometry->location->lng;
		$ret['success'] = true;
		$ret['record'] = $tmp_obj;
		
	}
	return $ret;
}

function armorall_haversine($latitude, $longitude, $dist = 10, $radius = 3956) {
	$ret = array( 'success' => false );

	$result = db_query( 
				  'SELECT sl.*, (:radius * 2) * ASIN(SQRT(POWER(SIN((:latitude1 - sl.latitude) * PI() / 360), 2) + COS(:latitude2 * PI() / 180) * COS(sl.latitude * PI() / 180) * POWER(SIN((:longitude1 - sl.longitude) *  PI() / 360), 2))) as distance
				   FROM {store_locations} sl
				   WHERE sl.latitude between :latitude3 - (:dist1 / 69) and :latitude4 + (:dist2 / 69)
					   AND sl.longitude between :longitude2 - (:dist3 / abs(cos(radians(:latitude5)) * 69)) and :longitude3 + (:dist4 / abs(cos(radians(:latitude6)) * 69))
				   HAVING distance <= :dist5
				   ORDER BY Distance', 
				  array( 
				  	  ':radius' 	=> $radius,
				  	  ':latitude1'	=> $latitude,
				  	  ':latitude2'	=> $latitude,
				  	  ':longitude1'	=> $longitude,
				  	  ':latitude3'	=> $latitude,
				  	  ':dist1'		=> $dist,
				  	  ':latitude4'	=> $latitude,
				  	  ':dist2'		=> $dist,
				  	  ':longitude2' => $longitude,
				  	  ':dist3'		=> $dist,
				  	  ':latitude5'	=> $latitude,
				  	  ':longitude3'	=> $longitude,
				  	  ':dist4'		=> $dist,
				  	  ':latitude6'	=> $latitude,
				  	  ':dist5'		=> $dist
				  ) 
			  );

	if ( $result->rowCount() > 0 ) {
		$ret['success'] = true;
		$ret['result'] = $result;
	}

	return $ret;
}
/**forms**/