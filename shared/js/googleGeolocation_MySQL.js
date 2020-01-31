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

// Each MySQL Row Variables
let classSubmit;
let classLat;
let classLong;
let marker = null;

// Handle Querry Limit Variables
let delay = 100;
let nextGeocode = 0;
let totalGeocode = 0;
let nextMarker = 0;
let totalMarkersOnPage = 0;

// Handle All Data in MySQL Table
let classLatAll;
let classLongAll;
let classNameAll;
let totalSqlData = 0;
let locationNames = new Array();
let locationAltitudes = new Array();

// Handle Uploading Delay
let totalNames = 0;
let uploadDone = false;
let totalAltitudes = 0;
let currentAltitude = 0;
let uploadingDelay = 500;

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

function allGeolocationAltitudes() {
    if (currentAltitude < totalSqlData) {
        let latitude = classLatAll[currentAltitude].innerText;
        let longitude = classLongAll[currentAltitude].innerText;
        $.get ('/methods/getaltitude.php', {LAT: parseFloat(latitude), LONG: parseFloat(longitude)}, (elevation) => {
            // console.log(elevation);
            locationAltitudes.push(parseFloat(elevation));
            currentAltitude++;
        });

        setTimeout(()=>{
            allGeolocationAltitudes();
        }, uploadingDelay);
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
            
                // save location names to an array
                // console.log(results[0].formatted_address);
                locationNames.push(results[0].formatted_address);

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

// Automatically reverse geolocation on current display table
function theNextMarker() {
    if (nextMarker < totalMarkersOnPage) {
        setTimeout(allGeolocationMarkers (geocoder, map, classLat[nextMarker].innerText, classLong[nextMarker].innerText, theNextMarker) , delay);
        nextMarker++;
    } else {
        // We're done. Show map bounds
    }
}

// Handle latency when uploading name back to MySQL
function uploadNamesAltitude() {
    if (totalNames < totalSqlData) {
        totalNames = locationNames.length;
    } else if (totalAltitudes < totalSqlData) {
        totalAltitudes = locationAltitudes.length;
    } else {
        // Uploading all the names to the MySQL Database
        $.get ('/methods/uploadAltitudeNames.php', {altitudes: locationAltitudes, names: locationNames}, (result) => {
            console.log(result);
        });

        // Finish uploading
        uploadDone = true;
    }

    if (!uploadDone) {
        setTimeout(()=>{
            uploadNamesAltitude();
        }, uploadingDelay);
    }
}

// Reverse all geolocation in the MySQL Database
function reverseAllMarkers() {
    // Zoom out of the Maps
    map.setZoom(3);

    // Reverse geocode and dispkay all markers on the maps
    if (nextMarker < totalSqlData) {
        setTimeout(allGeolocationMarkers (geocoder, map, classLatAll[nextMarker].innerText, classLongAll[nextMarker].innerText, reverseAllMarkers) , delay);
        nextMarker++;
    } else {
        // when finish, 
        // If names and altitude have not been resolve, 
        // resolve and save all location names and altitude back to MySQL Database 
        if (classNameAll[0].innerText == "") {
            uploadDone = false;
            uploadNamesAltitude();
        } else {
            alert ("All of The Map Markers Have Been Loaded!!");
        }
    }
}

function callReverseAll() {
    classLatAll = document.getElementsByClassName('latAll');
    classLongAll = document.getElementsByClassName('longAll');
    classNameAll = document.getElementsByClassName('locationName');
    totalSqlData = classLatAll.length;

    reverseAllMarkers(classLatAll, classLongAll, totalSqlData);
    allGeolocationAltitudes();
}

// MySQL AJAX - Reload MySQL Database without reloading the webpage
function refreshMySQL() {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("mysql-table").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET","/methods/refresh.php",true);
    xmlhttp.send();
} 

// ======= Call that function for the first time =======
window.onload = () => {
    //theNextMarker(); // automatically reverse geolocation on current display table
    
    // Load MySQL Database
    refreshMySQL();
    
    // Add Click Event on the callReverseAll Button
    document.getElementById("callReverseAll").addEventListener('click', () => {
        callReverseAll();
    });


    // Add the following code if you want the name of the file appear on select box field
    $(".custom-file-input").on("change", function() {
        let fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
};
