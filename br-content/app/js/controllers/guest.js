'use strict';

angular.module('blackroom')

.controller('guestController', function($scope, $rootScope, $routeParams, $location, $modal, $http, Data) {

    $rootScope.pageName = "Meus convidados";
    $scope.selection = [];

    $scope.toggle = function (guest) {
		guest.selected = !guest.selected;
	};

	$scope.init = function() {
		$scope.getGuests();
	};

	$scope.initGuest = function() {
		$scope.getHost();
		$scope.getAlbuns();
	}

	$scope.getHost = function() {
		Data.get('host').then(function (results){
			$scope.userhost = results.data;
		});
	};

	$scope.getAlbuns = function() {
		Data.get('album').then(function (results){
			$scope.albuns = results.data;
		});
	}

	$scope.getGuests = function() {
	    Data.get('guest').then(function (results){
	    	$scope.guests = results.data;
	    });
	};

	$scope.getSelected = function() {
		$scope.selection = [];
		$.each($scope.guests, function(key, guest) {
			if (guest.selected) {
				$scope.selection.push(guest);
			}
		});
	};

	$scope.create = function() {
		$scope.selection = [];
		$scope.action = 'create';
		$scope.newModalInstance();
	};

	$scope.update = function() {
		this.getSelected();

		if ($scope.selection.length == 0) {
			toaster.pop('warning', "Oooops", "Parece que voce não selecionou nenhum guest para editar :)", 5000);
		} else if ($scope.selection.length != 1) {
			toaster.pop('warning', "Oooops", "Só conseguimos editar um guest de cada vez. Selecione apenas um", 5000);
		} else {
			$scope.action = 'edit';
			$scope.newModalInstance();
		}
	};

	$scope.delete = function() {
		this.getSelected();
	    Data.delete('guest/' + $scope.selection[0].guest_id).then(function (results){
	    	Data.toast(results);
	    	$scope.getGuests();
	    });

	    $scope.selection
	};

	$scope.newModalInstance = function() {
		if ($scope.action === 'create') {
			$scope.modal_title = 'Novo convidado';
		} else {
			$scope.modal_title = 'Editar convidado';
		}

		var modalInstance = $modal.open({
			templateUrl: 'guest.html',
			controller: 'guestModalController',
			scope: $scope,
			resolve: {
				action: function() {
					return $scope.action;
				}
			}
		});

		modalInstance.result.then(function () {
			$scope.getGuests();
		});
	};

})

.controller('guestModalController', function($rootScope, $scope, $modalInstance, Data, toaster, action) {

	if (action === 'edit') {
		$scope.guestName = $scope.selection[0].name;
		$scope.guestDescription = $scope.selection[0].description;
	}

	$scope.handleSaveButton = function() {
		if (action === 'edit') {
			this.edit();
		} else {
			this.save();
		}
	};

	$scope.save = function () {
		Data.post('guest', {guest: $scope.guest}).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
				$modalInstance.close();
            }
        });

	};

	$scope.edit = function () {
		var guest = {};
		guest['name'] = $scope.guestName;
		guest['description'] = $scope.guestDescription;

		Data.put('guest/' + $scope.selection[0].guest_id, guest).then(function (results){
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