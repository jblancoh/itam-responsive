
/*
    core.js, WAJAF, the WebAbility(r) Javascript Application Framework
    Contains multi purpose functions, browser and WA objects
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

// WA is the main WAJAF Object that will contain anything else (except for the native JS object prototypes)
var WA = { version: '1.00.18',
           running: false };

// Main Javascript Native Object Prototypes

// builds a function based on the transformation of the main function.
// The parameters are sent to the prefunction to be transformed if necesary
// The result of the main funciton is sent to the post function with the same parameters to be transformed if necesary.
Function.prototype.buildTransformer = function(prefct, postfct, scope)
{
  var self = this;
  if (!WA.isFunction(prefct) && !WA.isFunction(postfct))
    return this;
  return function()
    {
      var args = WA.isFunction(prefct)?prefct.apply(scope || self, arguments):arguments;
      var ret = self.apply(scope || self, args);
      return WA.isFunction(postfct)?postfct.apply(scope || self, [ret]):ret;
    }
}

// will call our fct before executing the main Function.
// fct accept the same parameters as main Function.
// if fct returns true, the main Function is executed, otherwise no.
Function.prototype.buildFilter = function(fct, scope)
{
  var self = this;
  if (!WA.isFunction(fct))
    return this;
  return function()
    {
      return (fct.apply(scope || self, arguments) == true) ? self.apply(scope || self, arguments) : null;
    }
}

// Builds a callback function based on the main function scope with the specified parameters to be able to call it without parameters by another instance.
Function.prototype.buildCompact = function()
{
  var self = this;
  var args = arguments;
  return function()
    {
      var r1 = Array.prototype.slice.call(args);
      var r2 = Array.prototype.slice.call(arguments);
      return self.apply(self, r1.concat(r2));
    }
}

Function.prototype.delay = function(delay)
{
  var self = this;
  var args = [];
  for (var i = 1, l = arguments.length; i < l; args.push(arguments[i++]));
  var t = setTimeout(function() { return self.apply(self, args); }, delay);
  return t;
}

// String prototypes
String.prototype.sprintf = function()
{
  if (WA.isObject(arguments[0]))
  {
    var args = arguments[0];
    return this.replace(/\{([A-Za-z0-9\-_\.]+)\}/g, function(p0, p1){ return args[p1]; });
  }
  else
  {
    var args = arguments;
    return this.replace(/\{(\d+)\}/g, function(p0, p1){ return args[p1]; });
  }
}

String.prototype.escape = String.escape = function(value)
{
  var newstr = (value != undefined && value != null) ? value : this;
  return newstr.replace(/("|'|\\)/g, "\\$1");
}

String.prototype.padding = String.padding = function(size, pad, value)
{
  if (!pad) pad = ' ';
  var newstr = new String((value != undefined && value != null) ? value : this);
  while (newstr.length < size)
  {
    newstr = pad + newstr;
  }
  return newstr;
}

// Array prototypes
Array.prototype.indexOf = function(val, field)
{
  for (var i = 0, l = this.length; i < l; i++)
  {
    if ((field && this[i][field] == val) || (!field && this[i] == val))
      return i;
  }
  return false;
}

Array.prototype.remove = function(o, field)
{
  var index = this.indexOf(o, field);
  if(index != -1)
  {
    this.splice(index, 1);
  }
  return this;
}

// Date basic functions
Date.prototype.setNames = function(days, shortdays, months, shortmonths)
{
  Date.prototype.days = days;
  Date.prototype.shortdays = shortdays;
  Date.prototype.months = months;
  Date.prototype.shortmonths = shortmonths;
}

// english by default
Date.prototype.setNames(
  ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
  ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
  ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
  ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
);

Date.prototype.basicdays = [31,28,31,30,31,30,31,31,30,31,30,31];

Date.isDate = Date.prototype.isDate = function(year, month, day)
{
  var numdays = Date.prototype.basicdays[month-1];
  return day>0 && !!numdays && (day<=numdays || day==29 && year%4==0 && (year%100!=0 || year%400==0) );
}

Date.isTime = Date.prototype.isTime = function(hour, min, sec)
{
  return hour>=0 && hour<=23 && min>=0 && min<=59 && sec>=0 && sec<=59;
}

Date.isValid = Date.prototype.isValid = function(year, month, day, hour, min, sec, ms)
{
  hour = hour || 0;
  min = min || 0;
  sec = sec || 0;
  ms = ms || 0;
  return Date.isDate(year, month, day) && Date.isTime(hour, min, sec) && ms >= 0 && ms <= 999;
}

Date.prototype.isLeapYear = function()
{
  var year = this.getFullYear();
  return (year%4==0 && (year%100!=0 || year%400==0));
}

Date.prototype.getOrdinalSuffix = function()
{
  switch (this.getDate())
  {
    case 1: case 21: case 31: return "st";
    case 2: case 22:          return "nd";
    case 3: case 23:          return "rd";
    default:                  return "th";
  }
}

Date.prototype.getMaxMonthDays = function()
{
  var numdays = this.basicdays[this.getMonth()];
  if (numdays == 28 && this.isLeapYear())
    numdays++;
  return numdays;
}

Date.prototype.getDayOfYear = function()
{
  var day = this.getDate() - 1;
  for (var i = 0; i < this.getMonth()-1; i++)
    day += this.basicdays[i] + (i==1&&this.isLeapYear()?1:0);
  return day;
}

// adapted from http://www.merlyn.demon.co.uk/weekcalc.htm
Date.prototype.getWeekOfYear = function()
{
  var ms1d = 86400000;
  var ms7d = 604800000;
  var DC3 = Date.UTC(this.getFullYear(), this.getMonth(), this.getDate() + 3) / ms1d;
  var AWN = Math.floor(DC3 / 7);
  var Wyr = (new Date(AWN * ms7d)).getUTCFullYear();
  return AWN - Math.floor(Date.UTC(Wyr, 0, 7) / ms7d) + 1;
}

Date.prototype.getGMTOffset = function(colon)
{
  return (this.getTimezoneOffset() > 0 ? "-" : "+")
      + String.padding(2, "0", Math.floor(Math.abs(this.getTimezoneOffset()) / 60))
      + (colon ? ":" : "")
      + String.padding(2, "0", Math.abs(this.getTimezoneOffset() % 60));
}

// by extJS
Date.prototype.getTimezone = function()
{
  return this.toString().replace(/^.* (?:\((.*)\)|([A-Z]{1,4})(?:[\-+][0-9]{4})?(?: -?\d+)?)$/, "$1$2").replace(/[^A-Z]/g, "");
}

// original idea of structure/pattern by extJS
Date.prototype.grabformats = {
  j: "this.getDate()",                                           // day of the month, no leading 0
  d: "String.padding(2, '0', this.getDate())",                   // day of the month, leading 0
  D: "this.shortdays[this.getDay()]",                            // short name of day
  l: "this.days[this.getDay()]",                                 // full name of day

  w: "this.getDay()",                                            // day of the week, 0 = sunday
  N: "(this.getDay()==0?7:this.getDay())",                       // ISO day of the week, 1 = monday
  S: "this.getOrdinalSuffix()",                                  // english day of the week suffix

  z: "this.getDayOfYear()",                                      // day of the year, 0 to 365

  W: "String.padding(2, '0', this.getWeekOfYear())",             // ISO week of the year, leading 0

  n: "(this.getMonth() + 1)",                                    // number of month, 1 to 12, no leading 0
  m: "String.padding(2, '0', this.getMonth() + 1)",              // number of month, 01 to 12, leading 0
  M: "this.shortmonths[this.getMonth()]",                        // short name of month
  F: "this.months[this.getMonth()]",                             // full name of month
  t: "this.getMaxMonthDays()",                                   // number of days into the month

  L: "(this.isLeapYear() ? 1 : 0)",
  o: "(this.getFullYear() + (this.getWeekOfYear() == 1 && this.getMonth() > 0 ? +1 : (this.getWeekOfYear() >= 52 && this.getMonth() < 11 ? -1 : 0)))",
  Y: "this.getFullYear()",
  y: "('' + this.getFullYear()).substring(2, 4)",

  a: "(this.getHours() < 12 ? 'am' : 'pm')",
  A: "(this.getHours() < 12 ? 'AM' : 'PM')",
  g: "((this.getHours() % 12) ? this.getHours() % 12 : 12)",
  G: "this.getHours()",
  h: "String.padding(2, '0', (this.getHours() % 12) ? this.getHours() % 12 : 12)",
  H: "String.padding(2, '0', this.getHours())",

  i: "String.padding(2, '0', this.getMinutes())",
  s: "String.padding(2, '0', this.getSeconds())",
  u: "String.padding(3, '0', this.getMilliseconds())",

  O: "this.getGMTOffset()",
  P: "this.getGMTOffset(true)",
  T: "this.getTimezone()",
  Z: "(this.getTimezoneOffset() * -60)",
  c: "this.getUTCFullYear() + '-' + String.padding(2, '0', this.getUTCMonth() + 1) + '-' + String.padding(2, '0', this.getUTCDate()) + 'T' + "
        + "String.padding(2, '0', this.getUTCHours()) + ':' + String.padding(2, '0', this.getUTCMinutes()) + ':' + "
        + "String.padding(2, '0', this.getUTCSeconds()) + 'Z'",
  U: "Math.round(this.getTime() / 1000)"
};

Date.prototype.format = function(str)
{
  var code = [];
  for (var i = 0, l = str.length; i < l; i++)
  {
    var c = str.charAt(i);
    if (c == "\\")
    {
      i++;
      code.push("'" + String.escape(str.charAt(i)) + "'");
    }
    else
    {
      this.grabformats[c]!=undefined?code.push(this.grabformats[c]):code.push("'" + String.escape(c) + "'");
    }
  }

  var f = new Function('return ' + code.join('+') + ';');
  return f.call(this);
}


// Main WAJAF Object definition

WA.zIndex = 1;
WA.getNextZIndex = function()
{
  return WA.zIndex++;
}

WA.isDefined = function(val)
{
  return val !== undefined;
}

WA.isEmpty = function(val, blank)
{
  return val === undefined || val === null || ((WA.isArray(val) && !val.length)) || (!blank ? val === '' : false);
}

WA.isBool = function(val)
{
  return typeof val === 'boolean';
}

WA.isNumber = function(val)
{
  return typeof val === 'number' && isFinite(val);
}

WA.isString = function(val)
{
  return typeof val === 'string';
}

WA.isArray = function(val)
{
  return Object.prototype.toString.apply(val) === '[object Array]';
}

WA.isObject = function(val)
{
  return typeof val == 'object';
}

WA.isFunction = function(val)
{
  return Object.prototype.toString.apply(val) === '[object Function]';
}

WA.isDate = function(val)
{
  return Object.prototype.toString.apply(val) === '[object Date]';
}

WA.Extends = function(source, collector)
{
  for (var att in source)
    collector.prototype[att] = source[att];

  // original source to call parent
  collector.parent = source;
  return collector;
}

WA.clone = function(obj)
{
  var cloned = {};
  for (var i in obj)
  {
    if (typeof obj[i] == 'object')
      cloned[i] = WA.clone(obj[i]);
    else
      cloned[i] = obj[i];
  }
  return cloned;
}

// Will create a dom Node of specified type, and apply classname if defined
WA.createDomNode = function(type, id, classname)
{
  var domnode = document.createElement(type);
  if (id)
    domnode.id = id;
  if (classname !== null && classname != undefined)
    domnode.className = classname;
  return domnode;
}

WA.sizeof = function(obj, strict)
{
  var c = 0;
  for (var i in obj)
    if (!WA.isFunction(obj[i])) c++;
  return c;
}

WA.getDomNode = function(domID)
{
  if (arguments.length > 1)
  {
    var elements = [];
    for (var i = 0, l = arguments.length; i < l; i++)
      elements.push(getDomNode(arguments[i]));
    return elements;
  }
  if (typeof domID == 'string')
    return document.getElementById(domID);
  return null;
}

/* ALIAS to be compatible with Prototype @ www.conio.net */
var $ = WA.getDomNode;

