<?php
	require_once("config.php");
	$filter = $_GET['filter'];
	$boundary1 = $_GET['boundary1'];
	$boundary2 = $_GET['boundary2'];
	$b1_id = $_GET['b1_id'];
	$b1_name = $_GET['b1_name'];
	$sql = '';
	if($boundary1 == 'WaterShedRegions')
		$boundary1 = 'regions';
	else if($boundary1 == 'WaterShedSubRegions')
		$boundary1 = 'subbasin';
	switch ($boundary2) {
		case "Zip"://boundary1 as b1, zip as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.name as b2_id, concat('Zip ',b2.gid) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, s.stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, zip as b2, states as s where st_intersects(b1.geom,b2.geom) and " .$filter. " and s.statefp = b2.statefp10 and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "Tracts"://boundary1 as b1, tracts as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.geoid as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, s.stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, tracts as b2, states as s where st_intersects(b1.geom,b2.geom) and " .$filter. " and  s.statefp = b2.statefp and (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1;";
		break;
		case "Counties"://boundary1 as b1, counties as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.geoid as b2_id, b2.name as b2_name, s.stusps, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, counties as b2, states as s where st_intersects(b1.geom,b2.geom) and (".$filter.") and  s.statefp = b2.statefp and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "Region"://boundary1 as b1, region as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, concat('region.', b1.gid) as b2_id, b2.name as b2_name, '' as stusps, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, region as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "Districts"://boundary1 as b1, districts as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.geoid as b2_id, b2.name as b2_name, s.stusps, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, districts as b2, states as s where st_intersects(b1.geom,b2.geom) and  (".$filter.") and s.statefp = b2.statefp and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "Cities"://boundary1 3as b1, cities as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.geoid as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, s.stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, cities as b2, states as s where st_intersects(b1.geom,b2.geom) and  (".$filter.") and s.statefp = b2.statefp and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "MSAs"://boundary1 as b1, msas as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.geoid as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, msas as b2 where  st_intersects(b1.geom,b2.geom) and (".$filter.") and (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1;";
		break;
		case "NeighbourCities"://boundary1 as b1, neighbourcities as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, concat('neighborhood.', b2.gid) as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, b2.state as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, neighbourcities as b2 where  st_intersects(b1.geom,b2.geom) and (".$filter.") and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "LauCnty": //boundary1 as b1, laucnty as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.laus_code as b2_id, b2.county_name as b2_name, s.stusps, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, laucnty as b2, states as s where st_intersects(b1.geom,b2.geom) and (".$filter.") and  s.statefp = b2.statefp and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "Food_Report": //boundary1 as b1, food_report as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.code as b2_id, concat(b2.name,'-',b2.state) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, b2.state as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, food_report as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "BEA10": //boundary1 as b1, bea10 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, cast(b2.lm_code as integer) as b2_id, concat('BEA ',cast(b2.lm_code as integer)) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, bea10 as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
			// echo $sql;
		break;
		case "CBSA10": //boundary1 as b1, cbsa10 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, cast(b2.lm_code as integer) as b2_id, concat('CBSA ',cast(b2.lm_code as integer)) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, cbsa10 as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "ERS10": //boundary1 as b1, ERS10 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, cast(b2.lm_code as integer) as b2_id, concat('ERS10 ',cast(b2.lm_code as integer)) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, ERS10 as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "ERS10Rep": //boundary1 as b1, ERS10REP as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, cast(b2.lm_code as integer) as b2_id, concat('ERS10Rep ',cast(b2.lm_code as integer)) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, ERS10REP as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "MSAs_Grainger": //boundary1 as b1, msas_grainger as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.gid as b2_id, '' as stusps, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, msas_grainger as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "OpportunityZones": //boundary1 as b1, opportunityzones as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.geoid10 as b2_id, concat(b2.countyname,'-',b2.statename) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, b2.statename as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, opportunityzones as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "PEA10": //boundary1 as b1, PEA10 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, cast(b2.lm_code as integer) as b2_id, concat('PEA10 ',cast(b2.lm_code as integer)) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, PEA10 as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "TP10": //boundary1 as b1, TP10 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, cast(b2.lm_code as integer) as b2_id, concat('TP10 ',cast(b2.lm_code as integer)) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, TP10 as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "TP10METRO": //boundary1 as b1, TP10METRO as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, cast(b2.lm_code as integer) as b2_id, concat('TPMetro ',cast(b2.lm_code as integer)) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, TP10METRO as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "TP10MICRO": //boundary1 as b1, TP10MICRO as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, cast(b2.lm_code as integer) as b2_id, concat('TPMicro ',cast(b2.lm_code as integer)) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, TP10MICRO as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "TribalLand": //boundary1 as b1, TribalLand as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.gid as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, TribalLand as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "Schools_CCD": //boundary1 as b1, Schools_CCD as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.nces_distr as b2_id, coalesce(b2.name,'School Districts') as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, schools_ccd as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "ConsumerMarket": //boundary1 as b1, ConsumerMarket as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.geoid as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, '' as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, consumermarket as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "HRR": //boundary1 as b1, HSA as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.hrrnum as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, s.stusps as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, hrr as b2, states as s where s.statefp = b2.statefp  and st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "HSA": //boundary1 as b1, HSA as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.hsa93 as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, s.stusps as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, HSA as b2, states as s where s.statefp = b2.statefp  and st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "WaterShedRegions": //boundary1 as b1, HUC2 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, concat('HUC-', b2.huc) as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, b2.states as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, regions as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "SubBasin": //boundary1 as b1, HUC8 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, concat('HUC-', b2.huc) as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geomsimple))/st_area(b2.geomsimple)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geomsimple))/st_area(b1.geom)*100) as area_covered, b2.states as stusps, ST_AsGeoJSON(b2.geomsimple) AS geojson from ". $boundary1 ." as b1, subbasin as b2 where st_intersects(b1.geom,b2.geomsimple) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geomsimple) THEN  (st_area(st_intersection(b1.geom, b2.geomsimple))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geomsimple))/st_area(b2.geomsimple)*100)>1 END;";
		break;
		case "SubWatershed": //boundary1 as b1, HUC12 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.huc as b2_id, b2.name as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, b2.states as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, subwatershed as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		case "STR_Geocoded_All_New": //boundary1 as b1, HUC12 as b2
			$sql = "Select distinct(b2.gid,b1.gid) as gid, " .$b1_id. " as b1_id, " .$b1_name. " as b1_name, b2.id_1 as b2_id, concat(range_new, range_dir, ', ', township_new, township_dir) as b2_name, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100) as perc_intersection, (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100) as area_covered, b2.state as stusps, ST_AsGeoJSON(b2.geom) AS geojson from ". $boundary1 ." as b1, str_geocoded_all_new as b2 where st_intersects(b1.geom,b2.geom) and ". $filter . " and CASE WHEN st_area(b1.geom) < st_area(b2.geom) THEN  (st_area(st_intersection(b1.geom, b2.geom))/st_area(b1.geom)*100)>1 ELSE (st_area(st_intersection(b1.geom, b2.geom))/st_area(b2.geom)*100)>1 END;";
		break;
		default:
	}
	$geojson = array(
				'type'      => 'FeatureCollection',
				'features'  => array()
			);
	$query = pg_query($dbcon,$sql);
	while($edge=pg_fetch_assoc($query)) {
		$feature = array (
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
						'b1_id' => $edge['b1_id'],
						'b1_name'  => $edge['b1_name'],
						'b2_id' => $edge['b2_id'],
						'b2_name' => $edge['b2_name'],
						'stusps' => $edge['stusps'],
						'area_covered'  => $edge['area_covered'],
						'perc_intersection'  => $edge['perc_intersection']
					)
				);
		array_push($geojson['features'], $feature);
	}

	pg_close($dbcon);
	header('Content-type: application/json',true);
	echo json_encode($geojson);
?>
