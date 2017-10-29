
/* #Filter for posts shortcode
================================================== */
var DTPostsJQueryFilter = (function() {
	function DTPostsJQueryFilter() {
		this.timeouts = {};
		this.config = {
			postsContainer: null,
			categoryContainer: null,
			paginatorContainer: null,
			curPage: 1,
			curCategory: '*',
			postsPerPage: -1,
			items: []
		};
	}

	DTPostsJQueryFilter.prototype.init = function(settings) {
		$.extend( this.config, settings );

		this._setPostsPerPage();
		this._setCategory();
		this._setCurPage();
		this._setItems();

		this.setup();
	};

	DTPostsJQueryFilter.prototype.setup = function() {
		$('a', this.config.paginatorContainer).on('click.dtPostsPaginationFilter', {self: this}, this.paginationFilter);
		$('a', this.config.categoryContainer).on('click.dtPostsCategoryFilter', {self: this}, this.categoryFilter);

		this._getActiveElement(this.config.paginatorContainer).trigger('click.dtPostsPaginationFilter', { onSetup: true });
	};

	DTPostsJQueryFilter.prototype.paginationFilter = function(event, onSetup) {
		event.preventDefault();

		var item = $(this);
		var self = event.data.self;

		self._setAsActive(item);
		self._setCurPage();

		if ( ! onSetup ) {
			self._scrollToTopOfContainer( self._filterPosts );
		} else {
			self._filterPosts();
		}
	};

	DTPostsJQueryFilter.prototype.categoryFilter = function(event) {
		event.preventDefault();

		var item = $(this);
		var self = event.data.self;

		self._setAsActive(item);
		self._setCategory();
		self._setAsActive(self.config.paginatorContainer.find('a').first());
		self._setCurPage(1);

		self._showPagination();
		self._filterPosts();
	};

	DTPostsJQueryFilter.prototype._showPagination = function() {
		if ( this.config.curCategory && '*' != this.config.curCategory ) {
			var itemsCount = this.config.postsContainer.find('> '+this.config.curCategory).length;
			var maxPage = Math.ceil( itemsCount / this.config.postsPerPage );
			if ( maxPage == 1 ) {
				this.config.paginatorContainer.find('a').hide();
			} else {
				this.config.paginatorContainer.find('a').each(function(index) {
					var $this = $(this);
					if ( (index + 1) > maxPage ) {
						$this.hide();
					} else {
						$this.show();
					}
				});
			}
		} else {
			this.config.paginatorContainer.find('a').show();
		}

	};

	DTPostsJQueryFilter.prototype._filterPosts = function() {
		var self = this;

		// category filter emulation
		self.config.items.css("display", "none");

		var itemsCount = 0;
		self.config.items.filter(self.config.curCategory).each(function() {
			if ( self._showOnCurPage(++itemsCount) ) {
				$(this).css("display", "block");
			}
		});
	};

	DTPostsJQueryFilter.prototype._setPostsPerPage = function() {
		this.config.postsPerPage = parseInt( this.config.postsContainer.attr('data-posts-per-page') );
	};

	DTPostsJQueryFilter.prototype._setCategory = function() {
		this.config.curCategory = this._getActiveElement(this.config.categoryContainer).attr('data-filter') || this.config.curCategory;
	};

	DTPostsJQueryFilter.prototype._setCurPage = function(page) {
		this.config.curPage = page ? page : this._getActiveElement(this.config.paginatorContainer).attr('data-page-num');
	};

	DTPostsJQueryFilter.prototype._setItems = function() {
		this.config.items = $(".wf-cell", this.config.postsContainer);
	};

	DTPostsJQueryFilter.prototype._showOnCurPage = function(index) {
		return this.config.postsPerPage <= 0 || ( this.config.postsPerPage*(this.config.curPage - 1) < index && index <= this.config.postsPerPage*this.config.curPage );
	};

	DTPostsJQueryFilter.prototype._setAsActive = function(item) {
		item.addClass('act').siblings().removeClass('act');
	};

	DTPostsJQueryFilter.prototype._getActiveElement = function(items) {
		return items.find('a.act').first();
	};

	DTPostsJQueryFilter.prototype._isActive = function(item) {
		return item.hasClass('act');
	};

	DTPostsJQueryFilter.prototype._scrollToTopOfContainer = function(onComplite) {
		var scrollTo = this.config.postsContainer.parent();

		$("html, body").animate({
			scrollTop: scrollTo.offset().top - $("#phantom").height() - 50
		}, 400, onComplite ? onComplite.bind(this) : undefined);
	};

	DTPostsJQueryFilter.prototype._setTimeout = function(id, handler, time) {
		var self = this;

		if ( ! id ) {
			handler.bind(self);
		}

		if ( this.timeouts[id] ) {
			window.clearTimeout( this.timeouts[id] );
		}

		this.timeouts[id] = window.setTimeout(handler.bind(self), time);
	};

	return DTPostsJQueryFilter;
}());

