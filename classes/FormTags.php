<?php

if ( !defined('ABSPATH') ) {
	die;
}

class FormCreate extends FormContainer {
	public $pluginsn = 'nvb_';
	
	public function OpeningTag( $value ) { 
		$group_class = "";
		$content = '<tr>';
		
		if( isset( $value['id'] ) ) {
			$content .= '<th scope="row"><label for="' . $value['id'] . '">' . $value['name'] . '</label></th>';
		}
		$content .= '<td>';

		return $content;
	}
	
	public function ClosingTag( $value ) { 
		if( isset( $value['desc'] ) ) {
			$content = '<p class="description" id="' . $value['id'] . '-description">' . $value['desc'] . '</p></td></tr>';
		} else {
			$content = '<td></tr>';
		}

		return $content;
	 }
		
	 public function Header1( $value ) {
		 $content = $this->OpeningTag( $value );
		 $content .= '<h1>' . $value['name'] . '</h1>';
		 $content .= $this->ClosingTag( $value );
 
		 return $content;
	 }
		
	public function Header2( $value ) {
		$content = $this->OpeningTag( $value );
		$content .= '<h2>' . $value['name'] . '</h2>';
		$content .= $this->ClosingTag( $value );

		return $content;
	}
		
	public function Header3( $value ) {
		$content = $this->OpeningTag( $value );
		$content .= '<h3>' . $value['name'] . '</h3>';
		$content .= $this->ClosingTag( $value );

		return $content;
	}
		
	public function SectionText( $value ) { 
		$content = $this->OpeningTag( $value );
		$text = get_option( $value['id'], $value['std'] );

		$content .= '<input name="'.$value['id'].'" type="text" id="'.$value['id'].'" aria-describedby="'.$value['id'].'-description" value="' . $text . '" class="regular-text">';
		$content .= $this->ClosingTag( $value );

		return $content;
	}
		
	function SectionArray( $value ) {
		$content = $this->OpeningTag( $value );
		$text = get_option( $value['id'], $value['std'] );

		$content .= '<textarea name="' . $value['id'] . '" id="' . $value['id'] . '" class="whitespace" cols="100" rows="30" tabindex="4">' . $text . '</textarea>';
		
		$content .= $this->ClosingTag($value);

		return $content;
	}
	
	public function SectionColorPicker( $value ) { 
		$content = $this->OpeningTag( $value );
		$select_value = get_option( $value['id'], $value['std'] );
		 
		$content .= '<input type="text" id="' . $value['id'] . '" name="' . $value['id'] . '" value="' . $select_value . '" class="colorpicker" />';
		
		$content .= $this->ClosingTag($value);

		return $content;
	}
	
	public function SectionSelect( $value ) { 
		$content = $this->OpeningTag( $value );
		$select_value = get_option( $value['id'], $value['std'] );

		$content .= '<select id="' . $value['id'] . '" name="' . $value['id'] . '">';

		foreach( $value['options'] as $id => $row ) {
			if( $select_value == $id ) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$content .= '<option value="' . $id . '" ' . $selected . '>' . $row . '</option>';	
		}
		
		$content .= '</select>';
		
		$content .= $this->ClosingTag($value);

		return $content;
	}
}

?>