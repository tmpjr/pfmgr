angular.module( 'Application.dashboard', [
  'ui.state',
  'placeholders',
  'ui.bootstrap',
  'titleService',
  'accountService',
  'transactionService',
  'ngResource',
  'kendo.directives'
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

.controller('DashboardCtrl', function DashboardCtrl($scope, Account, Transaction, Security, titleService) {
  $scope.username = Security.userName;

  // $scope.account = Account.get({ id: 1 }, function(account){
  //   console.log('account: ', account.name);
  // });

  //$scope.gridData = Transaction.query();
  //console.log($scope.gridData);

  // $scope.rowClass = function(transaction) {
  //   //return transaction === $scope.geneSelected ? 'info' : undefined;
  // };

  $scope.gridOptions = {
    dataSource: {
      data: Transaction.query(),
      aggregate: [{ field: 'amount', aggregate: 'sum' }]
    },
    //pageSize: 3,
    sortable: true,
    columns: [
      { field: 'accountName', title: 'Account'},
      { field: 'description', title: 'Description' },
      { field: 'transactionDate', title: 'Date' },
      {
        field: 'amount',
        title: 'Amount',
        headerAttributes: { style: 'text-align: right' },
        attributes: { style: 'text-align: right' },
        footerTemplate: "Total:"
      }
    ]
  };

  titleService.setTitle( 'Dashboard' );
})

;
