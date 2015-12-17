(function() {
    'use strict';

    angular
        .module('App')
        .controller('TeacherListController', ['TeacherListService', 'LibGridService', '$location', TeacherListController]);

    function TeacherListController(teachListSrv, libGridSrv, $location) {
        this.teachListSrv = teachListSrv;
        this.gridSrv = new libGridSrv();
        this.location = $location;

        this.gridConf = this.gridSrv.init(this.teachListSrv.getColumns(), this.teachListSrv.api);
        this.gridSrv.load();
    }

    TeacherListController.prototype.getGridConf = function () {
        return this.gridConf;
    }

    TeacherListController.prototype.setFilter = function (name, value) {
        this.gridSrv.setFilter(name, value);
        this.gridSrv.load();
    }

    TeacherListController.prototype.setFilterKeyPress = function (e, name, value) {
        if (this.gridSrv.setFilterKeyPress(e, name, value)) {
            this.gridSrv.load();
        }
    }

    TeacherListController.prototype.addTeacher = function () {
        this.location.url('/teacher-add');
    }
})();