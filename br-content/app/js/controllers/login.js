'use strict';

angular.module('blackroom')

.controller('loginController', function($scope, $rootScope, $routeParams, $location, $http, Data) {

    $scope.login = {};
    $scope.signup = {};

    $scope.init = function() {
        Data.get('session').then(function (results) {
            if (results.uid !== undefined && results.uid !== "") {
                $rootScope.authenticated = true;
                $location.path('dashboard');
            }
        });
    }

    $scope.doLogin = function(customer) {
        Data.post('login', {customer: customer}).then(function(results) {
            Data.toast(results);
            if (results.status == "success") {
                $rootScope.authenticated = true;
                $location.path('dashboard');
            }
        });
    };

    $scope.signup = {email:'', password:'', name:''};
    
    $scope.signUp = function(customer) {
        Data.post('signUp', {customer: customer}).then(function(results) {
            Data.toast(results);
            if (results.status == "success") {
                $rootScope.authenticated = true;
                $location.path('dashboard');
            }
        });
    };

    $scope.logout = function() {
        Data.get('logout').then(function(results) {
            Data.toast(results);
            $rootScope.authenticated = false;
            $location.path('login');
        });
    };

});