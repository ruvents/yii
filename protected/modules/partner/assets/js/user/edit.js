var PartnerApp = angular.module('PartnerApp', ['ngSanitize']);

var CUserEdit = function (data) {
    this.data = data;
    this.init();
}
CUserEdit.prototype = {
    'init' : function () {
        var self = this;
        PartnerApp.controller('UserEditController', function($scope, $sce) {
            $scope.participants = self.data.participants;
            $scope.products = self.data.products;
            $scope.data = self.data.data;

            $.each($scope.data, function (i, row) {
                $.each(row, function (j, item) {
                    $scope.data[i][j].edit = $sce.trustAsHtml(item.edit);
                });
            });

            $.each($scope.participants, function (i, participant) {
                $scope.$watch(function () {
                    return participant.role;
                }, function (role, oldRole) {
                    $scope.changeRole(participant, role);
                });
            });

            $scope.changeRole = function (participant, role) {
                var $modal = $('<div/>', {
                    'class' : $('#yw0').attr('class'),
                    'html'  : $('#yw0').html()
                });
                $modal.on('hidden.bs.modal', function () {
                    $modal.remove();
                });
                $modal.find('.modal-footer .btn.btn-primary').click(function () {
                    var params = {
                        'action' : 'changeRole',
                        'role' : role,
                        'message' : $modal.find('.modal-body textarea').val()
                    };

                    $scope.setSavingState(participant);
                    $scope.$apply();
                    $.ajax({method: 'POST', url: '', data: params})
                        .done(function() {
                            $scope.setSuccessState(participant);
                        })
                        .fail(function () {
                            $scope.setErrorState(participant);
                        });
                    $modal.modal('hide');
                });
                $modal.modal('show');
            }

            $scope.clearParticipantMessageByTimeout = function (participant) {
                setTimeout(function (){
                    participant['class'] = null;
                    participant.message  = '';
                    $scope.$apply();
                }, 1000);
            }

            $scope.changeProduct = function (product) {
                var params = {'product' : product.Id};

                if (product.Paid) {
                    params.action = 'createOrderItem';
                } else {
                    params.action = 'deleteOrderItem';
                }

                $scope.setSavingState(product);
                $.ajax({method: 'POST', url: '', data: params})
                    .done(function(response) {
                        if (response.error) {
                            $scope.setErrorState(product, 'Ошибка: ' + response.message);
                            product.Paid = !product.Paid;
                        } else {
                            $scope.setSuccessState(product);
                        }
                    })
                    .fail(function () {
                        product.Paid = !product.Paid;
                        $scope.setErrorState(product);
                    });
            }

            $scope.enableDataEditMode = function (data) {
                $.each(data, function (i, item) {
                    item.editMode = true;
                });
            }

            $scope.setSavingState = function (item) {
                item['class'] = 'warning dark';
                item.message  = 'Сохранение...';
            }

            $scope.setSuccessState = function (item) {
                item['class'] = 'success';
                item.message  = 'Изменения сохранены!';
                $scope.$apply();
                $scope.clearState(item);
            }

            $scope.setErrorState = function (item, message) {
                item['class'] = 'error';
                item.message  = typeof(message) != "undefined" ? message : 'Ошибка при сохранении!';
                $scope.$apply();
                $scope.clearState(item);
            }

            $scope.clearState = function (item) {
                setTimeout(function (){
                    item['class'] = null;
                    item.message  = '';
                    $scope.$apply();
                }, 2000);
            }
        });
    }
}