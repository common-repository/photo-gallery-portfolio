/**
 * Block dependencies
 */
import './style.scss';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { TextControl, Button } = wp.components;
const { Component } = wp.element;

/**
 * adamlabsgallery Editor Element
 */
export  class AdamLabsGallery extends Component {

    constructor() {
        super( ...arguments );
        const { attributes: { text,gridTitle } } = this.props;
        this.state = {
          text ,
          gridTitle
        }
    }

    render() {
        const {
        attributes: { text,gridTitle },
        setAttributes  } = this.props;
      
        window.adamlabsgallery_react = this;
        const openDialog = () => {
          jQuery('select[name="adamlabsgallery-existing-grid"]').val("-1");
          jQuery('#adamlabsgallery-tiny-mce-dialog').dialog({
            id       : 'adamlabsgallery-tiny-mce-dialog',
            title	 : adamlabsgallery_lang.shortcode_generator,
            width    : 720,
            height   : 'auto'
          });
        }

        return (
          <div className="adamlabsgallery_block" >
                  <span>{this.state.gridTitle}&nbsp;</span>
                  <TextControl
                        className="grid_slug"
                        value={ this.state.text }
                        onChange={ ( text ) => setAttributes( { text } ) }
                    />
                  <Button 
                        isDefault
                        onClick = { openDialog } 
                        className="grid_edit_button"
                    >
                    {__( 'Edita', 'adamlabsgallery' )}
                  </Button>
          </div>
        );
    }
}


/**
 * Register block
 */
export default registerBlockType(
    'adamlabs/adamlabsgallery',
    {
        title: __( 'Add prefined Portfolio Gallery', 'adamlabsgallery' ),
        description: __( 'Add your predefined Portfolio Gallery.', 'adamlabsgallery' ),
        category: 'adamlabs',
        icon: {
          src:  'screenoptions',
          background: 'rgb(210,0,0)',
          color: 'white'
        },        
        keywords: [
            __( 'image', 'adamlabsgallery' ),
            __( 'gallery', 'adamlabsgallery' ),
            __( 'grid', 'adamlabsgallery' ),
        ],
        attributes: {
          text: {
              selector: '.adamlabsgallery',
              type: 'string',
              source: 'text',
          },
          gridTitle: {
              selector: '.adamlabsgallery',
              type: 'string',
              source: 'attribute',
             	attribute: 'data-gridtitle',
          }
        },
        edit: props => {
          const { setAttributes } = props;
          return (
            <div>
              <AdamLabsGallery {...{ setAttributes, ...props }} />
            </div>
          );
        },
        save: props => {
          const { attributes: { text,gridTitle } } = props;
          return (
            <div className="adamlabsgallery" data-gridtitle={gridTitle}>
               {text} 
            </div>
          );
        },
    },
);