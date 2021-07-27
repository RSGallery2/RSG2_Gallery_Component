	//This will sort your array
	function SortByIntValue(a, b) {
		var aValue = parseInt(jQuery(a).value, 10);
		var bValue = parseInt(jQuery(b).value, 10);
		return aValue - bValue;
	}


	// :
	jQuery(document).ready(function ($) {
		// alert ("assign");

		jQuery(".changeOrder").on('keyup mouseup',
			function (event) {
				var Idx;
				var element;
				var Count;

				event.preventDefault();

				var actElement = event.target;

				// Empty input
				if (actElement.value == '') {
					alert("Test 01 out");
					return;
				}

				var strActValue = actElement.value;
				var actValue = parseInt(actElement.value);
				var actGallery_id = actElement.getAttribute("gallery_id");

				// Negative value will be corrected to lowest value
				if (actValue < 1) {
					actValue = 1;
					actElement.value = actValue;
				}

				var OrderingAll = jQuery(".changeOrder");

				if (OrderingAll === null) {
					alert("OrderingAll === null");
				}

				Count = OrderingAll.length;

				var Ordering = [];
				for (Idx = 0; Idx < Count; Idx++) {

					element = OrderingAll[Idx];

					var gallery_id = element.getAttribute("gallery_id");
					if (actGallery_id == gallery_id) {
//                        alert("Test 03.04");

						Ordering.push(element);
					}
				}

				if (Ordering.length == 0) {
					return;
				}

				Count = Ordering.length;

				// Value higher than the count will be set to highest possible
				if (actValue > Count) {
					actValue = Count;
					actElement.value = actValue;
				}

				var OutTxt = '';

				// Sort array asc
				Ordering.sort(SortByIntValue);

				// assign changed ordering values
				var ChangeOld = 0;
				for (Idx = 1; Idx <= Count; Idx++) {
					element = Ordering[Idx - 1];

					var strIdx = Idx.toString();
					// not matching the changed element
					if (strActValue != element.value) {
						// Value different to expected so set it
						// The orderingIdx should be the Idx value
						if (element.value != strIdx) {
							element.value = strIdx;
						}
					}
					else {
						// Undefined up or down ?
						// UP: Missing
						if (ChangeOld == 0) {
							//							alert ("IDX: " + Idx + " " + "Value: " + parseInt(element.value));

							// New id moved up, hole found
							if (Idx < parseInt(element.value)) {
								ChangeOld = Idx;
							}
							else {
								// Down: Move old element up
								ChangeOld = Idx + 1;
							}
						}

						// On Old element assign changed value
						if (actElement.id != element.id) {
							element.value = ChangeOld.toString();
						}
					}
				}

				// Print array order
				OutTxt += '\n';
				for (Idx = 0; Idx < Count; Idx++) {
					element = Ordering[Idx];

					OutTxt += element.value + ",";
				}

				console.log("Order: " + OutTxt);
				/**/

			}
		);

		//alert ("done");
	});

