window.jQuery = window.$ = jQuery;

var Playouts = {

    // initiation
    start: function() {

        this.bind();
        this.override_styles.start();
        this.on_resize();
        this.on_images_loaded();

    },

    bind: function() {

        // disable empty url\'s
        $(document).on('click', '.pl-outer a[href="#"]', function(e) {
            e.preventDefault();
        });

        // overlay click, hide self
        $('.pl-overlay-main, .pl-overlay-container').on('click', Playouts.overlay.hide);

        // video modal
        $('.pl-video-modal').on('click', Playouts.elements.video_modal.expand);

    },

    /*
     * show and hide ovrlay background
     *
     */
    overlay: {

        show: function() {

            TweenLite.to( $('#pl-overlay-main'), .3, { opacity: .8, visibility: 'visible' });

        }

        ,hide: function() {

            TweenMax.to( $('#pl-overlay-main'), .3, { opacity: 0, onComplete: function() {
                this.target.css('visibility', 'hidden');
            }});

            if( $('#pl-overlay-container').hasClass('pl-visible') ) {
                Playouts.overlay.hide_container();
            }

        }

        ,show_container: function() {

            var $container = $('#pl-overlay-container');
            TweenLite.fromTo( $container, .3, { opacity: 0, scale: 0.9 }, { opacity: 1, scale: 1, visibility: 'visible' });
            $container.addClass('pl-visible');

        }

        ,hide_container: function() {

            var $container = $('#pl-overlay-container');
            TweenMax.to( $container, .3, { opacity: 0, scale: 0.9, onComplete: function() {
                $container.css('visibility', 'hidden');
                if( $container.hasClass('pl-visible') ) {
                    $container.html('').removeClass('pl-visible');
                }
            }});

        }

    },

    /*
     * override default css hover styles
     *
     */
    override_styles: {

        start: function() {

            $('.pl-button[data-hover-bg-color-override]')
                .on('mouseover', this.override_hover_bg_color )
                .on('mouseout', this.reset_hover_bg_color );

            $('.pl-button[data-hover-text-color-override]')
                .on('mouseover', this.override_hover_text_color )
                .on('mouseout', this.reset_hover_text_color );

            $('.pl-button[data-hover-shadow-override]')
                .on('mouseover', this.override_hover_shadow_color )
                .on('mouseout', this.reset_hover_shadow_color );

        }

        ,override_hover_bg_color: function() {
            var self = $(this);
            TweenLite.set( self, { backgroundColor: self.attr('data-hover-bg-color-override') });
        }
        ,reset_hover_bg_color: function() {
            $(this).css('background-color', '');
        }

        ,override_hover_text_color: function() {
            var self = $(this);
            TweenLite.set( self, { color: self.attr('data-hover-text-color-override') });
        }
        ,reset_hover_text_color: function() {
            $(this).css('color', '');
        }

        ,override_hover_shadow_color: function() {
            var self = $(this);
            var color = Playouts.hex_to_rgba( self.attr('data-hover-shadow-override') );
            TweenLite.set( self, { boxShadow: '0 20px 38px rgba(' + color + ', 0.15)' });
        }
        ,reset_hover_shadow_color: function() {
            $(this).css('box-shadow', '');
        }

    },

    hex_to_rgba: function( hex ) {
        var c;
        if( /^#([A-Fa-f0-9]{3}){1,2}$/.test( hex ) ) {
            c = hex.substring(1).split('');
            if( c.length == 3 ) {
                c = [c[0], c[0], c[1], c[1], c[2], c[2]];
            }
            c = '0x'+c.join('');
            return ''+[(c>>16)&255, (c>>8)&255, c&255].join(',');
        }
    },

    /*
     * get embed code from youtube url
     * TODO: add vimeo
     *
     */
    get_embed_from_url: function( video, autoplay = false ) {

        var reg_url = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?([\w\-]{10,12})(?:&feature=related)?(?:[\w\-]{0})?/g
        ,iframe_code = '<iframe src="http://www.youtube.com/embed/$1' + ( autoplay ? '?autoplay=1' : '' ) + '" frameborder="0" allowfullscreen></iframe>'
        ,embed_code = ( typeof video !== 'undefined' && video !== '' ) ? video.replace( reg_url, iframe_code ) : '';

        return embed_code;

    },

    elements: {

        start: function() {

            this.accordion.start();
            this.tab.start();
            this.progress.start();
            this.auto_type.start();
            this.carousel.start();
            this.image_comparison.start();
            this.number_counter.start();

        }

        ,number_counter: {

            start: function() {

                $('.pl-number-counter').waypoint({
                    handler: function() {

                        var self = $( this.element ).addClass('pl-animated');
                        var number = self.attr('data-number');
                        var duraction = parseInt( self.attr('data-duration'), 10 );
                        var $container = self.find('span');

                        self.prop( 'Counter', 0 ).animate({
                            Counter: number
                        }, {
                            duration : duraction,
                            easing : 'swing',
                            step : function ( now ) {
                                $container.html( Math.ceil( now ) );
                            }
                        });

                        this.destroy();

                    },
                    offset: '95%'
                });

            }

        }

        ,google_map: {

            start: function() {

                $('.pl-google-map').each(function() {

                    var self = $(this);
                    var _id = self.attr('id');

                    var _styles = [
                        {
                            "featureType": "administrative.country",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "visibility": "simplified"
                                },
                                {
                                    "hue": "#ff0000"
                                }
                            ]
                        }
                    ];

                    if( typeof playouts_map_styles !== 'undefined' && typeof playouts_map_styles[ _id ] !== 'undefined' && playouts_map_styles[ _id ] !== '' ) {
                        _styles = playouts_map_styles[ _id ];
                    }

                    var map_center = { // Madrid, Spain center
                        lat: parseFloat( '40.416775' ),
                        lng: parseFloat( '-3.70379' )
                    };

                    if( typeof self.attr('data-center-lat') !== 'undefined' ) {
                        map_center = {
                            lat: parseFloat( self.attr('data-center-lat') ),
                            lng: parseFloat( self.attr('data-center-lng') )
                        }
                    }

                    var map = new google.maps.Map( document.getElementById( _id ), {
                        zoom                : typeof self.attr('data-zoom-level') !== 'undefined' ? parseInt( self.attr('data-zoom-level'), 10 ) : 17,
                        center              : map_center,
                        styles              : _styles,
                        zoomControl         : ( typeof self.attr('data-zoom-controls') !== 'undefined' && self.attr('data-zoom-controls') == 'true' ) ? true : false,
                        scrollwheel         : false,
                        mapTypeControl      : false,
                        streetViewControl   : false,
                    });

                    var bounds = new google.maps.LatLngBounds();
                    var infowindow = new google.maps.InfoWindow();

                    var $pins = self.next('.pl-google-pins').find('li');
                    if( $pins.length ) {
                        $pins.each(function( i ) {

                            var $pin = $(this);

                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng( parseFloat( $pin.attr('data-lat') ), parseFloat( $pin.attr('data-lng') ) ),
                                map: map,
                                title: $pin.attr('data-title'),
                                icon: typeof $pin.attr('data-image') !== 'undefined' ? $pin.attr('data-image') : '',
                            });

                            // extend the bounds to include each marker's position
                            bounds.extend( marker.position );

                            // add pin label with title on click
                            google.maps.event.addListener( marker, 'click', (function( marker, i ) {
                                return function() {
                                    infowindow.setContent( $pin.attr('data-title') );
                                    infowindow.open( map, marker );
                                }
                            }) ( marker, i ) );

                        });

                        // set zoom after bounce
                        google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
                            if( typeof self.attr('data-zoom-level') !== 'undefined' ) {
                                map.setZoom( parseInt( self.attr('data-zoom-level'), 10 ) );
                            }
                        });

                        //if( typeof self.attr('data-bounds') !== 'undefined' ) {
                            // now fit the map to the newly inclusive bounds
                            map.fitBounds( bounds );
                        //}

                    }

                });

            }

        }

        ,image_comparison: {

            start: function() {

                $('.pl-image-comparison').each(function() {

                    var self = $(this);
                    var attr = {
                        no_overlay          : true,
                        default_offset_pct  : parseInt( self.attr('data-offset'), 10 ) * 0.01,
                    };
                    var direction = self.attr('data-direction');

                    if( typeof direction !== 'undefined' && direction == 'vertical' ) {
                        attr['orientation'] = 'vertical';
                    }

                    self.twentytwenty( attr );

                });

            }

        }

        ,video_modal: {

            expand: function(e) {

                e.preventDefault();

                var self = $(this);

                // display the container overlays
                Playouts.overlay.show();
                Playouts.overlay.show_container();

                // get the video template
                var _template = $( $('#pl-template-video-modal').html() );

                // append the template inside the container
                $('#pl-overlay-container').html( _template );

                // set the size of the screen
                _template.find('.pl-video-screen').addClass( 'pl-screen-size-' + self.attr('data-screen-size') );

                setTimeout(function() { // wait for the animation to end and append the embed video code
                    _template.find('.pl-iframe-scaler').html(
                        Playouts.get_embed_from_url(
                            self.attr('href'),
                            typeof self.attr('data-autoplay') !== 'undefined'
                        )
                    );
                }, 150);

            }

        }

        ,carousel: {

            start: function() {

                $('.pl-slider').each( Playouts.elements.carousel.build_slider );

            }

            ,build_slider: function() {

                var self = $(this);
                var slide_width = self.attr('data-slide-width');

                var attr = {
                    cellAlign               : slide_width == '100' ? 'left' : 'center',
                    contain                 : true,
                    lazyLoad                : true,
                    groupCells              : typeof self.attr('data-group') !== 'undefined' ? parseInt( self.attr('data-group'), 10 ) : false,
                    autoPlay                : typeof self.attr('data-autoplay') !== 'undefined' ? parseInt( self.attr('data-autoplay'), 10 ) : false,
                    wrapAround              : typeof self.attr('data-infinite') !== 'undefined' && self.attr('data-infinite') == 'true' ? true : false,
                    pauseAutoPlayOnHover    : typeof self.attr('data-autoplay-stop') !== 'undefined' ? true : false,
                    adaptiveHeight          : typeof self.attr('data-adaptive-height') !== 'undefined' && self.attr('data-adaptive-height') == 'true' ? true : false,
                    prevNextButtons         : typeof self.attr('data-navigation') !== 'undefined' && self.attr('data-adaptive-height') == 'true' ? true : false,
                    pageDots                : typeof self.attr('data-pagination') !== 'undefined' && self.attr('data-pagination') == 'true' ? true : false,
                    selectedAttraction      : typeof self.attr('data-attraction') !== 'undefined' ? parseFloat( self.attr('data-attraction') ) : 0.025,
                    friction                : typeof self.attr('data-friction') !== 'undefined' ? parseFloat( self.attr('data-friction') ) : 0.28,
                    freeScroll              : typeof self.attr('data-free') !== 'undefined' && self.attr('data-free') == 'true' ? true : false
                };

                self.find('> *').css('width', slide_width + '%');

                self.flickity( attr );

            }

        }

        ,auto_type: {

            start: function() {

                $('.pl-auto-type-holder').each(function() {

                    var self = $(this),
                        id = self.attr('id'),
                        texts = [];

                    self.find('li').each(function() {
                        texts.push( this.innerHTML );
                    });

                    Typed.new( '#' + id + ' .pl-auto-type', {
                        strings: texts,
                        typeSpeed: 60,
                        backDelay: 1000,
                        loop: true,
                    });

                });



            }

        }

        ,progress: {

            start: function() {

                $('.pl-progress-bars.pl-is-animated').waypoint({
                    handler: function() {

                        var self = $( this.element ).addClass('pl-animated');
                        var _delay = parseFloat( self.attr('data-animation-delay') );

                        $('.pl-progress-bar', self).each(function( i ) {

                            var self = $(this),
                                width = self.attr('data-progress'),
                                $label = self.find('.pl-progress-counter'),
                                $counter = $label.find('em');

                            TweenLite.to( self.find('.pl-the-bar'), 1.2, { width: width + '%', ease: Expo.easeInOut, delay: i * _delay });
                            TweenLite.to( $label, 1.2, { opacity: 1, delay: ( i * _delay ) + 0.5 });

                        });

                        this.destroy();

                    },
                    offset: '80%'
                });

            }

        }

        ,accordion: {

            start: function() {

                $('.pl-accordion').on('click', '.pl-accordion-title', Playouts.elements.accordion.on_click_accordion);

            }

            ,on_click_accordion: function() {

                var self = $(this),
                    $item = self.closest('.pl-accirdion-item'),
                    $content = $item.find('.pl-accordion-content'),
                    $inner = $item.find('.pl-accordion-content-inner'),
                    close_other = self.closest('.pl-accordion').hasClass('pl-close-other');

                if( ! $item.hasClass('pl-active') ) { // open

                    if( close_other ) {
                        self.closest('.pl-accordion').find('.pl-accirdion-item.pl-active').find('.pl-accordion-title').trigger('click');
                    }

                    $item.addClass('pl-active');

                    TweenLite.fromTo( $content, .22, { height: 0 }, { height: $inner.outerHeight(), onComplete: function() {
                        $content.css('height', 'auto');
                    }});

                }else{ // close

                    TweenLite.fromTo( $content, .22, { height: $inner.outerHeight() }, { height: 0 });
                    $item.removeClass('pl-active');

                }
            }
        }

        ,tab: {

            start: function() {

                $('.pl-tabs').on('click', '.pl-tab-nav li', Playouts.elements.tab.on_click_tabs);

                $('.pl-tabs').each(function() {

                    var self = $(this),
                        $border = $('.pl-nav-border', self),
                        left = parseInt( self.find('li:first').outerWidth(), 10 );

                    TweenLite.to( $border, .4, { x: 0, scaleX: left, ease: Power4.easeOut } );

                });

            }

            ,on_click_tabs: function(e) {

                e.preventDefault();

                var self = $(this);

                if( self.hasClass('pl-active') ) { return; }

                Playouts.elements.tab.scale_tabs_border( self );

                var $tab = self.closest('.pl-tabs'),
                    $nav = self.closest('.pl-tab-nav'),
                    tab_id = self.children('a').attr('href'),
                    $display_section = $tab.find('.pl-tab-section' + tab_id);

                $nav.find('li').removeClass('pl-active');
                self.addClass('pl-active');

                $tab.find('.pl-tab-section').removeClass('pl-active');
                $display_section.addClass('pl-active');
                TweenLite.fromTo( $display_section, .4, { opacity: 0 }, { opacity: 1 });


            }

            ,scale_tabs_border: function( self ) {

                var left = self.position().left,
                    width =  parseInt( self.outerWidth(), 10 );

                TweenLite.to( $('.pl-nav-border', self.closest('.pl-tabs') ), .4, { x: left, scaleX: width, ease: Power4.easeOut } );

            }
        }
    },

    on_images_loaded: function() {

        $(document).imagesLoaded(function() {

            Playouts.elements.start();
            Playouts.animations();

        });

    },

    on_resize: function() {

        var self = this;

        $(window).on("debouncedresize", function( event ) {
            // ..
        });

    },

    animations: function() {

        this.appearance();
        this.appearance_stagger();
        this.background_parallax();
        this.sequence();
        this.video_button();
        this.animated_text();
        this.heading();

    },

    heading: function() {

        $('.pl-heading').waypoint({
            handler: function() {

                var self = $( this.element ).addClass('pl-animated');
                var $to_animate = self.find('.pl-anim-wrap > *');

                var animation_speed = typeof self.attr('data-animation-speed') !== 'undefined' ? parseInt( self.attr('data-animation-speed'), 10 ) * 0.001 : .45;
                var animation_delay = typeof self.attr('data-animation-delay') !== 'undefined' ? parseInt( self.attr('data-animation-delay'), 10 ) * 0.001 : .07;

                TweenMax.set( $to_animate, { visibility:'visible' } );
                TweenMax.staggerFromTo( $to_animate, animation_speed, { y: '100%', scale:.95 }, { y: '0%', scale:1, ease: Power4.easeOut }, animation_delay );

                this.destroy();

            },
            offset: '80%'
        });

    },

    animated_text: function() {

        $('.pl-animated-texts').waypoint({
            handler: function() {

                var self = $( this.element ).addClass('pl-animated');
                var $to_animate = self.find('.pl-animated-text-inner');

                var animation_speed = typeof self.attr('data-animation-speed') !== 'undefined' ? parseInt( self.attr('data-animation-speed'), 10 ) * 0.001 : .45;
                var animation_delay = typeof self.attr('data-animation-delay') !== 'undefined' ? parseInt( self.attr('data-animation-delay'), 10 ) * 0.001 : .07;

                TweenMax.set( $to_animate, { visibility:'visible' } );
                TweenMax.staggerFromTo( $to_animate, animation_speed, { y: '100%', scale:.95 }, { y: '0%', scale:1, ease: Power4.easeOut }, animation_delay );

                this.destroy();

            },
            offset: '80%'
        });

    },

    background_video: function() {

        if( $('.pl-video-wrap').length ) {
            $('.pl-video-wrap').each(function() {
                $(this).pl_core_video_background();
            });
        }

    },

    video_button: function() {

        $('.pl-video-modal').on('mouseenter', function() {

            var self = $(this).find('.pl-video-button');

            if( self.hasClass('pl-animated') ) { return; }

            self.addClass('pl-animated');

            setTimeout(function() {
                self.removeClass('pl-animated');
            }, 500);

        });

    },

    appearance: function() {

        $('.pl-animation').waypoint({
            handler: function() {

                var self = $( this.element ).addClass('pl-animated');

                var animation  = typeof self.attr('data-animation') !== 'undefined' ? self.attr('data-animation') : 'scale';
                var animation_speed = typeof self.attr('data-animation-speed') !== 'undefined' ? parseInt( self.attr('data-animation-speed'), 10 ) * 0.001 : .4;
                var animation_delay = typeof self.attr('data-animation-delay') !== 'undefined' ? parseInt( self.attr('data-animation-delay'), 10 ) * 0.001 : 0;

                // TODO: choose easings from panel
                switch( animation ) {
                    case 'scale':
                        TweenMax.fromTo( self, animation_speed, { scale: 0.8 }, { opacity:1, scale: 1, clearProps:"scale", delay: animation_delay } );
                        break;
                    case 'top':
                        TweenMax.fromTo( self, animation_speed, { y: -80 }, { opacity:1, y: 0, delay: animation_delay, ease: Power4.easeOut } );
                        break;
                    case 'right':
                        TweenMax.fromTo( self, animation_speed, { x: 80 }, { opacity:1, x: 0, delay: animation_delay, ease: Power4.easeOut } );
                        break;
                    case 'bottom':
                        TweenMax.fromTo( self, animation_speed, { y: 80 }, { opacity:1, y: 0, delay: animation_delay, ease: Power4.easeOut } );
                        break;
                    case 'left':
                        TweenMax.fromTo( self, animation_speed, { x: -80 }, { opacity:1, x: 0, delay: animation_delay, ease: Power4.easeOut } );
                        break;
                }

                this.destroy();

            },
            offset: '80%'
        });

    },

    // TODO: add distance
    appearance_stagger: function() {

        $('.pl-animation-stagger').waypoint({
            handler: function() {

                var self = $( this.element ).addClass('pl-animated');

                var animation  = typeof self.attr('data-animation') !== 'undefined' ? self.attr('data-animation') : 'scale';
                var animation_speed = typeof self.attr('data-animation-speed') !== 'undefined' ? parseInt( self.attr('data-animation-speed'), 10 ) * 0.001 : .4;
                var animation_delay = typeof self.attr('data-animation-delay') !== 'undefined' ? parseInt( self.attr('data-animation-delay'), 10 ) * 0.001 : 0;

                switch( animation ) {
                    case 'scale':
                        TweenMax.staggerFromTo( self.find('> *'), animation_speed, { scale: 0.8 }, { opacity:1, scale: 1 }, animation_delay );
                        break;
                    case 'top':
                        TweenMax.staggerFromTo( self.find('> *'), animation_speed, { y: -80 }, { opacity:1, y: 0, ease: Power4.easeOut }, animation_delay );
                        break;
                    case 'right':
                        TweenMax.staggerFromTo( self.find('> *'), animation_speed, { x: 80 }, { opacity:1, x: 0, ease: Power4.easeOut }, animation_delay );
                        break;
                    case 'bottom':
                        TweenMax.staggerFromTo( self.find('> *'), animation_speed, { y: 80 }, { opacity:1, y: 0, ease: Power4.easeOut }, animation_delay );
                        break;
                    case 'left':
                        TweenMax.staggerFromTo( self.find('> *'), animation_speed, { x: -80 }, { opacity:1, x: 0, ease: Power4.easeOut }, animation_delay );
                        break;
                }

                this.destroy();

            },
            offset: '80%'
        });

    },

    sequence: function() {

        $('.pl-animated-appearance').waypoint({
            handler: function() {

                var self = $( this.element ).addClass('pl-animated');

                var $to_animate = $(' > *', self);
                var animation_speed = parseInt( self.attr('data-animation-speed'), 10 ) * 0.001;
                var animation_delay = parseInt( self.attr('data-animation-delay'), 10 ) * 0.001;

                TweenMax.staggerTo( $to_animate, animation_speed, { opacity:1 }, animation_delay );

                this.destroy();

            },
            offset: '80%'
        });

    },

    background_parallax: function() {
        var $parallax = $('.pl-parallax');
        if ( $parallax.length ) {
            $parallax.each(function() {
                $(this).pl_core_parallax_background();
            });
        }
    },

    on_click_accordion: function() {

    }
}

// google callback
window.playouts_init_map = function() {
    Playouts.elements.google_map.start();
}

$(document).ready(function() {
    Playouts.start();
});
