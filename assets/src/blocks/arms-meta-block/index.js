import { registerBlockType } from '@wordpress/blocks';
import { TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { useBlockProps } from '@wordpress/block-editor';

/**
 * WordPress dependencies
 */
import { __, _x } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import metadata from './block.json';

const { name } = metadata;
export { metadata, name };

export const settings = {
	title: _x( 'ARMS Meta Block', 'arms' ),
	description: __( 'Just a test block.', 'arms' ),
	icon: 'groups',
	keywords: [ __( 'Meta', 'arms' ) ],
	edit: ( {} ) => {
		const blockProps = useBlockProps();
		const postType = useSelect(
			( select ) => select( 'core/editor' ).getCurrentPostType(),
			[]
		);

		const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

		const metaFieldValue = meta.myguten_meta_block_field;
		const updateMetaValue = ( newValue ) => {
			setMeta( { ...meta, myguten_meta_block_field: newValue } );
		};

		return (
			<div { ...blockProps }>
				<TextControl
					label="Meta Block Field"
					value={ metaFieldValue }
					onChange={ updateMetaValue }
				/>
			</div>
		);
	},
	// No information saved to the block.
	// Data is saved to post meta via the hook.
	save: () => {
		return null;
	},
};

registerBlockType( name, settings );
