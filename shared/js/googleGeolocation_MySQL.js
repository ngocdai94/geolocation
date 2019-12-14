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
 * GLOBAL VARIABLES
 */
let map;
let geocoder;
let infowindow;
let infoWindowArray = new Array();

// Each MySQL Row Variable
let classSubmit;
let classLat;
let classLong;
let marker = null;

// Handle Querry Limit Variable
let delay = 100;
let nextGeocode = 0;
let totalGeocode = 0;
let nextMarker = 0;
let totalMarkersOnPage = 0;

// Handle All Data in MySQL Table
let classLatAll;
let classLongAll;
let totalSqlData = 0;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: {lat: 41.4212156, lng: -104.1831666}
    });
    geocoder = new google.maps.Geocoder;
    infowindow = new google.maps.InfoWindow;
    classSubmit = document.getElementsByClassName('reverseGeocode');
    classLat = document.getElementsByClassName('lat');
    classLong = document.getElementsByClassName('long');

    totalGeocode = classSubmit.length;
    totalMarkersOnPage = totalGeocode;

    for (let i = 0; i < totalGeocode; i++) {
        classSubmit[i].addEventListener('click', function() {
                geocodeLatLng(geocoder, map, infowindow, classLat[i].innerText, classLong[i].innerText)
        });
        // mark all geolocation on the map
        // allGeolocationMarkers (geocoder, map, classLat[i].innerText, classLong[i].innerText);
    }
}

function allGeolocationMarkers (geocoder, map, latitude, longitude, next) {
    let latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};

    // infoWindowArray[nextMarker] = new google.maps.InfoWindow;

    geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
            console.log(results);
            if (results[0]) {
                // map.setZoom(15);
                // map.setCenter(latlng);
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(latlng),
                    animation: google.maps.Animation.DROP,
                    //title: results[0],
                    map: map
                });
                // infowindow.setContent(results[0].formatted_address);
                // infowindow.open(map, marker);

                /** 
                 * Source: https://stackoverflow.com/questions/47777107/how-to-add-google-maps-with-multiple-markers-showing-infowindows-on-load-and-on
                 * 
                */
                google.maps.event.addListener(marker, 'click', (function(marker, nextMarker) {
                    return function () {
                        infowindow.close();     // Close previously opened infowindow
                        map.setCenter(latlng);  // Set center
                        infowindow.setContent(results[0].formatted_address); // Set address
                        infowindow.open(map, marker);
                    }
                    // infowindow.close(); // Close previously opened infowindow
                    // infowindow.setContent(results[0].formatted_address); // Set address
                    // infowindow.open(map, marker);
                })(marker, nextMarker));
            } else {
                window.alert('No results found');
            }
        } else {
            // window.alert('Geocoder failed due to: ' + status);
            if (status === 'OVER_QUERY_LIMIT') {
                // console.log(status + " Querry Delay: " + delay + "ms " + '\n');
                nextMarker--;
                delay++;
            } else {
                console.log(status + " Server Delay: " + delay + "ms " + '\n');
            }
        }
        next();
    });
}

function geocodeLatLng(geocoder, map, infowindow, latitude, longitude) {
    // let input = document.getElementById('latlng').value;
    // let latlngStr = input.split(',', 2);
    // let latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
    let latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};
    geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
            console.log(results);
            if (results[0]) {
                map.setZoom(17);
                map.setCenter(latlng);
                
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(marker.get('map'), marker);       
                });
            } else {
                window.alert('No results found');
            }
        } else {
            // window.alert('Geocoder failed due to: ' + status);
        }
    });
}

function codeLatLng(origin) {
    let lat = parseFloat(document.getElementById("latitude").value) || 0;
    let lng = parseFloat(document.getElementById("longitude").value) || 0;
    if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
        let latlng = new google.maps.LatLng(lat, lng);
        if (origin == 1) ddversdms();
            map.setCenter(latlng); // NEED TO FIX THIS TO LINK TO THE CURRENT MAP
            map.setZoom(17);


            /**
                Uncomment the line below to set multiple marker
                */
            // if (marker != null) marker.setMap(null);
            marker = new google.maps.Marker({
                map: map,
                position: latlng
            });
            myReverseGeocode(lat, lng, "");
            fromPlace = 0
    } else alert(trans.InvalidCoordinatesShort)
}

