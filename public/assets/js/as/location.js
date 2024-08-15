		// инициализация карт
		ymaps.ready(init);

		function init() {

			var suggestView = new ymaps.SuggestView('location', {
				boundedBy: [
					[53.38008177, 34.04055003],
					[57.78053234, 40.89601878]
				],
				// - искать только в этой области
				strictBounds: true,
				// - требуемое количество результатов
				results: 10,
				provider: {
					suggest: function (request, options) {

						var parseItems = ymaps.suggest(request).then(function (items) {
							for (var i = 0; i < items.length; i++) {

								var displayNameArr = items[i].displayName.split(',');
								var displayNameArrValue = items[i].value.split(',');


								var newDisplayName = [];
								for (var j = 0; j < displayNameArr.length; j++) {
									if (displayNameArr[j].indexOf('Россия') == -1) {
										newDisplayName.push(displayNameArr[j]);
									}
								}
								items[i].displayName = newDisplayName.join();


								var newDisplayNameValue = [];
								for (var j = 0; j < displayNameArrValue.length; j++) {
									if (displayNameArrValue[j].indexOf('Россия') == -1) {
										newDisplayNameValue.push(displayNameArrValue[j]);
									}
								}
								items[i].value = newDisplayNameValue.join();


							}

							console.log('items', items);

							return items;
						});

						//console.log('parseItems', parseItems);

						return parseItems;


					}
				}
			});



			var suggestView = new ymaps.SuggestView('loadingAddress', {
				boundedBy: [
					[53.38008177, 34.04055003],
					[57.78053234, 40.89601878]
				],
				// - искать только в этой области
				strictBounds: true,
				// - требуемое количество результатов
				results: 10,
				provider: {
					suggest: function (request, options) {

						var parseItems = ymaps.suggest(request).then(function (items) {
							for (var i = 0; i < items.length; i++) {

								var displayNameArr = items[i].displayName.split(',');
								var displayNameArrValue = items[i].value.split(',');


								var newDisplayName = [];
								for (var j = 0; j < displayNameArr.length; j++) {
									if (displayNameArr[j].indexOf('Россия') == -1) {
										newDisplayName.push(displayNameArr[j]);
									}
								}
								items[i].displayName = newDisplayName.join();


								var newDisplayNameValue = [];
								for (var j = 0; j < displayNameArrValue.length; j++) {
									if (displayNameArrValue[j].indexOf('Россия') == -1) {
										newDisplayNameValue.push(displayNameArrValue[j]);
									}
								}
								items[i].value = newDisplayNameValue.join();


							}

							console.log('items', items);

							return items;
						});

						//console.log('parseItems', parseItems);

						return parseItems;


					}
				}
			});



			


		}