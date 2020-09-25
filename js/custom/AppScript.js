var emptyFn = function() {};
var labelEngine = new labelgun['default'](emptyFn, emptyFn);
var hideLabels = true;
var hideOverlayLabels = true;
var textCache = {};
var textCacheOverlay = {};
var tilesPending = 0;
var tilesLoaded = 0;
var wmsFetchExtent;
var t;
var col = 0;
var Ghosted = 'Off';
var countArray = new Array();

$(document).ready(function() {
  toggleSearchDivs('srchZip');
});

var view = new ol.View({
  center: ol.proj.fromLonLat([-97, 38]),
  zoom: 4
});
// // Ratio Layers
var ratioLayer,
  ratioStates,
  ratioCounties,
  ratioCBSA10,
  ratioZip,
  ratioTracts;

// All Crosswalk layers
var sLayer, crswlkOverlay, ratioOverlay, tracts, zips, county;

var datasetsArray = {
  Zip: 'Zip',
  Tracts: 'Tract',
  Counties: 'County',
  Region: 'Region',
  Districts: 'District',
  Cities: 'City',
  MSAs: 'MSA',
  NeighbourCities: 'Neighborhood',
  Food_Report: 'BLS',
  LauCnty: 'LAUS',
  BEA10: 'BEA',
  CBSA10: 'CBSA',
  ERS10: 'ERS',
  ERS10Rep: 'ERS Rep',
  MSAs_Grainger: 'MSA Grainger',
  OpportunityZones: 'Opportunity Zone',
  PEA10: 'PEA',
  TP10: 'TP',
  TP10METRO: 'TP Metro',
  TP10MICRO: 'TP Micro',
  TribalLand: 'Tribal Land',
  Schools_CCD: 'School District',
  ConsumerMarket: 'Consumer Market',
  HRR: 'HRR',
  HSA: 'HSA',
  WaterShedRegions: 'Watershed Region',
  SubBasin: 'Sub Basin',
  SubWatershed: 'Sub Watershed',
  STR_Geocoded_All_New: 'STR'
};
///
var reportID = {
  Zip: 'b1.name',
  Tracts: 'b1.geoid',
  Counties: 'b1.geoid',
  Districts: 'b1.geoid',
  Cities: 'b1.geoid',
  MSAs: 'b1.geoid',
  NeighbourCities: 'b1.gid',
  LauCnty: 'b1.laus_code',
  Food_Report: 'b1.code',
  BEA10: 'b1.lm_code',
  CBSA10: 'b1.lm_code',
  ERS10: 'b1.lm_code',
  ERS10Rep: 'b1.lm_code',
  MSAs_Grainger: 'b1.gid',
  OpportunityZones: 'b1.geoid10',
  PEA10: 'b1.lm_code',
  TP10: 'b1.lm_code',
  TP10METRO: 'b1.lm_code',
  TP10MICRO: 'b1.lm_code',
  TribalLand: 'b1.gid',
  Schools_CCD: 'b1.nces_distr',
  ConsumerMarket: 'b1.geoid',
  HRR: 'b1.hrrnum',
  HSA: 'b1.hsa93',
  WaterShedRegions: 'b1.huc',
  SubBasin: 'b1.huc',
  SubWatershed: 'b1.huc',
  STR_Geocoded_All_New: 'b1.gid'
};
///
var reportName = {
  Zip: 'concat(\'zip.\',b1.name)',
  Tracts: 'b1.name',
  Counties: 'b1.name',
  Districts: 'b1.name',
  Cities: 'b1.name',
  MSAs: 'b1.name',
  NeighbourCities: 'b1.name',
  LauCnty: 'b1.county_name',
  Food_Report: 'b1.name',
  BEA10: 'concat(\'BEA.\', b1.lm_code)',
  CBSA10: 'concat(\'CBSA.\', b1.lm_code)',
  ERS10: 'concat(\'ERS.\', b1.lm_code)',
  ERS10Rep: 'concat(\'ERS_Rep.\', b1.lm_code)',
  MSAs_Grainger: 'b1.name',
  OpportunityZones: 'b1.countyname',
  PEA10: 'concat(\'PEA.\', b1.lm_code)',
  TP10: 'concat(\'TP.\', b1.lm_code)',
  TP10METRO: 'concat(\'TPMetro.\', b1.lm_code)',
  TP10MICRO: 'concat(\'TPMicro.\', b1.lm_code)',
  TribalLand: 'b1.name',
  Schools_CCD: 'b1.name',
  ConsumerMarket: 'b1.name',
  HRR: 'b1.name',
  HSA: 'b1.name',
  SubWatershed: 'b1.name',
  WaterShedRegions: 'b1.name',
  SubBasin: 'b1.name',
  STR_Geocoded_All_New: 'concat(b1.range_new, b1.range_dir, \', \', b1.township_new, b1.township_dir)'
};

var sourceDatasets, sourceTracts, sourceCounties, sourceZips, sourceCounties;

var formatArea = function(polygon) {
  var area = ol.Sphere.getArea(polygon);
  var output = (Math.round(area * 100 * 10.764) / 100);
  return output;
};
var geojsonFormat = new ol.format.GeoJSON();

var bingAerial = new ol.layer.Tile({
  displayInLayerSwitcher: false,
  name: "Bing Aerial",
  visible: false,
  baseLayer: true,
  preload: Infinity,
  source: new ol.source.BingMaps({
    key: 'Ai9y3x8v0FM1vGDUXevZDinOzkJVacIW8kJOtSwUDNn8WGpE0ZjxZPJttvIYZg5L',
    imagerySet: "AerialWithLabels"
  })
});
var osmLight = new ol.layer.Tile({
  displayInLayerSwitcher: false,
  name: "OSM Light Gray",
  visible: true,
  baseLayer: true,
  source: new ol.source.OSM({
    "url": "http://{a-c}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png"
  })
});

var bingRoads = new ol.layer.Tile({
  displayInLayerSwitcher: false,
  name: "Bing Roads",
  visible: false,
  baseLayer: true,
  preload: Infinity,
  source: new ol.source.BingMaps({
    key: 'Ai9y3x8v0FM1vGDUXevZDinOzkJVacIW8kJOtSwUDNn8WGpE0ZjxZPJttvIYZg5L',
    imagerySet: "Road"
  })
});

var osm = new ol.layer.Tile({
  name: "OSM",
  baseLayer: true,
  source: new ol.source.OSM(),
  visible: false
});

var terrainStamen = new ol.layer.Tile({
  displayInLayerSwitcher: false,
  name: "Terrain Stamen",
  baseLayer: true,
  visible: false,
  source: new ol.source.Stamen({
    layer: 'terrain'
  })
});
var osm = new ol.layer.Tile({
  source: new ol.source.OSM()
});
var geoJSONFormat = new ol.format.GeoJSON();

