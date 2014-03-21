import 'dart:html';
import 'package:google_maps/google_maps.dart';
import 'package:google_maps/google_maps_places.dart';

GMap map;
InfoWindow infowindow;

void main(){
  final st_lucia = new LatLng(-27.5000, 153.0000);

  map = new GMap(querySelector("#map"), new MapOptions()
    ..mapTypeId = MapTypeId.ROADMAP
    ..center = st_lucia
    ..zoom = 15
  );

  final request = new PlaceSearchRequest()
    ..location = st_lucia
    ..radius = 1500
    ..types = ['store']
  ;

  infowindow = new InfoWindow();
  final service = new PlacesService(map);
  // TODO search not documented
  service.nearbySearch(request, callback);
}

void callback(List<PlaceResult> results, PlacesServiceStatus status, PlaceSearchPagination pagination) {
  if (status == PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {

      if(results[i].photos == null || results[i].photos.length == 0)
        continue;

        var img = new ImageElement(
            src: "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=" +
                  results[i].reference +
                 "&sensor=false&key=AIzaSyCSc01anvKtXZ5Q_fGp7p8_2JbiWBtSAq4"
        );

        querySelector("#images").nodes.add(img);
    }
  }
}

void randomiseGrid(int gridColumns, int gridRows){
  var scaleSteps = 3;




}

class CellSize {
  constructor(public width, public height){}
}