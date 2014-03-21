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
  if(status == PlacesServiceStatus.OK) {
    if(result.photos != null && result.photos.length > 0){
      PlacePhoto firstPhoto = result.photos[0];

      PhotoOptions photoOptions = new PhotoOptions()
      ..maxHeight = 400
      ..maxWidth = 400
      ;

      querySelector("#images").nodes.add(new ImageElement(src: firstPhoto.getUrl(photoOptions)));
    }
  }
}

void randomiseGrid(int gridColumns, int gridRows){
  var scaleSteps = 3;




}

class CellSize {
  constructor(public width, public height){}
}
