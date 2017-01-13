
/* The JS twitter API wrapper
  Ing. Philippe Thomassigny
  Alernativa Digital SA de CV
*/

// Cambiar la cantidad maxima de mensajes desplegados
var maxmessages = 100;
// cambiar la frecuencia para ir a buscar al servidor la informacion
// en milisegundos. 60000 = 60 segundos. No se recomienda de bajar de 1 minuto.
var hitserver = 60000;
// cambiar la frecuencia de rotacion de mensajes, en milisegundos
// null se auto adapta a la cantidad recibida
var rotacion = 4000;


var toshow = [];
var lastid = 0;

function searchback(r)
{
  var data = WA.JSON.decode(r.responseText);

  lastid = data.max_id;
  for (var i in data.results)
  {
    if (!data.results[i].text)
      continue;
    toshow.push(data.results[i]);
  }
}

function search()
{
  var r = ajax('twitter.php', 'POST', null, searchback, true);
}

function transformText(text)
{
  text = text.replace(/(http:\/\/[^\s^\)^\]]*)/gi, '<a class="externallink" href="$1" target="_blank">$1</a>');
  text = text.replace(/@([a-z0-9_]*)/gi, '@<a class="userlink" href="http://twitter.com/$1" target="_blank">$1</a>');
  text = text.replace(/#([a-z0-9_]*)/gi, '<a class="hashlink" href="http://twitter.com/search?q=%23$1" target="_blank">#$1</a>');
  return text;
}

function calcdate(d)
{
  var d = new Date(d);
  return d.format('d/m/Y H:i');
}

var currentnode = null;
var currentanim = null;

function showbox()
{
  // put the box at the beginning and remove the anim
  document.getElementById('fillin').removeChild(currentanim)
  document.getElementById('fillin').insertBefore(currentnode, document.getElementById('fillin').firstChild);

  // show the box
  currentnode.style.opacity = '0';
  currentnode.style.filter = 'alpha(opacity=0)';
  currentnode.style.visibility = 'visible';
  anim(currentnode.id, currentnode, null, {autostart:true, loop:false, chain:[{type:'move',tinit:0,tend:100,time:500}]});

  currentnode = null;
  currentanim = null;
}

function show()
{
  var ok = true;
  while (ok)
  {
    if (toshow.length > 0)
    {
      if (!document.getElementById(''+toshow[toshow.length-1].id))
      {
        // too much ? remoce
        var count = 0;
        for (var i in document.getElementById('fillin').childNodes)
          count++;

        if (count > maxmessages)
          // los mas viejos los quitas
          document.getElementById('fillin').removeChild(document.getElementById('fillin').lastChild)

        // create new nodos
        ok = false;
        
        var dt = calcdate(toshow[toshow.length-1].created_at);
        var dtsp = dt.split(' ');
        
        
        
        var n = new WA.createDomNode('div', ''+toshow[toshow.length-1].id, 'tbox');
        n.innerHTML = '<div><div style="float:left;"><img class="timage" src="' + toshow[toshow.length-1].profile_image_url + 
                        '" align="left"/><br/> <span class="trate">' + dtsp[0] + '</span><br/><span class="trate">' + dtsp[1]  + '</span> </div>' + 
                        '<div><a class="ownerlink" href="http://twitter.com/'+
                          toshow[toshow.length-1].from_user+'" target="_blank">' +
                          //toshow[toshow.length-1].from_user + '</a>: <span class="trate">' + calcdate(toshow[toshow.length-1].created_at) + '</span><br />' +
                          toshow[toshow.length-1].from_user + '</a><br />' +
        transformText(toshow[toshow.length-1].text) + '</div> </div><div style="clear:both; height:5px;"></div>';
        n.style.visibility = 'hidden';

        var nx = new WA.createDomNode('div', 'animx', null);
        nx.style.height = '0px';

        if (document.getElementById('fillin').firstChild)
          document.getElementById('fillin').insertBefore(nx, document.getElementById('fillin').firstChild);
        else
          document.getElementById('fillin').appendChild(nx);
        document.getElementById('fillin').appendChild(n);

        var height = browser.getNodeOuterHeight(n);
        // animate
        currentnode = n;
        currentanim = nx;

        anim('box', nx, showbox, {autostart:true, loop:false, chain:[{tupe:'move',hinit:0,hend:height,time:500}]});
      }
      toshow.splice(toshow.length-1,1);
    }
    else
      ok = false;
  }
  var t = setTimeout(show, rotacion);
}

function psearch()
{
  search();

  var t = setTimeout(psearch, hitserver);
}

psearch();
show();