WA.i18n = function() {}
WA.i18n.defaulti18n = {
  'json.error': 'The JSON code has been parsed with error, it cannot be built.\n',
  'json.unknown': 'The JSON core do not know what to do with this unknown type: '
};
WA.i18n.i18n = {};

WA.i18n.setEntry = function(id, message)
{
  WA.i18n.defaulti18n[id] = message;
}

WA.i18n.loadMessages = function(messages)
{
  for (var i in messages)
  {
    if (!WA.isString(messages[i]))
      continue;
    WA.i18n.i18n[i] = messages[i];
  }
}

WA.i18n.getMessage  = function(id)
{
  return WA.i18n.i18n[id] || WA.i18n.defaulti18n[id] || id;
}

// UTF-8 conversions, encoding
WA.UTF8 = function() {}

WA.UTF8.encode = function(value)
{
  if (typeof value == 'object')
  {
    var elements = {};
    for (var i in value)
      elements[i] = WA.UTF8.encode(value[i]);
    return elements;
  }
  value = value.replace(/\r\n/g,'\n');
  var utf = '';
  for (var i = 0, l = value.length; i < l; i++)
  {
    var c = value.charCodeAt(i);
    if (c < 128)
    {
      utf += String.fromCharCode(c);
    }
    else if((c > 127) && (c < 2048))
    {
      utf += String.fromCharCode((c >> 6) | 192);
      utf += String.fromCharCode((c & 63) | 128);
    }
    else
    {
      utf += String.fromCharCode((c >> 12) | 224);
      utf += String.fromCharCode(((c >> 6) & 63) | 128);
      utf += String.fromCharCode((c & 63) | 128);
    }
  }
  return utf;
}

