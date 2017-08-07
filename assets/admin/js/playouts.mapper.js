"use strict";

window.jQuery = window.$ = jQuery;

var Pl_mapper = {

    /*
     * contains all data for modules
     *
     */
    __mapper_data: {},
    /*
     * contains the level structure of the modules
     *
     */
    __mapper_tree: [],

    /*
     * empty mapper objects
     *
     */
    __clear_mapper: function() {
        this.__mapper_data = [];
        this.__mapper_tree = [];
    },
    __clear_mapper_data: function() {
        this.__mapper_data = [];
    },
    __clear_mapper_tree: function() {
        this.__mapper_tree = [];
    },

    /*
     * the return value of function get_tree_modules_children_by_id
     *
     */
    parser_tree_return_value: false,

    /*
     * build the mapper data object
     *
     * uid: id of the item
     * data: module object
     * options: the module options to inherit
     *
     */
    map_data: function( uid, module_obj, options ) {

        if( options ) {
            this.sync_object_data( module_obj, options );
        }

        this.__mapper_data[ uid ] = module_obj;

    },

    /*
     * loop module parameters and sync the values
     *
     */
    sync_object_data: function( module_obj, options ) {

        if( typeof options === 'object' ) {

            for ( var option in options ) {

                var option_value = options[ option ];

                if( typeof module_obj.params[ option ] !== 'undefined' ) {

                    module_obj.params[ option ].value = option_value;

                }
            }
        }
    },

    /*
     * build the mapper tree object
     *
     * append_shortcode: boolean, update the html editor content if set to "true"
     *
     */
    map_tree: function( append_shortcode ) {

        // clear the current tree object
        this.__mapper_tree = [];
        // parse the current ui modules
        this.parse_modules_and_build_tree( $('#pl-main .pl-blocks'), false );
        // returns shortcodes string and update the html editor content if set to "true"
        Pl_shortcoder.reload_shortcodes_and_push( this.__mapper_tree, append_shortcode );

    },

    /*
     * parse html ui modules and build mapper tree object ( levels )
     *
     */
    parse_modules_and_build_tree: function( $modules, parent_obj ) {

        var self = this;

        var push_to_object = parent_obj ? parent_obj.children : self.__mapper_tree;

        var $container = parent_obj ? $modules.find('.pl-content:first') : $modules;

        $container.children('.pl-block').each(function() {

            var $e = $(this);

            var current = {
                'id'        : $e.attr('data-id'),
                'children'  : []
            };

            push_to_object.push( current );

            if( $( '.pl-block', $e ).length > 0 ) {
                self.parse_modules_and_build_tree( $e, current );
            }

        });

    },

    /*
     * update the option of a module by id
     *
     * id: mapper module id
     * option: id of the option
     * value: new value to inherit
     *
     */
    update_mapper_module_options: function( uid, option, value ) {

        if( typeof this.__mapper_data[uid] !== 'undefined' && typeof this.__mapper_data[uid].params[option] !== 'undefined' ) {

            var param = this.__mapper_data[uid].params[option];

            if( typeof param.value !== 'undefined' ) {
                param.value = value;
            }else{
                param['value'] = value;
            }
        }

    },

    get_tree_modules_children_by_id: function( tree_obj, module_id ) {

        var tree_length = tree_obj.length;

        for ( var i = 0; i < tree_length; i++ ) {

            if( tree_obj[i].id == module_id ) {
                Pl_mapper.parser_tree_return_value = tree_obj[i].children;
                break;
            }

            if( tree_obj[i].children && $.isArray( tree_obj[i].children ) ) {
                Pl_mapper.get_tree_modules_children_by_id( tree_obj[i].children, module_id );
            }

        }

        return Pl_mapper.parser_tree_return_value;

    }

};
