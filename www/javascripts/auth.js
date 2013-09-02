var modalAuthObj = null;
$(function(){
  modalAuthObj = new ModalAuth();
});

var ModalAuth = function()
{
  this.modal = $('#ModalAuth');
  this.src = this.modal.data('src');
  this.width = this.modal.data('width');
  this.height = this.modal.data('height');

  this.init();
};
ModalAuth.prototype.init = function()
{
  var self = this;

  self.modal.modal({show: false});
  self.modal.on('show', function(){
    var iframe = $('<iframe></iframe>');
    iframe.attr('src', self.src);
    iframe.attr('width', self.width);
    iframe.attr('height', self.height);
    iframe.attr('scrolling', 'no');
    iframe.attr('frameborder', 0);
    iframe.on('load', function(event){
      self.iFrameResize(event);
    });
    self.modal.append(iframe);
  });
  self.modal.on('hidden', function(){
    $('iframe', self.modal).remove();
  });

  $('#NavbarLogin, #PromoLogin').on('click', function(e){
    e.preventDefault();
    self.modal.modal('show');
  });
};
ModalAuth.prototype.success = function()
{
  var self = this;

  self.modal.modal('hide');
  window.location.reload();
};
ModalAuth.prototype.iFrameResize = function(event)
{
  var target = event.currentTarget;
  $(target).attr('height', target.contentWindow.document.body.offsetHeight);
};