var DTPostsIsotopeFilter = (function() {
	function DTPostsIsotopeFilter() {
		DTPostsJQueryFilter.call(this);

		this.config = {
			postsContainer: null,
			categoryContainer: null,
			orderByContainer: null,
			orderContainer: null,
			paginatorContainer: null,
			curPage: 1,
			curCategory: '*',
			initialOrder: '',
			order: '',
			orderBy: '',
			postsPerPage: -1,
			items: [],
			isPhone: false
		};
	}

	DTPostsIsotopeFilter.prototype = new DTPostsJQueryFilter();

	DTPostsIsotopeFilter.prototype.init = function(settings) {
		$.extend( this.config, settings );

		this._setPostsPerPage();
		this._setCategory();
		this._setOrderBy();
		this._setOrder();
		this._setCurPage();
		this._setItems();

		this.config.initialOrder = this.config.order;

		this.setup();
	};

	DTPostsIsotopeFilter.prototype.setup = function() {
		$('a', this.config.paginatorContainer).on('click.dtPostsPaginationFilter', {self: this}, this.paginationFilter);
		$('a', this.config.categoryContainer).on('click.dtPostsCategoryFilter', {self: this}, this.categoryFilter);
		$('a', this.config.orderContainer).on('click.dtPostsOrderFilter', {self: this}, this.orderFilter);
		$('a', this.config.orderByContainer).on('click.dtPostsOrderByFilter', {self: this}, this.orderByFilter);

		this._getActiveElement(this.config.paginatorContainer).trigger('click.dtPostsPaginationFilter', { onSetup: true });
	};

	DTPostsIsotopeFilter.prototype.orderFilter = function(event) {
		event.preventDefault();

		var item = $(this);
		var self = event.data.self;

		self._setAsActive(item);
		self._setOrder();
		self._filterPosts();
	};

	DTPostsIsotopeFilter.prototype.orderByFilter = function(event) {
		event.preventDefault();

		var item = $(this);
		var self = event.data.self;

		self._setAsActive(item);
		self._setOrderBy();
		self._filterPosts();
	};

	DTPostsIsotopeFilter.prototype._filterPosts = function() {
		var self = this;
		self.config.postsContainer.resetEffects();

		self.config.postsContainer.isotope({ filter: self.config.curCategory, sortAscending: 'asc' == self.config.order, sortBy: self.config.orderBy });

		if ( self.config.curPage ) {
			self._filterByCurPage();
		}
		setTimeout(function(){
			$(".iso-container").isotope('layout');
		}, 800);
		self.config.postsContainer.IsoLayzrInitialisation();
		loadingEffects();
	};

	DTPostsIsotopeFilter.prototype._filterByCurPage = function() {
		var items = this.config.items.slice(0);
		if ( this.config.initialOrder && this.config.initialOrder != this.config.order ) {
			items.reverse();
		}

		var itemsCount = 0;
		items.map(function(item) {
			if ( ! item.isHidden && ! this._showOnCurPage(++itemsCount) ) {
				item.hide();
			}
		}, this);

		this.config.postsContainer.isotope('layout');
	};

	DTPostsIsotopeFilter.prototype._setOrderBy = function() {
		this.config.orderBy = this._getActiveElement(this.config.orderByContainer).attr('data-by');
	};

	DTPostsIsotopeFilter.prototype._setOrder = function() {
		this.config.order = this._getActiveElement(this.config.orderContainer).attr('data-sort');
	};

	DTPostsIsotopeFilter.prototype._setItems = function() {
		this.config.items = this.config.postsContainer.isotope('getItemElements').map(function(item) { return this.config.postsContainer.isotope('getItem', item); }, this);
	
	};

	return DTPostsIsotopeFilter;
}());

