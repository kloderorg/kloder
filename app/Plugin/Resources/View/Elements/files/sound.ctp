<?php echo $this->Html->css(array('sound/360player', 'sound/360player-visualization'), null, array('inline' => false)); ?>
<?php echo $this->Html->script(array('sound/berniecode-animator', 'sound/360player'), array('inline' => false)); ?>
<script type="text/javascript">
soundManager.onready(function() {
	soundManager.useFastPolling = true; // increased JS callback frequency, combined with useHighPerformance = true
	threeSixtyPlayer.config.scaleFont = (navigator.userAgent.match(/msie/i)?false:true);
	threeSixtyPlayer.config.showHMSTime = true;
	threeSixtyPlayer.config.useWaveformData = true;
	threeSixtyPlayer.config.useEQData = true;
	if (threeSixtyPlayer.config.useWaveformData) soundManager.flash9Options.useWaveformData = true;
	if (threeSixtyPlayer.config.useEQData) soundManager.flash9Options.useEQData = true;
	if (threeSixtyPlayer.config.usePeakData) soundManager.flash9Options.usePeakData = true;
	if (threeSixtyPlayer.config.useWaveformData || threeSixtyPlayer.flash9Options.useEQData || threeSixtyPlayer.flash9Options.usePeakData) {
	  soundManager.preferFlash = true;
	}
	if (window.location.href.match(/hifi/i)) threeSixtyPlayer.config.useFavIcon = true;
	if (window.location.href.match(/html5/i)) soundManager.useHTML5Audio = true;
});
</script>

<div id="config-ui" style="clear:both;position:relative;max-width:1110px;margin-top:1em;display:none">
	<div style="position:relative">
		<form action="#" method="get">
			<div id="controls">
				<div class="checkbox">
					<div>
						<input id="use-waveform" type="checkbox" checked="checked" title="Enable waveform feature." onclick="controller.updateExample();controller.updateExampleCode()" value="Apply" /> Waveform
						<input id="disabled-1" type="checkbox" title="Enable EQ (spectrum) feature." onclick="controller.updateExample();controller.updateExampleCode()" value="Apply" style="margin-left:1em" checked="checked" /> EQ
						<input type="checkbox" name="use-amplifier" id="use-amplifier" checked="checked" onclick="controller.updateExample();controller.updateExampleCode()" style="margin-left:1em"> Amplifier
					</div>
				</div>
				<div style="clear:left"></div>
				<dl class="col">
					<dt>Circle Diameter</dt>
					<dd class="title">Size</dd>
					<dd>1-256</dd>
					<dd class="control">
						<div class="bar" title="Click to move here"></div>
						<div class="slider" title="Size of circle"></div>
					</dd>
					
					<dt>Waveform Thickness</dt>
					<dd class="title">thickness</dd>
					<dd>1-100</dd>
					<dd class="control">
						<div class="bar" title="Click to move here"></div>
						<div class="slider" title="Thickness of line"></div>
					</dd>
					
					<dt>Wave Downsample</dt>
					<dd class="title">(Scale)</dd>
					<dd>1-16</dd>
					<dd class="control">
						<div class="bar" title="Click to move here"></div>
						<div class="slider" title="Amount to downsample waveform data by"></div>
					</dd>
					
					<dt>EQ Thickness</dt>
					<dd class="title">thickness</dd>
					<dd>1-50</dd>
					<dd class="control">
						<div class="bar" title="Click to move here"></div>
						<div class="slider" title="Thickness of line"></div>
					</dd>
					
					<dt>EQ Downsample</dt>
					<dd class="title">(Scale)</dd>
					<dd>1-16</dd>
					<dd class="control">
						<div class="bar" title="Click to move here"></div>
						<div class="slider" title="Amount to downsample EQ data by"></div>
					</dd>
				</dl>
				<div id="options" class="col">
					<div>
						Waveform position:
						<input type="radio" name="waveform-inside" id="waveform-inside" value="true" checked="checked" onclick="controller.updateExample();controller.updateExampleCode()"> Inside | <input type="radio" name="waveform-inside" id="waveform-inside" value="false" onclick="controller.updateExample();controller.updateExampleCode()"> Outside
					</div>
					<div>
						EQ position:
						<input type="radio" name="eq-inside" id="eq-inside" value="true" onclick="controller.updateExample();controller.updateExampleCode()"> Inside | <input type="radio" name="eq-inside" id="eq-inside" value="false" checked="checked" onclick="controller.updateExample();controller.updateExampleCode()"> Outside
					</div>
					<div>
						Waveform color:
						<input type="text" name="waveform-color" id="waveform-color" value="#000000" onclick="createCP(this,setWaveformColor)" />
					</div>
					<div>
						EQ color:
						<input type="text" name="eq-color" id="eq-color" value="#000000" onclick="createCP(this,setEQColor)" />
					</div>
					<div>
						Loaded ring color:
						<input type="text" name="loaded-ring-color" id="loaded-ring-color" value="#000000" onclick="createCP(this,setLoadedRingColor)" />
					</div>
					<div>
						Progress ring color:
						<input type="text" name="progress-ring-color" id="progress-ring-color" value="#000000" onclick="createCP(this,setProgressRingColor)" />
					</div>
					<div>
						Background ring color:
						<input type="text" name="bg-ring-color" id="bg-ring-color" value="#000000" onclick="createCP(this,setBackgroundRingColor)" />
					</div>
					<p class="compact">
						<input type="button" onclick="controller.randomize()" value="Randomize controls" title="Assign random control values" style="font-size:x-small" />
					</p>
				</form>
			</div>
			<div id="cp-container">
				<!-- color picker stuff goes here -->
			</div>
			<div id="config-code-block" style="float:right;display:inline;margin-left:1em;margin-top:-0.7em">
				<!--
				<pre id="config-link" class="block"><code style="cursor:pointer" onclick="document.getElementById('config-link').style.display='none';document.getElementById('config-pre-block').style.display='block';return false"> [click to show code]                 </code></pre>
				-->
				<pre id="config-pre-block" class="block"><code id="config-code">Code goes here</code></pre>
			</div>
		 </div>
	</div>
	<p style="clear:left">Get a sound playing, then adjust the values to see real-time updates.</p>
</div>




<!--<div class="ui360">
 <a href="http://www.fugitives.ca/wp-content/plugins/mp3-player-plugin-for-wordpress/streetlight/07%20Graffiti%20Sex.mp3">play "an.mp3"</a>
</div>-->
<?php echo $this->Html->url('/resources/resources_files/get/'.$file['ResourcesFile']['id']) ?>
<div class="ui360 ui360-vis">
	<a href="<?php echo $this->Html->url('/resources/resources_files/get/'.$file['ResourcesFile']['id']) ?>"><?php echo $file['ResourcesFile']['name'] ?></a>
	<div class="metadata">
		<div class="duration">4:43</div>
		<ul>
			<li><p>Electric razor</p><span>0:00</span></li>
			<li><p>Water, scissors</p><span>2:41</span></li>
			<li><p>More razor work</p><span>4:00</span></li>
		</ul>
	</div>
</div>
