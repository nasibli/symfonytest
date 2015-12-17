(function() {
    'use strict';

    angular
        .module('App')
        .controller('TeacherController',['TeacherService', TeacherController]);

    function TeacherController (teachSrv) {
        this.teachSrv = teachSrv;
        this.teacher = teachSrv.teacher;
        this.errMsgs = teachSrv.errMsgs;
    }

    TeacherController.prototype.save = function () {
        this.teachSrv.save(this.teacher);
    }
})();