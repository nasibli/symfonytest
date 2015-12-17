(function() {
    'use strict';

    angular
        .module('App')
        .controller('PupilController', ['PupilService', '$location', PupilController]);

    function PupilController (pupilSrv, $location) {
        this.pupilSrv = pupilSrv;
        this.pupil = pupilSrv.pupil;
        this.errMsgs = pupilSrv.errMsgs;
        this.location = $location;
    }

    PupilController.prototype.save = function () {
        var self = this;
        this.pupilSrv.save(this.pupil)
            .success(function (data) {
                if (data.success) {
                    self.location.url('/pupil-list');
                }
            })
            .error(function (data) {
                console.dir(data);
            });
    }
})();