var map, zips, tracts, county, states, statefp, stabbr, statename;
var crosswalkLayers = [];
function init() {
  $('select').selectpicker();
  toastr.info("Use Text Boxes or click on the map");
  toastr.info("Use ';' delimiter for searching Multiple ZIPS like '36322;36005'");
  crosswalkLayers['SubWatershed'] = getBoundary("subwatershed");
  crosswalkLayers['States'] = getBoundary('states');
  crosswalkLayers['Region'] = getBoundary("region");
  crosswalkLayers['BEA10'] = getBoundary("bea10");
  crosswalkLayers['CBSA10'] = getBoundary("cbsa10");
  crosswalkLayers['ERS10'] = getBoundary("ers10");
  crosswalkLayers['ERS10Rep'] = getBoundary("ers10rep");
  crosswalkLayers['MSAs_Grainger'] = getBoundary("msas_grainger");
  // crosswalkLayers['OpportunityZones'] = getBoundary("opportunityzones");
  crosswalkLayers['PEA10'] = getBoundary("pea10");
  crosswalkLayers['TP10'] = getBoundary("tp10");
  crosswalkLayers['TP10METRO'] = getBoundary("tp10metro");
  crosswalkLayers['TP10MICRO'] = getBoundary("tp10micro");
  crosswalkLayers['TribalLand'] = getBoundary("triballand");
  crosswalkLayers['Schools_CCD'] = getBoundary("schools_ccd");
  crosswalkLayers['ConsumerMarket'] = getBoundary("consumermarket");
  crosswalkLayers['HSA'] = getBoundary("hsa");
  crosswalkLayers['HRR'] = getBoundary("hrr");
  crosswalkLayers['WaterShedRegions'] = getBoundary("watershedregions");
  crosswalkLayers['wmsSubWatershed'] = getWMS("SubWatershed", "subwatershed", "subwatershed", false);

  map = new ol.Map({
    layers: [
      /* Base Maps */
      bingAerial,
      terrainStamen,
      bingRoads,
      osmLight,
      crosswalkLayers['wmsSubWatershed'],
      /* Layer */
      crosswalkLayers['States'],
      crosswalkLayers['SubWatershed'],
      crosswalkLayers['Region'],
      crosswalkLayers['BEA10'],
      crosswalkLayers['CBSA10'],
      crosswalkLayers['ERS10'],
      crosswalkLayers['ERS10Rep'],
      crosswalkLayers['MSAs_Grainger'],
      // crosswalkLayers['OpportunityZones'],
      crosswalkLayers['PEA10'],
      crosswalkLayers['TP10'],
      crosswalkLayers['TP10METRO'],
      crosswalkLayers['TP10MICRO'],
      crosswalkLayers['TribalLand'],
      crosswalkLayers['WaterShedRegions']
    ],
    target: 'map',
    view: view
  });
  crosswalkLayers['SubWatershed'].setVisible(false);
  toggleBaseLayers(crosswalkLayers['States']);

  map.on('singleclick', onMouseClick);
  map.on('pointermove', function(evt) {
    if (evt.dragging) {
      return;
    }
    var pixel = map.getEventPixel(evt.originalEvent);
    var hit = map.forEachLayerAtPixel(pixel, function(layer) {
      var source = layer.getSource();
      if (source instanceof ol.source.TileWMS || source instanceof ol.source.Vector) {
        return layer;
      }
    });
    map.getTargetElement().style.cursor = hit ? 'pointer' : '';
  });
}

function getWMS(title, name, layerName, visi) {
  var source;
  source = new ol.source.TileWMS({
    url: '../geoserver/wms',
    params: {
      'LAYERS': 'Farmer:' + layerName
    },
    serverType: 'geoserver'
  });
  source.on(['tileloadend', 'tileloaderror'], function() {
    ++tilesLoaded;
    var percentage = Math.round(tilesLoaded / tilesPending * 100);
    $('#js-progress-bar').css({
      'width': percentage + '%'
    });

    if (percentage >= 100) {
      setTimeout(function() {
        $('#js-progress-bar').css({
          'width': '0',
          'opacity': '0'
        });
        tilesLoaded = 0;
        tilesPending = 0;
      }, 600);
    }
  });

  source.on('tileloadstart', function() {
    $('#js-progress-bar').css({
      'opacity': '1',
      'height': '5px'
    });
    ++tilesPending;
  });

  var wms = new ol.layer.Tile({
    displayInLayerSwitcher: false,
    extent: wmsFetchExtent,
    title: title,
    name: name,
    source: source,
    visible: visi
  });

  return wms;
}

