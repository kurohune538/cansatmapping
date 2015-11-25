var changeViewPoint, handler, loadCzml, loadJsonLine, options, scene, viewPoints, viewPointsArray, viewer;

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

viewPointsArray = [];

viewPoints = function(_label, _lat, _lng, _heading, _pitch, _range) {
  this.label = _label;
  this.lat = _lat;
  this.lng = _lng;
  this.heading = _heading;
  this.pitch = _pitch;
  this.range = _range;
};

changeViewPoint = function(num, delay) {
  var boundingSphere, center, headingPitchRange, newHeading, newLat, newLng, newPitch, newRange;
  newLat = viewPointsArray[num].lat;
  newLng = viewPointsArray[num].lng;
  newHeading = Cesium.Math.toRadians(viewPointsArray[num].heading);
  newPitch = Cesium.Math.toRadians(viewPointsArray[num].pitch);
  newRange = viewPointsArray[num].range;
  center = Cesium.Cartesian3.fromDegrees(newLng, newLat);
  boundingSphere = new Cesium.BoundingSphere(center, newRange);
  headingPitchRange = new Cesium.HeadingPitchRange(newHeading, newPitch, newRange);
  viewer.camera.constrainedAxis = Cesium.Cartesian3.UNIT_Z;
  viewer.camera.flyToBoundingSphere(boundingSphere, {
    duration: delay,
    offset: headingPitchRange
  });
};

viewPointsArray[0] = new viewPoints('JAPAN', 33.284693, 130.266907, 0, -85, 3500);

viewPointsArray[1] = new viewPoints('USA', 33.295993, 130.196842, 0, -89, 8000);

viewPointsArray[2] = new viewPoints('INDIA', 33.260708, 130.230819, 0, -85, 2500);

viewPointsArray[3] = new viewPoints('AFRICA', 33.251168, 130.119531, 0, -85, 96062);
