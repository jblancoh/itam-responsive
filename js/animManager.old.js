
/* animManager.js, WAJAF, the WebAbility(r) Javascript Application Framework
   (c) 2008 Philippe Thomassigny
*/

// individual sprites to anim
function animSprite(id, domNode, callback, script)
{
  var self = this;

  if (typeof domNode == 'string')
    this.domNode = $(domNode);
  else
    this.domNode = domNode;
  if (this.domNode == null)
    return null;

  this.id = id;
  this.callback = callback;
  this.script = script;

  this.timer = null;
  this.starttime = null;
  this.pointer = 0;

  this.getHex = getHex;
  function getHex(v)
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
    self.timer = setTimeout(self.anim, 10);
  }

  this.anim = anim;
  function anim()
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
          self.domNode.style.color = '#' + self.getHex(order.rend) + self.getHex(order.gend) + self.getHex(order.bend);
        if (order.brend != undefined)
          self.domNode.style.backgroundColor = '#' + self.getHex(order.brend) + self.getHex(order.bgend) + self.getHex(order.bbend);
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
      self.timer = setTimeout(self.anim, 10);
    }
    else
    {
      if (order.type == 'wait')
      {
        self.timer = setTimeout(self.anim, order.time - diff );
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
        self.domNode.style.color = '#' + self.getHex(r) + self.getHex(g) + self.getHex(b);
      }
      if (order.brend != undefined)
      {
        var br = order.brinit + Math.ceil((order.brend-order.brinit)/order.time*diff);
        var bg = order.bginit + Math.ceil((order.bgend-order.bginit)/order.time*diff);
        var bb = order.bbinit + Math.ceil((order.bbend-order.bbinit)/order.time*diff);
        self.domNode.style.backgroundColor = '#' + self.getHex(br) + self.getHex(bg) + self.getHex(bb);
      }
      if (order.tend != undefined)
      {
        var t = order.tinit + Math.ceil((order.tend-order.tinit)/order.time*diff);
        self.domNode.style.opacity = t/100;
        self.domNode.style.filter = 'alpha(opacity: '+t+')';
      }
      self.timer = setTimeout(self.anim, 10);
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

  this.sprites = {};
  this.sequences = {};

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
    if (self.sprites[id])
      return self.sprites[id];

    var sp = new animSprite(id, domNode, callback, script);
    if (sp)
      self.sprites[id] = sp;
    return sp;
  }

  this.destroySprite = destroySprite;
  function destroySprite(id)
  {
    if (self.sprites[id])
    {
      self.sprites[id].destroy();
      delete self.sprites[id];
    }
  }

  // sequence is {} of sprites animations
  // loop: true/false
  // chain: [] of {}:
  //    type: 'show', 'hide', 'move', 'wait'
  //    domid: dom on which execute this order
  //    metrics: xinit, xend, yinit, yend, winit, wend, hinit, hend, tinit, tend => x,y: position, w,h: size, t: transparency
  //    time: time to do it in ms
  //    calculate: function to call instead of using position, size and transparency. will get back a metrics object
  //    wait: time to wait before executing next sequence order
  this.createSequence = createSequence;
  function createSequence(id, sequence)
  {

  }

  this.destroySequence = destroySequence;
  function destroySequence(id)
  {
    if (self.sequences[id])
    {
      // remove anything of this sequence
      delete self.sequences[id];
    }
  }

  // Sequences functions



  // flush
  this.destroy = destroy;
  function destroy()
  {
    for (var i in self.sprites)
    {
      self.sprites[i].destroy();
      delete self.sprites[i];
    }
    delete self.sprites;
    for (var i in self.sequences)
      delete self.sequences[i];
    delete self.sequences;
    self = null;
  }

  eventManager.registerFlush(self.destroy);
}

var animManager = new _animManager();
// shortcut
var anim = animManager.createSprite;
