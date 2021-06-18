<?php
	class ItemVariantType extends Relation {
		static	$always_cache	= true;

		function description() {
			return ItemVariantTypeName::find_first('item_variant_type_id=' . $this->id . ' AND language_id=' . $_SESSION['language_id'], array('cache' => true));
		}
	}