var DTPostsJGridFilter = (function() {
	function DTPostsJGridFilter() {}

	DTPostsJGridFilter.prototype = new DTPostsJQueryFilter();

	DTPostsJGridFilter.prototype._filterPosts = function() {
		var self = this;

		// category filter emulation
		self.config.items.css("display", "none");

		var itemsCount = 0;
		var visibleItems = [];
		self.config.items.filter(self.config.curCategory).each(function() {
			if ( self._showOnCurPage( ++itemsCount ) ) {
				$(this).css("display", "block");
				visibleItems.push( this );
			}
		});

		visibleItems = $(visibleItems);
		self.config.postsContainer.data('visibleItems', visibleItems);
		self.config.postsContainer.collage({ images: visibleItems });
	};

	DTPostsJGridFilter.prototype._setItems = function() {
		this.config.items = $(".wf-cell", this.config.postsContainer);
	};

	return DTPostsJGridFilter;
}());

var DTMasonryControls = (function () {
    function DTMasonryControls(config) {
        var defaults = {
            paginatorContainer: null,
            postLimit: 1,
            curPage: 1,
            items: [],
            onPaginate: function () {
            }
        };

        this.config = $.extend(defaults, config);
    }

    DTMasonryControls.prototype.setCurPage = function (curPage) {
        this.config.curPage = parseInt(curPage);
    };

    DTMasonryControls.prototype.getCurPage = function () {
        return this.config.curPage;
    };

    DTMasonryControls.prototype.reset = function (items) {
        this.config.items = items;
        this.setCurPage(1);
        this.appendControls();
        this._filterByCurPage();
    };

    DTMasonryControls.prototype.appendControls = function () {
    };

    DTMasonryControls.prototype._filterByCurPage = function () {
        this.showItem(this.config.items);
    };

    DTMasonryControls.prototype.hideItem = function (item) {
        item.removeClass('visible').addClass('hidden').hide();
    };

    DTMasonryControls.prototype.showItem = function (item) {
        item.addClass('visible').removeClass('hidden').show();
    };

    return DTMasonryControls;
}());

