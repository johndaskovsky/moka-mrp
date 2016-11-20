var mokamrp = angular.module('mokamrp', ['ngRoute','ui.bootstrap','ngSanitize']);

mokamrp.config(function($routeProvider) {
  $routeProvider.
    when('/', {
      templateUrl: 'js/mokamrp/templates/home.html?v=1',
      controller: 'mokamrpCtrl'
    }).
    when('/:series', {
      templateUrl: 'js/mokamrp/templates/series.html?v=1',
      controller: 'mokamrpCtrl'
    }).
    otherwise({
      redirectTo: '/'
    });
});

mokamrp.factory('classes', function($http){
  function getClasses(callback){
    $http({
      method: 'GET',
      url: 'https://yogainternational.com/json/live',
      cache: true
    }).success(callback);
  }
  return {
    list: getClasses
  };
});
mokamrp.factory('series', function($http){
  function getSeries(callback){
    $http({
      method: 'GET',
      url: 'https://yogainternational.com/json/live-series',
      cache: true
    }).success(callback);
  }
  return {
    list: getSeries
  };
});

mokamrp.directive('onLastRepeatLiveNow', function() {
  return function($scope, element, attrs) {
    if ($scope.$last) setTimeout(function() {
      $scope.$emit('onRepeatLastLiveNow', element, attrs);
    }, 1);
  };
});
mokamrp.directive('onLastRepeatUpcoming', function() {
  return function($scope, element, attrs) {
    if ($scope.$last) setTimeout(function() {
      $scope.$emit('onRepeatLastUpcoming', element, attrs);
    }, 1);
  };
});
mokamrp.directive('onLastRepeatRecent', function() {
  return function($scope, element, attrs) {
    if ($scope.$last) setTimeout(function() {
      $scope.$emit('onRepeatLastRecent', element, attrs);
    }, 1);
  };
});
mokamrp.directive('onLastRepeatSeries', function() {
  return function($scope, element, attrs) {
    if ($scope.$last) setTimeout(function() {
      $scope.$emit('onRepeatLastSeries', element, attrs);
    }, 1);
  };
});

mokamrp.controller('mokamrpCtrl', function($scope, $rootScope, $routeParams, classes, series, $location) {
  $rootScope.liveTitle = 'Live | Yoga International';
  $rootScope.liveUrl = "https://yogainternational.com/live";
  $scope.limit = 100;
  $scope.classes = [];
  $scope.series = [];

  $scope.filterRecent = function(obj) {
    if (obj.start < Date.now()/1000) {
      return true;
    } else {
      return false;
    }
  };  

  $scope.filterUpcoming = function(obj) {
    if (obj.start > Date.now()/1000) {
      return true;
    } else {
      return false;
    }
  };  

  $scope.filterCurrentSeries = function(obj) {
    if (obj.id == $routeParams.series) {
      return true;
    } else {
      return false;
    }
  };  

  $scope.filterClassInSeries = function(obj) {
    if (obj.live_series == $routeParams.series) {
      return true;
    } else {
      return false;
    }
  }; 

  $scope.greaterThanNow = function(date) {
    if (date > Date.now()/1000) {
      return true;
    } else {
      return false;
    }
  }; 

  $scope.$on('onRepeatLastLiveNow', function() {
    $scope.hideLoading();
    var videoEmbed = $('#live-now-embed').data('video');
    $('#live-now-embed').html(videoEmbed);

    if($("#featured-live-workshop .slide-caption").length === 0) {
      $('#first-live-workshop .slide-caption').clone().appendTo("#featured-live-workshop");  
    }

    addtocalendar.load();
    $('#addToCalData .atcb-list').addClass('dropdown-menu');
    $('#addToCalData .atcb-list').clone().appendTo("#addToCalButtonGroup");
  });
  $scope.$on('onRepeatLastUpcoming', function() {
    if( !$('#upcoming-live-section').hasClass('slick-initialized') ) {
      $scope.slickMultiSlideSm('#upcoming-live-section');
    }
  });
  $scope.$on('onRepeatLastRecent', function() {
    if( !$('#recently-live-section').hasClass('slick-initialized') ) {
      $scope.slickMultiSlideSm('#recently-live-section');
    }
  });
  $scope.$on('onRepeatLastSeries', function() {
    if( !$('#live-series-section').hasClass('slick-initialized') ) {
      $scope.slickMultiSlideSm('#live-series-section');
    }
    if( !$('#series-banner').hasClass('slick-initialized') ) {
      $('#series-banner').slick({
        lazyLoad: 'progressive',
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 500
      });
    }
  });

  $scope.decodeHtml = function(html){
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
  };

  $scope.slickMultiSlideSm = function(idName) {
    $(idName).slick({
      lazyLoad: 'ondemand',
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 4,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  };

  $scope.hideLoading = function(html){
    $('.classes-loading-bar').hide();
    $('.live-content').show();
  };

  $scope.$on('$routeChangeSuccess', function(event) {
    if($routeParams.series === undefined) {
      $('.live-member-workshop-section').show();
    } else {
      $('.live-member-workshop-section').hide();
    }
  });

  classes.list(function(data) { 
    $scope.classes = data;        
  });

  series.list(function(data) { 
    $scope.series = data;       
  });
});
