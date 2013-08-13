angular.module('transactionService', ['ngResource'])
    .factory('Transaction', function($resource) {
        return $resource('/api/transaction/user/fetch', {}, {
            query: { method: 'GET', isArray: true }
        });
    })

;