// public method for utf8 decoding
WA.UTF8.decode = function(value)
{
  var str = '';
  var i = 0;
  var c1 = c2 = c3 = 0;
  while ( i < value.length )
  {
    c1 = value.charCodeAt(i);
    if (c1 < 128)
    {
      str += String.fromCharCode(c1);
      i++;
    }
    else if((c1 > 191) && (c1 < 224))
    {
      c2 = value.charCodeAt(i+1);
      str += String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    }
    else
    {
      c2 = value.charCodeAt(i+1);
      c3 = value.charCodeAt(i+2);
      str += String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    }
  }
  return str;
}

// DEBUG tool
// WA.debug is a singleton
// @message: string
// @level: integer
WA.debug = function() {}

WA.debug.Console = null;    // node value if debug available
WA.debug.Level = 4;         // 1 = system main & user, 2 = main & user, 3 = user only, 4 = nothing
WA.debug.filter = null;     // array of filtered words, null = all shown

WA.debug.explain = function(message, level)
{
  if (!level) // no level = user level
    level = 3;
  if ((!WA.debug.Console && !window.console) || level < WA.debug.Level)
    return;
  if (typeof WA.debug.filter == 'array')
  {
    var visible = false;
    for (var i in WA.debug.filter)
    {
      if (message.match(WA.debug.filter[i]))
      {
        visible = true;
        break;
      }
    }
    if (!visible)
      return;
  }
  // The console may be a popup or a division
  if (window.console && window.console.firebug)
    window.console.log(message);
  else if (WA.debug.Console.write)
    WA.debug.Console.write(message+'<br />');
  else
    WA.debug.Console.innerHTML += message+'<br />';
}

var debug = WA.debug.explain; // @strict

// json
WA.JSON = function() {}
WA.JSON.withalert = false;

WA.JSON.decode = function(json)
{
  var code = null;
  try
  {
    // 1. We parse the json code
    code = eval('(' + json + ')');
  }
  catch (e)
  {
    if (WA.JSON.withalert)
      alert(WA.i18n.getMessage('json.error') + e.message + '\n' + json);
    throw e;
  }
  if (code.debug)
  {
    debug(code.system);
    code = code.code;
  }
  return code;
}

