'use strict';

angular.module('blackroom', [
	'ngRoute',
	'ngAnimate',
	'toaster',
	'blackroom.directives',
    'blackroom.filters',
    'blackroom.data'
    ])

.config(['$routeProvider', function($routeProvider) {
    $routeProvider
        .when('/login', {
            title: 'Login',
            templateUrl: 'views/login.html',
            controller: 'loginController'
        })
        .when('/logout', {
            title: 'Logout',
            templateUrl: 'views/login.html',
            controller: 'logoutCtrl'
        })
        .when('/signup', {
            title: 'Signup',
            templateUrl: 'views/signup.html',
            controller: 'loginController'
        })
        .when('/dashboard', {
            title: 'Dashboard',
            templateUrl: 'views/dashboard.html',
            controller: 'loginController'
        })
        .when('/', {
            title: 'Login',
            templateUrl: 'views/login.html',
            controller: 'loginController',
            role: '0'
        })
        .otherwise({
            redirectTo: '/'
        });	
}])

.run(function($rootScope, $location, Data) {

    $rootScope.$on("$routeChangeStart", function(event, next, current) {
        $rootScope.authenticated = false;
        Data.get('session').then(function (results) {
            if (results.uid) {
                $rootScope.authenticated = true;
                $rootScope.uid = results.uid;
                $rootScope.name = results.name;
                $rootScope.email = results.email;
            } else {
                var nextUrl = next.$$route.originalPath;
                if (nextUrl != '/signup' && nextUrl != '/login') {
                    $location.path("/login");
                }
            }
        });
    });
});