var DTMasonryPaginationControls = (function () {
    function DTMasonryPaginationControls(config) {
        DTMasonryControls.call(this, config);

        var defaults = {
            previousButtonClass: '',
            previousButtonLabel: '',
            pagerClass: '',
            nextButtonClass: '',
            nextButtonLabel: '',
            activeClass: 'act',
            pagesToShow: 5
        };

        this.config = $.extend(defaults, config);

        this.appendControls();

        $('a.act', this.config.paginatorContainer).trigger('click.dtPostsPaginationFilter', {onSetup: true});
    }

    DTMasonryPaginationControls.prototype = new DTMasonryControls();

    DTMasonryPaginationControls.prototype.addEvents = function () {
        $('a', this.config.paginatorContainer).not('.dots').on('click.dtPostsPaginationFilter', {self: this}, this.config.onPaginate);
        $('a.dots', this.config.paginatorContainer).on('click.dtPostsPaginationDots', {self: this}, function(event) {
            event.preventDefault();
            event.data.self.config.paginatorContainer.find('div:hidden a').unwrap();
            event.data.self.config.paginatorContainer.find('a.dots').remove();
        });
    };

    DTMasonryPaginationControls.prototype.appendControls = function () {
        var pageControls = this.config.paginatorContainer;
        var pageCount = Math.ceil(this.config.items.length / this.config.postLimit);
        var activePage = this.config.curPage;

        pageControls.empty();

        if (pageCount <= 1) {
            return;
        }

        var i, _i;

        if (activePage !== 1) {
            pageControls.prepend('<a href="#" class="' + this.config.previousButtonClass + '" data-page-num="' + (activePage - 1) + '">' + this.config.previousButtonLabel + '</a>');
        }

        var pagesToShow = this.config.pagesToShow | 5;
        var pagesToShowMinus1 = pagesToShow - 1;
        var pagesBefore = Math.floor(pagesToShowMinus1 / 2);
        var pagesAfter = Math.ceil(pagesToShowMinus1 / 2);
        var startPage = Math.max(activePage - pagesBefore, 1);
        var endPage = activePage + pagesAfter;

        if (startPage <= pagesBefore) {
            endPage = startPage + pagesToShowMinus1;
        }

        if (endPage > pageCount) {
            startPage = Math.max(pageCount - pagesToShowMinus1, 1);
            endPage = pageCount;
        }

        var dots = '<a href="javascript:void(0);" class="dots">…</a>';
        var leftPagesPack = $('<div style="display: none;"></div>');
        var rightPagesPack = $('<div style="display: none;"></div>');

        for (i = _i = 1; 1 <= pageCount ? _i <= pageCount : _i >= pageCount; i = 1 <= pageCount ? ++_i : --_i) {
            if (i < startPage && i != 1) {
                leftPagesPack.append('<a href="#" class="' + this.config.pagerClass + '" data-page-num="' + +i + '">' + i + '</a>');
                continue;
            }

            if (i == startPage && leftPagesPack.children().length) {
                pageControls.append(leftPagesPack).append($(dots));
            }

            if (i > endPage && i != pageCount) {
                rightPagesPack.append('<a href="#" class="' + this.config.pagerClass + '" data-page-num="' + +i + '">' + i + '</a>');
                continue;
            }

            if (i == pageCount && rightPagesPack.children().length) {
                pageControls.append(rightPagesPack).append($(dots));
            }

            pageControls.append('<a href="#" class="' + this.config.pagerClass + '" data-page-num="' + +i + '">' + i + '</a>');
        }

        if (activePage < pageCount) {
            pageControls.append('<a href="#" class="' + this.config.nextButtonClass + '" data-page-num="' + (activePage + 1) + '">' + this.config.nextButtonLabel + '</a>');
        }
        pageControls.find('a[data-page-num="' + activePage + '"]').addClass(this.config.activeClass);

        this.addEvents();
    };

    DTMasonryPaginationControls.prototype._filterByCurPage = function () {
        var self = this;
        this.config.items.get().map(function (item, index) {
            if (self._showOnCurPage(index + 1)) {
                self.showItem($(item));
            } else {
                self.hideItem($(item));
            }
        });
    };

    DTMasonryPaginationControls.prototype._showOnCurPage = function (index) {
        return this.config.postLimit <= 0 || ( this.config.postLimit * (this.getCurPage() - 1) < index && index <= this.config.postLimit * this.getCurPage() );
    };

    DTMasonryPaginationControls.prototype._setAsActive = function (item) {
        item.addClass('act').siblings().removeClass('act');
    };

    return DTMasonryPaginationControls;
}());

