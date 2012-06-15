/*
|---------------------------------------------------------------
| �������������� ��������
|---------------------------------------------------------------
*/

function deleteEmployment(id) {
  el = $('ue_' + id);
  new Ajax('/system/remote/delete.employment.php?id=' + id, {
    method: 'get',
    update: el
  }).request();
}

function deleteActivity(id, user_id) {
  el = $('ue_' + id);
  new Ajax('/system/remote/delete.activity.php?id=' + id + '&user_id=' + user_id, {
    method: 'get',
    update: el
  }).request();
}

/****************************************************************/

window.addEvent('domready', function() {

  /*
  |---------------------------------------------------------------
  | �������������� �������� // ������
  |---------------------------------------------------------------
  */    
  function setWorkAddListners() {
    $$('input.company').each(function(element, i) {
      var indicator = new Element('div', {'class': 'ajax-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter(element);
      var completer = new Autocompleter.Ajax.Json(element, '/system/remote/get.company.php?action=get_company', {
        onRequest: function(element) {
          indicator.setStyle('display', '');
        },
        onComplete: function(element) {
          indicator.setStyle('display', 'none');
        }
      });
    });
  }  
  if ($('add_work_button')) {
    setWorkAddListners();    
    $('add_work_button').addEvent('click', function(e) {
      e = new Event(e).stop();
      var el = new Element('div');
      new Ajax('/system/remote/get.company.php?action=get_work', {
        method: 'get',
        update: el,
        onComplete: function () {
          el.injectBefore($('add_work'));
          setWorkAddListners();
        }
      }).request();
    });
  }
  
  /*
  |---------------------------------------------------------------
  | �������������� �������� // ��������
  |---------------------------------------------------------------
  */
  if ($('add_phone_button')) {
    $('add_phone_button').addEvent('click', function(e) {
      e = new Event(e).stop();
      var el = new Element('div');
      new Ajax('/system/remote/contacts.get.php?action=get_phone', {
        method: 'get',
        update: el,
        onComplete: function () {
          el.injectBefore($('add_phone'));
        }
      }).request();
    });
  }

  /*
  |---------------------------------------------------------------
  | �������������� �������� // �����
  |---------------------------------------------------------------
  */
  if ($('country')) {    
    $('country').addEvent('change', function(e) {
      var value = $('country').getProperty('value');
      new Json.Remote('/system/remote/address.get.php?type=region&id=' + value, {
        onComplete: function(jsonObj) {
          wu.buildOptions(0, '- �������� ������ -', jsonObj.regions, $('region'));
          wu.buildOptions(0, '- �������� ����� -', 0, $('city'));
        }
      }).send();
    });
  }
  
  if ($('region')) {    
    $('region').addEvent('change', function(e) {
      var value = $('region').getProperty('value');
      new Json.Remote('/system/remote/address.get.php?type=city&id=' + value, {
        onComplete: function(jsonObj) {
          wu.buildOptions(0, '- �������� ����� -', jsonObj.cities, $('city'));
        }
      }).send();
    });
  }
  
  /*
  |---------------------------------------------------------------
  | �������������� �������� // ����������
  |---------------------------------------------------------------
  */
  /*if ($('activity_sortables')) {
    new Sortables('#activity_sortables div');
  }*/
  if ($('add_activity_button')) {
    setWorkAddListners();    
    $('add_activity_button').addEvent('click', function(e) {
      e = new Event(e).stop();
      var el = new Element('div');
      new Ajax('/system/remote/get.activity.php?action=get_activity', {
        method: 'get',
        update: el,
        onComplete: function () {
          el.injectBefore($('add_activity'));
        }
      }).request();
    });
  }

});