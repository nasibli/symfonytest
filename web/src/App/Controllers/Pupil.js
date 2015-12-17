(function() {
    'use strict';

    angular
        .module('App')
        .controller('PupilController', ['PupilService', PupilController]);

    function PupilController (pupilSrv) {
        this.pupilSrv = pupilSrv;
        this.pupil = pupilSrv.pupil;
        this.errMsgs = pupilSrv.errMsgs;
    }

    PupilController.prototype.save = function () {
        this.pupilSrv.save(this.pupil);
    }
})();
