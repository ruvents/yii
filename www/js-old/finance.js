window.addEvent('domready', function() {
  
  // ������ ���������� ��������
  $('paymentForm').addEvent('submit', function(e) {
    new Event(e).stop();
    this.send({
      update: $('formIncrease')
    });
  });

});