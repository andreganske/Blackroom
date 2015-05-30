'use strict';

angular.module('blackroom', [
	'ngRoute',
	'ngAnimate',
	'toaster',
	'blackroom.directives',
    'blackroom.filters',
    'blackroom.data'
    ])

.config(function($routeProvider) {
    $routeProvider.when('/login', {
        title: 'Login',
        templateUrl: 'br-content/views/login.html',
        controller: 'loginController'
    });


    $routeProvider.when('/logout', {
        title: 'Logout',
        templateUrl: 'br-content/views/login.html',
        controller: 'logoutCtrl'
    });

    $routeProvider.when('/signup', {
        title: 'Signup',
        templateUrl: 'br-content/views/signup.html',
        controller: 'loginController'
    });

    $routeProvider.when('/dashboard', {
        title: 'Dashboard',
        templateUrl: 'br-content/views/dashboard.html',
        controller: 'loginController'
    });

    $routeProvider.when('/', {
        title: 'Login',
        templateUrl: 'br-content/views/login.html',
        controller: 'loginController',
        role: '0'
    });

    $routeProvider.otherwise({
        redirectTo: '/login'
    });	
})

.run(function($rootScope, $location, Data){

	$rootScope.$on('$routeChangeStart', function(event, next, current) {
		$rootScope.authenticated = false;

		Data.get('session').then(function(results) {
			if (results.uid) {
				$rootScope.authenticated = true;
				$rootScope.uid = results.uid;
				$rootScope.name = results.name;
				$rootScope.email = results.email;
			} else {
				var nextUrl = next.$route.originalPath;
				if (nextUrl != '/signup' && nextUrl != '/login') {
					$location.path('/login');
				}
			}
		});
	});
});