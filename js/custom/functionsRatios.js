function removeLayersRatios(val) {
  if (val != 'overlays') {
    try {
      map.removeLayer(ratioStates);
    } catch (e) {}
  }
  try {
    map.removeLayer(ratioCBSA10);
  } catch (e) {}
  try {
    map.removeLayer(ratioZip);
  } catch (e) {}
  try {
    map.removeLayer(ratioTracts);
  } catch (e) {}
  try {
    map.removeLayer(ratioCounties);
  } catch (e) {}
  try {
    map.removeLayer(ratioOverlay);
  } catch (e) {}
  try {
    map.removeLayer(sLayer);
  } catch (e) {}
}

function searchInputBoundary(val, code) {
  try {
    map.removeLayer(sLayer);
  } catch (e) {}
  try {
    map.removeLayer(ratioOverlay);
  } catch (e) {}
  Ghosted = "On";
  try {
    eval('ratio' + val).setStyle(getBoundaryAndLabelStyle);
  } catch (e) {}
  if ($("#searchBarRatios").val() != '') {
    try {
      map.removeLayer(sLayer);
    } catch (e) {}
    var value = $("#searchBarRatios").val();
    var values = value.split(";", 9);
    var filter = '(';
    for (var i = 0; i < values.length; i++) {
      if (i > 0)
        filter += ' OR ';
      if (val == 'NeighbourCities' || val == 'Counties') {
        if (values[i].indexOf(',') > -1) {
          var array = values[i].split(",", 2);
          filter += '(LOWER(a.' + code + ')=LOWER(\'' + $.trim(array[0]) + '\') and LOWER(a.state)=LOWER(\'' + $.trim(array[1]) + '\'))';
        }
      } else
        filter += 'a.' + code + '=\'' + $.trim(values[i]) + '\'';
    }
    filter += ')';
    if (val === 'Counties_ratio')
      val = 'Counties';
    sLayerSource = new ol.source.Vector({
      url: "server_scripts/searchBoundaries.php?cond=" + val + "&filter=" + filter,
      format: new ol.format.GeoJSON(),
      strategy: ol.loadingstrategy.all
    });
    sLayer = getBoundaryCrosswalks(sLayerSource, val)
    try {
      map.addLayer(sLayer);
    } catch (e) {}
    var listenerKey = sLayerSource.on('change', function(e) {
      if (sLayerSource.getFeatures().length > 0) {
        if (sLayerSource.getState() == 'ready') {
          ol.Observable.unByKey(listenerKey);
          var features = sLayerSource.getFeatures();
          var extent = sLayerSource.getExtent();
          map.getView().fit(extent, {
            duration: 1000
          });
          try {
            $("#btnGetRatios").removeAttr('disabled');
          } catch (e) {}
          if (features.length > 0) {
            $("#myModalLabel").html("Ratio Report for " + value + " of " + $("#selRatio option:selected").html());
          }
        }
      } else {
        toastr.error("No " + val + " Code Found!");
      }
    });
  } else {
    toastr.error("Please enter the County Code");
    $("#searchBar" + val + "").css('border', '1px solid #d50000');
  }
}

function btnGoRatios() {
  try {
    map.removeLayer(ratioOverlay);
  } catch (e) {}
  var boundaryRatio1 = $("#selRatio").val().split('-')[0];
  var boundaryRatio2 = $("#selRatio").val().split('-')[1];
  var code = $("#searchBarRatios").val();
  var layerRatio = boundaryRatio1 + '_' + boundaryRatio2;
  layerRatio = layerRatio.replace('10', '');
  var value = $("#searchBarRatios").val();
  var ratioSource = new ol.source.Vector({
    url: "server_scripts/ratioBoundaries.php?table=" + layerRatio + "&boundary2=" + boundaryRatio2 + "&boundary1=" + boundaryRatio1 + "&value=" + value,
    format: new ol.format.GeoJSON(),
    strategy: ol.loadingstrategy.all
  });
  ratioOverlay = getBoundaryCrosswalks(ratioSource, '' + layerRatio + '-Ratio')
  map.addLayer(ratioOverlay);
  var listenerKey = ratioSource.on('change', function(e) {
    if (ratioSource.getState() == 'ready') {
      ol.Observable.unByKey(listenerKey);
      var features = ratioSource.getFeatures();
      if (features.length > 0) {
        var extent = ratioSource.getExtent();
        map.getView().fit(extent, {
          duration: 1000
        });
        var table = '<div style="margin-bottom: 8px; color: rgba(58, 131, 124, 0.81); margin-bottom: 10px;">';
        table += '<b>Toggle column: </b>';
        table += '<a class="toggle-vis" data-column="1">' + boundaryRatio2 + '</a> - ';
        table += '<a class="toggle-vis" data-column="2">Res Ratio</a> - ';
        table += '<a class="toggle-vis" data-column="3">Bus Ratio</a> - ';
        table += '<a class="toggle-vis" data-column="4">Oth Ratio</a> - ';
        table += '<a class="toggle-vis" data-column="5">Tot Ratio</a>';
        table += '</div>';
        table += '<table style="width: 100%;" border="1" class="table table-bordered tablesorter">';
        table += '<thead><tr>';
        table += '<th>Rank</th>';
        table += '<th>' + boundaryRatio2 + '</th>';
        table += '<th>Res Ratio</th>';
        table += '<th>Bus Ratio</th>';
        table += '<th>Oth Ratio</th>';
        table += '<th>Tot Ratio</th>';
        table += '</tr></thead>';
        table += '<tbody>';
        for (var i = 0; i < features.length; i++) {
          table += '<tr><td></td>';
          table += '<td>' + features[i].S.name + '</td>';
          table += '<td>' + features[i].S.res_ratio + '</td>';
          table += '<td>' + features[i].S.bus_ratio + '</td>';
          table += '<td>' + features[i].S.bus_ratio + '</td>';
          table += '<td>' + features[i].S.tot_ratio + '</td>';
          table += '</tr>';
        }
        table += '</tbody>';
        table += '</table>';
        $("#table").html(table);
        $("#table").show();
        paginateTable();
        $("#btnGoRatio").removeAttr('disabled');
      }
    }
  });
}
