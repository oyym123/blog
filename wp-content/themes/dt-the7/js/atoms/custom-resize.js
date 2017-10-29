
	$window.on("debouncedresize", function( event ) {
		dtGlobals.resizeCounter++;

		//Photos widget
		if ( $.isFunction($.fn.calcPics) ) {
			$(".instagram-photos").calcPics();
		}
		//Filter responsiveness
		$.mobileHeader();
		$.headerBelowSlider();

		/*Mobile header*/
		if(window.innerWidth >= dtLocal.themeSettings.mobileHeader.firstSwitchPoint){
			$page.removeClass("show-mobile-header");
			$page.addClass("closed-mobile-header");
			$body.removeClass("show-sticky-mobile-header");
			$body.removeClass("show-overlay-mobile-header").addClass("closed-overlay-mobile-header");
			$(".mobile-sticky-header-overlay").removeClass("active");
			$(".dt-mobile-menu-icon").removeClass("active");
			$html.removeClass("menu-open");
			if (!headerBelowSliderExists ) {
				if (!bodyTransparent) {
					$('.masthead:not(.mixed-header):not(#phantom):not(.side-header)')
					.velocity({
						translateY : "",
					}, 0);
				}
			}
		}
		if(window.innerWidth <= dtLocal.themeSettings.mobileHeader.firstSwitchPoint){
			$('.masthead:not(.mixed-header):not(#phantom)').addClass("masthead-mobile");
		}else{
			$('.masthead:not(.mixed-header):not(#phantom)').removeClass("masthead-mobile");
		}
		
		//Custom select
		$('.mini-nav select').trigger('render');
		
		//Fancy headers
		$.fancyFeaderCalc();

		
		/*Detect first/last visible item microwidgets*/
		$(".mini-widgets, .mobile-mini-widgets").find(" > *").removeClass("first last");
		$(".mini-widgets, .mobile-mini-widgets").find(" > *:visible:first").addClass("first");
		$(".mini-widgets, .mobile-mini-widgets").find(" > *:visible:last").addClass("last");
	
		//Stripe Video bg
		$(".stripe-video-bg > video").each(function(){
			if($(".header-side-line").length > 0 && !$(".boxed").length > 0 ){
	            var sideHW = $(".side-header-v-stroke").width();
	        }else if(!$("body").hasClass("sticky-header") && !$("body").hasClass("overlay-navigation") && $(".side-header").length > 0){
	            var sideHW = $(".side-header").width();
	        }else{
	            var sideHW = 0;
	        }
	      	var stripePadL  = 2000 + sideHW,
	      		 pageOfL  = stripePadL - $(".content").position().left - 22;

			var $_this = $(this),
				$this_h = $_this.height(),
				$pageW = $("#page").width();
			$_this.css({
				left: pageOfL,
				width: $pageW
			});
		});
		
		//Set full height stripe
		$(".stripe, .dt-default").each(function(){
			var $_this = $(this),
				$_this_min_height = $_this.attr("data-min-height");
			if($.isNumeric($_this_min_height)){
				$_this.css({
					"minHeight": $_this_min_height + "px"
				});
			}else if(!$_this_min_height){
				$_this.css({
					"minHeight": 0
				});
			}else if($_this_min_height.search( '%' ) > 0){
				$_this.css({
					"minHeight": $window.height() * (parseInt($_this_min_height)/100) + "px"
				});
			}else{
				$_this.css({
					"minHeight": $_this_min_height
				});
			};
		});

		/*Floating content*/
		
		$parentHeight = $floatContent.parent().height();
		$floatContentHeight = $floatContent.height();
		

		/* Sticky footer */

		$(".mobile-false .footer-overlap .page-inner").css({
			'min-height': window.innerHeight - $(".footer").innerHeight(),
			'margin-bottom': $(".footer").innerHeight()
		});

	}).trigger( "debouncedresize" );