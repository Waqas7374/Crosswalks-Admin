<?php
	require_once("config.php");
	$table = $_GET['table'];
	$boundary1 = $_GET['boundary1'];
	$boundary2 = $_GET['boundary2'];
	$value = $_GET['value'];

	$sql = '';
	switch ($table) {
		case "cbsa_zip":
			$sql = "Select b.gid, a.zip as name, a.res_ratio, a.bus_ratio, a.oth_ratio, a.tot_ratio, ST_AsGeoJSON(b.geom) AS geojson from cbsa_zip as a, zip as b where cbsa = '" .$value. "' and a.zip = b.name;";
			// echo $sql;
		break;
		case "zip_cbsa":
			$sql = "Select b.gid, a.cbsa as name, a.res_ratio, a.bus_ratio, a.oth_ratio, a.tot_ratio, ST_AsGeoJSON(b.geom) AS geojson from zip_cbsa as a, cbsa10 as b where zip = '" .$value. "' and cast (a.cbsa as integer ) = cast( b.lm_code as integer );";
    break;
		case "counties_zip":
			$sql = "Select b.gid, a.zip as name, a.res_ratio, a.bus_ratio, a.oth_ratio, a.tot_ratio, ST_AsGeoJSON(b.geom) AS geojson from counties_zip as a, zip as b where county = '" .$value. "' and cast (a.zip as integer ) = cast( b.name as integer );";
    break;
		case "zip_counties":
			$sql = "Select b.gid, a.county as name, a.res_ratio, a.bus_ratio, a.oth_ratio, a.tot_ratio, ST_AsGeoJSON(b.geom) AS geojson from zip_counties as a, counties as b where zip = '" .$value. "' and cast (a.county as integer ) = cast( b.geoid as integer );";
    break;
		case "tracts_zip":
			$sql = "Select b.gid, a.zip as name, a.res_ratio, a.bus_ratio, a.oth_ratio, a.tot_ratio, ST_AsGeoJSON(b.geom) AS geojson from tracts_zip as a, zip as b where tract = '" .$value. "' and cast (a.zip as integer ) = cast( b.name as integer );";
    break;
		case "zip_tracts":
			$sql = "Select b.gid, a.tract as name, a.res_ratio, a.bus_ratio, a.oth_ratio, a.tot_ratio, ST_AsGeoJSON(b.geom) AS geojson from zip_tracts as a, tracts as b where zip = '" .$value. "' and a.tract = b.geoid;";
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
						'name' => $edge['name'],
						'res_ratio'  => $edge['res_ratio'],
						'bus_ratio' => $edge['bus_ratio'],
						'tot_ratio' => $edge['tot_ratio']
					)
				);
		array_push($geojson['features'], $feature);
	}

	pg_close($dbcon);
	header('Content-type: application/json',true);
	echo json_encode($geojson);
?>
