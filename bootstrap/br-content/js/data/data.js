'use strict';

angular.module('blackroom.data', [])

.factory("Data", ['$http', 'toaster', function($http, toaster) {
	
	var serviceBase = '/blackroom/br-content/api',
		obj = {};

	obj.toast = function(data) {
		toaster.pop(data.status, "", data.message, 10000, 'trustedHtml');
	}

	obj.get = function(query) {
		return $http.get(serviceBase + query).then(function(results) {
			return results.data;
		});
	}

	obj.post = function(query, object) {
		return $http.post(serviceBase + query, object).then(function(results) {
			return results.data;
		});
	}

	obj.put = function(query, object) {
		return $http.put(serviceBase + query, object).then(function(results) {
			return results.data;
		});
	}

	obj.delete = function(query) {
		return $http.delete(serviceBase + query).then(function(results) {
			return results.data;
		});
	}

	return obj;

}]);