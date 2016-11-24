var data = {};

var mokamrp = angular.module('mokamrp', ['ngRoute','ui.bootstrap','ngSanitize']);

mokamrp.config(function($routeProvider) {
  $routeProvider.
    when('/', {
      templateUrl: 'js/mokamrp/templates/home.html?v=1',
      controller: 'mokamrpCtrl'
    }).
    when('/groups', {
      templateUrl: 'js/mokamrp/templates/groups.html?v=1',
      controller: 'mokamrpCtrl'
    }).
    otherwise({
      redirectTo: '/'
    });
});


mokamrp.controller('mokamrpCtrl', function($scope) {
  $scope.data = data;
  $scope.groups = [];

  $scope.create = function() {
    $scope.groups.push($scope.name);

    $scope.data.groups = $scope.groups;
    data = $scope.data;
  };  

});
