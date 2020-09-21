<?php
	require_once("config.php");
	$cond = $_GET['cond'];
	$value = $_GET['value'];
	$sql = '';
	switch ($cond) {
		case "tract10":
			$sql = "SELECT gid, statefp10, countyfp10, tractce10, geoid10, name10, ST_AsGeoJSON(geom) AS geojson, geom  FROM public.tracts2010;";
		case "stTract-summary":
			$sql = "SELECT * FROM cr_crosswalks where statefp='". $value . "';";
		break;
		case "stTract-tract10":
			$sql = "SELECT * FROM tracts2010 where statefp10='". $value . "';";
		break;
		case "stTract-tract17":
			$sql = "SELECT * FROM tracts2017 where statefp='". $value . "';";
		break;
		case "stTract-tract18":
			$sql = "SELECT * FROM tracts2018 where statefp='". $value . "';";
		break;
		case "chng1year":
			$sql = "SELECT b.stusps, a.statefp, a.countyfp, a.name, a.num_of_tracts17, a.num_of_tracts18 FROM public.cr_crosswalks as a, states as b where CAST (a.statefp AS INTEGER) = CAST (b.statefp AS INTEGER) and num_of_tracts17!=num_of_tracts18";
		break;
		case "chng7year":
			$sql = "SELECT b.stusps, a.statefp, a.countyfp, a.name, a.num_of_tracts10, a.num_of_tracts17 FROM public.cr_crosswalks as a, states as b where CAST (a.statefp AS INTEGER) = CAST (b.statefp AS INTEGER) and num_of_tracts10!=num_of_tracts17";
		break;
		case "chng8year":
			$sql = "SELECT b.stusps, a.statefp, a.countyfp, a.name, a.num_of_tracts10, a.num_of_tracts18 FROM public.cr_crosswalks as a, states as b where CAST (a.statefp AS INTEGER) = CAST (b.statefp AS INTEGER) and num_of_tracts10!=num_of_tracts18";
		break;
		case "states":
			$sql = "SELECT * FROM states";
		break;
		case "counties":
			$sql = "SELECT * FROM counties where statefp='". $value . "';";
		break;		
		default:
			// echo "Your favorite color is neither red, blue, nor green!";
	}
	// $dbcon = pg_connect("host=localhost dbname=farmer user=postgres password=7374");
	$query = pg_query($dbcon,$sql); 
	$geojson = array(); 
	if($cond=='states') {
		while($edge=pg_fetch_assoc($query)) {
			$feature = array (
				'statefp' => $edge['statefp'],
				'stusps' => $edge['stusps'],
				'name' => $edge['name']
		);
		array_push($geojson, $feature);
		}
	}
	else if($cond=='counties') {
		while($edge=pg_fetch_assoc($query)) {
			$feature = array (
				'statefp' => $edge['statefp'],
				'countyfp' => $edge['countyfp'],
				'name' => $edge['name']
		);
		array_push($geojson, $feature);
		}
	}
	else if($cond=='stTract-tract17' || $cond=='stTract-tract18') {
		while($edge=pg_fetch_assoc($query)) {
			$feature = array (
				'statefp' => $edge['statefp'],
				'countyfp' => $edge['countyfp'],
				'name' => $edge['namelsad']
			);
			array_push($geojson, $feature);
		}
	}
	else if($cond=='stTract-tract10') {
		while($edge=pg_fetch_assoc($query)) {
			$feature = array (
				'statefp' => $edge['statefp10'],
				'countyfp' => $edge['countyfp10'],
				'name' => $edge['namelsad10']
			);
			array_push($geojson, $feature);
		}
	}
	else if($cond=='tract10') {
		while($edge=pg_fetch_assoc($query)) {
			$feature = array (
				'gid' => $edge['gid'],
				'statefp' => $edge['statefp10'],
				'countyfp' => $edge['countyfp10'],
				'tractce' => $edge['tractce10'],
				'geoid' => $edge['geoid10'],
				'name' => $edge['name10']
			);
			array_push($geojson, $feature);
		}
	}
	else {
		if($cond=='chng1year') {
			while($edge=pg_fetch_assoc($query)) {
				$feature = array (
					// 'id' => $edge['id'],
					'stusps' => $edge['stusps'],
					'statefp' => $edge['statefp'],
					'countyfp' => $edge['countyfp'],
					'name' => $edge['name'],
					'num_of_tracts17' => $edge['num_of_tracts17'],
					'num_of_tracts18' => $edge['num_of_tracts18']
				);
				array_push($geojson, $feature);
			}
		}
		else if($cond=='chng7year') {
			while($edge=pg_fetch_assoc($query)) {
				$feature = array (
					// 'id' => $edge['id'],
					'stusps' => $edge['stusps'],
					'statefp' => $edge['statefp'],
					'countyfp' => $edge['countyfp'],
					'name' => $edge['name'],
					'num_of_tracts10' => $edge['num_of_tracts10'],
					'num_of_tracts17' => $edge['num_of_tracts17']
				);
				array_push($geojson, $feature);
			}
		}
		else if($cond=='chng8year') {
			while($edge=pg_fetch_assoc($query)) {
				$feature = array (
					// 'id' => $edge['id'],
					'stusps' => $edge['stusps'],
					'statefp' => $edge['statefp'],
					'countyfp' => $edge['countyfp'],
					'name' => $edge['name'],
					'num_of_tracts10' => $edge['num_of_tracts10'],
					'num_of_tracts18' => $edge['num_of_tracts18']
				);
				array_push($geojson, $feature);
			}
		}
	}
			
	pg_close($dbcon);
	header('Content-type: application/json',true);
	echo json_encode($geojson);
?>