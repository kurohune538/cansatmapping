var changeSerchedViewPoint, changeViewPoint, geocode, handler, loadCzml, loadJsonLine, options, scene, viewPoints, viewPointsArray, viewer;

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

setTimeout('changeViewPoint(0,3)', 500);

setTimeout('loadCzml()', 3400);

setTimeout('loadJsonLine("../czml/polyline.json")', 3400);

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
    var ghaterLine, i, lineColor, positionsCartesian3, underLine;
    for (i in json) {
      ghaterLine = json[i].ghaterLine;
      lineColor = Cesium.Color.fromBytes(30, 188, 149, 70);
      positionsCartesian3 = Cesium.Cartesian3.fromDegreesArrayHeights(ghaterLine);
      viewer.entities.add({
        name: 'line',
        polyline: {
          positions: positionsCartesian3,
          width: 2,
          material: lineColor
        }
      });
      underLine = json[i].underLine;
      lineColor = Cesium.Color.fromBytes(255, 255, 255, 100);
      positionsCartesian3 = Cesium.Cartesian3.fromDegreesArrayHeights(underLine);
      viewer.entities.add({
        name: 'line2',
        polyline: {
          positions: positionsCartesian3,
          width: 1,
          material: lineColor
        }
      });
    }
  });
};

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

viewPointsArray[0] = new viewPoints('JAPAN', 35.87501683, 138.8878458, -15, -40, 2200000);

viewPointsArray[1] = new viewPoints('USA', 34.9576309, -100.3646449, 0, -50, 6000000);

viewPointsArray[2] = new viewPoints('INDIA', 17.4346323, 78.8163729, 0, -45, 4000000);

viewPointsArray[3] = new viewPoints('AFRICA', 4.3192223, 19.8916924, 0, -75, 8000000);

geocode = function() {
  var geocoder, input;
  input = document.getElementById('inputtext').value;
  geocoder = new google.maps.Geocoder;
  geocoder.geocode({
    'address': input,
    'language': 'ja'
  }, function(results, status) {
    var lat, lon;
    if (status === google.maps.GeocoderStatus.OK) {
      lat = results[0].geometry.location.lat();
      lon = results[0].geometry.location.lng();
      changeSerchedViewPoint(lat, lon);
    }
  });
};

changeSerchedViewPoint = function(lat, lon) {
  var boundingSphere, center, headingPitchRange, newHeading, newLat, newLng, newPitch, newRange;
  newLat = lat;
  newLng = lon;
  newHeading = Cesium.Math.toRadians(0);
  newPitch = Cesium.Math.toRadians(-45);
  newRange = 250000;
  center = Cesium.Cartesian3.fromDegrees(newLng, newLat);
  boundingSphere = new Cesium.BoundingSphere(center, newRange);
  headingPitchRange = new Cesium.HeadingPitchRange(newHeading, newPitch, newRange);
  viewer.camera.constrainedAxis = Cesium.Cartesian3.UNIT_Z;
  viewer.camera.flyToBoundingSphere(boundingSphere, {
    duration: 3,
    offset: headingPitchRange
  });
};
