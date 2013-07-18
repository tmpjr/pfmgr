angular.module('securityService', ['ngResource'])
    .factory('Security', function($http) {
        return {
            userName: 'unknown',
            // userInfo: function() {
            //     $http({
            //         url: '/api/auth/check',
            //         method: 'GET'
            //     }).success(function(){

            //     });
            // },
            login: function(credentials) {
                //console.log(credentials);
                var params = {
                    username: credentials.inputEmail,
                    password: credentials.inputPassword
                };
                //console.log(params);
                return $http({
                    url: '/api/auth/check',
                    method: 'POST',
                    data: params
                });
            },
            logout: function() {
                return $http({
                    url: '/api/auth/logout',
                    method: 'GET'
                });
            }
        };
    })
;