WA.JSON.encode = function(data)
{
  var json = '';
  if (WA.isArray(data))
  {
    json += '[';
    var item = 0;
    for (var i = 0, l = data.length; i < l; i++)
    {
      json += (item++?',':'') + WA.JSON.encode(data[i]);
    }
    json += ']';
  }
  else if (data === null)
  {
    json += 'null';
  }
  else if (!WA.isDefined(data))
  {
    json += 'undefined';
  }
  else if (WA.isObject(data))
  {
    json += '{';
    var item=0;
    for (var i in data)
    {
      json += (item++?',':'')+'"'+i+'":'+WA.JSON.encode(data[i]);
    }
    json += '}';
  }
  else if (WA.isNumber(data))
  {
    json += data;
  }
  else if (WA.isString(data))
  {
    json += '"' + data.replace('\\', '\\\\', 'gm').replace('"', '\\"', 'gm') + '"';
  }
  else if (typeof data == 'boolean')
  {
    json += data?'true':'false';
  }
  else
  {
    if (WA.JSON.withalert)
      alert(WA.i18n.getMessage('json.unknown') + typeof data);
  }
  return json;
}

WA.browser = function()
{
  var agent = navigator.userAgent.toUpperCase();
  WA.browser.isCompat = (document.compatMode == 'CSS1Compat');
  WA.browser.isOpera = agent.indexOf('OPERA') > -1;
  WA.browser.isChrome = agent.indexOf('CHROME') > -1;
  WA.browser.isFirefox = agent.indexOf('FIREFOX') > -1;
  WA.browser.isSafari = !WA.browser.isChrome && agent.indexOf('SAFARI') > -1;
  WA.browser.isSafari2 = WA.browser.isSafari && agent.indexOf('APPLEWEBKIT/4') > -1;
  WA.browser.isSafari3 = WA.browser.isSafari && agent.indexOf('VERSION/3') > -1;
  WA.browser.isSafari4 = WA.browser.isSafari && agent.indexOf('VERSION/4') > -1;
  WA.browser.isMSIE = !WA.browser.isOpera && agent.indexOf('MSIE') > -1;
  WA.browser.isMSIE7 = WA.browser.isMSIE && agent.indexOf('MSIE 7') > -1;
  WA.browser.isMSIE8 = WA.browser.isMSIE && agent.indexOf('MSIE 8') > -1;
  WA.browser.isMSIE6 = WA.browser.isMSIE && !WA.browser.isMSIE7 && !WA.browser.isMSIE8;
  WA.browser.isWebKit = agent.indexOf('WEBKIT') > -1;
  WA.browser.isGecko = !WA.browser.isWebKit && agent.indexOf('GECKO') > -1;
  WA.browser.isGecko2 = WA.browser.isGecko && agent.indexOf('RV:1.8') > -1;
  WA.browser.isGecko3 = WA.browser.isGecko && agent.indexOf('RV:1.9') > -1;
  WA.browser.isLinux = agent.indexOf('LINUX') > -1;
  WA.browser.isWindows = !!agent.match(/WINDOWS|WIN32/);
  WA.browser.isMac = !!agent.match(/MACINTOSH|MAC OS X/);
  WA.browser.isAir = agent.indexOf('ADOBEAIR') > -1;
  WA.browser.isDom = document.getElementById && document.childNodes && document.createElement;
  WA.browser.isBoxModel = WA.browser.isIE && !WA.browser.isStandart;
  WA.browser.isSecure = (window.location.href.toUpperCase().indexOf('HTTPS') == 0);
  // DO WE NEED isFlash and isJava ?

  WA.browser.normalizedMouseButton = WA.browser.isMSIE ? {1:0, 2:2, 4:1} : (WA.browser.isSafari2 ? {1:0, 2:1, 3:2} : {0:0, 1:1, 2:2});

  // remove css image flicker
	if (WA.browser.isMSIE6)
    try { document.execCommand('BackgroundImageCache', false, true); } catch(e) {}
}

// ===================================
  // METRICS FUNCTIONS

  // get the size of the document. The document is the full usable html area
WA.browser.getDocumentWidth = function()
{
  if (WA.browser.isMSIE6)
    return document.body.scrollWidth;
  return document.documentElement.scrollWidth;
}

WA.browser.getDocumentHeight = function()
{
  if (WA.browser.isMSIE6)
    return document.body.scrollHeight;
  return document.documentElement.scrollHeight;
}

  // get the size of the window. The window is the browser visible area
WA.browser.getWindowWidth = function()
{
  if (!WA.browser.isMSIE)
    return window.innerWidth;

  if (document.documentElement && document.documentElement.clientWidth)
    return document.documentElement.clientWidth;

  if (document.body && document.body.clientWidth)
    return document.body.clientWidth;

  return 0;
}

WA.browser.getWindowHeight = function()
{
  if (!WA.browser.isMSIE)
    return window.innerHeight;

  if( document.documentElement && document.documentElement.clientHeight)
    return document.documentElement.clientHeight;

  if( document.body && document.body.clientHeight)
    return document.body.clientHeight;

  return 0;
}

  // get the size of the OS/screen
WA.browser.getScreenWidth = function()
{
  return screen.width;
}

WA.browser.getScreenHeight = function()
{
  return screen.height;
}

  // get the scroll of the window if the document is bigger than the window
WA.browser.getScrollLeft = function()
{
  if (WA.browser.isDom) // && (WA.browser.isMSIE7Sup || !WA.browser.isMSIE))
    return document.documentElement.scrollLeft;

  // ie6 and before
  if (document.body && document.body.scrollLeft)
    return document.body.scrollLeft;

  // others without dom
  if (typeof window.pageXOffset == 'number')
    return window.pageXOffset;

  return 0;
}

WA.browser.getScrollTop = function()
{
  // ie6 and before use BAD the documentelement on dom!
  if (WA.browser.isDom) // && (WA.browser.isMSIE7 || !WA.browser.isMSIE))
    return document.documentElement.scrollTop;

  // ie6 and before
  if (document.body && document.body.scrollTop)
    return document.body.scrollTop;

  // others without dom
  if (typeof window.pageYOffset == 'number')
    return window.pageYOffset;

  return 0;
}

