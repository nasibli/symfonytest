(function() {
    'use strict';

    angular
        .module('App')
        .controller('TeacherController',['TeacherService', '$location', TeacherController]);

    function TeacherController (teachSrv, $location) {
        this.teachSrv = teachSrv;
        this.teacher = teachSrv.teacher;
        this.errMsgs = teachSrv.errMsgs;
        this.location = $location;
    }

    TeacherController.prototype.save = function () {
        var self = this;
        this.teachSrv.save(this.teacher)
            .success(function (data) {
                if (data.success) {
                    self.location.url('/teacher-list');
                }
            })
            .error(function (data) {
                console.dir(data);
            });
    }
})();