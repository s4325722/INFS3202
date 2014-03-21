import 'dart:html';
import 'dart:js';
import 'package:google_maps/google_maps.dart';
import 'package:google_maps/google_maps_places.dart';

GMap map;
InfoWindow infowindow;
PlacesService service;
JsObject masonry;

void main(){
  masonry = new JsObject(context['Masonry'], [querySelector('#mason'), 100]);

  final st_lucia = new LatLng(-27.5000, 153.0000);

  map = new GMap(querySelector("#map"), new MapOptions()
    ..mapTypeId = MapTypeId.ROADMAP
    ..center = st_lucia
    ..zoom = 15
  );

  service = new PlacesService(map);

  final request = new PlaceSearchRequest()
    ..location = st_lucia
    ..radius = 3500
    ..types = ['restaurant']
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
      ..maxHeight = 200
      ..maxWidth = 200
      ;

      Element div = new DivElement()
      ..className = 'item';


      div.children.add(new ImageElement(src: firstPhoto.getUrl(photoOptions)));

      querySelector("#mason").children.add(div);

      masonry.callMethod("appended", [div]);

      masonry.callMethod("layout", []);
    }
  }
}

void randomiseGrid(int gridColumns, int gridRows){
  var scaleSteps = 3;




}

class CellSize {
  constructor(public width, public height){}
}
