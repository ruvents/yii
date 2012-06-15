var MailerSend = function () {
  $('mailerForm').addEvent('submit', function(e) {
   new Event(e).stop();
    var log = $('log').setText('���� ��������, �����...');
    this.send({
      update: log
    });
  });
}
var GroupManage = {
  
  _int: function() {
    if ($('cparticipants')) {
      GroupManage.getCurParticipants();
      GroupManage.bindParticipantList();
      GroupManage.bindParticipantCtrl();
    }
    if ($('aparticipants')) GroupManage.getAllParticipants();
  },
  
  getCurParticipants: function() {
    new Ajax('/system/modules/group/ajax.manage.php?action=cparticipants&group=' + $('group').getProperty('alt'), {
      method: 'get',
      update: 'cparticipants'
    }).request();
  },
  
  getAllParticipants: function() {
    new Ajax('/system/modules/group/ajax.manage.php?action=aparticipants', {
      method: 'get',
      update: 'aparticipants'
    }).request();
  },
  
  bindParticipantList: function () {
    $('cparticipants').addEvent('change', function() {
      var values = [];
      $$('#cparticipants option').each(function(option, i) {
        if (option.selected) values.push(option.getProperty('alt'));
      });
      $('control').setProperty('value', values.join(','));
    });
  },
  
  bindParticipantCtrl: function () {
    // ������ "������� ���������� ������"
    $('groups').addEvent('change', function() {
      // ID ������ ��� �������
      var gid = $('groups').getValue();
      // ��������� ������������ ������
      new Ajax('/system/modules/group/ajax.manage.php?action=cparticipants&group=' + gid, {
        method: 'get',
        update: 'aparticipants'
      }).request();
    });
    // ������ "��������"
    $('add').addEvent('click', function() {
      // rocID ��� ����������
      var array = $('control').getProperty('value').split(',');
      var value = [];
      // ������� ��������� rocID
      $$('#cparticipants option').each(function(option, i) {
        if (option.getProperty('alt') != null) {
          value.push(option.getProperty('alt'));
        }
      });
      // ��������� ������������ ������
      new Ajax('/system/modules/group/ajax.manage.php?action=addbyrocid&text=' + value.concat(array), {
        method: 'get',
        update: 'cparticipants'
      }).request();
    });
    // ������ "�������"
    $('del').addEvent('click', function() {
      $$('#cparticipants option').each(function(option, i) {
        if (option.selected) option.remove();
      });
    });
    // ������ "��������"
    $('mail').addEvent('click', function() {
      var value = [];
      $$('#cparticipants option').each(function(option, i) {
        if (option.selected && option.getProperty('alt') != null) {
          value.push(option.getProperty('value'));
        }
      });
      if (value != '') {
        new Element('div', {'id': 'mailer'}).injectTop($('group'));
        new Ajax('/system/modules/group/ajax.manage.php?action=mail&text=' + value.join(','), {
          method: 'get',
          update: 'mailer',
          onComplete: MailerSend
        }).request();
      } else {
        alert('�������� ���� �� ������ ���������� ����� ���������� ������ ������!');
      }
    });
    // ������ "����������"
    $('copy').addEvent('click', function() {
      $$('#aparticipants option').each(function(option, i) {
        if (option.selected) {
          var id = option.getProperty('id');
          var cp = $$('#cparticipants option').filterByAttribute('id', '=', id).length;
          if (cp == 0) option.clone().injectInside('cparticipants').setStyle('color', '#008000');
        }
      });
    });
    // ������ "���������"
    $('save').addEvent('click', function() {
      // ��������� ������
      var value = [];
      $$('#cparticipants option').each(function(option, i) {
        value.push(option.getProperty('value'));
      });
      // ����������
      new Ajax('/system/modules/group/ajax.manage.php?action=save&group=' + $('group').getProperty('alt') + '&text=' + value.join(','), {
        method: 'get',
        update: 'log'
      }).request();
    });
  }

}

window.addEvent('domready', function() {
  GroupManage._int();
});