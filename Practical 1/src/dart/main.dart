import 'dart:html';
import 'package:google_maps/google_maps.dart';
import 'package:google_maps/google_maps_places.dart';

GMap map;
InfoWindow infowindow;
PlacesService service;

void main(){
  final st_lucia = new LatLng(-27.5000, 153.0000);

  map = new GMap(querySelector("#map"), new MapOptions()
    ..mapTypeId = MapTypeId.ROADMAP
    ..center = st_lucia
    ..zoom = 15
  );

  service = new PlacesService(map);

  final request = new PlaceSearchRequest()
    ..location = st_lucia
    ..radius = 1500
    ..types = ['store']
  ;

  infowindow = new InfoWindow();
  // TODO search not documented
  service.nearbySearch(request, placeSearchCallback);
}

void placeSearchCallback(List<PlaceResult> results, PlacesServiceStatus status, PlaceSearchPagination pagination) {
  if (status == PlacesServiceStatus.OK) {

    for (var i = 0; i < results.length; i++) {

      var request = new PlaceDetailsRequest()
        ..reference = results[i].reference;

      service.getDetails(request, placeDetailsCallback);
    }
  }
}

void placeDetailsCallback(PlaceResult result, PlacesServiceStatus status){
  var img = new ImageElement(
      src: "https://maps.googleapis.com/maps/api/place/photo?key=AIzaSyCSc01anvKtXZ5Q_fGp7p8_2JbiWBtSAq4&" +
      "sensor=false&maxheight=400&photoreference=" +
      result.reference
  );

  //querySelector("#images").nodes.add(img);
}

void randomiseGrid(int gridColumns, int gridRows){
  var scaleSteps = 3;




}

class CellSize {
  constructor(public width, public height){}
}