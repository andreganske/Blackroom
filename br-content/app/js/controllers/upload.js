'use strict';

angular.module('blackroom')

.controller('uploadController', function($scope, $rootScope, $routeParams, $location, $http, Data) {

    $rootScope.pageName = "Minhas imagens";
    
    $scope.selection = [];

    $scope.toggle = function (album) {
		album.selected = !album.selected;
	};

    $scope.init = function() {
    	$scope.getUserImages();
    }

    $scope.getUserImages = function() {
	    Data.get('photo').then(function (results){
	    	$scope.photos = results.data;
	    });
    }


});