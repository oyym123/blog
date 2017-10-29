
/* #Masonry
================================================== */
// jQuery(document).ready(function($) {
	// !- Calculate columns size
	$.fn.calculateColumns = function(minWidth, colNum, padding, switchD, switchTH, switchTV, switchP, mode) {
		return this.each(function() {
			var $container = $(this),
				containerWidth = $container.width() - 1,
				containerPadding = (padding !== false) ? padding : 20,
				containerID = $container.attr("data-cont-id"),
				tempCSS = "",
				first = false;

			if($container.parent().hasClass("blog-shortcode")  || $container.parent().hasClass("portfolio-shortcode")){
				var	$classesContainer = $container.parent(),
					$containerIDN = $(".cont-id-"+containerID+"").parent();
			}else{
				var	$classesContainer = $container,
					$containerIDN = $(".cont-id-"+containerID+"");
			}

			if(typeof(minWidth)==='undefined') minWidth = 200;
			if(typeof(colNum)==='undefined') colNum = 6;


			for ( ; Math.floor(containerWidth/colNum) < minWidth; ) {
				colNum--;
				if (colNum <= 1) break;
			}

			if (!$("#col-style-id-"+containerID).exists()) {
				var jsStyle = document.createElement("style");
				jsStyle.id = "col-style-id-"+containerID;
				jsStyle.appendChild(document.createTextNode(""));
				document.head.appendChild(jsStyle);
			} else {
				var jsStyle = document.getElementById("col-style-id-"+containerID);
			}


			var $style = $("#col-style-id-"+containerID);

			var singleWidth,
				doubleWidth,
				columnsNum,
				normalizedPadding,
				normalizedMargin,
				normalizedPaddingTop;

			if (containerPadding < 10) {
				normalizedPadding = 0;
				normalizedPaddingTop = 0;
			}
			else {
				normalizedPaddingTop = containerPadding - 5;
				normalizedPadding = containerPadding - 10;
			};
			if (containerPadding == 0) {
				normalizedMargin = 0;
			}
			else {
				normalizedMargin = -containerPadding;
			};

			
			if($classesContainer.hasClass("resize-by-browser-width")){
				
				
				if (Modernizr.mq('only screen and (max-width:767px)')) {
					singleWidth = Math.floor(containerWidth / switchP)+"px";
					doubleWidth = Math.floor(containerWidth  / switchP)*2+"px";
					columnsNum = switchP;
				}else if(Modernizr.mq('(min-width:768px) and (max-width:991px)')){
					singleWidth = Math.floor(containerWidth / switchTV)+"px";
					doubleWidth = Math.floor(containerWidth  / switchTV)*2+"px";
					columnsNum = switchTV;
				}else if(Modernizr.mq('(min-width:992px) and (max-width:1199px)')){
					singleWidth = Math.floor(containerWidth / switchTH)+"px";
					doubleWidth = Math.floor(containerWidth  / switchTH)*2+"px";
					columnsNum = switchTH;
				}else {
					singleWidth = Math.floor(containerWidth / switchD)+"px";
					doubleWidth = Math.floor(containerWidth  / switchD)*2+"px";
					columnsNum = switchD;
				}

			}else{
				if (mode == "px") {
					singleWidth = Math.floor(containerWidth / colNum)+"px";
					doubleWidth = Math.floor(containerWidth  / colNum)*2+"px";
					columnsNum = colNum;
				}
				else {
					singleWidth = Math.floor(100000 / colNum)/1000+"%";
					doubleWidth = Math.floor(100000 / colNum)*2/1000+"%";
				};
			}

				if ( $containerIDN.not(".bg-under-post, .content-bg-on").hasClass("description-under-image") ) {
					if (columnsNum > 1) {
						tempCSS = " \
							.cont-id-"+containerID+" { margin: -"+normalizedPaddingTop+"px  -"+containerPadding+"px -"+normalizedPadding+"px ; } \
							.full-width-wrap .cont-id-"+containerID+" { margin: "+(-normalizedPaddingTop)+"px "+containerPadding+"px "+(-normalizedPadding)+"px ; } \
							.cont-id-"+containerID+"  .wf-cell { width: "+singleWidth+"; padding: "+normalizedPaddingTop +"px "+containerPadding+"px "+normalizedPadding+"px; } \
							.cont-id-"+containerID+"  .wf-cell.double-width { width: "+doubleWidth+"; } \
						";
					}
					else {
						tempCSS = " \
							.cont-id-"+containerID+" { margin: -"+normalizedPaddingTop+"px  -"+normalizedPadding+"px -"+containerPadding+"px ; } \
							.full-width-wrap .cont-id-"+containerID+" { margin: "+(-normalizedPaddingTop)+"px "+containerPadding+"px "+(-normalizedPadding)+"px ; } \
							.cont-id-"+containerID+"  .wf-cell { width: "+singleWidth+"; padding: "+normalizedPaddingTop +"px "+normalizedPadding+"px "+containerPadding+"px; } \
						";
					};
				}else {
					if (columnsNum > 1) {
						tempCSS = " \
							.cont-id-"+containerID+" { margin: -"+containerPadding+"px; } \
							.full-width-wrap .cont-id-"+containerID+" { margin: "+normalizedMargin+"px  "+containerPadding+"px; } \
							.cont-id-"+containerID+" .wf-cell { width: "+singleWidth+";  padding: "+containerPadding+"px; } \
							.cont-id-"+containerID+" .wf-cell.double-width { width: "+doubleWidth+"; } \
						";
						
					}
					else {
						tempCSS = " \
							.cont-id-"+containerID+" { margin: -"+containerPadding+"px; } \
							.full-width-wrap .cont-id-"+containerID+" { margin: "+normalizedMargin+"px "+containerPadding+"px; } \
							.cont-id-"+containerID+" .wf-cell { width: "+singleWidth+"; padding: "+containerPadding+"px; } \
						";
					};
				};
			
			$style.html(tempCSS);
			var newRuleID = jsStyle.sheet.cssRules.length;
			jsStyle.sheet.insertRule(".webkit-hack { }", newRuleID);
			jsStyle.sheet.deleteRule(newRuleID);

			$container.trigger("columnsReady");

		});
	};

	// !- Initialise slider
	$.fn.initSlider = function() {
		return this.each(function() {
		
			var $_this = $(this),
				attrW = $_this.data('width'),
				attrH = $_this.data('height');

			if ($_this.hasClass("royalReady")) {
				return;
			}

			$_this.postTypeScroller();

			$_this.addClass("royalReady");
			
		});
	};
	//disable isotope animation
	var positionFunc = Isotope.prototype._positionItem;
	Isotope.prototype._positionItem = function( item, x, y, isInstant ) {
	  // ignore isInstant, pass in true;
	  positionFunc(item, x, y, true);
	};
	$.fn.IsoLayzrInitialisation = function(container) {

		return this.each(function() {
			var $this = $(this);

			var layzrMsnr = new Layzr({
				container: container,
				selector: '.iso-lazy-load',
				attr: 'data-src',
				attrSrcSet: 'data-srcset',
				retinaAttr: 'data-src-retina',
				threshold: 30,
				before: function() {
					var ext = $(this).attr("data-src").substring($(this).attr("data-src").lastIndexOf(".")+1);
				   if(ext == "png"){
				     $(this).parent().addClass("layzr-bg-transparent");
				  }
					// For fixed-size images with srcset; or have to be updated on window resize.
					this.setAttribute("sizes", this.width+"px");
				},
				callback: function() {
					this.classList.add("iso-layzr-loaded");
					var $this =  $(this);
		         	$this.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e) {
						setTimeout(function(){
							$this.parent().removeClass("layzr-bg");
						}, 200)
					});
				}
			});
		});
		
	};

	/* !Containers of masonry and grid content */
	
	var	$isoCollection = $(".iso-container");
	var	$gridCollection = $(".iso-grid:not(.jg-container, .iso-container), .blog.layout-grid .wf-container.description-under-image:not(.jg-container, .iso-container), .grid-masonry:not(.iso-container), .shortcode-blog-posts.iso-grid"),
		//$flexGrid = $(".blog-grid-shortcode"),
		$combinedCollection = $isoCollection.add($gridCollection),
		$isoPreloader = dtGlobals.isoPreloader = $('<div class="iso-preloader pace pace-active"><div class="pace-activity"></div></div>').appendTo("body").hide();
		$combinedCollection.not(".blog-grid-shortcode").addClass("dt-isotope");

	/* !Smart responsive columns */
	if ($combinedCollection.exists()) {
		$combinedCollection.each(function(i) {
			var $this = $(this);
			if($this.parent().hasClass("blog-shortcode") || $this.parent().hasClass("portfolio-shortcode")){
				var	$dataAttrContainer = $this.parent();
			}else{
				var	$dataAttrContainer = $this;
			}
			var $container = $(this),
				contWidth = parseInt($dataAttrContainer.attr("data-width")),
				contNum = parseInt($dataAttrContainer.attr("data-columns")),
				desktopNum = parseInt($dataAttrContainer.attr("data-desktop-columns-num")),
				tabletHNum = parseInt($dataAttrContainer.attr("data-h-tablet-columns-num")),
				tabletVNum = parseInt($dataAttrContainer.attr("data-v-tablet-columns-num")),
				phoneNum = parseInt($dataAttrContainer.attr("data-phone-columns-num"));
			var contPadding = parseInt($dataAttrContainer.attr("data-padding"));
			
			$container.addClass("cont-id-"+i).attr("data-cont-id", i);
			$container.calculateColumns(contWidth, contNum, contPadding, desktopNum, tabletHNum, tabletVNum, phoneNum, "px");
			if(contPadding > 10){
				$container.addClass("mobile-paddings");
			}

			$window.on("debouncedresize", function () {
				$container.calculateColumns(contWidth, contNum, contPadding, desktopNum, tabletHNum, tabletVNum, phoneNum, "px");

				if(contPadding > 10){
					$container.addClass("mobile-paddings");
				}
			});
		});
	}
	

	//if(!dtGlobals.isPhone){
		// !- Responsive height hack
		$.fn.heightHack = function() {
			//if(!$(".layzr-loading-on").length > 0){

				return this.each(function() {
					var $img = $(this);
					if ($img.hasClass("height-ready") || $img.parents(".post-rollover").exists() || $img.parents(".slider-masonry").exists()) {
						return;
					}

					var	imgWidth = parseInt($img.attr('width')),
						imgHeight = parseInt($img.attr('height')),
						imgRatio = imgWidth/imgHeight;

					if($img.parents(".testimonial-vcard, .dt-format-gallery, .shortcode-blog-posts.iso-grid ").exists()) {
						$img.wrap("<div />");
					};

					$img.parent().css({
						"padding-bottom" : 100/imgRatio+"%",
						"height" : 0,
						"display" : "block"
					});

					$img.attr("data-ratio", imgRatio).addClass("height-ready");
					
				});
			//}
		};

			
		/* !Isotope initialization */
		$.fn.IsoInitialisation = function(item, mode, trans, equalh) {
			return this.each(function() {
				var $this = $(this);
				if ($this.hasClass("iso-item-ready")) {
					return;
				}
				$this.isotope({
					itemSelector : item,
					layoutMode : mode,
					stagger: 30,
					resize: false,
					transitionDuration: 0,
					equalheight: equalh,
					hiddenStyle: {
						opacity: 0
					},
					visibleStyle: {
						opacity: 1
					},
					masonry: { columnWidth: 1 },
					getSortData : {
						date : function( $elem ) {
							return $($elem).attr('data-date');
						},
						name : function( $elem ) {
							return $($elem).attr('data-name');
						}
					}
				});
				$this.addClass("iso-item-ready");

			});
			
		};
		

		
		/* !Masonry and grid layout */

		/* !Filter: */
		//var $container = $('.iso-container, .portfolio-grid');
		$('.iso-container, .portfolio-grid').each(function(){
			if($(this).parent().hasClass("blog-shortcode") || $(this).parent().hasClass("portfolio-shortcode")){
				var $container = null;
			}else{
				var $container = $(this);
			}
			
			$('.filter:not(.iso-filter):not(.without-isotope):not(.with-ajax) .filter-categories a').on('click.presscorFilterCategories', function(e) {
				var selector = $(this).attr('data-filter');
				if ($container != null){
					$container.isotope({ filter: selector });
				}
				return false;
			});

			// !- filtering
			$('.filter:not(.iso-filter):not(.without-isotope):not(.with-ajax) .filter-extras .filter-by a').on('click', function(e) {
				var sorting = $(this).attr('data-by'),
					sort = $(this).parents('.filter-extras').find('.filter-sorting > a.act').first().attr('data-sort');
				if ($container != null){
					$container.isotope({ sortBy : sorting, sortAscending : 'asc' == sort });
				}
				return false;
			});

			// !- sorting
			$('.filter:not(.iso-filter):not(.without-isotope):not(.with-ajax) .filter-extras .filter-sorting a').on('click', function(e) {
				var sort = $(this).attr('data-sort'),
					sorting = $(this).parents('.filter-extras').find('.filter-by > a.act').first().attr('data-by');
				if ($container != null){
					$container.isotope({ sortBy : sorting, sortAscending : 'asc' == sort });
				}
				return false;
			});
		});


		/* !Masonry layout */
		if ($isoCollection.exists() || $gridCollection.exists() ) {

			// Show preloader
			$isoPreloader.fadeIn(50);

			$combinedCollection.not(".blog-grid-shortcode").each(function() {
				var $this = $(this);
				if($this.hasClass("blog-shortcode") || $this.hasClass("portfolio-shortcode")){
					var	$isoContainer = $this.find(".dt-isotope");
				}else{
					var	$isoContainer = $this;
				}

				// Hack to make sure that masonry will correctly calculate columns with responsive images height. 
				$(".preload-me", $isoContainer).heightHack();
				// Slider initialization
				$(".slider-masonry", $isoContainer).initSlider();
				
				$isoContainer.one("columnsReady", function() {

					//Call isotope
					if($isoContainer.hasClass("iso-container")){
						$isoContainer.IsoInitialisation('.iso-item', 'masonry', 400);
					}else{
						if($isoContainer.parent().hasClass("gradient-overlay-layout-list") || $isoContainer.parent().hasClass("content-rollover-layout-list")){
							var equalheight = false;
						}else{
							var equalheight = true;
						}
						$isoContainer.IsoInitialisation('.wf-cell', 'fitRows', 400, equalheight);
					}
					$isoContainer.isotope('on', 'layoutComplete', function (objArray){
					    //callback isotope on load ...
					    for(var i = 0; i < objArray.length; i++){
					        var obj = objArray[i];
					        var  $container = $(this);
					       $isoContainer.trigger("IsoReady");
					    }
					});
					//Blog: layout text on image
					if($isoContainer.parent().hasClass("content-rollover-layout-list") && ! $isoContainer.parent().hasClass("disable-layout-hover")){
						$this.find(".post-entry-wrapper").each(function(){
							var $this = $(this),
								$thisOfTop = $this.find(".entry-excerpt").height() + $this.find(".post-details").height();
							$this.stop().velocity({
								translateY : $thisOfTop
							}, 0);
							$this.parents(".post").first().on("mouseenter", function(e) {
								$this.stop().velocity({
									translateY : 0
								}, 0);
							});
							$this.parents(".post").first().on("mouseleave", function(e) {
								$this.stop().velocity({
									translateY : $thisOfTop
								}, 0);
							});
						})
					}
					/* !Call layzr on isotope layoutComplete */
					$isoContainer.one("IsoReady", function() {
						$isoContainer.isotope("layout");

						/*Init layzr*/
						$isoContainer.IsoLayzrInitialisation();
						 setTimeout(function () {
						 	$isoContainer.isotope("layout");
						 }, 350);

					});

					// Recalculate everything on window resize
					$window.on("columnsReady", function () {
						if($(".slider-masonry", $isoContainer).hasClass("royalReady")){
							$(".slider-masonry", $isoContainer).each(function(){
								var scroller = $(this).parents(".ts-wrap").data("thePhotoSlider");
								if(typeof scroller!= "undefined"){
									scroller.update();
								};
							});
						}
						if($isoContainer.parent().hasClass("content-rollover-layout-list") && ! $isoContainer.parent().hasClass("disable-layout-hover")){
						$this.find(".post-entry-wrapper").each(function(){
							var $this = $(this),
								$thisOfTop = $this.find(".entry-excerpt").height() + $this.find(".post-details").height();
							$this.stop().velocity({
								translateY : $thisOfTop
							}, 0);
							$this.parents(".post").first().on("mouseenter", function(e) {
								$this.stop().velocity({
									translateY : 0
								}, 0);
							});
							$this.parents(".post").first().on("mouseleave", function(e) {
								$this.stop().velocity({
									translateY : $thisOfTop
								}, 0);
							});
						})
					}

					$isoContainer.isotope("layout");

						
					});
					
				});

			});

			// Hide preloader
			$isoPreloader.stop().fadeOut(300);

		};