(function() {
    'use strict';

    angular
        .module('App')
        .controller('PupilListController', ['PupilListService', 'LibGridService', '$location', '$routeParams',
            PupilListController]);

    function PupilListController(pupilListSrv, libGridSrv, $location, $routeParams) {
        this.pupilListSrv = pupilListSrv;
        this.gridSrv = new libGridSrv();
        this.location = $location;
        this.teacherName = '';
        if ($routeParams.name) {
            this.teacherName = $routeParams.name;
        }
        this.gridConf = this.gridSrv.init(this.pupilListSrv.getColumns(), this.pupilListSrv.api);
        if ($routeParams.id) {
            this.gridSrv.setFilter('id', $routeParams.id);
        }
        this.gridSrv.load();
    }

    PupilListController.prototype.getGridConf = function () {
        return this.gridConf;
    }

    PupilListController.prototype.setFilter = function (name, value) {
        this.pupilListSrv.setFilter(name, value);
        this.pupilListSrv.load();
    }

    PupilListController.prototype.setFilterKeyPress = function (e, name, value) {
        if (this.pupilListSrv.setFilterKeyPress(e, name, value)) {
            this.pupilListSrv.load();
        }
    }

    PupilListController.prototype.addPupil = function () {
        this.location.url('/pupil-add');
    }
})();