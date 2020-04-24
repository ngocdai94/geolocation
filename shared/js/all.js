/**
 * File Name: all.js
 * Author: Dai Nguyen
 * Description: This file will contain Google API Geolocation's functionalities
 * to resolve address location on the Google Maps
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

// Another Map Section
let mapEmbedded;
let geocoderEmbedded;
let infoWindowEmbedded;
let fromPlace = 0;
let markerEmbedded = null;

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
let loadingDelay = 1000;

// Current MySQL Page
let currentPage = 1;

// Map Marker Object Array
let mapMarkerArray = [];
let jsonMarkers;

// Page Sections
let homeSection;
let mysqlSection;
let dmsSection; 
let homeLink;
let mysqlLink;
let dmsLink;


function initMap() {
    // Get Google Maps ready for MySQL section 
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: {lat: 41.4212156, lng: -104.1831666}
    });
    geocoder = new google.maps.Geocoder;
    infowindow = new google.maps.InfoWindow;

    // Get Google Maps ready for DMS section
    mapEmbedded = new google.maps.Map(document.getElementById('mapEmbedded'), {
        zoom: 4,
        center: {lat: 41.4212156, lng: -104.1831666}
    });
    geocoderEmbedded = new google.maps.Geocoder;
    infoWindowEmbedded = new google.maps.InfoWindow;
}

/**
 * From googleGeolocation_MySQL.js
 */
function initMySQLButtons() {
    // Initialize Click Events on MySQL Table
    classSubmit = document.getElementsByClassName('reverseGeocode');
    classLat = document.getElementsByClassName('lat');
    classLong = document.getElementsByClassName('long');

    totalGeocode = classSubmit.length;
    totalMarkersOnPage = totalGeocode;

    for (let i = 0; i < totalGeocode; i++) {
        classSubmit[i].addEventListener('click', function() {
            geocodeLatLng(geocoder, map, infowindow, classLat[i].innerText, classLong[i].innerText);
        });
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

                // save markers object to array
                mapMarkerArray.push(marker);
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

                console.log(marker);

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

    // Reverse geocode and display all markers on the maps
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
        }
        alert ("All of The Map Markers Have Been Loaded!!");
    }
}

function callReverseAll() {
    classLatAll = document.getElementsByClassName('latAll');
    classLongAll = document.getElementsByClassName('longAll');
    classNameAll = document.getElementsByClassName('locationName');
    totalSqlData = classLatAll.length;

    reverseAllMarkers();
    allGeolocationAltitudes();
}

// MySQL AJAX - Reload MySQL Database without reloading the webpage
function refreshMySQL($str="") {
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

    if ($str != "") {
        xmlhttp.open("GET","/methods/refresh.php?page="+$str,true);
        xmlhttp.send();
    } else {
        xmlhttp.open("GET","/methods/refresh.php",true);
        xmlhttp.send();
    }

    setTimeout(()=>{
        initMySQLButtons();
    }, loadingDelay);
} 
/*----------------------------------------------------------------------------*/

/**
 * From googleGeolocation_Embedded.js
 */
function getCurrentLocation(address) {
    // Address and Posititions Place Holder
    let addressResult;
    let lat;
    let long;

    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            let pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            lat = position.coords.latitude;
            long = position.coords.longitude;

            let latlng = {lat: parseFloat(lat), lng: parseFloat(long)};

            markerEmbedded = new google.maps.Marker({
                position: new google.maps.LatLng(pos),
                animation: google.maps.Animation.DROP,
                // title: results[0],
                map: mapEmbedded
            });

            geocoder.geocode({'location': latlng}, 
                function(results, status) {
                    if (status === 'OK') {
                        addressResult = results[0].formatted_address;
                        
                        document.getElementById("latitude").value = lat;
                        document.getElementById("longitude").value = long
                        document.getElementById("latlong").value = lat + "," +long;
                        document.getElementById("address").value = addressResult;
                    }
                }
            );

            infoWindowEmbedded.setPosition(pos);
            infoWindowEmbedded.setContent(results[0]);
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
        getCurrentLocation(address)
    }
}

function codeLatLngEmbedded(origin) {
    var lat = parseFloat(document.getElementById("latitude").value) || 0;
    var lng = parseFloat(document.getElementById("longitude").value) || 0;
    if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
        var latlng = new google.maps.LatLng(lat, lng);
        if (origin == 1) ddversdms();
        
        mapEmbedded.setCenter(latlng); // NEED TO FIX THIS TO LINK TO THE CURRENT MAP
        mapEmbedded.setZoom(17);

        if (markerEmbedded != null) markerEmbedded.setMap(null);
        markerEmbedded = new google.maps.Marker({
            map: mapEmbedded,
            position: latlng
        });
        myReverseGeocode(lat, lng, "");
        fromPlace = 0;
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
    setTimeout(codeLatLngEmbedded(2), 1e3)
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
    // latToGeocode = parseFloat(latToGeocode);
    // lngToGeocode = parseFloat(lngToGeocode);
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

/*----------------------------------------------------------------------------*/

// Show or hide unecessary section on the Web Page
function showHome() {
    homeSection.css("display", "block");
    mysqlSection.css("display", "none");
    dmsSection.css("display", "none");

    homeLink.addClass("active");
    mysqlLink.removeClass("active");
    dmsLink.removeClass("active");
}

function showHideMySQL() {
    homeSection.css("display", "none");
    mysqlSection.css("display", "block");
    dmsSection.css("display", "none");

    homeLink.removeClass("active");
    mysqlLink.addClass("active");
    dmsLink.removeClass("active");
}

function showHideDMS() {
    homeSection.css("display", "none");
    dmsSection.css("display", "block");
    mysqlSection.css("display", "none");

    homeLink.removeClass("active");
    mysqlLink.removeClass("active");
    dmsLink.addClass("active");
}

// ======= Call that function for the first time =======
$ (() => {
    //theNextMarker(); // automatically reverse geolocation on current display table
    
    // Load MySQL Database
    refreshMySQL();
    
    // Add class selected to a selected click event
    $('.pagination a').click(function(){
         $(this).parent().find('a.text-dark').removeClass("text-dark");
         $(this).addClass("text-dark");
    });

    // Add the following code if you want the name of the file appear on select box field
    $(".custom-file-input").on("change", function() {
        let fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    // Show and Hide section
    homeSection = $('#Home');
    mysqlSection = $('#MySQL_Database');
    dmsSection = $('#DMS_Geo_Calculation');
    homeLink = $('#homeLink');
    mysqlLink = $('#mysqlLink');
    dmsLink = $('#dmsLink');

    mysqlSection.css("display", "none");
    dmsSection.css("display", "none");
});
