
/*
    animManager.js, WAJAF, the WebAbility(r) Javascript Application Framework
    Contains the Manager singleton to manage animation and sprites
    (c) 2008-2009 Philippe Thomassigny

    This file is part of WAJAF

    WAJAF is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WAJAF is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WAJAF.  If not, see <http://www.gnu.org/licenses/>.
*/

// individual sprites to anim
function _animSprite(id, domNode, callback, script)
{
  var self = this;

  if (typeof domNode == 'string')
    this.domNode = $(domNode);
  else
    this.domNode = domNode;
  if (this.domNode == null)
    return;

  this.id = id;
  this.callback = callback;
  this.script = script;

  this.timer = null;
  this.starttime = null;
  this.pointer = 0;

  this.suspendedtime = 0;
  this.suspended = false;

  function _getHex(v)
  {
    if (v < 0 ) v = 0;
    if (v > 255) v = 255;
    var s = v.toString(16).toUpperCase();
    if (s.length < 2)
      s = '0' + s;
    return s;
  }

  this.start = start;
  function start()
  {
    self.starttime = new Date().getTime();
    self.pointer = 0;
    if (self.timer) // previous start
    {
      clearTimeout(self.timer);
      self.timer = null;
    }
    _anim();
  }

  this.suspend = suspend;
  function suspend()
  {
    // no timer ? it is not working so we do not suspend
    if (self.timer && !self.suspended)
    {
      self.suspendedtime = new Date().getTime();
      self.suspended = true;
      clearTimeout(self.timer);
      self.timer = null;
    }
  }

  this.resume = resume;
  function resume()
  {
    if (self.suspended)
    {
      var delta = new Date().getTime() - self.suspendedtime;
      self.starttime += delta;
      self.suspended = false;
      _anim();
    }
  }

  this.stop = stop;
  function stop()
  {
    if (self.timer) // previous start
    {
      clearTimeout(self.timer);
      self.timer = null;
    }
    // we destroy ourself
    setTimeout( function() { animManager.destroySprite(self.id); }, 1);
  }

  function _anim()
  {
    clearTimeout(self.timer);
    self.timer = null;

    var time = new Date().getTime();
    var diff = time - self.starttime;
    var order = self.script.chain[self.pointer];
    if (order.calculate)
      order = order.calculate(diff, order);
    if (diff > order.time)
    {
      if (order.type == 'move')
      {
        if (order.xend != undefined)
          self.domNode.style.left = order.xend + 'px';
        if (order.yend != undefined)
          self.domNode.style.top = order.yend + 'px';
        if (order.wend != undefined)
          self.domNode.style.width = order.wend + 'px';
        if (order.hend != undefined)
          self.domNode.style.height = order.hend + 'px';
        if (order.rend != undefined)
          self.domNode.style.color = '#' + _getHex(order.rend) + _getHex(order.gend) + _getHex(order.bend);
        if (order.brend != undefined)
          self.domNode.style.backgroundColor = '#' + _getHex(order.brend) + _getHex(order.bgend) + _getHex(order.bbend);
        if (order.tend != undefined)
        {
          self.domNode.style.opacity = order.tend/100;
          self.domNode.style.filter = 'alpha(opacity: '+order.tend+')';
        }
      }
      self.pointer++;
      if (!self.script.chain[self.pointer])
      {
        if (!self.script.loop)
        {
          if (self.callback)
            self.callback('end');
          animManager.destroySprite(self.id);
          return;
        }
        self.pointer = 0;
        if (self.callback)
          self.callback('loop');
      }
      self.starttime = new Date().getTime() - diff + order.time; // start new cycle synchronized on last one
      self.timer = setTimeout(_anim, 10);
    }
    else
    {
      if (order.type == 'wait')
      {
        self.timer = setTimeout(_anim, order.time - diff );
        return;
      }
      if (order.xend != undefined)
      {
        var x = order.xinit + Math.ceil((order.xend-order.xinit)/order.time*diff);
        self.domNode.style.left = x + 'px';
      }
      if (order.yend != undefined)
      {
        var y = order.yinit + Math.ceil((order.yend-order.yinit)/order.time*diff);
        self.domNode.style.top = y + 'px';
      }
      if (order.wend != undefined)
      {
        var w = order.winit + Math.ceil((order.wend-order.winit)/order.time*diff);
        self.domNode.style.width = w + 'px';
      }
      if (order.hend != undefined)
      {
        var h = order.hinit + Math.ceil((order.hend-order.hinit)/order.time*diff);
        self.domNode.style.height = h + 'px';
      }
      if (order.rend != undefined)
      {
        var r = order.rinit + Math.ceil((order.rend-order.rinit)/order.time*diff);
        var g = order.ginit + Math.ceil((order.gend-order.ginit)/order.time*diff);
        var b = order.binit + Math.ceil((order.bend-order.binit)/order.time*diff);
        self.domNode.style.color = '#' + _getHex(r) + _getHex(g) + _getHex(b);
      }
      if (order.brend != undefined)
      {
        var br = order.brinit + Math.ceil((order.brend-order.brinit)/order.time*diff);
        var bg = order.bginit + Math.ceil((order.bgend-order.bginit)/order.time*diff);
        var bb = order.bbinit + Math.ceil((order.bbend-order.bbinit)/order.time*diff);
        self.domNode.style.backgroundColor = '#' + _getHex(br) + _getHex(bg) + _getHex(bb);
      }
      if (order.tend != undefined)
      {
        var t = order.tinit + Math.ceil((order.tend-order.tinit)/order.time*diff);
        self.domNode.style.opacity = t/100;
        self.domNode.style.filter = 'alpha(opacity: '+t+')';
      }
      self.timer = setTimeout(_anim, 10);
    }
  }

  this.destroy = destroy;
  function destroy()
  {
    if (self.timer)
      clearTimeout(self.timer);
    self.timer = null;
    self.starttime = null;
    self.pointer = 0;
    self.id = null;
    self.callback = null;
    self.script = null;
    self.domNode = null;
    self = null;
  }

  if (script.autostart)
    this.start();
  return this;
}

