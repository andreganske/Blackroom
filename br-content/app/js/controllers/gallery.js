'use strict';

angular.module('blackroom')

.controller('galleryController', function($scope, $rootScope, $routeParams, $location, $http, Data) {

    $rootScope.pageName = "Galeria de imagens";

    Data.get('photos').then(function(data){
    	$scope.photos = data.data;
    });

});