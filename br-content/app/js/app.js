'use strict';

angular.module('blackroom', [
	'ngRoute',
	'ngAnimate',
	'blackroom.directives',
	'blackroom.filters',
	'blackroom.data',
	'angular-loading-bar',
	'ui.bootstrap',
	'toaster',
	'flow',
	])

.config(['$routeProvider', 'flowFactoryProvider', function($routeProvider, flowFactoryProvider) {
	$routeProvider
		.when('/login', {
			title: 'Login',
			templateUrl: 'app/views/login.html',
			controller: 'loginController'
		})
		.when('/logout', {
			title: 'Logout',
			templateUrl: 'app/views/login.html',
			controller: 'logoutCtrl'
		})
		.when('/signup', {
			title: 'Signup',
			templateUrl: 'app/views/signup.html',
			controller: 'loginController'
		})
		.when('/dashboard', {
			title: 'Dashboard',
			templateUrl: 'app/views/dashboard.html',
			controller: 'dashboardController'
		})
		.when('/gallery', {
			title: 'gallery',
			templateUrl: 'app/views/gallery.html',
			controller: 'galleryController'
		})
		.when('/upload', {
			title: 'Upload',
			templateUrl: 'app/views/upload.html',
			controller: 'uploadController'
		})
		.when('/myguests', {
			title: 'Login',
			templateUrl: 'app/views/myguests.html',
			controller: 'guestController',
			role: '0'
		})
		.when('/', {
			title: 'Login',
			templateUrl: 'app/views/login.html',
			controller: 'loginController',
			role: '0'
		})
		.otherwise({
			redirectTo: '/'
		});

		flowFactoryProvider.defaults = {
			target: 'api/v2/upload.php',
			permanentErrors: [404, 500, 501],
			maxChunkRetries: 3,
			chunkRetryInterval: 5000,
			simultaneousUploads: 4
		};

		flowFactoryProvider.on('catchAll', function (event) {
			console.log('catchAll', arguments);
		});
}])

.run(function($rootScope, $location, Data) {

	$rootScope.$on("$routeChangeStart", function(event, next, current) {
		$rootScope.authenticated = false;
		Data.get('session').then(function (results) {
			if (results.uid !== undefined && results.uid !== "") {
				$rootScope.uid = results.uid;
				$rootScope.name = results.name;
				$rootScope.email = results.email;
				$rootScope.email = results.email;
				
				$rootScope.authenticated = true;

				if (results.admin) {
					$location.path("/myguests");
				}
			} else {
				var nextUrl = next.$$route.originalPath;
				if (nextUrl != '/signup' && nextUrl != '/login') {
					$location.path("/login");
				}
			}
		});
	});

});