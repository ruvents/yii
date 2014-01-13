COrderEdit = function () {
  this.orderitems = $('table#order-items tbody');
  this.init();
}
COrderEdit.prototype = {
  init : function () {
    var self = this;
    this.loadOrderItem();
    this.initNewOrderItemForm();
  },
  loadOrderItem : function () {
    var self = this;
    self.orderitems.html('<tr><td colspan="4" class="muted" style="text-align: center">Идет загрузка...</td></tr>');
    $.getJSON('', {'Method' : 'GetItemList'}, function (response) {
      self.orderitems.html('');
      $.each(response, function (i, item) {
        var tr = $('<tr/>', {
          'html' : '<td>'+item.Owner+'</td><td>'+item.Product+'</td><td>'+item.Price+'</td><td><a href="#" class="btn btn-mini btn-danger pull-right">Удалить</a></td>'
        });
        tr.data('order-item-id', item.Id);
        tr.find('.btn.btn-danger').click(function (e) {
          e.preventDefault();
          if (confirm("Вы точно хотите удалить этот заказ?")){
            $.getJSON('', {'Method' : 'DeteleItem', 'OrderItemId' : tr.data('order-item-id')}, function (result) {
              if (result.success) {
                tr.remove();
              }
              else {
                self.showModal('Ошибка!', result.message);
              }
            });
          }
        });
        self.orderitems.append(tr);
      });
    });
  },

  initNewOrderItemForm : function () {
    var self = this;
    var form = self.orderitems.parent('table').parent('form');

    form.find('input[type="text"]:eq(0)').autocomplete({
      'minLength' : 2,
      'source' : '/user/ajax/search',
      'select' : function (event, ui) {
        form.find('input[name="RunetId"]').val(ui.item.value);
        $(this).val(ui.item.label);
        return false;
      }
    })

    form.submit(function (e) {
      e.preventDefault();
      var data = form.serializeArray();
      form.find('input,select').val('');
      data.push({'name' : 'Method', 'value' : 'CreateItem'});
      $.getJSON('', data, function (response) {
        if (response.success) {
          self.loadOrderItem();
        } else {
          self.showModal('Ошибка!', response.message);
        }
      });
    });
  },

  showModal : function (title, text) {
    var $modal = $('<div/>', {
      'class' : 'modal'
    });
    $modal.html(
      '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3>'+title+'</h3></div>'
     +'<div class="modal-body">'+text+'</div>'
    );
    $modal.on('hidden', function () {
      $modal.remove();
    });
    $('body').append($modal);
    $modal.modal('show');
  }
}
$(function () {
  new COrderEdit();
});