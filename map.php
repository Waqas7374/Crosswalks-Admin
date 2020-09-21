<?php
?>
<!DOCTYPE html>
<html class="ls-top-navbar show-sidebar sidebar-l2" lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Crosswalks</title>

  <link rel="stylesheet" type="text/css" href="css/libs/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="css/libs/bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="css/libs/bootstrap-select.min.css" />
  <link rel="stylesheet" type="text/css" href="css/libs/ol.css"/>
  <link rel="stylesheet" type="text/css" href="css/libs/jquery-ui.css"/>
  <link rel="stylesheet" type="text/css" href="css/libs/material-design-iconic-font.css"/>
  <link rel="stylesheet" type="text/css" href="css/libs/jquery.dataTables.min.css"/>
  <link rel="stylesheet" type="text/css" href="css/libs/toastr.css"/>
  <link rel="stylesheet" type="text/css" href="css/custom/style.css"/>
  <link rel="stylesheet" type="text/css" href="css/custom/styleRatios.css"/>

  <link rel="shortcut icon" href="#" />

  <script type="text/javascript" src="js/libs/ol.js"></script>
  <script type="text/javascript" src="js/libs/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="js/libs/jquery-ui.js"></script>
  <!-- <script type="text/javascript" src="js/libs/jquery.multiselect.js"></script> -->
  <script type="text/javascript" src="js/libs/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/libs/bootstrap-select.min.js"></script>
  <script type="text/javascript" src="js/libs/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="js/libs/toastr.js"></script>
  <script type="text/javascript" src="js/libs/rbush.min.js"></script>
  <script type="text/javascript" src="js/libs/labelgun.min.js"></script>