// get the maximum scroll available
WA.browser.getScrollWidth = function()
{
  return WA.browser.getDocumentWidth();
}

WA.browser.getScrollHeight = function()
{
  return WA.browser.getDocumentHeight();
}

  // get the left of a DOM element into the document
WA.browser.getNodeDocumentLeft = function(node)
{
  var l = node.offsetLeft;
  if (node.offsetParent != null)
    l += WA.browser.getNodeDocumentLeft(node.offsetParent) + WA.browser.getNodeBorderLeftWidth(node.offsetParent) + WA.browser.getNodeMarginLeftWidth(node.offsetParent);
  return l;
}

  // get the top of a DOM element into the document
WA.browser.getNodeDocumentTop = function(node)
{
  var t = node.offsetTop;
  if (node.offsetParent != null)
    t += WA.browser.getNodeDocumentTop(node.offsetParent) + WA.browser.getNodeBorderTopHeight(node.offsetParent) + WA.browser.getNodeMarginTopHeight(node.offsetParent);
  return t;
}

  // get the left of a DOM element into the referenced node. If referenced node is NOT into the fathers, then it will give the left in the document
WA.browser.getNodeNodeLeft = function(node, refnode)
{
  if (!node)
    return null;
  var l = node.offsetLeft;
  if (node.offsetParent != null && node.offsetParent != refnode)
    l += WA.browser.getNodeBorderLeftWidth(node.offsetParent) + WA.browser.getNodeNodeLeft(node.offsetParent, refnode);
  return l;
}

  // get the top of a DOM element into the referenced node. If referenced node is NOT into the fathers, then it will give the top in the document
WA.browser.getNodeNodeTop = function(node, refnode)
{
  if (!node)
    return null;
  var t = node.offsetTop;
  if (node.offsetParent != null && node.offsetParent != refnode)
    t += WA.browser.getNodeBorderTopHeight(node.offsetParent) + WA.browser.getNodeNodeTop(node.offsetParent, refnode);
  return t;
}

  // get the scroll of the node if the content is bigger than the node
WA.browser.getNodeScrollLeft = function(node)
{
  if (WA.browser.isDom) // && (WA.browser.isMSIE7 || !WA.browser.isMSIE))
    return node.scrollLeft;

  // others without dom
  if (typeof node.pageXOffset == 'number')
    return node.pageXOffset;

  return 0;
}

WA.browser.getNodeScrollTop = function(node)
{
  if (WA.browser.isDom) // && (WA.browser.isMSIE7 || !WA.browser.isMSIE))
    return node.scrollTop;

  // others without dom
  if (typeof node.pageYOffset == 'number')
    return node.pageYOffset;

  return 0;
}

// get the maximum scroll available
WA.browser.getNodeScrollWidth = function(node)
{
  return WA.browser.getDocumentWidth();
}

WA.browser.getNodeScrollHeight = function(node)
{
  return WA.browser.getDocumentHeight();
}

/*
  About size and functions to get sizes:

     | margin | border | padding | content | padding | border | margin |
     |-------- extrawidth -------|- width -|
     |- externalwidth -|-------- innerwidth ---------|
              |----------------- offsetwidth -----------------|
     |--------------------------- outerwidth --------------------------|

  The external is the sum of left and right external
  The extra is the sum of left and right extra

  Same applies with height
*/

WA.browser.getNodeMarginLeftWidth = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.marginLeft, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('margin-left')) || 0;
}

WA.browser.getNodeMarginRightWidth = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.marginRight, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('margin-right')) || 0;
}

WA.browser.getNodeMarginWidth = function(node)
{
  return WA.browser.getNodeMarginLeftWidth(node) + WA.browser.getNodeMarginRightWidth(node);
}

WA.browser.getNodeMarginTopHeight = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.marginTop, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('margin-top')) || 0;
}

WA.browser.getNodeMarginBottomHeight = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.marginBottom, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('margin-bottom')) || 0;
}

WA.browser.getNodeMarginHeight = function(node)
{
  return WA.browser.getNodeMarginTopHeight(node) + WA.browser.getNodeMarginBottomHeight(node);
}

WA.browser.getNodeBorderLeftWidth = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.borderLeftWidth, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('border-left-width')) || 0;
}

WA.browser.getNodeBorderRightWidth = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.borderRightWidth, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('border-right-width')) || 0;
}

WA.browser.getNodeBorderWidth = function(node)
{
  return WA.browser.getNodeBorderLeftWidth(node) + WA.browser.getNodeBorderRightWidth(node);
}

WA.browser.getNodeBorderTopHeight = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.borderTopWidth, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('border-top-width')) || 0;
}

WA.browser.getNodeBorderBottomHeight = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.borderBottomWidth, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('border-bottom-width')) || 0;
}

WA.browser.getNodeBorderHeight = function(node)
{
  return WA.browser.getNodeBorderTopHeight(node) + WA.browser.getNodeBorderBottomHeight(node);
}

WA.browser.getNodePaddingLeftWidth = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.paddingLeft, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('padding-left')) || 0;
}

WA.browser.getNodePaddingRightWidth = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.paddingRight, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('padding-right')) || 0;
}

WA.browser.getNodePaddingWidth = function(node)
{
  return WA.browser.getNodePaddingLeftWidth(node) + WA.browser.getNodePaddingRightWidth(node);
}

WA.browser.getNodePaddingTopHeight = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.paddingTop, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('padding-top')) || 0;
}

