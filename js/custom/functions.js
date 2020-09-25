function Crosswalk(val, column) {
  if ($('#selGo' + val + '').val() != "none") {
    try {
      map.removeLayer(crswlkOverlay);
    } catch (e) {}
    try {
      $("#btnGo" + val + "").attr("disabled", "disabled");
    } catch (e) {}
    var str = $("#selGo" + val + "").val();
    var arr = str.split("Cr");
    var val2 = arr[1];
    var boundary1 = val;
    var boundary2 = val2;
    var displayText = datasetsArray[val2];
    var b1_id = reportID[boundary1];
    var b1_name = reportName[boundary1];
    var value = $("#searchBar" + boundary1 + "").val();
    var filter = '';
    if (boundary1 == "Counties" || boundary1 == "NeighbourCities") {
      var state = '';
      state = 'state';
      var values = value.split(";", 9);
      for (var i = 0; i < values.length; i++) {
        if (i > 0)
          filter += ' OR ';
        if (values[i].indexOf('-') > -1) {
          var array = values[i].split("-", 2);
          filter += '(b1.statefp = \'' + $.trim(array[1]) + '\' and b1.countyfp=\'' + $.trim(array[0]) + '\')';
        } else if (values[i].indexOf(',') > -1) {
          var array = values[i].split(",", 2);
          filter += '(LOWER(b1.name)=LOWER(\'' + $.trim(array[0]) + '\') and LOWER(b1.' + state + ')=LOWER(\'' + $.trim(array[1]) + '\'))';
        }
      }
    } else {
      if ($("#searchBar" + boundary1 + "").val().indexOf(';') !== -1) {
        var values = value.split(";", 9);
        filter = '(';
        for (var i = 0; i < values.length; i++) {
          if (i > 0)
            filter += ' OR ';
          filter += column + '=\'' + $.trim(values[i]) + '\'';
        }
        filter += ')';
      } else
        filter = column + '=\'' + $.trim(value) + '\'';
    }
    var crswlkSource = new ol.source.Vector({
      url: "server_scripts/intersectedBoundaries.php?boundary1=" + val + "&boundary2=" + boundary2 + "&b1_id=" + b1_id + "&b1_name=" + b1_name + "&filter=" + filter,
      format: new ol.format.GeoJSON(),
      strategy: ol.loadingstrategy.all
    });
    try {
      $("#btnGo" + boundary1 + "").attr("disabled", "disabled");
    } catch (e) {}
    crswlkOverlay = getBoundaryCrosswalks(crswlkSource, boundary1 + '-Tract')
    map.addLayer(crswlkOverlay);
    var listenerKey = crswlkSource.on('change', function(e) {
      if (crswlkSource.getState() == 'ready') {
        ol.Observable.unByKey(listenerKey);
        var features = crswlkSource.getFeatures();
        if (features.length > 0) {
          var extent = crswlkSource.getExtent();
          map.getView().fit(extent, {
            duration: 1000
          });
          if ($("#searchBar" + val + "").val().indexOf(';') !== -1) {
            var table = '<div style="margin-bottom: 8px; color: rgba(58, 131, 124, 0.81); margin-bottom: 10px;"><b>Toggle column: </b><a class="toggle-vis" data-column="1">' + boundary1 + ' ID</a> - <a class="toggle-vis" data-column="2">' + displayText + ' ID</a> - <a class="toggle-vis" data-column="3">' + displayText + ' Name</a> - ';

            if (features[0].S.stusps != '')
              table += '<a class="toggle-vis" data-column="4">State</a> - ';
            table += '<a class="toggle-vis" data-column="5">Percent of ' + boundary1 + '</a> - <a class="toggle-vis" data-column="6">Percent within ' + boundary1 + '</a></div>';
            table += '<table style="width: 100%;" border="1" class="table table-bordered tablesorter"><thead><tr><th>Rank</th><th>' + boundary1 + ' ID</th><th>' + displayText + ' ID</th><th>' + displayText + ' Name</th>';
            if (features[0].S.stusps != '')
              table += '<th>State</th>';
            table += '<th>Percent of ' + boundary1 + '</th><th>Percent within ' + boundary1 + '</th></tr></thead>';
          } else {
            var table = '<div style="margin-bottom: 8px; color: rgba(58, 131, 124, 0.81); margin-bottom: 10px;"><b>Toggle column: </b><a class="toggle-vis" data-column="1">' + displayText + ' ID</a> -<a class="toggle-vis" data-column="2">' + displayText + ' Name</a> - ';
            if (features[0].S.stusps != '')
              table += '<a class="toggle-vis" data-column="3">State</a> - ';
            table += '<a class="toggle-vis" data-column="4">Percent of ' + boundary1 + '</a> - <a class="toggle-vis" data-column="5">Percent within ' + boundary1 + '</a></div>';
            table += '<table style="width: 100%;" border="1" class="table table-bordered tablesorter"><thead><tr><th>Rank</th><th>' + displayText + ' ID</th><th>' + displayText + ' Name</th>';
            if (features[0].S.stusps != '')
              table += '<th>State</th>';
            table += '<th>Percent of ' + boundary1 + '</th><th>Percent within ' + boundary1 + '</th></tr></thead>';
          }
          table += '<tbody><tr>';
          var options = '';
          var array = [];
          countArray = [];
          for (var i = 0; i < features.length; i++) {
            table += '<td></td>';
            if ($("#searchBar" + boundary1 + "").val().indexOf(';') !== -1) {
              table += '<td>' + features[i].S.b1_id + '</td>';
              if (array.indexOf(features[i].S.b1_id) === -1) {
                countArray[array.length] = 0;
                options += '<option value="' + array.length + '">' + features[i].S.b1_id + '</option>';
                array.push(features[i].S.b1_id);
              }
              countArray[array.indexOf(features[i].S.b1_id)] = countArray[array.indexOf(features[i].S.b1_id)] + parseFloat(features[i].S.perc_intersection);
            }
            table += '<td>' + features[i].S.b2_id + '</td>';
            table += '<td>' + features[i].S.b2_name + '</td>';
            col = 3;
            if (features[0].S.stusps != '') {
              table += '<td>' + features[i].S.stusps + '</td>';
              col = 4;
            }
            table += '<td>' + parseFloat(features[i].S.area_covered).toFixed(4) + '</td>';
            table += '<td>' + Math.round(features[i].S.perc_intersection) + '</td>';
            table += '</tr>';
          }
          table += '</tbody>';
          if ($("#searchBar" + boundary1 + "").val().indexOf(';') !== -1) {
            col = col + 1;
            table += '<tfoot><tr><th  colspan="' + col + '" style="text-align:right" ><select id="dtFilter"  onchange="filterTable()" class="selectGroup"><option value="all" selected>All</option>' + options + '</select></th>';
          } else {
            table += '<tfoot><tr><th colspan="' + col + '" style="text-align:right"></th><th></th>';
          }
          if ($("#searchBar" + boundary1 + "").val().indexOf(';') !== -1)
            table += '<th class="sumDiv"></th>';
          table += '<th></th></tr></tfoot>';
          table += '</table>';
          $("#table").html('');
          $("#table").html(table);
          $("#table").show();
          paginateTable();
          $("#btnGo" + boundary1 + "").removeAttr('disabled');
        } else {
          toastr.error("No Data Found!");
        }
      }
    });
  }
}

