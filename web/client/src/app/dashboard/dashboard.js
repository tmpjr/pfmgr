angular.module( 'Application.dashboard', [
  'ui.state',
  'placeholders',
  'ui.bootstrap',
  'titleService',
  'accountService',
  'ngResource'
])

.config(function config($stateProvider) {
  $stateProvider.state( 'dashboard', {
    url: '/dashboard',
    views: {
      "main": {
        controller: 'DashboardCtrl',
        templateUrl: 'dashboard/dashboard.tpl.html'
      }
    }
  });
})

.controller('DashboardCtrl', function DashboardCtrl($scope, Account, Security, titleService) {
  $scope.username = Security.userName;

  $scope.account = Account.get({ id: 1 }, function(account){
    console.log('account: ', account.name);
  });

  titleService.setTitle( 'Dashboard' );
})

;