function getBoundary(lyrName, statefp) {
  var color, dashStyle, strokeWidth = 2,
    fontSize = "10px";

  var source = new ol.source.Vector({
    loader: function(extent, resolution, projection) {
      var extent = map.getView().calculateExtent(map.getSize());
      extent = ol.extent.applyTransform(extent, ol.proj.getTransform("EPSG:3857", "EPSG:4326"));
      wmsFetchExtent = extent;
      var url;
      if (lyrName.indexOf("_ratio") !== -1)
        lyrName = lyrName.replace('_ratio', '');
      if (lyrName == "zip") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp10=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,colorid,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "str_geocoded_all_new") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=state ILIKE ' + '\'%25' + statefp + '%25\'' + '&outputFormat=application/json&PropertyName=id_1,section_field_new,township_new,township_dir,range_new,range_dir,county,state,geom&srsname=EPSG:3857';
      } else if (lyrName == "msas") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp ILIKE ' + '\'%25' + statefp + '%25\'' + '&outputFormat=application/json&PropertyName=name,statefp,colorid,geoid,geom&srsname=EPSG:3857';
      } else if (lyrName == "food_report") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp ILIKE ' + '\'%25' + statefp + '%25\'' + '&outputFormat=application/json&PropertyName=name,statefp,code,colorid,geom&srsname=EPSG:3857';
      } else if (lyrName == "watershedregions") {
        url = '../geoserver/ows?service=WFS&version=1.0.0&request=GetFeature&typename=Farmer:' + lyrName + '&outputFormat=application/json&PropertyName=huc,colorid,geomsimple&srsname=EPSG:3857';
      } else if (lyrName == "subwatershed") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp ILIKE ' + '\'%25' + statefp + '%25\'' + '&outputFormat=application/json&PropertyName=huc,statefp,colorid,geomsimple&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "subbasin") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=states ILIKE ' + '\'%25' + stabbr + '%25\'' + '&outputFormat=application/json&PropertyName=huc,colorid,geomsimple&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "neighbourcities") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:neighbourcities&CQL_FILTER=statefp ILIKE ' + '\'%25' + statefp + '%25\'' + '&outputFormat=application/json&PropertyName=name,colorid,state,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "consumermarket") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:consumermarket&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,colorid,geoid,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "schools_ccd") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,colorid,statefp,nces_distr,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "hrr") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,colorid,statefp,hrrnum,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "hsa") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,colorid,statefp,hsa93,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "tracts") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,colorid,geoid,statefp,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "cities" || lyrName == "districts") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,geoid,colorid,statefp,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "counties") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,colorid,state,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "laucnty") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=name,laus_code,colorid,statefp,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "laucnty") {
        url = '../geoserver/wfs?service=WFS&' +
          'version=1.1.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=statefp=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=county_name,colorid,statefp,geom&srsname=EPSG:3857&' + ',EPSG:3857';
      } else if (lyrName == "states") {
        url = '../geoserver/ows?service=WFS&version=1.0.0&request=GetFeature&typename=Farmer:' + lyrName + '&outputFormat=application/json&PropertyName=name,statefp,stusps,geom&srsname=EPSG:3857';
      } else if (lyrName == "region" || lyrName == "msas_grainger" || lyrName == "triballand") {
        url = '../geoserver/ows?service=WFS&version=1.0.0&request=GetFeature&typename=Farmer:' + lyrName + '&outputFormat=application/json&PropertyName=name,colorid,geom&srsname=EPSG:3857';
      } else if (lyrName == "bea10" || lyrName == "cbsa10" || lyrName == "ers10" || lyrName == "ers10rep" || lyrName == "pea10" || lyrName == "tp10" || lyrName == "tp10metro" || lyrName == "tp10micro") {
        url = '../geoserver/ows?service=WFS&version=1.0.0&request=GetFeature&typename=Farmer:' + lyrName + '&outputFormat=application/json&PropertyName=lm_code,colorid,geom&srsname=EPSG:3857';
      } else if (lyrName == "opportunityzones") {
        url = '../geoserver/ows?service=WFS&version=1.0.0&request=GetFeature&typename=Farmer:' + lyrName + '&CQL_FILTER=strToLowerCase(statename)=' + '\'' + statefp + '\'' + '&outputFormat=application/json&PropertyName=geoid10,countyname,colorid,geom&srsname=EPSG:3857';
      }
      $.ajax({
        url: url,
        dataType: 'json',
        beforeSend: function(e) {
          $('#js-progress-bar').css({
            'opacity': '1',
            'height': '5px'
          });
          ++tilesPending;
        },
        success: function(data) {
          ++tilesLoaded;
          var percentage = Math.round(tilesLoaded / tilesPending * 100);
          $('#js-progress-bar').css({
            'width': percentage + '%'
          });
          if (percentage >= 100) {
            setTimeout(function() {
              $('#js-progress-bar').css({
                'width': '0',
                'opacity': '0'
              });
              tilesLoaded = 0;
              tilesPending = 0;
            }, 600);
          }
          var features = geoJSONFormat.readFeatures(data);
          source.addFeatures(features);
        }
      });
    },
    strategy: ol.loadingstrategy.bbox
  });

  switch (lyrName) {
    case "zip":
      color = "#00AEEF";
      dashStyle = [0, 0];
      break;
    default:
      break;
  }

  function setStyle(context) {
    context.font = fontSize + " 'Lato'";
    context.fillStyle = color;
    context.strokeStyle = color;
    context.textBaseline = 'hanging';
    context.textAlign = 'start';
  }

  var textMeasureContext = document.createElement('CANVAS').getContext('2d');
  setStyle(textMeasureContext);

  var height = textMeasureContext.measureText('WI').width;

  function createLabel(canvas, text, coord) {
    var halfWidth = canvas.width / 2;
    var halfHeight = canvas.height / 2;
    var bounds = {
      bottomLeft: [Math.round(coord[0] - halfWidth), Math.round(coord[1] - halfHeight)],
      topRight: [Math.round(coord[0] + halfWidth), Math.round(coord[1] + halfHeight)]
    };
    labelEngine.ingestLabel(bounds, coord.toString(), 1, canvas, text, false);
  }

  function sortByWidth(a, b) {
    return ol.extent.getWidth(b.getExtent()) - ol.extent.getWidth(a.getExtent());
  }

  var labelStyle = new ol.style.Style({
    renderer: function(coords, ftr) {
      if (lyrName == "tracts")
        var text = ftr.feature.get('tractce');
      else if (feature.c.indexOf("subwatershed") !== -1 || feature.c.indexOf("watershedregions") !== -1 || feature.c.indexOf("subbasin") !== -1)
        text = "HUC-" + feature.get('huc');
      else if (lyrName == "counties" || lyrName == "states" || lyrName == "zip")
        var text = ftr.feature.get('name');
      createLabel(textCache[text], text, coords);
    }
  });

  var boundaryStyle = new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: color,
      width: strokeWidth,
      lineDash: dashStyle
    })
  });
  var styleWithLabel = [boundaryStyle, labelStyle];
  var styleWithoutLabel = [boundaryStyle];
  var vectorLayer = new ol.layer.Vector({
    displayInLayerSwitcher: false,
    name: lyrName,
    declutter: true,
    visible: true,
    source: source,
    style: getBoundaryAndLabelStyle
  });

  vectorLayer.on('precompose', function(e) {
    pixelRatio = e.frameState.pixelRatio;
    labelEngine.destroy();
  });
  vectorLayer.on('postcompose', function(e) {
    var labels = labelEngine.getShown();
    for (var i = 0, ii = labels.length; i < ii; ++i) {
      var label = labels[i];
      e.context.drawImage(label.labelObject, label.minX, label.minY);
    }
  });
  return vectorLayer;
}

function getBoundaryCrosswalks(source, lyrName) {
  var color, dashStyle, strokeWidth = 2,
    fontSize = "10px";
  var fillColor = "rgba(82, 82, 82, 0.67)";

  function setStyle(context) {
    context.font = fontSize + " 'Lato'";
    context.fillStyle = fillColor;
    context.strokeStyle = color;
    context.textBaseline = 'hanging';
    context.textAlign = 'start';
  }

  var textMeasureContext = document.createElement('CANVAS').getContext('2d');
  setStyle(textMeasureContext);

  var height = textMeasureContext.measureText('WI').width;

  function createLabel(canvas, text, coord) {
    var halfWidth = canvas.width / 2;
    var halfHeight = canvas.height / 2;
    var bounds = {
      bottomLeft: [Math.round(coord[0] - halfWidth), Math.round(coord[1] - halfHeight)],
      topRight: [Math.round(coord[0] + halfWidth), Math.round(coord[1] + halfHeight)]
    };
    labelEngine.ingestLabel(bounds, coord.toString(), 1, canvas, text, false);
  }

  function sortByWidth(a, b) {
    return ol.extent.getWidth(b.getExtent()) - ol.extent.getWidth(a.getExtent());
  }

  var labelStyle = new ol.style.Style({
    renderer: function(coords, ftr) {
      var text = ftr.feature.get('name');
      createLabel(textCache[text], text, coords);
    }
  });

  var boundaryStyle = new ol.style.Style({
    stroke: new ol.style.Stroke({
      color: color,
      width: strokeWidth,
      lineDash: dashStyle
    })
  });
  var styleWithLabel = [boundaryStyle, labelStyle];
  var styleWithoutLabel = [boundaryStyle];
  var vectorLayer = new ol.layer.Vector({
    displayInLayerSwitcher: false,
    name: lyrName,
    declutter: true,
    visible: true,
    source: source,
    style: getStyle,
    opacity: 0.7
  });

  vectorLayer.on('precompose', function(e) {
    pixelRatio = e.frameState.pixelRatio;
    labelEngine.destroy();
  });
  vectorLayer.on('postcompose', function(e) {
    var labels = labelEngine.getShown();
    for (var i = 0, ii = labels.length; i < ii; ++i) {
      var label = labels[i];
      e.context.drawImage(label.labelObject, label.minX, label.minY);
    }
  });

  return vectorLayer;
}

