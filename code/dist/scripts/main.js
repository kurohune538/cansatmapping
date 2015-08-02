var options, viewer;

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