function _animManager()
{
  var self = this;

  var counter = 1;   // for idless sprites, private
  this.sprites = {};

  // script is {}
  // autostart: true/false
  // loop: true/false
  // chain: [] of {}:
  //    type: 'move', 'wait'
  //    metrics: xinit, xend, yinit, yend, winit, wend, hinit, hend, tinit, tend   => x,y: position, w,h: size, t: transparency,
  //             rinit, rend, ginit, gend, binit, bend, brinit, brend, bginit, bgend, bbinit, bbend   => red, green, blue & background
  //    time: time to do it in ms
  //    calculate: function to call instead of using position, size and transparency. will get back a metrics object
  // the sprite will be destroyed at the end of script except if loop = on
  this.createSprite = createSprite;
  function createSprite(id, domNode, callback, script)
  {
    if (!id)
      id = 'sprite'+(counter++);
    if (self.sprites[id])
      return self.sprites[id];

    debug('animManager.createSprite('+id+')');
    var sp = new _animSprite(id, domNode, callback, script);
    self.sprites[id] = sp;
    return sp;
  }

  this.fadein = fadein;
  function fadein(domNode, time, callback)
  {
    self.createSprite(domNode.id, domNode, callback, {autostart:true,loop:false,chain:[{type:'move',tinit:0,tend:100,time:time}]});
  }

  this.fadeout = fadeout;
  function fadeout(domNode, time, callback)
  {
    self.createSprite(domNode.id, domNode, callback, {autostart:true,loop:false,chain:[{type:'move',tinit:100,tend:0,time:time}]});
  }

  this.openV = openV;
  function openV(domNode, time, hend, callback)
  {
    self.createSprite(domNode.id, domNode, callback, {autostart:true,loop:false,chain:[{type:'move',hinit:WA.browser.isMSIE?1:0,hend:hend,time:time}]});
  }

  this.closeV = closeV;
  function closeV(domNode, time, hinit, callback)
  {
    self.createSprite(domNode.id, domNode, callback, {autostart:true,loop:false,chain:[{type:'move',hinit:hinit,hend:WA.browser.isMSIE?1:0,time:time}]});
  }

  this.openH = openH;
  function openH(domNode, time, wend, callback)
  {
    self.createSprite(domNode.id, domNode, callback, {autostart:true,loop:false,chain:[{type:'move',winit:WA.browser.isMSIE?1:0,wend:wend,time:time}]});
  }

  this.closeH = closeH;
  function closeH(domNode, time, winit, callback)
  {
    self.createSprite(domNode.id, domNode, callback, {autostart:true,loop:false,chain:[{type:'move',winit:winit,wend:WA.browser.isMSIE?1:0,time:time}]});
  }

  this.open = open;
  function open(domNode, time, wend, hend, callback)
  {
    self.createSprite(domNode.id, domNode, callback, {autostart:true,loop:false,chain:[{type:'move',winit:WA.browser.isMSIE?1:0,wend:wend,hinit:WA.browser.isMSIE?1:0,hend:hend,time:time}]});
  }

  this.close = close;
  function close(domNode, time, winit, hinit, callback)
  {
    self.createSprite(domNode.id, domNode, callback, {autostart:true,loop:false,chain:[{type:'move',winit:winit,wend:WA.browser.isMSIE?1:0,hinit:hinit,hend:WA.browser.isMSIE?1:0,time:time}]});
  }

  this.destroySprite = destroySprite;
  function destroySprite(id)
  {
    if (self.sprites[id])
    {
      debug('animManager.destroySprite('+id+')');
      self.sprites[id].destroy();
      delete self.sprites[id];
    }
  }

  // flush
  function destroy()
  {
    for (var i in self.sprites)
    {
      self.sprites[i].destroy();
      delete self.sprites[i];
    }
    delete self.sprites;
    self = null;
  }

  eventManager.registerFlush(destroy);
}

var animManager = new _animManager();
WA.Managers.anim = animManager;
// shortcut
var anim = animManager.createSprite;
var sprite = animManager.createSprite;