function onMouseClick(evt) {
  var region;
  var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
    return feature;
  });
  var viewResolution = /** @type {number} */ (view.getResolution());

  try {
    coordinates = feature.getGeometry().getCoordinates();
  } catch (e) {}
  try {
    coords = ol.proj.transform(coordinates, 'EPSG:3857', 'EPSG:4326');
  } catch (e) {}
  var layer = map.forEachFeatureAtPixel(evt.pixel, function(feature, layer) {
    return layer;
  });
  if (feature && layer) {
    var layerName = layer.get('name');
    if (layerName == 'states') {
      Ghosted = "Off";
      removeLayers();
      statename = feature.S.name;
      statefp = feature.S.statefp;
      stabbr = feature.S.stusps;
      var code = $("#selCode").val();
      $('#selGo' + code).prop('selectedIndex', 0);
      $("#selGo" + code).trigger("change");
      $("#selGo" + code).selectpicker('refresh');
      var selCode = $("#selCode").val();
      if (selCode === 'Zip' || selCode === 'Tracts' || selCode === 'Counties' || selCode === 'LauCnty' || selCode === 'Food_Report' || selCode === 'Districts' || selCode === 'Cities' || selCode === 'MSAs' || selCode === 'NeighbourCities' || selCode === 'Schools_CCD' || selCode === 'ConsumerMarket' || selCode === 'HSA' || selCode === 'HRR' || selCode === 'SubBasin') {
          try { map.removeLayer(crosswalkLayers[selCode]); } catch (e) {}
          crosswalkLayers[selCode] = getBoundary(selCode.toLowerCase(), statefp);
          map.addLayer(crosswalkLayers[selCode]);
      }
      else if (selCode == 'OpportunityZones') {
        try { map.removeLayer(crosswalkLayers[selCode]); } catch (e) {}
        crosswalkLayers[selCode] = getBoundary('opportunityzones', statename.toLowerCase());
        map.addLayer(crosswalkLayers[selCode]);
      }
      else if (selCode == 'SubWatershed') {
        try { map.removeLayer(crosswalkLayers['wmsSubWatershed']); } catch (e) {}
        crosswalkLayers['wmsSubWatershed'].getSource().updateParams({
          'LAYERS': 'Farmer:subwatershed',
          'cql_filter': "statefp ilike '%" + statefp + "%'",
          'STYLES': 'subwatershed'
        });
        crosswalkLayers['wmsSubWatershed'].setVisible(true);
        crosswalkLayers['wmsSubWatershed'] = getBoundary('subwatershed', statefp);
        map.addLayer(crosswalkLayers['wmsSubWatershed']);
      } else if (selCode == 'STR_Geocoded_All_New') {
        crosswalkLayers[selCode] = getBoundary('str_geocoded_all_new', stabbr);
        map.addLayer(crosswalkLayers[selCode]);
      } else if (selCode == 'State') {
        $("#searchBarState").val(feature.S.name);
        var extent = feature.getGeometry().getExtent();
        map.getView().fit(extent, {
          duration: 1000
        });
        $('#selGoState').prop('selectedIndex', 0);
        $("#selGoState").trigger("change");
        $("#selGoState").selectpicker('refresh');
        $("#btnSearchState").trigger("click");
      }
      var extent = feature.getGeometry().getExtent();
      map.getView().fit(extent, {
        duration: 1000
      });
      $("#instructions").hide();
    } else if (layerName == 'states_ratio') {
      removeLayersRatios('overlays');
      statename = feature.S.name;
      statefp = feature.S.statefp;
      stabbr = feature.S.stusps;
      var code = $("#selRatio").val();
      code = code.split('-');
      if (code[0] !== 'cbsa10') {
        var obj = new Object();
        var val = code[0].capitalize();
        if (val === 'Zip') {
          ratioZip = getBoundary(code[0] + '_ratio', statefp);
          map.addLayer(ratioZip);
        } else if (val === 'CBSA10') {
          ratioCBSA10 = getBoundary(code[0] + '_ratio', statefp);
          map.addLayer(ratioCBSA10);
        } else if (val === 'Tracts') {
          ratioTracts = getBoundary(code[0] + '_ratio', statefp);
          map.addLayer(ratioTracts);
        } else if (val === 'Counties') {
          ratioCounties = getBoundary(code[0] + '_ratio', statefp);
          map.addLayer(ratioCounties);
        }
        var extent = feature.getGeometry().getExtent();
        map.getView().fit(extent, {
          duration: 1000
        });
      }
    } else if (layerName == 'counties_ratio') {
      var code = feature.S.geoid;
      $("#searchBarRatios").val(code);
      searchInputBoundary('Counties_ratio', 'geoid');
    } else if (layerName == 'zip_ratio') {
      var code = feature.S.name;
      $("#searchBarRatios").val(code);
      searchInputBoundary('Zip', 'name');
    } else if (layerName == 'tracts_ratio') {
      var geoid = feature.S.geoid;
      $("#searchBarRatios").val(geoid);
      searchInputBoundary('Tracts', 'geoid');
    } else if (layerName == 'counties') {
      var stusps = feature.S.state;
      var name = feature.S.name;
      var txt = name + "," + stusps;
      Ghosted = 'On';
      $("#searchBarCounties").val(txt);
      btnSearch('Counties', 'name');
    } else if (layerName == 'region') {
      removeLayers();
      region = feature.S.name;
      $("#searchBarRegion").val(region);
      var extent = feature.getGeometry().getExtent();
      map.getView().fit(extent, {
        duration: 1000
      });
      $('#selGoRegions').prop('selectedIndex', 0);
      $("#selGoRegions").trigger("change");
      $("#selGoRegions").selectpicker('refresh');
      $("#btnSearchRegion").trigger("click");
    } else if (layerName == 'tracts') {
      var geoid = feature.S.geoid;
      $("#searchBarTracts").val(geoid);
      btnSearch('Tracts', 'geoid');
    } else if (layerName == 'zip') {
      var zcta5ce = feature.S.name;
      $("#searchBarZip").val(zcta5ce);
      btnSearch('Zip', 'name');
    } else if (layerName == 'districts') {
      var geoid = feature.S.geoid;
      $("#searchBarDistricts").val(geoid);
      btnSearch('Districts', 'geoid');
    } else if (layerName == 'cities') {
      var geoid = feature.S.geoid;
      $("#searchBarCities").val(geoid);
      btnSearch('Cities', 'geoid');
    } else if (layerName == 'msas') {
      var geoid = feature.S.geoid;
      $("#searchBarMSAs").val(geoid);
      btnSearch('MSAs', 'geoid');
    } else if (layerName == 'neighbourcities') {
      var stusps = feature.S.state;
      var name = feature.S.name;
      var txt = name + "," + stusps;
      $("#searchBarNeighbourCities").val(txt);
      btnSearch('NeighbourCities', 'name');
    } else if (layerName == 'laucnty') {
      var laus_code = feature.S.laus_code;
      $("#searchBarLauCnty").val(laus_code);
      btnSearch('LauCnty', 'laus_code');
    } else if (layerName == 'food_report') {
      var code = feature.S.code;
      $("#searchBarFood_Report").val(code);
      btnSearch('Food_Report', 'code');
    } else if (layerName == 'bea10') {
      var code = feature.S.lm_code;
      $("#searchBarBEA10").val(code);
      btnSearch('BEA10', 'lm_code');
    } else if (layerName == 'cbsa10') {
      var code = feature.S.lm_code;
      $("#searchBarCBSA10").val(code);
      btnSearch('CBSA10', 'lm_code');
    } else if (layerName == 'cbsa10_ratio') {
      var code = feature.S.lm_code;
      $("#searchBarRatios").val(code);
      searchInputBoundary('CBSA10', 'lm_code');
    } else if (layerName == 'ers10') {
      var code = feature.S.lm_code;
      $("#searchBarERS10").val(code);
      btnSearch('ERS10', 'lm_code');
    } else if (layerName == 'ers10rep') {
      var code = feature.S.lm_code;
      $("#searchBarERS10Rep").val(code);
      btnSearch('ERS10Rep', 'lm_code');
    } else if (layerName == 'msas_grainger') {
      var code = feature.c;
      code = code.replace('msas_grainger.', '');
      $("#searchBarMSAs_Grainger").val(code);
      btnSearch('MSAs_Grainger', 'gid');
    } else if (layerName == 'opportunityzones') {
      var code = feature.S.geoid10;
      $("#searchBarOpportunityZones").val(code);
      btnSearch('OpportunityZones', 'geoid10');
    } else if (layerName == 'pea10') {
      var code = feature.S.lm_code;
      $("#searchBarPEA10").val(code);
      btnSearch('PEA10', 'lm_code');
    } else if (layerName == 'tp10') {
      var code = feature.S.lm_code;
      $("#searchBarTP10").val(code);
      btnSearch('TP10', 'lm_code');
    } else if (layerName == 'tp10metro') {
      var code = feature.S.lm_code;
      $("#searchBarTP10METRO").val(code);
      btnSearch('TP10METRO', 'lm_code');
    } else if (layerName == 'tp10micro') {
      var code = feature.S.lm_code;
      $("#searchBarTP10MICRO").val(code);
      btnSearch('TP10MICRO', 'lm_code');
    } else if (layerName == 'triballand') {
      var code = feature.c;
      code = code.replace('triballand.', '');
      $("#searchBarTribalLand").val(code);
      btnSearch('TribalLand', 'gid');
    } else if (layerName == 'schools_ccd') {
      var code = feature.S.nces_distr;
      $("#searchBarSchools_CCD").val(code);
      btnSearch('Schools_CCD', 'nces_distr');
    } else if (layerName == 'consumermarket') {
      var code = feature.S.geoid;
      $("#searchBarConsumerMarket").val(code);
      btnSearch('ConsumerMarket', 'geoid');
    } else if (layerName == 'hsa') {
      var code = feature.S.hsa93;
      $("#searchBarHSA").val(code);
      btnSearch('HSA', 'hsa93');
    } else if (layerName == 'hrr') {
      var code = feature.S.hrrnum;
      $("#searchBarHRR").val(code);
      btnSearch('HRR', 'hrrnum');
    } else if (layerName == 'watershedregions') {
      var code = feature.S.huc;
      $("#searchBarWaterShedRegions").val(code);
      btnSearch('WaterShedRegions', 'huc');
    } else if (layerName == 'subbasin') {
      var code = feature.S.huc;
      $("#searchBarSubBasin").val(code);
      btnSearch('SubBasin', 'huc');
    } else if (layerName == 'subwatershed') {
      wmsSubWatershed.getSource().updateParams({
        'STYLES': 'CrosswalkGhosted'
      });
      var code = feature.S.huc;
      $("#searchBarSubWatershed").val(code);
      btnSearch('SubWatershed', 'huc');
    } else if (layerName == 'str_geocoded_all_new') {
      var code = feature.c;
      code = code.replace('str_geocoded_all_new.', '');
      $("#searchBarSTR_Geocoded_All_New").val(code);
      btnSearch('STR_Geocoded_All_New', 'gid');
    }
  }
}

