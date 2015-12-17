<!DOCTYPE html>
<html ng-app="App">
<head>
    <meta charset="UTF-8" />
    <title>Школа китайского языка</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="angular-ui-grid/ui-grid.css">
    <link rel="stylesheet" href="main.css">
</head>
<body>
<nav id="top-nav" class="navbar navbar-default">
    <div class="container-fluid">
        <H1 class="navbar-text navbar-left">Школа китайского языка</H1>
    </div>
</nav>
<div class="clearfix"></div>
<div id="wrapper" class="container-fluid">
    <div id="sidebar-left" class="col-lg-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="#/teacher-list"> Учителя </a></li>
            <li><a href="#/pupil-list"> Ученики </a></li>
        </ul>
    </div>
    <div id="content" ng-view class="col-lg-10 col-lg-offset-2">

    </div>

</div>
<script src="angular/angular.js"></script>
<script src="angular/angular-route.js"></script>
<script src="angular-ui-grid/ui-grid.js"></script>
<script src="src/App/module.js"></script>
<script src="src/Lib/module.js"></script>
<script src="src/App/Controllers/TeacherList.js"></script>
<script src="src/App/Controllers/Teacher.js"></script>
<script src="src/App/Services/Teacher.js"></script>
<script src="src/App/Services/TeacherList.js"></script>
<script src="src/App/Controllers/PupilList.js"></script>
<script src="src/App/Services/PupilList.js"></script>
<script src="src/App/Controllers/Pupil.js"></script>
<script src="src/App/Services/Pupil.js"></script>
<script src="src/Lib/Services/Grid.js"></script>
</body>
</html>