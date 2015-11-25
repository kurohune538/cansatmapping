
options = {
  baseLayerPicker: false
  fullscreenButton: false
  homeButton: false
  sceneModePicker: false
  navigationHelpButton: false
  geocoder: false
  animation: false
  timeline: false
  imageryProvider: new Cesium.ArcGisMapServerImageryProvider({
    url: '//server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer'
    enablePickFeatures: false
  })
}

viewer = new Cesium.Viewer('cesiumContainer', options)
viewer.camera.setView
  position: Cesium.Cartesian3.fromDegrees(138.45, 34.39, 20000000)
  roll: 0.0

setTimeout 'loadCzml()', 500
loadCzml = ->
  promise = Cesium.CzmlDataSource.load('./czml/cansat.czml')
  promise.then((dataSource) ->
    viewer.dataSources.add dataSource
    return
  ).otherwise (error) ->
    alert 'CZMLデータが読み込めません'
    return
  return

# ポリライン作成
loadJsonLine = (fileName) ->
  jsonFile = fileName
  $.getJSON jsonFile, (json) ->
    for i of json
      positions = json[i].positions
      lineColor = Cesium.Color.fromBytes(30, 188, 149, 70)
      positionsCartesian3 = Cesium.Cartesian3.fromDegreesArrayHeights(positions)
      viewer.entities.add
        name: 'line'
        polyline:
          positions: positionsCartesian3
          width: 2
          material: lineColor
    return
  return

loadJsonLine '../czml/polyline.json'