$("#selRatio").change(function() {

  var value1 = $("#selRatio").val().split('-')[0];
  if (value1 == 'cbsa10') {
    removeLayersRatios();
    ratioCBSA10 = getBoundary(value1 + "_ratio");
    map.addLayer(ratioCBSA10);
  } else {
    removeLayersRatios('overlays');
    ratioStates = getBoundary("states_ratio");
    map.addLayer(ratioStates);
  }
});

$("#selCode").change(function() {
  Ghosted = "Off";
  hideAndClearAll();
  removeLayers();
  var code = $("#selCode").val();
  var instructionsArray = {
    Zip: '36322;36005',
    Tracts: '01097003301;01073003300',
    Counties: 'Cook,IL; DuPage,IL; Will,IL'
  };
  if (code !== "SubWatershed")
    crosswalkLayers['wmsSubWatershed'].setVisible(false);
  if (code == 'Zip' || code == 'Tracts' || code == 'Counties' || code == 'LauCnty' || code == 'Cities' || code == 'Districts' || code == 'MSAs' || code == 'NeighbourCities' || code == 'State' || code == 'Food_Report' || code == 'Schools_CCD' || code == 'ConsumerMarket' || code == 'HRR' || code == 'HSA' || code == 'SubBasin' || code == 'SubWatershed' || code == 'OpportunityZones' || code == 'STR_Geocoded_All_New') {
    toastr.info("Use ';' delimiter for searching Multiple " + code + " like " + instructionsArray[code] + "");
    toggleSearchDivs('srch' + code + '');
    toggleBaseLayers(crosswalkLayers['States']);
  } else if (code == 'Region' || code == 'BEA10' || code == 'CBSA10' || code == 'ERS10' || code == 'ERS10Rep' || code == 'MSAs_Grainger' || code == 'PEA10' || code == 'TP10' || code == 'TP10METRO' || code == 'TP10MICRO' || code == 'TribalLand' || code == 'WaterShedRegions') {
    toggleSearchDivs('srch' + code + '');
    toggleBaseLayers(crosswalkLayers[code]);
  }
});

