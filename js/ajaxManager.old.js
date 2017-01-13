
/* ajaxManager.js, WAJAF, the WebAbility(r) Javascript Application Framework
   (c) 2008 Philippe Thomassigny
*/

function ajaxRequest(url, method, data, feedback, autosend, listener)
{
  var self = this;
  // parameters
  this.url = url;
  this.method = method.toUpperCase();
  this.data = data;
  this.feedback = feedback;
  this.autosend = autosend;
  // special parameters
  this.period = 0;
  this.times = 0;
  this.timeoutabort = 0;        // time out to abort, no default, let it to the ajax autocontrol.
  this.statefeedback = null;    // consider waiting, error and abort feedbacks
  // working attributes
  this.request = null;
  this.parameters = null;
  this.timer = null;
  this.timerabort = null;
  this.state = 0;               // 0 = nothing, 1 = sent and waiting, 2 = finished, 3 = error
  this.listener = listener;


  try { this.request = new XMLHttpRequest(); }
  catch(e) {
    try { this.request = new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
    catch(e) {
      try { this.request = new ActiveXObject("Msxml2.XMLHTTP"); }
      catch(e) {
        try { this.request = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch(e) {
          alert("XMLHttpRequest not supported");
          return;
        }
      }
    }
  }

  this.callNotify = callNotify;
  function callNotify(event)
  {
    if (self.listener)
    {
      self.listener(event);
    }
  }

  // Special parameters
  this.setPeriodic = setPeriodic;
  function setPeriodic(period, times)
  {
    self.period = period;
    self.times = times;
    return;
  }

  this.addStateFeedback = addStateFeedback;
  function addStateFeedback(statefeedback, timeoutabort)
  {
    self.statefeedback = statefeedback;
    self.timeoutabort = timeoutabort;
    return;
  }

  // Parameters for POST/GET send
  this.addParameter = addParameter;
  function addParameter(id, value)
  {
    if (self.parameters === null)
      self.parameters = {};
    self.parameters[id] = value;
    return;
  }

  this.getParameters = getParameters;
  function getParameters()
  {
    var data = self.data || '';
    for (i in self.parameters)
      data += (data.length > 0?'&':'') + escape(i) + '=' + escape(self.parameters[i]);
    return data;
  }

  this.clearParameters = clearParameters;
  function clearParameters()
  {
    self.parameters = null;
    return;
  }

  // Ajax control
  this.headers = headers;
  function headers()
  {
    self.request.setRequestHeader('X-Requested-With', 'WAJAF::Ajax - WebAbility(r) v5');
    if (self.method == 'POST')
    {
      self.request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      if (self.request.overrideMimeType)
        self.request.setRequestHeader("Connection", "close");
      self.request.setRequestHeader("Method", "POST " + self.url + " HTTP/1.1");
    }
    return;
  }

  this.send = send;
  function send()
  {
    if (self.timer)
      self.timer = null;
    if (self.request.readyState != 0 && self.request.readyState != 4) // still doing something
      return;

    self.request.onreadystatechange = self.process;
    if (self.timeoutabort)
      self.timerabort = setTimeout( function() { self.abort(); }, self.timeoutabort );
    try
    {
      var url = self.url;
      var parameters = self.getParameters();
      if (self.method == 'GET' && parameters.length > 0)
        url += (url.match(/\?/) ? '&' : '?') + parameters;
      self.request.open(self.method, url, true);
      self.headers();
      self.callNotify('start');
      self.request.send(self.method == 'POST' ? parameters : null);
      self.state = 1;
      debug('Enviando request AJAX: '+url, 2);
    }
    catch (e)
    {
      debug('Error creando request AJAX: '+url, 2);
      self.state = 3;
      self.processError(1, e);
    }
    return;
  }

  this.process = process;
  function process()
  {
    try
    {
      if (self.request.readyState == 4)
      {
        if (self.request.status == 200)
        {
          debug('Respuesta AJAX recibida: '+self.url, 2);
          if (self.timerabort)
          {
            clearTimeout(self.timerabort);
            self.timerabort = null;
          }
          self.callNotify('stop');
          if (self.feedback)
          {
            self.feedback(self.request);
          }
          self.state = 2;
        }
        else
        {
          debug('Error en la respuesta AJAX recibida: '+self.url, 2);
          self.state = 3;
          // we call error feedback, or alert
          self.processError(3, "Error "+self.request.status+":\n" + self.request.statusText);
        }
        self.request.onreadystatechange = nothing;  // IE6 CANNOT assign null !!!
        var state = self.checkPeriod();
        if (!state)
          setTimeout( function() { ajaxManager.destroyRequest(self); }, 1);
      }
      else
      {
        self.waiting();
      }
    }
    catch(e)
    {
      debug('Error grave en la respuesta AJAX recibida: '+self.url, 2);
      self.state = 3;
      self.processError(2, e);
    }
    return;
  }

  this.checkPeriod = checkPeriod;
  function checkPeriod()
  {
    if (self.period)
    {
      if (self.times-- > 0)
      {
        self.timer = setTimeout( function() { self.send(); }, self.period);
        return true;
      }
    }
    return false;
  }

  this.waiting = waiting;
  function waiting()
  {
    // dispatcher for user events like "loading...", "making request", "sending information" based on readyState , etc ?
    // could also use a setInterval to periodically call this function to know how is going the call
    if (self.statefeedback)
      self.statefeedback('wait', self.request.readyState, '');
    return;
  }

  // any error
  // type = 1: error sending, 2: error during process, 3: error state != 200, 4: timeout forced
  this.processError = processError;
  function processError(type, error)
  {
    self.callNotify('error');
    if (typeof error == 'object')
      error = error.message;
    // abort and call feedback error
    if (self.statefeedback)
      self.statefeedback('error', type, error);
    else
      alert('Error: '+type+', '+error);
    return;
  }

  // we abort after a given timeout
  this.abort = abort;
  function abort()
  {
    self.timerabort = null;
    if (self.timer)
    {
      clearTimeout(self.timer);
      self.timer = null;
    }
    self.processError(4, 'Timeout');
    self.request.abort();
    self.request.onreadystatechange = null;
    if (!self.checkPeriod())
      setTimeout( function() { ajaxManager.destroyRequest(self); }, 1);
    return;
  }

  this.destroy = destroy;
  function destroy()
  {
    self.period = 0;
    self.times = 0;
    if (self.timerabort)
    {
      clearTimeout(self.timerabort);
      self.timerabort = null;
    }
    if (self.timer)
    {
      clearTimeout(self.timer);
      self.timer = null;
    }
    if (self.state == 1 || self.state == 3)
    {
      self.abort();
    }
    self.request.onreadystatechange = nothing;
    self.clearParameters();
    delete self.request;
    self.statefeedback = null;
    self.feedback = null;
    self = null;
    return;
  }

  if (autosend)
    self.send();
  return;
}

function _ajaxManager()
{
  var self = this;
  this.requests = [];
  this.listener = null;

  this.setListener = setListener;
  function setListener(listener)
  {
    self.listener = listener;
  }

  this.callNotify = callNotify;
  function callNotify(event)
  {
    if (self.listener)
    {
      self.listener(event);
    }
  }

  this.destroyRequest = destroyRequest;
  function destroyRequest(r)
  {
    for (var i=0, l=self.requests.length; i < l; i++)
    {
      if (self.requests[i] == r)
      {
        self.requests[i].destroy();
        self.requests.splice(i, 1);
        self.callNotify('destroy');
        break;
      }
    }
    return;
  }

  this.createRequest = createRequest;
  function createRequest(url, method, data, feedback, dosend)
  {
    self.callNotify('create');
    var r = new ajaxRequest(url, method, data, feedback, dosend, self.listener);
    if (r)
    {
      self.requests.push(r);
    }
    return r;
  }

  this.createPeriodicRequest = createPeriodicRequest;
  function createPeriodicRequest(period, times, url, method, data, feedback, dosend)
  {
    self.callNotify('create');
    var r = new ajaxRequest(url, method, data, feedback, dosend, self.listener);
    if (r)
    {
      self.requests.push(r);
      r.setPeriodic(period, times);
    }
    return r;
  }

  this.destroy = destroy;
  function destroy()
  {
    self.listener = null;
    for (var i=0, l=self.requests.length; i < l; i++)
      self.requests[i].destroy();
    delete self.requests;
    self = null;
    return;
  }

  eventManager.registerFlush(self.destroy);
  return;
}

var ajaxManager = new _ajaxManager();
// shortcuts
var ajax = ajaxManager.createRequest; // @strict
