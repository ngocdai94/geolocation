<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <title>Test Template</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/dashboard/">

    <!-- Bootstrap core CSS -->
    <link href="/shared/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicons -->
    <!-- <link rel="apple-touch-icon" href="/docs/4.4/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/4.4/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/4.4/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/4.4/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/4.4/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="/docs/4.4/assets/img/favicons/favicon.ico">
    <meta name="msapplication-config" content="/docs/4.4/assets/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c"> -->

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="/shared/css/main.css" rel="stylesheet">
    <link href="/shared/css/dms.css" rel="stylesheet">
    <link href="/shared/css/dashboard.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Testing Template</a>
        <!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Sign out</a>
            </li>
        </ul> -->
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/test.php">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="file"></span>
                                MySQL Database
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="shopping-cart"></span>
                                Products
                            </a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="users"></span>
                                Customers
                            </a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="bar-chart-2"></span>
                                Reports
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link active" href="/tabs/dms-geolocation.php">
                                <span data-feather="layers"></span>
                                DMS Geolocation
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <section>
                <div class="left">
                    <h2>Get Reverse Geolocation by Longitude & Latitude</h2>
                    <div>
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
                </div>
            </section>

            <!-- Google Maps -->
            <section class="map">
                <div id="map"></div>
            </section>
        </main>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/shared/js/googleGeolocation_Embedded.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDcHfA2WqTubyiS9ABL3Qi8y7xZkf3-s9c&libraries=places&callback=initMap">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="/shared/js/feather.min.js"></script>
    <script src="/shared/js/dashboard.js"></script>
</body>

</html>