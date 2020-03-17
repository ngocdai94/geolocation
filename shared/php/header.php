<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <!-- <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors"> -->
    <!-- <meta name="generator" content="Jekyll v3.8.6"> -->
    <title><?=$title?></title>

        <!-- Favicons -->
    <!-- <link rel="apple-touch-icon" href="/docs/4.4/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/4.4/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/4.4/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/4.4/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/4.4/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="/docs/4.4/assets/img/favicons/favicon.ico">
    <meta name="msapplication-config" content="/docs/4.4/assets/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c"> -->

    <!-- <link rel="canonical" href="https://getbootstrap.com/docs/4.4/examples/dashboard/"> -->

    <link rel="stylesheet" type="text/css" media="screen" href="/shared/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="/shared/css/all.css"/>
    <!-- Bootstrap core CSS -->
    <!-- <link rel="stylesheet" type="text/css" media="screen" href="<//?=$dmsCSS?>"/> -->
    <!-- <style>
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
    </style> -->

    <!-- Custom styles for this template -->
    <link href="/shared/css/dashboard.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="/">Testing Template</a>
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
                            <a id="homeLink" class="nav-link active" href="#home" onclick="showHome()">
                                <span data-feather="home"></span>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="mysqlLink" class="nav-link" href="#mysql" onclick="showHideMySQL()">
                                <span data-feather="file"></span>
                                MySQL Database
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="dmsLink" class="nav-link" href="#dms" onclick="showHideDMS()">
                                <span data-feather="layers"></span>
                                DMS Geolocation
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>