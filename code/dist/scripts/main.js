var handler, loadCzml, loadJsonLine, options, scene, viewer;

options = {
  baseLayerPicker: false,
  fullscreenButton: false,
  homeButton: false,
  sceneModePicker: false,
  navigationHelpButton: false,
  geocoder: false,
  animation: false,
  timeline: false,
  imageryProvider: new Cesium.ArcGisMapServerImageryProvider({
    url: '//server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer',
    enablePickFeatures: false
  })
};

viewer = new Cesium.Viewer('cesiumContainer', options);

viewer.camera.setView({
  position: Cesium.Cartesian3.fromDegrees(138.45, 34.39, 20000000),
  roll: 0.0
});

setTimeout('loadCzml()', 500);

loadCzml = function() {
  var promise;
  promise = Cesium.CzmlDataSource.load('./czml/cansat.czml');
  promise.then(function(dataSource) {
    viewer.dataSources.add(dataSource);
  }).otherwise(function(error) {
    alert('CZMLデータが読み込めません');
  });
};

loadJsonLine = function(fileName) {
  var jsonFile;
  jsonFile = fileName;
  $.getJSON(jsonFile, function(json) {
    var i, lineColor, positions, positionsCartesian3;
    for (i in json) {
      positions = json[i].positions;
      lineColor = Cesium.Color.fromBytes(30, 188, 149, 70);
      positionsCartesian3 = Cesium.Cartesian3.fromDegreesArrayHeights(positions);
      viewer.entities.add({
        name: 'line',
        polyline: {
          positions: positionsCartesian3,
          width: 2,
          material: lineColor
        }
      });
    }
  });
};

loadJsonLine('../czml/polyline.json');

scene = viewer.scene;

handler = new Cesium.ScreenSpaceEventHandler(viewer.canvas);

handler.setInputAction((function(movement) {
  var childNodesLength, element;
  childNodesLength = 0;
  element = scene.pick(movement.position);
  if (element) {
    setTimeout((function() {
      var childNodesLength;
      var iframeContents;
      iframeContents = $('iframe:first').contents().find('.cesium-infoBox-description');
      childNodesLength = iframeContents[0].childNodes.length;
      if (childNodesLength === 0) {
        $(function() {
          $('.cesium-infoBox-visible').hide();
        });
        viewer.selectedEntity = void 0;
      } else {
        $(function() {
          $('.cesium-infoBox-visible').show();
        });
      }
    }), 1);
  }
}), Cesium.ScreenSpaceEventType.LEFT_CLICK);
