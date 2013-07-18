angular.module('accountService', ['ngResource'])
    .factory('Account', function($resource) {
        return $resource('/api/account/:id', {}, {
            query: { method:'GET', params: { id: 'id' }, isArray:true}
        });
    })

;