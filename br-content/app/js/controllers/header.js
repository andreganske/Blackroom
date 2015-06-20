'use strict';

angular.module('blackroom')

.controller('headerController', function($scope, $rootScope, $location) {

   $scope.navLinks = [{
        title: 'Painel de controle',
        linkText: 'dashboard',
    },{
        title: 'Meus albuns',
        linkText: 'gallery'
    },{
        title: 'Minhas imagens',
        linkText: 'upload'
    },{
        title: 'Convidados',
        linkText: 'myguests'
    }];

    $scope.navClass = function(page) {
        var currentRoute = $location.path().substring(1) || 'dashboard';
        return page === currentRoute ? 'active' : '';
    };
    
});