var DTMasonryLoadMoreControls = (function () {
    function DTMasonryLoadMoreControls(config) {
        DTMasonryControls.call(this, config);

        var defaults = {
            loadMoreButtonClass: '',
            loadMoreButtonLabel: 'Load more'
        };

        this.config = $.extend(defaults, config);

        this.appendControls();

        $('a.act', this.config.paginatorContainer).trigger('click.dtPostsPaginationFilter', {onSetup: true});
    }

    DTMasonryLoadMoreControls.prototype = new DTMasonryControls();

    DTMasonryLoadMoreControls.prototype.addEvents = function () {
        $('a', this.config.paginatorContainer).on('click.dtPostsPaginationFilter', {self: this}, this.config.onPaginate);
    };

    DTMasonryLoadMoreControls.prototype.appendControls = function () {
        var pageControls = this.config.paginatorContainer;
        var pageCount = Math.ceil(this.config.items.length / this.config.postLimit);
        var activePage = this.config.curPage;

        pageControls.empty();

        if (pageCount <= 1) {
            return;
        }

        if (activePage < pageCount) {
            pageControls.append('<a href="#" class="' + this.config.loadMoreButtonClass + '"><span class="stick"></span><span class="button-caption">' + this.config.loadMoreButtonLabel + '</span></a>').css("display", "flex");
        } else {
            pageControls.css("display", "none");
        }

        this.addEvents();
    };

    DTMasonryLoadMoreControls.prototype._filterByCurPage = function () {
        var self = this;
        var postsToShow = self.getCurPage() * self.config.postLimit;

        this.config.items.get().map(function (item, index) {
            if (index < postsToShow) {
                self.showItem($(item));
            } else {
                self.hideItem($(item));
            }
        });
    };

    return DTMasonryLoadMoreControls;
}());

var DTIsotopeFilter = (function () {
    function DTIsotopeFilter(config) {
        var defaults = {
            onCategoryFilter: function () {
            },
            onOrderFilter: function () {
            },
            onOrderByFilter: function () {
            },
            categoryContainer: null,
            orderContainer: null,
            orderByContainer: null,
            postsContainer: null,
            order: 'desc',
            orderBy: 'date',
            curCategory: '*'
        };
        this.config = $.extend(defaults, config);

        this.addEvents();
    }

    DTIsotopeFilter.prototype.addEvents = function () {
        $('a', this.config.categoryContainer).on('click.dtPostsCategoryFilter', {self: this}, this.config.onCategoryFilter);
        $('a', this.config.orderContainer).on('click.dtPostsOrderFilter', {self: this}, this.config.onOrderFilter);
        $('a', this.config.orderByContainer).on('click.dtPostsOrderByFilter', {self: this}, this.config.onOrderByFilter);
    };

    DTIsotopeFilter.prototype.setOrder = function (order) {
        this.config.order = order;
    };

    DTIsotopeFilter.prototype.setOrderBy = function (orderBy) {
        this.config.orderBy = orderBy;
    };

    DTIsotopeFilter.prototype.setCurCategory = function (curCategory) {
        this.config.curCategory = curCategory;
    };

    DTIsotopeFilter.prototype.getFilteredItems = function () {
        return $(this.config.postsContainer.isotope('getFilteredItemElements'));
    };

    DTIsotopeFilter.prototype.getItems = function () {
        return $(this.config.postsContainer.isotope('getItemElements'));
    };

    DTIsotopeFilter.prototype.layout = function () {
        this.config.postsContainer.isotope('layout');
    };

    DTIsotopeFilter.prototype.scrollToTopOfContainer = function (onComplite, bindTo) {
        var scrollTo = this.config.postsContainer.parent();

        $("html, body").animate({
            scrollTop: scrollTo.offset().top - $("#phantom").height() - 50
        }, 400, onComplite ? onComplite.bind(bindTo | this) : undefined);
    };

    DTIsotopeFilter.prototype._filterPosts = function () {
        this.config.postsContainer.isotope({
            filter: this.config.curCategory,
            sortAscending: 'asc' == this.config.order,
            sortBy: this.config.orderBy
        });
    };

    DTIsotopeFilter.prototype._setAsActive = function (item) {
        item.addClass('act').siblings().removeClass('act');
    };

    return DTIsotopeFilter;
}());