$(".selGo").change(function() {
  try {
    map.removeLayer(crswlkOverlay);
  } catch (e) {}
  var eleID = $(this).attr('id');
  var boundary = eleID.split('Go').pop();
  Crosswalk(boundary, reportID[boundary]);
});
$("#selRelation").change(function() {
  $(".timespan").hide();
  if ($("#selRelation").val() == "st-tract")
    $(".timespan").show();
});
$("#selTr-St").change(function() {
  $.ajax({
    dataType: "json",
    url: 'server_scripts/readGeojson.php?value=none&cond=' + $("#selTr-St").val(),
    beforeSend: function(e) {
      $(".loader").show();
    },
    success: function(data) {
      $(".loader").hide();
      var table = ''
      if (data.length != 0) {
        if ($("#selTr-St").val() == 'chng1year') {
          table = '<div style=" color: rgba(58, 131, 124, 0.81); margin-bottom: 10px;">Toggle column: <a class="toggle-vis" data-column="0">State</a> - <a class="toggle-vis" data-column="1">State ID</a> - <a class="toggle-vis" data-column="2">County</a> - <a class="toggle-vis" data-column="3">County ID</a> - <a class="toggle-vis" data-column="4"># of Tracts 2017</a> - <a class="toggle-vis" data-column="5"># of Tracts 2018</a></div>';
          table += '<table border="1" id="myTable" style="width: 100%;" class="table table-bordered tablesorter"><thead><tr><th scope="col">State</th><th scope="col">State ID</th><th scope="col">County</th><th scope="col">County ID</th><th scope="col"># of Tracts 2017</th><th scope="col"># of Tracts 2018</th></tr></thead>';
        } else if ($("#selTr-St").val() == 'chng7year') {
          table = '<div style="margin-bottom: 8px;color: rgba(58, 131, 124, 0.81); margin-bottom: 10px;">Toggle column: <a class="toggle-vis" data-column="0">State</a> - <a class="toggle-vis" data-column="1">State ID</a> - <a class="toggle-vis" data-column="2">County</a> - <a class="toggle-vis" data-column="3">County ID</a> - <a class="toggle-vis" data-column="4"># of Tracts 2010</a> - <a class="toggle-vis" data-column="5"># of Tracts 2017</a></div>';
          table += '<table border="1" id="myTable" style="width: 100%;" class="table table-bordered tablesorter"><thead><tr><th scope="col">State</th><th scope="col">State ID</th><th scope="col">County</th><th scope="col">County ID</th><th scope="col"># of Tracts 2010</th><th scope="col"># of Tracts 2017</th></tr></thead>';
        } else if ($("#selTr-St").val() == 'chng8year') {
          table = '<div style="margin-bottom: 8px;color: rgba(58, 131, 124, 0.81); margin-bottom: 10px;"><b>Toggle column: </b><a class="toggle-vis" data-column="0">State</a> - <a class="toggle-vis" data-column="1">State ID</a> - <a class="toggle-vis" data-column="2">County</a> - <a class="toggle-vis" data-column="3">County ID</a> - <a class="toggle-vis" data-column="4"># of Tracts 2010</a> - <a class="toggle-vis" data-column="5"># of Tracts 2018</a></div>';
          table += '<table border="1" id="myTable" style="width: 100%;" class="table table-bordered tablesorter"><thead><tr><th scope="col">State</th><th scope="col">State ID</th><th scope="col">County</th><th scope="col">County ID</th><th scope="col"># of Tracts 2010</th><th scope="col"># of Tracts 2018</th></tr></thead>';
        }
        table += '<tbody><tr>';
        for (var i = 0; i < data.length; i++) {

          table += '<td>' + data[i].stusps + '</td>';
          table += '<td>' + data[i].statefp + '</td>';
          table += '<td>' + data[i].name + '</td>';
          table += '<td>' + data[i].countyfp + '</td>';
          if ($("#selTr-St").val() == 'chng1year') {
            table += '<td>' + data[i].num_of_tracts17 + '</td>';
            table += '<td>' + data[i].num_of_tracts18 + '</td>';
          } else if ($("#selTr-St").val() == 'chng7year') {
            table += '<td>' + data[i].num_of_tracts10 + '</td>';
            table += '<td>' + data[i].num_of_tracts17 + '</td>';
          } else if ($("#selTr-St").val() == 'chng8year') {
            table += '<td>' + data[i].num_of_tracts10 + '</td>';
            table += '<td>' + data[i].num_of_tracts18 + '</td>';
          }
          table += '</tr>';
        }
        table += '</tbody></table>';
      } else {
        table = '<span style="font-size: 18px; font-weight: bold; color: #000;">No Data Found!!</span>'
      }
      $("#myModalLabel").html('Summary for Census Tracts Changes Over Years');
      $("#table").html('');
      $("#table").html(table);
      $("#table").show();
      $("#optionsCounty").hide();
      $('#myModal').removeClass('fade');
      $('#myModal').addClass('show');
      paginateTable(10);
    }
  });
});

