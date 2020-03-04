<?php include "../private/config.php"?>
<?php include "../shared/php/header.php"?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="grid-container"> 
            <div class="grid-item">
                <section class="left">
                    <h2>Get Reverse Geolocation by Longitude & Latitude</h2>
                    <div>
                        <form class="form-horizontal" role="form">
                        <h4>Address</h4>
                            <div class="form-group">
                                <div class="col-md-12 addressWrapper">
                                    <!-- <p class="h4">Address</p> -->
                                    <input id="address" class="form-control" type="text" value="">
                                    <div id="resultsWrapper">
                                        <div id="results" class="mapSearchResults"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                <button type="button" class="btn btn-primary" onclick="codeAddress()">Get GPS Coordinates</button>
                                </div>
                            </div>
                        </form>
                        
                        <form class="form-horizontal" role="form">
                            <h4>DD (decimal degrees)*</h4>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="latitude">Latitude</label>
                                <div class="col-md-9">
                                    <input id="latitude" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="longitude">Longitude</label>
                                <div class="col-md-9">
                                    <input id="longitude" class="form-control" type="text">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" onclick="codeLatLng(1)">Get
                                        Address</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="longitude">Lat,Long</label>
                                <div class="col-md-9">
                                    <input id="latlong" class="form-control selectall" type="text">
                                </div>
                            </div>

                        </form>
                    </div>

                    <div>
                        <form class="form-horizontal" role="form">
                            <h4>DMS (degrees, minutes, seconds)*</h4>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="latitude">Latitude</label>
                                <div class="col-md-9">
                                    <label class="radio-inline">
                                        <input type="radio" name="latnordsud" value="nord" id="nord" checked="">
                                        N
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="latnordsud" value="sud" id="sud">
                                        S
                                    </label>

                                    <input class="form-control sexagesimal" id="latitude_degres" type="text">
                                    <label for="latitude_degres">°</label>
                                    <input class="form-control sexagesimal" id="latitude_minutes" type="text">
                                    <label for="latitude_minutes">'</label>
                                    <input class="form-control sexagesimalsec" id="latitude_secondes" type="text">
                                    <label for="latitude_secondes">''</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="longitude">Longitude</label>
                                <div class="col-md-9">
                                    <label class="radio-inline">
                                        <input type="radio" name="lngestouest" value="est" id="est" checked="">
                                        E
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="lngestouest" value="ouest" id="ouest">
                                        W
                                    </label>

                                    <input class="form-control sexagesimal" id="longitude_degres" type="text">
                                    <label for="longitude_degres">°</label>


                                    <input class="form-control sexagesimal" id="longitude_minutes" type="text">
                                    <label for="longitude_minutes">'</label>
                                    <input class="form-control sexagesimalsec" id="longitude_secondes" type="text">
                                    <label for="longitude_secondes">''</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" onclick="dmsversdd()">Get
                                        Address</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            <div class="grid-item">
                <!-- Google Maps -->
                <!-- <section class="map"> -->
                    <div id="map"></div>
                <!-- </section> -->
            <!-- </div> -->
        </div>
    </main>

<?php include "../shared/php/footer.php"?>