var DTJQueryFilter = (function() {
    function DTJQueryFilter(config) {
        DTIsotopeFilter.call(this, config);

        this.items = this.config.postsContainer.find('.wf-cell');
        this.filteredItems = this.items;
    }

    DTJQueryFilter.prototype = new DTIsotopeFilter();

    DTJQueryFilter.prototype.getFilteredItems = function () {
        return this.filteredItems;
    };

    DTJQueryFilter.prototype.getItems = function () {
        return this.items;
    };

    DTJQueryFilter.prototype.layout = function () {};

     DTJQueryFilter.prototype._filterPosts = function() {
         this.items.hide();
         this.filteredItems = this._sortItems(this.items.filter(this.config.curCategory));
         this.filteredItems.detach().prependTo(this.config.postsContainer);
         this.filteredItems.show();
    };

    DTJQueryFilter.prototype._sortItems = function(items) {
        var activeSort = this.config.orderBy;
        var activeOrder = this.config.order;
        var $nodes = $([]);
        $nodes.$nodesCache = $([]);

        items.each(function() {
            var $this = $(this);
            $nodes.push({
                node: this,
                $node: $this,
                name: $this.attr("data-name"),
                date: new Date($this.attr("data-date"))
            });
        });

        if (activeSort === "date" && activeOrder ==="desc") {
            $nodes.sort(function(a, b){return b.date - a.date});
        }
        else if (activeSort === "date" && activeOrder ==="asc") {
            $nodes.sort(function(a, b){return a.date - b.date});
        }
        else if (activeSort === "name" && activeOrder ==="desc") {
            $nodes.sort(function(a, b){
                var x = a.name.toLowerCase();
                var y = b.name.toLowerCase();
                if (x > y) {return -1;}
                if (x < y) {return 1;}
                return 0;
            });
        }
        else if (activeSort === "name" && activeOrder ==="asc") {
            $nodes.sort(function(a, b){
                var x = a.name.toLowerCase();
                var y = b.name.toLowerCase();
                if (x < y) {return -1;}
                if (x > y) {return 1;}
                return 0;
            });
        }

        $nodes.each(function() {
            $nodes.$nodesCache.push(this.node);
        });

        return $nodes.$nodesCache;
    };

    return DTJQueryFilter;
}());

$('.dt-shortcode.with-isotope').each(function () {
    var $this = $(this);
    var $container = $this.find('.iso-grid, .iso-container');
    var filterConfig = {
        postsContainer: $container,
        categoryContainer: $this.find('.filter-categories'),
        paginatorContainer: $this.find('.iso-paginator')
    };

    if ($container.hasClass('dt-isotope')) {
        var postsFilter = new DTPostsIsotopeFilter();
        $.extend(filterConfig, {
            orderByContainer: $this.find('.filter-extras .filter-by'),
            orderContainer: $this.find('.filter-extras .filter-sorting'),
            isPhone: dtGlobals.isPhone
        });
    } else {
        var postsFilter = new DTPostsJGridFilter();
    }

    postsFilter.init(filterConfig);
});