function getBoundaryAndLabelStyle(feature, resolution) {
  var color, dashStyle, strokeWidth = 1,
    fontSize = "17px";

  function setStyle(context) {
    context.font = fontSize + " 'Lato'";
    context.fillStyle = color;
    context.strokeStyle = color;
    context.textBaseline = 'hanging';
    context.textAlign = 'start';
  }
  var textMeasureContext = document.createElement('CANVAS').getContext('2d');
  setStyle(textMeasureContext);

  var height = textMeasureContext.measureText('WI').width;

  function createLabel(canvas, text, coord) {
    var halfWidth = canvas.width / 2;
    var halfHeight = canvas.height / 2;
    var bounds = {
      bottomLeft: [Math.round(coord[0] - halfWidth), Math.round(coord[1] - halfHeight)],
      topRight: [Math.round(coord[0] + halfWidth), Math.round(coord[1] + halfHeight)]
    };
    labelEngine.ingestLabel(bounds, coord.toString(), 1, canvas, text, false);
  }

  function sortByWidth(a, b) {
    return ol.extent.getWidth(b.getExtent()) - ol.extent.getWidth(a.getExtent());
  }

  var labelStyle = new ol.style.Style({
    renderer: function(coords, state) {
      var text = "";
      if (feature.c.indexOf("laucnty") !== -1)
        text = feature.get('laus_code');
      else if (feature.c.indexOf("subwatershed") !== -1 || feature.c.indexOf("watershedregion") !== -1 || feature.c.indexOf("watershedsubregion") !== -1 || feature.c.indexOf("subbasin") !== -1)
        text = "HUC-" + feature.get('huc');
      else if (feature.c.indexOf("cbsa") !== -1 || feature.c.indexOf("ers") !== -1 || feature.c.indexOf("bea") !== -1 || feature.c.indexOf("tp10") !== -1 || feature.c.indexOf("pea10") !== -1)
        text = feature.get('lm_code');
      else if (feature.c.indexOf("opportunityzones") !== -1)
        text = feature.get('geoid10');
      else if (feature.c.indexOf("laucnty") !== -1)
        text = feature.get('laus_code');
      else if (feature.c.indexOf("schools_ccd") !== -1)
        text = feature.get('nces_distr');
      else if (feature.c.indexOf("str") !== -1)
        text = feature.get('township_new') + feature.get('township_dir') + ' ' + feature.get('range_new') + feature.get('range_dir');
      else
        text = feature.get('name');
      createLabel(textCache[text], text, coords);
    }
  });
  // // // Ghosted Check
  if (Ghosted == 'Off') {
    if (feature.c.indexOf("zip") !== -1 || feature.c.indexOf("counties") !== -1 || feature.c.indexOf("tracts") !== -1 || feature.c.indexOf("districts") !== -1 || feature.c.indexOf("cities") !== -1 || feature.c.indexOf("msas") !== -1 || feature.c.indexOf("neighborhood") !== -1 || feature.c.indexOf("district") !== -1 || feature.c.indexOf("laucnty") !== -1 || feature.c.indexOf("food_report") !== -1 || feature.c.indexOf("region") !== -1 || feature.c.indexOf("bea") !== -1 || feature.c.indexOf("cbsa") !== -1 || feature.c.indexOf("cbsa") !== -1 || feature.c.indexOf("ers10") !== -1 || feature.c.indexOf("opportunityzones") !== -1 || feature.c.indexOf("pea10") !== -1 || feature.c.indexOf("tp10") !== -1 || feature.c.indexOf("tribal") !== -1 || feature.c.indexOf("schools_ccd") !== -1 || feature.c.indexOf("consumermarket") !== -1 || feature.c.indexOf("hsa") !== -1 || feature.c.indexOf("hrr") !== -1 || feature.c.indexOf("subbasin") !== -1) {
      var colorid = parseFloat(feature.get('colorid'));
      var fillColor = '';
      fillColor = '#715b5b';
      if (colorid == 0)
        fillColor = 'rgba(185, 103, 95, 0.5)';
      else if (colorid == 1)
        fillColor = 'rgba(209, 144, 46, 0.5)';
      else if (colorid == 2)
        fillColor = 'rgba(172, 156, 109, 0.5)';
      else if (colorid == 3)
        fillColor = 'rgba(87, 119, 0, 0.5)';
      else if (colorid == 4)
        fillColor = 'rgba(103, 125, 144, 0.5)';
      else if (colorid == 5)
        fillColor = 'rgba(223, 168, 140, 0.5)';
      var boundaryStyle = new ol.style.Style({
        fill: new ol.style.Fill({
          color: fillColor
        }),
        stroke: new ol.style.Stroke({
          color: '#000',
          width: strokeWidth,
          lineDash: dashStyle
        })
      });
    } else {
      var strokecolor = color;
      if (feature.c.indexOf("subwatershed") !== -1) {
        strokecolor = 'rgba(255, 255, 255, 0)';
      }
      var boundaryStyle = new ol.style.Style({
        stroke: new ol.style.Stroke({
          color: strokecolor,
          width: strokeWidth,
          lineDash: dashStyle
        })
      });
    }
  } else {
    var strokecolor = color;
    if (feature.c.indexOf("subwatershed") !== -1) {
      strokecolor = 'rgba(255, 255, 255, 0)';
    }
    var boundaryStyle = new ol.style.Style({
      stroke: new ol.style.Stroke({
        color: strokecolor,
        width: strokeWidth,
        lineDash: dashStyle
      })
    });
  }


  var styleWithLabel = [labelStyle, boundaryStyle];
  var styleWithoutLabel = [boundaryStyle];

  if (hideLabels)
    return styleWithoutLabel;

  var text = "";
  if (feature.c.indexOf("laucnty") !== -1)
    text = feature.get('laus_code');
  else if (feature.c.indexOf("subbasin") !== -1 || feature.c.indexOf("subwatershed") !== -1 || feature.c.indexOf("watershedregion") !== -1 || feature.c.indexOf("watershedsubregion") !== -1)
    text = "HUC-" + feature.get('huc');
  else if (feature.c.indexOf("cbsa") !== -1 || feature.c.indexOf("ers") !== -1 || feature.c.indexOf("bea") !== -1 || feature.c.indexOf("tp10") !== -1 || feature.c.indexOf("pea10") !== -1)
    text = feature.get('lm_code');
  else if (feature.c.indexOf("opportunityzones") !== -1)
    text = feature.get('geoid10');
  else if (feature.c.indexOf("schools_ccd") !== -1)
    text = feature.get('nces_distr');
  else if (feature.c.indexOf("laucnty") !== -1)
    text = feature.get('laus_code');
  else if (feature.c.indexOf("str") !== -1)
    text = feature.get('township_new') + feature.get('township_dir') + ' ' + feature.get('range_new') + feature.get('range_dir');
  else
    text = feature.get('name');
  var width = textMeasureContext.measureText(text).width;
  var geometry = feature.getGeometry();
  if (geometry.getType() == 'MultiPolygon') {
    geometry = geometry.getPolygons().sort(sortByWidth)[0];
  }
  var extentWidth = ol.extent.getWidth(geometry.getExtent());
  if (extentWidth / resolution > width) {
    var canvas = textCache[text] = document.createElement('CANVAS');
    canvas.width = width * pixelRatio;
    canvas.height = height * pixelRatio;
    var context = canvas.getContext('2d');
    context.scale(pixelRatio, pixelRatio);
    setStyle(context);
    context.strokeText(text, 0, 0);
    context.fillText(text, 0, 0);
    labelStyle.setGeometry(geometry.getInteriorPoint());
    return styleWithLabel;
  } else {
    return styleWithoutLabel;
  }
}