function getRatios(geoid) {
  // console.log(geoid);
}

function removeLayers() {
  try {
    map.removeLayer(sLayer);
    map.removeLayer(crswlkOverlay);
    for (let [key, value] of Object.entries(crosswalkLayers)) {
      crosswalkLayers[key]
    }
  } catch (e) {console.log(e);}
}

function hideAndClearAll() {
  $(".divGoZip").hide();
  $(".divGoSchools_CCD").hide();
  $(".divGoConsumerMarket").hide();
  $(".divGoHRR").hide();
  $(".divGoHSA").hide();
  $(".divGoTracts").hide();
  $(".divGoCounties").hide();
  $(".divGoRegions").hide();
  $(".divGoState").hide();
  $(".divGoDistricts").hide();
  $(".divGoCities").hide();
  $(".divGoMSAs").hide();
  $(".divGoNeighbourCities").hide();
  $(".divGoLauCnty").hide();
  $(".divGoFood_Report").hide();
  $(".divGoBEA10").hide();
  $(".divGoCBSA10").hide();
  $(".divGoERS10").hide();
  $(".divGoERS10Rep").hide();
  $(".divGoOpportunityZones").hide();
  $(".divGoPEA10").hide();
  $(".divGoTP10").hide();
  $(".divGoTP10METRO").hide();
  $(".divGoTP10MICRO").hide();
  $(".divGoTribalLand").hide();

  $('#searchBarZip').val("");
  $('#searchBarSchools_CCD').val("");
  $('#searchBarConsumerMarket').val("");
  $('#searchBarHSA').val("");
  $('#searchBarHRR').val("");
  $('#searchBarTracts').val("");
  $('#searchBarCounties').val("");
  $('#searchBarRegion').val("");
  $('#searchBarState').val("");
  $('#searchBarDistricts').val("");
  $('#searchBarCities').val("");
  $('#searchBarMSAs').val("");
  $('#searchBarNeighbourCities').val("");
  $('#searchBarLauCnty').val("");
  $('#searchBarFood_Report').val("");
  $('#searchBarBEA10').val("");
  $('#searchBarCBSA10').val("");
  $('#searchBarERS10').val("");
  $('#searchBarERS10Rep').val("");
  $('#searchBarOpportunityZones').val("");
  $('#searchBarPEA10').val("");
  $('#searchBarTP10').val("");
  $('#searchBarTP10METRO').val("");
  $('#searchBarTP10MICRO').val("");
  $('#searchBarTribalLand').val("");
}

