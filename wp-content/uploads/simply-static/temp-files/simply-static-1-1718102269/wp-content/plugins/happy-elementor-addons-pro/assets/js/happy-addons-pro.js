'use strict';
window.Happy = window.Happy || {};

(function ($, Happy, w) {
	var $window = $(w);

	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	};

    $window.on('elementor/frontend/init', function () {

		// Set user time in cookie
		var HappyLocalTimeZone = new Date().toString().match(/([A-Z]+[\+-][0-9]+.*)/)[1];
		var ha_secure = (document.location.protocol === 'https:') ? 'secure' : '';
		document.cookie = "HappyLocalTimeZone="+HappyLocalTimeZone+";SameSite=Strict;"+ha_secure;

		var CountDown = function ($scope) {
			var $item = $scope.find('.ha-countdown');
			var $countdown_item = $item.find('.ha-countdown-item');
			var $end_action = $item.data('end-action');
			var $redirect_link = $item.data('redirect-link');
			var $end_action_div = $item.find('.ha-countdown-end-action');
			var $editor_mode_on = $scope.hasClass('elementor-element-edit-mode');
			$item.countdown({
				end: function () {
					if (('message' === $end_action || 'img' === $end_action) && $end_action_div !== undefined) {
						$countdown_item.css("display", "none");
						$end_action_div.css("display", "block");
					} else if ('url' === $end_action && $redirect_link !== undefined && $editor_mode_on !== true) {
						window.location.replace($redirect_link)
					}
				}
			});
		};

		var CarouselBase = elementorModules.frontend.handlers.Base.extend({
			onInit: function () {
				elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.run();
			},

			getDefaultSettings: function () {
				return {
					selectors: {
						container: '.ha-carousel-container'
					},
					arrows: false,
					dots: false,
					checkVisible: false,
					infinite: true,
					slidesToShow: 3,
					rows: 0,
					prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
					nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
				}
			},

			getDefaultElements: function () {
				var selectors = this.getSettings('selectors');
				return {
					$container: this.findElement(selectors.container)
				};
			},

			onElementChange: function () {
				this.elements.$container.slick('unslick');
				this.run();
			},

			getReadySettings: function () {
				var settings = {
					infinite: !!this.getElementSettings('loop'),
					autoplay: !!this.getElementSettings('autoplay'),
					autoplaySpeed: this.getElementSettings('autoplay_speed'),
					speed: this.getElementSettings('animation_speed'),
					centerMode: !!this.getElementSettings('center'),
					vertical: !!this.getElementSettings('vertical'),
					slidesToScroll: 1,
				};

				switch (this.getElementSettings('navigation')) {
					case 'arrow':
						settings.arrows = true;
						break;
					case 'dots':
						settings.dots = true;
						break;
					case 'both':
						settings.arrows = true;
						settings.dots = true;
						break;
				}

				settings.slidesToShow = parseInt( this.getElementSettings('slides_to_show') ) || 1;
				settings.responsive = [
					{
						breakpoint: elementorFrontend.config.breakpoints.lg,
						settings: {
							slidesToShow: (parseInt(this.getElementSettings('slides_to_show_tablet')) || settings.slidesToShow),
						}
					},
					{
						breakpoint: elementorFrontend.config.breakpoints.md,
						settings: {
							slidesToShow: (parseInt(this.getElementSettings('slides_to_show_mobile')) || parseInt(this.getElementSettings('slides_to_show_tablet'))) || settings.slidesToShow,
						}
					}
				];

				return $.extend({}, this.getSettings(), settings);
			},

			run: function () {
				this.elements.$container.slick(this.getReadySettings());
			}
		});

		// Source Code
		var SourceCode = function ($scope) {
			var $item = $scope.find('.ha-source-code');
			var $lng_type = $item.data('lng-type');
			var $after_copy_text = $item.data('after-copy');
			var $code = $item.find('code.language-' + $lng_type);
			var $copy_btn = $scope.find('.ha-copy-code-button');

			$copy_btn.on('click', function () {
				var $temp = $("<textarea>");
				$(this).append($temp);
				$temp.val($code.text()).select();
				document.execCommand("copy");
				$temp.remove();
				if ($after_copy_text.length) {
					$(this).text($after_copy_text);
				}
			});

			if ($lng_type !== undefined && $code !== undefined) {
				Prism.highlightElement($code.get(0));
			}
		};

		// Animated text
		var AnimatedText = function ($scope) {
			var $item = $scope.find('.cd-headline'),
				$animationDelay = $item.data('animation-delay');
			happyAnimatedText({
				selector: $item,
				animationDelay: $animationDelay,
			});
		};

		//Instagram Feed
		var InstagramFeed = function($scope) {
			var button = $scope.find('.ha-insta-load-more');
			var instagram_wrap = $scope.find('.ha-insta-default');
			button.on("click", function(e) {
				e.preventDefault();
				var $self = $(this),
				query_settings = $self.data("settings"),
				total = $self.data("total"),
				items = $scope.find('.ha-insta-item').length;
				$.ajax({
					url: HappyProLocalize.ajax_url,
					type: 'POST',
					data: {
						action: "ha_instagram_feed_action",
						security: HappyProLocalize.nonce,
						query_settings: query_settings,
						loaded_item: items,
					},
					success: function(response) {
						if(total > items){
							$(response).appendTo(instagram_wrap);
						}else{
							$self.text('All Loaded').addClass('loaded');
							setTimeout( function(){
								$self.css({"display": "none"});
							},800);
						}

					},
					error: function(error) {}
				});
			});
		};

		//Scrolling Image
		var ScrollingImage = elementorModules.frontend.handlers.Base.extend({

			onInit: function () {
				elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.wrapper = this.$element.find('.ha-scrolling-image-wrapper');
				this.run();
			},
			onElementChange: function () {
				this.run();
			},
			run: function () {
				var container_height = this.wrapper.innerHeight(),
					container_width = this.wrapper.innerWidth(),
					image_align = this.wrapper.data('align'),
					scroll_direction = this.wrapper.data('scroll-direction'),
					items = this.wrapper.find('.ha-scrolling-image-container'),
					single_image_box = items.find('.ha-scrolling-image-item'),
					scroll = 'scroll' + image_align + scroll_direction + parseInt(container_height) + parseInt(container_width),
					duration = this.wrapper.data('duration'),
					direction = 'normal',
					horizontal_align_width = 10,
					vertical_align_height = 10;

				var start = {'transform': 'translateY('+container_height+'px)'},
					end = {'transform': 'translateY(-101%)'};
				if('bottom' === scroll_direction || 'right' === scroll_direction){
					direction = 'reverse';
				}
				if('ha-horizontal' === image_align){
					start = {'transform': 'translateX('+container_width+'px)'};
					end = {'transform': 'translateX(-101%)'};
					single_image_box.each(function(){
						horizontal_align_width += $(this).outerWidth(true);
					});
					items.css({'width':horizontal_align_width,'display':'flex'});
					items.find('img').css({'max-width':'100%'});
					single_image_box.css({'display':'inline-flex'});
				}
				if('ha-vertical' === image_align){
					single_image_box.each(function(){
						vertical_align_height += $(this).outerHeight(true);
					});
				}
				$.keyframe.define([{
					name: scroll,
					'0%': start,
					'100%':end,
				}]);

				items.playKeyframe({
					name: scroll,
					duration: duration.toString() + 'ms',
					timingFunction: 'linear',
					delay: '0s',
					iterationCount: 'infinite',
					direction: direction,
					fillMode: 'none',
					complete: function(){
					}
				});
			}
		});

		//Pricing Table ToolTip
		var PricingTableToolTip = function($scope) {
			var tooltip_area = $scope.find('.ha-pricing-table-feature-tooltip');
			tooltip_area.on({
				mouseenter: function (e) {
					var $this = $(this),
						direction = $this[0].getBoundingClientRect(),
						tooltip = $this.find('.ha-pricing-table-feature-tooltip-text'),
						tooltipW = tooltip.outerWidth(true),
						tooltipH = tooltip.outerHeight(true),
						W_width = $(window).width(),
						W_height = $(window).height();
					tooltip.css({ left: '0', right: 'auto', top: 'calc(100% + 1px)', bottom: 'auto' });
					if(W_width - direction.left < tooltipW && direction.right < tooltipW){
						tooltip.css({ left: 'calc(50% - ('+tooltipW+'px/2))'});
					}else if(W_width - direction.left < tooltipW){
							tooltip.css({ left: 'auto', right: '0' });
					}
					if(W_height - direction.bottom < tooltipH){
							tooltip.css({ top: 'auto', bottom: 'calc(100% + 1px)' });
					}
				}
			});

		};

		var TabHandlerBase = elementorModules.frontend.handlers.Base.extend({
			$activeContent: null,

			getDefaultSettings: function () {
				return {
					selectors: {
						tabTitle: '.ha-tab__title',
						tabContent: '.ha-tab__content'
					},
					classes: {
						active: 'ha-tab--active'
					},
					showTabFn: 'show',
					hideTabFn: 'hide',
					toggleSelf: false,
					hidePrevious: true,
					autoExpand: true
				};
			},

			getDefaultElements: function () {
				var selectors = this.getSettings('selectors');

				return {
					$tabTitles: this.findElement(selectors.tabTitle),
					$tabContents: this.findElement(selectors.tabContent)
				};
			},

			activateDefaultTab: function () {
				var settings = this.getSettings();

				if (!settings.autoExpand || 'editor' === settings.autoExpand && !this.isEdit) {
					return;
				}

				var defaultActiveTab = this.getEditSettings('activeItemIndex') || 1,
					originalToggleMethods = {
						showTabFn: settings.showTabFn,
						hideTabFn: settings.hideTabFn
					};

				// Toggle tabs without animation to avoid jumping
				this.setSettings({
					showTabFn: 'show',
					hideTabFn: 'hide'
				});

				this.changeActiveTab(defaultActiveTab);

				// Return back original toggle effects
				this.setSettings(originalToggleMethods);
			},

			deactivateActiveTab: function (tabIndex) {
				var settings = this.getSettings(),
					activeClass = settings.classes.active,
					activeFilter = tabIndex ? '[data-tab="' + tabIndex + '"]' : '.' + activeClass,
					$activeTitle = this.elements.$tabTitles.filter(activeFilter),
					$activeContent = this.elements.$tabContents.filter(activeFilter);

				$activeTitle.add($activeContent).removeClass(activeClass);

				$activeContent[settings.hideTabFn]();
			},

			activateTab: function (tabIndex) {
				var settings = this.getSettings(),
					activeClass = settings.classes.active,
					$requestedTitle = this.elements.$tabTitles.filter('[data-tab="' + tabIndex + '"]'),
					$requestedContent = this.elements.$tabContents.filter('[data-tab="' + tabIndex + '"]');

				$requestedTitle.add($requestedContent).addClass(activeClass);

				$requestedContent[settings.showTabFn]();
			},

			isActiveTab: function (tabIndex) {
				return this.elements.$tabTitles.filter('[data-tab="' + tabIndex + '"]').hasClass(this.getSettings('classes.active'));
			},

			bindEvents: function () {
				var _this = this;

				this.elements.$tabTitles.on({
					keydown: function keydown(event) {
						if ('Enter' === event.key) {
							event.preventDefault();

							_this.changeActiveTab(event.currentTarget.getAttribute('data-tab'));
						}
					},
					click: function click(event) {
						event.preventDefault();

						_this.changeActiveTab(event.currentTarget.getAttribute('data-tab'));
					}
				});
			},

			onInit: function () {
				elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.activateDefaultTab();
			},

			onEditSettingsChange: function (propertyName) {
				if ('activeItemIndex' === propertyName) {
					this.activateDefaultTab();
				}
			},

			changeActiveTab: function (tabIndex) {
				var isActiveTab = this.isActiveTab(tabIndex),
					settings = this.getSettings();

				if ((settings.toggleSelf || !isActiveTab) && settings.hidePrevious) {
					this.deactivateActiveTab();
				}

				if (!settings.hidePrevious && isActiveTab) {
					this.deactivateActiveTab(tabIndex);
				}

				if (!isActiveTab) {
					this.activateTab(tabIndex);
				}
			}
		});

		// TimeLine
		var TimeLine = function($scope) {
			var T_ID = $scope.data('id');
			var timeline = $scope.find('.ha-timeline-wrap');
			var dataScroll = timeline.data('scroll');
			var timeline_block = timeline.find('.ha-timeline-block');
			var event = "scroll.timelineScroll"+T_ID+" resize.timelineScroll"+T_ID;

            function scroll_tree() {
                timeline_block.each(function () {
                    var block_height = $(this).outerHeight(true);
                    var $offsetTop = $(this).offset().top;
                    var window_middle_p = $window.scrollTop() + $window.height() / 2;
                    if ($offsetTop < window_middle_p) {
                        $(this).addClass("ha-timeline-scroll-tree");
                    } else {
                        $(this).removeClass("ha-timeline-scroll-tree");
                    }
                    var scroll_tree_wrap = $(this).find('.ha-timeline-tree-inner');
                    var scroll_height = ($window.scrollTop() - scroll_tree_wrap.offset().top) + ($window.outerHeight() / 2);

                    if ($offsetTop < window_middle_p && timeline_block.hasClass('ha-timeline-scroll-tree')) {
                        if (block_height > scroll_height) {
                            scroll_tree_wrap.css({"height": scroll_height * 1.5 + "px",});
                        } else {
                            scroll_tree_wrap.css({"height": block_height * 1.2 + "px",});
                        }
                    } else {
                        scroll_tree_wrap.css("height", "0px");
                    }
                });
            }

			if( 'yes' === dataScroll) {
				scroll_tree();
				$window.on(event, scroll_tree);
			}else{
				$window.off(event);
			}
		};

        var HotSpots = elementorModules.frontend.handlers.Base.extend({
            onInit: function () {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                this.init();
            },

            bindEvents: function() {
            	if (!this.isEdit) {
                    this.elements.$items.on('click.spotClick', function(e) {
                    	e.preventDefault();
                    });
				}
			},

            getDefaultSettings: function () {
                return {
                    selectors: {
                        item: '.ha-hotspots__item'
                    },
                }
            },

            getDefaultElements: function () {
                return {
                    $items: this.findElement(this.getSettings('selectors.item'))
                };
            },

            onElementChange: function(changedProp) {
				if ( ! this.hasTipso() ) {
					return
				}

            	if (changedProp.indexOf('tooltip_') === 0) {
                    this.elements.$items.tipso('destroy');
					this.init();
                }
			},

			hasTipso: function() {
				return $.fn['tipso'];
			},

            init: function () {
            	var position = this.getElementSettings('tooltip_position'),
					width = this.getElementSettings('tooltip_width'),
					background = this.getElementSettings('tooltip_bg_color'),
                	color = this.getElementSettings('tooltip_color'),
                	speed = this.getElementSettings('tooltip_speed'),
                	delay = this.getElementSettings('tooltip_delay'),
                	hideDelay = this.getElementSettings('tooltip_hide_delay'),
                	hideArrow = this.getElementSettings('tooltip_hide_arrow'),
                	hover = this.getElementSettings('tooltip_hover'),
					elementId = this.getID();

				if ( ! this.hasTipso() ) {
					return
				}

            	this.elements.$items.each(function(index, item) {
            		var $item = $(item),
						target = $item.data('target'),
						settings = $item.data('settings'),
						classes = [
                            'ha-hotspots--' + elementId,
                            'elementor-repeater-item-' + target,
						];
            		$item.tipso({
                        color: color,
                        width: width.size || 200,
                        position: settings.position || position,
						speed: speed,
						delay: delay,
                        showArrow: !hideArrow,
                        tooltipHover: !!hover,
                        hideDelay: hideDelay,
                        background: background,
						useTitle: false,
                        content: $('#ha-' + target).html(),
                        onBeforeShow: function($e, e, tooltip) {
                            tooltip.tipso_bubble.addClass(classes.join(' '));
						}
					});
                });
            }
        });

		var LineChart = function( $scope ) {
			elementorFrontend.waypoint($scope, function () {
				var $chart = $(this),
					$container = $chart.find( '.ha-line-chart-container' ),
					$chart_canvas = $chart.find( '#ha-line-chart' ),
					settings      = $container.data( 'settings' );

				if ( $container.length ) {
					var chart = new Chart( $chart_canvas, settings );
				}
			} );
		};

		var RadarChart = function( $scope ) {
			elementorFrontend.waypoint($scope, function () {
				var $chart = $(this),
					$container = $chart.find( '.ha-radar-chart-container' ),
					$chart_canvas = $chart.find( '#ha-radar-chart' ),
					settings      = $container.data( 'settings' );

				if ( $container.length ) {
					var chart = new Chart( $chart_canvas, settings );
				}
			} );
		};

		var PolarChart = function( $scope ) {
			elementorFrontend.waypoint($scope, function () {
				var $chart = $(this),
					$container = $chart.find( '.ha-polar-chart-container' ),
					$chart_canvas = $chart.find( '#ha-polar-chart' ),
					settings      = $container.data( 'settings' );

				if ( $container.length ) {
					var chart = new Chart( $chart_canvas, settings );
				}
			} );
		};

		var PieChart = function( $scope ) {
			elementorFrontend.waypoint($scope, function () {
				var $chart = $(this),
					$container = $chart.find( '.ha-pie-chart-container' ),
					$chart_canvas = $chart.find( '#ha-pie-chart' ),
					settings      = $container.data( 'settings' );

				if ( $container.length ) {
					var chart = new Chart( $chart_canvas, settings );
				}
			} );
		};

		var StickyVideoArray = [];
		var StickyVideo = function( $scope ) {
			var $id = '#ha-sticky-video-player-' + $scope.data('id'),
				$wrap = $scope.find( '.ha-sticky-video-wrap' ),
				$settting = $wrap.data( 'ha-player' ),
				$box = $wrap.find( '.ha-sticky-video-box' ),
				$overlay_box = $wrap.find( '.ha-sticky-video-overlay' ),
				$overlay_play = $overlay_box.find( '.ha-sticky-video-overlay-icon' ),
				$sticky_close = $wrap.find( '.ha-sticky-video-close i' ),
				$all_box = $('.ha-sticky-video-box'),
				event = "scroll.haStickyVideo"+$scope.data('id'),
				//event = "scroll.haStickyVideo"+$scope.data('id')+" resize.haStickyVideo"+$scope.data('id'),
				set;
				//console.log(StickyVideoArray);

				var option = {
					autoplay: $settting.autoplay,
					muted: $settting.muted,
					loop: $settting.loop,
					clickToPlay: false,
					hideControls: false,
					// youtube: {
					// 	start: '30',
					// 	end: '45',
					// },
				}

				/* var playerAbc = new Plyr('#player', {
					title: 'Example Title',
					// autoplay: true,
					youtube: {
						start: '30',
						end: '45',
					},
				  }); */
				var playerAbc = new Plyr($id);
				var StickyVideoObject = {
					'player' : playerAbc,
					'event' : event,
					'player_box' : $box,
				}
				StickyVideoArray.push(StickyVideoObject);
				//on overlay click
				if( 0 !== $overlay_box.length ){
					var $el = 0 !== $overlay_play.length ? $overlay_play : $overlay_box;
					$el.on('click',function(){
						playerAbc.play();
					});
				}

				//on close sticky
				$sticky_close.on('click',function(){
					$box.removeClass("sticky");
					$box.addClass("sticky-close");
					playerAbc.pause();
				});

				//on Play
				playerAbc.on('play', function (e) {
					//console.log(e,StickyVideoArray);
					$overlay_box.css('display', 'none');
					if( $box.hasClass('sticky-close') ){
						$box.removeClass("sticky-close");
					}
					StickyVideoArray.forEach( function(item, index) {
						//console.log( item );
							if( item.player !== playerAbc ){
								//console.log('push');
								item.player.pause();
							}
							if( item.event !== event ){
								$window.off(item.event);
								//console.log('event');
							}
							if( item.player_box !== $box ){
								item.player_box.removeClass("sticky");
								//console.log('removeClass');
							}
					});

					//$all_box.removeClass("sticky");
				    //console.log($settting,$id);
					if( true === $settting.sticky){
						$window.on( event, function(){

							var height = $box.outerHeight(true);
							var $offsetTop = $wrap.offset().top;
							var videoBoxTopPoint = $window.scrollTop();
							var videoBoxMiddlePoint = $offsetTop + height / 2;
							if( ! $box.hasClass('sticky-close') ){
								if ( videoBoxMiddlePoint < videoBoxTopPoint) {
									$box.addClass("sticky");
								} else {
									$box.removeClass("sticky");
								}
							}

						});
					}else{
						$window.off(event);
					}
				});

				// on pause
				playerAbc.on('pause', function (e) {
					$window.off(event);
				});

				$window.on("load resize", debounce(function () {
					//console.log($box.find( 'iframe' ));
					// cannot rely on iframe here cause iframe has extra height
					var height = $box.find( '.plyr' ).height();

					$wrap.css('min-height', height + 'px');
				}, 100));

				/* var event = "scroll.timelineScroll"+T_ID+" resize.timelineScroll"+T_ID;

            function scroll_tree() {
                timeline_block.each(function () {
                    var block_height = $(this).outerHeight(true);
                    var $offsetTop = $(this).offset().top;
                    var window_middle_p = $window.scrollTop() + $window.height() / 2;
                    if ($offsetTop < window_middle_p) {
                        $(this).addClass("ha-timeline-scroll-tree");
                    } else {
                        $(this).removeClass("ha-timeline-scroll-tree");
                    }
                    var scroll_tree_wrap = $(this).find('.ha-timeline-tree-inner');
                    var scroll_height = ($window.scrollTop() - scroll_tree_wrap.offset().top) + ($window.outerHeight() / 2);

                    if ($offsetTop < window_middle_p && timeline_block.hasClass('ha-timeline-scroll-tree')) {
                        if (block_height > scroll_height) {
                            scroll_tree_wrap.css({"height": scroll_height * 1.5 + "px",});
                        } else {
                            scroll_tree_wrap.css({"height": block_height * 1.2 + "px",});
                        }
                    } else {
                        scroll_tree_wrap.css("height", "0px");
                    }
                });
            }

			if( 'yes' === dataScroll) {
				scroll_tree();
				$window.on(event, scroll_tree);
			}else{
				$window.off(event);
			} */


		};

		//facebook feed
		var FacebookFeed = function($scope) {
			var button = $scope.find('.ha-facebook-load-more');
			var facebook_wrap = $scope.find('.ha-facebook-items');
			button.on("click", function(e) {
				e.preventDefault();
				var $self = $(this),
					query_settings = $self.data("settings"),
					total = $self.data("total"),
					items = $scope.find('.ha-facebook-item').length;
				$.ajax({
					url: HappyProLocalize.ajax_url,
					type: 'POST',
					data: {
						action: "ha_facebook_feed_action",
						security: HappyProLocalize.nonce,
						query_settings: query_settings,
						loaded_item: items,
					},
					success: function(response) {
						if(total > items){
							$(response).appendTo(facebook_wrap);
						}else{
							$self.text('All Loaded').addClass('loaded');
							setTimeout( function(){
								$self.css({"display": "none"});
							},800);
						}
					},
					error: function(error) {}
				});
			});
		};

		//SmartPostList
		var SmartPostList = function($scope) {
			var filterWrap = $scope.find('.ha-spl-filter'),
				customSelect = $scope.find('.ha-spl-custom-select'),
				gridWrap = $scope.find('.ha-spl-grid-area'),
				mainWrapper = $scope.find('.ha-spl-wrapper'),
				querySettings = mainWrapper.data("settings"),

				dataOffset = mainWrapper.attr("data-offset"),

				nav = $scope.find('.ha-spl-pagination button'),
			    navPrev = $scope.find('.ha-spl-pagination button.prev'),
				navNext = $scope.find('.ha-spl-pagination button.next'),
				filter = $scope.find('ul.ha-spl-filter-list li span'),
				event;

				customSelect.niceSelect();

			var select = $scope.find('.ha-spl-custom-select li');

			function afterClick(e){
				e.preventDefault();
				var $self = $(this),
				dataFilter = filterWrap.attr("data-active"),
				dataTotalOffset = mainWrapper.attr("data-total-offset"),
				offset = '',
				termId = '';

				if(  e.target.classList.contains('prev') || e.target.classList.contains('fa-angle-left')  ){
					if(0 == parseInt(dataTotalOffset)){
						navPrev.attr("disabled", true);
						return;
					}
					offset = 0 !== parseInt(dataTotalOffset) ? ( parseInt(dataTotalOffset) - parseInt(dataOffset) ) : '0';
					if( undefined !== dataFilter ){
						termId = dataFilter;
					}
				}

				else if( e.target.classList.contains('next') || e.target.classList.contains('fa-angle-right') ){
					offset = parseInt(dataTotalOffset) + parseInt(dataOffset);
					if( undefined !== dataFilter ){
						termId = dataFilter;
					}
				}

				if( e.target.hasAttribute("data-value") ){

					termId = e.target.dataset['value'];

					filterWrap[0].setAttribute('data-active', termId);
					if( 'SPAN' === e.target.tagName ){
						filter.removeClass('ha-active');
						e.target.classList.toggle('ha-active');
					}

					offset = 0;
					navPrev.attr("disabled", true);
					navNext.removeAttr('disabled');
				}

				$.ajax({
					url: HappyProLocalize.ajax_url,
					type: 'POST',
					data: {
						action: "ha_smart_post_list_action",
						security: HappyProLocalize.nonce,
						querySettings: querySettings,
						offset: offset,
						termId: termId,
					},
					success: function(response) {

						if( $(response).length > 0 ){

							gridWrap.css('height',gridWrap.outerHeight(true) + 'px');
							gridWrap.html('');
							$(response).appendTo(gridWrap);
							gridWrap.css('height','');
							mainWrapper[0].attributes['data-total-offset'].value = offset;

							if(  e.target.classList.contains('prev') || e.target.classList.contains('fa-angle-left')  ){
								navNext.removeAttr('disabled');
							}
							if( e.target.classList.contains('next') || e.target.classList.contains('fa-angle-right') ){
								navPrev.removeAttr('disabled');
							}
						}else{
							if( e.target.classList.contains('next') || e.target.classList.contains('fa-angle-right') ){
								navNext.attr("disabled", true);
							}
						}

					},
					error: function(error) {}
				});
			};

			nav.on("click", afterClick);
			filter.on("click", afterClick);
			select.on("click", afterClick);
		};

		//PostGrid
		var postGridSkins = [
			'classic',
			'hawai',
			'standard',
			'monastic',
			'stylica',
			'outbox',
			'crossroad',
		];
		var PostGrid = function($scope) {
			var wrapper = $scope.find('.ha-pg-wrapper'),
				gridWrap = wrapper.find('.ha-pg-grid-wrap'),
				button = wrapper.find('button.ha-pg-loadmore');
				button.on("click", function(e) {
					e.preventDefault();
					var $self = $(this),
					querySettings = $self.data("settings"),
					items = wrapper.find('.ha-pg-item').length;

					$self.attr("disabled", true);

					$.ajax({
						url: HappyProLocalize.ajax_url,
						type: 'POST',
						data: {
							action: "hapro_post_grid_action",
							security: HappyProLocalize.nonce,
							querySettings: querySettings,
							loadedItem: items,
						},
						beforeSend: function () {
							$self.find('.eicon-loading').css({"display": "inline-block"});
						},
						success: function(response) {
							if( response ){
								$(response).each( function(){
									var $self = $(this); // every item
									if($self.hasClass('ha-pg-item')){
										$self.addClass('ha-pg-item-loaded').appendTo( gridWrap );
									}else{
										$self.appendTo( gridWrap );
									}
								});
							}else{
								$self.text('All Loaded').addClass('loaded');
								setTimeout( function(){
									$self.css({"display": "none"});
								},800);
							}
							$self.find('.eicon-loading').css({"display": "none"});
							$self.removeAttr('disabled');

						},
						error: function(error) {}
					});
				});
		};


		// Advanced Data Table
		var DataTable = function( $scope ) {
			const dataTable = $scope.find('.ha-advanced-table');
			const widgetWrapper = $scope.find('.elementor-widget-container');
			const row_td = $scope.find('.ha-advanced-table__body-row-cell');
			const search = dataTable.data('search') == true ? true : false;
			const paging = dataTable.data('paging') == true ? true : false;
			var scrollX = dataTable.data('scroll-x') == undefined ? false : true;

			if (window.innerWidth <= 767) {
				var scrollX = true;
			}
			// DataTables.js settings
			$(dataTable).DataTable( {
				"searching": search,
				"paging": paging,
				"orderCellsTop": true,
				"scrollX": scrollX
			} );
			const column_th = $scope.find('.ha-advanced-table__head-column-cell');
			column_th.each( function(index, v) {
				$(column_th[index]).css('width', '');
			});

			// export table button
			if (dataTable.data('export-table') == true) {
				widgetWrapper.prepend('<div class="ha-advanced-table-btn-wrap"><button class="ha-advanced-table-btn">' + dataTable.data('export-table-text') + '</button></div>');
				const dataTableBtn = $scope.find('.ha-advanced-table-btn');

				dataTableBtn.on('click', function() {
					const oldDownloadNode = document.querySelector('.ha-advanced-data-table-export');
					if (oldDownloadNode) {
						oldDownloadNode.parentNode.removeChild(oldDownloadNode);
					}

					const csv = [];
					dataTable.find('tr').each(function(index, tr) {
						const data = [];

						$(tr).find('th').each(function(index, th) {
							data.push(th.textContent);
						})
						$(tr).find('td').each(function(index, td) {
							data.push(td.textContent);
						})

						csv.push(data.join(","));
					});
					const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });

					const downloadNode = document.createElement("a");
					const url = URL.createObjectURL(csvFile);

					const date = new Date();
					const options = { day: '2-digit', month: 'short', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' }
					const formatedDate = date.toLocaleDateString('en-BD', options);
					const dateString = JSON.stringify(formatedDate.replace(/[ , :]/g, '-'));

					downloadNode.download = 'advanced-data-table-' + dateString + '~' + dataTable.data('widget-id') + '.csv';
					downloadNode.setAttribute("href", url);
					downloadNode.classList.add('ha-advanced-data-table-export');
					downloadNode.style.visibility = 'hidden';
					document.body.appendChild(downloadNode);
					downloadNode.click();
				})
			}

		};


		// ModalPopup
		var ModalPopup = function( $scope ) {
			var boxWrapper = $scope.find('.ha-modal-popup-box-wrapper');
			var triggerType = boxWrapper.data('trigger-type');
			var trigger = $scope.find('.ha-modal-popup-trigger');
			var overlayClose = $scope.find('.ha-modal-popup-overlay');
			var iconClose = $scope.find('.ha-modal-popup-content-close');
			var modal = $scope.find('.ha-modal-popup-content-wrapper');
			var modalAnimation = modal.data('animation');
			var modalDelay = modal.data('display-delay');

			if('pageload' == triggerType){
				if ( ! modal.hasClass('ha-modal-show')){
					setTimeout(function() {
						modal.addClass('ha-modal-show');
						modal.find('.ha-modal-animation').addClass( modalAnimation );
					}, parseInt(modalDelay));
				}

			}else{
				trigger.on("click", function (e) {
					e.preventDefault();
					var wrapper = $(this).closest('.ha-modal-popup-box-wrapper'),
						modal = wrapper.find('.ha-modal-popup-content-wrapper'),
						modalAnimation = modal.data('animation'),
						modalContent = modal.find('.ha-modal-animation');

						if ( ! modal.hasClass('ha-modal-show')){
							modal.addClass('ha-modal-show');
							modalContent.addClass( modalAnimation );
						}
				});
			}

			overlayClose.on( "click", close_modal );
			iconClose.on( "click", close_modal );
			function close_modal(el){
				var wrap = $(this).closest('.ha-modal-popup-content-wrapper'),
				modalAnimation = wrap.data('animation');
				if ( wrap != null && wrap.hasClass('ha-modal-show')){
					wrap.removeClass('ha-modal-show');
					wrap.find('.'+modalAnimation).removeClass( modalAnimation );
				}
			}
		}

		//Mini Cart
		var miniCart = function ($scope) {
			$scope.find(".ha-mini-cart-inner").on( "click mouseenter mouseleave", function (e) {
				var cart_btn = $(this),
					on_click = cart_btn.hasClass("ha-mini-cart-on-click"),
					on_hover = cart_btn.hasClass("ha-mini-cart-on-hover"),
					popup = cart_btn.find(".ha-mini-cart-popup");

				if( popup.length == 0 ){
					return;
				}

				if( "click" === e.type && on_click ){
					popup.fadeToggle()
				}

				if( "mouseenter" === e.type && on_hover ){
					popup.fadeIn()
				}

				if( "mouseleave" === e.type && on_hover ){
					popup.fadeOut()
				}
			});

			if( $scope.find(".ha-mini-cart-popup").length > 0  && $scope.find(".ha-mini-cart-on-click").length > 0 ){
				$('body').on("click", function(e){
					if( $(e.target).hasClass("ha-mini-cart-popup") || $(e.target).parents().hasClass("ha-mini-cart-popup") || $(e.target).hasClass("ha-mini-cart-button") || $(e.target).parents().hasClass("ha-mini-cart-button") ){
						return;
					}else{
						$scope.find(".ha-mini-cart-popup").removeAttr('style');
					}
				});
			}
		};

		//Image Scroller
		var ImageScroller = elementorModules.frontend.handlers.Base.extend({

			onInit: function () {
				elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.wrapper = this.$element.find('.ha-image-scroller-wrapper');
				this.run();
			},
			onElementChange: function () {
				this.run();
			},
			run: function () {
				// var widgetID = $scope.data('id');
				// var wrapper = $scope.find('.ha-image-scroller-wrapper');
				var triggerType = this.wrapper.data('trigger-type');
				if( 'hover' !== triggerType ){
					return;
				}
				var figure = this.wrapper.find('.ha-image-scroller-container');
				var scrollType = this.wrapper.data('scroll-type');
				var scrollDirection = this.wrapper.data('scroll-direction');
				var imageScroll = figure.find('img');
				var transformY = imageScroll.height() - figure.height();
				var transformX = imageScroll.width() - figure.width();

				// console.log(scrollType,transformY,transformX);

				if( scrollType === 'vertical' && transformY > 0 ) {
					if( 'top' === scrollDirection){
						mouseEvent('translateY',transformY);
					} else {
						imageScroll.css("transform", "translateY" + "( -" + transformY + "px)");
						mouseEventRevers('translateY',transformY);
					}
				}

				if( scrollType === 'horizontal' && transformX > 0 ) {
					if( 'left' === scrollDirection){
						mouseEvent('translateX',transformX);
					} else {
						imageScroll.css("transform", "translateX" + "( -" + transformX + "px)");
						mouseEventRevers('translateX',transformX);
					}
				}

				function mouseEvent( cssProperty, value ){
					figure.on('mouseenter', function () {
						imageScroll.css("transform", cssProperty + "( -" + value + "px)");
					});
					figure.on('mouseleave',function () {
						imageScroll.css("transform", cssProperty + "( 0px)");
					});
				}

				function mouseEventRevers( cssProperty, value ){
					figure.on('mouseenter', function () {
						imageScroll.css("transform", cssProperty + "( 0px)");
					});
					figure.on('mouseleave',function () {
						imageScroll.css("transform", cssProperty + "( -" + value + "px)");
					});
				}
			}
		});

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-countdown.default',
			CountDown
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-team-carousel.default',
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(CarouselBase, {
					$element: $scope,
					selectors: {
						container: '.ha-team-carousel-wrap',
					},
					prevArrow: '<button type="button" class="slick-prev"><i class="hm hm-arrow-left"></i></button>',
					nextArrow: '<button type="button" class="slick-next"><i class="hm hm-arrow-right"></i></button>'
				});
			}
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-source-code.default',
			SourceCode
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-animated-text.default',
			AnimatedText
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-logo-carousel.default',
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(CarouselBase, {
					$element: $scope,
					selectors: {
						container: '.ha-logo-carousel-wrap',
					},
				});
			}
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-testimonial-carousel.default',
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(CarouselBase, {
					$element: $scope,
					selectors: {
						container: '.ha-testimonial-carousel__wrap',
					},
				});
			}
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-advanced-tabs.default',
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(TabHandlerBase, {
					$element: $scope,
				});
			}
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-instagram-feed.default',
			InstagramFeed
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-scrolling-image.default',
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(ScrollingImage, {
					$element: $scope,
				});
			}
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-pricing-table.default',
			PricingTableToolTip
		);

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-accordion.default',
            function ($scope) {
                elementorFrontend.elementsHandler.addHandler(TabHandlerBase, {
                    $element: $scope,
                    selectors: {
                        tabTitle: '.ha-accordion__item-title',
                        tabContent: '.ha-accordion__item-content'
                    },
                    classes: {
                        active: 'ha-accordion__item--active'
                    },
                    showTabFn: 'slideDown',
                    hideTabFn: 'slideUp',
                });
            }
        );

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-toggle.default',
            function ($scope) {
                elementorFrontend.elementsHandler.addHandler(TabHandlerBase, {
                    $element: $scope,
                    selectors: {
                        tabTitle: '.ha-toggle__item-title',
                        tabContent: '.ha-toggle__item-content'
                    },
                    classes: {
                        active: 'ha-toggle__item--active'
                    },
                    hidePrevious: false,
                    autoExpand: 'editor',
                    showTabFn: 'slideDown',
                    hideTabFn: 'slideUp',
                });
            }
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-timeline.default',
			TimeLine
		);

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/ha-hotspots.default',
            function($scope) {
                elementorFrontend.elementsHandler.addHandler(HotSpots, {
                	$element: $scope
				})
            }
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-line-chart.default',
			LineChart
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-pie-chart.default',
			PieChart
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-polar-chart.default',
			PolarChart
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-radar-chart.default',
			RadarChart
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-facebook-feed.default',
			FacebookFeed
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-smart-post-list.default',
			SmartPostList
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-advanced-data-table.default',
			DataTable
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-twitter-carousel.default',
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(CarouselBase, {
					$element: $scope,
					selectors: {
						container: '.ha-tweet-carousel-items',
					},
				});
			}
		);

		// Post Carousel
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-post-carousel.default',
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(CarouselBase, {
					$element: $scope,
					selectors: {
						container: '.ha-posts-carousel-wrapper',
					},
					prevArrow: '<button type="button" class="slick-prev"><i class="hm hm-arrow-left"></i></button>',
					nextArrow: '<button type="button" class="slick-next"><i class="hm hm-arrow-right"></i></button>'
				});
			}
		);

		// product carousel
		function initProductCarousel( $scope ) {
			elementorFrontend.elementsHandler.addHandler(CarouselBase, {
				$element: $scope,
				selectors: {
					container: '.ha-product-carousel-wrapper',
				},
				prevArrow: '<button type="button" class="slick-prev"><i class="hm hm-arrow-left"></i></button>',
				nextArrow: '<button type="button" class="slick-next"><i class="hm hm-arrow-right"></i></button>'
			});
		}

		function productCarouselHandlerCallback( $scope ) {
			initProductCarousel($scope);
			initProductQuickView($scope);

			if ($scope.find('.ha-product-carousel-wrapper').hasClass('ha-layout-modern')) {
				$scope
					.find('.ha-product-carousel-add-to-cart a')
					.html('<i class="fas fa-shopping-cart"></i>');
			}
		}

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-product-carousel.classic',
			productCarouselHandlerCallback
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-product-carousel.modern',
			productCarouselHandlerCallback
		);

		// product category carousel
		var productCategoryCarouselSkins = [
			'classic',
			'minimal',
		];
		function initProductCategoryCarousel( $scope ) {
			elementorFrontend.elementsHandler.addHandler(CarouselBase, {
				$element: $scope,
				selectors: {
					container: '.ha-product-cat-carousel',
				},
				prevArrow: '<button type="button" class="slick-prev"><i class="hm hm-arrow-left"></i></button>',
				nextArrow: '<button type="button" class="slick-next"><i class="hm hm-arrow-right"></i></button>'
			});
		}

		productCategoryCarouselSkins.forEach(function(index) {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/ha-product-category-carousel.' + index,
				initProductCategoryCarousel
			);
		});

		postGridSkins.forEach(function(index) {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/ha-post-grid.' + index,
				PostGrid
			);
		});

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-sticky-video.default',
			StickyVideo
		);

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-modal-popup.default',
			ModalPopup
		);

		function initProductQuickView($scope) {
			var $button = $scope.find('.ha-pqv-btn');

			if (!$.fn['magnificPopup']) {
				return;
			}

			$button.magnificPopup({
				type: 'ajax',
				ajax: {
					settings: {
						cache: true,
						dataType: 'html'
					},
					cursor: 'mfp-ajax-cur',
					tError: 'The product could not be loaded.'
				},
				callbacks: {
					ajaxContentAdded: function() {
						$(this.content).addClass(this.currItem.el.data('modal-class'))
					}
				}
			});
		}

		elementorFrontend.hooks.addAction( 'frontend/element_ready/ha-product-grid.classic', initProductQuickView );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/ha-product-grid.hover', initProductQuickView );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/ha-single-product.classic', initProductQuickView );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/ha-single-product.standard', initProductQuickView );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/ha-single-product.landscape', initProductQuickView );


		$(document.body).on('adding_to_cart', function(event, $addToCart) {
			$addToCart
				.parent('.ha-product-grid__btns')
				.removeClass('ha-is--added')
				.addClass('ha-is--adding');
		});

		$(document.body).on('added_to_cart', function(event, fragment, hash, $addToCart) {
			$addToCart
				.parent('.ha-product-grid__btns')
				.removeClass('ha-is--adding')
				.addClass('ha-is--added');
		});

		elementorFrontend.hooks.addAction( 'frontend/element_ready/ha-mini-cart.default', miniCart );

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/ha-image-scroller.default',
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(ImageScroller, {
					$element: $scope,
				});
			}
		);

	});

}(jQuery, Happy, window));
