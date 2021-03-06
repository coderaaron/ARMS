<?php
/**
 * Generated by the WordPress Meta Box Generator at http://goo.gl/8nwllb
 */
class ARMS_Meta_Box {
	private $screens = array(
		'post',
		'pet',
	);

	private $cat_breed_array = array(
		'Abyssinian',
		'American Curl',
		'American Shorthair',
		'American Wirehair',
		'Applehead Siamese',
		'Balinese',
		'Bengal',
		'Birman',
		'Bobtail',
		'Bombay',
		'British Shorthair',
		'Burmese',
		'Burmilla',
		'Calico',
		'Canadian Hairless',
		'Chartreux',
		'Chausie',
		'Chinchilla',
		'Cornish Rex',
		'Cymric',
		'Devon Rex',
		'Dilute Calico',
		'Dilute Tortoiseshell',
		'Domestic Long Hair',
		'Domestic Long Hair - buff',
		'Domestic Long Hair - buff and white',
		'Domestic Long Hair - gray and white',
		'Domestic Long Hair - orange',
		'Domestic Long Hair - orange and white',
		'Domestic Long Hair-black',
		'Domestic Long Hair-black and white',
		'Domestic Long Hair-gray',
		'Domestic Long Hair-white',
		'Domestic Medium Hair',
		'Domestic Medium Hair - buff',
		'Domestic Medium Hair - buff and white',
		'Domestic Medium Hair - gray and white',
		'Domestic Medium Hair - orange and white',
		'Domestic Medium Hair-black',
		'Domestic Medium Hair-black and white',
		'Domestic Medium Hair-gray',
		'Domestic Medium Hair-orange',
		'Domestic Medium Hair-white',
		'Domestic Short Hair',
		'Domestic Short Hair - buff',
		'Domestic Short Hair - buff and white',
		'Domestic Short Hair - gray and white',
		'Domestic Short Hair - orange and white',
		'Domestic Short Hair-black',
		'Domestic Short Hair-black and white',
		'Domestic Short Hair-gray',
		'Domestic Short Hair-mitted',
		'Domestic Short Hair-orange',
		'Domestic Short Hair-white',
		'Egyptian Mau',
		'Exotic Shorthair',
		'Extra-Toes Cat (Hemingway Polydactyl)',
		'Havana',
		'Himalayan',
		'Japanese Bobtail',
		'Javanese',
		'Korat',
		'LaPerm',
		'Maine Coon',
		'Manx',
		'Munchkin',
		'Nebelung',
		'Norwegian Forest Cat',
		'Ocicat',
		'Oriental Long Hair',
		'Oriental Short Hair',
		'Oriental Tabby',
		'Persian',
		'Pixie-Bob',
		'Ragamuffin',
		'Ragdoll',
		'Russian Blue',
		'Scottish Fold',
		'Selkirk Rex',
		'Siamese',
		'Siberian',
		'Singapura',
		'Snowshoe',
		'Somali',
		'Sphynx (hairless cat)',
		'Tabby',
		'Tabby - black',
		'Tabby - Brown',
		'Tabby - buff',
		'Tabby - Grey',
		'Tabby - Orange',
		'Tabby - white',
		'Tiger',
		'Tonkinese',
		'Torbie',
		'Tortoiseshell',
		'Turkish Angora',
		'Turkish Van',
		'Tuxedo',
	);

