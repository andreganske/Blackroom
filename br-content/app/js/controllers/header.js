'use strict';

angular.module('blackroom')

.controller('headerController', function($scope, $rootScope, $location) {

   $scope.navLinks = [{
        title: 'Dashboard',
        linkText: 'dashboard',
    },{
        title: 'Albuns',
        linkText: 'gallery'
    },{
        title: 'Incluir fotos',
        linkText: 'upload'
    }];

    $scope.navClass = function(page) {
        var currentRoute = $location.path().substring(1) || 'dashboard';
        return page === currentRoute ? 'active' : '';
    };
    
});