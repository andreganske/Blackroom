'use strict';

angular.module('blackroom')

.controller('uploadController', function($scope, $rootScope, $routeParams, $location, $http, Data, Upload) {

    $rootScope.pageName = "Upload de imagens";

    $scope.$watch('files', function (files) {

        $scope.formUpload = false;
        
        if (files != null) {    
            for (var i = 0; i < files.length; i++) {
                $scope.errorMsg = null;
                (function (file) {
                    upload(file);
                })(files[i]);
            }
        }
    });

    $scope.upload = function (file) {
        file.upload = Upload.http({
            url: serviceBase + '/upload' + $scope.getReqParams(),
            method: 'POST',
            headers: {
                'Content-Type': file.type
            },
            data: file
        });

        file.upload.then(function (response) {
            file.result = response.data;
        }, function (response) {
            if (response.status > 0) {
            	Data.toast(response);
            }
        });

        file.upload.progress(function (evt) {
            file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
        });
    };

    angular.element(window).bind('dragover', function (e) {
        e.preventDefault();
    });

    angular.element(window).bind('drop', function (e) {
        e.preventDefault();
    });

});