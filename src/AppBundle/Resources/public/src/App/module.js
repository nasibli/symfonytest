angular.module('App',['ngRoute','Lib']);

angular.module('App').config(['$routeProvider',
  function($routeProvider) {
    $routeProvider
      .when('/teacher-list', {
        templateUrl: 'bundles/app/src/App/views/teacher-list.html',
        controller: 'TeacherListController as listCtrl'
      })
      .when('/pupil-list', {
        templateUrl: 'bundles/app/src/App/views/pupil-list.html',
        controller: 'PupilListController as plistCtrl'
      })
      .when('/teacher-pupils/:id/:name', {
        templateUrl: 'bundles/app/src/App/views/teacher-pupil-list.html',
        controller: 'PupilListController as plistCtrl'
      })
      .when('/teacher-add', {
         templateUrl: 'bundles/app/src/App/views/teacher-add.html'/*,
         controller: 'TeacherListController as teachCtrl'*/
      })
      .when('/pupil-add', {
        templateUrl: 'bundles/app/src/App/views/pupil-add.html'
      });
  }]);