

jQuery(document).ready(function($) {
	//Cache variables
	var $document = $(document),
		$window = $(window),
		$html = $("html"),
		$body = $("body"),
		$page = $("#page");

	/*!- Custom resize function*/
	var dtResizeTimeout;
	if(dtGlobals.isMobile && !dtGlobals.isWindowsPhone && !dtGlobals.isAndroid){
		$window.bind("orientationchange", function(event) {
			clearTimeout(dtResizeTimeout);
			dtResizeTimeout = setTimeout(function() {
				$window.trigger( "debouncedresize" );
			}, 200);
		});
	}else{
		$window.on("resize", function() {
			clearTimeout(dtResizeTimeout);
			dtResizeTimeout = setTimeout(function() {
				$window.trigger( "debouncedresize" );
			}, 200);
		});
	}
	/* #Retina images using srcset polyfill
	================================================== */
	//Layzy img loading
	$.fn.layzrInitialisation = function(container) {
	  return this.each(function() {
	      var $this = $(this);

	      var layzr = new Layzr({
	        container: container,
	        selector: '.lazy-load',
	        attr: 'data-src',
	        attrSrcSet: 'data-srcset',
	        retinaAttr: 'data-src-retina',
	        hiddenAttr: 'data-src-hidden',
	        threshold: 0,
	        callback: function() {

			 	// console.log(this)
	          	this.classList.add("is-loaded");
	         	var $this =  $(this);
	         	setTimeout(function(){
					$this.parents().removeClass("layzr-bg");
				}, 350)
				if($this.parents().hasClass("owl-carousel")){
					$this.parents(".owl-carousel").trigger('refresh.owl.carousel');
				}
	        },
	        after: function() {
	        	var $this =  $(this);
	        	if(this.complete && !$this.hasClass("is-loaded") ){
						this.classList.add("is-loaded");
					setTimeout(function(){
						var $this =  $(this);
					
						$this.parents().removeClass("layzr-bg");
					}, 350)
				}
	        }
	      });
	    });
	};
	$(".layzr-loading-on, .vc_single_image-img").layzrInitialisation();

	$.fn.layzrBlogInitialisation = function(container) {
	  return this.each(function() {
	      var $this = $(this);
	      $(".blog-shortcode.jquery-filter article").removeClass("shown");
	      $this.addClass("shown");
	      $this.find("img").addClass("blog-thumb-lazy-load-show");

	      var layzrBlog = new Layzr({
	        container: container,
	        selector: ".blog-thumb-lazy-load-show",
	        attr: 'data-src',
	        attrSrcSet: 'data-srcset',
	        retinaAttr: 'data-src-retina',
	        hiddenAttr: 'data-src-hidden',
	        threshold: 30,
	        callback: function() {
	        	if($(this).parents(".post").first().hasClass("visible")){
	          		this.classList.add("is-loaded");
		         	var $this =  $(this);
		         	setTimeout(function(){
						$this.parent().removeClass("layzr-bg");
					}, 350);
				}
	        }
	      });
	    });
	};
	$(".layzr-loading-on .blog-shortcode.jquery-filter.mode-list .visible").layzrBlogInitialisation();

	/*Call visual composer function for preventing full-width row conflict */
	if($('div[data-vc-stretch-content="true"]').length > 0 && $('div[data-vc-full-width-init="false"]').length > 0){
		vc_rowBehaviour();

	}