function getStyle(feature, resolution) {
  try {
    var color, dashStyle, strokeWidth = 4,
      fontSize = "20px";
    if (feature.S.gid.indexOf("srch") !== -1) {
      var colorid = parseFloat(feature.get('colorid'));
      dashStyle = [0, 0];
      color = "#000";
      var fillColor = '';
      fillColor = "#715b5b";
      if (colorid == 0)
        fillColor = 'rgba(185, 103, 95, 0.8)';
      else if (colorid == 1)
        fillColor = 'rgba(209, 144, 46, 0.8)';
      else if (colorid == 2)
        fillColor = 'rgba(172, 156, 109, 0.8)';
      else if (colorid == 3)
        fillColor = 'rgba(87, 119, 0, 0.8)';
      else if (colorid == 4)
        fillColor = 'rgba(103, 125, 144, 0.8)';
      else if (colorid == 5)
        fillColor = 'rgba(223, 168, 140, 0.8)';
      fontSize = "17px";
    } else {
      dashStyle = [15, 7];
      color = "#9f0404";
      fillColor = "rgba(82, 82, 82, 0.35)";
      strokeWidth = 3
    }
  } catch (e) {}

  function setStyle(context) {
    context.font = fontSize + " 'Lato'";
    context.fillStyle = color;
    context.strokeStyle = color;
    context.textBaseline = 'hanging';
    context.textAlign = 'start';
  }
  var textMeasureContext = document.createElement('CANVAS').getContext('2d');
  setStyle(textMeasureContext);

  var height = textMeasureContext.measureText('WI').width;

  function createLabel(canvas, text, coord) {
    var halfWidth = canvas.width / 2;
    var halfHeight = canvas.height / 2;
    var bounds = {
      bottomLeft: [Math.round(coord[0] - halfWidth), Math.round(coord[1] - halfHeight)],
      topRight: [Math.round(coord[0] + halfWidth), Math.round(coord[1] + halfHeight)]
    };
    labelEngine.ingestLabel(bounds, coord.toString(), 1, canvas, text, false);
  }

  function sortByWidth(a, b) {
    return ol.extent.getWidth(b.getExtent()) - ol.extent.getWidth(a.getExtent());
  }
  var text = feature.S.b2_name;
  var labelStyle = new ol.style.Style({
    renderer: function(coords, state) {
      createLabel(textCacheOverlay[text], text, coords);
    }
  });
  var boundaryStyle = new ol.style.Style({
    fill: new ol.style.Fill({
      color: fillColor
    }),
    stroke: new ol.style.Stroke({
      color: color,
      width: strokeWidth,
      lineDash: dashStyle
    })
  });

  var styleWithLabel = [labelStyle, boundaryStyle];
  var styleWithoutLabel = [boundaryStyle];

  if ((feature.S.gid.indexOf("srch") !== -1)) {
    if (hideLabels)
      return styleWithoutLabel;
    var width = textMeasureContext.measureText(text).width;
    var geometry = feature.getGeometry();
    if (geometry.getType() == 'MultiPolygon') {
      geometry = geometry.getPolygons().sort(sortByWidth)[0];
    }
    var extentWidth = ol.extent.getWidth(geometry.getExtent());
    if (extentWidth / resolution > width) {
      if (!(text in textCacheOverlay)) {
        var canvas = textCacheOverlay[text] = document.createElement('CANVAS');
        canvas.width = width * pixelRatio;
        canvas.height = height * pixelRatio;
        var context = canvas.getContext('2d');
        context.scale(pixelRatio, pixelRatio);
        setStyle(context);
        context.strokeText(text, 0, 0);
        context.fillText(text, 0, 0);
      }
      labelStyle.setGeometry(geometry.getInteriorPoint());
      return styleWithLabel;
    } else {
      return styleWithoutLabel;
    }
  } else {
    if (hideOverlayLabels)
      return styleWithoutLabel;
    var width = textMeasureContext.measureText(text).width;
    var geometry = feature.getGeometry();
    if (geometry.getType() == 'MultiPolygon') {
      geometry = geometry.getPolygons().sort(sortByWidth)[0];
    }
    var extentWidth = ol.extent.getWidth(geometry.getExtent());
    if (extentWidth / resolution > width) {
      if (!(text in textCacheOverlay)) {
        var canvas = textCacheOverlay[text] = document.createElement('CANVAS');
        canvas.width = width * pixelRatio;
        canvas.height = height * pixelRatio;
        var context = canvas.getContext('2d');
        context.scale(pixelRatio, pixelRatio);
        setStyle(context);
        context.strokeText(text, 0, 0);
        context.fillText(text, 0, 0);
      }
      labelStyle.setGeometry(geometry.getInteriorPoint());
      return styleWithLabel;
    } else {
      return styleWithoutLabel;
    }
  }
}
$("#searchBarZip").on('keypress', function(e) {
  $("#searchBarZip").css('border', '1px solid rgba(38,166,154,0.8)');
  toastr.clear();
});
$("#searchBarTracts").on('keypress', function(e) {
  $("#searchBarTracts").css('border', '1px solid rgba(38,166,154,0.8)');
  toastr.clear();
});

$("#searchBarCounties").on('keypress', function(e) {
  $("#searchBarCounties").css('border', '1px solid rgba(38,166,154,0.8)');
  toastr.clear();
});

$(".close, .cls").on("click", function() {
  $('#myModal').removeClass('show');
  $('#myModal').addClass('fade');
});

$(".showReport, .showRatioReport").on("click", function() {
  $('#myModal').removeClass('fade');
  $('#myModal').addClass('show');
});

$(".close2").on("click", function() {
  $('.modal').removeClass('show');
  $('.modal').addClass('fade');
});

function isNumber(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode == 59)
    return true;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}

$('#radioBtn a').on('click', function() {
  var sel = $(this).data('title');
  var tog = $(this).data('toggle');

  $('#' + tog).prop('value', sel);

  $('a[data-toggle="' + tog + '"]').not('[data-title="' + sel + '"]').removeClass('active').addClass('notActive');
  $('a[data-toggle="' + tog + '"][data-title="' + sel + '"]').removeClass('notActive').addClass('active');
  if (sel == "Change") {
    $("#divCrosswalk").hide();
    $("#divRatio").hide();
    $("#divChanges").show();
  } else if (sel == "Crosswalk") {
    removeLayersRatios();
    $("#divChanges").hide();
    $("#divRatio").hide();
    $("#divCrosswalk").show();
  } else if (sel == "Ratio") {
    crosswalkLayers['States'].setVisible(false);
    removeLayers();
    $("#divChanges").hide();
    $("#divCrosswalk").hide();
    $("#divRatio").show();
  }
});

$('#resizeDiv')
  .draggable({
    handle: '.modal-header'
  })
  .resizable({
    minHeight: 300,
    minWidth: 300,
    resize: function(event, ui) {
      var h = $('#resizeDiv').height();
      h = h - 80;
      $('.modal-body').css("height", h);
    }
  });

$('body').on('click', 'a.toggle-vis', function(e) {
  e.preventDefault();
  var column = t.column($(this).attr('data-column'));
  column.visible(!column.visible());
});