	private $dog_breed_array = array(
		'Affenpinscher',
		'Afghan Hound',
		'Airedale Terrier',
		'Akbash',
		'Akita',
		'Alaskan Malamute',
		'American Bulldog',
		'American Eskimo Dog',
		'American Staffordshire Terrier',
		'American Water Spaniel',
		'Anatolian Shepherd',
		'Appenzell Mountain Dog',
		'Australian Cattle Dog/Blue Heeler',
		'Australian Kelpie',
		'Australian Shepherd',
		'Australian Terrier',
		'Basenji',
		'Basset Hound',
		'Beagle',
		'Bearded Collie',
		'Beauceron',
		'Bedlington Terrier',
		'Belgian Shepherd Dog Sheepdog',
		'Belgian Shepherd Laekenois',
		'Belgian Shepherd Malinois',
		'Belgian Shepherd Tervuren',
		'Bernese Mountain Dog',
		'Bichon Frise',
		'Black and Tan Coonhound',
		'Black Labrador Retriever',
		'Black Mouth Cur',
		'Black Russian Terrier',
		'Bloodhound',
		'Blue Lacy',
		'Bluetick Coonhound',
		'Border Collie',
		'Border Terrier',
		'Borzoi',
		'Boston Terrier',
		'Bouvier des Flanders',
		'Boxer',
		'Boykin Spaniel',
		'Briard',
		'Brittany Spaniel',
		'Brussels Griffon',
		'Bull Terrier',
		'Bullmastiff',
		'Cairn Terrier',
		'Canaan Dog',
		'Cane Corso Mastiff',
		'Carolina Dog',
		'Catahoula Leopard Dog',
		'Cattle Dog',
		'Caucasian Sheepdog (Caucasian Ovtcharka)',
		'Cavalier King Charles Spaniel',
		'Chesapeake Bay Retriever',
		'Chihuahua',
		'Chinese Crested Dog',
		'Chinese Foo Dog',
		'Chinook',
		'Chocolate Labrador Retriever',
		'Chow Chow',
		'Cirneco dell\'Etna',
		'Clumber Spaniel',
		'Cockapoo',
		'Cocker Spaniel',
		'Collie',
		'Coonhound',
		'Corgi',
		'Coton de Tulear',
		'Curly-Coated Retriever',
		'Dachshund',
		'Dalmatian',
		'Dandi Dinmont Terrier',
		'Doberman Pinscher',
		'Dogo Argentino',
		'Dogue de Bordeaux',
		'Dutch Shepherd',
		'English Bulldog',
		'English Cocker Spaniel',
		'English Coonhound',
		'English Pointer',
		'English Setter',
		'English Shepherd',
		'English Springer Spaniel',
		'English Toy Spaniel',
		'Entlebucher',
		'Eskimo Dog',
		'Feist',
		'Field Spaniel',
		'Fila Brasileiro',
		'Finnish Lapphund',
		'Finnish Spitz',
		'Flat-coated Retriever',
		'Fox Terrier',
		'Foxhound',
		'French Bulldog',
		'Galgo Spanish Greyhound',
		'German Pinscher',
		'German Shepherd Dog',
		'German Shorthaired Pointer',
		'German Spitz',
		'German Wirehaired Pointer',
		'Giant Schnauzer',
		'Glen of Imaal Terrier',
		'Golden Retriever',
		'Gordon Setter',
		'Great Dane',
		'Great Pyrenees',
		'Greater Swiss Mountain Dog',
		'Greyhound',
		'Harrier',
		'Havanese',
		'Hound',
		'Hovawart',
		'Husky',
		'Ibizan Hound',
		'Illyrian Sheepdog',
		'Irish Setter',
		'Irish Terrier',
		'Irish Water Spaniel',
		'Irish Wolfhound',
		'Italian Greyhound',
		'Italian Spinone',
		'Jack Russell Terrier',
		'Jack Russell Terrier (Parson Russell Terrier)',
		'Japanese Chin',
		'Jindo',
		'Kai Dog',
		'Karelian Bear Dog',
		'Keeshond',
		'Kerry Blue Terrier',
		'Kishu',
		'Klee Kai',
		'Komondor',
		'Kuvasz',
		'Kyi Leo',
		'Labrador Retriever',
		'Lancashire Heeler',
		'Lancashire Heeler',
		'Leonberger',
		'Lhasa Apso',
		'Lowchen',
		'Maltese',
		'Manchester Terrier',
		'Maremma Sheepdog',
		'Mastiff',
		'McNab',
		'Miniature Pinscher',
		'Mountain Cur',
		'Mountain Dog',
		'Munsterlander',
		'Neapolitan Mastiff',
		'New Guinea Singing Dog',
		'Newfoundland Dog',
		'Norfolk Terrier',
		'Norwegian Buhund',
		'Norwegian Elkhound',
		'Norwegian Lundehund',
		'Norwich Terrier',
		'Nova Scotia Duck-Tolling Retriever',
		'Old English Sheepdog',
		'Otterhound',
		'Papillon',
		'Patterdale Terrier (Fell Terrier)',
		'Pekingese',
		'Peruvian Inca Orchid',
		'Petit Basset Griffon Vendeen',
		'Pharaoh Hound',
		'Pit Bull Terrier',
		'Plott Hound',
		'Podengo Portugueso',
		'Pointer',
		'Polish Lowland Sheepdog',
		'Pomeranian',
		'Poodle',
		'Portuguese Water Dog',
		'Presa Canario',
		'Pug',
		'Puli',
		'Pumi',
		'Rat Terrier',
		'Redbone Coonhound',
		'Retriever',
		'Rhodesian Ridgeback',
		'Rottweiler',
		'Saint Bernard',
		'Saluki',
		'Samoyed',
		'Schipperke',
		'Schnauzer',
		'Scottish Deerhound',
		'Scottish Terrier Scottie',
		'Sealyham Terrier',
		'Setter',
		'Shar Pei',
		'Sheep Dog',
		'Shepherd',
		'Shetland Sheepdog Sheltie',
		'Shiba Inu',
		'Shih Tzu',
		'Siberian Husky',
		'Silky Terrier',
		'Skye Terrier',
		'Sloughi',
		'Smooth Fox Terrier',
		'South Russian Ovtcharka',
		'Spaniel',
		'Spitz',
		'Staffordshire Bull Terrier',
		'Standard Poodle',
		'Sussex Spaniel',
		'Swedish Vallhund',
		'Terrier',
		'Thai Ridgeback',
		'Tibetan Mastiff',
		'Tibetan Spaniel',
		'Tibetan Terrier',
		'Tosa Inu',
		'Toy Fox Terrier',
		'Treeing Walker Coonhound',
		'Vizsla',
		'Weimaraner',
		'Welsh Corgi',
		'Welsh Springer Spaniel',
		'Welsh Terrier',
		'West Highland White Terrier Westie',
		'Wheaten Terrier',
		'Whippet',
		'White German Shepherd',
		'Wire-haired Pointing Griffon',
		'Wirehaired Terrier',
		'Xoloitzcuintle/Mexican Hairless',
		'Yellow Labrador Retriever',
		'Yorkshire Terrier Yorkie',
	);