WA.browser.getNodePaddingBottomHeight = function(node)
{
  return WA.browser.isMSIE?parseInt(node.currentStyle.paddingBottom, 10) || 0:parseInt(window.getComputedStyle(node, null).getPropertyValue('padding-bottom')) || 0;
}

WA.browser.getNodePaddingHeight = function(node)
{
  return WA.browser.getNodePaddingTopHeight(node) + WA.browser.getNodePaddingBottomHeight(node);
}

WA.browser.getNodeExternalLeftWidth = function(node)
{
  return WA.browser.getNodeMarginLeftWidth(node) + WA.browser.getNodeBorderLeftWidth(node);
}

WA.browser.getNodeExternalRightWidth = function(node)
{
  return WA.browser.getNodeMarginRightWidth(node) + WA.browser.getNodeBorderRightWidth(node);
}

WA.browser.getNodeExternalWidth = function(node)
{
  return WA.browser.getNodeExternalLeftWidth(node) + WA.browser.getNodeExternalRightWidth(node);
}

WA.browser.getNodeExternalTopHeight = function(node)
{
  return WA.browser.getNodeMarginTopHeight(node) + WA.browser.getNodeBorderTopHeight(node);
}

WA.browser.getNodeExternalBottomHeight = function(node)
{
  return WA.browser.getNodeMarginBottomHeight(node) + WA.browser.getNodeBorderBottomHeight(node);
}

WA.browser.getNodeExternalHeight = function(node)
{
  return WA.browser.getNodeExternalTopHeight(node) + WA.browser.getNodeExternalBottomHeight(node);
}

WA.browser.getNodeExtraLeftWidth = function(node)
{
  return WA.browser.getNodeMarginLeftWidth(node) + WA.browser.getNodeBorderLeftWidth(node) + WA.browser.getNodePaddingLeftWidth(node);
}

WA.browser.getNodeExtraRightWidth = function(node)
{
  return WA.browser.getNodeMarginRightWidth(node) + WA.browser.getNodeBorderRightWidth(node) + WA.browser.getNodePaddingRightWidth(node);
}

WA.browser.getNodeExtraWidth = function(node)
{
  return WA.browser.getNodeExtraLeftWidth(node) + WA.browser.getNodeExtraRightWidth(node);
}

WA.browser.getNodeExtraTopHeight = function(node)
{
  return WA.browser.getNodeMarginTopHeight(node) + WA.browser.getNodeBorderTopHeight(node) + WA.browser.getNodePaddingTopHeight(node);
}

WA.browser.getNodeExtraBottomHeight = function(node)
{
  return WA.browser.getNodeMarginBottomHeight(node) + WA.browser.getNodeBorderBottomHeight(node) + WA.browser.getNodePaddingBottomHeight(node);
}

WA.browser.getNodeExtraHeight = function(node)
{
  return WA.browser.getNodeExtraTopHeight(node) + WA.browser.getNodeExtraBottomHeight(node);
}

  // get the real size of a DOM element
WA.browser.getNodeWidth = function(node)
{
  return WA.browser.getNodeOffsetWidth(node) - WA.browser.getNodePaddingWidth(node) - WA.browser.getNodeBorderWidth(node);
}

WA.browser.getNodeHeight = function(node)
{
  return WA.browser.getNodeOffsetHeight(node) - WA.browser.getNodePaddingHeight(node) - WA.browser.getNodeBorderHeight(node);
}

WA.browser.getNodeInnerWidth = function(node)
{
  return WA.browser.getNodeOffsetWidth(node) - WA.browser.getNodeBorderWidth(node);
}

WA.browser.getNodeInnerHeight = function(node)
{
  return WA.browser.getNodeOffsetHeight(node) - WA.browser.getNodeBorderHeight(node);
}

WA.browser.getNodeOffsetWidth = function(node)
{
  return parseInt(node.offsetWidth, 10) || 0;
}

WA.browser.getNodeOffsetHeight = function(node)
{
  return parseInt(node.offsetHeight, 10) || 0;
}

WA.browser.getNodeOuterWidth = function(node)
{
  return WA.browser.getNodeOffsetWidth(node) + WA.browser.getNodeMarginWidth(node);
}

WA.browser.getNodeOuterHeight = function(node)
{
  return WA.browser.getNodeOffsetHeight(node) + WA.browser.getNodeMarginHeight(node);
}

// ===================================
// MOUSE FUNCTIONS

/*
  The mouse is not standard on all navigators.
  ie and safari does not map same clicks keys (left, center, right), we need corresponding table

  NOTE Than both mouse and keyboard events are mixed in the same event
*/

  // getCursorNode will return the DOM node in which the event happened
WA.browser.getCursorNode = function(e)
{
  var ev = e || window.event;
  if (ev.target) return ev.target;
  if (ev.srcElement) return ev.srcElement;
  return null;
}

  // returns the absolute position of the event in the document
WA.browser.getCursorDocumentX = function(e)
{
  var ev = e || window.event;
  return ev.clientX + WA.browser.getScrollLeft() - (document.documentElement.clientLeft || 0);  // MSIE 7 has a weird 2 pixels offset for mouse coords !
}

  // returns the absolute position of the event in the document
WA.browser.getCursorDocumentY = function(e)
{
  var ev = e || window.event;
  return ev.clientY + WA.browser.getScrollTop() - (document.documentElement.clientLeft || 0);  // MSIE 7 has a weird 2 pixels offset for mouse coords !
}

  // returns the absolute position of the event in the browserwindow
