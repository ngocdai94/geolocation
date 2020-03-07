/**
 * File Name: googleGeolocation.js
 * Author: Dai Nguyen
 * Description: This file will contain Google API Geolocation's functionalities
 * to result address location on the Google Maps
 * 
 * References:
 *  1. 
 *  2. 
 * 
 */

/**
 * GLOBAL VARIABLE
 */
let mapEmbedded;
let geocoderEmbedded;
let infoWindowEmbedded;
let fromPlace = 0;
let markerEmbedded = null;

function initMap() {
    mapEmbedded = new google.maps.Map(document.getElementById('mapEmbedded'), {
        zoom: 4,
        center: {lat: 41.4212156, lng: -104.1831666}
    });
    geocoderEmbedded = new google.maps.Geocoder;
    infoWindowEmbedded = new google.maps.InfoWindow;
}

function getCurrentLocation() {
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            let pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            markerEmbedded = new google.maps.Marker({
                position: new google.maps.LatLng(pos),
                animation: google.maps.Animation.DROP,
                //title: results[0],
                mapEmbedded: mapEmbedded
            });

            infoWindowEmbedded.setPosition(pos);
            // infoWindowEmbedded.setContent('Location found.');
            // infoWindowEmbedded.open(mapEmbedded);
            mapEmbedded.setCenter(pos);
            mapEmbedded.setZoom(17);
        }, function () {
            handleLocationError(true, infoWindowEmbedded, mapEmbedded.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindowEmbedded, mapEmbedded.getCenter());
    }
}

function geocodeLatLng(geocoderEmbedded, mapEmbedded, infoWindowEmbedded, input) {
    // let input = document.getElementById('latlng').value;
    let latlngStr = input.split(',', 2);
    let latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
    geocoderEmbedded.geocode({'location': latlng}, function(results, status) {
    if (status === 'OK') {
        console.log(results);
        if (results[0]) {
        mapEmbedded.setZoom(17);
        mapEmbedded.setCenter(latlng);
        
        markerEmbedded = new google.maps.Marker({
            position: latlng,
            mapEmbedded: mapEmbedded
        });
        infoWindowEmbedded.setContent(results[0].formatted_address);
        infoWindowEmbedded.open(mapEmbedded, markerEmbedded);
        } else {
        window.alert('No results found');
        }
    } else {
        window.alert('Geocoder failed due to: ' + status);
    }
    });
}

function codeAddress() {
    var address = document.getElementById("address").value;
    if (fromPlace == 1) {
        mapEmbedded.setCenterAnimated(locationFromPlace);
        annotation.selected = false;
        annotation.coordinate = locationFromPlace;
        annotation.address = addressFromPlace;
        annotation.lat = locationFromPlace.latitude;
        annotation.lng = locationFromPlace.longitude;
        setTimeout(function() {
            annotation.selected = true
        }, 500);
        document.getElementById("latitude").value = locationFromPlace.latitude;
        document.getElementById("longitude").value = locationFromPlace.longitude;
        document.getElementById("latlong").value = locationFromPlace.latitude + "," + locationFromPlace.longitude;
        document.getElementById("address").value = addressFromPlace;
        bookUp(addressFromPlace, locationFromPlace.latitude, locationFromPlace.longitude);
        ddversdms()
    } else {
        myForwardGeocode(address)
    }
}

function codeLatLng(origin) {
    var lat = parseFloat(document.getElementById("latitude").value) || 0;
    var lng = parseFloat(document.getElementById("longitude").value) || 0;
    if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
        var latlng = new google.maps.LatLng(lat, lng);
        if (origin == 1) ddversdms();
        
        mapEmbedded.setCenter(latlng); // NEED TO FIX THIS TO LINK TO THE CURRENT MAP
        mapEmbedded.setZoom(17);

        if (markerEmbedded != null) markerEmbedded.setMap(null);
        markerEmbedded = new google.maps.Marker({
            mapEmbedded: mapEmbedded,
            position: latlng
        });
        myReverseGeocode(lat, lng, "");
        fromPlace = 0
    } else alert(trans.InvalidCoordinatesShort)
}

function dmsversdd() {
    var lat, lng, nordsud, estouest, latitude_degres, latitude_minutes, latitude_secondes, longitude_degres, longitude_minutes, longitude_secondes;
    if (document.getElementById("sud").checked) nordsud = -1;
    else nordsud = 1;
    if (document.getElementById("ouest").checked) estouest = -1;
    else estouest = 1;
    latitude_degres = parseFloat(document.getElementById("latitude_degres").value) || 0;
    latitude_minutes = parseFloat(document.getElementById("latitude_minutes").value) || 0;
    latitude_secondes = parseFloat(document.getElementById("latitude_secondes").value) || 0;
    longitude_degres = parseFloat(document.getElementById("longitude_degres").value) || 0;
    longitude_minutes = parseFloat(document.getElementById("longitude_minutes").value) || 0;
    longitude_secondes = parseFloat(document.getElementById("longitude_secondes").value) || 0;
    lat = nordsud * (latitude_degres + latitude_minutes / 60 + latitude_secondes / 3600);
    lng = estouest * (longitude_degres + longitude_minutes / 60 + longitude_secondes / 3600);
    document.getElementById("latitude").value = Math.round(lat * 1e7) / 1e7;
    document.getElementById("longitude").value = lng;
    setTimeout(codeLatLng(2), 1e3)
}

