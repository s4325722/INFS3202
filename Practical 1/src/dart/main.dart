import 'dart:html';
import 'dart:js';
import 'dart:collection';
import 'dart:async';
import 'package:google_maps/google_maps.dart';
import 'package:google_maps/google_maps_places.dart';

GMap map;
PlacesService service;
JsObject isotope;
num placeCount = 0;
var nearbySearchRequest;

var typeClasses = {
  'bar': 'two',
  'cafe': 'one',
  'restaurant': 'three',
  'store': 'four'
};


void main(){
  isotope = new JsObject(context["Isotope"], [querySelector("#categories"), new JsObject.jsify({
    'itemSelector': '.item',
    'layoutMode': 'masonry',
    'getSortData': {
      'name': '[item-place-name]',
      'type': '[item-place-type]'
    },
    'sortBy': ['type', 'name'],
    'sortAscending': true,
    'transitionDuration': '0.4s'
  })]);

  final st_lucia = new LatLng(-27.5000, 153.0000);

  map = new GMap(querySelector("#map"), new MapOptions()
    ..mapTypeId = MapTypeId.ROADMAP
    ..center = st_lucia
    ..zoom = 15
  );

  service = new PlacesService(map);

//  final request = new PlaceSearchRequest()
//    ..location = st_lucia
//    ..radius = 3500
//    ..types = ['bar', 'restaurant', 'store', 'cafe']
//  ;

  final request = new RadarSearchRequest()
      ..location = st_lucia
      ..radius = 4500
      ..types = ['bar', 'restaurant', 'store', 'cafe']
  ;

  nearbySearchRequest = request;

  //service.nearbySearch(request, placeSearchCallback);
  service.radarSearch(request, radarSearchCallback);
}

void radarSearchCallback(List<PlaceResult> results, PlacesServiceStatus status) {
  if (status == PlacesServiceStatus.OK) {

    List<Future> futures = new List<Future>();

    print("RadarSearch (" + results.length.toString() + ")");

    for (var i = 0; i < results.length; i++) {
      var request = new PlaceDetailsRequest()
        ..reference = results[i].reference;

      futures.add(new Future.delayed(new Duration(seconds: i / 5), () => service.getDetails(request, placeDetailsCallback)));

//      placeCount++;
//
//      var request = new PlaceDetailsRequest()
//        ..reference = results[i].reference;
//
//      service.getDetails(request, placeDetailsCallback);
    }

    Future.wait(futures);
  }
}

void placeSearchCallback(List<PlaceResult> results, PlacesServiceStatus status, PlaceSearchPagination pagination) {
  if (status == PlacesServiceStatus.OK) {

    List<Future> futures = new List<Future>();


    for (var i = 0; i < results.length; i++) {
      var request = new PlaceDetailsRequest()
        ..reference = results[i].reference;

      futures.add(Future.delayed(new Duration(seconds: i / 5), () => service.getDetails(request, placeDetailsCallback)));

//      placeCount++;
//
//      var request = new PlaceDetailsRequest()
//        ..reference = results[i].reference;
//
//      service.getDetails(request, placeDetailsCallback);
    }

    Future.wait(futures);
  }
}

void placeDetailsCallback(PlaceResult result, PlacesServiceStatus status){
  print("PlaceDetails [" + status.toString() + "]");

  if(status == PlacesServiceStatus.OK) {
    if(result.photos != null && result.photos.length > 0){
      PlacePhoto firstPhoto = result.photos[0];

      PhotoOptions photoOptions = new PhotoOptions()
      ..maxHeight = 200
      ..maxWidth = 200
      ;

      Element div = new DivElement()
      ..className = 'item ' + typeClasses[getPrimaryType(result.types)];

      div.setAttribute('item-place-type', getPrimaryType(result.types));
      div.setAttribute('item-place-name', result.name);

      Element a = new AnchorElement();
      a.setAttribute('href', '#');
      div.children.add(a);


      a.children.add(createPlaceDetailsNode(result.name, getPlaceLocality(result), result.formattedPhoneNumber));
      a.children.add(new ImageElement(src: firstPhoto.getUrl(photoOptions)));


      querySelector("#categories").children.add(div);

      isotope.callMethod("appended", [div]);

      isotope.callMethod("layout", []);

      isotope.callMethod("arrange", []);
    }
  }
}

Element createPlaceDetailsNode(String name, String location, String phone){
  Element element = new DivElement()
  ..className = "description"
  ;

  ParagraphElement p1 = new ParagraphElement()
  ..className = "name"
  ..text = name
  ;

  ParagraphElement p2 = new ParagraphElement()
  ..className = "location"
  ..text = location
  ;

  ParagraphElement p3 = new ParagraphElement()
  ..className = "phone"
  ..text = phone
  ;

  element.children.add(p1);
  element.children.add(p2);
  element.children.add(p3);

  return element;
}

String getPrimaryType(List<String> types){
  final approvedTypes = ['bar', 'restaurant', 'store', 'cafe'];

  for(int i = 0; i < types.length; i++){
    if(approvedTypes.indexOf(types[i]) != -1)
      return types[i];
  }

  return null;
}

String getPlaceLocality(PlaceResult place){
  for(int i = 0; i < place.addressComponents.length; i++){
    if(place.addressComponents[i].types.contains("locality")){
      return place.addressComponents[i].shortName;
    }
  }

  return "Unknown";
}