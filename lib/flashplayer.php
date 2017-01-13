<?php
  if($player['id'] == 'flowplayer')
  {
  	//<!-- <img src="/img/amiti.jpeg"style="width:450px; height:290px" /> -->
    $playerfl = <<<POV
    <script type="text/javascript" src="http://cybercast.com.mx/streaming/flowplayer-3.2.2.min.js"></script>
<div class="player" id="fms1" href="ondemand/video.mp4" style="display:block;width:{$flashparams['width']}px;height:{$flashparams['height']}px; margin-left:10px; float:left;">


</div>
		<script language="JavaScript">
		\$f("fms1", "http://cybercast.com.mx/streaming/flowplayer-3.2.2.swf", {

		  clip: {
		    url: '$file',
		    bufferLength: 20,
		    provider: 'rtmp',
		    autoPlay: true
		  },

		  plugins: {

		    gatracker: {
		      url: "http://cybercast.com.mx/streaming/flowplayer.analytics-3.1.5.swf",
		      labels: {
		        start: "Start",
		        play: "Play",
		        pause: "Pause",
		        resume: "Resume",
		        seek: "Seek",
		        stop: "Stop",
		        finish: "Finish",
		        mute: "Mute",
		        unmute: "Unmute",
		        fullscreen: "Full Screen",
		        fullscreenexit: "Full Screen Exit"
		      },
		      debug: false,
		      trackingMode: "AS3",
		      googleId: "UA-17153173-1"
		    },

		      controls:{
		      url: 'http://cybercast.com.mx/streaming/flowplayer.controls-3.2.1.swf',
			backgroundColor: "transparent",
			backgroundGradient: "none",
			sliderColor: '#FFA500',
			sliderBorder: '1.5px solid rgba(160,160,160,0.7)',
			volumeSliderColor: '#FFFFFF',
			volumeBorder: '1.5px solid rgba(160,160,160,0.7)',

			timeColor: '#ffffff',
			durationColor: '#FFA500',

			tooltipColor: 'rgba(255, 255, 255, 0.7)',
			tooltipTextColor: '#000000'

		    },

		    viral: {
		      url: 'http://cybercast.com.mx/streaming/flowplayer.viralvideos-3.2.1.swf',

		      share: {
		        description: '{$flashparams['descripcion']}',
		        title: 'haz click sobre el icono para compartir este video'
		      },

		      embed: {
		        title: 'Copia y pega el siguente codigo en tu pagina'
		      },

		      email: {
		        texts: {
		          title: 'Comparte este video por email',
		          to: 'Introduce un email',
		          toSmall: 'multiples correos separados con comas',
		          message: 'Mensaje personal',
		          optional: '(opcional)',
		          from: 'Tu nombre',
		          fromAddress: 'Tu correo electronico',
		          Send: 'Enviar'
		        }
		      }

		    },


		    rtmp: {
		      url: 'http://cybercast.com.mx/streaming/flowplayer.rtmp-3.2.1.swf',
		      netConnectionUrl: '$url'
		    }
		  }


		});
</script>
POV;
  }
  else if ($player['id'] == 'swfobject')
  {
    $playerfl = <<<POV
    <script type='text/javascript' src='http://cybercast.com.mx/streaming/mediaplayer-viral/swfobject.js'></script>

    <div id='mediaspace' style="float:left; width:{$flashparams['width']}px;height:{$flashparams['height']}px;"></div>
    
    <script type="text/javascript">
      var so = new SWFObject('http://cybercast.com.mx/streaming/mediaplayer-viral/player.swf','mpl','{$flashparams['width']}','{$flashparams['height']}','9');
      so.addParam('allowscriptaccess','always');
      so.addParam('allowfullscreen','true');
      so.addVariable('file', '$file');
      so.addVariable('provider', 'rtmp');
      so.addVariable('streamer', '$url');
      so.addVariable('autostart','true');
      so.addVariable('bufferlengh', '3');
      so.write('mediaspace');
    </script>
POV;
  }
  else
    $playerfl = '';
?>