function toggleSearchDivs(val) {
  $(".divSearch").hide();
  // hide all
  $(".srchTracts").hide();
  $(".srchCounties").hide();
  $(".srchLauCnty").hide();
  $(".srchFood_Report").hide();
  $(".srchRegion").hide();
  $(".srchDistricts").hide();
  $(".srchCities").hide();
  $(".srchMSAs").hide();
  $(".srchNeighbourCities").hide();
  $(".srchState").hide();
  $(".srchBEA10").hide();
  $(".srchCBSA10").hide();
  $(".srchERS10").hide();
  $(".srchERS10Rep").hide();
  $(".srchMSAs_Grainger").hide();
  $(".srchOpportunityZones").hide();
  $(".srchPEA10").hide();
  $(".srchTP10").hide();
  $(".srchTP10METRO").hide();
  $(".srchTP10MICRO").hide();
  $(".srchTribalLand").hide();
  $(".srchZip").hide();
  // show
  $("." + val + "").show();
}

function toggleBaseLayers(val) {
  crosswalkLayers['Region'].setVisible(false);
  crosswalkLayers['BEA10'].setVisible(false);
  crosswalkLayers['CBSA10'].setVisible(false);
  crosswalkLayers['States'].setVisible(false);
  crosswalkLayers['ERS10'].setVisible(false);
  crosswalkLayers['ERS10Rep'].setVisible(false);
  crosswalkLayers['MSAs_Grainger'].setVisible(false);
  // // baseOpportunityZones.setVisible(false);
  crosswalkLayers['PEA10'].setVisible(false);
  crosswalkLayers['TP10'].setVisible(false);
  crosswalkLayers['TP10METRO'].setVisible(false);
  crosswalkLayers['TP10MICRO'].setVisible(false);
  crosswalkLayers['WaterShedRegions'].setVisible(false);
  crosswalkLayers['TribalLand'].setVisible(false);
  // baseSTR_Geocoded_All_New.setVisible(false);
  // show
  val.setVisible(true);
  val.setStyle(getBoundaryAndLabelStyle);
}

function toggleOverlay(value) {
  if (value == "on") {
    hideOverlayLabels = false;
  } else if (value == "off") {
    hideOverlayLabels = true;
  }
  try {
    crswlkOverlay.setStyle(getStyle);
  } catch (e) {}
}

function changeLabels(value) {
  if (value == "on") {
    hideLabels = false;
  } else if (value == "off") {
    hideLabels = true;
  }
  crosswalkLayers['States'].setStyle(getBoundaryAndLabelStyle);
  crosswalkLayers['Region'].setStyle(getBoundaryAndLabelStyle);

  var code = $("#selCode").val();

  if ($("#searchBar" + code + "").val() != '') {
    Ghosted = "On";
    try {
      crosswalkLayers[code].setStyle(getBoundaryAndLabelStyle);
    } catch (e) {}
  } else {
    Ghosted = "Off";
    try {
      crosswalkLayers[code].setStyle(getBoundaryAndLabelStyle);
    } catch (e) {}
  }
  crosswalkLayers['SubWatershed'].setVisible(true);
  try {
    sLayer.setStyle(getStyle);
  } catch (e) {}
  try {
    crswlkOverlay.setStyle(getStyle);
  } catch (e) {}
}

