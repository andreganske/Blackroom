'use strict';

angular.module('blackroom')

.controller('galleryController', function($scope, $rootScope, $routeParams, $location, $modal, $http, Data) {

    $rootScope.pageName = "Meus albuns";

    $scope.selection = [];

    $scope.toggle = function (album) {
		album.selected = !album.selected;
	};

	$scope.init = function() {
		$scope.getAlbuns();
	}

	$scope.getAlbuns = function() {
	    Data.get('album').then(function (results){
	    	$scope.albuns = results.data;
	    });
	}

	$scope.getSelected = function() {
		$scope.selection = [];
		$.each($scope.albuns, function(key, album) {
			if (album.selected) {
				$scope.selection.push(album);
			}
		});
	};

	$scope.newModalInstance = function() {
		if ($scope.action === 'create') {
			$scope.modal_title = 'Novo album';
		} else {
			$scope.modal_title = 'Editar album';
		}

		var modalInstance = $modal.open({
			templateUrl: 'album.html',
			controller: 'albumModalController',
			scope: $scope,
			resolve: {
				action: function() {
					return $scope.action;
				}
			}
		});

		modalInstance.result.then(function () {
			$scope.getAlbuns();
		});
	};

	$scope.new = function() {
		$scope.selection = [];
		$scope.action = 'create';
		$scope.newModalInstance();
	};

	$scope.edit = function() {
		this.getSelected();

		if ($scope.selection.length == 0) {
			toaster.pop('warning', "Oooops", "Parece que voce não selecionou nenhum album para editar :)", 5000);
		} else if ($scope.selection.length != 1) {
			toaster.pop('warning', "Oooops", "Só conseguimos editar um album de cada vez. Selecione apenas um", 5000);
		} else {
			$scope.action = 'edit';
			$scope.newModalInstance();
		}
	};

	$scope.delete = function() {
		this.getSelected();
	    Data.delete('album/' + $scope.selection[0].album_id).then(function (results){
	    	Data.toast(results);
	    	$scope.getAlbuns();
	    });

	    $scope.selection
	};
})

.controller('albumModalController', function($rootScope, $scope, $modalInstance, Data, toaster, action) {

	if (action === 'edit') {
		$scope.albumName = $scope.selection[0].name;
		$scope.albumDescription = $scope.selection[0].description;
	}

	$scope.handleSaveButton = function() {
		if (action === 'edit') {
			this.edit();
		} else {
			this.save();
		}
	};

	$scope.save = function () {
		var album = {};
		album['name'] = $scope.albumName;
		album['description'] = $scope.albumDescription;

		Data.post('album', album).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
				$modalInstance.close();
            }
        });

	};

	$scope.edit = function () {
		var album = {};
		album['name'] = $scope.albumName;
		album['description'] = $scope.albumDescription;

		Data.put('album/' + $scope.selection[0].album_id, album).then(function (results){
            Data.toast(results);
            if (results.status == "success") {
				$modalInstance.close();
            }
        });
	};

	$scope.cancel = function () {
		$modalInstance.dismiss('cancel');
	};
});