function ddversdms() {
    var lat, lng, latdeg, latmin, latsec, lngdeg, lngmin, lngsec;
    lat = parseFloat(document.getElementById("latitude").value) || 0;
    lng = parseFloat(document.getElementById("longitude").value) || 0;
    if (lat >= 0) document.getElementById("nord").checked = true;
    if (lat < 0) document.getElementById("sud").checked = true;
    if (lng >= 0) document.getElementById("est").checked = true;
    if (lng < 0) document.getElementById("ouest").checked = true;
    lat = Math.abs(lat);
    lng = Math.abs(lng);
    latdeg = Math.floor(lat);
    latmin = Math.floor((lat - latdeg) * 60);
    latsec = Math.round((lat - latdeg - latmin / 60) * 1e3 * 3600) / 1e3;
    lngdeg = Math.floor(lng);
    lngmin = Math.floor((lng - lngdeg) * 60);
    lngsec = Math.floor((lng - lngdeg - lngmin / 60) * 1e3 * 3600) / 1e3;
    document.getElementById("latitude_degres").value = latdeg;
    document.getElementById("latitude_minutes").value = latmin;
    document.getElementById("latitude_secondes").value = latsec;
    document.getElementById("longitude_degres").value = lngdeg;
    document.getElementById("longitude_minutes").value = lngmin;
    document.getElementById("longitude_secondes").value = lngsec
}

function handleLocationError(browserHasGeolocation, infoWindowEmbedded, pos) {
    infoWindowEmbedded.setPosition(pos);
    infoWindowEmbedded.setContent(browserHasGeolocation ?
                          'Error: The Geolocation service failed.' :
                          'Error: Your browser doesn\'t support geolocation.');
    infoWindowEmbedded.open(mapEmbedded);
}

function myReverseGeocode(latToGeocode, lngToGeocode, intro) {
    latToGeocode = parseFloat(latToGeocode);
    lngToGeocode = parseFloat(lngToGeocode);
    if (latToGeocode >= -90 && latToGeocode <= 90 && lngToGeocode >= -180 && lngToGeocode <= 180) {
        $.ajax({
            type: "GET",
            url: "https://api.opencagedata.com/geocode/v1/json?q=" + latToGeocode + "+" + lngToGeocode + "&key=" + trans.OpenKey + "&no_annotations=1&language=" + trans.Locale,
            dataType: "json",
            success: function(data) {
                if (data.status.code == 200) {
                    if (data.total_results >= 1) {
                        if (intro == -1) geolocAddr = data.results[0].formatted;
                        updateAll(intro, data.results[0].formatted, latToGeocode, lngToGeocode)
                    } else {
                        if (intro == -1) geolocAddr = trans.NoResolvedAddress;
                        updateAll(intro, trans.NoResolvedAddress, latToGeocode, lngToGeocode)
                    }
                } else {
                    if (intro == -1) geolocAddr = trans.GeocodingError;
                    updateAll(intro, trans.InvalidCoordinates, latToGeocode, lngToGeocode)
                }
            },
            error: function(xhr, err) {
                updateAll(trans.Geolocation, trans.InvalidCoordinates, latToGeocode, lngToGeocode)
            }
        }).always(function() {
            if (intro == -1) initializeMap()
        });
        return false
    } else alert(trans.InvalidCoordinatesShort)
}

function myForwardGeocode(addr) {
    // $.ajax({
    //     type: "GET",
    //     url: "https://api.opencagedata.com/geocode/v1/json?q=" + encodeURIComponent(addr) + "&key=" + trans.OpenKey + "&no_annotations=1&language=" + trans.Locale,
    //     dataType: "json",
    //     success: function(data) {
    //         if (data.status.code == 200) {
    //             if (data.total_results >= 1) {
    //                 var latres = data.results[0].geometry.lat;
    //                 var lngres = data.results[0].geometry.lng;
    //                 var pos = new mapkit.Coordinate(latres, lngres);
    //                 mapEmbedded.setCenterAnimated(pos);
    //                 annotation.coordinate = pos;
    //                 updateAll("", data.results[0].formatted, latres, lngres)
    //             } else {
    //                 alert(trans.GeolocationError)
    //             }
    //         } else {
    //             alert(trans.GeolocationError)
    //         }
    //     },
    //     error: function(xhr, err) {
    //         alert(trans.GeolocationError)
    //     }
    // }).always(function() {});
    // return false

    getCurrentLocation();
}

$(() => {
    // initMap();
});