WA.browser.getCursorWindowX = function(e)
{
  var ev = e || window.event;
  return ev.clientX - (document.documentElement.clientLeft || 0);  // MSIE 7 has a weird 2 pixels offset for mouse coords !;
}

  // returns the absolute position of the event in the browserwindow
WA.browser.getCursorWindowY = function(e)
{
  var ev = e || window.event;
  return ev.clientY - (document.documentElement.clientLeft || 0);  // MSIE 7 has a weird 2 pixels offset for mouse coords !;
}

  // returns the absolute position of the event in the container based on the OFFSET metrix (i.e. with border included)
  // IF the function does not work on FIREFOX: DO NOT MODIFY the code,
  //     but add a position: relative to the container !
  // (note: FF and Safari, gets natural origin with border, IE and opera, without border :S)
WA.browser.getCursorOffsetX = function(e)
{
  var offset = 0;
  if (WA.browser.isMSIE || WA.browser.isOpera)
    offset = WA.browser.getNodeBorderLeftWidth(WA.browser.getCursorNode(e));

  var ev = e || window.event;
  if(typeof(ev.layerX) == 'number')
    return ev.layerX + offset;
  if(typeof(ev.offsetX) == 'number')
    return ev.offsetX + offset;
  return 0;
}

// returns the absolute position of the event in the container based on the OFFSET metrix (i.e. with border included)
// IF the function does not work on FIREFOX: DO NOT MODIFY the code,
//     but add a position: relative to the container !
WA.browser.getCursorOffsetY = function(e)
{
  var offset = 0;
  if (WA.browser.isMSIE || WA.browser.isOpera)
    offset = WA.browser.getNodeBorderTopHeight(WA.browser.getCursorNode(e));

  var ev = e || window.event;
  if(typeof(ev.layerY) == 'number')
    return ev.layerY + offset;
  if(typeof(ev.offsetY) == 'number')
    return ev.offsetY + offset;
  return 0;
}

// returns the absolute position of the event in the container based on the INNER metrix (i.e. without border included)
// IF the function does not work on FIREFOX: DO NOT MODIFY the code,
//     but add a position: relative to the container !
WA.browser.getCursorInnerX = function(e)
{
  var offset = 0;
  if (!WA.browser.isMSIE && !WA.browser.isOpera)
    offset = WA.browser.getNodeBorderLeftWidth(WA.browser.getCursorNode(e));

  var ev = e || window.event;
  if(typeof(ev.layerX) == 'number')
    return ev.layerX - offset;
  if(typeof(ev.offsetX) == 'number')
    return ev.offsetX - offset;
  return 0;
}

// returns the absolute position of the event in the container based on the INNER metrix (i.e. without border included)
// IF the function does not work on FIREFOX: DO NOT MODIFY the code,
//     but add a position: relative to the container !
WA.browser.getCursorInnerY = function(e)
{
  var offset = 0;
  if (!WA.browser.isMSIE && !WA.browser.isOpera)
    offset = WA.browser.getNodeBorderTopHeight(WA.browser.getCursorNode(e));

  var ev = e || window.event;
  if(typeof(ev.layerY) == 'number')
    return ev.layerY - offset;
  if(typeof(ev.offsetY) == 'number')
    return ev.offsetY - offset;
  return 0;
}

// click functions
WA.browser.getButtonClick = function(e)
{
  var ev = e || window.event;
  if (ev.type != 'click' && ev.type != 'dblclick')
    return false;
  var button = ev.button ? WA.browser.normalizedMouseButton[ev.button] : (ev.which ? ev.which-1 : 0);
  return button;
}

// click functions
WA.browser.getButtonPressed = function(e)
{
  var ev = e || window.event;
  if (ev.type != 'mousedown' && ev.type != 'mouseup')
    return false;
  var button = ev.button ? WA.browser.normalizedMouseButton[ev.button] : (ev.which ? ev.which-1 : false);
  return button;
}

WA.browser.getWheel = function(e)
{
  var ev = e || window.event;
  if (ev.type != 'DOMMouseScroll' && ev.type != 'mousewheel')
    return false;
  var delta = 0;
  if(ev.wheelDelta)
  {
    delta = ev.wheelDelta / 120;
  }
  else if (ev.detail)
  {
    delta = -ev.detail / 3;
  }
  return delta;
}

WA.browser.cancelEvent = function(e)
{
  var ev = e || window.event;
  if (ev.stopPropagation)
    ev.stopPropagation();
  if (ev.preventDefault)
    ev.preventDefault();
  if (ev.stopEvent)
    ev.stopEvent();
  if (WA.browser.isMSIE) window.event.keyCode = 0;
  ev.cancel = true;
  ev.cancelBubble = true;
  ev.returnValue = false;
  return false;
}

// ===================================
// KEYBOARD FUNCTIONS

/*
  The keyboard is not standard on all navigators.
  known properties: shift, control, alt, keycode, charcode, navigation key
  navigation keys are: arrows, page up/down, insert, home, end, enter, tab escape

  NOTE Than both mouse and keyboard events are mixed in the same event
*/

// key functions
WA.browser.getKey = function(e)
{
  var ev = e || window.event;
  if (ev.type != 'keydown' && ev.type != 'keyup')
    return false;
  return ev.keyCode || ev.which;
}

WA.browser.getChar = function(e)
{
  var ev = e || window.event;
  if (ev.type != 'keypress')
    return false;
  return String.fromCharCode(ev.charCode ? ev.charCode : ev.keyCode);
}

WA.browser.ifShift = function(e)
{
  var ev = e || window.event;
  return ev.shiftKey;
}

WA.browser.ifCtrl = function(e)
{
  var ev = e || window.event;
  return ev.ctrlKey || ev.metaKey;
}

