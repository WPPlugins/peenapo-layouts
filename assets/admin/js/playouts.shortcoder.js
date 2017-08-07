"use strict";

window.jQuery = window.$ = jQuery;

var Pl_shortcoder = {

    /*
     * contains the shortcodes as string
     *
     */
    __s: '',

    /*
     * returns shortcodes string and append it to the editor
     *
     * object_tree:
     * append_shortcode: boolean, append the shortcode string to the html editor
     *
     */
    reload_shortcodes_and_push: function( object_tree, append_shortcode ) {

        this.clear_shortcodes();

        this.parse_tree_and_build_shortcode( object_tree );

        if( append_shortcode ) {
            this.append_shortcodes();
        }

        return this.__s;

    },

    /*
     * empty the shortcodes string
     *
     */
    clear_shortcodes: function() {
        this.__s = '';
    },

    /*
     * parse tree object and build shortcode string and parameters
     *
     */
    parse_tree_and_build_shortcode: function( object_tree ) {

        var self = this;

        for( var i = 0; i < object_tree.length; i++ ) {

            var element = object_tree[i];
            self.build( element );

        }

    },

    /*
     * build the shortcode string element []
     *
     */
    build: function( tree_obj_item ) {

        var self = this;
        var data = Pl_mapper.__mapper_data[ tree_obj_item.id ];
        var params = '';
        var content = '';

        //console.log( data );

        if( typeof data.module !== 'undefined' ) {

            for ( var field in data.params ) {

                var moduleParam = data.params[ field ];

                if( typeof moduleParam.value !== 'undefined' && moduleParam.value !== '' ) {

                    if( typeof moduleParam.is_content !== 'undefined' ) {
                        content = moduleParam.value;
                    }else{
                        params += self.add_parameter( field, moduleParam );
                    }

                }
            }

            self.__s += '[' + data.module + params + ']' + content;

            if( tree_obj_item.children.length > 0 ) {
                self.parse_tree_and_build_shortcode( tree_obj_item.children );
            }

            self.__s += '[/' + data.module + ']';

        }else{

            Pl_main.notify( 'module_not_mapped' );

        }

    },

    /*
     * creates the shortcode parameter
     *
     */
    add_parameter: function( param_name, param ) {

        if( param.type == 'editor' ) {
            param.value = Pl_main.wpautop( param.value );
        }
        return ' ' + param_name + '="' + Pl_main.escape_param( param.value ) + '"';

    },

    append_shortcodes: function() {
        this.set_editor_content( this.__s );
    },

    /*
     * get the current content of the wp editor
     *
     */
    get_editor_content: function() {

        if( typeof tinymce !== 'undefined' ) {
            var editor = tinymce.get('content');
            return ( editor && editor instanceof tinymce.Editor ) ? editor.getContent() : $('#content').val();
        }
        return;
    },

    /*
     * set the wp editor's content
     *
     */
    set_editor_content: function( new_content ) {

        if( typeof tinymce !== 'undefined' ) {
            var editor = tinymce.get( 'content' );
            if( editor && editor instanceof tinymce.Editor && ! editor.isHidden() ) {
                editor.setContent( new_content );
            }else{
                $('textarea#content').val( new_content );
            }
        }

    },

};
