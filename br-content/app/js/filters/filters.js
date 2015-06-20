'use strict';

angular.module('blackroom.filters', [])

.filter('unique', function() {
	return function(collection, keyname) {
		var output = [],
			keys = [];

		angular.forEach(collection, function(item) {
			var key = item[keyname];
			if (keys.indexOf(key) === -1) {
				keys.push(key);
				output.push(item);
			}
		});
		return output;
	};
})

.filter('category', function() {
	return function(collection, category) {
		var output = [];

		angular.forEach(collection, function(item) {
			if (item.category.name == category) {
				output.push(item);
			}
		});
		return output;
	};
})

.filter('filterActive', function() {
	return function(status) {
		if (status === 1) {
			return "Ativo";
		} else {
			return "Inativo";
		}
	};
});