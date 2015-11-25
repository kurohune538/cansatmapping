
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

setTimeout 'changeViewPoint(0,3)', 500

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

# ラインをクリックしたときはinfoBoxを非表示にする
scene = viewer.scene
handler = new (Cesium.ScreenSpaceEventHandler)(viewer.canvas)
handler.setInputAction ((movement) ->
  childNodesLength = 0
  element = scene.pick(movement.position)
  if element
    setTimeout (->
      `var childNodesLength`
      iframeContents = $('iframe:first').contents().find('.cesium-infoBox-description')
      childNodesLength = iframeContents[0].childNodes.length
      if childNodesLength == 0
        $ ->
          $('.cesium-infoBox-visible').hide()
          return
        viewer.selectedEntity = undefined
      else
        $ ->
          $('.cesium-infoBox-visible').show()
          return
      return
    ), 1
  return
), Cesium.ScreenSpaceEventType.LEFT_CLICK


# ビューの切り替え
viewPointsArray = []

viewPoints = (_label, _lat, _lng, _heading, _pitch, _range) ->
  @label = _label
  @lat = _lat
  @lng = _lng
  @heading = _heading
  @pitch = _pitch
  @range = _range
  return

changeViewPoint = (num, delay) ->
  newLat = viewPointsArray[num].lat
  newLng = viewPointsArray[num].lng
  newHeading = Cesium.Math.toRadians(viewPointsArray[num].heading)
  newPitch = Cesium.Math.toRadians(viewPointsArray[num].pitch)
  newRange = viewPointsArray[num].range
  center = Cesium.Cartesian3.fromDegrees(newLng, newLat)
  boundingSphere = new (Cesium.BoundingSphere)(center, newRange)
  headingPitchRange = new (Cesium.HeadingPitchRange)(newHeading, newPitch, newRange)
  viewer.camera.constrainedAxis = Cesium.Cartesian3.UNIT_Z
  viewer.camera.flyToBoundingSphere boundingSphere,
    duration: delay
    offset: headingPitchRange
  return

viewPointsArray[0] = new viewPoints('JAPAN', 35.87501683, 138.8878458, -15, -40, 2200000)
viewPointsArray[1] = new viewPoints('USA', 34.9576309, -100.3646449, 0, -50, 6000000)
viewPointsArray[2] = new viewPoints('INDIA', 17.4346323, 78.8163729, 0, -45, 4000000)
viewPointsArray[3] = new viewPoints('AFRICA', 4.3192223, 19.8916924, 0, -75, 8000000)


