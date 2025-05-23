<!DOCTYPE html>
<html>
<head>
  <title>Awesome Markers Example: Basic</title>
  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="../dist/leaflet.awesome-markers.css">
  <!-- <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" /> -->
  <link rel="stylesheet" href="css/leaflet.css" />
  <!--[if lte IE 8]>
      <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.ie.css" />
  <![endif]-->
</head>
<body>
  <div id="map" style="width: 800px; height: 600px"></div>
  
  <!-- <script src="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js"></script> -->
  <script src="../js/leaflet.js"></script>
  <script src="../dist/leaflet.awesome-markers.js"></script>
  <script>
    var map = L.map('map').setView([51.932994,4.509373], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([51.941196,4.512291], {icon: L.AwesomeMarkers.icon({ markerColor: 'red'}) }).addTo(map);
    L.marker([51.927913,4.521303], {icon: L.AwesomeMarkers.icon({icon: 'coffee', prefix: 'fa', markerColor: 'red', iconColor: '#f28f82'}) }).addTo(map);
    L.marker([51.936063,4.502077], {icon: L.AwesomeMarkers.icon({icon: 'cog', prefix: 'fa', markerColor: 'purple', iconColor: 'black'}) }).addTo(map);
    L.marker([51.932835,4.506969], {icon: L.AwesomeMarkers.icon({icon: 'glass', prefix: 'fa', markerColor: 'green'}) }).addTo(map);
    L.marker([51.930295,4.515209], {icon: L.AwesomeMarkers.icon({icon: 'shopping-cart', prefix: 'fa', markerColor: 'blue'}) }).addTo(map);
    L.marker([51.930083,4.507742], {icon: L.AwesomeMarkers.icon({icon: 'info', prefix: 'fa', markerColor: 'orange'}) }).addTo(map);

    L.marker([51.930454,4.527054], {icon: L.AwesomeMarkers.icon({icon: 'group', prefix: 'fa', markerColor: 'darkred'}) }).addTo(map);
    L.marker([51.929607,4.527054], {icon: L.AwesomeMarkers.icon({icon: 'arrow-right', prefix: 'fa', markerColor: 'darkblue'}) }).addTo(map);
    L.marker([51.928919,4.528856], {icon: L.AwesomeMarkers.icon({icon: 'twitter', prefix: 'fa', markerColor: 'cadetblue'}) }).addTo(map);
    L.marker([51.930295,4.530745], {icon: L.AwesomeMarkers.icon({icon: 'phone', prefix: 'fa', markerColor: 'darkpurple'}) }).addTo(map);
    L.marker([51.925055,4.512806], {icon: L.AwesomeMarkers.icon({icon: 'ambulance', prefix: 'fa', markerColor: 'darkgreen'}) }).addTo(map);
    L.marker([51.925902,4.505768], {icon: L.AwesomeMarkers.icon({icon: 'medkit', prefix: 'fa', markerColor: 'darkblue'}) }).addTo(map);
  </script>
</body>
</html>
