/************************************************************************************
 * @requires jQuery v1.7 or later
************************************************************************************/
//! ++++++++++++++++++++++++++++++++++++++

(function(jQuery,undefined){

	  //! ANIMATION MATRIX
	  // PREPARE THE HOVER ANIMATIONS
	  
	  var miGalleryAnimmatrix,
		  miGalleryItemAnimations,
		  supressFocus,
		  startAnimations,
		  mergedTransitions,
		  vhandlers = {};
	  
	  function miGalleryReady() {
		  
			miGalleryAnimmatrix = {
			
					'adamlabsgallery-none':               [0, {autoAlpha:1,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0},{autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, 0, {autoAlpha:1,overwrite:"all"} ],

					'adamlabsgallery-fade':               [0.3, {autoAlpha:0,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0},{autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, 0.3, {autoAlpha:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
					
					'adamlabsgallery-fadeblur':               [0.3, {autoAlpha:1,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0},{autoAlpha:0.5,ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, 0.3, {autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
					
					'adamlabsgallery-fadeout':            [0.3, {autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, {autoAlpha:0,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0}, 0.3, {autoAlpha:1,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],

					'adamlabsgallery-covergrowup':        [0.3, {autoAlpha:1,top:"100%",marginTop:-10,rotationZ:0,x:0,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0},{autoAlpha:1,top:"0%", marginTop:0, ease:adamlabsgallerygs.Power2.easeOut, overwrite:"all"}, 0.3, {autoAlpha:1,top:"100%",marginTop:-10,bottom:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,true],

					'adamlabsgallery-flipvertical':       [0.5, {x:0,y:0,scale:1,rotationZ:0,rotationY:0,skewX:0,skewY:0,rotationX:180,autoAlpha:0,z:-0.001,transformOrigin:"50% 50%"}, {rotationX:0,autoAlpha:1,scale:1,z:0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} , 0.5,{rotationX:180,autoAlpha:0,scale:1,z:-0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,true],
							
					'adamlabsgallery-flipverticalout':	[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationY:0,skewX:0,skewY:0,rotationX:0,autoAlpha:1,z:0.001,transformOrigin:"50% 50%"},{rotationX:-180,scale:1,autoAlpha:0,z:-150,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,0.5,{rotationX:0,autoAlpha:1,scale:1,z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ],

					'adamlabsgallery-fliphorizontal':		[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,skewX:0,skewY:0,rotationY:180,autoAlpha:0,z:-0.001,transformOrigin:"50% 50%"}, {rotationY:0,autoAlpha:1,scale:1,z:0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} , 0.5, {rotationY:180,autoAlpha:0,scale:1,z:-0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,true],
					
					'adamlabsgallery-fliphorizontalout':	[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,z:0.001,transformOrigin:"50% 50%"}, {rotationY:-180,scale:1,autoAlpha:0,z:-150,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} , 0.5, {rotationY:0,autoAlpha:1,scale:1,z:0.001,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ],

					'adamlabsgallery-flipup':				[0.5, {x:0,y:0,scale:0.8,rotationZ:0,rotationX:90,rotationY:0,skewX:0,skewY:0,autoAlpha:0,z:0.001,transformOrigin:"50% 100%"}, {scale:1,rotationX:0,autoAlpha:1,z:0.001,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} , 0.3, {scale:0.8,rotationX:90,autoAlpha:0,z:0.001,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,true],
							
					'adamlabsgallery-flipupout':			[0.5, {rotationX:0,autoAlpha:1,y:0,ease:adamlabsgallerygs.Bounce.easeOut,overwrite:"all"} ,{x:0,y:0,scale:1,rotationZ:0,rotationX:-90,rotationY:0,skewX:0,skewY:0,autoAlpha:1,z:0.001,transformOrigin:"50% 0%"} , 0.3, {rotationX:0,autoAlpha:1,y:0,ease:adamlabsgallerygs.Bounce.easeOut,overwrite:"all"} ],
					
					'adamlabsgallery-flipdown':			[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:-90,rotationY:0,skewX:0,skewY:0,autoAlpha:0,z:0.001,transformOrigin:"50% 0%"},{rotationX:0,autoAlpha:1,y:0,ease:adamlabsgallerygs.Bounce.easeOut,overwrite:"all"} ,0.3, {rotationX:-90,z:0,ease:adamlabsgallerygs.Power2.easeOut,autoAlpha:0,overwrite:"all"},true ],
					
					'adamlabsgallery-flipdownout':		[0.5, {scale:1,rotationX:0,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}, {x:0,y:0,scale:0.8,rotationZ:0,rotationX:90,rotationY:0,skewX:0,skewY:0,autoAlpha:0,z:0.001,transformOrigin:"50% 100%"}, 0.3, {scale:1,rotationX:0,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],

					'adamlabsgallery-flipright':			[0.5, {x:0,y:0,scale:0.8,rotationZ:0,rotationX:0,rotationY:90,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"0% 50%"},{scale:1,rotationY:0,autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,0.3,{autoAlpha:0,scale:0.8,rotationY:90,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ,true],
							
					'adamlabsgallery-fliprightout':		[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,skewX:0,skewY:0,rotationY:0,autoAlpha:1,transformOrigin:"100% 50%"},{scale:1,rotationY:-90,autoAlpha:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,0.3,{scale:1,z:0,rotationY:0,autoAlpha:1,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-flipleft':			[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:-90,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"100% 50%"},{rotationY:0,autoAlpha:1,z:0.001,scale:1,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ,0.3,{autoAlpha:0,rotationY:-90,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,true],
							
					'adamlabsgallery-flipleftout':		[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,skewX:0,skewY:0,rotationY:0,autoAlpha:1,transformOrigin:"0% 50%"},{scale:1,rotationY:90,autoAlpha:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,0.3,{scale:1,z:0,rotationY:0,autoAlpha:1,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-turn':				[0.5, {x:50,y:0,scale:0,rotationZ:0,rotationX:0,rotationY:-40,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{scale:1,x:0,rotationY:0,autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,0.3,{scale:0,rotationY:-40,autoAlpha:1,z:0,x:50,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,true],
							
					'adamlabsgallery-turnout':			[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{rotationY:40,scale:0.6,autoAlpha:0,x:-50,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ,0.3,{scale:1,rotationY:0,z:0,autoAlpha:1,x:0, rotationX:0, rotationZ:0, ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"} ],

					'adamlabsgallery-slide':				[0.5, {x:-10000,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,x:0, y:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:-10000,y:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-slideout':			[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,x:0, y:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:0,y:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],

					'adamlabsgallery-slideright':			[0.5, {xPercent:-50,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:-50,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-sliderightout':		[0.5, {autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:50,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-scaleleft':			[0.5, {x:0,y:0,scaleX:0,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"100% 50%"},{autoAlpha:1,x:0, scaleX:1, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:0,z:0,scaleX:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-scaleright':			[0.5, {x:0,y:0,scaleX:0,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"0% 50%"},{autoAlpha:1,x:0, scaleX:1, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:0,z:0,scaleX:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],

					'adamlabsgallery-slideleft':			[0.5, {xPercent:50,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:50,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-slideleftout':		[0.5, {autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:-50,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"}],

					'adamlabsgallery-slideup':			[0.5, {x:0,yPercent:50,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,yPercent:50,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
						
					'adamlabsgallery-slideupout':			[0.5, {autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:0,yPercent:-50,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-slidedown':			[0.5, {x:0,yPercent:-50,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,yPercent:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,yPercent:-50,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-slidedownout':		[0.5, {autoAlpha:1,yPercent:0, z:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:0,yPercent:50,scale:1,rotationZ:0,rotationX:0,z:10,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,yPercent:0,z:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-slideshortright':	[0.5, {x:-30,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,x:-30,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
						
					'adamlabsgallery-slideshortrightout':	[0.5, {autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:30,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,x:30, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-slideshortleft':		[0.5, {x:30,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,x:30,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-slideshortleftout':	[0.5, {autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:-30,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,x:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"}],

					'adamlabsgallery-slideshortup':		[0.5, {x:0,y:30,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,y:30,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-slideshortupout':	[0.5, {autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:0,y:-30,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-slideshortdown':		[0.5, {x:0,y:-30,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,y:-30,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-slideshortdownout':	[0.5, {autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{x:0,y:30,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-skewright':			[0.5, {xPercent:-100,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:60,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,skewX:-60,xPercent:-100,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-skewrightout':		[0.5, {autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:100,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:-60,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"}],

					'adamlabsgallery-skewleft':			[0.5, {xPercent:100,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:-60,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:100,z:0,skewX:60,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-skewleftout':		[0.5, {autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:-100,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:60,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, skewX:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"}],

					'adamlabsgallery-shifttotop':			[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,y:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],

					'adamlabsgallery-rollleft':			[0.5, {xPercent:50,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:90,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:50,z:0,rotationZ:90,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-rollleftout':		[0.5, {autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:50,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:90,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-rollright':			[0.5, {xPercent:-50,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:-90,transformOrigin:"50% 50%"},{autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:0,xPercent:-50,rotationZ:-90,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-rollrightout':		[0.5, {autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},{xPercent:-50,y:0,scale:1,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:-90,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,xPercent:0, rotationZ:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-falldown':			[0.4, {x:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0, yPercent:-100},{autoAlpha:1,yPercent:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.4,{yPercent:-100,autoAlpha:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,delay:0.2,overwrite:"all"} ],
							
					'adamlabsgallery-falldownout':		[0.4, {autoAlpha:1,yPercent:0,ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},{x:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0, yPercent:100},0.4,{autoAlpha:1,yPercent:0,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ],

					'adamlabsgallery-zoomin':		        [0.3, {x:0,y:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:80,scale:0.6,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1,rotationZ:0,ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},0.3,{autoAlpha:0,scale:0.6,z:0,rotationZ:80,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
							
					'adamlabsgallery-rotatescaleout':		[0.3, {autoAlpha:1,scale:1,rotationZ:0,ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},{x:0,y:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,rotationZ:80,scale:0.6,transformOrigin:"50% 50%"},0.3,{autoAlpha:1,scale:1,rotationZ:0,ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"}],

					'adamlabsgallery-zoomintocorner':		[0.5, {x:0, y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"20% 50%"},{autoAlpha:1,scale:1.2, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{x:0, y:0,scale:1,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
							
					'adamlabsgallery-zoomouttocorner':	[0.5, {x:0, y:0,scale:1.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"80% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{x:0, y:0,scale:1.2,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
							
					'adamlabsgallery-zoomtodefault':		[0.5, {x:0, y:0,scale:1.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{x:0, y:0,scale:1.2,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
					
					'adamlabsgallery-zoomdefaultblur':		[0.5, {x:0, y:0,scale:1.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{x:0, y:0,scale:1.2,autoAlpha:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
					
					'adamlabsgallery-zoomback':			[0.5, {x:0, y:0,scale:0.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},0.5,{x:0, y:0,scale:0.2,autoAlpha:0,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
							
					'adamlabsgallery-zoombackout':		[0.5, {autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},{x:0, y:0,scale:0.2,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.5,{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"}],

					'adamlabsgallery-zoomfront':			[0.5, {x:0, y:0,scale:1.5,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.5,{autoAlpha:0,x:0, y:0,scale:1.5,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
							
					'adamlabsgallery-zoomfrontout':		[0.5, {autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},{x:0, y:0,scale:1.5,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:0,transformOrigin:"50% 50%"},0.5,{autoAlpha:1,scale:1, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"}],

					'adamlabsgallery-flyleft':			[0.8, {x:-80, y:0,z:0,scale:0.3,rotationZ:0,rotationY:75,rotationX:10,skewX:0,skewY:0,autoAlpha:0.01,transformOrigin:"30% 10%"},{rotationY:0, rotationX:0,rotationZ:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"},0.8,{autoAlpha:0.01,x:-40, y:0,z:300,rotationY:60,rotationX:20,overwrite:"all"}],
							
					'adamlabsgallery-flyleftout':			[0.8, {rotationY:0,rotationX:0,rotationZ:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"},{x:-80, y:0,z:0,scale:0.3,rotationZ:0,rotationY:75,rotationX:10,skewX:0,skewY:0,autoAlpha:0.01,transformOrigin:"30% 10%"},0.8,{rotationY:0,rotationX:0,rotationZ:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"}],

					'adamlabsgallery-flyright':			[0.8, {skewX:0,skewY:0,autoAlpha:0,x:80, y:0,z:0,scale:0.3,rotationZ:0,rotationY:-75,rotationX:10,transformOrigin:"70% 20%"},{rotationY:0,rotationX:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"},0.8,{autoAlpha:0,x:40, y:-40,z:300,rotationY:-60,rotationX:-40,overwrite:"all"}],
							
					'adamlabsgallery-flyrightout':		[0.8, {rotationY:0,rotationX:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"},{skewX:0,skewY:0,autoAlpha:0,x:80, y:0,z:0,scale:0.3,rotationZ:0,rotationY:-75,rotationX:10,transformOrigin:"70% 20%"},0.8,{rotationY:0, rotationX:0,autoAlpha:1,scale:1, x:0, y:0, z:0,ease:adamlabsgallerygs.Power3.easeInOut,overwrite:"all"}],

					'adamlabsgallery-mediazoom':			[0.3, {x:0, y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1.4, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:0, y:0,scale:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
					
					'adamlabsgallery-zoomblur':			[0.3, {x:0, y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1.4, x:0, y:0, ease:adamlabsgallerygs.Back.easeOut,overwrite:"all"},0.3,{autoAlpha:1,x:0, y:0,scale:1,z:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
					
					'adamlabsgallery-blur':			[0.3, {autoAlpha:1},{autoAlpha:1, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
					
					'adamlabsgallery-grayscalein':			[0.3, {autoAlpha:1},{autoAlpha:1, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
					
					'adamlabsgallery-grayscaleout':			[0.3, {autoAlpha:1, filter: 'grayscale(100%)'},{autoAlpha:1, ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"},0.3,{autoAlpha:1,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],
					
					'adamlabsgallery-zoomandrotate':		[0.6, {x:0, y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{autoAlpha:1,scale:1.4, x:0, y:0, rotationZ:30,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"},0.4,{x:0, y:0,scale:1,z:0,rotationZ:0,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"}],

					'adamlabsgallery-pressback':			[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"50% 50%"},{rotationY:0,autoAlpha:1,scale:0.8,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ,0.3,{rotationY:0,autoAlpha:1,z:0,scale:1,ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ],
							
					'adamlabsgallery-3dturnright':		[0.5, {x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,transformPerspective:600},{x:-40,y:0,scale:0.8,rotationZ:2,rotationX:5,rotationY:-28,skewX:0,skewY:0,autoAlpha:1,transformOrigin:"100% 50% 40%",transformPerspective:600,ease:adamlabsgallerygs.Power3.easeOut,overwrite:"all"} ,0.3,{z:0,x:0,y:0,scale:1,rotationZ:0,rotationX:0,rotationY:0,skewX:0,skewY:0,autoAlpha:1,force3D:"auto",ease:adamlabsgallerygs.Power2.easeOut,overwrite:"all"} ,true]
						   
				};
						   
			miGalleryItemAnimations = {
	  
				'adamlabsgallery-item-zoomin': {
					enter: {time: 0.3, obj: {transformOrigin: '50% 50%', overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Power3.easeOut}},
					leave:  {time: 0.3, obj: {transformOrigin: '50% 50%', scale: 1, overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Power3.easeOut}}
				},
				
				'adamlabsgallery-item-zoomout': {
					enter: {time: 0.3, obj: {transformOrigin: '50% 50%', overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Sine.easeOut}},
					leave:  {time: 0.3, obj: {transformOrigin: '50% 50%', scale: 1, overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Sine.easeOut}}
				},
				
				'adamlabsgallery-item-fade': {
					enter: {time: 0.3, obj: {overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Sine.easeOut}},
					leave:  {time: 0.3, obj: {opacity: 1, overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Sine.easeOut}}
				},
				
				'adamlabsgallery-item-blur': {
					enter: {time: 0.3, obj: {overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Power2.easeOut}},
					leave:  {time: 0.3, obj: {blur: 0, overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Power2.easeOut}}
				},
				
				'adamlabsgallery-item-shift': {
					enter: {time: 0.3, obj: {overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Sine.easeOut}},
					leave:  {time: 0.3, obj: {x: 0, y: 0, overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Sine.easeOut}}
				},
				
				'adamlabsgallery-item-rotate': {
					enter: {time: 0.3, obj: {transformOrigin: '50% 50%', overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Sine.easeOut}},
					leave:  {time: 0.3, obj: {transformOrigin: '50% 50%', rotation: 0, overwrite: 'all', force3D: 'auto', ease: adamlabsgallerygs.Sine.easeOut}}
				}
				
			};
			
			// 2.2.5
			startAnimations = jQuery.extend(true, {}, miGalleryAnimmatrix);
			mergedTransitions = [
			
				'slideup',
				'covergrowup',
				'slideleft',
				'slidedown',
				'flipvertical',
				'fliphorizontal',
				'flipup',
				'flipdown',
				'flipright',
				'flipleft',
				'skewleft',
				'flipleft',
				'zoomin',
				'flyleft',
				'flyright'
			
			];
			
	  }
	  
	  /* 2.1.5 compatibility for SR defer (tools) */
	  if(typeof adamlabsgallerygs !== 'undefined') {

		  miGalleryReady();
		  
	  }
	  /* tools is not available yet, wait until it is */
	  else {

		  var adamlabsgallerygsTimer = setInterval(function() {
			 
				if(typeof adamlabsgallerygs !== 'undefined') {

					clearInterval(adamlabsgallerygsTimer);
					miGalleryReady();
					
				}
			  
		  }, 100);
		  
	  }

	////////////////////////////////////////
	// THE REVOLUTION PLUGIN STARTS HERE //
	///////////////////////////////////////


	jQuery.fn.extend({

		// OUR PLUGIN HERE :)
		adamlabsgallery: function(options) {
				
				////////////////////////////////
				// SET DEFAULT VALUES OF ITEM //
				////////////////////////////////
				//! DEFAULT OPTIONS
				jQuery.fn.adamlabsgallery.defaults = {

					forceFullWidth:"off",
					forceFullScreen:"off",
					fullScreenOffsetContainer:"",
					row:3,
					column:4,
					space:10,						//Spaces between the Grid Elements

					pageAnimation:"fade",			//horizontal-flipbook,  vertical-flipbook,
													//horizontal-flip, vertical-flip,
													//fade,
													//horizontal-slide, vertical-slide,

					animSpeed:600,
					delayBasic:0.08,

					smartPagination:"on",
					paginationScrollToTop:"off",
					paginationScrollToTopOffset:200,

					layout:"even",					//masonry, even, cobbles
					rtl:"off",						// RTL MANAGEMENT

					aspectratio:"auto",				//16:9, 4:3, 1:1, ....

					bgPosition:"center center",		//left,center,right,  top,center,bottom,  50% 50%
					bgSize:"cover",					//cover,contain,normal
					videoJsPath:"",
					overflowoffset:0,
					mainhoverdelay:0,			// The Delay before an Item act on Hover at all.
					rowItemMultiplier:[],
					filterGroupClass:"",
					filterType:"",
					filterLogic:"or",
					filterDeepLink:"off",
					showDropFilter:"hover",
					filterNoMatch:"No Items for the Selected Filter",
					evenGridMasonrySkinPusher:"on",

					loadMoreType:"none",		//none, button, scroll
					loadMoreItems:[],
					loadMoreAmount:5,
					loadMoreTxt : "Load More",
					loadMoreNr:"on",
					loadMoreEndTxt: "No More Items for the Selected Filter",
					loadMoreAjaxUrl:"",
					loadMoreAjaxToken:"",
					loadMoreAjaxAction:"",


					lazyLoad:"off",
					lazyLoadColor:"#ff0000",

					gridID:0,

					mediaFilter:"", 		

					spinner:"",
					spinnerColor:"",

					lightBoxMode:"single",

					cobblesPattern:"",

					searchInput:".faqsearch",

					googleFonts:'',
					googleFontJS:'//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js',

					ajaxContentTarget:"",			// In Which Container (ID!) the Content should be loaded
					ajaxScrollToOnLoad:"off",		// On Load the Container should roll up to a position
					ajaxScrollToOffset:100,
					ajaxCallback:"",				// The call back should be called after content is loaded
					ajaxCallbackArgument:"on",		// Extend the Call back function with the object of infos
					ajaxCssUrl:"",					// Load CSS when Ajax loaded 1st time
					ajaxJsUrl:"",					// Load JS when Ajax loaded 1st time
					ajaxCloseButton:"on",
					ajaxNavButton:"on",
					ajaxCloseTxt:"Close",			// The Text what we write default on the Button
					ajaxCloseType:"type1",			// type1 / type2 to show only the buttons, or with text together the buttons
					ajaxClosePosition:"tr",			// Position of the Button  (tl,t,tr,bl,b,br)
					ajaxCloseInner:"true",			// Inner or Outer of the Position
					ajaxCloseStyle:"light",			// Style - Light or Dark
					ajaxTypes:[],					// AWAITING OF OBJECT  type:"type of content", func:jQuery function

					youtubeNoCookie:"false",

					cookies: {
						search:"off",
						filter:"off",
						pagination:"off",
						loadmore:"off",
						timetosave:"30"
					}

				};

				options = jQuery.extend({}, jQuery.fn.adamlabsgallery.defaults, options);
				if (typeof WebFontConfig=="undefined") WebFontConfig = {};
				
				/* 2.1.5 compatibility for SR defer (tools) */
				function onInit() {

					var opt=options,
						win;

					var container = jQuery(this),
						containerIds = container.attr('id');
					
					opt.contPadTop = parseInt(container.css('paddingTop'),0);
					opt.contPadBottom = parseInt(container.css('paddingBottom'),0);
					opt.viewportBuffer = opt.hasOwnProperty('viewportBuffer') ? opt.viewportBuffer * 0.01 : 0;
					if(!opt.hasOwnProperty('inViewport')) opt.inViewport = true;
					
					// if (container == undefined) return false;

					container.parent().css('position','relative');

					if (opt.layout=="cobbles") {
						opt.layout = "even";
						opt.evenCobbles = "on";
					} else {
						opt.evenCobbles = "off";
					}
					
					/******************************
						-	GOOGLE FONTS PRELOADING	-
					********************************/
					//! GOOGLE FONTS LOADING
					function gridInit(container,opt) {
						mainPreparing(container,opt);
						opt.initialised="ready";
						jQuery('body').trigger('adamlabsgalleryready',container.attr('id'));
					}

					if (opt.get!="true" && opt.get!=true) {

						opt.get=true;

						// SELECTOR CONTAINER FOR FILTER GROUPS
						if (opt.filterGroupClass==undefined || opt.filterGroupClass.length==0) {
							opt.filterGroupClass = "#" + container.attr('id');
						} else
						   opt.filterGroupClass = "."+opt.filterGroupClass;

						   

						// REPORT SOME IMPORTAN INFORMATION ABOUT THE SLIDER
						if (window.adamlabslogs==true)
							try{
								console.groupCollapsed("Portfolio Gallery Initialisation on "+container.attr('id'));
								console.groupCollapsed("Used Options:");
								console.info(options);
								console.groupEnd();
								console.groupCollapsed("Tween Engine:");
							} catch(e) {}

						// CHECK IF adamlabsgallerygs.TweenLite IS LOADED AT ALL
						if (adamlabsgallerygs.TweenLite==undefined) {
							if (window.adamlabslogs==true)
							    try {console.error("GreenSock Engine Does not Exist!");} catch(e) {}
							return false;
						}

						adamlabsgallerygs.force3D = true;

						if (window.adamlabslogs==true)
							try {console.info("GreenSock Engine Version in Portfolio Gallery:"+adamlabsgallerygs.TweenLite.version);} catch(e) {}

						adamlabsgallerygs.TweenLite.lagSmoothing(2000, 16);
						adamlabsgallerygs.force3D = "auto";

						if (window.adamlabslogs==true)
							try {
								console.groupEnd();
								console.groupEnd();
								} catch(e) {}


						// FULLSCREEN MODE TESTING
						jQuery("body").data('fullScreenMode',false);
						jQuery(window).on('mozfullscreenchange webkitfullscreenchange fullscreenchange',function(){
						     jQuery("body").data('fullScreenMode',!jQuery("body").data('fullScreenMode'));

						});


						// CREATE THE SPINNER
						opt.adamlabsgalleryloader = buildLoader(container.parent(),opt);

						if (opt.firstshowever==undefined) jQuery(opt.filterGroupClass+'.adamlabsgallery-navigationbutton,'+opt.filterGroupClass+' .adamlabsgallery-navigationbutton').css({visibility:"hidden"});
						// END OF THE SPINNER FUN


						// END OF TEST ELEMENTS

						container.parent().append('<div class="adamlabsgallery-relative-placeholder" style="width:100%;height:auto"></div>');
						container.wrap('<div class="adamlabsgallery-container-fullscreen-forcer" style="position:relative;left:0px;top:0px;width:100%;height:auto;"></div>');
						
						var offl = container.parent().parent().find('.adamlabsgallery-relative-placeholder').offset().left;
						
						if (opt.forceFullWidth=="on" || opt.forceFullScreen=="on")
							container.closest('.adamlabsgallery-container-fullscreen-forcer').css({left:(0-offl),width:jQuery(window).width()});

						opt.animDelay = (opt.delayBasic==0) ? "off" : "on" ;

						opt.container = container;
						opt.mainul = container.find('ul').first();
						opt.mainul.addClass("mainul").wrap('<div class="adamlabsgallery-overflowtrick"></div>');

						// MANIPULATE LEFT / RIGHT BUTTONS
						var ensl = jQuery(opt.filterGroupClass+'.adamlabsgallery-navbutton-solo-left,'+opt.filterGroupClass+' .adamlabsgallery-navbutton-solo-left');
						var ensr = jQuery(opt.filterGroupClass+'.adamlabsgallery-navbutton-solo-right,'+opt.filterGroupClass+' .adamlabsgallery-navbutton-solo-right');

						if (ensl.length>0) {
							ensl.css({marginTop:(0-ensl.height()/2)});
							ensl.appendTo(container.find('.adamlabsgallery-overflowtrick'));
						}

						if (ensr.length>0) {
							ensr.css({marginTop:(0-ensr.height()/2)});
							ensr.appendTo(container.find('.adamlabsgallery-overflowtrick'));
						}


						adamlabsgallerygs.CSSPlugin.defaultTransformPerspective = 1200;

						opt.animSpeed=opt.animSpeed/1000;
						opt.delayBasic=opt.delayBasic/100;


						setOptions(container,opt);

						opt.filter = opt.statfilter;

						opt.origcolumn = opt.column;
						opt.currentpage = 0;

						//opt.started=true;

						container.addClass("adamlabsgallery-layout-"+opt.layout);

						/******************************
							-	CHECK VIDEO API'S	-
						********************************/
						loadVideoApis(container,opt);

						/**********************************************************************
							-	CHECK IF GRID IS FULLSCREEN AND SET PREDEFINED HEIGHT	-
						**********************************************************************/

						if (opt.layout=="even" && opt.forceFullScreen=="on") {
							var coh = jQuery(window).height();
							if (opt.fullScreenOffsetContainer!=undefined) {
								try{
									var offcontainers = opt.fullScreenOffsetContainer.split(",");
									if (offcontainers)
										jQuery.each(offcontainers,function(index,searchedcont) {
											coh = coh - jQuery(searchedcont).outerHeight(true);
											if (coh<opt.minFullScreenHeight) coh=opt.minFullScreenHeight;
										});
								} catch(e) {}
							}

							var adamlabsgalleryo = container.find('.adamlabsgallery-overflowtrick').first();
							var ul = container.find('ul').first();
							adamlabsgalleryo.css({display:"block",height:coh+"px"});
							ul.css({display:"block",height:coh+"px"});
							container.closest('.adamlabsgallery-grid-wrapper, .myportfolio-container').css({height:"auto"}).removeClass("adamlabsgallery-startheight");
						}

						if (opt.googleFonts.length!=0 && opt.layout=="masonry") {
							// var fontstoload = opt.googleFonts.length;
							var loadit = true;

							jQuery('head').find('*').each(function(){
								if (jQuery(this).attr('src')!=undefined && jQuery(this).attr('src').indexOf('webfont.js') >0)
										loadit = false;
							});
							if (WebFontConfig.active==undefined && loadit) {
								WebFontConfig = {
									google: { families: opt.googleFonts  },
									active: function() {
											gridInit(container,opt);
									},
									inactive: function() {
											gridInit(container,opt);
									},
									timeout:1500
								};
								var wf = document.createElement('script');
								wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
								   '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
								wf.type = 'text/javascript';
								wf.async = 'true';
								var s = document.getElementsByTagName('script')[0];
								s.parentNode.insertBefore(wf, s);
							} else {
								gridInit(container,opt);
							}
						/**********************************************
							-	WITHOUT GOOGLE FONT, START THE GRID	-
						**********************************************/

						} else {
							gridInit(container,opt);
						}



						/***********************************
							-	LOAD MORE ITEMS HANDLING	-
						************************************/

						//! LOAD MORE ITEMS

						if (opt.loadMoreType=="button") {
							container.append('<div class="adamlabsgallery-loadmore-wrapper" style="text-align:center"><div class="adamlabsgallery-navigationbutton adamlabsgallery-loadmore">LOAD MORE</div></div>');
							opt.lmbut = opt.container.find('.adamlabsgallery-loadmore');
							opt.lmtxt = opt.loadMoreTxt+" ("+checkMoreToLoad(opt).length+")";
														
							if (opt.loadMoreNr=="off")
								opt.lmtxt = opt.loadMoreTxt;

							opt.lmbut.html(opt.lmtxt);

							opt.lmbut.click(function() {
								if (opt.lmbut.data('loading')!=1)
								 loadMoreItems(opt);
							});

							if (checkMoreToLoad(opt).length==0) 
								opt.lmbut.remove();
						}

						else

						if (opt.loadMoreType=="scroll") {

							container.append('<div style="display:inline-block" class="adamlabsgallery-navigationbutton adamlabsgallery-loadmore">LOAD MORE</div>');

							opt.lmbut = opt.container.find('.adamlabsgallery-loadmore');
							opt.lmtxt = opt.loadMoreTxt+" ("+checkMoreToLoad(opt).length+")";
							if (opt.loadMoreNr=="off")
								opt.lmtxt = opt.loadMoreTxt;
							opt.lmbut.html(opt.lmtxt);




							jQuery(document, window).scroll(function() {								
								checkBottomPos(opt,true);

							});
							
							if (checkMoreToLoad(opt).length==0) 
								opt.lmbut.remove();
						}

						// checkAvailableFilters(container,opt);
						tabBlurringCheck(container,opt);
					}

					/* 2.2 */
					jQuery('body').on('click.' + containerIds, '.adamlabsgallery-filterbutton', function() {
 
					    var grid = jQuery(this).closest('.adamlabsgallery-grid');
					 
					    if(grid.find('.adamlabsgallery-item').length === grid.find('.adamlabsgallery-item.itemishidden').length) {
					 		grid.append('<span class="no-filter-message">'+opt.filterNoMatch+'</span>');
					        grid.addClass('show-message');
					 
					    }
					    else {
					 
					        grid.removeClass('show-message');
					 
					    }
					 
					}).on('mouseover', '.adamlabsgallery-item', function() {
						
						/* 2.2.6 */
						jQuery('.adamlabsgallery-item-in-focus').removeClass('adamlabsgallery-item-in-focus');
						jQuery(this).addClass('adamlabsgallery-item-in-focus');
						
					}).on('mouseenter.' + containerIds + ' mouseover.' + containerIds, function() {
						
						jQuery('.adamlabsgallerybox-container').addClass('adamlabsgallerybox-show-toolbar adamlabsgallerybox-show-nav');
						
					}).on('click.' + containerIds, '.adamlabsgallerybox', function() {

						/* 2.1.5 */
						stopAllVideos(true);
						
					}).on('click.' + containerIds, '.adamlabsgallerybox-clone', function() {
						
						jQuery(this).closest('.adamlabsgallery-item').find('.adamlabsgallerybox').eq(0).click();
						return false;
						
					}).on('mouseenter.' + containerIds + ' mouseleave.' + containerIds, '.adamlabsgallery-anime-item', function(e) {
						
						/* 2.1.6.2 */
						var $this = jQuery(this).find('.adamlabsgallery-item-anime');
						if(!$this.length) return;
						
						var tpe = e.type.replace('mouse', ''),
							data = $this.data(),
							itm = data.anime_itm,
							other = data.anime_itm_other,
							anime,
							start,
							prop,
							obj,
							val,
							cur,
							tw;

						if(other) {
							
							var animeOther = miGalleryItemAnimations[other][tpe],
								objOther = jQuery.extend({}, animeOther.obj);
							
							if(tpe === 'enter') {
															
								switch(other) {
									
									case 'adamlabsgallery-item-zoomin':
										val = data.anime_itm_other_zoomin;
										if(isNaN(val)) val = '100';
										val = parseInt(val, 10) * 0.01;
										objOther.scale = Math.max(Math.min(val, 2), 0);
									break;
									
									case 'adamlabsgallery-item-zoomout':
										val = data.anime_itm_other_zoomout;
										if(isNaN(val)) val = '100';
										val = parseInt(val, 10) * 0.01;
										objOther.scale = Math.max(Math.min(val, 2), 0);
									break;
									
									case 'adamlabsgallery-item-fade':
										val = data.anime_itm_other_fade;
										if(isNaN(val)) val = '100';
										val = parseInt(val, 10) * 0.01;
										objOther.opacity = Math.max(Math.min(val, 1), 0);
									break;
									
									case 'adamlabsgallery-item-blur':
									
										val = data.anime_itm_other_blur;
										if(isNaN(val)) val = '5';
										val = parseInt(val, 10);
										val = Math.max(Math.min(val, 30), 0);
										objOther.blur = Math.max(Math.min(val, 30), 0);
											
										opt.container.find('.isvisiblenow .adamlabsgallery-item-anime').not($this).each(function() {
									
											var $_this = jQuery(this);
											cur = $_this.data('anime_blur_amount') || 0;
											anime = $_this.data('anime_blur');
											if(anime) {
												anime.eventCallback('onUpdate', null);
												anime.kill();
											}
											
											start = {blur: cur};
											obj = jQuery.extend({}, objOther);
											tw = new adamlabsgallerygs.TweenLite(start, animeOther.time, obj);
												
											$_this.data('anime_blur', tw);
											tw.eventCallback('onUpdate', function() {	
												
												$_this.data('anime_blur_amount', start.blur);
												adamlabsgallerygs.TweenLite.set($_this.find('.adamlabsgallery-entry-media'), {msFilter: 'blur('+ start.blur + 'px)', filter: 'blur('+ start.blur + 'px)', webkitFilter: 'blur(' + start.blur + 'px)'});
											
											});
											
										});
										
									break;
									
									case 'adamlabsgallery-item-shift':
										prop = data.anime_itm_other_shift;
										val = data.anime_itm_shift_other_amount;
										if(isNaN(val)) val = '10';
										val = parseInt(val, 10);
										if(prop === 'up' || prop === 'right') val *= -1;
										prop = prop === 'up' || prop === 'down' ? 'y' : 'x';
										objOther[prop] = Math.max(Math.min(val, 200), -200);
									break;
									
									case 'adamlabsgallery-item-rotate':
										val = data.anime_itm_other_rotate;
										if(isNaN(val)) val = '100';
										val = parseInt(val, 10);
										objOther.rotation = Math.max(Math.min(val, 359), -359);
									break;
									
								}
								
							}
							
							if(!objOther.hasOwnProperty('blur')) {
								adamlabsgallerygs.TweenLite.to(opt.container.find('.isvisiblenow .adamlabsgallery-item-anime').not($this), animeOther.time, objOther);
							}
							else if(tpe === 'leave') {
								
								opt.container.find('.isvisiblenow .adamlabsgallery-item-anime').not($this).each(function() {
									
									var $_this = jQuery(this);
									cur = $_this.data('anime_blur_amount');
										
									if(!cur) return;
									
									anime = $_this.data('anime_blur');
									if(anime) {
										anime.eventCallback('onUpdate', null);
										anime.kill();
									}
									
									start = {blur: cur};
									obj = jQuery.extend({}, objOther);
									tw = new adamlabsgallerygs.TweenLite(start, animeOther.time, obj);
										
									$_this.data('anime_blur', tw);
									tw.eventCallback('onUpdate', function() {	
										
										$_this.data('anime_blur_amount', start.blur);
										adamlabsgallerygs.TweenLite.set($_this.find('.adamlabsgallery-entry-media'), {msFilter: 'blur('+ start.blur + 'px)', filter: 'blur('+ start.blur + 'px)', webkitFilter: 'blur(' + start.blur + 'px)'});
									
									});
									
								});
								
							}
							
						}

						if(itm) {
							
							var animeItm = miGalleryItemAnimations[itm][tpe],
								objAnime = jQuery.extend({}, animeItm.obj);
							
							if(tpe === 'enter') {
								
								switch(itm) {
									
									case 'adamlabsgallery-item-zoomin':
										val = data.anime_itm_zoomin;
										if(isNaN(val)) val = '100';
										val = parseInt(val, 10) * 0.01;
										objAnime.scale = Math.max(Math.min(val, 2), 0);
									break;
									
									case 'adamlabsgallery-item-zoomout':
										val = data.anime_itm_zoomout;
										if(isNaN(val)) val = '100';
										val = parseInt(val, 10) * 0.01;
										objAnime.scale = Math.max(Math.min(val, 2), 0);
									break;
									
									case 'adamlabsgallery-item-fade':
										val = data.anime_itm_fade;
										if(isNaN(val)) val = '100';
										val = parseInt(val, 10) * 0.01;
										objAnime.opacity = Math.max(Math.min(val, 1), 0);
									break;
									
									case 'adamlabsgallery-item-blur':
									
										val = data.anime_itm_blur;
										if(isNaN(val)) val = '5';
										val = parseInt(val, 10);
										objAnime.blur = Math.max(Math.min(val, 30), 0);
										
										cur = $this.data('anime_blur_amount') || 0;
										anime = $this.data('anime_blur');
											
										if(anime) {
											anime.eventCallback('onUpdate', null);
											anime.kill();
										}
										
										start = {blur: cur};
										tw = new adamlabsgallerygs.TweenLite(start, animeItm.time, objAnime);
											
										$this.data('anime_blur', tw);
										tw.eventCallback('onUpdate', function() {	
											
											$this.data('anime_blur_amount', start.blur);
											adamlabsgallerygs.TweenLite.set($this.find('.adamlabsgallery-entry-media'), {msFilter: 'blur('+ start.blur + 'px)', filter: 'blur('+ start.blur + 'px)', webkitFilter: 'blur(' + start.blur + 'px)'});
										
										});
									
									break;
									
									case 'adamlabsgallery-item-shift':
										prop = data.anime_itm_shift;
										val = data.anime_itm_shift_amount;
										if(isNaN(val)) val = '10';
										val = parseInt(val, 10);
										if(prop === 'up' || prop === 'right') val *= -1;
										prop = prop === 'up' || prop === 'down' ? 'y' : 'x';
										objAnime[prop] = Math.max(Math.min(val, 200), -200);
									break;
									
									case 'adamlabsgallery-item-rotate':
										val = data.anime_itm_rotate;
										if(isNaN(val)) val = '30';
										val = parseInt(val, 10);
										objAnime.rotation = Math.max(Math.min(val, 359), -359);
									break;
									
								}
							}

							if(!objAnime.hasOwnProperty('blur')) {
								adamlabsgallerygs.TweenLite.to($this, animeItm.time, objAnime);
							}
							else {
								
								cur = $this.data('anime_blur_amount');
								if(!cur) return;
								
								anime = $this.data('anime_blur');
								if(anime) {
									anime.eventCallback('onUpdate', null);
									anime.kill();
								}
								
								start = {blur: cur};
								tw = new adamlabsgallerygs.TweenLite(start, animeItm.time, objAnime);
									
								$this.data('anime_blur', tw);
								tw.eventCallback('onUpdate', function() {	
									
									$this.data('anime_blur_amount', start.blur);
									adamlabsgallerygs.TweenLite.set($this.find('.adamlabsgallery-entry-media'), {msFilter: 'blur('+ start.blur + 'px)', filter: 'blur('+ start.blur + 'px)', webkitFilter: 'blur(' + start.blur + 'px)'});
								
								});	
							}
						}
					});
					
					if(opt.paginationSwipe === 'on') {
						
						var pageX,
							newX;
						
						container.find('.adamlabsgallery-overflowtrick').on('touchstart', function(event) {
							
							event = event.originalEvent;
							if(event.touches) event = event.touches[0];
							pageX = event.pageX;
							if(opt.paginationDragVer === 'off') return false;
							
						}).on('touchmove', function(event) {
							
							event = event.originalEvent;
							if(event.touches) event = event.touches[0];
							newX = event.pageX;
							
						}).on('touchend', function() {
							
							if(Math.abs(pageX - newX) > opt.pageSwipeThrottle) {
							
								if(pageX > newX) onRightNavClick(container);
								else onLeftNavClick(container);
							
							}
							
						});
						
 					}
					
					if(opt.paginationAutoplay === 'on') {
						
						var paginationMouse, 
							paginationDelay,
							paginationLoaded;
							
						function paginationMouseOut() {

							paginationMouse = false;
							clearInterval(paginationDelay);
							if(paginationLoaded) paginationDelay = setInterval(changeGrid, opt.paginationAutoplayDelay); 

						}

						function changeGrid() {

							onRightNavClick(container);

						}
							
						container.on('mouseenter.adamlabsgallerypagination', function() {

							paginationMouse = true;
							clearInterval(paginationDelay);

						}).on('mouseleave.adamlabsgallerypagination', paginationMouseOut);

						container.on('adamlabsgallery_ready_to_use', function() {
							
							if(!container.find('.adamlabsgallery-pagination').length) {
								
								container.off('.adamlabsgallerypagination');
								return;
								
							}
							
							paginationLoaded = true;
							if(!paginationMouse) paginationMouseOut();
							
						});
					}

					if(opt.filterDeepLink == "on") {
	 
						jQuery(".adamlabsgallery-filterbutton").click(function(){
					 		var beforehash = window.location.href.split('#');
					 		beforehash = beforehash[0]; 
					 		filter = jQuery(this).data("filter");
					 		if(filter.indexOf("filter-")!=0){
					 			history.pushState({}, null,  beforehash);
					 		}
					 		else{
					 			var hash = filter.replace("filter-","#");
					 			history.pushState({}, null,  beforehash + hash);
					 		}
					 	});

					    var grid = jQuery('.adamlabsgallery-grid'),
					    url = window.location.href;
					    if(!grid.length || url.search('#') === -1) return;
					 
					    var hash = url.split('#');
					    hash = hash[hash.length - 1];
					    if(!hash) return;
					 
					    hash = hash.toLowerCase().split(' ').join('').split('/').join('');
					    var timer = setInterval(function() {
					 
					        if(grid.is(':visible')) {
					 
					            clearInterval(timer);
					            jQuery('div[data-filter="filter-' + hash + '"]').trigger('click');
					        }
					 
					    }, 500);

					}
					
					function scrollCheck() {
					
						var rect = container[0].getBoundingClientRect(),
							high = win.height(),
							perc = high * opt.viewportBuffer;

						if(rect.top < high - perc && rect.bottom > perc) {
							
							win.off('scroll.adamlabsgalleryviewport resize.adamlabsgalleryviewport', scrollCheck);
							opt.inViewport = true;
							
							container.find('.adamlabsgallery-item').each(function() {
								
								var itm = jQuery(this),	
									vanime = itm.data('viewportanime');
						
								if(vanime) adamlabsgallerygs.TweenLite.to(itm, vanime[0], vanime[1]);
								
							});
							
						}
						
					}
					
					if(!opt.inViewport) {
						
						var ids = container[0].id;
						win = jQuery(window).on('scroll.adamlabsgalleryviewport resize.adamlabsgalleryviewport', scrollCheck);
						scrollCheck();
						
					}

				}
				
				if(miGalleryAnimmatrix) {

					return this.each(onInit);
					
				}
				/* tools is not available yet, wait until it is */
				else {
				
					var $this = this,
						initTimer = setInterval(function() {
						
							if(miGalleryAnimmatrix) {

								clearInterval(initTimer);
								$this.each(onInit);
								
							}
						
					}, 100);
					 
				}

				return this;


		},

		//! METHODS
		// APPEND NEW ELEMENT
		adamlabsgalleryappend: function(options) {
						// CATCH THE CONTAINER
						var container=jQuery(this);
						prepareItemsInGrid(opt,true);
						//setItemsOnPages(opt);
						organiseGrid(opt,"adamlabsgalleryappend");
						prepareSortingAndOrders(container);

						return opt.lastslide;

				},
		adamlabsgallerykill: function() {
			
						var container = jQuery(this),
							ids = container.attr('id');
						
						// 2.2.5
						jQuery('body').off('.' + ids);
						jQuery(window).off('.resize.adamlabsgallery' + ids + ' resize.adamlabsgallerylb' + ids);
						
						container.find('*').each(function() {
							jQuery(this).off();
							jQuery(this).remove();
						});
						container.remove();
						container.html();
						container = null;
				},

		// METHODE CURRENT
		adamlabsgalleryreadsettings: function(options) {
						options = options == undefined ? {} : options;
						// CATCH THE CONTAINER
						var container=jQuery(this);
						var opt = getOptions(container);
						return opt;
				},

		// METHODE CURRENT
		adamlabsgalleryredraw: function(options) {
						options = options == undefined ? {} : options;
						// CATCH THE CONTAINER
						var container=jQuery(this);
						var opt = getOptions(container);
						if (opt===undefined) return;
						if (options!=undefined) {
							if (options.space!=undefined)  opt.space=parseInt(options.space,0);
							if (options.row!=undefined)  opt.row=parseInt(options.row,0);
							if (options.rtl!=undefined) opt.rtl=options.rtl;
							if (options.aspectratio!=undefined)  opt.aspectratio=options.aspectratio;
							if (options.forceFullWidth!=undefined) {
								opt.forceFullWidth = options.forceFullWidth;
								if (opt.forceFullWidth=="on") {
									var offl = container.parent().parent().find('.adamlabsgallery-relative-placeholder').offset().left;
									container.closest('.adamlabsgallery-container-fullscreen-forcer').css({left:(0-offl),width:jQuery(window).width()});
								}
								else
								container.closest('.adamlabsgallery-container-fullscreen-forcer').css({left:0,width:"auto"});
							}

							if (options.rowItemMultiplier!=undefined) opt.rowItemMultiplier = options.rowItemMultiplier;

							if (options.responsiveEntries!=undefined) opt.responsiveEntries = options.responsiveEntries;
							if (options.hideBlankItemsAt!=undefined) opt.hideBlankItemsAt = options.hideBlankItemsAt;

							if (options.column!=undefined)  {
								if (options.column<=0 || options.column>=20) {
									var gbfc = getBestFitColumn(opt,jQuery(window).width(),"id");
									opt.column = gbfc.column;
									opt.columnindex = gbfc.index;
									opt.mmHeight = gbfc.mmHeight;
								} else {
									opt.column=parseInt(options.column,0);
								}
								opt.origcolumn = opt.column;
							}

							if (options.animSpeed!=undefined)  opt.animSpeed=options.animSpeed/1000;
							if (options.delayBasic!=undefined)  opt.delayBasic=options.delayBasic/100;

							if (options.pageAnimation!=undefined)  opt.pageAnimation = options.pageAnimation;
							if (options.changedAnim!=undefined)  opt.changedAnim = options.changedAnim;
							if (options.silent == true) opt.silent=true;
						}


						opt.started=true;

						
						setOptions(container,opt);
						setItemsOnPages(opt);
						organiseGrid(opt,"adamlabsgalleryredraw");

				},
		// QUICK REDRAW ITEMS
		adamlabsgalleryquickdraw: function(options) {


						// CATCH THE CONTAINER
						var container=jQuery(this);
						var opt = getOptions(container);
						opt.silent=true;
						setOptions(container,opt);
						setItemsOnPages(opt);
						organiseGrid(opt,"adamlabsgalleryquickdraw");
						stopAllVideos(true);

		},

		// METHODE CURRENT
		adamlabsgalleryreinit: function(options) {
						// CATCH THE CONTAINER
						var container=jQuery(this);
						prepareItemsInGrid(opt,true);
						//setItemsOnPages(opt);
						organiseGrid(opt,"adamlabsgalleryreinit");
						prepareSortingAndOrders(container);

						return opt.lastslide;
				}


});
		
		function checkBottomPos(opt,scroll) {			
			var bottompos = (opt.container.offset().top + opt.container.height() + (opt.contPadTop + opt.contPadBottom)) - jQuery(document).scrollTop(),
				wh = jQuery(window).height(),
				dh = jQuery(document).height();
			
			if ((opt.lastBottomCompare!=bottompos && wh>=bottompos) || (scroll && wh>=bottompos) || (dh===wh && wh>bottompos)) {		
			
					opt.lastBottomCompare = bottompos;
					if (opt.lmbut && opt.lmbut.data('loading')!=1) {
						opt.lmbut.data('loading',1);								
						loadMoreItems(opt);
					}
			}
		}


		/******************************
			-  COOKIE HaNDLING  -
		*******************************/
		function createCookie(name, value, days) {
		    var expires;

		    if (days) {
		        var date = new Date();
		        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		        expires = "; expires=" + date.toGMTString();
		    } else {
		        expires = "";
		    }
		    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
		}

		function readCookie(name) {
		    var nameEQ = encodeURIComponent(name) + "=";
		    var ca = document.cookie.split(';');
		    for (var i = 0; i < ca.length; i++) {
		        var c = ca[i];
		        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
		        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
		    }
		    return null;
		}
		
		/*
		function eraseCookie(name) {
		    createCookie(name, "", -1);
		}
		*/

		/******************************
			-	Action on TAB Blur 	-
		********************************/
		(function(){
		    var stateKey,
		        eventKey,
		        keys = {
		                hidden: "visibilitychange",
		                webkitHidden: "webkitvisibilitychange",
		                mozHidden: "mozvisibilitychange",
		                msHidden: "msvisibilitychange"
		    };
		    for (stateKey in keys) {
		        if (stateKey in document) {
		            eventKey = keys[stateKey];
		            break;
		        }
		    }
		    return function(c) {
		        if (c) document.addEventListener(eventKey, c);
		        return !document[stateKey];
		    };
		})();

		var tabBlurringCheck = function() {

			var notIE = (document.documentMode === undefined),
			    isChromium = window.chrome;

			 if (!jQuery("body").hasClass("adamlabsgallery-blurlistenerexists")) {
				jQuery("body").addClass("adamlabsgallery-blurlistenerexists");
				if (notIE && !isChromium) {

				    // checks for Firefox and other  NON IE Chrome versions
					jQuery(window).on("focusin", function () {
						
						if(supressFocus) return;
						
				        setTimeout(function(){
				            // TAB IS ACTIVE, WE CAN START ANY PART OF THE SLIDER
				            jQuery('body').find('.adamlabsgallery-grid.adamlabsgallery-container').each(function() {
				            	jQuery(this).adamlabsgalleryquickdraw();
				            });
				        },300);

				    }).on("focusout", function () {
						// TAB IS NOT ACTIVE, WE CAN STOP ANY PART OF THE SLIDER
				    });

				} else {

				    // checks for IE and Chromium versions
				    if (window.addEventListener) {

				        // bind focus event
				      window.addEventListener("focus", function (event) {
							
							if(supressFocus) return;
							
				            setTimeout(function(){
				                 // TAB IS ACTIVE, WE CAN START ANY PART OF THE SLIDER
					            jQuery('body').find('.adamlabsgallery-grid.adamlabsgallery-container').each(function() {
					            	jQuery(this).adamlabsgalleryquickdraw();
					            });

				            },300);

				        }, false);



				    } else {

				        // bind focus event
				        window.attachEvent("focus", function (event) {
							
							if(supressFocus) return;
							
				            setTimeout(function(){
								// TAB IS ACTIVE, WE CAN START ANY PART OF THE SLIDER
								jQuery('body').find('.adamlabsgallery-grid.adamlabsgallery-container').each(function() {
				            		jQuery(this).adamlabsgalleryquickdraw();
								});

					         },300);

				        });


				    }
				}
			}
		};

/*********************************
	-	CHECK AVAILABLE FILTERS	-
*********************************/
/*
function checkAvailableFilters(container,opt) {
	container.find('.adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton').each(function() {
		var filt = jQuery(this);

		if (container.find('ul >li.'+filt.data('filter')).length==0) {
			adamlabsgallerygs.TweenLite.to(filt,0.3,{autoAlpha:0.3});
			filt.addClass("notavailablenow");
		} else {
			adamlabsgallerygs.TweenLite.to(filt,0.3,{autoAlpha:1});
		}
	});	
}
*/

/*********************************************************
	-	CHECK IF MORE TO LOAD FOR SELECTED FILTERS	-
*********************************************************/

function checkMoreToLoad(opt) {

	var container = opt.container,
		selfilters  = [],
		fidlist = [],
		/* searchchanged = jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper.adamlabsgallery-search-wrapper .adamlabsgallery-justfilteredtosearch').length, */
		forcesearch =jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper.adamlabsgallery-search-wrapper .adamlabsgallery-forcefilter').length;


	jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected, '+opt.filterGroupClass+' .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected').each(function() {
		var fid = jQuery(this).data('fid');
		if (jQuery.inArray(fid,fidlist)==-1) {
			selfilters.push(fid);
			fidlist.push(fid);
		}
	});

	if (jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected, '+opt.filterGroupClass+' .adamlabsgallery-filter-wrapper .adamlabsgallery-filterbutton.selected').length==0)
		selfilters.push(-1);

	var itemstoload = [];


	for (var i=0;i<opt.loadMoreItems.length;i++) {
		jQuery.each(opt.loadMoreItems[i][1],function(index,filtid) {
			jQuery.each(selfilters,function(selindex,selid) {
				if (selid == filtid && opt.loadMoreItems[i][0]!=-1 && (forcesearch==0 || forcesearch==1 && opt.loadMoreItems[i][2]==="cat-searchresult"))
					itemstoload.push(opt.loadMoreItems[i]);
			});
		});
	}
	
	addCountSuffix(container,opt);
	return itemstoload;
}

function addCountSuffix(container,opt) {

	var searchchanged = jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper.adamlabsgallery-search-wrapper .adamlabsgallery-justfilteredtosearch').length,
		forcesearch =jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper.adamlabsgallery-search-wrapper .adamlabsgallery-forcefilter').length;
	jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper.adamlabsgallery-show-amount .adamlabsgallery-filterbutton').each(function() {
		var filter = jQuery(this);
		if (filter.find('.adamlabsgallery-el-amount').length==0 || searchchanged>0 ) {
			var	fid = filter.data('fid'),
				catname = filter.data('filter');
				if (forcesearch>0)
					catname = catname+".cat-searchresult";
			var amount = container.find('.'+catname).length;


			for (var i=0;i<opt.loadMoreItems.length;i++) {
				// var found = false;
				if (forcesearch==0)
					jQuery.each(opt.loadMoreItems[i][1],function(index,filtid) {

							if (filtid === fid && opt.loadMoreItems[i][0]!=-1 ) amount++;
					});

				else

				if (jQuery.inArray(fid,opt.loadMoreItems[i][1])!=-1 && opt.loadMoreItems[i][2]==="cat-searchresult") {
					amount++;
				}
			}



			if (filter.find('.adamlabsgallery-el-amount').length==0) filter.append('<span class="adamlabsgallery-el-amount">0</span>');
			countToTop(filter,amount);
		}
	});


	jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper.adamlabsgallery-search-wrapper .adamlabsgallery-justfilteredtosearch').removeClass("adamlabsgallery-justfilteredtosearch");

}

function countToTop(filter,amount) {

	var output = filter.find('.adamlabsgallery-el-amount'),
		obj = {value:parseInt(output.text(),0)};
	adamlabsgallerygs.TweenLite.to(obj,2,{value:amount,onUpdate:changeCount,onUpdateParams:["{self}",'value'],ease:adamlabsgallerygs.Power3.easeInOut});
	function changeCount(tween,prop) {
		output.html(Math.round(tween.target[prop]));
	}
}

/******************************
	-	BUILD LOADER 	-
********************************/
function buildLoader(container,opt,nominheight) {
		// CREATE THE SPINNER
		if (opt.adamlabsgalleryloader != undefined && opt.adamlabsgalleryloader.length>0) return false;

		container.append('<div class="adamlabsgallery-loader '+opt.spinner+'">'+
								  		'<div class="dot1"></div>'+
								  	    '<div class="dot2"></div>'+
								  	    '<div class="bounce1"></div>'+
										'<div class="bounce2"></div>'+
										'<div class="bounce3"></div>'+
									 '</div>');
		adamlabsgalleryloader = container.find('.adamlabsgallery-loader');

		if (opt.spinner=="spinner1" || opt.spinner=="spinner2") adamlabsgalleryloader.css('background', opt.spinnerColor);
		if (opt.spinner=="spinner3" || opt.spinner=="spinner4") container.find('.bounce1, .bounce2, .bounce3, .dot1, .dot2').css('background', opt.spinnerColor);
		if (!nominheight) adamlabsgallerygs.TweenLite.to(container,0.3,{minHeight:"100px",zIndex:0});
		return adamlabsgalleryloader;
		// END OF THE SPINNER FUN
}

/***********************************
	-	SET LOADED KEYS TO NULL	-
************************************/

function setKeyToNull(opt,key) {
	jQuery.each(opt.loadMoreItems,function(index,item) {
		if (item[0] == key) {
				opt.loadMoreItems[index][0] = -1;
				opt.loadMoreItems[index][2] ="already loaded";
		}
	});
}

/********************************************************
	-	CHECK AMOUNT OF STILL AVAILABLE ELEMENTS	-
********************************************************/
function loadMoreEmpty(opt) {
	var empty = true;

	for (var i=0;i<opt.loadMoreItems.length;i++) {
		if (opt.loadMoreItems[i][0]!=-1)
		  empty= false;
	}	
	return empty;
}


/******************************
	-	LOAD MORE ITEMS	-
********************************/
function loadMoreItems(opt) {
	
	// COLLECT ELEMENTS FROM ARRAY WE NEED TO LOAD
	var container = opt.container,
		availableItems = checkMoreToLoad(opt),
		itemstoload = [];


	// LOAD IT IF WE HAVE SOMETHIGN TO LOAD
	jQuery.each(availableItems,function(index,item) {
		if (itemstoload.length<opt.loadMoreAmount) {
			itemstoload.push(item[0]);
			setKeyToNull(opt,item[0]);
		}
	});


	var restitems = checkMoreToLoad(opt).length;

	
	
	if (opt.loadMoreType==="scroll") {			
		opt.adamlabsgalleryloader.addClass("infinityscollavailable");
		if (opt.adamlabsgalleryloaderprocess != "add") {
			opt.adamlabsgalleryloaderprocess = "add";
			adamlabsgallerygs.TweenLite.to(opt.adamlabsgalleryloader,0.5,{autoAlpha:1,overwrite:"all"});
		}
	}
	
	



	if (itemstoload.length>0) {
		
		if (opt.lmbut.length>0) {
			adamlabsgallerygs.TweenLite.to(opt.lmbut,0.4,{autoAlpha:0.2});
			opt.lmbut.data('loading',1);
		}

		var objData = {
		     action: opt.loadMoreAjaxAction,
		     client_action: 'load_more_items',
		     token: opt.loadMoreAjaxToken,
		     data: itemstoload,
		     gridid: opt.gridID,
		    };
		
		/* 2.1.6 */
		if(opt.customGallery) objData.customgallery = true;

		jQuery.ajax({
		     type:'post',
		     url:opt.loadMoreAjaxUrl,
		     dataType:'json',
		     data:objData
		    }).success(function(data,status,arg3) {

				if (data.success) {
					
					var addit = jQuery(data.data).filter(function(i) {
						
						if(this.nodeType === 1) {
							
							jQuery(this).data('adamlabsgallery-load-more-new', i);
							return true;
							
						}
						
					});
					
					/* 2.1.6 */
					if(opt.customGallery) addit.addClass('adamlabsgallery-newli');
					
					// IF WE ARE IN SEARCH MODE
					if (jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper.adamlabsgallery-search-wrapper .adamlabsgallery-forcefilter').length>0) addit.addClass("cat-searchresult");
					opt.container.find('ul').first().append(addit);

					// checkAvailableFilters(container,opt);

					// CATCH THE CONTAINER
					prepareItemsInGrid(opt,true);
					setItemsOnPages(opt);
					
					stopAllVideos(true);

					setTimeout(function() {
						// opt.animDelay = "off";
						organiseGrid(opt,"Ajax Loaded");
						prepareSortingAndOrders(container);

						if (loadMoreEmpty(opt)) 
							opt.lmbut.remove();													
						else {
							
							opt.lmtxt = opt.loadMoreTxt+" ("+restitems+")";
							if (opt.loadMoreNr=="off")
								opt.lmtxt = opt.loadMoreTxt;


							if ( restitems== 0)
								opt.lmbut.html(opt.loadMoreEndTxt);
							else
								opt.lmbut.html(opt.lmtxt);
							if (opt.lmbut.length>0) {
								adamlabsgallerygs.TweenLite.to(opt.lmbut,0.4,{autoAlpha:1,overwrite:"all"});
								opt.lmbut.data('loading',0);
							}
						}

						setTimeout(function() {
							opt.animDelay = "on";


						},500);

					},10);
				}
		    }).error(function(arg1, arg2, arg3) {
		    	opt.lmbut.html("FAILURE: "+arg2);
		    });


	} else {
		if (loadMoreEmpty(opt)) {			
			opt.lmbut.remove();
			if (opt.loadMoreType==="scroll")  {
				opt.adamlabsgalleryloader.remove();
				
			}
		} else {
			opt.lmbut.data('loading',0).html(opt.loadMoreEndTxt);
			
		}
	}

	

	//container.find('ul').first().append(li);

}


/*************************************
	-	LOAD AJAX CONTENTS -
*************************************/
function killOldCustomAjaxContent(act) {
	// REMOVE THE CUSTOM CONTAINER LOADED FROM EXTERNAL
					var oldposttype = act.data('lastposttype'),
						postid = act.data('oldajaxsource'),
						posttype = act.data('oldajaxtype'),
						videoaspect = act.data('oldajaxvideoaspect'),
						selector = act.data('oldselector');

					if (oldposttype != undefined && oldposttype!="") {
						try{
							jQuery.each(jQuery.fn.adamlabsgallery.defaults.ajaxTypes,function(index,obj) {
									if (obj != undefined && obj.type!=undefined) {
										if (obj.type==oldposttype && obj.killfunc!=undefined)
												setTimeout(function() {
													if (obj.killfunc.call(this,{id:postid,type:posttype,aspectratio:videoaspect,selector:selector})) {
															act.empty();
													}
												},250);
									}
								});
							} catch(e) { console.log(e);}
					}
					act.data('lastposttype',"");
}


////////////////////////////////
// ADD AJAX NAVIGATION //
////////////////////////////////
function addAjaxNavigagtion(opt,act) {
	var acclass = ' adamlabsgallery-acp-'+opt.ajaxClosePosition;
	acclass = acclass+" adamlabsgallery-acp-"+opt.ajaxCloseStyle;
	acclass = acclass+" adamlabsgallery-acp-"+opt.ajaxCloseType;
	
	var loc = "adamlabsgallery-icon-left-open-1",
		roc = "adamlabsgallery-icon-right-open-1",
		xoc = '<i class="adamlabsgallery-icon-cancel"></i>';

	if (opt.ajaxCloseType=="type1") {
			loc = "adamlabsgallery-icon-left-open-big";
			roc = "adamlabsgallery-icon-right-open-big";
			opt.ajaxCloseTxt = "";
			xoc = "X";
	}

	if (opt.ajaxCloseInner=="true" || opt.ajaxCloseInner==true) acclass=acclass+" adamlabsgallery-acp-inner";

	var conttext = '<div class="adamlabsgallery-ajax-closer-wrapper'+acclass+'">';

	if (opt.ajaxClosePosition=="tr" || opt.ajaxClosePosition=="br") {
		if (opt.ajaxNavButton=="on")
			conttext = conttext + '<div class="adamlabsgallery-ajax-left adamlabsgallery-ajax-navbt"><i class="'+loc+'"></i></div><div class="adamlabsgallery-ajax-right adamlabsgallery-ajax-navbt"><i class="'+roc+'"></i></div>';
		if (opt.ajaxCloseButton=="on")
			conttext = conttext + '<div class="adamlabsgallery-ajax-closer adamlabsgallery-ajax-navbt">'+xoc+opt.ajaxCloseTxt+'</div>';

	} else {
		if (opt.ajaxCloseButton=="on")
			conttext = conttext + '<div class="adamlabsgallery-ajax-closer adamlabsgallery-ajax-navbt">'+xoc+opt.ajaxCloseTxt+'</div>';
		if (opt.ajaxNavButton=="on")
			conttext = conttext + '<div class="adamlabsgallery-ajax-left adamlabsgallery-ajax-navbt"><i class="'+loc+'"></i></div><div class="adamlabsgallery-ajax-right adamlabsgallery-ajax-navbt"><i class="'+roc+'"></i></div>';
	}
	conttext = conttext + "</div>";


	switch (opt.ajaxClosePosition) {
		case "tl":
		case "tr":
		case "t":
			act.prepend(conttext);
		break;
		case "bl":
		case "br":
		case "b":
			act.append(conttext);
		break;
	}

	// CLICK ON CLOSE
	act.find('.adamlabsgallery-ajax-closer').click(function() {
		showHideAjaxContainer(act,false,null,null,0.25,true);
	});

	function arrayClickableItems(arr1) {
		var arr2 = [];
		jQuery.each(arr1,function(index,obj) {
			if (jQuery(obj).closest('.itemtoshow.isvisiblenow').length>0)
			  arr2.push(obj);
		});
		return arr2;
	}

	// CLICK ON NEXT AJAX CONTENT
	act.find('.adamlabsgallery-ajax-right').click(function() {
		var lastli = act.data('container').find('.lastclickedajax').closest('li'),
			nexts = lastli.nextAll().find('.adamlabsgallery-ajax-a-button'),
			prevs = lastli.prevAll().find('.adamlabsgallery-ajax-a-button');

		nexts = arrayClickableItems(nexts);
		prevs = arrayClickableItems(prevs);

		if (nexts.length>0) {
			nexts[0].click();
		} else {
			prevs[0].click();
		}
	});

	// CLICK ON PREV AJAX CONTENT
	act.find('.adamlabsgallery-ajax-left').click(function() {
		var lastli = act.data('container').find('.lastclickedajax').closest('li'),
			nexts = lastli.nextAll().find('.adamlabsgallery-ajax-a-button'),
			prevs = lastli.prevAll().find('.adamlabsgallery-ajax-a-button');

		nexts = arrayClickableItems(nexts);
		prevs = arrayClickableItems(prevs);

		if (prevs.length>0) {
			prevs[prevs.length-1].click();
		} else {
			nexts[nexts.length-1].click();
		}
	});
}

////////////////////////////////
// SHOW / HIDE AJAX CONTAINER //
////////////////////////////////
function showHideAjaxContainer(act,show,scroll,scrolloffset,speed,kill) {

	 		speed = speed==undefined ? 0.25 : speed;


	 		var opt = act.data('container').data('opt'),
	 			hh = act.data('lastheight') != undefined ? act.data('lastheight') : "100px";


			if (!show) {
				//adamlabsgallerygs.TweenLite.to(act,speed,{autoAlpha:0});
				if (kill) {
					killOldCustomAjaxContent(act);
					hh = "0px";
				}


				adamlabsgallerygs.TweenLite.to(act.parent(),speed,{height:hh, ease:adamlabsgallerygs.Power2.easeInOut,
					onStart:function() {
						adamlabsgallerygs.TweenLite.to(act,speed,{autoAlpha:0,ease:adamlabsgallerygs.Power3.easeOut});
					},
					onComplete:function() {
						setTimeout(function() {
							if (kill) act.html("");
						},300);
					}
				});

			} else {

				speed = speed+1.2;
				addAjaxNavigagtion(opt,act);
				adamlabsgallerygs.TweenLite.set(act,{height:"auto"});
				adamlabsgallerygs.TweenLite.set(act.parent(),{minHeight:0,maxHeight:"none",height:"auto",overwrite:"all"});
				adamlabsgallerygs.TweenLite.from(act,speed,{height:hh, ease:adamlabsgallerygs.Power3.easeInOut,
					onStart:function() {

						adamlabsgallerygs.TweenLite.to(act,speed,{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeOut});

					},
					onComplete:function() {
						act.data('lastheight',act.height());
						jQuery(window).trigger("resize.adamlabsgallery" + act.data('container').attr('id'));
						if (act.find('.adamlabsgallery-ajax-closer-wrapper').length==0) addAjaxNavigagtion(opt,act);
					}
				});

				if (opt.ajaxScrollToOnLoad!="off")
					jQuery("html, body").animate({scrollTop:(act.offset().top-scrolloffset)},{queue:false,speed:0.5});
			}
}

////////////////////
// REMOVE LOADER //
////////////////////
function removeLoader(act) {
	act.closest('.adamlabsgallery-ajaxanimwrapper').find('.adamlabsgallery-loader').remove();
}

////////////////////
// AJAX CALL BACK //
////////////////////
function ajaxCallBack(opt,a) {
	if (opt.ajaxCallback==undefined || opt.ajaxCallback=="" || opt.ajaxCallback.length<3)
		return false;

	var splitter = opt.ajaxCallback.split(')');
		splitter = splitter[0].split('(');
	
	var callback = splitter[0],
		args = splitter.length>1 && splitter[1]!="" ? splitter[1]+"," : "",
		obj = {};

	try{
		obj.containerid = "#"+opt.ajaxContentTarget;
		obj.postsource = a.data('ajaxsource');
		obj.posttype = a.data('ajaxtype');
		
		if (opt.ajaxCallbackArgument == "on")
			eval(callback+"("+args+"obj)");
		else
			eval(callback+"("+args+")");
		} catch(e) { console.log("Callback Error"); console.log(e);}
}

///////////////////////
// LOAD MORE CONTENT //
///////////////////////
function loadMoreContent(container,opt,a) {

		//MARK THE LAST CLICKED AJAX ELEMENT
		container.find('.lastclickedajax').removeClass("lastclickedajax");
		a.addClass("lastclickedajax");


		var act = jQuery("#"+opt.ajaxContentTarget).find('.adamlabsgallery-ajax-target').eq(0),
			postid = a.data('ajaxsource'),
			posttype = a.data('ajaxtype'),
			videoaspect = a.data('ajaxvideoaspect');

			act.data('container',container);

		if (videoaspect=="16:9")
			videoaspect ="widevideo";
		else
			videoaspect ="normalvideo";


		showHideAjaxContainer(act,false);

		if (act.length>0) {



			// ADD LOAD MORE TO THE CONTAINER
			//try{
				// PRELOAD AJAX JS FILE IN CASE IT NEEDED
				if (opt.ajaxJsUrl!=undefined && opt.ajaxJsUrl!="" && opt.ajaxJsUrl.length>3)	{
					jQuery.getScript(opt.ajaxJsUrl).done( function(script,textStatus) {
						opt.ajaxJsUrl = "";
					})
					.fail(function(jqxhr,settings,exception) {
						console.log("Loading Error on Ajax jQuery File. Please doublecheck if JS File and Path exist:"+opt.ajaxJSUrl);
						opt.ajaxJsUrl = "";
					});
				}
				// PRELOAD AJAX CSSS FILE IN CASE IT NEEDED
				if (opt.ajaxCssUrl!=undefined && opt.ajaxCssUrl!="" && opt.ajaxCssUrl.length>3)	{
					jQuery("<link>")
						.appendTo('head')
						.attr({type:"text/css", rel:"stylesheet"})
						.attr('href', opt.ajaxCssUrl);

					opt.ajaxCssUrl = "";
				}

				buildLoader(act.closest('.adamlabsgallery-ajaxanimwrapper'),opt);

				if (act.data('ajaxload') != undefined)
					act.data('ajaxload').abort();

				killOldCustomAjaxContent(act);

				switch (posttype) {
					// IF THE CONTENT WE LOAD IS FROM A POST
					case "postid":
						var objData = {
										 action: opt.loadMoreAjaxAction,
									     client_action: 'load_more_content',
									     token: opt.loadMoreAjaxToken,
									     postid:postid
									    };

						setTimeout(function() {

							act.data('ajaxload',jQuery.ajax({
							     type:'post',
							     url:opt.loadMoreAjaxUrl,
							     dataType:'json',
							     data:objData
							    }));
							act.data('ajaxload').success(function(data,status,arg3) {

									if (data.success) {
										jQuery(act).html(data.data);
										showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset);
										removeLoader(act);
										ajaxCallBack(opt,a);

									}
							 });
							 act.data('ajaxload').error(function(arg1, arg2, arg3) {
							 		if (arg2!="abort") {
								    	jQuery(act).append("<p>FAILURE: <strong>"+arg2+"</strong></p>");
										removeLoader(act);
									}
							 });
						},300);
					break;
					// IF THE CONTENER WE LOAD IS A YOUTUBE VIDEO
					case "youtubeid":
						setTimeout(function() {
							if(opt.youtubeNoCookie!="false"){
								act.html('<div class="adamlabsgallery-ajax-video-container '+videoaspect+'"><iframe width="560" height="315" src="//www.youtube-nocookie.com/embed/'+postid+'?autoplay=1&vq=hd1080&fs=1" frameborder="0" allowfullscreen></iframe></div>');
							}
							else{
								act.html('<div class="adamlabsgallery-ajax-video-container '+videoaspect+'"><iframe width="560" height="315" src="//www.youtube.com/embed/'+postid+'?autoplay=1&vq=hd1080&fs=1" frameborder="0" allowfullscreen></iframe></div>');
							}							
						    removeLoader(act);
							showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset);
							ajaxCallBack(opt,a);
						},300);
					break;
					// IF THE CONTENER WE LOAD IS A VIMEO VIDEO
					case "vimeoid":
						setTimeout(function() {

							act.html('<div class="adamlabsgallery-ajax-video-container '+videoaspect+'"><iframe src="https://player.vimeo.com/video/'+postid+'?portrait=0&autoplay=1" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>');
						    removeLoader(act);
							showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset);
							ajaxCallBack(opt,a);
						},300);
					break;
					// IF THE CONTENER WE LOAD IS A Wistia VIDEO
					case "wistiaid":
						setTimeout(function() {
							act.html('<div class="adamlabsgallery-ajax-video-container '+videoaspect+'"><iframe src="//fast.wistia.net/embed/iframe/'+postid+'"allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="640" height="388"></iframe></div>');
						    removeLoader(act);
							showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset);
							ajaxCallBack(opt,a);
						},300);
					break;
					// IF THE CONTENER WE LOAD IS HTML5 VIDEO
					case "html5vid":
						postid=postid.split("|");
						setTimeout(function() {
							var mediaType = postid[0].search('mp4') !== -1 ? 'video/mp4' : 'audio/mpeg';
							act.html('<video autoplay="true" loop="" class="rowbgimage" poster="" width="100%" height="auto"><source src="'+postid[0]+'" type="' + mediaType + '"><source src="'+postid[1]+'" type="video/webm"><source src="'+postid[2]+'" type="video/ogg"></video>');
						    removeLoader(act);
							showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset);
							ajaxCallBack(opt,a);
						},300);
					break;
					// IF THE CONTENER WE LOAD IS SOUNDCLOUD
					case "soundcloud" :
					case "soundcloudid":
						setTimeout(function() {
							act.html('<iframe width="100%" height="250" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'+postid+'&amp;auto_play=true&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>');
						    removeLoader(act);
							showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset);
							ajaxCallBack(opt,a);
						},300);
					break;
					// IF THE CONTENER WE LOAD IS AN IMAGE
					case "imageurl":
						setTimeout(function() {
							var img = new Image();
							img.onload = function () {
	 							 var img = jQuery(this);
	 							 act.html("");
	 							 img.css({width:"100%",height:"auto"});
								 act.append(jQuery(this));
								 removeLoader(act);
								 showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset);
								 ajaxCallBack(opt,a);
							};
							img.onerror = function(e) {
								 act.html("Error");
								 removeLoader(act);
								 showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset);
							};
						    img.src=postid;
						},300);
					break;
					// EXTENDED VARIABLES FOR CONTENT LOADING
					default:
						jQuery.each(jQuery.fn.adamlabsgallery.defaults.ajaxTypes,function(index,obj) {

						if (obj.openAnimationSpeed==undefined) obj.openAnimationSpeed=0;

							if (obj != undefined && obj.type!=undefined) {
								if (obj.type==posttype) {
									setTimeout(function() {
										act.data('lastposttype',posttype);
										act.data('oldajaxsource',postid);
										act.data('oldajaxtype',posttype);
										act.data('oldajaxvideoaspect',videoaspect);
										act.data('oldselector',"#"+opt.ajaxContentTarget+' .adamlabsgallery-ajax-target');
										showHideAjaxContainer(act,true,opt.ajaxScrollToOnLoad,opt.ajaxScrollToOffset,0);
										act.html(obj.func.call(this,{id:postid,type:posttype,aspectratio:videoaspect}));
										removeLoader(act);

									},300);
								}
							}
						});

					break;
				}


			  //} catch(e) {}
		}

}

	//////////////////
	// IS MOBILE ?? //
	//////////////////
	var is_mobile = function() {
	    var agents = ['android', 'webos', 'iphone', 'ipad', 'blackberry','Android', 'webos', 'iPod', 'iPhone', 'iPad', 'Blackberry', 'BlackBerry'];
		var ismobile=false;
	    for(var i in agents) {

		    if (navigator.userAgent.split(agents[i]).length>1) {
	            ismobile = true;

	          }
	    }
	    return ismobile;
	};

/********************************************************************************
	-	PREPARE PRESELECTED FILTERS, PAGINATION AND SEARCH BASED ON COOKIES	-
*********************************************************************************/
function resetFiltersFromCookies(opt,triggerclick,otherids) {
	if (opt.cookies.filter=="on") {
				var selectedFilters = otherids!==undefined ? otherids : readCookie("grid_"+opt.gridID+"_filters");

				if (selectedFilters!==undefined && selectedFilters!==null && selectedFilters.length>0) {
					var foundfilters = 0;
					jQuery.each(selectedFilters.split(","),function(index,filt) {
						if (filt!==undefined && filt!==-1 && filt!=="-1") {
							jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton,'+opt.filterGroupClass+' .adamlabsgallery-filterbutton').each(function() {
								if ((jQuery(this).data('fid') == filt || parseInt(jQuery(this).data('fid'),0)===parseInt(filt,0)) && !jQuery(this).hasClass("adamlabsgallery-pagination-button")) {
									if (triggerclick) 
											jQuery(this).click();
									else
											jQuery(this).addClass("selected");
									foundfilters++;
								}
							});							
						}
					});
					if (foundfilters>0) 
						jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton.adamlabsgallery-allfilter,'+opt.filterGroupClass+' .adamlabsgallery-filterbutton.adamlabsgallery-allfilter').removeClass("selected");
				}
			}
}

function resetPaginationFromCookies(opt,otherids) {
	// HANDLE THE PAGINATION  - WHICH PAGE SHOULD BE SHOWN IF PAGINATION WAS SAVED AS COOKIE
	if (opt.cookies.pagination==="on") {
		var pagec = otherids!==undefined ? otherids : readCookie("grid_"+opt.gridID+"_pagination");		
		if (pagec!==undefined && pagec!==null && pagec.length>0)
		 	jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton.adamlabsgallery-pagination-button,'+opt.filterGroupClass+' .adamlabsgallery-filterbutton.adamlabsgallery-pagination-button').each(function() {
		 		if (parseInt(jQuery(this).data('page'),0) === parseInt(pagec,0) && !jQuery(this).hasClass("selected"))
		 			jQuery(this).click();
		 	});
	}
}

function resetSearchFromCookies(opt) {
	if (opt.cookies.search==="on") {
		var lastsearch = readCookie("grid_"+opt.gridID+"_search");
		if (lastsearch!==undefined && lastsearch!=null && lastsearch.length>0) {							
			 jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').val(lastsearch).trigger("change");
			 opt.cookies.searchjusttriggered = true;
		}
	}
}

function onRightNavClick(container) {
	
	var opt = getOptions(container);
	
	opt.oldpage = opt.currentpage;
	opt.currentpage++;

	if (opt.currentpage>=opt.realmaxpage) opt.currentpage = 0;

	var gbfc = getBestFitColumn(opt,jQuery(window).width(),"id");
	opt.column = gbfc.column;
	opt.columnindex = gbfc.index;
	opt.mmHeight = gbfc.mmHeight;

	setItemsOnPages(opt);
	organiseGrid(opt,"RightNavigation");
	setOptions(container,opt);
	
	stopAllVideos(true);
}

function onLeftNavClick(container) {
	
	var opt = getOptions(container);
	opt.oldpage = opt.currentpage;
	opt.currentpage--;

	if (opt.currentpage<0) opt.currentpage = opt.realmaxpage-1;

	var gbfc = getBestFitColumn(opt,jQuery(window).width(),"id");
	opt.column = gbfc.column;
	opt.columnindex = gbfc.index;
	opt.mmHeight = gbfc.mmHeight;

	setItemsOnPages(opt);
	organiseGrid(opt,"LeftNavigation");
	setOptions(container,opt);

	stopAllVideos(true);
	
}

/*************************************
	-	PREPARING ALL THE GOODIES	-
**************************************/
//! MAIN PREPARING
function mainPreparing(container,opt) {

			/*************************************************************
				-	PREPARE PRESELECTED FILTERS BASED ON COOKIED	-
			**************************************************************/
			resetFiltersFromCookies(opt);

			/*********************************************
				-	BUILD LEFT/RIGHT BIG CONTAINER 	-
			**********************************************/

		/*	container.find('.adamlabsgallery-filters, .navigationbuttons, .adamlabsgallery-pagination').wrapAll('<div class="adamlabsgallery-leftright-container dark"></div>');
			container.find('.adamlabsgallery-filter-clear').remove();
			container.find('.adamlabsgallery-overflowtrick').css({float:"left"});


			if (navcont.length>0) {
						var wcor = navcont.outerWidth(true);
						container.find('.adamlabsgallery-overflowtrick').css({width:container.width() - wcor});
					}
*/
			var navcont = container.find('.adamlabsgallery-leftright-container');
			/*******************************************
				-	PREPARE GRID	-
			*******************************************/

			var gbfc = getBestFitColumn(opt,jQuery(window).width(),"id");
			opt.column = gbfc.column;
			opt.columnindex = gbfc.index;
			opt.mmHeight = gbfc.mmHeight;
			prepareItemsInGrid(opt);
			organiseGrid(opt,"MainPreparing");

			/***********************************
				-	PREPARE SEARCH FUNCTION	-
			***********************************/

			if (jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').length>0) {

				var fgc = opt.filterGroupClass.replace(".",""),
					srch = "Search Result",
					submit = jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-submit'),
					clear = jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-clean');

				jQuery(opt.filterGroupClass+".adamlabsgallery-filter-wrapper.adamlabsgallery-search-wrapper").append('<div style="display:none !important" class="adamlabsgallery-filterbutton hiddensearchfield '+fgc+'" data-filter="cat-searchresult"><span>'+srch+'</span></div>');

				opt.lastsearchtimer = 0;

				function inputsubmited() {
					if (opt.lastsearchtimer == 1) return false;
					opt.lastsearchtimer = 1;

					buildLoader(jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper'),{ spinner:"spinner3", spinnerColor:"#fff"},true);

					adamlabsgallerygs.TweenLite.fromTo(jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader'),0.3,{autoAlpha:0},{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut});

					var allFilter,
						$this = this,
						ifield = jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input'),
						ival = ifield.val(),
						hidsbutton = jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper.adamlabsgallery-filter-wrapper .hiddensearchfield');

					ifield.attr('disabled','true');
					
					if (ival.length>0) {
						ifield.trigger("searchstarting");						
						var searchdata = {search:ival,id:opt.gridID},
							objData = {
						     action: opt.loadMoreAjaxAction,
						     client_action: 'get_grid_search_ids',
						     token: opt.loadMoreAjaxToken,
						     data: searchdata
						    };


						jQuery.ajax({
						     type:'post',
						     url:opt.loadMoreAjaxUrl,
						     dataType:'json',
						     data:objData
						 }).success(function(result,status,arg3) {
						 		
						 		// SAVE THE COOKIE FOR THE CURRENT GRID WITH LAST SEARCH RESULT						 		
						 		if (opt.cookies.search==="on")
						 			createCookie("grid_"+opt.gridID+"_search", ival, opt.cookies.timetosave*(1/60/60));

							 	if (opt.cookies.searchjusttriggered === true) {	
							 		var cpageids = readCookie("grid_"+opt.gridID+"_pagination"),
							 			cfilterids = readCookie("grid_"+opt.gridID+"_filters");
							 		setTimeout(function() {							 			
							 			resetFiltersFromCookies(opt,true,cfilterids);										
							 			resetPaginationFromCookies(opt,cpageids);
									},200);
									opt.cookies.searchjusttriggered = false;
								}
							 	setTimeout(function() {
								 	opt.lastsearchtimer = 0;
								 	jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').attr('disabled',false);
									adamlabsgallerygs.TweenLite.to(jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader'),0.5,{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut,onComplete:function() {
										jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader').remove();
									}});
									jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').trigger("searchended");
								},1000);
							   	var rarray = [];
							   	if (result)
							 		jQuery.each(result,function(index,id){
								 		if (id!=undefined && jQuery.isNumeric(id))
								 			rarray.push(id);
							 		});

						 		//CALL AJAX TO GET ID'S FOR RESULTS
								container.find('.cat-searchresult').removeClass("cat-searchresult");
								var found = 0;

								jQuery.each(opt.loadMoreItems,function(andex,litem) {
									litem[2]="notsearched";
									jQuery.each(rarray,function(bndex,id){
										if (parseInt(litem[0],0) === parseInt(id,0) && parseInt(litem[0],0)!=-1) {
											litem[2]="cat-searchresult";
											found++;
											return false;
										}
									});
								});


								jQuery.each(rarray,function(index,id){
									container.find('.adamlabsgallery-post-id-'+id).addClass("cat-searchresult");
								});
								hidsbutton.addClass("adamlabsgallery-forcefilter").addClass("adamlabsgallery-justfilteredtosearch");
								
								/* 2.1.5 */
								// jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .adamlabsgallery-allfilter').trigger("click");
								allFilter = jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .adamlabsgallery-allfilter');
								if(allFilter.length) allFilter.trigger("click");
								else onFilterClick.call($this);
								
						}).error(function(arg1, arg2, arg3) {
							console.log("FAILURE: "+arg2);
							setTimeout(function() {
								 	opt.lastsearchtimer = 0;
								 	jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').attr('disabled',false);
									adamlabsgallerygs.TweenLite.to(jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader'),0.5,{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut,onComplete:function() {
										jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader').remove();
									}});
									jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').trigger("searchended");
							},1000);
						});


					} else {
						jQuery.each(opt.loadMoreItems,function(andex,litem) {litem[2]="notsearched";});
						container.find('.cat-searchresult').removeClass("cat-searchresult");
						hidsbutton = jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper.adamlabsgallery-filter-wrapper .hiddensearchfield');
						hidsbutton.removeClass("adamlabsgallery-forcefilter").addClass("adamlabsgallery-justfilteredtosearch");
						
						// CLEAR COOKIE, FIELD IS EMPTY
						if (opt.cookies.search==="on")						 			
				    		createCookie("grid_"+opt.gridID+"_search", "", -1);
						
						/* 2.1.5 */
						// jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .adamlabsgallery-allfilter').trigger("click");
						allFilter = jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .adamlabsgallery-allfilter');
						if(allFilter.length) allFilter.trigger("click");
						else onFilterClick.call($this);
						
						setTimeout(function() {
								 	opt.lastsearchtimer = 0;
								 	jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').attr('disabled',false);
									adamlabsgallerygs.TweenLite.to(jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader'),0.5,{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut,onComplete:function() {
										jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader').remove();
									}});
									jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').trigger("searchended");
						},1000);

					}
				}
				
				
				
				submit.click(inputsubmited);
				jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').on("change",inputsubmited);

				clear.click(function() {
					if (opt.cookies.search==="on")						 			
				    	createCookie("grid_"+opt.gridID+"_search", "", -1);
		
					jQuery.each(opt.loadMoreItems,function(andex,litem) {litem[2]="notsearched";});
					container.find('.cat-searchresult').removeClass("cat-searchresult");
					var hidsbutton = jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper.adamlabsgallery-filter-wrapper .hiddensearchfield');
					jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').val("");
					hidsbutton.removeClass("adamlabsgallery-forcefilter").addClass("adamlabsgallery-justfilteredtosearch");
					
					/* 2.1.5 */
					// jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .adamlabsgallery-allfilter').trigger("click");
					var allFilter = jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .adamlabsgallery-allfilter');
					if(allFilter.length) allFilter.trigger("click");
					else onFilterClick.call(this);
					
					setTimeout(function() {
					 	opt.lastsearchtimer = 0;
					 	jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').attr('disabled',false);
						adamlabsgallerygs.TweenLite.to(jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader'),0.5,{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut,onComplete:function() {
							jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper').find('.adamlabsgallery-loader').remove();
						}});
						jQuery(opt.filterGroupClass+'.adamlabsgallery-search-wrapper .adamlabsgallery-search-input').trigger("searchended");
					},1000);

				});

			}

			
			addCountSuffix(container,opt);


			/***************************************
				-	PREPARE DROP DOWN FILTERS	-
			****************************************/
			jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper,'+opt.filterGroupClass+' .adamlabsgallery-filter-wrapper').each(function(i) {
			
				var efw = jQuery(this);

				if (efw.hasClass("dropdownstyle")) {
									efw.find('.adamlabsgallery-filter-checked').each(function() {
						jQuery(this).prependTo(jQuery(this).parent());
					});

					if (!is_mobile()) {
						if (opt.showDropFilter=="click") {
							efw.click(function() {
								var efw = jQuery(this).closest('.adamlabsgallery-filter-wrapper');
								efw.find('.adamlabsgallery-selected-filterbutton').addClass("hoveredfilter");
								efw.find('.adamlabsgallery-dropdown-wrapper').stop().show();
							});
							efw.on("mouseleave",function() {
								var efw = jQuery(this).closest('.adamlabsgallery-filter-wrapper');
								efw.find('.adamlabsgallery-selected-filterbutton').removeClass("hoveredfilter");
								efw.find('.adamlabsgallery-dropdown-wrapper').stop().hide();
	
							});
						} else {
							efw.hover(function() {
								var efw = jQuery(this).closest('.adamlabsgallery-filter-wrapper');
								efw.find('.adamlabsgallery-selected-filterbutton').addClass("hoveredfilter");
								efw.find('.adamlabsgallery-dropdown-wrapper').stop().show();
							},function() {
								var efw = jQuery(this).closest('.adamlabsgallery-filter-wrapper');
								efw.find('.adamlabsgallery-selected-filterbutton').removeClass("hoveredfilter");
								efw.find('.adamlabsgallery-dropdown-wrapper').stop().hide();
	
							});
						}
					} else {
						efw.find('.adamlabsgallery-selected-filterbutton').click(function() {
							var esfb = efw.find('.adamlabsgallery-selected-filterbutton');
							if (esfb.hasClass("hoveredfilter")) {																		
									esfb.removeClass("hoveredfilter");									
									efw.find('.adamlabsgallery-dropdown-wrapper').stop().hide();
								
							} else {		
									esfb.addClass("hoveredfilter");									
									efw.find('.adamlabsgallery-dropdown-wrapper').stop().show();
							}							
						});
					}

					
				}
			});

			if (is_mobile()) {
				jQuery(document).on('click touchstart',function(event) {		
					var p = jQuery(event.target).closest('.adamlabsgallery-filter-wrapper');
					if (p.length==0) {										
						opt.container.find('.hoveredfilter').removeClass("hoveredfilter");					
						opt.container.find('.adamlabsgallery-dropdown-wrapper').stop().hide();
					}					
				});
			}

			opt.container.find('.adamlabsgallery-filters').each(function(i) {
				adamlabsgallerygs.TweenLite.set(this,{zIndex:(70-i)});
			});

			opt.container.find('.adamlabsgallery-filter-wrapper.dropdownstyle').each(function(i) {
				adamlabsgallerygs.TweenLite.set(this,{zIndex:(1570-i)});
			});
			
			var containerIds = container.attr('id');

			/***********************************************
				-	HANDLE OF LEFT NAVIGATION BUTTON	-
			*************************************************/
			// 2.2.5
			jQuery('body').on('click.' + containerIds, '#' + container.attr('id') + ' ' + opt.filterGroupClass+'.adamlabsgallery-left,'+opt.filterGroupClass+' .adamlabsgallery-left', function() {
				
				onLeftNavClick(container);
				
			});
			
			/***********************************************
				-	HANDLE OF RIGHT NAVIGATION BUTTON	-
			***********************************************/
			
			/* 2.1.6.2 */
			// 2.2.5
			jQuery('body').on('click.' + containerIds, '#' + containerIds + ' ' + opt.filterGroupClass+'.adamlabsgallery-right,'+opt.filterGroupClass+' .adamlabsgallery-right', function() {
			
				onRightNavClick(container);
			
			});


			/**************************************
				-	HANDLE OF FILTER BUTTONS	-
			****************************************/
			
			/* 2.1.5 */
			function onFilterClick() {

				var opt = getOptions(container);

				stopAllVideos(true);
				var efb = jQuery(this);

				// TURN OFF ALL SELECTED BUTTON
				if (!efb.hasClass("adamlabsgallery-pagination-button")) {
					jQuery(opt.filterGroupClass+'.adamlabsgallery-allfilter, '+opt.filterGroupClass+' .adamlabsgallery-allfilter').removeClass("selected");
					if (efb.hasClass("adamlabsgallery-allfilter")) {
						jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton, '+opt.filterGroupClass+' .adamlabsgallery-filterbutton').each(function() {
							 jQuery(this).removeClass("selected");
						});
					}
				}

				if (efb.closest('.adamlabsgallery-filters').hasClass("adamlabsgallery-singlefilters") || opt.filterType=="single") {
						jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton, '+opt.filterGroupClass+' .adamlabsgallery-filterbutton').each(function() {
							 jQuery(this).removeClass("selected");
						});
						efb.addClass("selected");
				} else {
					if (efb.hasClass("selected"))
						efb.removeClass("selected");
					else
						efb.addClass("selected");
				}

				var hidsbutton = jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .hiddensearchfield');
				if (hidsbutton.hasClass("adamlabsgallery-forcefilter")) hidsbutton.addClass("selected");

				var countofselected=0,
					filtcookie = "";
				jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton.selected,'+opt.filterGroupClass+' .adamlabsgallery-filterbutton.selected').each(function() {
					if (jQuery(this).hasClass("selected") && !jQuery(this).hasClass("adamlabsgallery-pagination-button")) {
					  countofselected++;							
					  filtcookie = countofselected===0 ? jQuery(this).data('fid') : filtcookie+","+jQuery(this).data('fid');
					 }

				});

				// CREATE A COOKIE FOR THE LAST SELECTION OF FILTERS
				if (opt.cookies.filter==="on" && opt.cookies.searchjusttriggered !== true) 
					createCookie("grid_"+opt.gridID+"_filters",filtcookie,opt.cookies.timetosave*(1/60/60));
				

				if (countofselected==0)
				  jQuery(opt.filterGroupClass+'.adamlabsgallery-allfilter,'+opt.filterGroupClass+' .adamlabsgallery-allfilter').addClass("selected");

				opt.filterchanged = true;
				opt.currentpage=0;

				if (opt.maxpage==1) {
					 jQuery(opt.filterGroupClass+'.navigationbuttons,'+opt.filterGroupClass+' .navigationbuttons').css({display:'none'});
					 jQuery(opt.filterGroupClass+'.adamlabsgallery-pagination,'+opt.filterGroupClass+' .adamlabsgallery-pagination').css({display:'none'});
				} else {
					 jQuery(opt.filterGroupClass+'.navigationbuttons,'+opt.filterGroupClass+' .navigationbuttons').css({display:'inline-block'});
					 jQuery(opt.filterGroupClass+'.adamlabsgallery-pagination,'+opt.filterGroupClass+' .adamlabsgallery-pagination').css({display:'inline-block'});
				}
				
				if (opt.lmbut!=undefined && opt.lmbut.length>0)	{
					var itemtoload = checkMoreToLoad(opt).length;
					if (itemtoload>0) {
						if (opt.loadMoreNr=="off")
							opt.lmbut.html(opt.loadMoreTxt);
						else								
							opt.lmbut.html(opt.loadMoreTxt+" ("+itemtoload+")");
					}
					else
						opt.lmbut.data('loading',0).html(opt.loadMoreEndTxt);

				} 



				setItemsOnPages(opt);
				organiseGrid(opt,"filtergroup");
				setOptions(container,opt);
			}
			
			jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton, '+opt.filterGroupClass+' .adamlabsgallery-filterbutton').each(function() {

				if (!jQuery(this).hasClass("adamlabsgallery-pagination-button"))
					jQuery(this).click(onFilterClick);


			});


			/*****************************************
				-	IN CASE WINDOW IS RESIZED 	-
			******************************************/
			var resizetimer;
			jQuery(window).on("resize.adamlabsgallery" + containerIds,function(e) {
				
					clearTimeout(resizetimer);

					if (opt.forceFullWidth=="on" || opt.forceFullScreen=="on") {
						var offl = container.parent().parent().find('.adamlabsgallery-relative-placeholder').offset().left;
						container.closest('.adamlabsgallery-container-fullscreen-forcer').css({left:(0-offl),width:jQuery(window).width()});
					}
					else
						container.closest('.adamlabsgallery-container-fullscreen-forcer').css({left:0,width:"auto"});

					if (navcont.length>0) {
						var wcor = navcont.outerWidth(true);

						adamlabsgallerygs.TweenLite.set(container.find('.adamlabsgallery-overflowtrick'),{width:container.width() - wcor,overwrite:"all"});
					}


				var gbfc = getBestFitColumn(opt,jQuery(window).width(),"id");
				opt.column = gbfc.column;
				opt.columnindex = gbfc.index;
				opt.mmHeight = gbfc.mmHeight;

				setOptions(container,opt);
				resizetimer = setTimeout(function() {
					
					opt.fromResize = true;
					
					opt = getOptions(container);					
					setItemsOnPages(opt);
					organiseGrid(opt,"resize");
					setOptions(container,opt);
					
					/* 2.1.5 */
					stopAllVideos(true, false, false, true);
					
				},200);

			}).on('resize.adamlabsgallerylb' + containerIds, function() {
				
				var lbVid = jQuery('.adamlabsgallerybox-slide--video .adamlabsgallerybox-iframe');
				if(lbVid.length) {
					
					var ratio = jQuery('body').hasClass('adamlabsgallery-four-by-three'),
						scale = opt.container.data('lightboxsettings').videoScale,
						win = jQuery(window),
						winWide = win.width(),
						winTall = win.height(),
						high,
						wid,
						vw,
						vh;
						
					if(scale) {
						buffer = opt.container.data('lightboxsettings').videoScaleBuffer;
						if(buffer) winTall -= buffer * 2;
					}
						
					if(!ratio) {	
						vw = 1280;
						vh = 720;
					}
					else {
						vw = 800;
						vh = 600;
					}
				
					lbVid.each(function() {

						if(vw < winWide && vh < winTall && !scale) {	
							wid = vw;
							high = vh;
						}
						else {
							wid = winWide / vw;
							high = winTall / vh;
							
							var perc = wid > high ? high : wid;
							wid = vw * perc;
							high = vh * perc;
							
							if(winWide > winTall) {		
								if(high > winTall) {
									high = winTall;
									wid = vw * (high / vh);
								}
							}
							else {
								if(wid > high) {
									if(wid > winWide) {
										wid = winWide;
										high = vh * (wid / vw);
									}
								}
								else {
									if(high > winTall) {
										high = winTall;
										wid = vw * (high / vh);
									}
								}
							}
						}
						jQuery(this).width(wid).height(high);
					});
				}
			});


			/************************************************
				-	Container Height to right position	-
			************************************************/
			
			// 2.2.5
			function onItemPosition() {
				
				var navcont = container.find('.adamlabsgallery-leftright-container'),
					adamlabsgalleryo = container.find('.adamlabsgallery-overflowtrick').first(),
					positionDif,
					ul;
				
				clearTimeout(container.data('callednow'));

				if (opt.maxheight>0 && opt.maxheight<9999999999) {
					opt.inanimation = false;
					ul = opt.mainul;
						
					navcont = container.find('.adamlabsgallery-leftright-container');

					var padtop = parseInt(adamlabsgalleryo.css('paddingTop'),0);
					padtop = padtop == undefined ? 0 : padtop;
					padtop = padtop == null ? 0 : padtop;
					var padbot = parseInt(adamlabsgalleryo.css('paddingBottom'),0);
					padbot = padbot == undefined ? 0 : padbot;
					padbot = padbot == null ? 0 : padbot;

					var newheight = opt.maxheight+opt.overflowoffset+padtop+padbot;

					if (opt.forceFullScreen=="on") {
						var coh = jQuery(window).height();
						if (opt.fullScreenOffsetContainer!=undefined) {
							try{
								var offcontainers = opt.fullScreenOffsetContainer.split(",");
								jQuery.each(offcontainers,function(index,searchedcont) {
									coh = coh - jQuery(searchedcont).outerHeight(true);

									if (coh<opt.minFullScreenHeight) coh=opt.minFullScreenHeight;
								});
							} catch(e) {}
						}

						newheight =coh;
					}
					
					// 2.2.5
					var heightspeed = opt.firstLoadFinnished ? opt.animSpeed : opt.startAnimationSpeed * 0.001;
					
					/*
					var heightspeed = 0.3;
					
					if (ul.height()<200)
						heightspeed = 1;
					*/
					
					ul.data('hhh', newheight);
					adamlabsgalleryo.data('hhh', newheight);
					
					adamlabsgallerygs.TweenLite.to(ul,heightspeed,{force3D:"auto",height:newheight,ease:adamlabsgallerygs.Power3.easeInOut,clearProps:"transform"});
					adamlabsgallerygs.TweenLite.to(adamlabsgalleryo,heightspeed,{force3D:true,height:newheight,ease:adamlabsgallerygs.Power3.easeInOut,clearProps:"transform",onComplete:function() {
						container.closest('.adamlabsgallery-grid-wrapper, .myportfolio-container').css({height:"auto"}).removeClass("adamlabsgallery-startheight").addClass('adamlabsgallery-revealed');
						opt.releaseHovers = true;
					}});

					if (navcont.length>0)
						adamlabsgallerygs.TweenLite.to(navcont,heightspeed,{minHeight:newheight,ease:adamlabsgallerygs.Power3.easeInOut});

						var ensl = jQuery(opt.filterGroupClass+'.adamlabsgallery-navbutton-solo-left,'+opt.filterGroupClass+' .adamlabsgallery-navbutton-solo-left');
						var ensr = jQuery(opt.filterGroupClass+'.adamlabsgallery-navbutton-solo-right,'+opt.filterGroupClass+' .adamlabsgallery-navbutton-solo-right');

						if (ensl.length>0)
							ensl.css({marginTop:(0-ensl.height()/2)});


						if (ensr.length>0)
							ensr.css({marginTop:(0-ensr.height()/2)});

				} else {
					if (opt.maxheight==0) {
						ul = container.find('ul').first();
						adamlabsgallerygs.TweenLite.to(ul,1,{force3D:"auto",height:0,ease:adamlabsgallerygs.Power3.easeInOut,clearProps:"transform"});
						adamlabsgallerygs.TweenLite.to(adamlabsgalleryo,1,{force3D:true,height:0,ease:adamlabsgallerygs.Power3.easeInOut,clearProps:"transform"});
					}
				}
				
				container.data('callednow',setTimeout(function() {
					container.find('.itemtoshow.isvisiblenow').each(function() {
						hideUnderElems(jQuery(this));
					});
				},250));

				// IF WE ARE IN THE FIRST LOAD AND ACTIVATE PROCESS
				if (opt.firstLoadFinnished===undefined) {
					container.trigger("adamlabsgallery_ready_to_use");
					
					// HANDLE THE COOKIES WHICH NEED TO BE HANDLED AFTER FIRST LOAD
					resetSearchFromCookies(opt);

					// HANDLE THE PAGINATION  - WHICH PAGE SHOULD BE SHOWN IF PAGINATION WAS SAVED AS COOKIE
					resetPaginationFromCookies(opt);

					opt.firstLoadFinnished = true;
				}
							
			}
				
			container.on('itemsinposition',function(e, complete) {
				
				// 2.2.5
				/*
				var container = jQuery(this),
					opt = getOptions(container);				
				*/
				
				isComplete = complete;
				clearTimeout(opt.iteminspositiontimer);
				opt.iteminspositiontimer = setTimeout(onItemPosition, 50);

			});
			
			prepareSortingAndOrders(container);
			prepareSortingClicks(container);
			
			// 2.2.5
			var convertNav = opt.convertFilterMobile && 'ontouchend' in document;
			if(convertNav) {
				
				prepareMobileDropdowns(container);
				
			}
			else {
				
				container.find('.adamlabsgallery-filter-wrapper').css('visibility', 'visible');
				container.find('.adamlabsgallery-selected-filterbutton').show();
				
			}
			
			// 2.2.6
			var id = container.attr('id');
			if(id.search('adamlabsgallery-grid-') !== -1) {
			
				id = id.split('adamlabsgallery-grid-')[1];
				if(id.search('-') === -1) return;
				
				id = id.split('-')[0];
				var shortcodes = jQuery('.adamlabsgallery-filter-wrapper.adamlabsgallery-fgc-' + id);
				if(!shortcodes.length) return;
				
				if(convertNav) {
					
					prepareMobileDropdowns(shortcodes, true);
					
				}
				else {

					shortcodes.css('visibility', 'visible');
					shortcodes.find('.adamlabsgallery-selected-filterbutton').show();
					
				}
			}

}

// 2.2.5
function prepareMobileDropdowns(container, shortcode) {
	
	var selct;
	function buildMobileDrop() {
			
		var $this = jQuery(this).hide();
		selct += '<option value="' + $this.attr('data-filter') + '">' + $this.children('span').not('.adamlabsgallery-filter-checked').eq(0).text() + '</option>';
		
	}
	
	container.find('.adamlabsgallery-mobile-filter-button').addClass('adamlabsgallery-selected-filterbutton').show();
	
	if(!shortcode) container = container.find('.adamlabsgallery-filter-wrapper');
	container.addClass('adamlabsgallery-mobile-filter-wrap').each(function() {
		
		var $this = jQuery(this).css('position', 'relative');
		selct = '<select class="adamlabsgallery-sorting-select">';
		$this.find('.adamlabsgallery-filterbutton').each(buildMobileDrop);
		selct += '</select>';
		
		jQuery(selct).on('change', function() {
			
			$this.find('.adamlabsgallery-filterbutton[data-filter="' + this.value + '"]').click();
			$this.find('.adamlabsgallery-selected-filterbutton span').eq(0).text(jQuery(this.options[this.selectedIndex]).text());
			
		}).appendTo($this);
		
	});
	
}

/**********************************************
	-	PREPARE SORTING AND ORDERS 	-
**********************************************/

function prepareSortingAndOrders(container) {

			var opt = getOptions(container);

			/************************************************
				-	HANDLING OF SORTING ISSUES   -
			*************************************************/

			// PREPARE THE DATE SRINGS AND MAKE A TIMESTAMP OF IT
			container.find('.adamlabsgallery-item').each(function() {
				var dd = new Date(jQuery(this).data('date'));
				jQuery(this).data('date',dd.getTime()/1000);
			});

			jQuery(opt.filterGroupClass+'.adamlabsgallery-sortbutton-order,'+opt.filterGroupClass+' .adamlabsgallery-sortbutton-order').each(function() {
				var eso = jQuery(this);
				eso.removeClass("adamlabs-desc").addClass("adamlabs-asc");
				eso.data('dir',"asc");
			});
	}

function prepareSortingClicks(container) {

			opt = getOptions(container);
			var resizetimer;

			jQuery(opt.filterGroupClass+'.adamlabsgallery-sortbutton-wrapper .adamlabsgallery-sortbutton-order,'+opt.filterGroupClass+' .adamlabsgallery-sortbutton-wrapper .adamlabsgallery-sortbutton-order').click(function() {
				var eso = jQuery(this);
				if (eso.hasClass("adamlabs-desc")) {
					eso.removeClass("adamlabs-desc").addClass("adamlabs-asc");
					eso.data('dir',"asc");
				} else {
					eso.removeClass("adamlabs-asc").addClass("adamlabs-desc");
					eso.data('dir',"desc");
				}

				var dir = eso.data('dir');
				stopAllVideos(true,true);
				jQuery(opt.filterGroupClass+'.adamlabsgallery-sorting-select,'+opt.filterGroupClass+' .adamlabsgallery-sorting-select').each(function() {

					var sorter = jQuery(this).val();
					clearTimeout(resizetimer);
					container.find('.adamlabsgallery-item').tsort({data:sorter,forceStrings:"false",order:dir});
					resizetimer = setTimeout(function() {
						opt = getOptions(container);
						setItemsOnPages(opt);
						organiseGrid(opt,"preparSorting");
						setOptions(container,opt);
					},200);

				});

			});

			// SORTING FUNCTIONS
			jQuery(opt.filterGroupClass+'.adamlabsgallery-sorting-select,'+opt.filterGroupClass+' .adamlabsgallery-sorting-select').each(function() {
				var sel = jQuery(this);

				sel.change(function() {
					//container.find('iframe').css({visibility:'hidden'});
					//container.find('.video-eg').css({visibility:'hidden'});

					var eso = jQuery(this).closest('.adamlabsgallery-sortbutton-wrapper').find('.adamlabsgallery-sortbutton-order');

					var sorter = sel.val();
					var sortername = sel.find('option:selected').text();
					var dir = eso.data('dir');

					stopAllVideos(true,true);
					clearTimeout(resizetimer);
					sel.parent().parent().find('.sortby_data').text(sortername);
					var sorted = container.find('.adamlabsgallery-item').tsort({data:sorter,forceStrings:"false",order:dir});
					if (sorted!==undefined) {
					
						opt = getOptions(container);
						setItemsOnPages(opt);
						organiseGrid(opt,"OnSorting");
						setOptions(container,opt);
					}
				});
			});


}


function fixCenteredCoverElement(item,ecc,media) {

		  if (ecc==undefined) ecc = item.find('.adamlabsgallery-entry-cover');
		  if (media==undefined)  media = item.find('.adamlabsgallery-entry-media');
		  if (ecc && media) {
			  var mh = media.outerHeight();			  
			  adamlabsgallerygs.TweenLite.set(ecc,{height:mh});
			  var cc = item.find('.adamlabsgallery-cc');
			  adamlabsgallerygs.TweenLite.set(cc,{top:((mh - cc.height()) / 2 )});
		 }

}




/********************************************
	-	GET BEST FITTING COLUMN AMOUNT 	-
********************************************/
function getBestFitColumn(opt,winw,resultoption) {
	var lasttop = winw,
		lastbottom = 0,
		smallest =9999,
		largest = 0,
		samount = opt.column,
		/* lamoung = opt.column, */
		lastamount = opt.column,
		resultid = 0,
		resultidb = 0;

	if (opt.responsiveEntries!=undefined && opt.responsiveEntries.length>0)
		jQuery.each(opt.responsiveEntries, function(index,obj) {
			var curw = obj.width != undefined ? obj.width : 0,
				cura = obj.amount != undefined ? obj.amount : 0;

			if (smallest>curw) {
				smallest = curw;
				samount = cura;
				resultidb = index;

			}
			if (largest<curw) {
				largest = curw;
				lamount = cura;
			}

			if (curw>lastbottom && curw<=lasttop) {
					lastbottom = curw;
					lastamount = cura;
					resultid = index;
			}
		});

	if (smallest>winw) {
		lastamount = samount;
		resultid = resultidb;
	}

	var obj = {};
	obj.index = resultid;
	obj.column = lastamount;
	obj.mmHeight = opt.responsiveEntries[obj.index].mmheight;	
	
	// 2.2.6 
	var blankItems = jQuery('.adamlabsgalleryblankskin-wrapper'),
		hideBlankAt = opt.hideBlankItemsAt;	
	
	if(blankItems.length && hideBlankAt !== 'none') {
		
		var method = resultid >= parseInt(hideBlankAt, 10) ? 'addClass' : 'removeClass';
		blankItems[method]('skipblank');
	}
	
	if (resultoption=="id")
		return obj;
	else
		return lastamount;
}



/******************************
	-	Get Options	-
********************************/
 function getOptions(container){
 	return container.data('opt');
 }

/******************************
	-	Set Options	-
********************************/
 function setOptions(container,opt){
 	container.data('opt',opt);
 }


/******************************
	-	CHECK MEDIA LISTENERS	-
********************************/
function checkMediaListeners(item) {
	// MAKE SURE THAT YOUTUBE OR VIMEO PLAYER HAS LISTENER
	item.find('iframe').each(function(i) {
		var ifr = jQuery(this);
		if (ifr.attr('src').toLowerCase().indexOf('youtube')>0) prepareYT(ifr);
		else
		if (ifr.attr('src').toLowerCase().indexOf('vimeo')>0) prepareVimeo(ifr);
		else
		if (ifr.attr('src').toLowerCase().indexOf('wistia')>0) prepareWs(ifr);
		else
		if (ifr.attr('src').toLowerCase().indexOf('soundcloud')>0) prepareSoundCloud(ifr);
	 });

	 //  VIDEO PLAYER HAS LISTENER ?
     item.find('video').each(function(i) {
		prepareVideo(jQuery(this));
 	 });

}


/******************************
	-	CHECK MEDIA LISTENERS	-
********************************/
function waitMediaListeners(item, container, opt) {
	 var ifr =  item.find('iframe').first(),
	 	 vid = item.find('video').first(),
	 	 vt = ifr.length>0 && ifr.attr('src').toLowerCase().indexOf('youtube')>0 ? "y" :
	 	 	  ifr.length>0 && ifr.attr('src').toLowerCase().indexOf('vimeo')>0 ? "v" :
	 	 	  ifr.length>0 &&  ifr.attr('src').toLowerCase().indexOf('wistia')>0 ? "w" :
	 	 	  ifr.length>0 && ifr.attr('src').toLowerCase().indexOf('soundcloud')>0 ? "s" :
	 	 	  vid.length>0 && vid.length>=1 ? "h" : "";

	 var intr = setInterval(function() {
		// MAKE SURE THAT YOUTUBE OR VIMEO PLAYER HAS LISTENER
		item.find('iframe, video').each(function(i) {	
			/* 2.1.5 */
			if (vt==="" || (vt==="y" && prepareYT(ifr)) || (vt==="v" && prepareVimeo(ifr)) || (vt==="w" && prepareWs(ifr)) || (vt==="s" && prepareSoundCloud(ifr)) || (vt==="h" && prepareVideo(vid))) {
				
				clearInterval(intr);
				
				// 2.2.6
				if(item.data('simplevideo') === 1) videoClickEvent(item, container, opt, true);
				
			}
		 });
	 },50);

}


/******************************
	-	DIRECTION CALCULATOR	-
********************************/
function directionPrepare(direction,effect,ww,hh,correction) {

		var xy = {};
		switch( direction ) {
			case 0:
				// from top
				xy.x = 0;
				xy.y = effect=="in" ? (0 - hh): (10+hh);
				xy.y = correction  && effect=="in" ? xy.y -5 : xy.y;
				break;
			case 1:
				// from right
				xy.y = 0;
				xy.x = effect=="in" ? ww : -10-ww;
				xy.x = correction  && effect=="in" ? xy.x + 5 : xy.x;
				break;
			case 2:
				// from bottom
				xy.y = effect=="in" ? hh : (-10-hh);
				xy.x = 0;
				xy.y = correction  && effect=="in" ? xy.y  + 5 : xy.y;
				break;
			case 3:
				// from left
				xy.y = 0;
				xy.x = effect=="in" ? (0-ww) : (10+ww) ;
				xy.x = correction  && effect=="in" ? xy.x - 5 : xy.x;
				break;
		}
		return xy;
}

/********************************************
	-	GET THE MOUSE MOVE DIRECTION	-
********************************************/

function getDir( item, coordinates ) {

			// the width and height of the current div
			var w = item.width(),
				h = item.height(),
				x = ( coordinates.x - item.offset().left - ( w/2 )) * ( w > h ? ( h/w ) : 1 ),
				y = ( coordinates.y - item.offset().top  - ( h/2 )) * ( h > w ? ( w/h ) : 1 ),
				direction = Math.round( ( ( ( Math.atan2(y, x) * (180 / Math.PI) ) + 180 ) / 90 ) + 3 ) % 4;
				return direction;
			}


function hideUnderElems(item) {
	item.find('.adamlabsgallery-handlehideunder').each(function() {
		var elem = jQuery(this);
		var hideunder = elem.data('hideunder'),
			hideunderheight = elem.data('hideunderheight'),
			hidetype = elem.data('hidetype');
		if (elem.data('knowndisplay')==undefined)  elem.data('knowndisplay',elem.css("display"));


		if ((item.width()<hideunder && hideunder!=undefined) ||  (item.height()<hideunderheight && hideunderheight!=undefined)) {
		    if (hidetype == "visibility")
		    	elem.addClass("forcenotvisible");
		     else

			 if (hidetype == "display")
		   		elem.addClass("forcenotdisplay");

		} else {
		     if (hidetype == "visibility")
		    	elem.removeClass("forcenotvisible");
		      else

			  if (hidetype == "display")
		   		elem.removeClass("forcenotdisplay");
		}
	 });

}


/**********************************************
	-	Even Grid with MasonrySkin Pusher	-
***********************************************/

function offsetParrents(off,item) {
	
	var ul = item.closest('.mainul'),
		ot = ul.parent(),
		uldh = ul.data('hhh');
	
	if(!uldh || !ot.data('hhh')) return;
	
	if ((item.position().top + item.height()>uldh+40) || off==0 || (ul.data('bh')!=0 && ul.data('bh')!=undefined && item.position().top + item.height()>parseInt(ul.data('bh'),0)+40)) {

		if (ul.data('bh') == undefined || ul.data('bh') == 0) ul.data('bh',ul.data('hhh'));
		if (ot.data('bh') == undefined || ot.data('bh') == 0) ot.data('bh',ot.data('hhh'));

		var ulb = ul.data('bh'),
			otb = ot.data('bh');
			
		// 2.1.5
		var grid = ot.closest('.adamlabsgallery-grid'),
			navLeft = grid.find('.adamlabsgallery-navbutton-solo-left'),
			navRight = grid.find('.adamlabsgallery-navbutton-solo-right');
			
		if(!navLeft.length) navLeft = false;
		if(!navRight.length) navRight = false;
		
		if(!ot.data('fheightcalc')) {
			
			var filterHeight = 0,
				otIndex = ot.index(),
				fHeight = grid.find('.adamlabsgallery-filters').each(function() {
				
				var $this = jQuery(this);
				if($this.css('position') === 'relative' && $this.index() > otIndex && this.className.search(/solo-left|solo-right/) === -1) {
					filterHeight += $this.outerHeight(true);
				}
			
			});
			
			var filterMargin = filterHeight ? (parseInt(grid.css('padding-top'), 10) + parseInt(grid.css('padding-bottom'), 10)) / 2 : 0;
			ot.data({fheightcalc: filterHeight || true, fmargincalc: filterMargin, fstartval: Math.ceil(filterHeight / 2) + Math.ceil(filterMargin / 2)});
			
		}

		if (off!=0) {
			
			clearTimeout(ul.data('offtimer'));
			if(!ot.data('navarrowtrick')) {
				
				if(navLeft) navLeft.appendTo(grid);
				if(navRight) navRight.appendTo(grid);
				ot.data('navarrowtrick', true);
				
			}
			
			ul.data('alreadyinoff',false);
			adamlabsgallerygs.TweenLite.to(ul,0.2,{height:ulb + off});
			adamlabsgallerygs.TweenLite.to(ot,0.2,{height:otb + off});
			
			var fHeight = ot.data('fheightcalc'),
				fMargin = ot.data('fmargincalc') || 0;
				
			if(fHeight === true) fHeight = 0;
			if(navLeft || navRight) adamlabsgallerygs.TweenLite.set([navLeft, navRight], {top: (otb / 2) + (fHeight / 2), y: fMargin});
			
		} else {
			
			if (!ul.data('alreadyinoff')) {
				ul.data('offtimer', setTimeout(function() {
					
					ul.data('alreadyinoff',true);
					adamlabsgallerygs.TweenLite.to(ul,0.3,{height:ulb,ease:adamlabsgallerygs.Power3.easeIn});
					adamlabsgallerygs.TweenLite.to(ot,0.3,{height:otb,ease:adamlabsgallerygs.Power3.easeIn,onComplete:function() {
						ul.data('bh',0);
						ot.data('bh',0);
						ul.data('alreadyinoff',false);
						if(navLeft) navLeft.appendTo(ot);
						if(navRight) navRight.appendTo(ot);
						
						var obj = {top: '50%'};
							fMar = ot.data('fstartval');
							
						if(fMar) obj.y = fMar;
						if(navLeft || navRight) adamlabsgallerygs.TweenLite.set([navLeft, navRight], obj);
						ot.removeData('navarrowtrick');
					}});
					
				}, 100));
			}
		}
	}
}

 /**************************************
 	-	//! ITEM HOVER ANIMATION	-
 **************************************/

 function itemHoverAnim(item,art,opt,direction) {

	  	 if(item.data('simplevideo') != 1) checkMediaListeners(item);
		 if(item.hasClass('adamlabsgallery-video-active')) return;
	  	 
		 /* 2.1.5 */
	  	 // if (item.find('.isplaying, .isinpause').length>0) return false;
	  	 
		 // 2.2.5
  		 // clearTimeout(item.data('hovertimer'));
		 // if (art=="set") curdelays=0;
  		 //item.data('hovertimer',setTimeout(function() {

	  		 	 item.data('animstarted',1);

	  		 	 adamlabsgallerygs.TweenLite.set(item,{z:0.01,x:0,y:0,rotationX:0,rotationY:0,rotationZ:0});
			 	 // ADD A CLASS FOR ANY FURTHER DEVELOPEMENTS
				 item.addClass("adamlabsgallery-hovered");
				 var ecc = item.find('.adamlabsgallery-entry-cover');
				 adamlabsgallerygs.TweenLite.set(ecc,{transformStyle:"flat"});
				 /* if (art!="set") */ fixCenteredCoverElement(item,ecc);

				 //if (!ecc.hasClass("adamlabsgallery-visible-cover")) adamlabsgallerygs.TweenLite.fromTo(ecc,0.2,{autoAlpha:0},{force3D:"auto",autoAlpha:1,overwrite:"auto"});

				 if (item.find('.adamlabsgallery-entry-content').length>0 && /* art!="set" && */ opt.layout=="even") {
				 	var pt = item.data('pt'), pb = item.data('pb'), pl = item.data('pl'), pr = item.data('pr'),
				 		bt = item.data('bt'), bb = item.data('bb'), bl = item.data('bl'), br = item.data('br');

				 	item.data('hhh',item.outerHeight());
				 	item.data('www',item.outerWidth());

					adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-content'),{display:"block"});
					
					/* 2.1.6 */
					if(item.hasClass('adamlabsgallery-split-content')) {
						adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-media-wrapper'),{height:item.data('hhh')});
					}
					
					adamlabsgallerygs.TweenLite.set(item,{z:0.1,zIndex:50,x:0-(pl+pr+br+bl)/2, y:0-(pt+pb+bt+bb)/2,height:"auto",width:item.data('www')+pl+pr+bl+br});
					
					// 2.2.5
					if (opt.inanimation != true && opt.releaseHovers) adamlabsgallerygs.TweenLite.set(item.closest('.adamlabsgallery-overflowtrick'),{overflow:"visible"});
					
					if (opt.evenGridMasonrySkinPusher=="on") {
						var hdifference = item.height() - item.data('hhh');
						offsetParrents(hdifference,item);
					}

				 	 // SPECIAL FUN FOR OVERLAPPING CONTAINER, SHOWING MASONRY IN EVEN GRID !!!
				 	 item.css({	paddingTop:pt+"px",
				 	 			paddingLeft:pl+"px",
				 	 			paddingRight:pr+"px",
				 	 			paddingBottom:pr+"px"
				 	 		});
				 	 item.css({borderTopWidth:bt+"px",borderBottomWidth:bb+"px",borderLeftWidth:bl+"px",borderRightWidth:br+"px"});

				 }
					
				// 2.2.5
				 // jQuery.each(miGalleryAnimmatrix,function(index,key) {
					 
					 item.find('.adamlabsgallery-transition').each(function() {
							
							var elem = jQuery(this),
								trans = elem.attr('data-transition'),
								duration = elem.attr('data-duration');
							
							if(!miGalleryAnimmatrix.hasOwnProperty(trans)) return;
							var key = miGalleryAnimmatrix[trans];
							duration = !duration || duration === 'default' ? key[0] : parseInt(duration, 10) * 0.001;
						 
						 	 var dd = elem.data('delay')!=undefined ? elem.data('delay') : 0;
							  	 animfrom = jQuery.extend({}, key[1]);
							  	 animto = jQuery.extend({}, key[2]);

	  						  // SET ANIMATE POSITIONS
	  						  animto.delay=dd;
	  						  animto.overwrite="all";
	  						  animfrom.overwrite="all";
	  						  animto.transformStyle="flat";
	  						  animto.force3D=true;
	  						  var elemdelay = 0;

	  						  // IF IT IS NOT MEDIA, WE CAN REMOVE ALL TRANSFORMS
	  						  var isOut = trans.indexOf('out') > -1;
	  						  if (!elem.hasClass("adamlabsgallery-entry-media") && !isOut)
	  						  	animto.clearProps="transform";

	  						  if (isOut) animfrom.clearProps = "transform";
	  						  
		  					  animto.z=0.001;

		  					  // SET PERSPECTIVE IF IT IS STILL UNDEFINED
		  					  if (animfrom.transformPerspective ==undefined)
			  						  animfrom.transformPerspective = 1000;

			  				  // IF IT IS AN OVERLAY, WE NEED TO SET Z POSITION EXTREM
			  				  if (elem.hasClass("adamlabsgallery-overlay")) {
				  				  if (animfrom.z == undefined) animfrom.z = -0.002;
				  				  animto.z = -0.0001;
			  				  }

	  						  var animobject = elem;
	  						  // var splitted = false;

	  						  // ID MEDIA EXIST AND VIDEO EXIST, NO HOVER NEEDED
	  						  if (elem.hasClass("adamlabsgallery-entry-media") && elem.find('.adamlabsgallery-media-video').length>0)
	  						    return true;

	  						  // ANIMATE BREAK DOWN
	  						 adamlabsgallerygs.TweenLite.killTweensOf(animobject,false);
							 var xy,
								 af,
								 at,
								 tw;								 

							  // 2.2.5
							  /*
							  // IF IT IS ONLY START, WE NEED TO SET INSTEAD OF ANIMATE
	  						  if (art=="set" ) {	  						  		
		  							tw = adamlabsgallerygs.TweenLite.set(animobject,animfrom);
		  							//adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-cover'),{visibility:"visible"});
		  							if (isOut) {			  						  	
		  						  		tw.eventCallback("onComplete",resetTransforms,[animobject]);			  						
			  						}
	  						  } else
						      */
  						  		switch (trans) {
	  						  		case "adamlabsgallery-shifttotop":
	  						  				animto.y =  0 - item.find('.adamlabsgallery-bc.eec').last().height();
	  						  				adamlabsgallerygs.TweenLite.fromTo(elem,0.5,{y:0},{y:animto.y});
	  						  		break;
	  						  		case "adamlabsgallery-slide":
	  						  				xy =  directionPrepare(direction,"in",item.width(),item.height());
											af = {};
											at = {};
											jQuery.extend(af,animfrom);
											jQuery.extend(at,animto);
											
	  						  				// af.css.x = xy.x;
	  						  				// af.css.y = xy.y;
											
											af.x = xy.x;
	  						  				af.y = xy.y;
											
											adamlabsgallerygs.TweenLite.fromTo(animobject,duration,af,at,elemdelay);
	  						  		break;
	  						  		case "adamlabsgallery-slideout":
	  						  				xy =  directionPrepare(direction,"out",item.width(),item.height());
											af = {};
											at = {};
											jQuery.extend(af,animfrom);
											jQuery.extend(at,animto);
	  						  				at.x = xy.x;
	  						  				at.y = xy.y;
	  						  				at.clearProps="";
											adamlabsgallerygs.TweenLite.fromTo(animobject,duration,af,at,elemdelay);
	  						  		break;
									
									case 'adamlabsgallery-blur':
									case 'adamlabsgallery-fadeblur':
									case 'adamlabsgallery-zoomblur':
									case 'adamlabsgallery-zoomdefaultblur':
										
										var blur,
											amount = parseInt(this.dataset.bluramount, 10);
											
										at = {onUpdate: function() {
											
											blur = tw.progress() * amount;
											animobject.css('filter', 'blur(' + blur + 'px)');
											
										}};
										jQuery.extend(at,animto);
										tw = adamlabsgallerygs.TweenLite.fromTo(animobject,duration,animfrom,at,elemdelay);
										
									break;
									
									case 'adamlabsgallery-grayscalein':
									case 'adamlabsgallery-grayscaleout':
										
										var grayscale,
											animein = trans.search('in') !== -1;
											
										at = {onUpdate: function() {
											
											grayscale = animein ? tw.progress() * 100 : (1 - tw.progress()) * 100;
											animobject.css('filter', 'grayscale(' + grayscale + '%)');
											
										}};
										jQuery.extend(at,animto);
										tw = adamlabsgallerygs.TweenLite.fromTo(animobject,duration,animfrom,at,elemdelay);
										
									break;

	  						  		default:
									
	  						  				adamlabsgallerygs.TweenLite.fromTo(animobject,duration,animfrom,animto,elemdelay);
											
	  						  		break;
  						  		}
					 });
				 // });
		//},curdelays));

}


/*********************************
	-	VIDEO HAS BEEN CLICKED	-
********************************/

function videoClickEvent(item,container,opt,simpleframe) {

	 supressFocus = true;
	
	 item.css({transform:"none",'-moz-transform':'none','-webkit-transform':'none'});
	 item.closest('.adamlabsgallery-overflowtrick').css({transform:"none",'-moz-transform':'none','-webkit-transform':'none'});
	 item.closest('ul').css({transform:"none",'-moz-transform':'none','-webkit-transform':'none'});
	 item.addClass('adamlabsgallery-video-active');
	
	 // PREPARE THE CONTAINERS FOR MEDIAS
	 if (!simpleframe)
		 item.find('.adamlabsgallery-media-video').each(function() {
		   var prep = jQuery(this),
		   	   media= item.find('.adamlabsgallery-entry-media');
		   if (prep.data('youtube')!=undefined && item.find('.adamlabsgallery-youtube-frame').length==0) {
			  if(opt.youtubeNoCookie!="false"){
		  	  	var ytframe = "https://www.youtube-nocookie.com/embed/"+prep.data('youtube')+"?version=3&enablejsapi=1&html5=1&controls=1&autohide=1&rel=0&showinfo=0&fs=1";
			  }
			  else {
			  	var ytframe = "https://www.youtube.com/embed/"+prep.data('youtube')+"?version=3&enablejsapi=1&html5=1&controls=1&autohide=1&rel=0&showinfo=0&fs=1";	
			  }
			  media.append('<iframe class="adamlabsgallery-youtube-frame" wmode="Opaque" style="position:absolute;top:0px;left:0px;display:none" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" data-src="'+ytframe+'" src="about:blank" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
		   }

		   if (prep.data('vimeo')!=undefined && item.find('.adamlabsgallery-vimeo-frame').length==0) {

		  	  var vimframe = "https://player.vimeo.com/video/"+prep.data('vimeo')+"?title=0&byline=0&html5=1&portrait=0";
			  media.append('<iframe class="adamlabsgallery-vimeo-frame"  allowfullscreen="false" style="position:absolute;top:0px;left:0px;display:none" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" data-src="'+vimframe+'" src="about:blank"></iframe>');
		   }
			if (prep.data('wistia')!=undefined && item.find('.adamlabsgallery-wistia-frame').length==0) {
			  
		  	  var wsframe = "https://fast.wistia.net/embed/iframe/"+prep.data('wistia')+"?version=3&enablejsapi=1&html5=1&controls=1&autohide=1&rel=0&showinfo=0";
			  media.append('<iframe class="adamlabsgallery-wistia-frame" wmode="Opaque" style="position:absolute;top:0px;left:0px;display:none" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" data-src="'+wsframe+'" src="about:blank"></iframe>');
		   }
		   if (prep.data('soundcloud')!=undefined && item.find('.adamlabsgallery-soundcloud-frame').length==0) {
			  
			   var scframe = 'https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/'+prep.data('soundcloud')+'&amp;auto_play=false&amp;hide_related=false&amp;visual=true&amp;show_artwork=true';
			   media.append('<iframe class="adamlabsgallery-soundcloud-frame" allowfullscreen="false" style="position:absolute;top:0px;left:0px;display:none" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" scrolling="no" frameborder="no" data-src="'+scframe+'" src="about:blank"></iframe>');
		   }

		   if ((prep.data('mp4')!=undefined || prep.data('webm')!=undefined || prep.data('ogv')!=undefined) && item.find('.adamlabsgallery-video-frame').length==0 ) {

	           media.append('<video class="adamlabsgallery-video-frame" style="position:absolute;top:0px;left:0px;display:none" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" data-origw="'+prep.attr("width")+'" data-origh="'+prep.attr("height")+'" ></video');
		       if (prep.data('mp4')!=undefined) {
				   var mediaType = prep.data("mp4").search('mp4') !== -1 ? 'video/mp4' : 'audio/mpeg';
				   media.find('video').append('<source src="'+prep.data("mp4")+'" type="' + mediaType + '" />');
			   }
		       if (prep.data('webm')!=undefined) media.find('video').append('<source src="'+prep.data("webm")+'" type="video/webm" />');
		       if (prep.data('ogv')!=undefined) media.find('video').append('<source src="'+prep.data("ogv")+'" type="video/ogg" />');
		   }

		 });

	 adjustMediaSize(item,true,null,opt);

	 var ifr = item.find('.adamlabsgallery-youtube-frame'),
	 	cover = item.find('.adamlabsgallery-entry-cover'),
	 	poster = item.find('.adamlabsgallery-media-poster'),
	 	vt = "y",
	 	go = false;

	 if (!ifr.length) { ifr=item.find('.adamlabsgallery-vimeo-frame'); vt = "v";}
	 if (!ifr.length) { ifr=item.find('.adamlabsgallery-wistia-frame'); vt="w";}
	 if (!ifr.length) { ifr=item.find('.adamlabsgallery-soundcloud-frame'); vt="s";}
	 if (!ifr.length) { ifr=item.find('.adamlabsgallery-video-frame'); vt="h"; }


	 // IN CASE NO FRAME IS PREDEFINED YET WE NEED TO LOAD API, AND VIDEO, AND CHANGE SRC

	 if (ifr.attr('src')=="about:blank") 
	 	ifr.attr('src',ifr.data('src'));
	 else
	 if (ifr.hasClass("adamlabsgallery-video-frame"))
	 	adamlabsgallerygs.TweenLite.set(ifr,{opacity:0,display:"block"});
	 else 
	 	go = true;
	
	 loadVideoApis(container,opt);
	 
	 if (!simpleframe) adamlabsgallerygs.TweenLite.set(ifr,{opacity:1,display:"block"});
	 
	 var intr;
	 function onCheck() {

	 	if (go || (vt=="y" && prepareYT(ifr)) || (vt=="v" && prepareVimeo(ifr)) || (vt=="w" && prepareWs(ifr)) || (vt=="s" && prepareSoundCloud(ifr)) || (vt=="h" && prepareVideo(ifr))) {
 			clearInterval(intr);

 			if (!simpleframe) {

 				if (is_mobile()) {
	 				adamlabsgallerygs.TweenLite.set(ifr,{autoAlpha:1});
			 		adamlabsgallerygs.TweenLite.set(poster,{autoAlpha:0});
			 		adamlabsgallerygs.TweenLite.set(cover,{autoAlpha:0});
 				} else {
			 		adamlabsgallerygs.TweenLite.to(ifr,0.5,{autoAlpha:1});
			 		adamlabsgallerygs.TweenLite.to(poster,0.5,{autoAlpha:0});
			 		adamlabsgallerygs.TweenLite.to(cover,0.5,{autoAlpha:0});
			
			 	}
				
				if (vt==="y") playYT(ifr,simpleframe);
			 	if (vt==="v") playVimeo(ifr,simpleframe);
			 	if (vt==="s") playSC(ifr,simpleframe);
			 	if (vt==="h") playVideo(ifr,simpleframe);
				if (vt==="w") playWs(ifr,simpleframe);
		 	}
			
			/*
		 	if (ifr.attr('src') !=undefined) {

		 		if (ifr.attr('src').toLowerCase().indexOf('youtube')>0)
				 	playYT(ifr,simpleframe);
				if (ifr.attr('src').toLowerCase().indexOf('vimeo')>0)
				 	playVimeo(ifr,simpleframe);
				if (ifr.attr('src').toLowerCase().indexOf('wistia')>0)
				 	playWs(ifr,simpleframe);
				if (ifr.attr('src').toLowerCase().indexOf('soundcloud')>0)
				 	playSC(ifr,simpleframe);
				
			}
			*/
	 	}	

		suppressFocus = false;
		
	}
	 
	 intr = setInterval(onCheck,100);
	 onCheck();
}

function setMediaEntryAspectRatio(obj)  {
	
	var attrw = obj.img!==undefined ? obj.img.attr('width') : 1,
		attrh = obj.img!==undefined ? obj.img.attr('height') : 1;
		
	if (obj.ar===undefined || obj.ar=="auto" || isNaN(obj.ar)) {
		obj.imgw = obj.imgw===undefined ? obj.img!=undefined ? obj.img.width() : 1 : obj.imgw;
		obj.imgh = obj.imgh===undefined ? obj.img!=undefined ? obj.img.height() : 1 : obj.imgh;

		obj.imgw = obj.imgw===null || isNaN(obj.imgw) || obj.imgw===undefined || obj.imgw===false ? 1 : obj.imgw;
		obj.imgh = obj.imgh===null || isNaN(obj.imgh) || obj.imgh===undefined || obj.imgh===false ? 1 : obj.imgh;
						
		obj.imgw = obj.img!=undefined ? attrw!==undefined && attrw!==false ? attrw : obj.imgw : 1;
		obj.imgh = obj.img!=undefined ? attrh!==undefined && attrh!==false ? attrh : obj.imgh : 1;
		
		obj.ar = obj.img!==undefined && obj.img.length>=1 ? (obj.imgh/obj.imgw)*100 : 0;
	}
	
	if (obj.ip.data('keepAspectRatio')!==1) {
		obj.ip.css({paddingBottom:obj.ar+"%"});
		obj.ip.data('bottompadding',obj.ar);		
	}

	if (obj.keepAspectRatio)	
		obj.ip.data('keepAspectRatio',1);


}

/*
function onMouseMoveTrigger() {
	
	var $this = jQuery(this);
	if($this.data('animstarted') != 1) $this.trigger('mouseenter');

}
*/

 /**********************************
 	-	PREPARE PORTFOLIO -
 **********************************/
 function prepareItemsInGrid(opt,appending) {

 	var container = opt.container;
 	container.addClass("adamlabsgallery-container");

 	if (!appending) {
	 	container.find('.mainul>li').each(function() {
	 		jQuery(this).addClass("adamlabsgallery-newli");
	 	});
 	}

 	// BASIC VARIABLES
	var items = opt.mainul[0].getElementsByClassName('adamlabsgallery-newli'), /* opt.mainul.find('>.adamlabsgallery-newli'), */
		/* itemw = 100/opt.column, */
		ar = opt.aspectratio,
		cwidth = container.find('.adamlabsgallery-overflowtrick').parent().width(),
		/* ul = container.find('ul').first(), */
		/* adamlabsgalleryo = container.find('.adamlabsgallery-overflowtrick').first(), */
		itemh=0,
		aratio = 1,
		hratio = 1;
	
	// CALCULATE THE ASPECT RATIO
	ar = ar.split(":");

	if (ar.length>1) {
		aratio=parseInt(ar[0],0) / parseInt(ar[1],0);
		hratio = parseInt(ar[1],0) / parseInt(ar[0],0);
		itemh = (cwidth / opt.column) / aratio;
		kar=true;
		hratio=hratio*100;
	} else {
		aratio ="auto";
		hratio ="auto";
		kar=false;
	}
	
	/* 2.1.6.2 */
	var itemAnime = container.find('li[data-anime]').length,
		itemAnimeOther = container.find('li[data-anime-other]').length;
		
	if(itemAnime || itemAnimeOther) container.addClass('adamlabsgallery-itm-anime');
 
	// PREPARE THE ITEMS
	for (var q=0;q<items.length;q++) {			
 		var $item = items[q],
 			item= jQuery($item),
 			media = item.find('.adamlabsgallery-entry-media'),
 			img = media.find('img'),
 			mediasrc = img!=undefined && img.length>0 ? img.attr('src') : undefined,
			lzysrc = img!=undefined && img.length>0 ? img.data('lazysrc') : undefined;


		if (lzysrc===undefined) lzysrc = mediasrc;

		media.addClass(opt.mediaFilter);

 		adamlabsgallerygs.TweenLite.set(item,{force3D:"auto",autoAlpha:0,opacity:0});


	 	// PREPARE CLASS OF ITEM
	 	item.addClass("adamlabsgallery-item");
		
		// 2.1.6.2
		if(itemAnime || itemAnimeOther) {
			
			var blur1 = item.attr('data-anime-blur'),
				blur2 = item.attr('data-anime-other-blur');
				
			if(blur1 || blur2) item.addClass('adamlabsgallery-anime-blur');
			
			item.addClass('adamlabsgallery-anime-item').find('.adamlabsgallery-media-cover-wrapper').addClass('adamlabsgallery-item-anime').data({
				
				anime_itm: item.attr('data-anime'),
				anime_itm_other: item.attr('data-anime-other'),	
				anime_itm_zoomin: item.attr('data-anime-zoomin'),
				anime_itm_other_zoomin: item.attr('data-anime-other-zoomin'),	
				anime_itm_zoomout: item.attr('data-anime-zoomout'),
				anime_itm_other_zoomout: item.attr('data-anime-other-zoomout'),	
				anime_itm_fade: item.attr('data-anime-fade'),
				anime_itm_other_fade: item.attr('data-anime-other-fade'),	
				anime_itm_shift: item.attr('data-anime-shift'),	
				anime_itm_other_shift: item.attr('data-anime-other-shift'),	
				anime_itm_shift_amount: item.attr('data-anime-shift-amount'),
				anime_itm_shift_other_amount: item.attr('data-anime-other-shift-amount'),
				anime_itm_rotate: item.attr('data-anime-rotate'),
				anime_itm_other_rotate: item.attr('data-anime-other-rotate'),
				anime_itm_blur: blur1,
				anime_itm_other_blur: blur2
				
			}).find('.adamlabsgallery-entry-media.grayscale').removeClass('grayscale').parent().addClass('grayscale');

		}

	 	var imgopts  = { bgpos: img.length>=1 && img!=undefined ? img.data("bgposition") : undefined,
	 					 bgsize: img.length>=1 && img!=undefined ? img.data("bgsize") : undefined,
	 					 bgrepeat: img.length>=1 && img!=undefined ? img.data("bgrepeat") : undefined,
	 					};

	 	imgopts.bgpos =  imgopts.bgpos===undefined ? "" : "background-position:"+imgopts.bgpos+";";
	 	imgopts.bgsize =  imgopts.bgsize===undefined ? "" : "background-size:"+imgopts.bgsize+";";
	 	imgopts.bgrepeat =  imgopts.bgrepeat===undefined ? "" : "background-repeat:"+imgopts.bgrepeat+";";
	 	
		/* 2.1.6 */
		var bgUrl = lzysrc || '';
	 	media.append('<div class="adamlabsgallery-media-poster" src="'+lzysrc+'" data-src="'+lzysrc+'" data-lazythumb="'+img.data("lazythumb")+'" style="'+imgopts.bgsize+imgopts.bgrepeat+imgopts.bgpos+'background-image:url('+bgUrl+')"></div>');
	 	
	 	// WRAP MEDIA CONTENT
	 	if (opt.layout=="even") {	 			  						
			 media.wrap('<div class="adamlabsgallery-entry-media-wrapper" style="width:100%;height:100%;overflow:hidden;position:relative;"></div>');
		} else
			media.wrap('<div class="adamlabsgallery-entry-media-wrapper" style="overflow:hidden;position:relative;"></div>');
			
		
		setMediaEntryAspectRatio({ip:media,img:img,ar:hratio,keepAspectRatio:kar});
		
		if (img!=undefined && img.length>0) img.css({display:"none"});


		item.find('.adamlabsgallery-media-video').each(function() {
			
			var prep = jQuery(this),				
				videovisible = "display:none;",
				viddatasrc = "data-src=";
				vidsrc = "src=";

		   if (prep.data('poster')!=undefined && prep.data('poster').length>3)	
		   			media.find('.adamlabsgallery-media-poster').css({opacity:1,backgroundImage:"url("+prep.data('poster')+")"}).attr('src',prep.data('poster')).data('src',prep.data('poster'));
		   else {
			   
			      // 2.2.5 
	 			 if(!item.hasClass('adamlabsgallery-split-content')) item.find('.adamlabsgallery-entry-cover').remove();

	 			 item.find('.adamlabsgallery-media-poster').remove();
				 videovisible = "display:block;";
				 hratio = (parseInt(prep.attr('height'),0) / parseInt(prep.attr('width'),0))*100;				 
				 setMediaEntryAspectRatio({ip:media,ar:hratio,keepAspectRatio:true});
				 /**
				 -	CLICK ON ITEM TO PLAY VIDEO IN SIMPLEFRAME-
				 **/
				 item.data('simplevideo',1);
				 // videoClickEvent(item,container,opt,true);
			}
	
		// ?
		if (item.find('.adamlabsgallery-click-to-play-video').length==0) {
			  item.find('.adamlabsgallery-entry-cover').find('*').each(function () {
				  if (jQuery(this).closest('a').length==0 && jQuery(this).find('a').length==0) {
					  jQuery(this).addClass("adamlabsgallery-click-to-play-video");
				  }
			  });

			  item.find('.adamlabsgallery-overlay').addClass("adamlabsgallery-click-to-play-video");
		   }

			//YOUTUBE PREPARING
			if (prep.data('youtube')!=undefined) {
				
				if(opt.youtubeNoCookie!="false"){
					var ytframe = "https://www.youtube-nocookie.com/embed/"+prep.data('youtube')+"?version=3&enablejsapi=1&html5=1&controls=1&autohide=1&rel=0&showinfo=0&fs=1&playsinline=1";
				}
				else {
					var ytframe = "https://www.youtube.com/embed/"+prep.data('youtube')+"?version=3&enablejsapi=1&html5=1&controls=1&autohide=1&rel=0&showinfo=0&fs=1&playsinline=1";	
				}	
			  	media.append('<iframe class="adamlabsgallery-youtube-frame" wmode="Opaque" style="position:absolute;top:0px;left:0px;'+videovisible+'" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" '+viddatasrc+'"'+ytframe+'"' + vidsrc + '"about:blank" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
			}

			//VIMEO PREPARING
			if (prep.data('vimeo')!=undefined) {
				
			  	var vimframe = "https://player.vimeo.com/video/"+prep.data('vimeo')+"?title=0&byline=0&html5=1&portrait=0&playsinline=1";
				media.append('<iframe class="adamlabsgallery-vimeo-frame" style="position:absolute;top:0px;left:0px;'+videovisible+'" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""  width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" '+viddatasrc+'"'+vimframe+'"' + vidsrc + '"about:blank"></iframe>');
			}
			   
			//wistia PREPARING
			if (prep.data('wistia')!=undefined) {
				var wsframe = "https://fast.wistia.net/embed/iframe/"+prep.data('wistia')+"?version=3&enablejsapi=1&html5=1&controls=1&autohide=1&rel=0&showinfo=0";
				media.append('<iframe class="adamlabsgallery-wistia-frame" wmode="Opaque" style="position:absolute;top:0px;left:0px;'+videovisible+'" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" '+viddatasrc+'"'+wsframe+'"' + vidsrc + '"about:blank"></iframe>');
			}
			
			//SOUND CLOUD PREPARING
			if (prep.data('soundcloud')!=undefined) {
		   		var scframe = 'https://w.soundcloud.com/player/?url=https://api.soundcloud.com/tracks/'+prep.data('soundcloud')+'&amp;auto_play=false&amp;hide_related=false&amp;visual=true&amp;show_artwork=true';
		   		media.append('<iframe class="adamlabsgallery-soundcloud-frame" style="position:absolute;top:0px;left:0px;'+videovisible+'" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" '+viddatasrc+'"'+scframe+'"' + vidsrc + '"about:blank"></iframe>');
		   	}

			//VIDEO PREPARING
			if (prep.data('mp4')!=undefined || prep.data('webm')!=undefined || prep.data('ogv')!=undefined) {

			   media.append('<video class="adamlabsgallery-video-frame" controls style="position:absolute;top:0px;left:0px;'+videovisible+'" width="'+prep.attr("width")+'" height="'+prep.attr("height")+'" data-origw="'+prep.attr("width")+'" data-origh="'+prep.attr("height")+'" playsinline></video');
			   var hvid = media.find('video');
			   if (prep.data('mp4')!=undefined) {
				   var mediaType = prep.data("mp4").search('mp4') !== -1 ? 'video/mp4' : 'audio/mpeg';
				   hvid.append('<source src="'+prep.data("mp4")+'" type="' + mediaType + '" />');
			   }
			   if (prep.data('webm')!=undefined) hvid.append('<source src="'+prep.data("webm")+'" type="video/webm" />');
			   if (prep.data('ogv')!=undefined) hvid.append('<source src="'+prep.data("ogv")+'" type="video/ogg" />');

			}

			 /*************************************
			 	-	CLICK ON ITEM VIDEO ICONS	-
			 **************************************/

			 item.find('.adamlabsgallery-click-to-play-video').click(function() {
				 
				 var item = jQuery(this);
				 if(item.hasClass('adamlabsgallery-ajaxclicklistener') || item.closest('.adamlabsgallery-ajaxclicklistener').length) return;
				 
				 item = item.closest('.adamlabsgallery-item');
				 videoClickEvent(item,container,opt);
			 });
			
			 if (item.data('simplevideo')==1) {
				 
				 var vid = item.find('video');
				 if(!vid.length) waitMediaListeners(item, container, opt);
				 else vid.css('opacity', '1');
				 
			 }

		});

		// PREPARE THE CONTAINERS FOR MEDIAS
		if (item.find('.adamlabsgallery-media-video').length==0) item.find('.adamlabsgallery-click-to-play-video').remove();
		
		adjustMediaSize(item,true,null,opt);

 		//CHECK IF ENTRY HAS MEDIA & CONTENT PART
 		if (item.find('.adamlabsgallery-entry-content').length>0 && item.find('.adamlabsgallery-media-cover-wrapper').length>0) {
	 		if (item.find('.adamlabsgallery-entry-content').index()<item.find('.adamlabsgallery-media-cover-wrapper').index())
	 		{

	 		} else {
	 		  item.find('.adamlabsgallery-entry-content').addClass('adamlabsgallery-notalone');
	 		}
	 		
 		}


 		// PREPARE THE COVER ELEMENT POSITIONS
		 item.find('.adamlabsgallery-entry-cover').each(function(i) {

			 var eec = jQuery(this),
			 	 clickable = eec.data('clickable');

			 // 2.2.5
			 // eec.css({visibility:"hidden"});

			 eec.find('.adamlabsgallery-top').wrapAll('<div class="adamlabsgallery-tc eec"></div>');
			 eec.find('.adamlabsgallery-left').wrapAll('<div class="adamlabsgallery-lc eec"></div>');
			 eec.find('.adamlabsgallery-right').wrapAll('<div class="adamlabsgallery-rc eec"></div>');
			 eec.find('.adamlabsgallery-center').wrapAll('<div class="adamlabsgallery-cc eec"></div>');
			 eec.find('.adamlabsgallery-bottom').wrapAll('<div class="adamlabsgallery-bc eec"></div>');

			 eec.find('.eec').append('<div></div>');

			 if (clickable=="on" && eec.find('.adamlabsgallery-overlay').length>=1) {
				 eec.click(function(e) {
					if (jQuery(e.target).closest('a').length==0)
					  jQuery(this).find('.adamlabsgallery-invisiblebutton')[0].click();
				 }).css({cursor:"pointer"});
			 }
		 });

		 	item.data('pt',parseInt(item.css("paddingTop"),0));
		 	item.data('pb',parseInt(item.css("paddingBottom"),0));
		 	item.data('pl',parseInt(item.css("paddingLeft"),0));
		 	item.data('pr',parseInt(item.css("paddingRight"),0));
		 	item.data('bt',parseInt(item.css("borderTopWidth"),0));
		 	item.data('bb',parseInt(item.css("borderBottomWidth"),0));
		 	item.data('bl',parseInt(item.css("borderLeftWidth"),0));
		 	item.data('br',parseInt(item.css("borderRightWidth"),0));

		 if (item.find('.adamlabsgallery-entry-content').length>0 && opt.layout=="even") {

		 	item.css({paddingTop:"0px",paddingLeft:"0px",paddingRight:"0px",paddingBottom:"0px"});
		 	item.css({borderTopWidth:"0px",borderBottomWidth:"0px",borderLeftWidth:"0px",borderRightWidth:"0px"});

		 }


	
		 /****************************************
		 	-	AJAX EXTENSION PREPARING	-
		 *****************************************/

		 if (opt.ajaxContentTarget != undefined && jQuery("#"+opt.ajaxContentTarget).length>0)
			 item.find('.adamlabsgallery-ajaxclicklistener, a').each(function() {

				 var a = jQuery(this),
				 	 act = jQuery("#"+opt.ajaxContentTarget).find('.adamlabsgallery-ajax-target');
				 	 if (!act.parent().hasClass("adamlabsgallery-ajaxanimwrapper")) {
					 	act.wrap('<div class="adamlabsgallery-ajaxanimwrapper" style="position:relative;overflow:hidden;"></div>');
				 	 }
				 if (a.data('ajaxsource')!=undefined && a.data('ajaxtype')!=undefined) {
					 a.addClass("adamlabsgallery-ajax-a-button");
					 a.click(function() {
						 loadMoreContent(container,opt,a);
						 if (act.length>0)
					 		return false;
					 	 else
					 		return true;

					 });

				 }
			 });

		 /***********************************************
		 	-	TRIGGER FILTER ON CATEGORY CLICK	-
		 ************************************************/
		 item.find('.adamlabsgallery-triggerfilter').click(function() {
			var fil = jQuery(this).data('filter');
			jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton,'+opt.filterGroupClass+' .adamlabsgallery-filterbutton').each(function() {
				if (jQuery(this).data('filter') == fil) jQuery(this).trigger("click");
			});
			return false;
		 }).css({cursor:"pointer"});


		 /******************************
		 	-	HOVER ON ITEMS	-
		 ********************************/
		item.on('mouseenter.hoverdir mouseleave.hoverdir', function( event ) {
			
			var item=jQuery(this);/*.off('mousemove.hoverdir', onMouseMoveTrigger);*/
			
			// 2.2.5
			/*
			if(!opt.releaseHovers && opt.startAnimation === 'none') {
				
				item.on('mousemove.hoverdir', onMouseMoveTrigger);
				return;
				
			}
			*/
			
		    var direction = getDir( item, { x : event.pageX, y : event.pageY } );	
			//if(item.find('.isplaying').length) return;
				
		  	if (event.type === 'mouseenter') {
				
				itemHoverAnim(jQuery(this),"nope",opt,direction);
				
			}
			else {
					
				 // 2.2.5
		  		 // clearTimeout(item.data('hovertimer'));
				 
		  		 if ( item.data('animstarted')==1) {
			  		  item.data('animstarted',0);

				 	 // REMOVE THE CLASS FOR ANY FURTHER DEVELOPEMENTS
					 item.removeClass("adamlabsgallery-hovered");
					 /* var ecc = item.find('.adamlabsgallery-entry-cover'), */
					 var maxdelay=0;

					 if (item.find('.adamlabsgallery-entry-content').length>0 && opt.layout=="even") {
						adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-content'),{display:"none"});
						adamlabsgallerygs.TweenLite.set(item,{zIndex:5});
						adamlabsgallerygs.TweenLite.set(item.closest('.adamlabsgallery-overflowtrick'),{overflow:"hidden"});

						// SPECIAL FUN FOR OVERLAPPING CONTAINER, SHOWING MASONRY IN EVEN GRID !!!
						item.css({paddingTop:"0px",paddingLeft:"0px",paddingRight:"0px",paddingBottom:"0px"});
						item.css({borderTopWidth:"0px",borderBottomWidth:"0px",borderLeftWidth:"0px",borderRightWidth:"0px"});
					 	//item.find('.adamlabsgallery-entry-media').css({height:"100%"});
					 	
						// 2.2.5
						if(!isNaN(item.data('hhh'))) {
							adamlabsgallerygs.TweenLite.set(item,{z:0,'height':item.data('hhh'),width:item.data('www'),x:0,y:0});
						}
					 	
						if (opt.evenGridMasonrySkinPusher=="on") offsetParrents(0,item);
					 }


					 // 2.2.5
					 // jQuery.each(miGalleryAnimmatrix,function(index,key) {
						 item.find('.adamlabsgallery-transition').each(function() {
							 
							var elem = jQuery(this),
								trans = elem.attr('data-transition');
								
							if(!miGalleryAnimmatrix.hasOwnProperty(trans)) return;
							var key = miGalleryAnimmatrix[trans];
							 
							  var dd = elem.data('delay')!=undefined ? elem.data('delay') : 0,
							  	  animto = key[4],
							  	  elemdelay =0,
							  	  animobject = elem,
							  	  /* splitted = false, */
							  	  isOut = trans.indexOf('out') > -1,
								  xy,
								  tw;
							  	 				  	  
							  	 
								  if (maxdelay<dd) maxdelay = dd;
		  						  if (animto.z == undefined) animto.z = 1;
		  						  switch (trans) {
		  						  		case "adamlabsgallery-slide":
		  						  				xy =  directionPrepare(direction,"in",item.width(),item.height(),true);

		  						  				animto.x = xy.x;
		  						  				animto.y = xy.y;
												tw = adamlabsgallerygs.TweenLite.to(animobject,0.5,{y:animto.y, x:animto.x,overwrite:"all",onCompleteParams:[animobject],onComplete:function(obj) {
													adamlabsgallerygs.TweenLite.set(obj,{autoAlpha:0});
												}});
		  						  		break;
		  						  		case "adamlabsgallery-slideout":

		  						  				xy =  directionPrepare(direction,"out",item.width(),item.height());
		  						  				animto.x = 0;
		  						  				animto.y = 0;
		  						  				animto.overwrite = "all";
												tw = adamlabsgallerygs.TweenLite.fromTo(animobject,0.5,{autoAlpha:1,x:xy.x,y:xy.y},{x:0,y:0,autoAlpha:1,overwrite:"all"});
		  						  		break;
										
										case 'adamlabsgallery-blur':
										case 'adamlabsgallery-fadeblur':
										case 'adamlabsgallery-zoomblur':
										case 'adamlabsgallery-zoomdefaultblur':
										
											var blur,
												amount = parseInt(this.dataset.bluramount, 10),
												at = {onUpdate: function() {
												
												blur = (1 - tw.progress()) * amount;
												animobject.css('filter', 'blur(' + blur + 'px)');
												
											}};
											jQuery.extend(at,animto);
											tw = adamlabsgallerygs.TweenLite.to(animobject,key[3],at,elemdelay);
											
										break;
										
										case 'adamlabsgallery-grayscalein':
										case 'adamlabsgallery-grayscaleout':
										
											var grayscale,
												animein = trans.search('in') !== -1;
												
											at = {onUpdate: function() {
												
												grayscale = animein ? (1 - tw.progress()) * 100 : tw.progress() * 100;
												animobject.css('filter', 'grayscale(' + grayscale + '%)');
												
											}};
											jQuery.extend(at,animto);
											tw = adamlabsgallerygs.TweenLite.to(animobject,key[3],at,elemdelay);
											
										break;

		  						  		default:

												animto.force3D="auto";																										
		  						  				tw = adamlabsgallerygs.TweenLite.to(animobject,key[3],animto,elemdelay);
		  						  		break;
	  						  		}
	  						  		
	  						  	if (isOut) {			  						  	
			  						tw.eventCallback("onComplete",resetTransforms,[animobject]);			  						
			  					}
			  			});
					 //});
				 
 				}
				
				// 2.2.5
				/*
	 			if (item.hasClass("adamlabsgallery-demo"))
	 				setTimeout(function() {
		 				  itemHoverAnim(item);
	 				},800);
				*/
 			}
		});
		
		/*
			2.2.5
		*/
		 // PREPARE VISIBLE AND UNVISIBLE ELEMENTS !!
		 //itemHoverAnim(item,"set",opt);

		 //if (item.hasClass("adamlabsgallery-demo")) itemHoverAnim(item);

	}
	
	// 2.2.5
	container.find('.adamlabsgallery-transition').each(function() {
		
		var elem = jQuery(this);
		if(elem.data('prepared')) return;
		elem.data('prepared', true);
			
		var trans = elem.attr('data-transition');
		if(!miGalleryAnimmatrix.hasOwnProperty(trans)) return;
		
		var key = miGalleryAnimmatrix[trans],
			animfrom = jQuery.extend({}, key[1]);
			
		adamlabsgallerygs.TweenLite.set(elem, animfrom);
		
	});
	
	loadVideoApis(container,opt);
 	setItemsOnPages(opt);
	opt.mainul.find('.adamlabsgallery-newli').removeClass('adamlabsgallery-newli');
	
 }



function resetTransforms(element) {
	adamlabsgallerygs.TweenLite.set(element,{clearProps:"transform", css:{clearProps:"transform"}});
}
 /*****************************************
 	-	Get IframeOriginal Size	-
 *****************************************/
function adjustMediaSize(item,resize,p,opt) {	 
	 // PREPARE IFRAMES !!
 	var srcfor = item.find('iframe').length>0 ? "iframe" :
 				item.find('.adamlabsgallery-video-frame').length>0 ? ".adamlabsgallery-video-frame" : "";

	// Calculate Iframe Width and Height
		if (srcfor!=="") {
 		item.find(srcfor).each(function(i) {
 			var ifr = jQuery(this);
	 		ifr.data('origw',ifr.attr('width'));
	 		ifr.data('origh',ifr.attr('height'));
	 		var oldw = ifr.data('origw'),
	 			oldh = ifr.data('origh'),
	 			ifw,ifh;

	 		ifw = p!=undefined ? p.itemw : item.width();
	 		ifh = Math.round((ifw  / oldw) * oldh);
	 		ifw = Math.round(ifw);

	 		ifr.data('neww',ifw);
		 	ifr.data('newh',ifh);

			if (resize && opt.layout!="even") {
		 	   adamlabsgallerygs.TweenLite.set(ifr,{width:ifw,height:ifh});
		 	//   adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-media'),{width:ifw,height:ifh});
	 		} else {
		 	   adamlabsgallerygs.TweenLite.set(ifr,{width:"100%",height:"100%"});
		 	  // adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-media'),{width:"100%",height:"100%"});
	 		}
	 	});
		} 	
 }





 /******************************
 	-	SET PAGE FILTER	-
 ********************************/
 function setItemsOnPages(opt) {
 	
		var container = opt.container;

		 // BASIC VARIABLES
		 // 2.2.6
		 var items = container.find('.mainul>li').not('.skipblank'),
		 	 itemperpage = opt.column*opt.row,
			 i;

		 // CALCULATE ITEM PER PAGE HERE (BASED ON LAYOUT AND MULTIPLIER
		 var mp = opt.rowItemMultiplier;
		 var mpl = mp.length;
		
		 if (mpl>0)
			 if (/*opt.column!=1 &&*/ opt.layout=="even") {
			 	 itemperpage = 0;
				 for (i=0;i<opt.row;i++) {
					 var cle = i - (mpl*Math.floor(i/mpl));
					 	itemperpage = itemperpage + mp[cle][opt.columnindex];
				 }
			 }

		// COBBLES PATTER SHOULD SHOW ONLY AS MANY ROWS AS MAX ROWS REALLY SET
		if (opt.evenCobbles == "on" && opt.cobblesPattern!=undefined) {
			 /* var trow = 0, */
			 /* var tcol = 0, */
			 var tcount = 0;
			 
			 itemperpage = 0;

			  jQuery.each(items, function(i,$item) {
				   var item = jQuery(item),
						cobblesw = item.data('cobblesw'),
						cobblesh = item.data('cobblesh');

					if (opt.cobblesPattern!=undefined && opt.cobblesPattern.length>2) {
						var newcobblevalues =  getCobblePat(opt.cobblesPattern,i);
						cobblesw = parseInt(newcobblevalues.w,0);
						cobblesh = parseInt(newcobblevalues.h,0);

					}

					cobblesw = cobblesw==undefined ? 1 : cobblesw;
					cobblesh = cobblesh==undefined ? 1 : cobblesh;

					if (opt.column < cobblesw) cobblesw = opt.column;
					tcount = tcount + cobblesw*cobblesh;

					if ((opt.column*opt.row)>=tcount) itemperpage++;

			});
		}

		 var minindex = itemperpage*opt.currentpage,
		 	 /* cwidth = container.find('.adamlabsgallery-overflowtrick').parent().width(), */
		 	 maxindex = minindex + itemperpage,
		 	 filters = jQuery(opt.filterGroupClass+'.adamlabsgallery-filterbutton.selected:not(.adamlabsgallery-navigationbutton),'+opt.filterGroupClass+' .adamlabsgallery-filterbutton.selected:not(.adamlabsgallery-navigationbutton)'),
		 	 indexcounter = 0,
			 isStream = opt.container.closest('.myportfolio-container').hasClass('source_type_stream');
		 
		 // 2.2.5
		 container.find('.adamlabsgallerybox').each(function() {
			
			if(this.className.search('facebook') !== -1) {
				
				jQuery(this).removeAttr('data-width data-height');
				
			}
			 
		 });
		 
		 // PREPARE THE ITEMS IF WE HAVE FILTERS ON PAGE
		 if (jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper, '+opt.filterGroupClass+' .adamlabsgallery-filter-wrapper').length>0) {
					 jQuery.each(items,function(index,$item) {

					 	var item= jQuery($item),
					 	items = item.find('.adamlabsgallerybox');
						
						if(items.length > 1) {	
							var len = items.length,
								itm;

							for(var i = 1; i < len; i++) {
								itm = items.eq(i);
								if(!itm.parent().hasClass('adamlabsgallerybox-additional')) continue;
								itm.removeClass('adamlabsgallerybox').addClass('adamlabsgallerybox-clone').off('click.essbox-start').removeAttr('data-adamlabsgallerybox').removeData('adamlabsgallerybox');
							}
						}
						
						item.find('.adamlabsgallerybox').each(function() {
							
							var $this = jQuery(this),
								oTitle = $this.data('posttitle') || $this.data('caption');
								
							theTitle = oTitle ? encodeURIComponent(oTitle) : '';
							
					 		if (opt.lightBoxMode=="all") {
					 			$this.attr('data-adamlabsgallerybox',opt.lightboxHash);
							}
					 		else if (opt.lightBoxMode!="contentgroup") {
						 		$this.attr('data-adamlabsgallerybox',"");
							}
							
							var settings = {
							
								featured: $this.data('featured'),
								titl: theTitle,
								lbTitle: opt.lightBoxPostTitle,
								lbTag: opt.lightBoxPostTitleTag,
								lbImg: opt.lightBoxFeaturedImg,
								lbMargin: opt.lightBoxFeaturedMargin,
								lbWidth: opt.lightBoxFeaturedWidth,
								lbPos: opt.lightBoxFeaturedPos,
								lbMin: opt.lightboxPostMinWid,
								lbMax: opt.lightboxPostMaxWid,
								margin: opt.lightboxMargin,
								padding: opt.lbContentPadding,
								overflow: opt.lbContentOverflow,
								revslider: $this.data('revslider'),
								adamlabsgallery: $this.data('lbadamlabsgallery'),
								ispost: $this.data('ispost'),
								gridid: $this.data('gridid')
								
							};
							
							settings = JSON.stringify(settings);
							
							if($this.hasClass('adamlabsgallerybox-post') && $this.attr('href') === 'javascript:void(0);') {
								
								$this.attr('data-type', 'ajax')
									 .attr('href', opt.loadMoreAjaxUrl + 
									       '?action=' + opt.loadMoreAjaxAction + 
										   '&client_action=load_post_content' + 
										   '&token=' + opt.loadMoreAjaxToken + 
										   '&postid=' + $this.data('post') + 
										   '&settings=' + settings
								);
								
							}
							
							var additional = $this.closest('.adamlabsgallery-item').find('.adamlabsgallerybox-additional').find('.adamlabsgallerybox');
							if(additional.length) {
								
								additional.each(function() {	
									jQuery(this).attr('data-caption', oTitle).find('img').remove();
								});
								
							}
							
							if(isStream) $this.data('ratio', '16:9');
							
					 	});
						
						if(jQuery.fn.adamlabsgallerybox) opt.container.find('.adamlabsgallerybox').adamlabsgallerybox(opt.container.data('lightboxsettings'));

					 	// CHECK IF THE FILTER SELECTED, AND IT FITS TO THE CURRENT ITEM
					 	var nofilter = true,
					 		foundfilter = 0;
					 	jQuery.each(filters,function(index,curfilter) {
						 	if (item.hasClass(jQuery(curfilter).data('filter'))) {
						 		nofilter=false;
						 		foundfilter++;
						 	}
					 	});

					 	// IF ELEMENT DO NOT PASS IN ALL SELECTED FILTER, THEN DO NOT SHOW IT
					 	if (opt.filterLogic=="and" && foundfilter < filters.length) nofilter = true;

					 	// IF SEARCH FILTER IS ACTIVATED, AND ELEMENT IS NOT FITTING IN SEARCH AND IN SELECTED FILTER, THEN HIDE IT
					 	hidsbutton = jQuery(opt.filterGroupClass+'.adamlabsgallery-filter-wrapper .hiddensearchfield');
					 	if (hidsbutton.hasClass("adamlabsgallery-forcefilter") && foundfilter < filters.length) nofilter = true;
						
					 	// FILTER BASED SHOW OR HIDE THE ITEM (Less then Items fit on Page
					 	if (indexcounter>=minindex && indexcounter<maxindex && !nofilter) {

						 	item.addClass("itemtoshow").removeClass("itemishidden").removeClass("itemonotherpage");

						 	if (opt.lightBoxMode=="filterpage" || opt.lightBoxMode=="filterall") {
						 		item.find('.adamlabsgallerybox').attr('data-adamlabsgallerybox',opt.filterGroupClass.replace('.', ''));
							}						 
						 	indexcounter++;
					 	} else {

						 	if (!nofilter) {
						 		if (indexcounter<minindex || indexcounter>=maxindex) {
							 		 item.addClass("itemonotherpage");
						 		     item.removeClass("itemtoshow");
						 		     indexcounter++;
							 	} else {
							 		item.addClass("itemtoshow").removeClass("itemishidden").removeClass("itemonotherpage");
							 		indexcounter++;
								}
							 	item.addClass("fitsinfilter");
								
								if (opt.lightBoxMode=="filterall") {
									item.find('.adamlabsgallerybox').attr('data-adamlabsgallerybox',opt.filterGroupClass.replace('.', ''));
								}
								
						 	}  else {
								item.addClass("itemishidden").removeClass("itemtoshow").removeClass("fitsinfilter");
							}
					 	}

				 	});
		} else {
	
				jQuery.each(items,function(index,$item) {
					 	var item= jQuery($item),
					 	items = item.find('.adamlabsgallerybox');
						
						if(items.length > 1) {	
							var len = items.length,
								itm;
								
							for(var i = 1; i < len; i++) {
								itm = items.eq(i);
								if(!itm.parent().hasClass('adamlabsgallerybox-additional')) continue;
								itm.removeClass('adamlabsgallerybox').addClass('adamlabsgallerybox-clone').off('click.essbox-start').removeAttr('data-adamlabsgallerybox').removeData('adamlabsgallerybox');
							}
						}
						
						item.find('.adamlabsgallerybox').each(function() {
							
							var $this = jQuery(this),
								oTitle = $this.data('posttitle') || $this.data('caption');
								
							theTitle = oTitle ? encodeURIComponent(oTitle) : '';
							
					 		if (opt.lightBoxMode=="all")
					 			$this.attr('data-adamlabsgallerybox',opt.lightboxHash);
					 		else
						 	if (opt.lightBoxMode!="contentgroup")
						 		$this.attr('data-adamlabsgallerybox',"");
							
							var settings = {
							
								featured: $this.data('featured'),
								titl: theTitle,
								lbTitle: opt.lightBoxPostTitle,
								lbTag: opt.lightBoxPostTitleTag,
								lbImg: opt.lightBoxFeaturedImg,
								lbMargin: opt.lightBoxFeaturedMargin,
								lbWidth: opt.lightBoxFeaturedWidth,
								lbPos: opt.lightBoxFeaturedPos,
								lbMin: opt.lightboxPostMinWid,
								lbMax: opt.lightboxPostMaxWid,
								margin: opt.lightboxMargin,
								padding: opt.lbContentPadding,
								overflow: opt.lbContentOverflow,
								revslider: $this.data('revslider'),
								adamlabsgallery: $this.data('lbadamlabsgallery'),
								ispost: $this.data('ispost'),
								gridid: $this.data('gridid')
								
							};
							
							settings = JSON.stringify(settings);
							
							if($this.hasClass('adamlabsgallerybox-post') && $this.attr('href') === 'javascript:void(0);') {
								
								$this.attr('data-type', 'ajax')
									 .attr('href', opt.loadMoreAjaxUrl + 
									       '?action=' + opt.loadMoreAjaxAction + 
										   '&client_action=load_post_content' + 
										   '&token=' + opt.loadMoreAjaxToken + 
										   '&postid=' + $this.data('post') + 
										   '&settings=' + settings
								);
								
							}
							
							var additional = $this.closest('.adamlabsgallery-item').find('.adamlabsgallerybox-additional').find('.adamlabsgallerybox');
							if(additional.length) {
								
								additional.each(function() {	
									jQuery(this).attr('data-caption', oTitle).find('img').remove();
								});
								
							}
							
							if(isStream) $this.data('ratio', '16:9');
							
					 	});

					 	if (opt.lightBoxMode=="filterall") 
						 	item.find('.adamlabsgallerybox').attr('data-adamlabsgallerybox',opt.lightboxHash);


					 	// FILTER BASED SHOW OR HIDE THE ITEM (Less then Items fit on Page
					 	if (indexcounter>=minindex && indexcounter<maxindex) {
					 		item.addClass("itemtoshow").removeClass("itemishidden").removeClass("itemonotherpage");
						 	indexcounter++;


						 	if (opt.lightBoxMode=="filterpage" || opt.lightBoxMode=="filterall") {
						 		item.find('.adamlabsgallerybox').attr('data-adamlabsgallerybox',opt.lightboxHash);
							}
					 	} else {

						 		if (indexcounter<minindex || indexcounter>=maxindex) {
							 		 item.addClass("itemonotherpage");
						 		     item.removeClass("itemtoshow");
						 		     indexcounter++;
							 	} else {
							 		item.addClass("itemtoshow").removeClass("itemishidden").removeClass("itemonotherpage");
							 		indexcounter++;
								}
							 	item.addClass("fitsinfilter");
					 	}

				 	});
					
					if(jQuery.fn.adamlabsgallerybox) opt.container.find('.adamlabsgallerybox').adamlabsgallerybox(opt.container.data('lightboxsettings'));

		}




	 	// HOW MANY NONEFILTERED ITEMS DO WE HAVE?
	 	opt.nonefiltereditems = container.find('.itemtoshow, .fitsinfilter').length;

	 	if (opt.loadMoreType!="none") {
	 		var amnt = 0;
	 		var onewaszero = false;
	 		filters.each(function() {

	 			var filt = jQuery(this).data('filter');
	 			if (filt !=undefined) {
		 			var newc = container.find('.'+filt).length;
			 		amnt = amnt + newc;
			 		if (newc==0) onewaszero = true;
			 	}
		 	});

		 	if (filters.length==0 || filters.length==1) amnt=1;

		   if (amnt==0 || onewaszero) 		   	
		   	loadMoreItems(opt);
		   
		   	


	 	}

	 	// BUILD THE PAGINATION BASED ON NONE FILTERED ITEMS
	 	var paginholder = jQuery(opt.filterGroupClass+'.adamlabsgallery-pagination,'+opt.filterGroupClass+' .adamlabsgallery-pagination');
	 	paginholder.find('.adamlabsgallery-pagination').remove();
	 	paginholder.html("");
	 	opt.maxpage=0;

 		var extraclass;
 		var pageamounts = Math.ceil(opt.nonefiltereditems / itemperpage);

 		opt.realmaxpage = pageamounts;

 		if (pageamounts>7 && opt.smartPagination=="on") {
		 	//BUILD PAGINATION IF ONLY SMART PAGES SHOULD BE ADDED
		 	if (opt.currentpage<3) {
			 	for (i=0;i<4;i++) {
				  if (i==opt.currentpage)
		 		 	extraclass="selected";
		 		  else
		 			extraclass="";
		 		  opt.maxpage++;
			 	  paginholder.append('<div class="adamlabsgallery-navigationbutton adamlabsgallery-filterbutton adamlabsgallery-pagination-button '+extraclass+'" data-page="'+i+'">'+(i+1)+'</div>');
			 	}
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton">...</div>');
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton adamlabsgallery-filterbutton adamlabsgallery-pagination-button '+extraclass+'" data-page="'+(pageamounts-1)+'">'+(pageamounts)+'</div>');
		 	}

		 	else

		 	if (pageamounts - opt.currentpage<4) {
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton adamlabsgallery-filterbutton adamlabsgallery-pagination-button '+extraclass+'" data-page="0">1</div>');
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton">...</div>');
			 	for (i=pageamounts-4;i<pageamounts;i++) {
				  if (i==opt.currentpage)
		 		 	extraclass="selected";
		 		  else
		 			extraclass="";
		 		  opt.maxpage++;
			 	  paginholder.append('<div class="adamlabsgallery-navigationbutton adamlabsgallery-filterbutton adamlabsgallery-pagination-button '+extraclass+'" data-page="'+i+'">'+(i+1)+'</div>');
			 	}
		 	} else {
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton adamlabsgallery-filterbutton adamlabsgallery-pagination-button '+extraclass+'" data-page="0">1</div>');
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton">...</div>');
			 	for (i=opt.currentpage-1;i<opt.currentpage+2;i++) {
				  if (i==opt.currentpage)
		 		 	extraclass="selected";
		 		  else
		 			extraclass="";
		 		  opt.maxpage++;
			 	  paginholder.append('<div class="adamlabsgallery-navigationbutton adamlabsgallery-filterbutton adamlabsgallery-pagination-button '+extraclass+'" data-page="'+i+'">'+(i+1)+'</div>');
			 	}
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton">...</div>');
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton adamlabsgallery-filterbutton adamlabsgallery-pagination-button '+extraclass+'" data-page="'+(pageamounts-1)+'">'+(pageamounts)+'</div>');

		 	}

 		} else {

	 		// BUILD PAGINATION WHEN ALL PAGES SHOULD BE ADDED
		 	for (i=0;i<pageamounts;i++) {

		 		if (i==opt.currentpage)
		 			extraclass="selected";
		 		else
		 			extraclass="";
		 		opt.maxpage++;
			 	paginholder.append('<div class="adamlabsgallery-navigationbutton adamlabsgallery-filterbutton adamlabsgallery-pagination-button '+extraclass+'" data-page="'+i+'">'+(i+1)+'</div>');
		 	}
		 }


	 	if (opt.maxpage==1) {
		 	 jQuery(opt.filterGroupClass+'.adamlabsgallery-navigationbutton,'+opt.filterGroupClass+' .adamlabsgallery-navigationbutton').not('.adamlabsgallery-loadmore').css({display:'none'});

		 	 paginholder.css({display:'none'});
	 	} else {
		 	 jQuery(opt.filterGroupClass+'.adamlabsgallery-navigationbutton,'+opt.filterGroupClass+' .adamlabsgallery-navigationbutton').css({display:'inline-block'});
		 	 paginholder.css({display:'inline-block'});
	 	}

	 	if (opt.currentpage>=Math.ceil(opt.nonefiltereditems / itemperpage)) {
	 		opt.oldpage = opt.currentpage;
	 		opt.currentpage = 0;
	 		// Rescan again, and turn visibilty on of the first items, to make them visible after filtering has less pages
	 		var counter =0;
	 		container.find('.itemtoshow, .fitsinfilter').each(function() {
		 		counter++;
		 		if (counter<maxindex)
		 		  jQuery(this).removeClass("itemonotherpage");
	 		});
	 		paginholder.find('.adamlabsgallery-pagination-button').first().addClass("selected");
	 	}
	 	if (opt.currentpage<0) opt.currentpage=0;




	 	/** HANDLE OF PAGINATION BUTTONS **/
		paginholder.find('.adamlabsgallery-pagination-button').on("click",function() {
			
			/* 2.1.5 */
			stopAllVideos(true);
			
		 	opt.oldpage=opt.currentpage;
		 	opt.currentpage = jQuery(this).data('page');
			opt = getOptions(container);	//new added
			var gbfc = getBestFitColumn(opt,jQuery(window).width(),"id");
			opt.column = gbfc.column;
			opt.columnindex = gbfc.index;
			opt.mmHeight = gbfc.mmHeight;

			// CREATE A COOKIE FOR THE LAST SELECTION OF FILTERS
			if (opt.cookies.pagination==="on" && opt.cookies.searchjusttriggered !== true) 
				createCookie("grid_"+opt.gridID+"_pagination",opt.currentpage,opt.cookies.timetosave*(1/60/60));

		 	setItemsOnPages(opt);
			organiseGrid(opt,"paginholder");
			setOptions(container,opt);
			
			if (opt.paginationScrollToTop=="on") {
				jQuery("html, body").animate({scrollTop:(container.offset().top-opt.paginationScrollToTopOffset)},{queue:false,speed:0.5});
			}

		});


		if (opt.firstshowever==undefined) jQuery(opt.filterGroupClass+'.adamlabsgallery-navigationbutton,'+opt.filterGroupClass+' .adamlabsgallery-navigationbutton').css({visibility:"hidden"});
		
		/*
			BEGIN SPECIAL CSS TRANSITIONS
			2.2.6
		*/
		var special = jQuery('.adamlabsgallery-overlay.adamlabsgallery-transition').filter(function() {
			
			return this.dataset.transition && this.dataset.transition.search(/collapse|line|spiral|circle/) !== -1;
			
		});
		
		if(special.length) {
			
			if(!opt.specialStyle) opt.specialStyle = jQuery('<style type="text/css" />').appendTo(jQuery('head'));
			
			var styles = '',
				specialStyles = [],
				containerId = opt.container.attr('id');
				
			special.each(function() {
				
				var level,
					paths,
					ids = '',
					$this = jQuery(this),
					len = specialStyles.length,
					par = $this.closest('.eec'),
					animName = this.dataset.transition,
					skin = $this.closest('.adamlabsgallery-item').attr('data-skin');
				
				if(par.length) {
						
					level = par.hasClass('adamlabsgallery-tc') ? 'tc' : par.hasClass('adamlabsgallery-cc') ? 'cc' : par.hasClass('adamlabsgallery-bc') ? 'bc' : '';
					if(level) ids = ' .adamlabsgallery-' + level;
					
				}
				
				var objName = !this.dataset.animcolor ? animName : animName + level + this.dataset.animcolor;
				for(var i = 0; i < len; i++) if(specialStyles[i][objName] === animName) return;
				
				specialStyles[len] = {};
				specialStyles[len][objName] = animName;
				
				var svg,
					color,
					colorOne,
					colorTwo,
					bg = 'background',
					isLine = animName.search('line') !== -1,
					isSpiral = animName.search('spiral') !== -1,
					isCircle = animName.search('circle') !== -1,
					isCollapse = animName.search('collapse') !== -1,
					isSvg = isLine || isSpiral;
				
				if(!$this.data('specialcolorone')) {
					
					colorOne = $this.css('background-image');
					color = this.dataset.animcolor || '#FFFFFF';
					
					if(!colorOne || colorOne === 'none') colorOne = $this.css('background-color');
					if(isLine) {
					
						if(animName.search('linediagonal') !== -1) {
						
							svg = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="400px" height="300px"><line x1="0" y1="300" x2="400" y2="0" stroke="' + color + '" stroke-width="1"></line></svg>';
						
						}
						else {
							
							if(animName.search('horizontal') !== -1) paths = ['0', '150', '100%', '1', '400', '0', '100%', '1', '400', '150', '100%', '1'];
							else paths = ['200', '0', '1', '100%', '0', '300', '1', '100%', '200', '300', '1', '100%'];
							
							// rectangle needed for sharp/crisp lines
							svg = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="400px" height="300px">';
							svg += '<rect x="0" y="0" width="100%" height="100%" fill="transparent"></rect>';
							svg += '<rect x="' + paths[0] + '" y="' + paths[1] + '" width="' + paths[2] + '" height="' + paths[3] + '" fill="' + color + '" shape-rendering="crispEdges"></rect>';
							svg += '<rect x="' + paths[4] + '" y="' + paths[5] + '" width="' + paths[6] + '" height="' + paths[7] + '" fill="' + color + '" shape-rendering="crispEdges"></rect>';
							svg += '<rect x="' + paths[8] + '" y="' + paths[9] + '" width="' + paths[10] + '" height="' + paths[11] + '" fill="' + color + '" shape-rendering="crispEdges"></rect>';
							svg += '</svg>';
							
						}
						
						colorTwo = "url('" + svg + "')";
					
					}
					else if(isSpiral) {
							
						svg = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="250px" height="234px">';
						svg += '<path fill="' + color + '" d="M201.449,16.732 C229.021,59.363 222.332,125.403 176.384,153.001 C188.917,168.041 211.475,162.194 226.514,153.823 C235.694,147.985 244.060,141.291 249.903,132.928 C240.707,148.816 229.021,163.025 212.306,173.888 C198.097,183.089 183.074,188.105 166.358,189.776 C150.489,190.613 133.774,188.105 119.563,179.743 C116.244,188.105 118.735,196.460 122.917,203.985 C134.620,222.372 154.672,229.896 173.878,233.244 C123.748,235.752 68.620,197.300 67.772,143.800 C56.088,144.628 48.568,156.331 45.214,166.363 C40.201,183.089 41.879,201.477 48.568,217.365 C30.175,187.268 26.010,148.816 41.049,116.209 C48.568,101.169 59.425,89.460 73.633,80.265 C69.451,76.088 64.438,72.741 58.594,71.903 C35.188,70.233 13.477,82.773 0.097,101.169 C22.673,56.024 84.490,28.435 130.437,54.346 C135.450,43.483 129.607,31.781 122.087,23.419 C109.555,10.039 92.839,3.354 76.122,0.015 C103.694,0.015 129.607,8.370 151.319,26.765 C169.695,43.483 180.567,66.056 182.243,90.290 C189.747,89.460 194.760,84.443 198.925,78.597 C211.475,59.363 208.123,36.797 201.449,16.732 L201.449,16.732 Z"/>';
						svg += '</svg>';
						colorTwo = "url('" + svg + "')";
					
					}
					else {
					
						colorTwo = !isCollapse ? color : colorOne;
					
					}
					
					$this.data({specialcolorone: colorOne, specialcolortwo: colorTwo});
					
				}
				else {
				
					colorOne = $this.data('specialcolorone');
					colorTwo = $this.data('specialcolortwo');
				
				}
				
				var duration = this.dataset.duration || 'default';
				duration = duration === 'default' ? 0.3 : duration * 0.001;
				
				var easing,
					delayed = '';
					delay = this.dataset.delay || '',
					transition = !isCollapse ? 'transform' : 'all';
					
				if(delay) delay = ' ' + delay + 's';
				
				styles += '#' + containerId + ' .adamlabsgallery-item[data-skin="' + skin + '"]' + ids + ' .adamlabsgallery-transition[data-transition="' + animName + '"] {background: transparent !important}';
				styles += '#' + containerId + ' .adamlabsgallery-item[data-skin="' + skin + '"]' + ids + ' .adamlabsgallery-transition[data-transition="' + animName + '"]:before {';
				styles += 'transition: all ' + duration + 's ease-out;';
				styles += bg + ': ' + colorOne;
				styles += '}';
				styles += '#' + containerId + ' .adamlabsgallery-hovered[data-skin="' + skin + '"]' + ids + ' .adamlabsgallery-transition[data-transition="' + animName + '"]:before {';
				styles += 'transition-delay: ' + delay + ';';
				styles += '}';
				
				if(isCircle) duration += 0.7;
				if(isSvg) bg = 'background-image';
				easing = isSpiral ? 'ease-in' : isCircle ? 'ease' : 'ease-out';
				
				styles += '#' + containerId + ' .adamlabsgallery-item[data-skin="' + skin + '"]' + ids + ' .adamlabsgallery-transition[data-transition="' + animName + '"]:after {';
				styles += 'transition: ' + transition + ' ' + duration + 's ' + easing + ';';
				styles += bg + ': ' + colorTwo;
				styles += '}';
				styles += '#' + containerId + ' .adamlabsgallery-hovered[data-skin="' + skin + '"]' + ids + ' .adamlabsgallery-transition[data-transition="' + animName + '"]:after {';
				styles += 'transition-delay: ' + delay + ';';
				styles += '}';
				
			});
			
			if(styles) opt.specialStyle.html(styles);
			
		}
	
 }

function waittorungGrid(img,opt,what,inited) {
	
	var mainul = img.closest('.mainul');
	clearTimeout( mainul.data("intreorganisier"));
	if (!mainul.hasClass("gridorganising")) {
		if(!inited.init) runGrid(opt,what,inited);
	} else {
		 mainul.data("intreorganisier",setTimeout(function() {
			waittorungGrid(img,opt,what,inited);
		 },10));
	}
	
}

function loadHoverImage(img) {
	
	var hoverImg = img.parent().find('.adamlabsgallery-hover-image');
	if(hoverImg.length) hoverImg.css('background-image', 'url("' + hoverImg.data('src') + '")').appendTo(img);
	
}

/*******************************************
	-	PREPARE LOADING OF IMAGES	-
********************************************/
function loadAllPrepared(img,opt,inited) {
		
		if (img.data('preloading')==1) return false;

		var limg = new Image();

 	 	if (img.data('lazysrc')!=img.attr('src') && img.data('lazysrc')!=undefined && img.data('lazysrc')!='undefined') {

			if (img.data('lazysrc') !=undefined && img.data('lazysrc') !='undefined')
				img.attr('src',img.data('lazysrc'));
		}

		img.data('preloading',1);

		limg.onload = function(loadedimg) {			
			img.data('lazydone',1);
			img.data('ww',limg.width);
			img.data('hh',limg.height);			
			img.closest('.showmeonload').addClass("itemtoshow").removeClass("showmeonload").addClass("loadedmedia");
			removeLLCover(img,limg.width,limg.height);
			
			/* 2.1.6 */
			loadHoverImage(img);	
			if (opt.lazyLoad=="on") waittorungGrid(img,opt,true,inited);

		};

		limg.onerror = function() {
				img.data('lazydone',1);
				img.closest('.showmeonload').addClass("itemtoshow").removeClass("showmeonload").addClass("loadedmedia");
				if (opt.lazyLoad=="on") waittorungGrid(img,opt,true,inited);

			};

		if (img.attr('src')!=undefined && img.attr('src')!='undefined') {
			limg.src = img.attr('src');
		}
		/* 2.1.6  */
		else if(img.data('src')!=undefined && img.data('src')!='undefined') {
			limg.src = img.data('src');
		}
		
		if (limg.complete) {
			img.data('lazydone',1);
			img.data('ww',limg.width);
			img.data('hh',limg.height);
			img.closest('.showmeonload').addClass("itemtoshow").removeClass("showmeonload").addClass("loadedmedia");
			removeLLCover(img,limg.width,limg.height);
			
			if (opt.lazyLoad=="on") waittorungGrid(img,opt,true,inited);

		}


}

/******************************
	-	WAIT FOR PRELOADS	-
********************************/

var waitForLoads = function(elements,opt) {
	
	if(opt.adamlabsgalleryloaderprocess!=="add") {
	
		elements.each(function() {
			
			if(!jQuery(this).hasClass("isvisiblenow")) {	
				opt.adamlabsgalleryloaderprocess = "add";
				adamlabsgallerygs.TweenLite.to(opt.adamlabsgalleryloader,0.5,{autoAlpha:1,ease:adamlabsgallerygs.Power3.easeInOut});
				return false;
			}
			
		});
		
	}
	
	var inter,
		found,
		inited = {init: false};
		
	function onPoster(i) {
		var img = jQuery(this),
			imgsrc = img.attr('src'),
			ip =img.parent();

		//img.css({display:"none"});
		if (img.data('lazydone')!=1 && opt.lazyLoad=="on" && ip.find('.lazyloadcover').length<1) {
			lthumb = img.data('lazythumb');			 		
			var	bgimg = "",
				blurclass ="";
				
			/* 2.1.6 */
			if (lthumb!=undefined && lthumb !== 'undefined') {
				imgsrc = img.data('lazysrc');
				bgimg = ";background-image:url("+lthumb+")";
				blurclass="adamlabsgallery-lazyblur";
			}			 		
			
			/* 2.1.6 colorpicker addition */
			if(!bgimg) bgimg = opt.lazyLoadColor;
			ip.append('<div class="lazyloadcover '+blurclass+'" style="background:'+bgimg+'"></div>');
			
			
		} 
		
		if (img.data('lazydone')!=1 && found<3) {			 		
			found++;
			loadAllPrepared(jQuery(this),opt,inited);
		}
		
		if (opt.lazyLoad!=="on") {
			adamlabsgallerygs.TweenLite.set(img,{autoAlpha:1});
		}

	 }
		
	function onEach() {

		opt.bannertimeronpause = true;
		opt.cd=0;			
		found = 0;
		
		elements.find('.adamlabsgallery-media-poster').each(onPoster);

		 if (found==0) {
			  if (opt.adamlabsgalleryloader.length>0 && opt.adamlabsgalleryloaderprocess!=="remove") {
				opt.adamlabsgalleryloaderprocess = "remove";
				var infdelay = 0;
				if (opt.adamlabsgalleryloader.hasClass("infinityscollavailable"))
					infdelay = 1;

				adamlabsgallerygs.TweenLite.to(opt.adamlabsgalleryloader,0.5,{autoAlpha:0, ease:adamlabsgallerygs.Power3.easeInOut, delay:infdelay});
			 }
		 }
		 if (found==0 && !elements.closest('.mainul').hasClass("gridorganising")) {
			 clearInterval(inter);	
			 if(!inited.init) runGrid(opt,false,inited);
		 }			 
	}
	
	inter = setInterval(onEach ,50);
	
	// 2.2.5
	// runGrid(opt);

};



 /******************************
 	-	ORGANISE GRID	-
 ********************************/
 function organiseGrid(opt,fromwhere) { 		 	
	waitForLoads(opt.container.find('.itemtoshow').not('.skipblank'),opt);
 }


function removeLLCover(img,imgw,imgh) {
	var ip = img.parent();
	
	setMediaEntryAspectRatio({ip:ip,img:img,imgw:imgw,imgh:imgh});
	if (!img.hasClass("coverremoved") && ip.find('.lazyloadcover').length>0) {
		img.addClass("coverremoved");			
		adamlabsgallerygs.TweenLite.set(ip.find('.lazyloadcover'),{zIndex:0});
		adamlabsgallerygs.TweenLite.fromTo(img,0.5,{autoAlpha:0,zIndex:1},{force3D:true, autoAlpha:1,ease:adamlabsgallerygs.Power1.easeInOut,onComplete:function() {
			img.parent().find('.lazyloadcover').remove();			
		}});
	} else
	if (opt.lazyLoad=="off") {
		//adamlabsgallerygs.TweenLite.fromTo(img,0.5,{autoAlpha:0,zIndex:1},{force3D:true, autoAlpha:1,ease:adamlabsgallerygs.Power1.easeInOut});
		adamlabsgallerygs.TweenLite.set(img,{force3D:true, autoAlpha:1});
	}
}


/***********************************************
	-	Run Grid To Prepare for Animation	-
************************************************/
function runGrid(opt,newelementadded,inited) {		
		
		inited.init = true;
		
		var  container = opt.container;			 
		if (opt.loadMoreType=="scroll") checkBottomPos(opt);

		if (opt.firstshowever==undefined) {
			if (container.is(":hidden"))
				adamlabsgallerygs.TweenLite.set(container,{autoAlpha:1,display:"block"});
			runGridMain(opt,newelementadded);
			jQuery(opt.filterGroupClass+'.adamlabsgallery-navigationbutton, '+opt.filterGroupClass+' .adamlabsgallery-navigationbutton').css({visibility:"visible"});
			
			//adamlabsgallerygs.TweenLite.to(opt.adamlabsgalleryloader,0.2,{autoAlpha:0});
			opt.firstshowever = 1;

		} else {
			runGridMain(opt,newelementadded);
			jQuery(opt.filterGroupClass+'.adamlabsgallery-navigationbutton, '+opt.filterGroupClass+' .adamlabsgallery-navigationbutton').css({visibility:"visible"});
		}

}

/**********************************
	-	GET THE COBBLES PATTERN	-
***********************************/

function getCobblePat(ar,index) {
	var cobblevalue = {};
	cobblevalue.w = 1;
	cobblevalue.h = 1;
	ar = ar.split(",");
	if (ar!=undefined) {
			ar = ar[index - (Math.floor(index/(ar.length)) * (ar.length))].split("x");
			cobblevalue.w = ar[0];
			cobblevalue.h = ar[1];
	}
	return cobblevalue;
}



/************************************************
	-	//! RUN THE GRID POSITION CALCULATION	-
*************************************************/
function runGridMain(opt,newelementadded) {
	
	// BASIC VARIABLES
 	var  container = opt.container,
 		 items = !opt.itemstoload ? container.find('.itemtoshow, .isvisiblenow').not('.ui-sortable-helper') : opt.itemstoload,
 	 	 p = {},
	 	 ul = container.find('ul').first(),
	 	 /* adamlabsgalleryo = container.find('.adamlabsgallery-overflowtrick').first(), */
	 	 ar = opt.aspectratio,
	 	 aratio,
	 	 coh = 0;

	 	opt.aspectratioOrig = opt.aspectratio;
	
	items = items.not('.skipblank');
		
	delete opt.itemstoload;
	container.find('.mainul').addClass("gridorganising");
	// CALCULATE THE ASPECT RATIO


	ar = ar.split(":");
 	aratio=parseInt(ar[0],0) / parseInt(ar[1],0);

	p.item = 0;
	p.pagetoanimate=0-opt.currentpage;			// Page Offsets
	p.col=0;									// Current Col
	p.row=0;									// Current Row
	p.pagecounter=0;							// Counter
	p.itemcounter=0;

	p.fakecol=0;
	p.fakerow=0;
	p.maxheight=0;

	p.allcol =0;
	p.allrow = 0;
	p.ulcurheight = 0;
	p.ulwidth = ul.width();

	p.verticalsteps = 1;




	p.currentcolumnheight = [];
	for (var i=0;i<opt.column;i++)
		p.currentcolumnheight[i] = 0;

	p.pageitemcounterfake=0;
	p.pageitemcounter=0;

	// GET DELAY BASIC
	if (opt.delayBasic!=undefined)
		p.delaybasic = opt.delayBasic;
	else
		p.delaybasic = 0.08;


	p.anim = opt.pageAnimation;

	p.itemtowait=0;
	p.itemouttowait=0;

	p.ease = "adamlabsgallerygs.Power1.easeInOut";
	p.easeout = p.ease;
	p.row=0;
	p.col=0;

	// MULTIPLIER SETTINGS
	var mp = opt.rowItemMultiplier,
		mpl = mp.length;
		/* origcol = opt.column; */


	p.y = 0;
	p.fakey = 0;
	var overflowtrick = container.find('.adamlabsgallery-overflowtrick').css('width',"100%");
	if (overflowtrick.width()==100)
		overflowtrick.css({width:overflowtrick.parent().width()});
	p.cwidth = overflowtrick.width()-(opt.overflowoffset*2);			// Current Width of Parrent Container

	opt.inanimation = true;

	p.cwidth_n_spaces = p.cwidth -  ((opt.column-1)*opt.space);

	p.itemw = Math.round(p.cwidth_n_spaces/opt.column);	// Current Item Width in PX
	p.originalitemw = p.itemw;

	var forceAR = false;


	// CHANGE ASPECT RATIO IF FULLSCREEN IS SET
	if (opt.forceFullScreen=="on") {
		coh = jQuery(window).height();
		if (opt.fullScreenOffsetContainer!=undefined) {
			try{
				var offcontainers = opt.fullScreenOffsetContainer.split(",");
				jQuery.each(offcontainers,function(index,searchedcont) {
					coh = coh - jQuery(searchedcont).outerHeight(true);
					if (coh<opt.minFullScreenHeight) coh=opt.minFullScreenHeight;
				});
			} catch(e) {}
		}
		forceAR = true;
	}



	if (opt.layout=="even") {

			p.itemh = Math.round(coh) == 0 ? Math.round((p.cwidth_n_spaces / opt.column) / aratio) : Math.round(coh/opt.row);
			opt.aspectratio = coh == 0 ? opt.aspectratio : p.itemw+":"+p.itemh;
			
			if (mpl>0) {
				adamlabsgallerygs.TweenLite.set(items,{display:"block",visibility:"visible",overwrite:"auto"});
			}
			else if (opt.evenCobbles=="on") {
				adamlabsgallerygs.TweenLite.set(items,{display:"block",visibility:"visible",overwrite:"auto"});
			}
			else {
				adamlabsgallerygs.TweenLite.set(items,{display:"block",width:p.itemw,height:p.itemh,visibility:"visible",overwrite:"auto"});
			}
	} else {		
		adamlabsgallerygs.TweenLite.set(items,{display:"block",width:p.itemw,height:"auto",visibility:"visible",overwrite:"auto"});
	}
	if (!newelementadded)  {

		adamlabsgallerygs.TweenLite.killTweensOf(items);
	}

	p.originalitemh = p.itemh;

	// PREPARE A GRID FOR CALCULATE THE POSITIONS OF COBBLES
	var thegrid = [],
		maxcobblerow = opt.row*opt.column*2;

	for (var rrr = 0 ; rrr<maxcobblerow; rrr++) {
		var newrow = [];
		for (var ccc = 0; ccc<opt.column;ccc++) {
				newrow.push(0);
		}
		thegrid.push(newrow);
	}

	var cobblepatternindex = 0;

	if (items.length==0) container.trigger('itemsinposition');
	// REPARSE THE ITEMS TO MAKE
	
 	jQuery.each(items,function(index,$item) {
		var item = jQuery($item),
			multi,
			eem;
			
		p.itemw = 	p.originalitemw;

		//fixCenteredCoverElement(item);


		adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-content'),{minHeight:opt.mmHeight+"px"});

		//! COBBLES
		if (opt.evenCobbles == "on" && !item.hasClass("itemonotherpage") && !item.hasClass("itemishidden")) {
				var cobblesw = item.data('cobblesw'),
					cobblesh = item.data('cobblesh');

				if (opt.cobblesPattern!=undefined && opt.cobblesPattern.length>2) {

					var newcobblevalues =  getCobblePat(opt.cobblesPattern,cobblepatternindex);
					cobblesw = parseInt(newcobblevalues.w,0);
					cobblesh = parseInt(newcobblevalues.h,0);
					cobblepatternindex++;
				}


				cobblesw = cobblesw==undefined ? 1 : cobblesw;
				cobblesh = cobblesh==undefined ? 1 : cobblesh;


				if (opt.column < cobblesw) cobblesw = opt.column;

				p.cobblesorigw = p.originalitemw;
				p.cobblesorigh = p.originalitemh;
				p.itemw = p.itemw * cobblesw + ((cobblesw-1) * opt.space);
				p.itemh =  p.originalitemh;

				p.itemh = p.itemh * cobblesh + ((cobblesh-1) * opt.space);

				var cobblepattern = cobblesw+":"+cobblesh,
					spacefound = false,
					r = 0,
					c = 0;
					
				switch (cobblepattern) {
								case "1:1":
									do {
										if (thegrid[r][c]==0) {
											thegrid[r][c] = "1:1";
											spacefound = true;
											p.cobblesx = c;
											p.cobblesy = r;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;



								case "1:2":
									do {
										if (thegrid[r][c]==0 && r<maxcobblerow-1 && thegrid[r+1][c]==0) {
											thegrid[r][c] = "1:2";
											thegrid[r+1][c] = "1:2";
											p.cobblesx = c;
											p.cobblesy = r;

											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;

								case "1:3":
									do {
										if (thegrid[r][c]==0 && r<maxcobblerow-2 && thegrid[r+1][c]==0 && thegrid[r+2][c]==0) {
											thegrid[r][c] = "1:3";
											thegrid[r+1][c] = "1:3";
											thegrid[r+2][c] = "1:3";
											p.cobblesx = c;
											p.cobblesy = r;

											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;
								
								case "1:4":
									do {
										if (thegrid[r][c]==0 && r<maxcobblerow-3 && thegrid[r+1][c]==0 && thegrid[r+2][c]==0 && thegrid[r+3][c]==0) {
											thegrid[r][c] = "1:4";
											thegrid[r+1][c] = "1:4";
											thegrid[r+2][c] = "1:4";
											thegrid[r+3][c] = "1:4";
											p.cobblesx = c;
											p.cobblesy = r;

											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;


								case "2:1":
									do {
										if (thegrid[r][c]==0 && c<opt.column-1 && thegrid[r][c+1]==0) {
											thegrid[r][c] = "2:1";
											thegrid[r][c+1] = "2:1";
											p.cobblesx = c;
											p.cobblesy = r;
											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;

								case "3:1":
									do {
										if (thegrid[r][c]==0 && c<opt.column-2 && thegrid[r][c+1]==0 && thegrid[r][c+2]==0) {
											thegrid[r][c] = "3:1";
											thegrid[r][c+1] = "3:1";
											thegrid[r][c+2] = "3:1";
											p.cobblesx = c;
											p.cobblesy = r;
											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;
								
								case "4:1":
									do {
										if (thegrid[r][c]==0 && c<opt.column-3 && thegrid[r][c+1]==0 && thegrid[r][c+2]==0 && thegrid[r][c+3]==0) {
											thegrid[r][c] = "4:1";
											thegrid[r][c+1] = "4:1";
											thegrid[r][c+2] = "4:1";
											thegrid[r][c+3] = "4:1";
											p.cobblesx = c;
											p.cobblesy = r;
											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;

								case "2:2":
									do {
										if (c<opt.column-1 && r<maxcobblerow-1 && thegrid[r][c]==0 && thegrid[r][c+1]==0 && thegrid[r+1][c]==0 && thegrid[r+1][c+1]==0) {
											thegrid[r][c] = "2:2";
											thegrid[r+1][c] = "2:2";
											thegrid[r][c+1] = "2:2";
											thegrid[r+1][c+1] = "2:2";

											p.cobblesx = c;
											p.cobblesy = r;

											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;

								case "3:2":
									do {
										if (c<opt.column-2 && r<maxcobblerow-1 && thegrid[r][c]==0 && thegrid[r][c+1]==0 && thegrid[r][c+2]==0 && thegrid[r+1][c]==0 && thegrid[r+1][c+1]==0 && thegrid[r+1][c+2]==0) {

											thegrid[r][c] = "3:2";
											thegrid[r][c+1] = "3:2";
											thegrid[r][c+2] = "3:2";
											thegrid[r+1][c] = "3:2";
											thegrid[r+1][c+1] = "3:2";
											thegrid[r+1][c+2] = "3:2";

											p.cobblesx = c;
											p.cobblesy = r;

											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;
								
								case "4:2":
									do {
										if (c<opt.column-3 && r<maxcobblerow-1 && thegrid[r][c]==0 && thegrid[r][c+1]==0 && thegrid[r][c+2]==0 && thegrid[r][c+3]==0 && thegrid[r+1][c]==0  && thegrid[r+1][c+1]==0 && thegrid[r+1][c+2]==0 && thegrid[r+1][c+3]==0) {

											thegrid[r][c] = "4:2";
											thegrid[r][c+1] = "4:2";
											thegrid[r][c+2] = "4:2";
											thegrid[r][c+3] = "4:2";
											thegrid[r+1][c] = "4:2";
											thegrid[r+1][c+1] = "4:2";
											thegrid[r+1][c+2] = "4:2";
											thegrid[r+1][c+3] = "4:2";

											p.cobblesx = c;
											p.cobblesy = r;

											spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;

								case "2:3":
									do {
										if (c<opt.column-1 && r<maxcobblerow-2 &&
											thegrid[r][c]==0 &&
											thegrid[r][c+1]==0 &&
											thegrid[r+1][c]==0 &&
											thegrid[r+1][c+1]==0  &&
											thegrid[r+2][c+1]==0 &&
											thegrid[r+2][c+1]==0

											)
										{

												thegrid[r][c] = "2:3";
												thegrid[r][c+1] = "2:3";
												thegrid[r+1][c] = "2:3";
												thegrid[r+1][c+1] = "2:3";
												thegrid[r+2][c] = "2:3";
												thegrid[r+2][c+1] = "2:3";

												p.cobblesx = c;
												p.cobblesy = r;

												spacefound = true;

										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;
								
								case "2:4":
									do {
										if (c<opt.column-1 && r<maxcobblerow-3 &&
											thegrid[r][c]==0 &&
											thegrid[r][c+1]==0 &&
											thegrid[r+1][c]==0 &&
											thegrid[r+1][c+1]==0  &&
											thegrid[r+2][c+1]==0 &&
											thegrid[r+2][c+1]==0 && 
											thegrid[r+3][c+1]==0 &&
											thegrid[r+3][c+1]==0

											)
										{

												thegrid[r][c] = "2:4";
												thegrid[r][c+1] = "2:4";
												thegrid[r+1][c] = "2:4";
												thegrid[r+1][c+1] = "2:4";
												thegrid[r+2][c] = "2:4";
												thegrid[r+2][c+1] = "2:4";
												thegrid[r+3][c] = "2:4";
												thegrid[r+3][c+1] = "2:4";

												p.cobblesx = c;
												p.cobblesy = r;

												spacefound = true;

										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;

								case "3:3":
									do {
										if (c<opt.column-2 && r<maxcobblerow-2 &&
											thegrid[r][c]==0 &&
											thegrid[r][c+1]==0 &&
											thegrid[r][c+2]==0 &&
											thegrid[r+1][c]==0  &&
											thegrid[r+1][c+1]==0 &&
											thegrid[r+1][c+2]==0 &&
											thegrid[r+2][c]==0  &&
											thegrid[r+2][c+1]==0 &&
											thegrid[r+2][c+2]==0

											)
										{

												thegrid[r][c] = "3:3";
												thegrid[r][c+1] = "3:3";
												thegrid[r][c+2] = "3:3";
												thegrid[r+1][c] = "3:3";
												thegrid[r+1][c+1] = "3:3";
												thegrid[r+1][c+2] = "3:3";
												thegrid[r+2][c] = "3:3";
												thegrid[r+2][c+1] = "3:3";
												thegrid[r+2][c+2] = "3:3";

												p.cobblesx = c;
												p.cobblesy = r;

												spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;
								
								case "4:3":
									do {
										if (c<opt.column-3 && r<maxcobblerow-2 &&
											thegrid[r][c]==0 &&
											thegrid[r][c+1]==0 &&
											thegrid[r][c+2]==0 &&
											thegrid[r][c+3]==0 &&
											thegrid[r+1][c]==0  &&
											thegrid[r+1][c+1]==0 &&
											thegrid[r+1][c+2]==0 &&
											thegrid[r+1][c+3]==0 &&
											thegrid[r+2][c]==0  &&
											thegrid[r+2][c+1]==0 &&
											thegrid[r+2][c+2]==0 && 
											thegrid[r+2][c+3]==0

											)
										{

												thegrid[r][c] = "4:3";
												thegrid[r][c+1] = "4:3";
												thegrid[r][c+2] = "4:3";
												thegrid[r][c+3] = "4:3";
												thegrid[r+1][c] = "4:3";
												thegrid[r+1][c+1] = "4:3";
												thegrid[r+1][c+2] = "4:3";
												thegrid[r+1][c+3] = "4:3";
												thegrid[r+2][c] = "4:3";
												thegrid[r+2][c+1] = "4:3";
												thegrid[r+2][c+2] = "4:3";
												thegrid[r+2][c+3] = "4:3";

												p.cobblesx = c;
												p.cobblesy = r;

												spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;
								
								case "3:4":
									do {
										if (c<opt.column-2 && r<maxcobblerow-3 &&
											thegrid[r][c]==0 &&
											thegrid[r][c+1]==0 &&
											thegrid[r][c+2]==0 &&
											thegrid[r+1][c]==0  &&
											thegrid[r+1][c+1]==0 &&
											thegrid[r+1][c+2]==0 &&
											thegrid[r+2][c]==0  &&
											thegrid[r+2][c+1]==0 &&
											thegrid[r+2][c+2]==0 &&
											thegrid[r+3][c]==0  &&
											thegrid[r+3][c+1]==0 &&
											thegrid[r+3][c+2]==0

											)
										{

												thegrid[r][c] = "3:4";
												thegrid[r][c+1] = "3:4";
												thegrid[r][c+2] = "3:4";
												thegrid[r+1][c] = "3:4";
												thegrid[r+1][c+1] = "3:4";
												thegrid[r+1][c+2] = "3:4";
												thegrid[r+2][c] = "3:4";
												thegrid[r+2][c+1] = "3:4";
												thegrid[r+2][c+2] = "3:4";
												thegrid[r+3][c] = "3:4";
												thegrid[r+3][c+1] = "3:4";
												thegrid[r+3][c+2] = "3:4";

												p.cobblesx = c;
												p.cobblesy = r;

												spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;
								
								case "4:4":
									do {
										if (c<opt.column-3 && r<maxcobblerow-3 &&
											thegrid[r][c]==0 &&
											thegrid[r][c+1]==0 &&
											thegrid[r][c+2]==0 &&
											thegrid[r][c+3]==0 &&
											thegrid[r+1][c]==0  &&
											thegrid[r+1][c+1]==0 &&
											thegrid[r+1][c+2]==0 &&
											thegrid[r+1][c+3]==0 &&
											thegrid[r+2][c]==0  &&
											thegrid[r+2][c+1]==0 &&
											thegrid[r+2][c+2]==0 &&
											thegrid[r+2][c+3]==0 && 
											thegrid[r+3][c]==0  &&
											thegrid[r+3][c+1]==0 &&
											thegrid[r+3][c+2]==0 &&
											thegrid[r+3][c+3]==0

											)
										{

												thegrid[r][c] = "4:4";
												thegrid[r][c+1] = "4:4";
												thegrid[r][c+2] = "4:4";
												thegrid[r][c+3] = "4:4";
												thegrid[r+1][c] = "4:4";
												thegrid[r+1][c+1] = "4:4";
												thegrid[r+1][c+2] = "4:4";
												thegrid[r+1][c+3] = "4:4";
												thegrid[r+2][c] = "4:4";
												thegrid[r+2][c+1] = "4:4";
												thegrid[r+2][c+2] = "4:4";
												thegrid[r+2][c+3] = "4:4";
												thegrid[r+3][c] = "4:4";
												thegrid[r+3][c+1] = "4:4";
												thegrid[r+3][c+2] = "4:4";
												thegrid[r+3][c+3] = "4:4";

												p.cobblesx = c;
												p.cobblesy = r;

												spacefound = true;
										}
										c++;
										if (c==opt.column) {
											c=0;r++;
										}
										if (r>=maxcobblerow) spacefound= true;
									} while (!spacefound);
								break;
				}

				opt.aspectratio = p.itemw+":"+p.itemh;

				adamlabsgallerygs.TweenLite.set(item,{width:p.itemw,height:p.itemh,overwrite:"auto"});
				eem = item.find('.adamlabsgallery-entry-media');
				multi = (p.itemh/p.itemw)*100; 				
				adamlabsgallerygs.TweenLite.set(eem,{paddingBottom:multi+"%"});

		} else {

				//IF ITEMW IS DIFFERENT BASED ON MULTIPLIER, WE NEED TO RESET SIZES
				var cle = p.row - (mpl*Math.floor(p.row/mpl));

				if (opt.layout=="even" && mpl>0) {
					/*if (origcol!=1)*/ opt.column = mp[cle][opt.columnindex];
					p.cwidth = container.find('.adamlabsgallery-overflowtrick').width()-(opt.overflowoffset*2);			// Current Width of Parrent Container
					p.cwidth_n_spaces = p.cwidth -  ((opt.column-1)*opt.space);
					p.itemw = Math.round(p.cwidth_n_spaces/opt.column);	// Current Item Width in PX

					p.itemh = coh == 0 ? (p.cwidth_n_spaces / opt.column) / aratio : coh/opt.row;
					opt.aspectratio = coh == 0 ? opt.aspectratio : p.itemw+":"+p.itemh;

					adamlabsgallerygs.TweenLite.set(item,{width:p.itemw,height:p.itemh,overwrite:"auto"});			// KRIKI KRIKI

				}// END OF MULTIPLIER CALCULATION#

				// RESET ASPECT RATIO IF FULLSCREEN ASPECT RATIO HAS BEEN CHANGED
				if (forceAR) {
					eem = item.find('.adamlabsgallery-entry-media');
					multi = (p.itemh/p.itemw)*100; 				
					//adamlabsgallerygs.TweenLite.set(eem,{paddingBottom:multi+"%"});
				}
		}


		if (opt.layout!=="even") {

			if (item.hasClass("itemtoshow"))
				if (item.width() != p.itemw || item.css("opacity")==0 || item.css("visibility")=="hidden")
						p = prepareItemToMessure(item,p,container);
				else {

					adjustMediaSize(item,true,p,opt);
					p.itemh = item.height();

				}
		    else {

		    	adjustMediaSize(item,true,p,opt);
		    	p.itemh = item.height();
		    }


		}

		//adjustMediaSize(item,true,p);

		p = animateGrid(item, opt, p, index);
		p.itemcounter++;
		
		if (ul.height()<p.maxheight) container.trigger('itemsinposition');



	});

	opt.aspectratio = opt.aspectratioOrig;

	// 2.2.5
	if(opt.hideMarkups === 'off' || p.itemtowait==0) {
		// this is already called below
		// opt.container.trigger('itemsinposition');
		container.find('.mainul').removeClass("gridorganising");
	}

	var gbfc = getBestFitColumn(opt,jQuery(window).width(),"id");
	opt.column = gbfc.column;
	opt.columnindex = gbfc.index;
	opt.mmHeight = gbfc.mmHeight;

	opt.maxheight = p.maxheight;
	opt.container.trigger('itemsinposition');
	opt.inanimation = true;

	// RESET FILTER AND STARTER VALUES
	opt.started = false;
	opt.filterchanged=false;
	opt.silent=false;
	opt.silentout=false;
	opt.changedAnim = "";
	setOptions(container,opt);
	
	if (opt.adamlabsgalleryloader.length>0 && opt.adamlabsgalleryloaderprocess != "remove") {
		opt.adamlabsgalleryloaderprocess = "remove";
		var infdelay = 0;

		if (opt.adamlabsgalleryloader.hasClass("infinityscollavailable"))
		 	infdelay = 1;		
		adamlabsgallerygs.TweenLite.to(opt.adamlabsgalleryloader,1,{autoAlpha:0,ease:adamlabsgallerygs.Power3.easeInOut,delay:infdelay});
	}

	opt.fromResize = false;
	if(!opt.inViewport) jQuery(window).trigger('resize.adamlabsgalleryviewport');
	
	
 }

/***************************************
	-	Prepare Item for Messure	-
***************************************/

function prepareItemToMessure(item,p,container) {

//		adamlabsgallerygs.TweenLite.set(item,{width:p.itemw,height:"auto",visibility:"visible"});
		adjustMediaSize(item,true,p,container.data('opt'));
	 	p.itemh = item.outerHeight(true);
		return p;
	}



/*****************************************
	-	GRID ANIMATOR -
*****************************************/
function animateGrid(item, opt, p, delayIndex) {
	
	// Basics
	// var item= jQuery($item);
		/* samepageanims =   ["fade","scale","vertical-flip","horizontal-flip","vertical-flipbook","horizontal-flipbook","fall","rotatefall","rotatescale","stack"], */
		/* horizontalanims = ["horizontal-slide","horizontal-harmonica"], */
		/* verticalalanims = ["vertical-slide","vertical-harmonica"]; */





	p.skipanim = false;
	p.x= Math.round(p.col*p.itemw);


	// CALCULATE THE POSITIONS

	if (opt.layout=="even") {
//		p.y = (Math.round(p.itemh)*p.row);
	} else {
		p.idealcol = 0;
		p.backupcol = p.col;
		for (var i=0;i<opt.column;i++)
		  if (p.currentcolumnheight[p.idealcol]>p.currentcolumnheight[i])
		    p.idealcol = i;

		p.y = p.currentcolumnheight[p.idealcol];

		p.x= Math.round(p.idealcol*p.itemw) + p.idealcol * opt.space;

		p.col=p.idealcol;


		if (p.itemh==undefined) p.itemh=0;

	}

	if (p.cobblesx != undefined) {
		p.x = p.cobblesx * p.cobblesorigw;
		p.y = p.cobblesy * p.cobblesorigh;
	}

	// 2.2.5
	if(p.anim !== 'rotatefall') {
		
		var loadNum = item.data('adamlabsgallery-load-more-new');
		if(isNaN(loadNum)) {
		
			p.waits = opt.animationType === 'item' ? p.col*(p.delaybasic)+p.row*(p.delaybasic*opt.column) : 
					  opt.animationType === 'col' ? p.col*(p.delaybasic) : 
					  p.row*(p.delaybasic*opt.column);
					
		}
		else {
			
			if(parseInt(loadNum, 10) === 0) opt.loadStartRow = p.row;
			
			p.waits = opt.animationType === 'item' ? loadNum * p.delaybasic : 
					  opt.animationType === 'col' ? p.col * p.delaybasic : 
					  (p.row - opt.loadStartRow) * (p.delaybasic * opt.column);
					  
			item.removeData('adamlabsgallery-load-more-new');
			
		}
				  
	}
	else {
		p.waits = p.col*(p.delaybasic)+p.row*(p.delaybasic*opt.column);
	}

	p.speed=opt.animSpeed;
	p.inxrot =0;
	p.inyrot =0;
	p.outxrot=0;
	p.outyrot=0;
	p.inorigin="center center";
	p.outorigin="center center";
	p.itemh = Math.round(p.itemh);
	p.scale=1;

	p.outfade=0;
	p.infade=0;


	/**************************************
		-	THE FADE OVER ANIMATIONS	-
	***************************************/
	if (item.hasClass("itemonotherpage") || item.hasClass('skipblank')) {
		p.skipanim = true;
	}
	/* 2.2.5 */
	else if((!opt.firstLoadFinnished && opt.startAnimation) || !opt.inViewport) {
		
		var startSpeed = opt.startAnimationSpeed ? opt.startAnimationSpeed : 0,
			startDelay = opt.startAnimationDelay ? opt.startAnimationDelay : 0;
			
		p.anim = opt.startAnimation;
		if(p.anim !== 'none') {
			
			p.speed = startSpeed ? startSpeed * 0.001 : 0;
			p.waits = startDelay ? p.anim !== 'reveal' ? startDelay / 100 : 0 : 0;
			
		}
		else {
			
			p.speed = 0;
			p.waits = 0;
			
		}
		
		if(p.waits) p.waits *= opt.startAnimationType === 'item' ? delayIndex : p[opt.startAnimationType];
		
	}

	/**************************************
		-	THE SLIDE ANIMATIONS	-
	***************************************/
	if (p.anim == "horizontal-slide") {
		p.waits=0;

		p.hsoffset = 0-p.cwidth-parseInt(opt.space);
		p.hsoffsetout = 0-p.cwidth-parseInt(opt.space);


		if (opt.oldpage!=undefined && opt.oldpage>opt.currentpage) {
			p.hsoffset = p.cwidth+parseInt(opt.space);
			p.hsoffsetout = p.cwidth+parseInt(opt.space);

		}


	} else

	if (p.anim == "vertical-slide") {
		p.waits=0;
		p.maxcalcheight = (opt.row * opt.space) + (opt.row*p.itemh);

		p.vsoffset = p.maxcalcheight+parseInt(opt.space);
		p.vsoffsetout = p.maxcalcheight+parseInt(opt.space);

		if (opt.oldpage!=undefined && opt.oldpage>opt.currentpage) {
			p.vsoffset = 0-p.maxcalcheight-parseInt(opt.space);
			p.vsoffsetout = 0-p.maxcalcheight-parseInt(opt.space);
		}
	}
	
	// 2.2.5
	if(opt.fromResize) p.waits = 0;

	// MAKE IN AND OUTWAITS EQUAL FOR THIS MOMENT
	p.outwaits = p.waits;


	// SPACE CORRECTIONS
	if (opt.layout=="even" && p.cobblesx == undefined)  {
			p.x= p.x + p.col * opt.space;
			//p.y = p.y + p.row*opt.space;
	}

	if (p.cobblesx != undefined) {

		p.x = p.x + p.cobblesx * opt.space;
		p.y = p.y + p.cobblesy * opt.space;
	}


	/********************************
		-	FLIPS && flipbookS	-
	*********************************/

    if (p.anim == "vertical-flip" || p.anim == "horizontal-flip" || p.anim == "vertical-flipbook" || p.anim == "horizontal-flipbook")
    	p=fakePositions(item,p,opt);



	/******************************
		-	FLIP VALUES 	-
	********************************/

	if (p.anim == "vertical-flip") {
		p.inxrot = -180;
		p.outxrot = 180;
	}
	else

	if (p.anim == "horizontal-flip") {
		p.inyrot = -180;
		p.outyrot = 180;

	}

	// EVEN SPEEDS
	p.outspeed=p.speed;

	if (opt.animDelay=="off") {
		p.waits=0;
		p.outwaits=0;
	}

	/******************************
		-	SCALES	-
	********************************/

	if (p.anim=="scale") {
		p.scale =0;
	}

	else

	/******************************
		-	flipbook VALUES	-
	********************************/

	if (p.anim == "vertical-flipbook") {
		p.inxrot = -90;
		p.outxrot = 90;
		p.inorigin = "center top";
		p.outorigin = "center bottom";
		p.waits = p.waits+p.speed/3;
		p.outfade=1;
		p.infade=1;
		p.outspeed=p.speed/1.2;
		p.ease = "Sine.easeOut";
		p.easeout = "Sine.easeIn";

		if (opt.animDelay=="off") {
			p.waits=p.speed/3;
			p.outwaits=0;
		}
	}

	else

	if (p.anim == "horizontal-flipbook") {
		p.inyrot = -90;
		p.outyrot = -90;
		p.inorigin = "left center";
		p.outorigin = "right center";
		p.waits = p.waits+p.speed/2.4;
		p.outfade=1;
		p.infade=1;
		p.outspeed=p.speed/1.2;
		p.ease = "Sine.easeOut";
		p.easeout = "Sine.easeIn";
		if (opt.animDelay=="off") {
			p.waits=p.speed/3;
			p.outwaits=0;
		}

	}

	else

	/******************************
		-	FALL ANIMATION	-
	********************************/

	if (p.anim == "fall" || p.anim== "rotatefall") {
		p.outoffsety = 100;
		p=fakePositions(item,p,opt);
		p.outfade=0;
	}



	if (p.anim=="rotatefall") {
		p.rotatez = 20;
		p.outorigin = "left top";
		p.outfade=1;
		p.outoffsety = 600;
	}

	else

	/******************************
		-	ROTATESCALE	-
	********************************/
	
	if (p.anim== "rotatescale") {
		p.scale=0;
		p.inorigin = "left bottom";
		p.outorigin = "center center";
		p.faeout =1;
		p.outoffsety = 100;
		p=fakePositions(item,p,opt);
	}

	else

	/******************************
		-	STACK	-
	********************************/

	if (p.anim== "stack") {
		p.scale=0;
		p.inorigin = "center center";
		p.faeout =1;
		p.ease = "adamlabsgallerygs.Power3.easeOut";

		p=fakePositions(item,p,opt);
		p.ease="Back.easeOut";
	}


	/**********************************************
		-	DEPENDENCIES ON CHANGES AND FILTERS	-
	**********************************************/

	// IF ANIMATION SHOULD BE DONE IN SILENT, WITHOUT ANIMATION AT ALL
	if (opt.silent) {
		p.waits=0;
		p.outwaits=0;
		p.speed=0;
		p.outspeed=0;

	}

	// IF ANIMATION OF OUTGOING ELEMENTS SHOULD BE SLIENT
	if (opt.silentout) {
		p.outwaits=0;
		p.outspeed=0.4;
		p.speed=0.4;
		p.ease="adamlabsgallerygs.Power3.easeOut";
		p.easeout=p.ease;

	}

	//p.waits=p.waits*1.15;
	//p.speed = p.speed + 0.5;

	//p.ease = adamlabsgallerygs.Power1.easeInOut;

	p.hooffset = opt.overflowoffset;
	p.vooffset = opt.overflowoffset;




	/******************************
		-	ANIMATION ITSELF	-
	********************************/



	if ((p.itemw+p.x-p.cwidth)<20 && (p.itemw+p.x-p.cwidth)>-20) {
		var dif = (p.itemw+p.x)-p.cwidth;
		p.itemw = p.itemw - dif;

	}


	if ((item.hasClass("itemtoshow") || item.hasClass("fitsinfilter")) && !p.skipanim) {
			item.addClass("isvisiblenow");

			if (opt.layout!="even") {
				p.currentcolumnheight[p.idealcol] = p.currentcolumnheight[p.idealcol] + p.itemh + parseInt(opt.space);
				if (p.ulcurheight<p.currentcolumnheight[p.idealcol]) {
					p.ulcurheight = p.currentcolumnheight[p.idealcol];

				}
			} else {
				p.ulcurheight = p.y + p.itemh;
			}

			if (p.maxheight<p.ulcurheight) {   //&& p.pagecounter<=opt.currentpage
				p.maxheight=p.ulcurheight;
			}



			p.itemtowait++;



			var localx = Math.round(p.hooffset+p.x);
			var localy = Math.round(p.vooffset+p.y);

			// RTL SUPPORT
			if (opt.rtl=="on")
				localx = (p.ulwidth-localx-p.itemw);

			// FADE OVER SPECIALS
			if (item.css("opacity")==0 && p.anim == "fade") {

				adamlabsgallerygs.TweenLite.set(item,{opacity:0,autoAlpha:0,width:p.itemw,height:p.itemh,scale:1,left:localx,y:0,top:localy,overwrite:"all"});

			} else

			// SCALE OVER SPECIALS
			if (item.css("opacity")==0 && p.anim == "scale") {
				adamlabsgallerygs.TweenLite.set(item,{width:p.itemw,height:p.itemh,scale:0,left:localx,y:0,top:localy,overwrite:"all"});
			} else

			// ROTATE SCALE ANIMATIONS
			if (item.css("opacity")==0 && p.anim == "rotatescale")
				adamlabsgallerygs.TweenLite.set(item,{width:p.itemw,height:p.itemh,scale:1,left:localx,top:localy,xPercent:+150,yPercent:+150,rotationZ:20,overwrite:"all"});

			else
			// THE FALL ANIMATION
			if (item.css("opacity")==0 && p.anim=="fall")
				adamlabsgallerygs.TweenLite.set(item,{width:p.itemw,height:p.itemh,scale:0.5,left:localx,top:localy,y:0,overwrite:"all"});

			else
			// THE ROTATEFALL ANIMATION
			if (item.css("opacity")==0 && p.anim=="rotatefall")
				adamlabsgallerygs.TweenLite.set(item,{autoAlpha:0,width:p.itemw,height:p.itemh,left:localx,rotationZ:0,top:localy,y:0,overwrite:"all"});





			// FADE OVER SPECIALS

			// PREPARING THE FLIPS
			if (item.css("opacity")==0 && (p.anim=="vertical-flip" || p.anim=="horizontal-flip" || p.anim=="vertical-flipbook" || p.anim=="horizontal-flipbook"))
				adamlabsgallerygs.TweenLite.set(item,{autoAlpha:p.infade,zIndex:10,scale:1,y:0,transformOrigin:p.inorigin, rotationX:p.inxrot,rotationY:p.inyrot,width:p.itemw,height:p.itemh,left:localx,top:localy,overwrite:"all"});

			// STACK ANIMATION
			if (p.anim=="stack")
				adamlabsgallerygs.TweenLite.set(item,{zIndex:p.pageitemcounter,scale:0.5,autoAlpha:1,left:localx,top:localy});

			// HORIZONTAL SLIDE ANIMATIONS
			if (p.anim=="horizontal-slide" && item.css("opacity")==0) {
				adamlabsgallerygs.TweenLite.set(item,{autoAlpha:1,left:Math.round(p.hooffset+(p.x-p.hsoffset)),top:localy, width:p.itemw, height:p.itemh});
			}

			// VERTICAL SLIDE ANIMATIONS
			if (p.anim=="vertical-slide" && item.css("opacity")==0)
				adamlabsgallerygs.TweenLite.set(item,{autoAlpha:1,left:localx,top:Math.round(p.vooffset+p.y-p.vsoffset), width:p.itemw, height:p.itemh});

			
			// 2.2.5
			// merge all layer hover transitions with start transitions
			var special,
				transition;
			
			if(p.anime !== 'none' && mergedTransitions.indexOf(p.anim) !== -1 && startAnimations.hasOwnProperty('adamlabsgallery-' + p.anim)) {
				
				transition = startAnimations['adamlabsgallery-' + p.anim];
				special = true;
				
				var transOptions =  jQuery.extend(true, {}, transition[1]);
				delete transOptions.autoAlpha;
				
				adamlabsgallerygs.TweenLite.set(item, {left: localx, top:localy, width: p.itemw, height: p.itemh});
				adamlabsgallerygs.TweenLite.set(item, transOptions);
				
			}


			//////////////////////////////////////////////////////////////
			// ANIMATE THE ITEMS IN THE GRID TO OPSITION AND VISIBILITY //
			//////////////////////////////////////////////////////////////


		   var ecc = item.find('.adamlabsgallery-entry-cover');
		   var media = item.find('.adamlabsgallery-entry-media');
		   if (ecc && media) {
			  var mh = media.outerHeight();
			  var cc = item.find('.adamlabsgallery-cc');
			  adamlabsgallerygs.TweenLite.to(ecc,0.01,{height:mh,ease:p.ease,delay:p.waits});
		      adamlabsgallerygs.TweenLite.to(cc,0.01,{top:((mh - cc.height()) / 2 ),ease:p.ease,delay:p.waits});
		   }

			opt.container.trigger('itemsinposition');
			
			function complete() {

				if (item.hasClass("itemtoshow")) {
					adamlabsgallerygs.TweenLite.set(item,{autoAlpha:1,overwrite:"all"});

				}
				p.itemtowait--;

				if (p.itemtowait==0) {	
				
					opt.container.trigger('itemsinposition');
					item.closest('.mainul').removeClass("gridorganising");

				}
				
			}
			
			var vanime;
			if(!special) {
				
				item.data('viewportanime', [
				
					p.speed,
					{force3D:"auto",autoAlpha:1,scale:1,transformOrigin:p.inorigin,rotationX:0,rotationY:0,y:0,x:0,xPercent:0,yPercent:0,z:0.1,rotationZ:0,left:localx,top:localy,ease:p.ease,delay:p.waits, onComplete: complete},
				
				]);
				
				if(opt.inViewport) {
					
					vanime = item.data('viewportanime');
					adamlabsgallerygs.TweenLite.to(item,vanime[0], vanime[1]);
					
				}
				
			}
			else {
				
				var trans = jQuery.extend(true, {}, transition[2]);
				trans.top = localy;
				trans.ease = p.ease;
				trans.delay = p.waits;
				trans.onComplete = complete;
				trans.overwrite = 'all';
				item.data('viewportanime', [p.speed, trans]);
				
				if(opt.inViewport) {
					
					vanime = item.data('viewportanime');
					adamlabsgallerygs.TweenLite.to(item, vanime[0], vanime[1]);
					
				}
				
			}

			//ANIMATE THE IFRAME SIZES, FOR VIDEOS AND REST FOR FUN
			/*if (item.find('iframe').length>0) {
		 		item.find('iframe').each(function() {
		 			var ifr = jQuery(this);
			 		var ifw = Math.round(ifr.data('neww'));
			 		var ifh = Math.round(ifr.data('newh'));
			 		if (opt.layout!="even") {
				 		adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-media-poster'),{width:ifw,height:ifh});
				 		adamlabsgallerygs.TweenLite.set(item.find('iframe'),{width:ifw,height:ifh});
				 	}	else {
					 	adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-media-poster'),{width:"100%",height:"100%"});
				 		adamlabsgallerygs.TweenLite.set(item.find('iframe'),{width:"100%",height:"100%"});
				 	}
				});
	 		}

	 		//ANIMATE THE HTML5 SIZES, FOR VIDEOS AND REST FOR FUN
			if (item.find('.video-eg').length>0) {
		 		item.find('.video-eg').each(function() {
		 			var ifr = jQuery(this);
			 		var ifw = ifr.data('neww');
			 		var ifh = ifr.data('newh');
			 		if (opt.layout!="even") {
				 		adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-media-poster'),{width:ifw,height:ifh});
				 		adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-media'),{width:ifw,height:ifh});
				 		adamlabsgallerygs.TweenLite.set(item.find('.video-eg'),{width:ifw,height:ifh});
				 	} else {
					 	adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-media-poster'),{width:"100%",height:"100%"});
				 		adamlabsgallerygs.TweenLite.set(item.find('.adamlabsgallery-entry-media'),{width:"100%",height:"100%"});
				 		adamlabsgallerygs.TweenLite.set(item.find('.video-eg'),{width:"100%",height:"100%"});
				 	}
				});
	 		}*/



			// NEXT PAGE PLEASE
			if (opt.layout=="masonry") p.col = p.backupcol;
			p=shiftGrid(p,opt,item);



	} else {

		p.itemouttowait++;
		adamlabsgallerygs.TweenLite.set(item,{zIndex:5});
		item.removeClass("isvisiblenow");



		if (item.css("opacity")>0) {
			// TWEEN OPACITY AND ROTATIONS

			if (p.anim=="stack") {

				adamlabsgallerygs.TweenLite.set(item,{zIndex:p.pageitemcounterfake+100});
				adamlabsgallerygs.TweenLite.to(item,p.outspeed/2,{force3D:"auto",x:-20-p.itemw,rotationY:30,rotationX:10,ease:Sine.easeInOut,delay:p.outwaits});
				adamlabsgallerygs.TweenLite.to(item,0.01,{force3D:"auto",zIndex:p.pageitemcounterfake,delay:p.outwaits+p.outspeed/3});

				adamlabsgallerygs.TweenLite.to(item,p.outspeed*0.2,{force3D:"auto",delay:p.outwaits+p.outspeed*0.9,autoAlpha:0,ease:Sine.easeInOut});
				adamlabsgallerygs.TweenLite.to(item,p.outspeed/3,{zIndex:2,force3D:"auto",x:0,scale:0.9,rotationY:0,rotationX:0,ease:Sine.easeInOut,delay:p.outwaits+p.outspeed/1.4,onComplete:function() {
						if (!item.hasClass("itemtoshow")) adamlabsgallerygs.TweenLite.set(item,{autoAlpha:0,overwrite:"all",display:"none"});
						p.itemouttowait--;
						if (p.itemouttowait==0) {
							
							opt.container.trigger('itemsinposition');
						}
					}});
			}

			else

			if (p.anim == "horizontal-flipbook" || p.anim == "vertical-flipbook") {

				adamlabsgallerygs.TweenLite.to(item,p.outspeed,{force3D:"auto",zIndex:2,scale:p.scale,autoAlpha:p.outfade,transformOrigin:p.outorigin,rotationX:p.outxrot,rotationY:p.outyrot,ease:p.easeout,delay:p.outwaits,onComplete:function() {

					if (!item.hasClass("itemtoshow")) adamlabsgallerygs.TweenLite.set(item,{autoAlpha:0,overwrite:"all",display:"none"});
					p.itemouttowait--;
					if (p.itemouttowait==0) {

						
						opt.container.trigger('itemsinposition');
					}
				}});
			}

			else

			if (p.anim =="fall")
				adamlabsgallerygs.TweenLite.to(item,p.outspeed,{zIndex:2,force3D:"auto",y:p.outoffsety,autoAlpha:0,ease:p.easeout,delay:p.outwaits,onComplete:function() {

					if (!item.hasClass("itemtoshow")) adamlabsgallerygs.TweenLite.set(item,{autoAlpha:0,overwrite:"all",display:"none"});
					p.itemouttowait--;
					if (p.itemouttowait==0) {
						
						opt.container.trigger('itemsinposition');
					}
				}});


			else

			if (p.anim=="horizontal-slide")
				adamlabsgallerygs.TweenLite.to(item,p.outspeed,{zIndex:2,force3D:"auto",autoAlpha:1,left:p.hooffset+item.position().left+p.hsoffsetout,top:p.vooffset+item.position().top,ease:p.easeout,delay:p.outwaits,onComplete:function() {

						adamlabsgallerygs.TweenLite.set(item,{autoAlpha:0,overwrite:"all",display:"none"});
						p.itemouttowait--;
						if (p.itemouttowait==0) {
							
							opt.container.trigger('itemsinposition');
						}
					}});
			else

			if (p.anim=="vertical-slide")
				adamlabsgallerygs.TweenLite.to(item,p.outspeed,{zIndex:2,force3D:"auto",autoAlpha:1,left:p.hooffset+item.position().left,top:p.vooffset+item.position().top+p.vsoffsetout,ease:p.easeout,delay:p.outwaits,onComplete:function() {

						adamlabsgallerygs.TweenLite.set(item,{autoAlpha:0,overwrite:"all",display:"none"});
						p.itemouttowait--;
						if (p.itemouttowait==0) {
							
							opt.container.trigger('itemsinposition');
						}
					}});
			else

			if (p.anim=="rotatefall" && item.css("opacity")>0) {

				adamlabsgallerygs.TweenLite.set(item,{zIndex:300-p.item});
				adamlabsgallerygs.TweenLite.to(item,p.outspeed/2,{force3D:"auto",transformOrigin:p.outorigin,ease:"adamlabsgallerygs.Bounce.easeOut",rotationZ:p.rotatez,delay:p.outwaits});
				adamlabsgallerygs.TweenLite.to(item,p.outspeed/2,{zIndex:2,force3D:"auto",autoAlpha:0,y:p.outoffsety,ease:adamlabsgallerygs.Power3.easeIn,delay:p.outwaits+p.outspeed/3});
			}

			else {
				
				adamlabsgallerygs.TweenLite.to(item,p.outspeed,{force3D:"auto",zIndex:2,scale:p.scale,autoAlpha:p.outfade,transformOrigin:p.outorigin,rotationX:p.outxrot,rotationY:p.outyrot,ease:p.easeout,delay:p.outwaits,onComplete:function() {

					if (!item.hasClass("itemtoshow")) adamlabsgallerygs.TweenLite.set(item,{autoAlpha:0,overwrite:"all",display:"none"});
					p.itemouttowait--;
					if (p.itemouttowait==0) {
						
						opt.container.trigger('itemsinposition');
					}
				}});
			}
		} else {
			adamlabsgallerygs.TweenLite.set(item,{zIndex:2,scale:p.scale,autoAlpha:0,transformOrigin:p.outorigin,rotationX:p.outxrot,rotationY:p.outyrot,onComplete:function() {

					if (!item.hasClass("itemtoshow")) adamlabsgallerygs.TweenLite.set(item,{autoAlpha:0,overwrite:"all",display:"none"});
					p.itemouttowait--;
					if (p.itemouttowait==0) {
						
						opt.container.trigger('itemsinposition');
					}
				}});


		}


		//CALCULATE FAKE POSITIONS IN GRID
		p=shiftGridFake(p,opt);



	}

	
	return p;
}

/******************************
	-	FAKE POSITIONS	-
********************************/
function fakePositions(item,p,opt) {
	if ((item.hasClass("itemtoshow") || item.hasClass("fitsinfilter")) && !p.skipanim) {
    		// ITEM MUST BE SHOWN
    	} else {
		// CHECK IF ITEM HAD ALREADY A POSITION IN GRID SOMEWHERE
    		var cc = item.data('col');
			var rr = item.data('row');

			// IF NOT, THE OUTGOING ITEMS NEED TO GET A POSITIONG IN GRID
			if (cc==undefined || rr==undefined ) {
				if (p.x!=0 && p.y!=0) {
					p.x= Math.round(p.fakecol*p.itemw);
					p.y = p.fakey;
					cc=p.fakecol;
					rr=p.fakerow;
					item.data('col',p.fakecol);
					item.data('row',p.fakerow);
				}
			}
			
			if (p.anim!=="rotatefall") {
				
				p.outwaits = opt.animationType === 'item' ? cc * p.delaybasic + rr * (p.delaybasic * opt.column) : 
							 opt.animationType === 'col' ? cc * p.delaybasic : 
							 rr * (p.delaybasic * opt.column);
				

			} else {
				
				p.outwaits = (opt.column-cc)*p.delaybasic+(rr)*(p.delaybasic*opt.column);

			}
			
		}
	return p;
}

/******************************
	-	SHIFT THE GRID	-
********************************/

function shiftGrid(p,opt,item) {
	item.data('col',p.col);
	item.data('row',p.row);
	p.pageitemcounter++;
	p.col=p.col+p.verticalsteps;

	p.allcol++;
	if (p.col==opt.column) {
		 p.col=0;
		 p.row++;
		 p.allrow++;
		 p.y = parseFloat(p.y) + parseFloat(p.itemh) + parseFloat(opt.space);
		 if (p.row==opt.row) {
		 	p.row=0;

	 		if (p.pageitemcounter>=opt.column*opt.row) p.pageitemcounter=0;
		 	p.pagetoanimate=p.pagetoanimate+1;
		 	p.pagecounter++;
		 	if (p.pageitemcounter==0)
			 	for (var i=0;i<opt.column;i++)
					p.currentcolumnheight[i] = 0;
		 }
	}

	return p;
}





/******************************
	-	SHIFT THE FAKE GRID	-
********************************/

function shiftGridFake(p,opt) {
	p.fakecol=p.fakecol+1;
	p.pageitemcounterfake++;

	if (p.fakecol==opt.column) {
		 p.fakecol=0;
		 p.fakerow++;
		 p.fakey = p.fakey + p.itemh + opt.space;

		 if (p.fakerow==opt.row) {
		 	p.fakerow=0;
		 	p.pageitemcounterfake=0;
		 }
	}
	return p;
}






















/******************************
	-	VIDEO HANDLINGS	-
********************************/


/******************************
	-	LOAD VIDEO APIS	-
********************************/

function checkVideoScript(tpe, search, url) {
	
	var loadit = true,
		httpprefix,
		before,
		s;
			
	vhandlers[tpe] = true;
	jQuery('script[src]').each(function() {
		
		if(jQuery(this).attr('src').search(search) !== -1) {
		   loadit = false;
		   return false;
		}
		
	});
	
	if(loadit) {
		
		s = document.createElement("script");
		s.src = url;
		before = document.getElementsByTagName("script")[0];
		
		try {
			before.parentNode.insertBefore(s, before);
		}
		catch(e) {}

	}
	
}

function loadVideoApis(container,opt) {
	
	container.find('iframe').each(function() {
		
		var src = jQuery(this).attr('src');
			
		if(src.indexOf('you') > 0 && !vhandlers.addedyt) {
		
			checkVideoScript('addedyt', 'www.youtube.com/iframe_api', 'https://www.youtube.com/iframe_api');
		
		}

		else if(src.indexOf('ws') > 0 && !vhandlers.addedws) {
			
			httpprefix = location.protocol !== 'https:' ? "http" : "https";
			checkVideoScript('addedws', 'fast.wistia.com/assets/external/E-v1.js', httpprefix + '://fast.wistia.com/assets/external/E-v1.js');

		}

		else if(src.indexOf('vim') > 0 && !vhandlers.addedvim) {
			
			checkVideoScript('addedvim', 'player.vimeo.com/api/player.js', 'https://player.vimeo.com/api/player.js');
			
		}
		
		else if(src.indexOf('soundcloud')>0 && !vhandlers.addedsc) {
			
			httpprefix = location.protocol !== 'https:' ? "http" : "https";
			checkVideoScript('addedsc', 'w.soundcloud.com/player/api.js', httpprefix + '://w.soundcloud.com/player/api.js');
			
		}
		
	});
}

/*
function toHHMMSS() {


	var date_now = new Date();

	var seconds = Math.floor(date_now)/1000;

	var minutes = Math.floor(seconds/60);
	var hours = Math.floor(minutes/60);
	var days = Math.floor(hours/24);

	hours = hours-(days*24);
	minutes = minutes-(days*24*60)-(hours*60);
	seconds = seconds-(days*24*60*60)-(hours*60*60)-(minutes*60);

	return hours+":"+minutes+":"+seconds;
}
*/


/************************************
	-	STOP ALL  VIDEOS	-
*************************************/

function stopAllVideos(forceall,killiframe,callerid,fromResize) {
	
	var isplaying=" isplaying";
	if (forceall) isplaying = "";	
	
	/* 2.1.5 */
	var visibleitems;
	if(!fromResize) {
		visibleitems = document.getElementsByClassName("adamlabsgallery-item isvisiblenow");
	}
	else {
		visibleitems = jQuery(".adamlabsgallery-item").not(".isvisiblenow").toArray();
	}

	for (var a=0;a<visibleitems.length;a++) {
		var _y = visibleitems[a].getElementsByClassName('adamlabsgallery-youtubevideo haslistener'+isplaying),
		    _v = visibleitems[a].getElementsByClassName('adamlabsgallery-vimeovideo haslistener'+isplaying),
		    _w = visibleitems[a].getElementsByClassName('adamlabsgallery-wistiavideo haslistener'+isplaying),
		    _h = visibleitems[a].getElementsByClassName('adamlabsgallery-htmlvideo haslistener'+isplaying),
		    _s = visibleitems[a].getElementsByClassName('adamlabsgallery-soundcloud'+isplaying),
			player,
			ifr,
			id,
			i;
			
		for (i=0;i<_y.length;i++) {
			ifr = jQuery(_y[i]);
			player = ifr.data('player');

			if (callerid !=ifr.attr('id')) {
				player.pauseVideo();
				if (forceall)  forceVideoInPause(ifr,false,player,"youtube");
			}
		}
		for (i=0;i<_v.length;i++) {
			ifr = jQuery(_v[i]);
			id = ifr.attr('id');
			player = ifr.data('newvimeoplayer');
			
			if (callerid !=id) {
				player.pause();
				if (callerid===undefined)		
					if (forceall)  forceVideoInPause(ifr,false,player,"vimeo");
			}
		}
		for (i=0;i<_w.length;i++) {
			ifr = jQuery(_w[i]);
			player = ifr.data('player');

			if (callerid!=ifr.attr('id')) {
				ifr.wistiaApi.pause();
				if (forceall)  forceVideoInPause(ifr,false,player,"wistia");
			}
		}
		for (i=0;i<_h.length;i++) {
			ifr = jQuery(_h[i]);
			id = ifr.attr('id');
			player=document.getElementById(id);

			if (callerid !=id) {
				player.pause();
				if (forceall)  forceVideoInPause(ifr,false,player,"html5vid");
			}
		}
		for (i=0;i<_s.length;i++) {
			ifr = jQuery(_s[i]);
			player = ifr.data('player');
			
			if (callerid !=ifr.attr('id')) {
				player.pause();
				if (forceall)  forceVideoInPause(ifr,false,player,"soundcloud");
			}
		}

	}

}

/*************************************************************
	-	FORCE VIDEO BACK IN PAUSE MODE AND SHOW POSTER 	-
*************************************************************/
function forceVideoInPause(vid,killiframe,player,vidtype) {

				vid.removeClass("isplaying");


				var item=vid.closest('.adamlabsgallery-item').removeClass('adamlabsgallery-video-active');

				if (item.find('.adamlabsgallery-media-video').length>0 && !jQuery("body").data('fullScreenMode')) {
					 var cover = item.find('.adamlabsgallery-entry-cover');
					 var poster = item.find('.adamlabsgallery-media-poster');
					 if (poster.length>0) {
					 	 if (!is_mobile()) {
							 adamlabsgallerygs.TweenLite.to(cover,0.5,{autoAlpha:1});
							 adamlabsgallerygs.TweenLite.to(poster,0.5,{autoAlpha:1});
							 adamlabsgallerygs.TweenLite.to(vid,0.5,{autoAlpha:0});
						} else {
							adamlabsgallerygs.TweenLite.set(cover,{autoAlpha:1});
							 adamlabsgallerygs.TweenLite.set(poster,{autoAlpha:1});
							 adamlabsgallerygs.TweenLite.set(vid,{autoAlpha:0});
						}

						 if (killiframe) {
						   if (vidtype=="youtube")
								try {  player.destroy(); } catch(e) {}
							else
						   if (vidtype=="vimeo")
							try {  player.unload(); } catch(e) {}
						   else
							if (vidtype=="wistia")
								try {  player.end(); } catch(e) {}
							else
						   if (vidtype!="html5vid") {
							   vid.removeClass("haslistener");
							   vid.removeClass("readytoplay");
							 }

					     } else {
							 setTimeout(function() {
							 	if (!is_mobile())
									vid.css({display:"none"});
							 },500);
						 }
					}
				}
			}


//////////////////////////////////////////
// CHANG THE YOUTUBE PLAYER STATE HERE //
////////////////////////////////////////
function onPlayerStateChange(event) {
		
		var ytcont = event.target.getIframe(),
			jc = jQuery(ytcont);
		
		/* 2.1.5 */
		clearTimeout(jc.data('adamlabsgalleryplayertimer'));

		if (event.data == YT.PlayerState.PLAYING) {
			event.target.setPlaybackQuality("hd1080");

			stopAllVideos(true,false,ytcont.id);
			jc.addClass("isplaying").removeClass("isinpause");			
		}

		if (event.data==2 ) {
			/* 2.1.5 */
			var targt = event.target;
			jc.data('adamlabsgalleryplayertimer', setTimeout(function () {
				if(targt.getPlayerState() == 2) {
					forceVideoInPause(jc);
					targt.pauseVideo();
				}
			}, 100));
		}

		if (event.data==0 ) {
			forceVideoInPause(jc);
		}
		
}






/////////////////////////////////////
// EVENT HANDLING FOR VIMEO VIDEOS //
/////////////////////////////////////

function vimeoready_auto(vimcont) {
		
		var player = vimcont.data('newvimeoplayer');
		
		if(!player) {
			
			player = new Vimeo.Player(vimcont[0]);
			vimcont.data('newvimeoplayer', player);
			
		}

		vimcont.addClass("readytoplay");

		player.on('play', function(data) {
			stopAllVideos(true,false,vimcont.attr('id'));
			vimcont.addClass("isplaying");
			vimcont.removeClass("isinpause");
		});

		player.on('finish', function(data) {
			forceVideoInPause(vimcont);
			vimcont.removeClass("isplaying");
		});

		player.on('pause', function(data) {
			forceVideoInPause(vimcont);
			vimcont.removeClass("isplaying");
		});
		
		player.on('error', function() {
			
			console.log('vimeo error occured');
			
		});

}

/*
function addEvent(element, eventName, callback) {

		if (element.addEventListener)  element.addEventListener(eventName, callback, false);
			else
		element.attachEvent(eventName, callback, false);
}
*/


///////////////////////////////////////
// EVENT HANDLING FOR VIDEO JS VIDEOS //
////////////////////////////////////////
function html5vidready(myVideo,vidcont,player_id) {

		var isSeeking,
			timer;
			
		vidcont.addClass("readytoplay");
		
		/* 2.1.5 */
		function testSeeking() {
			if(isSeeking) {
				isSeeking = false;
				return;	
			}
			forceVideoInPause(vidcont);
			vidcont.removeClass("isplaying");
		}

		vidcont.on('play',function() {
			
			/* 2.1.5 */
			if(isSeeking) return;	
			isSeeking = false;
			
			stopAllVideos(true,false,player_id);

			vidcont.addClass("isplaying");
			vidcont.removeClass("isinpause");
		});

		vidcont.on('pause',function() {
			/* 2.1.5 */
			clearTimeout(timer);
			timer = setTimeout(testSeeking, 100);	
		});

		vidcont.on('ended',function() {

			forceVideoInPause(vidcont);
			vidcont.removeClass("isplaying");
		});

		vidcont.on('seeking', function() {
			isSeeking = true;
		});


}



/********************************************************
	-	YOUTUBE IFRAME ID AND PLAYER OCNFIGURTION	-
*********************************************************/

function prepareYT(ifr) {


	 var frameID = "ytiframe"+Math.round(Math.random()*100000+1),
		player;

	 if (!ifr.hasClass("haslistener") && typeof YT != "undefined") {

		 try{
			ifr.attr('id',frameID);

				player = new YT.Player(frameID, {
					events: {
						"onStateChange": onPlayerStateChange
					}
				});
				ifr.data('player',player);
				ifr.addClass("haslistener").addClass("adamlabsgallery-youtubevideo");

			} catch(e) { return false;}
	} else {
		player = ifr.data('player');
		if (player!=undefined)
			if (typeof player.playVideo=="function")
				return true;
			else
				return false;
		else
			return false;
	}

}

function playYT(ifr) {

	var player = ifr.data('player');
	if (player !=undefined)
		if (typeof player.playVideo == "function")
			player.playVideo();


}

/********************************************************
	-	VIMEO IFRAME ID AND PLAYER CONFIGURATION	-
********************************************************/

function prepareVimeo(ifr) {

	 if (!ifr.hasClass("haslistener") && typeof Vimeo != "undefined") {
	     try {
	     			
					var frameID = "vimeoiframe"+Math.round(Math.random()*100000+1);
					ifr.attr('id',frameID);
					var isrc = ifr.attr('src');
					var queryParameters = {}, queryString = isrc,
					re = /([^&=]+)=([^&]*)/g, 
					m;
					// Creates a map with the query string parameters

					
					while (m = re.exec(queryString)) {
						queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
					}

					if (queryParameters['player_id']!=undefined)
						isrc = isrc.replace(queryParameters['player_id'],frameID);
					else
						isrc=isrc+"&player_id="+frameID;

					
					// https://github.com/vimeo/player.js/blob/master/docs/migrate-from-froogaloop.md
					isrc = isrc.replace(/&api=0|&api=1/, '');
					
					ifr.attr('src',isrc);
					
					vimeoready_auto(ifr);
					
					ifr.addClass("haslistener").addClass("adamlabsgallery-vimeovideo");
					
			 } catch(e) { 
				
				// console.log(e);
				return false;
				
			 }

	 } else {
			
	 		if (typeof Vimeo !== 'undefined') {
	 			
	 			 var player = ifr.data('newvimeoplayer');
		 		if (player && ifr.hasClass("readytoplay")) {
		 			
			 		return true;
		 		}
		 		else {
		 			
			 		return false;
		 		}
			} else {
				
				return false;
			}
	 }

}

function playVimeo(ifr) {
	
	var player = ifr.data('newvimeoplayer');
	if(player) player.play();
	
}


/********************************************************
	-	wistia IFRAME ID AND PLAYER OCNFIGURTION	-
*********************************************************/

function prepareWs(ifr) {


	 var frameID = "wsiframe"+Math.round(Math.random()*100000+1),
		player;

	 if (!ifr.hasClass("haslistener") && typeof Ws != "undefined") {

		 try{
			ifr.attr('id',frameID);

				player = new Ws.Player(frameID, {
					events: {
						"onStateChange": onPlayerStateChange
					}
				});
				ifr.data('player',player);
				ifr.addClass("haslistener").addClass("adamlabsgallery-wistiavideo");

			} catch(e) { return false;}
	} else {
		player = ifr.data('player');
		if (player!=undefined)
			if (typeof player.playVideo=="function")
				return true;
			else
				return false;
		else
			return false;
	}

}

function playWs(ifr) {

	var player = ifr.data('player');
	if (player !=undefined)
		if (typeof player.playVideo == "function")
			player.wistiaApi.Plau();
}

/********************************************************
	-	SOUNDCLUD IFRAME ID AND PLAYER CONFIGURATION	-
********************************************************/

function prepareSoundCloud(ifr) {
	
	var player;
	 if (ifr.data('player')==undefined && typeof SC != "undefined") {
		  var frameID = "sciframe"+Math.round(Math.random()*100000+1);
		 try{

			ifr.attr('id',frameID);
			player = SC.Widget(frameID);

			player.bind(SC.Widget.Events.PLAY,function() {
					stopAllVideos(true,false,ifr.attr('id'));
					ifr.addClass("isplaying");
					ifr.removeClass("isinpause");
				});
			player.bind(SC.Widget.Events.PAUSE,function() {
					if(ifr.hasClass('isplaying')) {
						forceVideoInPause(ifr);
						ifr.removeClass("isplaying");
					}
			});
			player.bind(SC.Widget.Events.FINISH,function() {
					forceVideoInPause(ifr);
					ifr.removeClass("isplaying");
			});
			ifr.data('player',player);
			ifr.addClass("haslistener").addClass("adamlabsgallery-soundcloud");

		 } catch(e) { return false;}
	} else {
		player = ifr.data('player');
		if (player!=undefined) {

			if (typeof player.getVolume=="function") {
				return true;
			} else {
				return false;
			}
		} else
			return false;
	}

}

function playSC(ifr) {

	var player = ifr.data('player');
	if (player !=undefined) {

		if (typeof player.getVolume == "function") {

			setTimeout(function() {
				player.play();
			},500);
		}
	}

}

/********************************************************
	    -	HTML5 VIDEO PLAYER CONFIGURATION	-
********************************************************/

function prepareVideo(html5vid) {
	
	var myVideo;
	 if (!html5vid.hasClass("haslistener")) {
	// 	try {
				 var videoID = "videoid_"+Math.round(Math.random()*100000+1);
				 html5vid.attr('id',videoID);
				 myVideo=document.getElementById(videoID);
				 myVideo.oncanplay=html5vidready(myVideo,html5vid,videoID);

				 html5vid.addClass("haslistener").addClass("adamlabsgallery-htmlvideo");

		//	} catch(e) { return false }
	} else {
		try {
			 var id = html5vid.attr('id');
			 myVideo=document.getElementById(id);

			 if (typeof myVideo.play=="function" && html5vid.hasClass("readytoplay"))
			   return true;
			else
			   return false;
			 } catch(e) { return false;}
	}
}

function playVideo(ifr) {
	
	var id = ifr.attr('id');
	var myVideo=document.getElementById(id);
	myVideo.play();

}






})(jQuery);


/*! TinySort
* Copyright (c) 2008-2013 Ron Valstar http://tinysort.sjeiti.com/
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*//*
* Description:
*   A jQuery plugin to sort child nodes by (sub) contents or attributes.
*
* Contributors:
*	brian.gibson@gmail.com
*	michael.thornberry@gmail.com
*
* Usage:
*   $("ul#people>li").tsort();
*   $("ul#people>li").tsort("span.surname");
*   $("ul#people>li").tsort("span.surname",{order:"desc"});
*   $("ul#people>li").tsort({place:"end"});
*   $("ul#people>li").tsort("span.surname",{order:"desc"},span.name");
*
* Change default like so:
*   $.tinysort.defaults.order = "desc";
*
*/
;(function($,undefined) {
	'use strict';
	// private vars
	var fls = !1							// minify placeholder
		,nll = null							// minify placeholder
		,prsflt = parseFloat				// minify placeholder
		,mathmn = Math.min					// minify placeholder
		,rxLastNr = /(-?\d+\.?\d*)$/g		// regex for testing strings ending on numbers
		,rxLastNrNoDash = /(\d+\.?\d*)$/g	// regex for testing strings ending on numbers ignoring dashes
		,aPluginPrepare = []
		,aPluginSort = []
		,isString = function(o){return typeof o=='string';}
		,loop = function(array,func){
            var l = array.length
                ,i = l
                ,j;
            while (i--) {
                j = l-i-1;
                func(array[j],j);
            }
		}
		// Array.prototype.indexOf for IE (issue #26) (local variable to prevent unwanted prototype pollution)
		,fnIndexOf = Array.prototype.indexOf||function(elm) {
			var len = this.length
				,from = Number(arguments[1])||0;
			from = from<0?Math.ceil(from):Math.floor(from);
			if (from<0) from += len;
			for (;from<len;from++){
				if (from in this && this[from]===elm) return from;
			}
			return -1;
		}
	;
	//
	// init plugin
	$.tinysort = {
		 id: 'TinySort'
		,version: '1.5.6'
		,copyright: 'Copyright (c) 2008-2013 Ron Valstar'
		,uri: 'http://tinysort.sjeiti.com/'
		,licensed: {
			MIT: 'http://www.opensource.org/licenses/mit-license.php'
			,GPL: 'http://www.gnu.org/licenses/gpl.html'
		}
		,plugin: (function(){
			var fn = function(prepare,sort){
				aPluginPrepare.push(prepare);	// function(settings){doStuff();}
				aPluginSort.push(sort);			// function(valuesAreNumeric,sA,sB,iReturn){doStuff();return iReturn;}
			};
			// expose stuff to plugins
			fn.indexOf = fnIndexOf;
			return fn;
		})()
		,defaults: { // default settings

			 order: 'asc'			// order: asc, desc or rand

			,attr: nll				// order by attribute value
			,data: nll				// use the data attribute for sorting
			,useVal: fls			// use element value instead of text

			,place: 'start'			// place ordered elements at position: start, end, org (original position), first
			,returns: fls			// return all elements or only the sorted ones (true/false)

			,cases: fls				// a case sensitive sort orders [aB,aa,ab,bb]
			,forceStrings:fls		// if false the string '2' will sort with the value 2, not the string '2'

			,ignoreDashes:fls		// ignores dashes when looking for numerals

			,sortFunction: nll		// override the default sort function
		}
	};
	$.fn.extend({
		tinysort: function() {
			var i,j,l
				,oThis = this
				,aNewOrder = []
				// sortable- and non-sortable list per parent
				,aElements = []
				,aElementsParent = [] // index reference for parent to aElements
				// multiple sort criteria (sort===0?iCriteria++:iCriteria=0)
				,aCriteria = []
				,iCriteria = 0
				,iCriteriaMax
				//
				,aFind = []
				,aSettings = []
				//
				,fnPluginPrepare = function(_settings){
					loop(aPluginPrepare,function(fn){
						fn.call(fn,_settings);
					});
				}
				//
				,fnPrepareSortElement = function(settings,element){
					if (typeof element=='string') {
						// if !settings.cases
						if (!settings.cases) element = toLowerCase(element);
						element = element.replace(/^\s*(.*?)\s*$/i, '$1');
					}
					return element;
				}
				//
				,fnSort = function(a,b) {
					var iReturn = 0;
					if (iCriteria!==0) iCriteria = 0;
					while (iReturn===0&&iCriteria<iCriteriaMax) {
						var oPoint = aCriteria[iCriteria]
							,oSett = oPoint.oSettings
							,rxLast = oSett.ignoreDashes?rxLastNrNoDash:rxLastNr
						;
						//
						fnPluginPrepare(oSett);
						//
						if (oSett.sortFunction) { // custom sort
							iReturn = oSett.sortFunction(a,b);
						} else if (oSett.order=='rand') { // random sort
							iReturn = Math.random()<0.5?1:-1;
						} else { // regular sort
							var bNumeric = fls
								// prepare sort elements
								,sA = fnPrepareSortElement(oSett,a.s[iCriteria])
								,sB = fnPrepareSortElement(oSett,b.s[iCriteria])
							;
							// maybe force Strings
							if (!oSett.forceStrings) {
								// maybe mixed
								var  aAnum = isString(sA)?sA&&sA.match(rxLast):fls
									,aBnum = isString(sB)?sB&&sB.match(rxLast):fls;
								if (aAnum&&aBnum) {
									var  sAprv = sA.substr(0,sA.length-aAnum[0].length)
										,sBprv = sB.substr(0,sB.length-aBnum[0].length);
									if (sAprv==sBprv) {
										bNumeric = !fls;
										sA = prsflt(aAnum[0]);
										sB = prsflt(aBnum[0]);
									}
								}
							}
							iReturn = oPoint.iAsc*(sA<sB?-1:(sA>sB?1:0));
						}

						loop(aPluginSort,function(fn){
							iReturn = fn.call(fn,bNumeric,sA,sB,iReturn);
						});

						if (iReturn===0) iCriteria++;
					}

					return iReturn;
				}
			;
			// fill aFind and aSettings but keep length pairing up
			for (i=0,l=arguments.length;i<l;i++){
				var o = arguments[i];
				if (isString(o))	{
					if (aFind.push(o)-1>aSettings.length) aSettings.length = aFind.length-1;
				} else {
					if (aSettings.push(o)>aFind.length) aFind.length = aSettings.length;
				}
			}
			if (aFind.length>aSettings.length) aSettings.length = aFind.length; // todo: and other way around?

			// fill aFind and aSettings for arguments.length===0
			iCriteriaMax = aFind.length;
			if (iCriteriaMax===0) {
				iCriteriaMax = aFind.length = 1;
				aSettings.push({});
			}

			for (i=0,l=iCriteriaMax;i<l;i++) {
				var sFind = aFind[i]
					,oSettings = $.extend({}, $.tinysort.defaults, aSettings[i])
					// has find, attr or data
					,bFind = !(!sFind||sFind==='')
					// since jQuery's filter within each works on array index and not actual index we have to create the filter in advance
					,bFilter = bFind&&sFind[0]===':'
				;
				aCriteria.push({ // todo: only used locally, find a way to minify properties
					 sFind: sFind
					,oSettings: oSettings
					// has find, attr or data
					,bFind: bFind
					,bAttr: !(oSettings.attr===nll||oSettings.attr==='')
					,bData: oSettings.data!==nll
					// filter
					,bFilter: bFilter
					,$Filter: bFilter?oThis.filter(sFind):oThis
					,fnSort: oSettings.sortFunction
					,iAsc: oSettings.order=='asc'?1:-1
				});
			}
			//
			// prepare oElements for sorting
			oThis.each(function(i,el) {
				var $Elm = $(el)
					,mParent = $Elm.parent().get(0)
					,mFirstElmOrSub // we still need to distinguish between sortable and non-sortable elements (might have unexpected results for multiple criteria)
					,aSort = []
				;
				for (j=0;j<iCriteriaMax;j++) {
					var oPoint = aCriteria[j]
						// element or sub selection
						,mElmOrSub = oPoint.bFind?(oPoint.bFilter?oPoint.$Filter.filter(el):$Elm.find(oPoint.sFind)):$Elm;
					// text or attribute value
					aSort.push(oPoint.bData?mElmOrSub.data(oPoint.oSettings.data):(oPoint.bAttr?mElmOrSub.attr(oPoint.oSettings.attr):(oPoint.oSettings.useVal?mElmOrSub.val():mElmOrSub.text())));
					if (mFirstElmOrSub===undefined) mFirstElmOrSub = mElmOrSub;
				}
				// to sort or not to sort
				var iElmIndex = fnIndexOf.call(aElementsParent,mParent);
				if (iElmIndex<0) {
					iElmIndex = aElementsParent.push(mParent) - 1;
					aElements[iElmIndex] = {s:[],n:[]};	// s: sort, n: not sort
				}
				if (mFirstElmOrSub.length>0)	aElements[iElmIndex].s.push({s:aSort,e:$Elm,n:i}); // s:string/pointer, e:element, n:number
				else							aElements[iElmIndex].n.push({e:$Elm,n:i});
			});
			//
			// sort
			loop(aElements, function(oParent) { oParent.s.sort(fnSort); });
			//
			// order elements and fill new order
			loop(aElements, function(oParent) {
				var aSorted = oParent.s
                    ,aUnsorted = oParent.n
                    ,iSorted = aSorted.length
                    ,iUnsorted = aUnsorted.length
                    ,iNumElm = iSorted+iUnsorted
					,aOriginal = [] // list for original position
					,iLow = iNumElm
					,aCount = [0,0] // count how much we've sorted for retrieval from either the sort list or the non-sort list (oParent.s/oParent.n)
				;
				switch (oSettings.place) {
					case 'first':	loop(aSorted,function(obj) { iLow = mathmn(iLow,obj.n); }); break;
					case 'org':		loop(aSorted,function(obj) { aOriginal.push(obj.n); }); break;
					case 'end':		iLow = iUnsorted; break;
					default:		iLow = 0;
				}
				for (i=0;i<iNumElm;i++) {
					var bFromSortList = contains(aOriginal,i)?!fls:i>=iLow&&i<iLow+iSorted
                        ,iCountIndex = bFromSortList?0:1
						,mEl = (bFromSortList?aSorted:aUnsorted)[aCount[iCountIndex]].e;
					mEl.parent().append(mEl);
					if (bFromSortList||!oSettings.returns) aNewOrder.push(mEl.get(0));
					aCount[iCountIndex]++;
				}
			});
			oThis.length = 0;
			Array.prototype.push.apply(oThis,aNewOrder);

			return oThis;
		}
	});
	// toLowerCase // todo: dismantle, used only once
	function toLowerCase(s) {
		return s&&s.toLowerCase?s.toLowerCase():s;
	}
	// array contains
	function contains(a,n) {
		for (var i=0,l=a.length;i<l;i++) if (a[i]==n) return !fls;
		return fls;
	}
	// set functions
	$.fn.TinySort = $.fn.Tinysort = $.fn.tsort = $.fn.tinysort;

	// Post Likes
	jQuery(document).ready(function() {
 
	    jQuery("span.adamlabsgallery-post-like").click(function(){
	     	var heart,post_id,count;
	     	count = 0;
	        heart = jQuery(this).closest("a");
	     
	        // Retrieve post ID from data attribute
	        post_id = heart.data("post_id");
	         
	        // Ajax call
	        jQuery.ajax({
	            type: "post",
	            url: adamlabsgallery_ajax_var.url,
	            data: "action=adamlabsgallery_post_like&nonce="+adamlabsgallery_ajax_var.nonce+"&post_like=&post_id="+post_id,
	            success: function(count){
	                // If vote successful
	                if(count != "already")
	                {
	                    heart.addClass("adamlabsgallery-post-like-voted");
	                    heart.closest("li").find(".adamlabsgallery-post-count").text(count);
	                }
	            }
	        });
	         
	        return false;
	    })
	})
})(jQuery);