</head>
<body onload="init();">
	<div id="map" class="map" style="width: 100%; height: 100%; position:fixed">
		<div class="progress">
			<div id="js-progress-bar" class="progress-bar"></div>
		</div>
		<div class="table-loader"><img src="css/images/loader.gif" alt="Be patient..." /></div>
		<div class="container" style="position: absolute; z-index: 500000; left: 3%;">
			<div class="row">
				<div class="form-group">
					<div class="col-sm-7 col-md-7">
						<div class="input-group">
							<div id="radioBtn" class="btn-group">
								<a class="btn btn-primary btn-sm active" data-toggle="main" data-title="Crosswalk" style="font-size: 15px;"><b>Crosswalks</b></a>
								<a class="btn btn-primary btn-sm notActive" data-toggle="main" data-title="Change" style="font-size: 15px;"><b>Monitor Changes over Years</b></a>
								<a class="btn btn-primary btn-sm notActive" data-toggle="main" data-title="Ratio" style="font-size: 15px;"><b>Crosswalk Ratios</b></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="divCrosswalk">
			<div class="divMapOptions">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-top: -4px;">Select a base layer: </p>
				<select id="selBaseMap" class="selectpicker form-control selectGroup show-tick" onchange="changeBaseMap();">
					<option value="osm">OSM</option>
					<option value="aerial">Aerial/Satellite</option>
					<option value="terrain")>Terrain</option>
					<option value="streets")>Streets</option>
				</select>
			</div>
			<div class="divSearchOptions">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-top: -4px;">Search for: </p>
				<select id="selCode" class="selectpicker form-control selectGroup show-tick">
					<optgroup label="Political / Gov. Boundaries">
						<option value="State">States</option>
						<option value="Counties">Counties</option>
						<option value="Cities">Cities / Townships</option>
						<option value="Districts">Political Districts</option>
						<option value="TribalLand">Tribal Nation Lands</option>
					</optgroup>
					<optgroup label="Economic Boundaries">
						<option value="Region">US Census Regions</option>
						<option value="Tracts">US Census Tracts</option>
						<option value="Food_Report">BLS Regions</option>
						<option value="MSAs">MSAs</option>
						<option value="BEA10">BEA10</option>
						<option value="CBSA10">CBSA10</option>
						<option value="PEA10">PEA10</option>
						<option value="TP10">TP10</option>
						<option value="TP10METRO">TP10METRO</option>
						<option value="TP10MICRO">TP10MICRO</option>
						<option value="LauCnty">LAUS</option>
					</optgroup>
					<optgroup label="Administrative Boundaries">
					<option value="Schools_CCD">School Districts</option>
						<option value="Zip" selected>Zip Codes</option>
						<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
							<option value="HSA">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HSA</option>
							<option value="HRR">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HRR</option>
						</optgroup>

					</optgroup>
					<optgroup label="Social & Other Boundaries">
						<option value="NeighbourCities">Neighborhoods</option>
						<option value="ConsumerMarket">Consumer Markets</option>
						<option value="OpportunityZones">Econ. Opportunity Zones</option>
					</optgroup>
          <optgroup label="Watersheds">
            <option value="SubBasin">Sub Basin Boundaries - HUC8</option><!--SubBasin-->
            <option value="SubWatershed">Sub Watershed Boundaries - HUC12</option><!--subwatershed-->
          </optgroup>
          <optgroup label="PLSS">
            <option value="STR_Geocoded_All_New">STR</option><!--SubBasin-->
          </optgroup>
					<option value="ERS10">ERS10</option>
					<option value="ERS10Rep">ERS10REP</option>
				</select>
			</div>

			<!-- search Zips -->
			<div class="divSearch srchZip" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search ZIP Code" class="searchBar" id="searchBarZip" autocomplete="off" name="search" onkeypress="return isNumber(event)"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchZip" onclick="btnSearch('Zip','name')"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchSchools_CCD" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search School Districts Code" class="searchBar" id="searchBarSchools_CCD" autocomplete="off" name="search" onkeypress="return isNumber(event)"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchSchools_CCD" onclick="btnSearch('Schools_CCD','nces_distr')"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchConsumerMarket" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Consumer Market Code" class="searchBar" id="searchBarConsumerMarket" autocomplete="off" name="search" onkeypress="return isNumber(event)"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchConsumerMarket" onclick="btnSearch('ConsumerMarket','geoid')"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchHSA" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search HSA Code" class="searchBar" id="searchBarHSA" autocomplete="off" name="search" onkeypress="return isNumber(event)"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchHSA" onclick="btnSearch('HSA','geoid')"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchHRR" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search HRR Code" class="searchBar" id="searchBarHRR" autocomplete="off" name="search" onkeypress="return isNumber(event)"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchHRR" onclick="btnSearch('HRR','geoid')"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchTracts" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Tract" class="searchBar" id="searchBarTracts" autocomplete="off" name="search" onkeypress="return isNumber(event)"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchTracts" onclick="btnSearch('Tracts','geoid');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchCounties" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="e.g: Cook,IL OR 031-17" class="searchBar" id="searchBarCounties" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchCounties" onclick="btnSearch('Counties','name');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchRegion" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Region" class="searchBar" id="searchBarRegion" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchRegion"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchState" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search State" class="searchBar" id="searchBarState" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchState"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchDistricts" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search District" class="searchBar" id="searchBarDistricts" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchDistricts" onclick="btnSearch('Districts','geoid');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchCities" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Cities" class="searchBar" id="searchBarCities" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchCities" onclick="btnSearch('Cities','geoid');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchMSAs" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search MSA" class="searchBar" id="searchBarMSAs" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchMSAs" onclick="btnSearch('MSAs','geoid');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchNeighbourCities" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Neighborhood" class="searchBar" id="searchBarNeighbourCities" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchNeighbourCities" onclick="btnSearch('NeighbourCities','name');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchLauCnty" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="LAUS Code" class="searchBar" id="searchBarLauCnty" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchLauCnty" onclick="btnSearch('LauCnty','laus_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchFood_Report" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="BLS Code" class="searchBar" id="searchBarFood_Report" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchFood_Report" onclick="btnSearch('Food_Report','code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchBEA10" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search BEA Boundaries" class="searchBar" id="searchBarBEA10" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchBEA10" onclick="btnSearch('BEA10','lm_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchCBSA10" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search CBSA Boundaries" class="searchBar" id="searchBarCBSA10" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchCBSA10" onclick="btnSearch('CBSA10','lm_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchERS10" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search ERS Boundaries" class="searchBar" id="searchBarERS10" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchERS10" onclick="btnSearch('ERS10','lm_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchERS10Rep" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search ERS Boundaries" class="searchBar" id="searchBarERS10Rep" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchERS10Rep" onclick="btnSearch('ERS10Rep','lm_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchOpportunityZones" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Opportunity Zones" class="searchBar" id="searchBarOpportunityZones" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchOpportunityZones" onclick="btnSearch('OpportunityZones','geoid10');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchWaterShedRegions" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Watershed Regions" class="searchBar" id="searchBarWaterShedRegions" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchWaterShedRegions" onclick="btnSearch('WaterShedRegions','geoid10');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchSubBasin" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Watershed Sub Regions" class="searchBar" id="searchBarSubBasin" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchSubBasin" onclick="btnSearch('SubBasin','geoid10');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchSubWatershed" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Sub Watershed" class="searchBar" id="searchBarSubWatershed" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchSubWatershed" onclick="btnSearch('SubWatershed','geoid10');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchSTR_Geocoded_All_New" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search STR_Geocoded_All_New" class="searchBar" id="searchBarSTR_Geocoded_All_New" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchSTR_Geocoded_All_New" onclick="btnSearch('STR_Geocoded_All_New','gid');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchPEA10" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search CBSA Boundaries" class="searchBar" id="searchBarPEA10" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchPEA10" onclick="btnSearch('PEA10','lm_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchTP10" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search TP10 Boundaries" class="searchBar" id="searchBarTP10" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchTP10" onclick="btnSearch('TP10','lm_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchTP10METRO" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search TP10 Metro" class="searchBar" id="searchBarTP10METRO" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchTP10METRO" onclick="btnSearch('TP10METRO','lm_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchTP10MICRO" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search TP10 Micro" class="searchBar" id="searchBarTP10MICRO" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchTP10MICRO" onclick="btnSearch('TP10MICRO','lm_code');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
			<div class="divSearch srchTribalLand" style="width:269px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Tribal Land" class="searchBar" id="searchBarTribalLand" autocomplete="off" name="search"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchTribalLand" onclick="btnSearch('TribalLand','gid');"><i class="fa fa-arrow-circle-right"></i></button>
			</div>

			<!-- div Go-->
			<div class="divSearch divGoZip" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoZip" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="ZipCrStates" disabled>Crosswalk with States</option>
							<option value="ZipCrCounties">Crosswalk with Counties</option>
							<option value="ZipCrCities">Crosswalk with Cities / Townships</option>
							<option value="ZipCrDistricts">Crosswalk with Political Districts</option>
							<option value="ZipCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="ZipCrRegion">Crosswalk with US Census Regions</option>
							<option value="ZipCrTracts">Crosswalk with US Census Tracts</option>
							<option value="ZipCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="ZipCrMSAs">Crosswalk with MSAs</option>
							<option value="ZipCrBEA10">Crosswalk with BEA10</option>
							<option value="ZipCrCBSA10">Crosswalk with CBSA10</option>
							<option value="ZipCrPEA10">Crosswalk with PEA10</option>
							<option value="ZipCrTP10">Crosswalk with TP10</option>
							<option value="ZipCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="ZipCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="ZipCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="ZipCrSchools_CCD">Crosswalk with School Districts</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="ZipCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="ZipCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="ZipCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="ZipCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="ZipCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
            <optgroup label="Watersheds">
              <option value="ZipCrSubBasin">Crosswalk with Sub Basin Boundaries - HUC8</option><!--SubBasin-->
              <option value="ZipCrSubWatershed">Crosswalk with Sub Watershed Boundaries - HUC12</option><!--subwatershed-->
            </optgroup>
            <optgroup label="PLSS">
              <option value="ZipCrSTR_Geocoded_All_New">Crosswalk with STR</option>
            </optgroup>
						<option value="ZipCrERS10">Crosswalk with ERS10</option>
						<option value="ZipCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoZip" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoSchools_CCD" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoSchools_CCD" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="Schools_CCDCrStates" disabled>Crosswalk with States</option>
							<option value="Schools_CCDCrCounties">Crosswalk with Counties</option>
							<option value="Schools_CCDCrCities">Crosswalk with Cities / Townships</option>
							<option value="Schools_CCDCrDistricts">Crosswalk with Political Districts</option>
							<option value="Schools_CCDCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="Schools_CCDCrRegion">Crosswalk with US Census Regions</option>
							<option value="Schools_CCDCrTracts">Crosswalk with US Census Tracts</option>
							<option value="Schools_CCDCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="Schools_CCDCrMSAs">Crosswalk with MSAs</option>
							<option value="Schools_CCDCrBEA10">Crosswalk with BEA10</option>
							<option value="Schools_CCDCrCBSA10">Crosswalk with CBSA10</option>
							<option value="Schools_CCDCrPEA10">Crosswalk with PEA10</option>
							<option value="Schools_CCDCrTP10">Crosswalk with TP10</option>
							<option value="Schools_CCDCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="Schools_CCDCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="Schools_CCDCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="Schools_CCDCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="Schools_CCDCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="Schools_CCDCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="Schools_CCDCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="Schools_CCDCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="Schools_CCDCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="Schools_CCDCrERS10">Crosswalk with ERS10</option>
						<option value="Schools_CCDCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoSchools_CCD" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoConsumerMarket" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoConsumerMarket" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="ConsumerMarketCrStates" disabled>Crosswalk with States</option>
							<option value="ConsumerMarketCrCounties">Crosswalk with Counties</option>
							<option value="ConsumerMarketCrCities">Crosswalk with Cities / Townships</option>
							<option value="ConsumerMarketCrDistricts">Crosswalk with Political Districts</option>
							<option value="ConsumerMarketCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="ConsumerMarketCrRegion">Crosswalk with US Census Regions</option>
							<option value="ConsumerMarketCrTracts">Crosswalk with US Census Tracts</option>
							<option value="ConsumerMarketCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="ConsumerMarketCrMSAs">Crosswalk with MSAs</option>
							<option value="ConsumerMarketCrBEA10">Crosswalk with BEA10</option>
							<option value="ConsumerMarketCrCBSA10">Crosswalk with CBSA10</option>
							<option value="ConsumerMarketCrPEA10">Crosswalk with PEA10</option>
							<option value="ConsumerMarketCrTP10">Crosswalk with TP10</option>
							<option value="ConsumerMarketCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="ConsumerMarketCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="ConsumerMarketCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="ConsumerMarketCrZip">Crosswalk with Zip Codes</option>
						<option value="ConsumerMarketCrSchools_CCD">Crosswalk with School Districts</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="ConsumerMarketCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="ConsumerMarketCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="ConsumerMarketCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="ConsumerMarketCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="ConsumerMarketCrERS10">Crosswalk with ERS10</option>
						<option value="ConsumerMarketCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoConsumerMarket" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoHRR" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoHRR" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="HRRCrStates" disabled>Crosswalk with States</option>
							<option value="HRRCrCounties">Crosswalk with Counties</option>
							<option value="HRRCrCities">Crosswalk with Cities / Townships</option>
							<option value="HRRCrDistricts">Crosswalk with Political Districts</option>
							<option value="HRRCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="HRRCrRegion">Crosswalk with US Census Regions</option>
							<option value="HRRCrTracts">Crosswalk with US Census Tracts</option>
							<option value="HRRCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="HRRCrMSAs">Crosswalk with MSAs</option>
							<option value="HRRCrBEA10">Crosswalk with BEA10</option>
							<option value="HRRCrCBSA10">Crosswalk with CBSA10</option>
							<option value="HRRCrPEA10">Crosswalk with PEA10</option>
							<option value="HRRCrTP10">Crosswalk with TP10</option>
							<option value="HRRCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="HRRCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="HRRCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="HRRCrZip">Crosswalk with Zip Codes</option>
						<option value="HRRCrSchools_CCD">Crosswalk with School Districts</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="HRRCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="HRRCrNeighbourCities">Crosswalk with Neighborhoods</option>
              <option value="HRRCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="HRRCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="HRRCrERS10">Crosswalk with ERS10</option>
						<option value="HRRCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoHRR" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoHSA" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoHSA" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="HSACrStates" disabled>Crosswalk with States</option>
							<option value="HSACrCounties">Crosswalk with Counties</option>
							<option value="HSACrCities">Crosswalk with Cities / Townships</option>
							<option value="HSACrDistricts">Crosswalk with Political Districts</option>
							<option value="HSACrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="HSACrRegion">Crosswalk with US Census Regions</option>
							<option value="HSACrTracts">Crosswalk with US Census Tracts</option>
							<option value="HSACrFood_Report">Crosswalk with BLS Regions</option>
							<option value="HSACrMSAs">Crosswalk with MSAs</option>
							<option value="HSACrBEA10">Crosswalk with BEA10</option>
							<option value="HSACrCBSA10">Crosswalk with CBSA10</option>
							<option value="HSACrPEA10">Crosswalk with PEA10</option>
							<option value="HSACrTP10">Crosswalk with TP10</option>
							<option value="HSACrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="HSACrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="HSACrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="HSACrZip">Crosswalk with Zip Codes</option>
						<option value="HSACrSchools_CCD">Crosswalk with School Districts</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="HSACrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="HSACrNeighbourCities">Crosswalk with Neighborhoods</option>
              <option value="HSACrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="HSACrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="HSACrERS10">Crosswalk with ERS10</option>
						<option value="HSACrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoHSA" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoTracts" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoTracts" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="TractsCrStates" disabled>Crosswalk with States</option>
							<option value="TractsCrCounties">Crosswalk with Counties</option>
							<option value="TractsCrCities">Crosswalk with Cities / Townships</option>
							<option value="TractsCrDistricts">Crosswalk with Political Districts</option>
							<option value="TractsCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="TractsCrRegion">Crosswalk with US Census Regions</option>
							<option value="TractsCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="TractsCrMSAs">Crosswalk with MSAs</option>
							<option value="TractsCrBEA10">Crosswalk with BEA10</option>
							<option value="TractsCrCBSA10">Crosswalk with CBSA10</option>
							<option value="TractsCrPEA10">Crosswalk with PEA10</option>
							<option value="TractsCrTP10">Crosswalk with TP10</option>
							<option value="TractsCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="TractsCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="TractsCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="TractsCrSchools_CCD">Crosswalk with School Districts</option>
							<option value="TractsCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="TractsCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="TractsCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="TractsCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="TractsCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="TractsCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="TractsCrERS10">Crosswalk with ERS10</option>
						<option value="TractsCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoTracts" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoCounties" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoCounties" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="CountiesCrStates" disabled>Crosswalk with States</option>
							<option value="CountiesCrCities">Crosswalk with Cities / Townships</option>
							<option value="CountiesCrDistricts">Crosswalk with Political Districts</option>
							<option value="CountiesCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="CountiesCrRegion">Crosswalk with US Census Regions</option>
							<option value="CountiesCrTracts">Crosswalk with US Census Tracts</option>
							<option value="CountiesCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="CountiesCrMSAs">Crosswalk with MSAs</option>
							<option value="CountiesCrBEA10">Crosswalk with BEA10</option>
							<option value="CountiesCrCBSA10">Crosswalk with CBSA10</option>
							<option value="CountiesCrPEA10">Crosswalk with PEA10</option>
							<option value="CountiesCrTP10">Crosswalk with TP10</option>
							<option value="CountiesCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="CountiesCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="CountiesCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="CountiesCrSchools_CCD">School Districts</option>
							<option value="CountiesCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="CountiesCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="CountiesCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="CountiesCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="CountiesCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="CountiesCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
            <optgroup label="Watersheds">
              <!-- <option value="CountiesCrWaterShedRegions">Crosswalk with Watershed Region Boundaries - HUC2</option> -->
              <option value="CountiesCrSubBasin">Crosswalk with Sub Basin Boundaries - HUC8</option><!--SubBasin-->
              <option value="CountiesCrSubWatershed">Crosswalk with Sub Watershed Boundaries - HUC12</option><!--subwatershed-->
            </optgroup>
						<option value="CountiesCrERS10">Crosswalk with ERS10</option>
						<option value="CountiesCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoCounties" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoRegions" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoRegions" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<option value="rCrStates">Crosswalk with States</option>
						<option value="rCrCounties" disabled>Crosswalk with Counties</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoRegions" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoState" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoState" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<option value="sCrCounties">Crosswalk with Counties</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoState" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoDistricts" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoDistricts" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="DistrictsCrStates" disabled>Crosswalk with States</option>
							<option value="DistrictsCrCounties">Crosswalk with Counties</option>
							<option value="DistrictsCrCities">Crosswalk with Cities / Townships</option>
							<option value="DistrictsCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="DistrictsCrRegion">Crosswalk with US Census Regions</option>
							<option value="DistrictsCrTracts">Crosswalk with US Census Tracts</option>
							<option value="DistrictsCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="DistrictsCrMSAs">Crosswalk with MSAs</option>
							<option value="DistrictsCrBEA10">Crosswalk with BEA10</option>
							<option value="DistrictsCrCBSA10">Crosswalk with CBSA10</option>
							<option value="DistrictsCrPEA10">Crosswalk with PEA10</option>
							<option value="DistrictsCrTP10">Crosswalk with TP10</option>
							<option value="DistrictsCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="DistrictsCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="DistrictsCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="DistrictsCrSchools_CCD">School Districts</option>
							<option value="DistrictsCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="DistrictsCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="DistrictsCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="DistrictsCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="DistrictsCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="DistrictsCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="DistrictsCrERS10">Crosswalk with ERS10</option>
						<option value="DistrictsCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoDistricts" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoCities" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoCities" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="CitiesCrStates" disabled>Crosswalk with States</option>
							<option value="CitiesCrCounties">Crosswalk with Counties</option>
							<option value="CitiesCrDistricts">Crosswalk with Political Districts</option>
							<option value="CitiesCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="CitiesCrRegion">Crosswalk with US Census Regions</option>
							<option value="CitiesCrTracts">Crosswalk with US Census Tracts</option>
							<option value="CitiesCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="CitiesCrMSAs">Crosswalk with MSAs</option>
							<option value="CitiesCrBEA10">Crosswalk with BEA10</option>
							<option value="CitiesCrCBSA10">Crosswalk with CBSA10</option>
							<option value="CitiesCrPEA10">Crosswalk with PEA10</option>
							<option value="CitiesCrTP10">Crosswalk with TP10</option>
							<option value="CitiesCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="CitiesCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="CitiesCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="CitiesCrSchools_CCD">School Districts</option>
							<option value="CitiesCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="CitiesCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="CitiesCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="CitiesCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="CitiesCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="CitiesCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="CitiesCrERS10">Crosswalk with ERS10</option>
						<option value="CitiesCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoCities" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoMSAs" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoMSAs" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="MSAsCrStates" disabled>Crosswalk with States</option>
							<option value="MSAsCrCounties">Crosswalk with Counties</option>
							<option value="MSAsCrCities">Crosswalk with Cities / Townships</option>
							<option value="MSAsCrDistricts">Crosswalk with Political Districts</option>
							<option value="MSAsCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="MSAsCrRegion">Crosswalk with US Census Regions</option>
							<option value="MSAsCrTracts">Crosswalk with US Census Tracts</option>
							<option value="MSAsCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="MSAsCrBEA10">Crosswalk with BEA10</option>
							<option value="MSAsCrCBSA10">Crosswalk with CBSA10</option>
							<option value="MSAsCrPEA10">Crosswalk with PEA10</option>
							<option value="MSAsCrTP10">Crosswalk with TP10</option>
							<option value="MSAsCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="MSAsCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="MSAsCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="MSAsCrSchools_CCD">School Districts</option>
							<option value="MSAsCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="MSAsCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="MSAsCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="MSAsCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="MSAsCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="MSAsCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="MSAsCrERS10">Crosswalk with ERS10</option>
						<option value="MSAsCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoMSAs" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoNeighbourCities" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoNeighbourCities" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="NeighbourCitiesCrStates" disabled>Crosswalk with States</option>
							<option value="NeighbourCitiesCrCounties">Crosswalk with Counties</option>
							<option value="NeighbourCitiesCrCities">Crosswalk with Cities / Townships</option>
							<option value="NeighbourCitiesCrDistricts">Crosswalk with Political Districts</option>
							<option value="NeighbourCitiesCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="NeighbourCitiesCrRegion">Crosswalk with US Census Regions</option>
							<option value="NeighbourCitiesCrTracts">Crosswalk with US Census Tracts</option>
							<option value="NeighbourCitiesCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="NeighbourCitiesCrMSAs">Crosswalk with MSAs</option>
							<option value="NeighbourCitiesCrBEA10">Crosswalk with BEA10</option>
							<option value="NeighbourCitiesCrCBSA10">Crosswalk with CBSA10</option>
							<option value="NeighbourCitiesCrPEA10">Crosswalk with PEA10</option>
							<option value="NeighbourCitiesCrTP10">Crosswalk with TP10</option>
							<option value="NeighbourCitiesCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="NeighbourCitiesCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="NeighbourCitiesCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="NeighbourCitiesCrSchools_CCD">School Districts</option>
							<option value="NeighbourCitiesCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="NeighbourCitiesCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="NeighbourCitiesCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="NeighbourCitiesCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="NeighbourCitiesCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="NeighbourCitiesCrERS10">Crosswalk with ERS10</option>
						<option value="NeighbourCitiesCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoNeighbourCities" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoLauCnty" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoLauCnty" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="LauCntyCrStates" disabled>Crosswalk with States</option>
							<option value="LauCntyCrCounties">Crosswalk with Counties</option>
							<option value="LauCntyCrCities">Crosswalk with Cities / Townships</option>
							<option value="LauCntyCrDistricts">Crosswalk with Political Districts</option>
							<option value="LauCntyCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="LauCntyCrRegion">Crosswalk with US Census Regions</option>
							<option value="LauCntyCrTracts">Crosswalk with US Census Tracts</option>
							<option value="LauCntyCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="LauCntyCrMSAs">Crosswalk with MSAs</option>
							<option value="LauCntyCrBEA10">Crosswalk with BEA10</option>
							<option value="LauCntyCrCBSA10">Crosswalk with CBSA10</option>
							<option value="LauCntyCrPEA10">Crosswalk with PEA10</option>
							<option value="LauCntyCrTP10">Crosswalk with TP10</option>
							<option value="LauCntyCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="LauCntyCrTP10MICRO">Crosswalk with TP10MICRO</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="LauCntyCrSchools_CCD">School Districts</option>
							<option value="LauCntyCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="LauCntyCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="LauCntyCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="LauCntyCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="LauCntyCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="LauCntyCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="LauCntyCrERS10">Crosswalk with ERS10</option>
						<option value="LauCntyCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoLauCnty" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoFood_Report" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoFood_Report" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="Food_ReportCrStates" disabled>Crosswalk with States</option>
							<option value="Food_ReportCrCounties">Crosswalk with Counties</option>
							<option value="Food_ReportCrCities">Crosswalk with Cities / Townships</option>
							<option value="Food_ReportCrDistricts">Crosswalk with Political Districts</option>
							<option value="Food_ReportCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="Food_ReportCrRegion">Crosswalk with US Census Regions</option>
							<option value="Food_ReportCrTracts">Crosswalk with US Census Tracts</option>
							<option value="Food_ReportCrMSAs">Crosswalk with MSAs</option>
							<option value="Food_ReportCrBEA10">Crosswalk with BEA10</option>
							<option value="Food_ReportCrCBSA10">Crosswalk with CBSA10</option>
							<option value="Food_ReportCrPEA10">Crosswalk with PEA10</option>
							<option value="Food_ReportCrTP10">Crosswalk with TP10</option>
							<option value="Food_ReportCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="Food_ReportCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="Food_ReportCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="Food_ReportCrSchools_CCD">School Districts</option>
							<option value="Food_ReportCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="Food_ReportCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="Food_ReportCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>

						<optgroup label="Social & Other Boundaries">
							<option value="Food_ReportCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="Food_ReportCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="Food_ReportCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="Food_ReportCrERS10">Crosswalk with ERS10</option>
						<option value="Food_ReportCrERS10">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoFood_Report" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoBEA10" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoBEA10" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="BEA10CrStates" disabled>Crosswalk with States</option>
							<option value="BEA10CrCounties">Crosswalk with Counties</option>
							<option value="BEA10CrCities">Crosswalk with Cities / Townships</option>
							<option value="BEA10CrDistricts">Crosswalk with Political Districts</option>
							<option value="BEA10CrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="BEA10CrRegion">Crosswalk with US Census Regions</option>
							<option value="BEA10CrTracts">Crosswalk with US Census Tracts</option>
							<option value="BEA10CrFood_Report">Crosswalk with BLS Regions</option>
							<option value="BEA10CrMSAs">Crosswalk with MSAs</option>
							<option value="BEA10CrCBSA10">Crosswalk with CBSA10</option>
							<option value="BEA10CrPEA10">Crosswalk with PEA10</option>
							<option value="BEA10CrTP10">Crosswalk with TP10</option>
							<option value="BEA10CrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="BEA10CrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="BEA10CrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="BEA10CrSchools_CCD">School Districts</option>
							<option value="BEA10CrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="BEA10CrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="BEA10CrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="BEA10CrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="BEA10CrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="BEA10CrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="BEA10CrERS10">Crosswalk with ERS10</option>
						<option value="BEA10CrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoBEA10" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoCBSA10" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoCBSA10" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="CBSA10CrStates" disabled>Crosswalk with States</option>
							<option value="CBSA10CrCounties">Crosswalk with Counties</option>
							<option value="CBSA10CrCities">Crosswalk with Cities / Townships</option>
							<option value="CBSA10CrDistricts">Crosswalk with Political Districts</option>
							<option value="CBSA10CrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="CBSA10CrRegion">Crosswalk with US Census Regions</option>
							<option value="CBSA10CrTracts">Crosswalk with US Census Tracts</option>
							<option value="CBSA10CrFood_Report">Crosswalk with BLS Regions</option>
							<option value="CBSA10CrMSAs">Crosswalk with MSAs</option>
							<option value="CBSA10CrBEA10">Crosswalk with BEA10</option>
							<option value="CBSA10CrPEA10">Crosswalk with PEA10</option>
							<option value="CBSA10CrTP10">Crosswalk with TP10</option>
							<option value="CBSA10CrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="CBSA10CrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="CBSA10CrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="CBSA10CrSchools_CCD">School Districts</option>
							<option value="CBSA10CrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="CBSA10CrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="CBSA10CrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="CBSA10CrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="CBSA10CrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="CBSA10CrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="CBSA10CrERS10">Crosswalk with ERS10</option>
						<option value="CBSA10CrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoCBSA10" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoERS10" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoERS10" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="ERS10CrStates" disabled>Crosswalk with States</option>
							<option value="ERS10CrCounties">Crosswalk with Counties</option>
							<option value="ERS10CrCities">Crosswalk with Cities / Townships</option>
							<option value="ERS10CrDistricts">Crosswalk with Political Districts</option>
							<option value="ERS10CrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="ERS10CrRegion">Crosswalk with US Census Regions</option>
							<option value="ERS10CrTracts">Crosswalk with US Census Tracts</option>
							<option value="ERS10CrFood_Report">Crosswalk with BLS Regions</option>
							<option value="ERS10CrMSAs">Crosswalk with MSAs</option>
							<option value="ERS10CrBEA10">Crosswalk with BEA10</option>
							<option value="ERS10CrCBSA10">Crosswalk with CBSA10</option>
							<option value="ERS10CrPEA10">Crosswalk with PEA10</option>
							<option value="ERS10CrTP10">Crosswalk with TP10</option>
							<option value="ERS10CrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="ERS10CrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="ERS10CrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="ERS10CrSchools_CCD">School Districts</option>
							<option value="ERS10CrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="ERS10CrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="ERS10CrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="ERS10CrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="ERS10CrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="ERS10CrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="ERS10CrERS10Rep">Crosswalk with ERS10REP</option>

					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoERS10" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoERS10Rep" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoERS10Rep" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="ERS10RepCrStates" disabled>Crosswalk with States</option>
							<option value="ERS10RepCrCounties">Crosswalk with Counties</option>
							<option value="ERS10RepCrCities">Crosswalk with Cities / Townships</option>
							<option value="ERS10RepCrDistricts">Crosswalk with Political Districts</option>
							<option value="ERS10RepCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="ERS10RepCrRegion">Crosswalk with US Census Regions</option>
							<option value="ERS10RepCrTracts">Crosswalk with US Census Tracts</option>
							<option value="ERS10RepCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="ERS10RepCrMSAs">Crosswalk with MSAs</option>
							<option value="ERS10RepCrBEA10">Crosswalk with BEA10</option>
							<option value="ERS10RepCrCBSA10">Crosswalk with CBSA10</option>
							<option value="ERS10RepCrPEA10">Crosswalk with PEA10</option>
							<option value="ERS10RepCrTP10">Crosswalk with TP10</option>
							<option value="ERS10RepCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="ERS10RepCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="ERS10RepCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="ERS10RepCrSchools_CCD">School Districts</option>
							<option value="ERS10RepCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="ERS10RepCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="ERS10RepCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="ERS10RepCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="ERS10RepCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="ERS10RepCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="ERS10RepCrERS10">Crosswalk with ERS10</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoERS10Rep" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoOpportunityZones" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoOpportunityZones" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="OpportunityZonesCrStates" disabled>Crosswalk with States</option>
							<option value="OpportunityZonesCrCounties">Crosswalk with Counties</option>
							<option value="OpportunityZonesCrCities">Crosswalk with Cities / Townships</option>
							<option value="OpportunityZonesCrDistricts">Crosswalk with Political Districts</option>
							<option value="OpportunityZonesCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="OpportunityZonesCrRegion">Crosswalk with US Census Regions</option>
							<option value="OpportunityZonesCrTracts">Crosswalk with US Census Tracts</option>
							<option value="OpportunityZonesCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="OpportunityZonesCrMSAs">Crosswalk with MSAs</option>
							<option value="OpportunityZonesCrBEA10">Crosswalk with BEA10</option>
							<option value="OpportunityZonesCrCBSA10">Crosswalk with CBSA10</option>
							<option value="OpportunityZonesCrPEA10">Crosswalk with PEA10</option>
							<option value="OpportunityZonesCrTP10">Crosswalk with TP10</option>
							<option value="OpportunityZonesCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="OpportunityZonesCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="OpportunityZonesCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="OpportunityZonesCrSchools_CCD">School Districts</option>
							<option value="OpportunityZonesCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="OpportunityZonesCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="OpportunityZonesCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
              <option value="OpportunityZonesCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="OpportunityZonesCrNeighbourCities">Crosswalk with Neighborhoods</option>
						</optgroup>
						<option value="OpportunityZonesCrERS10">Crosswalk with ERS10</option>
						<option value="OpportunityZonesCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoOpportunityZones" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoWaterShedRegions" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoWaterShedRegions" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="WaterShedRegionsCrStates" disabled>Crosswalk with States</option>
							<option value="WaterShedRegionsCrCounties">Crosswalk with Counties</option>
							<option value="WaterShedRegionsCrCities" disabled>Crosswalk with Cities / Townships</option>
							<option value="WaterShedRegionsCrDistricts" disabled>Crosswalk with Political Districts</option>
							<option value="WaterShedRegionsCrTribalLand" disabled>Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="WaterShedRegionsCrRegion" disabled>Crosswalk with US Census Regions</option>
							<option value="WaterShedRegionsCrTracts" disabled>Crosswalk with US Census Tracts</option>
							<option value="WaterShedRegionsCrFood_Report" disabled>Crosswalk with BLS Regions</option>
							<option value="WaterShedRegionsCrMSAs" disabled>Crosswalk with MSAs</option>
							<option value="WaterShedRegionsCrBEA10" disabled>Crosswalk with BEA10</option>
							<option value="WaterShedRegionsCrCBSA10" disabled>Crosswalk with CBSA10</option>
							<option value="WaterShedRegionsCrPEA10" disabled>Crosswalk with PEA10</option>
							<option value="WaterShedRegionsCrTP10" disabled>Crosswalk with TP10</option>
							<option value="WaterShedRegionsCrTP10METRO" disabled>Crosswalk with TP10METRO</option>
							<option value="WaterShedRegionsCrTP10MICRO" disabled>Crosswalk with TP10MICRO</option>
							<option value="WaterShedRegionsCrLauCnty" disabled>Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="WaterShedRegionsCrSchools_CCD" disabled>School Districts</option>
							<option value="WaterShedRegionsCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="WaterShedRegionsCrHSA" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="WaterShedRegionsCrHRR" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="WaterShedRegionsCrNeighbourCities" disabled>Crosswalk with Neighborhoods</option>
						</optgroup>
						<option value="WaterShedRegionsCrERS10" disabled>Crosswalk with ERS10</option>
						<option value="WaterShedRegionsCrERS10Rep" disabled>Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoWaterShedRegions" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoSubBasin" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoSubBasin" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="SubBasinCrCounties">Crosswalk with Counties</option>
              <option value="SubBasinCrStates" disabled>Crosswalk with States</option>
							<option value="SubBasinCrCities" disabled>Crosswalk with Cities / Townships</option>
							<option value="SubBasinCrDistricts" disabled>Crosswalk with Political Districts</option>
							<option value="SubBasinCrTribalLand" disabled>Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="SubBasinCrRegion" disabled>Crosswalk with US Census Regions</option>
							<option value="SubBasinCrTracts" disabled>Crosswalk with US Census Tracts</option>
							<option value="SubBasinCrFood_Report" disabled>Crosswalk with BLS Regions</option>
							<option value="SubBasinCrMSAs" disabled>Crosswalk with MSAs</option>
							<option value="SubBasinCrBEA10" disabled>Crosswalk with BEA10</option>
							<option value="SubBasinCrCBSA10" disabled>Crosswalk with CBSA10</option>
							<option value="SubBasinCrPEA10" disabled>Crosswalk with PEA10</option>
							<option value="SubBasinCrTP10" disabled>Crosswalk with TP10</option>
							<option value="SubBasinCrTP10METRO" disabled>Crosswalk with TP10METRO</option>
							<option value="SubBasinCrTP10MICRO" disabled>Crosswalk with TP10MICRO</option>
							<option value="SubBasinCrLauCnty" disabled>Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="SubBasinCrSchools_CCD" disabled>School Districts</option>
							<option value="SubBasinCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="SubBasinCrHSA" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="SubBasinCrHRR" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="SubBasinCrNeighbourCities" disabled>Crosswalk with Neighborhoods</option>
						</optgroup>
						<option value="SubBasinCrERS10" disabled>Crosswalk with ERS10</option>
						<option value="SubBasinCrERS10Rep" disabled>Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoSubBasin" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoSubWatershed" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoSubWatershed" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="SubWatershedCrStates" disabled>Crosswalk with States</option>
							<option value="SubWatershedCrCounties">Crosswalk with Counties</option>
							<option value="SubWatershedCrCities" disabled>Crosswalk with Cities / Townships</option>
							<option value="SubWatershedCrDistricts" disabled>Crosswalk with Political Districts</option>
							<option value="SubWatershedCrTribalLand" disabled>Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="SubWatershedCrRegion" disabled>Crosswalk with US Census Regions</option>
							<option value="SubWatershedCrTracts" disabled>Crosswalk with US Census Tracts</option>
							<option value="SubWatershedCrFood_Report" disabled>Crosswalk with BLS Regions</option>
							<option value="SubWatershedCrMSAs" disabled>Crosswalk with MSAs</option>
							<option value="SubWatershedCrBEA10" disabled>Crosswalk with BEA10</option>
							<option value="SubWatershedCrCBSA10" disabled>Crosswalk with CBSA10</option>
							<option value="SubWatershedCrPEA10" disabled>Crosswalk with PEA10</option>
							<option value="SubWatershedCrTP10" disabled>Crosswalk with TP10</option>
							<option value="SubWatershedCrTP10METRO" disabled>Crosswalk with TP10METRO</option>
							<option value="SubWatershedCrTP10MICRO" disabled>Crosswalk with TP10MICRO</option>
							<option value="SubWatershedCrLauCnty" disabled>Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="SubWatershedCrSchools_CCD" disabled>School Districts</option>
							<option value="SubWatershedCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="SubWatershedCrHSA" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="SubWatershedCrHRR" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="SubWatershedCrNeighbourCities" disabled>Crosswalk with Neighborhoods</option>
						</optgroup>
						<option value="SubWatershedCrERS10" disabled>Crosswalk with ERS10</option>
						<option value="SubWatershedCrERS10Rep" disabled>Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoSubWatershed" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoSTR_Geocoded_All_New" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoSTR_Geocoded_All_New" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="STR_Geocoded_All_NewCrStates" disabled>Crosswalk with States</option>
							<option value="STR_Geocoded_All_NewCrCounties">Crosswalk with Counties</option>
							<option value="STR_Geocoded_All_NewCrCities" disabled>Crosswalk with Cities / Townships</option>
							<option value="STR_Geocoded_All_NewCrDistricts" disabled>Crosswalk with Political Districts</option>
							<option value="STR_Geocoded_All_NewCrTribalLand" disabled>Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="STR_Geocoded_All_NewCrRegion" disabled>Crosswalk with US Census Regions</option>
							<option value="STR_Geocoded_All_NewCrTracts" disabled>Crosswalk with US Census Tracts</option>
							<option value="STR_Geocoded_All_NewCrFood_Report" disabled>Crosswalk with BLS Regions</option>
							<option value="STR_Geocoded_All_NewCrMSAs" disabled>Crosswalk with MSAs</option>
							<option value="STR_Geocoded_All_NewCrBEA10" disabled>Crosswalk with BEA10</option>
							<option value="STR_Geocoded_All_NewCrCBSA10" disabled>Crosswalk with CBSA10</option>
							<option value="STR_Geocoded_All_NewCrPEA10" disabled>Crosswalk with PEA10</option>
							<option value="STR_Geocoded_All_NewCrTP10" disabled>Crosswalk with TP10</option>
							<option value="STR_Geocoded_All_NewCrTP10METRO" disabled>Crosswalk with TP10METRO</option>
							<option value="STR_Geocoded_All_NewCrTP10MICRO" disabled>Crosswalk with TP10MICRO</option>
							<option value="STR_Geocoded_All_NewCrLauCnty" disabled>Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="STR_Geocoded_All_NewCrSchools_CCD" disabled>School Districts</option>
							<option value="STR_Geocoded_All_NewCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="STR_Geocoded_All_NewCrHSA" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="STR_Geocoded_All_NewCrHRR" disabled>&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="STR_Geocoded_All_NewCrNeighbourCities" disabled>Crosswalk with Neighborhoods</option>
						</optgroup>
						<option value="STR_Geocoded_All_NewCrERS10" disabled>Crosswalk with ERS10</option>
						<option value="STR_Geocoded_All_NewCrERS10Rep" disabled>Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoSTR_Geocoded_All_New" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoPEA10" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoPEA10" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="PEA10CrStates" disabled>Crosswalk with States</option>
							<option value="PEA10CrCounties">Crosswalk with Counties</option>
							<option value="PEA10CrCities">Crosswalk with Cities / Townships</option>
							<option value="PEA10CrDistricts">Crosswalk with Political Districts</option>
							<option value="PEA10CrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="PEA10CrRegion">Crosswalk with US Census Regions</option>
							<option value="PEA10CrTracts">Crosswalk with US Census Tracts</option>
							<option value="PEA10CrFood_Report">Crosswalk with BLS Regions</option>
							<option value="PEA10CrMSAs">Crosswalk with MSAs</option>
							<option value="PEA10CrBEA10">Crosswalk with BEA10</option>
							<option value="PEA10CrCBSA10">Crosswalk with CBSA10</option>
							<option value="PEA10CrTP10">Crosswalk with TP10</option>
							<option value="PEA10CrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="PEA10CrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="PEA10CrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="PEA10CrSchools_CCD">School Districts</option>
							<option value="PEA10CrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="PEA10CrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="PEA10CrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="PEA10CrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="PEA10CrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="PEA10CrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="PEA10CrERS10">Crosswalk with ERS10</option>
						<option value="PEA10CrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoPEA10" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoTP10" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoTP10" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="TP10CrStates" disabled>Crosswalk with States</option>
							<option value="TP10CrCounties">Crosswalk with Counties</option>
							<option value="TP10CrCities">Crosswalk with Cities / Townships</option>
							<option value="TP10CrDistricts">Crosswalk with Political Districts</option>
							<option value="TP10CrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="TP10CrRegion">Crosswalk with US Census Regions</option>
							<option value="TP10CrTracts">Crosswalk with US Census Tracts</option>
							<option value="TP10CrFood_Report">Crosswalk with BLS Regions</option>
							<option value="TP10CrMSAs">Crosswalk with MSAs</option>
							<option value="TP10CrBEA10">Crosswalk with BEA10</option>
							<option value="TP10CrCBSA10">Crosswalk with CBSA10</option>
							<option value="TP10CrPEA10">Crosswalk with PEA10</option>
							<option value="TP10CrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="TP10CrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="TP10CrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="TP10CrSchools_CCD">School Districts</option>
							<option value="TP10CrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="TP10CrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="TP10CrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="TP10CrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="TP10CrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="TP10CrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="TP10CrERS10">Crosswalk with ERS10</option>
						<option value="TP10CrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoTP10" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoTP10METRO" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoTP10METRO" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="TP10METROCrStates" disabled>Crosswalk with States</option>
							<option value="TP10METROCrCounties">Crosswalk with Counties</option>
							<option value="TP10METROCrCities">Crosswalk with Cities / Townships</option>
							<option value="TP10METROCrDistricts">Crosswalk with Political Districts</option>
							<option value="TP10METROCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="TP10METROCrRegion">Crosswalk with US Census Regions</option>
							<option value="TP10METROCrTracts">Crosswalk with US Census Tracts</option>
							<option value="TP10METROCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="TP10METROCrMSAs">Crosswalk with MSAs</option>
							<option value="TP10METROCrBEA10">Crosswalk with BEA10</option>
							<option value="TP10METROCrCBSA10">Crosswalk with CBSA10</option>
							<option value="TP10METROCrPEA10">Crosswalk with PEA10</option>
							<option value="TP10METROCrTP10">Crosswalk with TP10</option>
							<option value="TP10METROCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="TP10METROCrSchools_CCD">School Districts</option>
							<option value="TP10METROCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="TP10METROCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="TP10METROCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="TP10METROCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="TP10METROCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="TP10METROCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="TP10METROCrERS10">Crosswalk with ERS10</option>
						<option value="TP10METROCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoTP10METRO" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoTP10MICRO" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoTP10MICRO" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="TP10MICROCrStates" disabled>Crosswalk with States</option>
							<option value="TP10MICROCrCounties">Crosswalk with Counties</option>
							<option value="TP10MICROCrCities">Crosswalk with Cities / Townships</option>
							<option value="TP10MICROCrDistricts">Crosswalk with Political Districts</option>
							<option value="TP10MICROCrTribalLand">Crosswalk with Tribal Nation Lands</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="TP10MICROCrRegion">Crosswalk with US Census Regions</option>
							<option value="TP10MICROCrTracts">Crosswalk with US Census Tracts</option>
							<option value="TP10MICROCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="TP10MICROCrMSAs">Crosswalk with MSAs</option>
							<option value="TP10MICROCrBEA10">Crosswalk with BEA10</option>
							<option value="TP10MICROCrCBSA10">Crosswalk with CBSA10</option>
							<option value="TP10MICROCrPEA10">Crosswalk with PEA10</option>
							<option value="TP10MICROCrTP10">Crosswalk with TP10</option>
							<option value="TP10MICROCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="TP10METROCrSchools_CCD">School Districts</option>
							<option value="TP10MICROCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="TP10MICROCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="TP10MICROCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="TP10METROCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="TP10METROCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="TP10METROCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="TP10METROCrERS10">Crosswalk with ERS10</option>
						<option value="TP10METROCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoTP10MICRO" disabled>Go</button>
				</div>
			</div>
			<div class="divSearch divGoTribalLand" style="display:none; margin-left:271px; width:276px; margin-top:0px; height: 85px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 16px; margin-top: 6px;">Crosswalk: </p>
				<div style="display:flex;">
					<select id="selGoTribalLand" class="selectpicker selGo form-control selectGroup show-tick" style="width: 202px; float: left; margin-bottom: 16px; margin-left: 13px;">
						<option value="none" selected>None</option>
						<optgroup label="Political / Gov. Boundaries">
							<option value="TribalLandCrStates" disabled>Crosswalk with States</option>
							<option value="TribalLandCrCounties">Crosswalk with Counties</option>
							<option value="TribalLandCrCities">Crosswalk with Cities / Townships</option>
							<option value="TribalLandCrDistricts">Crosswalk with Political Districts</option>
						</optgroup>
						<optgroup label="Economic Boundaries">
							<option value="TribalLandCrRegion">Crosswalk with US Census Regions</option>
							<option value="TribalLandCrTracts">Crosswalk with US Census Tracts</option>
							<option value="TribalLandCrFood_Report">Crosswalk with BLS Regions</option>
							<option value="TribalLandCrMSAs">Crosswalk with MSAs</option>
							<option value="TribalLandCrBEA10">Crosswalk with BEA10</option>
							<option value="TribalLandCrCBSA10">Crosswalk with CBSA10</option>
							<option value="TribalLandCrPEA10">Crosswalk with PEA10</option>
							<option value="TribalLandCrTP10">Crosswalk with TP10</option>
							<option value="TribalLandCrTP10METRO">Crosswalk with TP10METRO</option>
							<option value="TribalLandCrTP10MICRO">Crosswalk with TP10MICRO</option>
							<option value="TribalLandCrLauCnty">Crosswalk with LAUS</option>
						</optgroup>
						<optgroup label="Administrative Boundaries">
						<option value="TribalLandCrSchools_CCD">School Districts</option>
							<option value="TribalLandCrZip">Crosswalk with Zip Codes</option>
							<optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;Healthcare">
								<option value="TribalLandCrHSA">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HSA</option>
								<option value="TribalLandCrHRR">&nbsp;&nbsp;&nbsp;&nbsp;Crosswalk with HRR</option>
							</optgroup>
						<optgroup label="Social & Other Boundaries">
							<option value="TribalLandCrNeighbourCities">Crosswalk with Neighborhoods</option>
							<option value="TribalLandCrConsumerMarket">Crosswalk with Consumer Markets</option>
							<option value="TribalLandCrOpportunityZones">Crosswalk with Econ. Opportunity Zones</option>
						</optgroup>
						<option value="TribalLandCrERS10">Crosswalk with ERS10</option>
						<option value="TribalLandCrERS10Rep">Crosswalk with ERS10REP</option>
					</select>
					<button type="submit" class="btnSearch showReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoTribalLand" disabled>Go</button>
				</div>
			</div>



		</div>

    <div class="logosDiv">
      <h5><b style="color: #51b4ac;">Boundaries Labels </b></h5>
      <div class="onoffswitch">
        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switchLabels" onchange="changeLabels($(this).prop('checked')?'on':'off')" >
        <label class="onoffswitch-label" for="switchLabels"></label>
      </div>
    </div>
    <div class="overlayDiv">
      <h5><b style="color: #51b4ac;"> Crosswalk Labels </b></h5>
      <div class="onoffswitch">
        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switchOverlay" onchange="toggleOverlay($(this).prop('checked')?'on':'off')">
        <label class="onoffswitch-label" for="switchOverlay"></label>
      </div>
    </div>
    <div id="divChanges" style="display:none;">
			<div class="divChangeOptions" style="left: 36px; width: 152px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-top: -4px;">Choose Relation: </p>
				<select id="selRelation" class="selectpicker form-control selectGroup show-tick">
					<option value="none">Choose One</option>
					<option value="st-tract">States-Tracts</option>
					<option value="st-zip" disabled>States-ZIPS</option>
				</select>
			</div>
			<div class="divChangeOptions timespan"  style="left: 190px; width: 216px; display:none;" >
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-top: -4px;">Choose Time Span: </p>
				<select id="selTr-St" class="selectpicker form-control selectGroup show-tick" >
					<option value="none">Choose One</option>
					<option value="chng1year">Changes Over 1 year</option>
					<option value="chng7year">Changes Over 7 years</option>
					<option value="chng8year">Changes Over 8 years</option>
				</select>
			</div>
		</div>
		<div id="divRatio" style="display:none;">
			<div class="divChangeOptions" style="left: 36px; width: 152px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-top: -4px;">Choose Relation: </p>
				<select id="selRatio" class="selectpicker form-control selectGroup show-tick">
					<option value="none">Choose One</option>
					<option value="cbsa10-zip">CBSA-ZIP</option>
					<option value="zip-cbsa10">ZIP-CBSA</option>
					<option value="counties-zip">COUNTY-ZIP</option>
					<option value="zip-counties">ZIP-COUNTY</option>
					<option value="tracts-zip">TRACT-ZIP</option>
					<option value="zip-tracts">ZIP-TRACT</option>
				</select>
			</div>
      <div class="divSearch srchRatio" style="width:370px; margin-top: -1px;">
				<p style="color: rgba(38,166,154,0.8); font-weight: bold; margin-left: 14px; margin-top: 6px;">Data Field: </p>
				<input type="text" style="float: left; border-radius: 5px 20px 21px 5px; border: 1px solid rgba(38,166,154,0.8);" placeholder="Search Code" class="searchBar" id="searchBarRatios" autocomplete="off" name="search" onkeypress="return isNumber(event)"/>
				<button type="submit" style="margin-left: -34px; font-size: 17px; margin-top: -2px; border-radius: 20px;" class="btnSearch" id="btnSearchRatios"><i class="fa fa-arrow-circle-right"></i></button>
			</div>
      <button type="submit" class="getRatio" id="btnGetRatios" disabled onclick="btnGoRatios();">Get Ratios</button>
      <button type="submit" class="showRatioReport" style="margin-top: 0px; color: rgba(38,166,154,0.7); background-color: white; border: 1px solid rgba(38,166,154,0.7);" id="btnGoRatio" disabled>Go</button>
		</div>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document" >
			<div class="modal-content ui-widget-content" id="resizeDiv">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b id="myModalLabel"></b></h4>
			  </div>
			  <div class="modal-body">
				<div id='table' style="display:none; text-align:center;"></div>
			  </div>
			</div>
		  </div>
		</div>
		<div id="instructions" style="display:none;">Click on any State to start Crosswalk or enter Code</div>

	</div>
	<div id="popup"  style="display:none;"><div class="arrow_box"></div><div id="errorTxt">Please enter the Zip Code</div></div>
<script type="text/javascript" src="js/custom/AppScript.js"></script>
<script type="text/javascript" src="js/custom/functions.js"></script>
<script type="text/javascript" src="js/custom/functionsRatios.js"></script>
</body>
</html>