function dmsversdd() {
    let lat, lng, nordsud, estouest, latitude_degres, latitude_minutes, latitude_secondes, longitude_degres, longitude_minutes, longitude_secondes;
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
    let lat, lng, latdeg, latmin, latsec, lngdeg, lngmin, lngsec;
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

function myReverseGeocode(latToGeocode, lngToGeocode, intro) {
    latToGeocode = parseFloat(latToGeocode);
    lngToGeocode = parseFloat(lngToGeocode);
    // if (latToGeocode >= -90 && latToGeocode <= 90 && lngToGeocode >= -180 && lngToGeocode <= 180) {
    //     $.ajax({
    //         type: "GET",
    //         url: "https://api.opencagedata.com/geocode/v1/json?q=" + latToGeocode + "+" + lngToGeocode + "&key=" + trans.OpenKey + "&no_annotations=1&language=" + trans.Locale,
    //         dataType: "json",
    //         success: function(data) {
    //             if (data.status.code == 200) {
    //                 if (data.total_results >= 1) {
    //                     if (intro == -1) geolocAddr = data.results[0].formatted;
    //                     updateAll(intro, data.results[0].formatted, latToGeocode, lngToGeocode)
    //                 } else {
    //                     if (intro == -1) geolocAddr = trans.NoResolvedAddress;
    //                     updateAll(intro, trans.NoResolvedAddress, latToGeocode, lngToGeocode)
    //                 }
    //             } else {
    //                 if (intro == -1) geolocAddr = trans.GeocodingError;
    //                 updateAll(intro, trans.InvalidCoordinates, latToGeocode, lngToGeocode)
    //             }
    //         },
    //         error: function(xhr, err) {
    //             updateAll(trans.Geolocation, trans.InvalidCoordinates, latToGeocode, lngToGeocode)
    //         }
    //     }).always(function() {
    //         if (intro == -1) initializeMap()
    //     });
    //     return false
    // } else alert(trans.InvalidCoordinatesShort)
}

// ======= Function to call the next Geocode operation when the reply comes back
// function theNextGeocode() {
//     if (nextGeocode < totalGeocode) {
//         setTimeout(classSubmit[nextGeocode].addEventListener('click', function() {
//                 geocodeLatLng(geocoder, map, infowindow, classLat[nextGeocode].innerText, classLong[nextGeocode].innerText, next)
//             }), 
//             delay);
//         nextGeocode++;
//     } else {
//         // We're done. Show map bounds
//         // map.fitBounds(bounds);
//     }
// }

// Automatically reverse geolocation on current display table
function theNextMarker() {
    if (nextMarker < totalMarkersOnPage) {
        setTimeout(allGeolocationMarkers (geocoder, map, classLat[nextMarker].innerText, classLong[nextMarker].innerText, theNextMarker) , delay);
        nextMarker++;
    } else {
        // We're done. Show map bounds
    }
}

// Reverse all geolocation in the MySQL Database
function reverseAllMarkers () {
    if (nextMarker < totalSqlData) {
        setTimeout(allGeolocationMarkers (geocoder, map, classLatAll[nextMarker].innerText, classLongAll[nextMarker].innerText, reverseAllMarkers) , delay);
        nextMarker++;
    } else {
        // We're done. Show map bounds
    }
}

function callReverseAll() {
     // reset nextMarker
    classLatAll = document.getElementsByClassName('latAll');
    classLongAll = document.getElementsByClassName('longAll');
    totalSqlData = classLatAll.length;

    reverseAllMarkers(classLatAll, classLongAll, totalSqlData);
}

// ======= Call that function for the first time =======
window.onload = () => {
    //theNextMarker(); // automatically reverse geolocation on current display table
    
    // Add Click Event on the callReverseAll Button
    document.getElementById("callReverseAll").addEventListener('click', () => {
        callReverseAll();
    });
};