	private $fields = array(
		array(
			'id' => 'legacy-pet-id',
			'label' => 'Legacy Pet ID',
			'type' => 'number',
		),
		array(
			'id' => 'tag-number',
			'label' => 'Tag Number',
			'type' => 'text',
		),
		array(
			'id' => 'primary-pet-status',
			'label' => 'Primary Pet Status',
			'type' => 'select',
			'options' => array(
				'c' => 'Created',
				'q' => 'Quarantined',
				'v' => 'Available',
				's' => 'Sidelined',
				'a' => 'Adopted',
				'd' => 'Deceased',
				'r' => 'Other',
				'p' => 'Adoption Pending',
				't' => 'Transferred Out of KAR',
				'u' => 'Returned to Relinquisher',
			),
		),
		array(
			'id' => 'secondary-pet-status',
			'label' => 'Secondary Pet Status',
			'type' => 'select',
			'options' => array(
				'n' => 'None',
				'e' => 'Euthanized',
				'm' => 'Medical',
				'a' => 'Application Screening',
				'i' => 'Incomplete/Missing Info',
				'o' => 'Other',
			),
		),
		array(
			'id' => 'adoption-fee',
			'label' => 'Adoption Fee',
			'type' => 'number',
		),
		array(
			'id' => 'pet-type',
			'label' => 'Pet Type',
			'type' => 'select',
			'options' => array(
				'c' => 'Cat',
				'd' => 'Dog',
			),
		),
		array(
			'id' => 'pet-sex',
			'label' => 'Pet Sex',
			'type' => 'select',
			'options' => array(
				'm' => 'Male',
				'f' => 'Female',
			),
		),
		array(
			'id' => 'date-of-birth',
			'label' => 'Date of Birth',
			'type' => 'date',
		),
		array(
			'id' => 'declawed',
			'label' => 'Declawed',
			'type' => 'checkbox',
		),
		array(
			'id' => 'microchipped',
			'label' => 'Microchipped',
			'type' => 'checkbox',
		),
		array(
			'id' => 'microchip-brand',
			'label' => 'Microchip brand',
			'type' => 'text',
		),
		array(
			'id' => 'microchip-id',
			'label' => 'Microchip ID',
			'type' => 'text',
		),
		array(
			'id' => 'cat-primary-breed',
			'label' => 'Primary Breed',
			'type' => 'select',
			'options' => array(
				'a' => 'HUGE LIST',
			),
		),
		array(
			'id' => 'cat-secondary-breed',
			'label' => 'Secondary Breed',
			'type' => 'select',
			'options' => array(
				'a' => 'ANOTHER HUGE LIST',
			),
		),
		array(
			'id' => 'dog-primary-breed',
			'label' => 'Primary Breed',
			'type' => 'select',
			'options' => array(
				'a' => 'HUGE LIST',
			),
		),
		array(
			'id' => 'dog-secondary-breed',
			'label' => 'Secondary Breed',
			'type' => 'select',
			'options' => array(
				'a' => 'ANOTHER HUGE LIST',
			),
		),
		array(
			'id' => 'mixed',
			'label' => 'Mixed',
			'type' => 'checkbox',
		),
		array(
			'id' => 'pet-size',
			'label' => 'Pet Size',
			'type' => 'select',
			'options' => array(
				's' => 'Small',
				'm' => 'Medium',
				'l' => 'Large',
				'xl' => 'Extra Large',
			),
		),
		array(
			'id' => 'shots-up-to-date',
			'label' => 'Shots up to date',
			'type' => 'checkbox',
		),
		array(
			'id' => 'spayed-neutered',
			'label' => 'Spayed/Neutered',
			'type' => 'checkbox',
		),
		array(
			'id' => 'no-cats',
			'label' => 'No Cats',
			'type' => 'checkbox',
		),
		array(
			'id' => 'no-dogs',
			'label' => 'No Dogs',
			'type' => 'checkbox',
		),
		array(
			'id' => 'no-kids',
			'label' => 'No Kids',
			'type' => 'checkbox',
		),
		array(
			'id' => 'housebroken',
			'label' => 'Housebroken',
			'type' => 'checkbox',
		),
		array(
			'id' => 'special-needs',
			'label' => 'Special Needs',
			'type' => 'checkbox',
		),
		array(
			'id' => 'special-needs-description',
			'label' => 'Special Needs Description',
			'type' => 'textarea',
		),
		array(
			'id' => 'last-update',
			'label' => 'Last Update',
			'type' => 'datetime',
		),
		array(
			'id' => 'entry-date',
			'label' => 'Entry Date',
			'type' => 'date',
		),
		array(
			'id' => 'adopted-date',
			'label' => 'Adopted Date',
			'type' => 'date',
		),
		array(
			'id' => 'pending',
			'label' => 'Pending',
			'type' => 'checkbox',
		),
		array(
			'id' => 'volunteer-comments',
			'label' => 'Volunteer Comments',
			'type' => 'textarea',
		),
		array(
			'id' => 'pet-origin',
			'label' => 'Pet Origin',
			'type' => 'text',
		),
		array(
			'id' => 'born-to',
			'label' => 'Born to',
			'type' => 'text',
		),
		array(
			'id' => 'offsite',
			'label' => 'Offsite',
			'type' => 'checkbox',
		),
		array(
			'id' => 'camp-ravenwood',
			'label' => 'Camp Ravenwood',
			'type' => 'checkbox',
		),
		array(
			'id' => 'adopted-date-2',
			'label' => 'Adopted Date (2)',
			'type' => 'date',
		),
		array(
			'id' => 'adopted-date-3',
			'label' => 'Adopted Date (3)',
			'type' => 'date',
		),
		array(
			'id' => 'pet-returned',
			'label' => 'Pet Returned',
			'type' => 'date',
		),
		array(
			'id' => 'transfer-date',
			'label' => 'Transfer Date',
			'type' => 'date',
		),
		array(
			'id' => 'at-office',
			'label' => 'At Office',
			'type' => 'checkbox',
		),
		array(
			'id' => 'black-friday',
			'label' => 'Black Friday',
			'type' => 'checkbox',
		),
		array(
			'id' => 'origin-reason',
			'label' => 'Origin Reason',
			'type' => 'textarea',
		),
		array(
			'id' => 'pets-number',
			'label' => 'Pets Number',
			'type' => 'number',
		),
		array(
			'id' => 'bissell',
			'label' => 'Bissell',
			'type' => 'checkbox',
		),
		array(
			'id' => 'cafe',
			'label' => 'Cafe',
			'type' => 'checkbox',
		),
		array(
			'id' => 'barn',
			'label' => 'Barn',
			'type' => 'checkbox',
		),
		array(
			'id' => 'ready-to-fix',
			'label' => 'Ready to fix',
			'type' => 'checkbox',
		),
	);

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'pet-info',
				__( 'Pet Info', 'arms' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'normal',
				'default'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 *
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'pet_info_data', 'pet_info_nonce' );
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'pet_info_' . $field['id'], true );
			switch ( $field['type'] ) {
				case 'checkbox':
					$input = sprintf(
						'<input %s id="%s" name="%s" type="checkbox" value="1">',
						$db_value === '1' ? 'checked' : '',
						$field['id'],
						$field['id']
					);
					break;
				case 'select':
					$input = sprintf(
						'<select id="%s" name="%s">',
						$field['id'],
						$field['id']
					);
					if ( $field['id'] == 'cat-primary-breed' || $field['id'] == 'cat-secondary-breed' ) {
						$field['options'] = $this->cat_breed_array;
					}
					if ( $field['id'] == 'dog-primary-breed' || $field['id'] == 'dog-secondary-breed' ) {
						$field['options'] = $this->dog_breed_array;
					}
					foreach ( $field['options'] as $key => $value ) {
						$field_value = ! is_numeric( $key ) ? $key : $value;
						$input .= sprintf(
							'<option %s value="%s">%s</option>',
							$db_value === $field_value ? 'selected' : '',
							$field_value,
							$value
						);
					}
					$input .= '</select>';
					break;
				case 'textarea':
					$input = sprintf(
						'<textarea class="large-text" id="%s" name="%s" rows="5">%s</textarea>',
						$field['id'],
						$field['id'],
						$db_value
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$field['type'] !== 'color' ? 'class="regular-text"' : '',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			$output .= $this->row_format( $label, $input, $field['id'] );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	/**
	 * Generates the HTML for table rows.
	 */
	public function row_format( $label, $input, $row_class ) {
		return sprintf(
			'<tr id="%s-row"><th scope="row">%s</th><td>%s</td></tr>',
			$row_class,
			$label,
			$input
		);
	}
	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['pet_info_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['pet_info_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'pet_info_data' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
				}
				update_post_meta( $post_id, 'pet_info_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'pet_info_' . $field['id'], '0' );
			}
		}
	}
}
new ARMS_Meta_Box;