$('.mode-masonry.jquery-filter, .mode-grid.jquery-filter').each(function () {
    var $this = $(this);
    var $container = $this.find('.iso-grid, .iso-container');

    var filterConfig = {
        order: $this.find('.filter-extras .filter-sorting a.act').attr('data-sort'),
        orderBy: $this.find('.filter-extras .filter-by a.act').attr('data-by'),
        curCategory: $this.find('.filter-categories a.act').attr('data-filter'),
        postsContainer: $container,
        categoryContainer: $this.find('.filter-categories'),
        orderByContainer: $this.find('.filter-extras .filter-by'),
        orderContainer: $this.find('.filter-extras .filter-sorting'),
        onCategoryFilter: function (event) {
            event.preventDefault();

            var item = $(this);
            var self = event.data.self;
            
             self.config.postsContainer.resetEffects();

            self._setAsActive(item);
            self.setCurCategory(item.attr('data-filter'));
            self._filterPosts();

            paginator.hideItem(self.getItems());
            paginator.reset(self.getFilteredItems());

            self.layout();
            self.config.postsContainer.IsoLayzrInitialisation();
            lazyLoading();
            loadingEffects();
        },
        onOrderFilter: function (event) {
            event.preventDefault();

            var item = $(this);
            var self = event.data.self;

             self.config.postsContainer.resetEffects();

            self._setAsActive(item);
            self.setOrder(item.attr('data-sort'));
            self._filterPosts();

            paginator.hideItem(self.getItems());
            paginator.reset(self.getFilteredItems());

            self.layout();
            self.config.postsContainer.IsoLayzrInitialisation();
            lazyLoading();
            loadingEffects();
        },
        onOrderByFilter: function (event) {
            event.preventDefault();

            var item = $(this);
            var self = event.data.self;

             self.config.postsContainer.resetEffects();

            self._setAsActive(item);
            self.setOrderBy(item.attr('data-by'));
            self._filterPosts();

            paginator.hideItem(self.getItems());
            paginator.reset(self.getFilteredItems());

            self.layout();
            self.config.postsContainer.IsoLayzrInitialisation();
            lazyLoading();
            loadingEffects();
        }
    };

    var isoFilter = new DTIsotopeFilter(filterConfig);
 

    switch ($this.attr('data-pagination-mode')) {
        case 'load-more':
            var paginator = new DTMasonryLoadMoreControls({
                loadMoreButtonClass: 'act button-load-more',
                loadMoreButtonLabel: dtLocal.moreButtonText.loadMore,
                postLimit: $this.attr('data-post-limit'),
                curPage: 0,
                items: isoFilter.getFilteredItems(),
                paginatorContainer: $this.find('.paginator'),
                onPaginate: function (event, onSetup) {
                    event.preventDefault();

                    var self = event.data.self;

                    self.setCurPage(self.getCurPage() + 1);
                    self._filterByCurPage();
                    isoFilter.layout();

                    if (!onSetup) {
                        self.appendControls();
                    }
                }
            });
            break;
        case 'pages':
            var paginator = new DTMasonryPaginationControls({
                previousButtonClass: 'nav-prev',
                previousButtonLabel: '<i class="fa fa-long-arrow-left" aria-hidden="true"></i>',
                nextButtonClass: 'nav-next',
                nextButtonLabel: '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>',
                postLimit: $this.attr('data-post-limit'),
                curPage: 1,
                pagesToShow: ($this.hasClass('show-all-pages') ? 999 : 5),
                items: isoFilter.getFilteredItems(),
                paginatorContainer: $this.find('.paginator'),
                onPaginate: function (event, onSetup) {
                    event.preventDefault();

                    var item = $(this);
                    var self = event.data.self;

                    self._setAsActive(item);
                    self.setCurPage(item.attr('data-page-num'));
                    self._filterByCurPage();
                    isoFilter.layout();

                    if (!onSetup) {
                        self.appendControls();
                        isoFilter.scrollToTopOfContainer();
                    }
                }
            });
            break;
        default:
            // Dummy pagination.
            var paginator = new DTMasonryControls();
    }

    function lazyLoading() {
        if ($this.hasClass("lazy-loading-mode")) {
            var buttonOffset = $this.find('.button-load-more').offset();
            if (buttonOffset && $window.scrollTop() > (buttonOffset.top - $window.height()) / 2) {
                $this.find('.button-load-more').trigger('click');

            }

        }
    }

    $window.on('scroll', function () {
        lazyLoading();
    });
    lazyLoading();
});

}); // jQuery(document).ready();
