var CUserEditEmployment = function () {
  this.form = $('.user-account-settings form');
  this.iterator = 0;
  this.items = this.form.find('.user-career-items');
  this.templates = {
    'item' : _.template($('#career-item-tpl').html()),
    'itemWithData' : _.template($('#career-item-withdata-tpl').html())
  }
  this.init();
}
CUserEditEmployment.prototype = {
  init : function () {
    var self = this;
    if (typeof employments !== 'undefined') {
      $.each(employments, function (i, data) {
        self.createFillItem(data);
      });
    }
    
    self.form.find('.form-row-add a').click(function () {
      self.createEmptyItem();
      return false;
    });
  },
          
  initItem : function (item) {
    var self = this;
    item.find('.form-row-remove').click(function () {
      item.find('input[name*="Deleted"]').val(1);
      item.hide();
      self.iterator--;
    });
    
    item.find('.form-row-date input[type="checkbox"]').change(function (e) {
      if ($(e.currentTarget).is(':checked')) {
        item.find('.form-row-date select[name*="EndMonth"]').attr('disabled', 'disabled');
        item.find('.form-row-date select[name*="EndYear"]').attr('disabled', 'disabled');
      }
      else {
        item.find('.form-row-date select[name*="EndMonth"]').removeAttr('disabled');
        item.find('.form-row-date select[name*="EndYear"]').removeAttr('disabled');
      }
    }).trigger('change');
  },
          
  createFillItem : function (data) {
    var self = this;
    data.i = self.iterator;
    var template = self.templates.itemWithData(data);
    self.items.append(template);
    var item = self.items.find('div.user-career-item:last-child');
    self.initItem(item);
    item.find('select').each(function () {
      $(this).find('option[value="'+$(this).data('selected')+'"]').attr('selected', 'selected');
      $(this).trigger('change');
    });
    self.iterator++;
  },       
  
  createEmptyItem : function () {
    var self = this;
    var template = self.templates.item({i : self.iterator});
    self.items.append(template);
    var item = self.items.find('div.user-career-item:last-child');
    self.initItem(item);
    self.iterator++;
  }
}
$(function () {
  var userEditEmployment = new CUserEditEmployment();
});


