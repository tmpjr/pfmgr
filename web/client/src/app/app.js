angular.module( 'Application', [
  'templates-app',
  'templates-common',
  'Application.home',
  'Application.dashboard',
  'ui.state',
  'ui.route',
  'ui.bootstrap',
  'ngResource',
  'http-auth-interceptor',
  'securityService',
  'kendo.directives'
])

.config( function myAppConfig ( $stateProvider, $urlRouterProvider ) {

  $urlRouterProvider
    .otherwise( '/dashboard' );
})

.run(function run($rootScope, titleService ) {
  // $rootScope.$on('$locationChangeStart', function(event, next, current) {
  //   $rootScope.modalLogin = true;
  // });

  titleService.setSuffix( ' | Personal Finance Manager' );
})

.directive('authApplication', function($rootScope) {
  return {
    restrict: 'C',
    link: function(scope, elem, attrs) {
      //once Angular is started, remove class:
      //elem.removeClass('waiting-for-angular');

      $rootScope.modalLogin = false;
      //$rootScope.userName = 'Not logged in';
      //var main = elem.find('#content');

      scope.$on('event:auth-loginRequired', function() {
        console.log('loginRequired');
        $rootScope.modalLogin = true;
      });
      scope.$on('event:auth-loginConfirmed', function() {
        console.log('loginConfirmed');
        $rootScope.modalLogin = false;
      });
    }
  };
})

.controller('AppCtrl', function AppCtrl($scope, $rootScope, Security, authService) {
    $scope.opts = {
      backdropFade: true,
      dialogFade: true
    };

    $scope.securityService = Security;
    $scope.username = Security.userName;
    $scope.$watch('securityService.userName', function(newValue) {
      $scope.username = newValue;
    });

    $scope.logout = function() {
      Security.logout().success(function(data, status, headers, config){
        console.log('success logging out...');
        //$rootScope.modalLogin = true;
        $rootScope.$broadcast('event:auth-loginRequired');
      })
      .error(function(data, status, headers, config){
        console.log('error logging out...');
      });
    };

    $scope.login = function() {
      $scope.loginFormSubmitted = true;
      if (this.usrLoginForm.$valid === true) {
        //console.log(this.credentials);
          Security.login(this.credentials)
          .success(function(data, status, headers, config){
              Security.userName = data.username;
              authService.loginConfirmed();
          })
          .error(function(data, status, headers, config){

          });
      }
    };
})

;

