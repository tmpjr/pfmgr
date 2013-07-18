angular.module( 'Application.home', [
  'ui.state',
  'placeholders',
  'ui.bootstrap',
  'titleService',
  'ngResource'
])

.config(function config( $stateProvider ) {
  $stateProvider.state( 'home', {
    url: '/home',
    views: {
      "main": {
        controller: 'HomeCtrl',
        templateUrl: 'home/home.tpl.html'
      }
    }
  });
})

.controller('HomeCtrl', function HomeCtrl($scope, titleService) {


  titleService.setTitle('Home');
})

;
