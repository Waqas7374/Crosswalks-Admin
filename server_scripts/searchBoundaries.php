<?php
	require_once("config.php");
	$cond = $_GET['cond'];
	$filter = $_GET['filter'];
	$sql = '';
	switch ($cond) {
		case "Cities":
			$sql = "SELECT Concat('srchCity',a.gid) as gid, a.statefp, a.geoid, a.name as b2_name, ST_AsGeoJSON(a.geom) AS geojson, a.colorid, b.stusps FROM cities as a, states as b where ". $filter . " and a.statefp=b.statefp;";
		break;
		case "Counties":
			$sql = "SELECT concat('srchCounty',a.gid) as gid, a.statefp, a.countyfp, a.name as b2_name, a.state as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM counties as a where " .$filter. ";";
			// echo $sql;
		break;
		case "Districts":
			$sql = "SELECT Concat('srchDistrict',a.gid) as gid, a.statefp, a.geoid, a.name as b2_name, ST_AsGeoJSON(a.geom) AS geojson, a.colorid, b.stusps FROM districts as a, states as b where ". $filter . " and a.statefp=b.statefp;";
		break;
		case "MSAs":
			$sql = "SELECT Concat('srchMSAS',a.gid) as gid, a.statefp, a.geoid, a.name as b2_name, ST_AsGeoJSON(a.geom) AS geojson, a.colorid, b.stusps FROM msas as a, states as b where ". $filter . " and a.statefp ILIKE '%' || b.statefp || '%' ;";
		break;
		case "NeighbourCities":
			$sql = "SELECT Concat('srchNeighbourCities',a.gid) as gid, a.statefp, a.name as b2_name, '' as geoid, a.state as stusps, a.colorid, ST_AsGeoJSON(a.geom) AS geojson FROM neighbourcities as a where " .$filter. ";";
		break;
		case "Tracts":
			$sql = "SELECT Concat('srchTract',a.gid) as gid, a.statefp, a.countyfp, a.geoid, a.name as b2_name, ST_AsGeoJSON(a.geom) AS geojson, a.colorid, b.stusps FROM tracts as a, states as b where ". $filter . " and a.statefp=b.statefp;";
		break;
		case "Zip":
			$sql = "SELECT Concat('srchZip',a.gid) as gid, a.statefp10 as statefp, s.stusps, a.name as b2_name, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson, a.colorid FROM zip as a, states as s where " .$filter. " and a.statefp10 = s.statefp;";
		break;
		case "LauCnty":
			$sql = "SELECT concat('srchLaus',a.gid) as gid, a.statefp, a.countyfp, a.county_name as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM laucnty as a where " .$filter. ";";
		break;
		case "Food_Report":
			$sql = "SELECT concat('srchFood_Report',a.gid) as gid, a.statefp, a.name as b2_name, a.state as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM food_report as a where " .$filter. ";";
		break;
		case "BEA10":
			$sql = "SELECT concat('srchBEA',a.gid) as gid, '' as statefp, cast(a.lm_code as integer) as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM bea10 as a where " .$filter. ";";
		break;
		case "CBSA10":
			$sql = "SELECT concat('srchCBSA',a.gid) as gid, '' as statefp, cast(a.lm_code as integer) as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM cbsa10 as a where " .$filter. ";";
		break;
		case "ERS10":
			$sql = "SELECT concat('srchERS',a.gid) as gid, '' as statefp, cast(a.lm_code as integer) as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM ers10 as a where " .$filter. ";";
		break;
		case "ERS10Rep":
			$sql = "SELECT concat('srchERS10Rep',a.gid) as gid, '' as statefp, cast(a.lm_code as integer) as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM ers10rep as a where " .$filter. ";";
		break;
		case "MSAs_Grainger":
			$sql = "SELECT concat('srchMSAs_Grainger',a.gid) as gid, '' as statefp, a.name as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM msas_grainger as a where " .$filter. ";";
		break;
		case "OpportunityZones":
			$sql = "SELECT concat('srchOpportunityZones',a.gid) as gid, '' as statefp, countyname as b2_name, '' as stusps, a.colorid, a.geoid10 as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM opportunityzones as a where " .$filter. ";";
		break;
		case "PEA10":
			$sql = "SELECT concat('srchPEA10',a.gid) as gid, '' as statefp, cast(a.lm_code as integer) as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM pea10 as a where " .$filter. ";";
		break;
		case "TP10":
			$sql = "SELECT concat('srchTP10',a.gid) as gid, '' as statefp, cast(a.lm_code as integer) as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM tp10 as a where " .$filter. ";";
		break;
		case "TP10METRO":
			$sql = "SELECT concat('srchTP10METRO',a.gid) as gid, '' as statefp, cast(a.lm_code as integer) as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM tp10metro as a where " .$filter. ";";
		break;
		case "TP10MICRO":
			$sql = "SELECT concat('srchTP10MICRO',a.gid) as gid, '' as statefp, cast(a.lm_code as integer) as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM tp10micro as a where " .$filter. ";";
		break;
		case "TribalLand":
			$sql = "SELECT concat('srchTribalLand',a.gid) as gid, '' as statefp, a.name as b2_name, '' as stusps, a.colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM triballand as a where " .$filter. ";";
		break;
		case "Schools_CCD":
			$sql = "SELECT concat('srchSchools_CCD',a.gid) as gid, a.statefp, nces_distr AS b2_name, a.state as stusps, a.colorid, a.nces_distr as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM schools_ccd as a where " .$filter. ";";
		break;
		case "ConsumerMarket":
			$sql = "SELECT concat('srchConsumerMarket',a.gid) as gid, a.stateid as statefp, a.name as b2_name, a.state as stusps, a.colorid, a.geoid, ST_AsGeoJSON(a.geom) AS geojson FROM ConsumerMarket as a where " .$filter. ";";
		break;
		case "HSA":
			$sql = "SELECT concat('srchHSA',a.gid) as gid, a.statefp, a.name as b2_name, '' as stusps, a.colorid, a.hsa93 as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM hsa as a where " .$filter. ";";
		break;
		case "HRR":
			$sql = "SELECT concat('srchHRR',a.gid) as gid, a.statefp, a.name as b2_name, '' as stusps, a.colorid, a.hrrnum as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM hrr as a where " .$filter. ";";
		break;
		case "WaterShedRegions":
			$sql = "SELECT concat('srchHUC12',a.gid) as gid, '' as statefp, concat('HUC- ', a.huc) as b2_name, a.states as stusps, a.colorid, a.huc as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM regions as a where " .$filter. ";";
		break;
		case "SubBasin":
			$sql = "SELECT concat('srchHUC12',a.gid) as gid, '' as statefp, concat('HUC- ', a.huc) as b2_name, a.states as stusps, a.colorid, a.huc as geoid, ST_AsGeoJSON(a.geomsimple) AS geojson FROM subbasin as a where " .$filter. ";";

		break;
		case "SubWatershed":
			$sql = "SELECT concat('srchHUC12',a.gid) as gid, a.statefp, concat('HUC- ', a.huc) as b2_name, a.states as stusps, a.colorid, a.huc as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM subwatershed as a where " .$filter. ";";
		break;
		case "STR_Geocoded_All_New":
			$sql = "SELECT concat('srchSTR',a.gid) as gid, '' as statefp, concat(a.range_new, a.range_dir, ', ', a.township_new, a.township_dir) as b2_name, a.state as stusps, '' as colorid, '' as geoid, ST_AsGeoJSON(a.geom) AS geojson FROM str_geocoded_all_new as a where " .$filter. ";";
		break;
		default:
	}
	$geojson = array(
				'type'      => 'FeatureCollection',
				'features'  => array()
			);
	$query = pg_query($dbcon,$sql);
	while($edge=pg_fetch_assoc($query)) {
		$feature = array
					(
					'type' => 'Feature',
					// 'id' => $edge['gid'],
					'geometry' => json_decode($edge['geojson'], true),
					 'crs' => array(
						'type' => 'EPSG',
						'properties' => array('code' => '3857')
					),
					'geometry_name' => 'geom',
					'properties' => array
					(
						'gid'  => $edge['gid'],
						'statefp' => $edge['statefp'],
						'b2_name' => $edge['b2_name'],
						'stusps' => $edge['stusps'],
						'colorid' => $edge['colorid'],
						'geoid' => $edge['geoid']
					)
				);
		array_push($geojson['features'], $feature);
	}

	pg_close($dbcon);
	header('Content-type: application/json',true);
	echo json_encode($geojson);
?>
