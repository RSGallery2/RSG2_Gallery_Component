/*
 SlideShow. Written by PerlScriptsJavaScripts.com
 Copyright http://www.perlscriptsjavascripts.com
 Code page http://www.perlscriptsjavascripts.com/js/slideshow.html
 Free and commercial Perl and JavaScripts
 */

 var slideshowOneX = {

	initialize: function (options) {
		this.effect = 23;// transition effect. number between 0 and 23, 23 is random effect
		this.duration = 1.5;// transition duration. number of seconds effect lasts
		this.display = 4;// seconds to display each image?
		this.oW = 400;// width of stage (first image)
		this.oH = 400;// height of stage
		this.zW = 40;// zoom width by (add or subtracts this many pixels from image width)
		this.zH = 30;// zoom height by
		
		alert ("surprise");
		/**
		if (this.modules)
			return this;
		this.setOptions(options);
		this.modules = $H({});
		this.count = history.length;
		this.states = [];
		this.states[this.count] = this.getHash();
		this.state = null;
		/**/
		return this;
	}
}
	
// start slideshow right once dom is ready
jQuery(document).ready(function($){
	alert ("initialize");
	slideshowOneX.initialize ();
});