WA.browser.ifAlt = function(e)
{
  var ev = e || window.event;
  return ev.altKey;
}

  // any shift, control, alt
WA.browser.ifModifier = function(e)
{
  var ev = e || window.event;
  return (ev.altKey || ev.ctrlKey || ev.metaKey || ev.shiftKey) ? true : false;
}

  // any navigation keys: arrows, page up/down, home/end, escape, enter, tab
WA.browser.ifNavigation = function(e)
{
  var c = WA.browser.getKey(e);
  return ((c >= 33 && c <= 40) || c == 9 || c == 13 || c == 27) ? true : false;
}

  // f1 to f12
WA.browser.ifFunction = function(e)
{
  var c = WA.browser.getKey(e);
  return (c >= 112 && c <= 123) ? true : false;
}

// ===================================
// SELECTION FUNCTIONS

// select something in the document
WA.browser.getSelectionRange = function(node, selectionStart, selectionEnd)
{
  if (node.setSelectionRange)
  {
    node.focus();
    node.setSelectionRange(selectionStart, selectionEnd);
  }
  else if (node.createTextRange)
  {
    var range = node.createTextRange();
    range.collapse(true);
    range.moveEnd('character', selectionEnd);
    range.moveStart('character', selectionStart);
    range.select();
  }
}

// ===================================
// FILL FUNCTIONS

// fill an innerHTML
WA.browser.setInnerHTML = function(node, content)
{
  if (WA.browser.isGecko)
  {
    var rng = document.createRange();
    rng.setStartBefore(node);
    var htmlFrag = rng.createContextualFragment(content);
    while (node.hasChildNodes())
      node.removeChild(node.lastChild);
    node.appendChild(htmlFrag);
  }
  else
  {
    node.innerHTML = content;
  }
}

var browser = WA.browser; // @strict

WA.render = function()
{}

WA.render.Integer = function(data, sep)
{
  if (!sep)
    return data;
  // we change the cast
  data = '' + data;
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(data))
	{
		data = data.replace(rgx, '$1' + sep + '$2');
	}
	return data;
}

WA.render.Fixed = function(data, fix, dec, sep)
{
  if (!WA.isNumber(fix)) fix=2;
  if (!dec) dex='.';
  if (!sep) sep = ',';
  data = data.toFixed(fix);
	data += '';
	x = data.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? dec + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1))
	{
		x1 = x1.replace(rgx, '$1' + sep + '$2');
	}
	return x1 + x2;
}

WA.render.Money = function(data, symbol, fix, dec, sep)
{
  return symbol + WA.render.Fixed(data, fix, dec, sep);
}

WA.Managers = {};

// color transfer, function to instanciate
function RGB(color)
{
  var self = this; // for inner functions
  this.ok = false;

  if (color.charAt(0) == '#')
    color = color.substr(1,6);
  color = color.replace(/ /,'').toLowerCase();

  var htmlcolors =
  {
    black: '000000',
    silver: 'c0c0c0',
    gray: '808080',
    white: 'ffffff',
    maroon: '800000',
    red: 'ff0000',
    purple: '800080',
    fuchsia: 'ff00ff',
    green: '008000',
    lime: '00ff00',
    olive: '808000',
    yellow: 'ffff00',
    navy: '000080',
    blue: '0000ff',
    teal: '008080',
    aqua: '00ffff'
  };

  for (var name in htmlcolors)
  {
    if (color == name)
    {
      this.name = color;
      color = htmlcolors[name];
    }
  }

  var rgb = /^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/.exec(color);
  if (rgb)
  {
    this.red = parseInt(rgb[1], 10);
    this.green = parseInt(rgb[2], 10);
    this.blue = parseInt(rgb[3], 10);
    this.ok = true;
  }
  else
  {
    rgb = /^(\w{2})(\w{2})(\w{2})$/.exec(color);
    if (rgb)
    {
      this.red = parseInt(rgb[1], 16);
      this.green = parseInt(rgb[2], 16);
      this.blue = parseInt(rgb[3], 16);
      this.ok = true;
    }
    else
    {
      rgb = /^(\w{1})(\w{1})(\w{1})$/.exec(color);
      if (rgb)
      {
        this.red = parseInt(rgb[1]+rgb[1], 16);
        this.green = parseInt(rgb[2]+rgb[2], 16);
        this.blue = parseInt(rgb[3]+rgb[3], 16);
        this.ok = true;
      }
    }
  }
  this.red = (this.red < 0 || isNaN(this.red)) ? 0 : ((this.red > 255) ? 255 : this.red);
  this.green = (this.green < 0 || isNaN(this.green)) ? 0 : ((this.green > 255) ? 255 : this.green);
  this.blue = (this.blue < 0 || isNaN(this.blue)) ? 0 : ((this.blue > 255) ? 255 : this.blue);

  this.toRGB = toRGB;
  function toRGB()
  {
    return 'rgb(' + self.red + ', ' + self.green + ', ' + self.blue + ')';
  }

  this.toHex = toHex;
  function toHex()
  {
    var red = self.red.toString(16);
    var green = self.green.toString(16);
    var blue = self.blue.toString(16);
    if (red.length == 1) red = '0' + red;
    if (green.length == 1) green = '0' + green;
    if (blue.length == 1) blue = '0' + blue;
    return '#' + red + green + blue;
  }
}

WA.start = function()
{
  WA.browser();
  WA.running = true;
}

WA.start();

// empty function for listeners assignement (IE bug mainly that does not accept null)
nothing = function() {};