function btnSearch(val, code) {
  Ghosted = "On";
  try {
    crosswalkLayers[val].setStyle(getBoundaryAndLabelStyle);
  } catch (e) {}
  $('#selGo' + val + '').prop('selectedIndex', 0);
  $('#selGo' + val + '').trigger("change");
  $('#selGo' + val + '').selectpicker('refresh');
  if ($("#searchBar" + val + "").val() != '') {
    if ($("#searchBar" + val + "").val() != '') {
      try {
        map.removeLayer(sLayer);
      } catch (e) {}
      var value = $("#searchBar" + val + "").val();
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
              states.setVisible(false);
            } catch (e) {}
            $('.divGo' + val + '').show();
            if (features.length > 1) {
              if (features[0].S.stusps != '')
                $("#myModalLabel").html("Crosswalk Report for " + val + " of " + features[0].S.stusps + " state");
              else
                $("#myModalLabel").html("Crosswalk Report for " + val);
            } else {
              if (features[0].S.stusps != '')
                $("#myModalLabel").html("Crosswalk Report for " + features[0].S.b2_name + ", " + features[0].S.stusps + "");
              else
                $("#myModalLabel").html("Crosswalk Report for " + features[0].S.b2_name);
            }
          }
        } else {
          toastr.error("No " + val + " Code Found!");
        }
      });
    } else {
      toastr.error("Please use the given search format!");
    }
  } else {
    toastr.error("Please enter the County Code");
    $("#searchBar" + val + "").css('border', '1px solid #d50000');
  }
}

function changeBaseMap() {
  bingAerial.setVisible(false);
  terrainStamen.setVisible(false);
  bingRoads.setVisible(false);
  osmLight.setVisible(false);

  switch ($("#selBaseMap").val()) {
    case 'aerial':
      bingAerial.setVisible(true);
      break;
    case 'terrain':
      terrainStamen.setVisible(true);
      break;
    case 'streets':
      bingRoads.setVisible(true);
      break;
    case 'osm':
      osmLight.setVisible(true);
      break;
    default:
      break;
  }
}

function paginateTable() {
  col = $(".tablesorter > tbody > tr:first > td").length;
  col = col - 2;
  t = $('.tablesorter').DataTable({
    "autoWidth": true,
    "columnDefs": [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
    "order": [
      [1, 'asc']
    ],
    "footerCallback": function(row, data, start, end, display) {
      var api = this.api(),
        data;

      // Remove the formatting to get integer data for summation
      var intVal = function(i) {
        return typeof i === 'string' ?
          i.replace(/[\$,]/g, '') * 1 :
          typeof i === 'number' ?
          i : 0;
      };

      // Total over all pages
      total = api
        .column(col)
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      // Total over this page
      pageTotal = api
        .column(col, {
          page: 'current'
        })
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);
      var sumCol4Filtered = display.map(el => data[el][col]).reduce((a, b) => intVal(a) + intVal(b), 0);
      // Update footer
      if ($("#searchBarZip").val().indexOf(';') !== -1 || $("#searchBarTracts").val().indexOf(';') !== -1 || $("#searchBarCounties").val().indexOf(';') !== -1) {
        $(api.column(col).footer()).html(
          Math.round(pageTotal) + '% (' + Math.round(sumCol4Filtered) + '% total)'
        );
      } else {
        $(api.column(col).footer()).html(
          Math.round(pageTotal) + '% ( ' + Math.round(total) + '% total)'
        );
      }
    }
  });

  t.on('order.dt search.dt', function() {
    t.column(0, {
      search: 'applied',
      order: 'applied'
    }).nodes().each(function(cell, i) {
      cell.innerHTML = i + 1;
    });
  }).draw();
  if ($("#searchBarZip").val().indexOf(';') !== -1 || $("#searchBarTracts").val().indexOf(';') !== -1 || $("#searchBarCounties").val().indexOf(';') !== -1)
    $(".sumDiv").text("");
}

function filterTable() {
  if ($("#dtFilter option:selected").text() == 'All') {
    $(".tablesorter").dataTable().fnFilter('');
    $(".sumDiv").text("");
  } else {
    $(".tablesorter").dataTable().fnFilter($("#dtFilter option:selected").text());
  }
}

function tableToJson(table) {
  var data = [];

  // first row needs to be headers
  var headers = [];
  for (var i = 0; i < table.rows[0].cells.length; i++) {
    headers[i] = table.rows[0].cells[i].innerHTML.toLowerCase().replace(/ /gi, '');
  }

  // go through cells
  for (var i = 1; i < table.rows.length; i++) {

    var tableRow = table.rows[i];
    var rowData = {};

    for (var j = 0; j < tableRow.cells.length; j++) {

      rowData[headers[j]] = tableRow.cells[j].innerHTML;

    }

    data.push(rowData);
  }

  return data;
}
String.prototype.capitalize = function() {
  return this.charAt(0).toUpperCase() + this.slice(1);
}
