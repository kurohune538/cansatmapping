var loadCzml, options, viewer;

options = {
  baseLayerPicker: false,
  fullscreenButton: false,
  homeButton: false,
  sceneModePicker: false,
  timeline: false,
  navigationHelpButton: false,
  geocoder: false,
  animation: false
};

viewer = new Cesium.Viewer('cesiumContainer', options);

viewer.camera.setView({
  position: Cesium.Cartesian3.fromDegrees(138.45, 34.39, 20000000),
  roll: 0.0
});

setTimeout('loadCzml()', 1000);

loadCzml = function() {
  var promise;
  promise = Cesium.CzmlDataSource.load('./czml/test.czml');
  promise.then(function(dataSource) {
    viewer.dataSources.add(dataSource);
  }).otherwise(function(error) {
    alert('KMLデータが読み込めません');
  });
};
