
		// Общие функции




	    // Общие функции. Загрузка статусов сделок при загрузки страницы.
	    $.ajax({
	        url: config.routes.select,
	        type: 'get',
	        data: {
	            type: "statusID"
	        },
	        success: function (data) {
	            console.log('statusID', data);
	            
	            data.forEach((item) => {
	                //$('#statusID').append('<option value="' + item.id + '">' + item.name + '</option>');
	                $('#statusID').append('<option value="' + item.id + '" title="'+ item.color + '">' + item.name + '</option>');
	                //'<div><span class="legend-indicator" style="background-color: ' + state.color + ' !important;"></span>' + state.text + '</div>'
	        
	
	            });
	        }
	    });


		// Общие функции. Загрузка статусов сделок при выборе в карточке статуса. /// --- нужно добавить еще выбор типа, общее, доставка или это график объектов
	    $("#statusID").select2({
	        minimumResultsForSearch: Infinity,
	        ajax: {
	            url: config.routes.select,
	            dataType: 'json',
	            delay: 250,
	            data: function (params) {
	                return {
	                    type: "statusID"
	                };
	            },
	            processResults: function (data) {
	                console.log('data', data);
	                var res = data.map(function (item) {
	                    return {id: item.id, text: item.name, color: item.color};
	                    });
	                return {
	                    results: res
	                };
	            }
	
	        },
	        templateResult: formatState,
	        templateSelection: formatRepoSelection
	        
	    });
	
		// Общие функции. Загрузка статусов сделок при выборе в карточке статуса.
	    function formatState (state) {
	        console.log('state', state);
	        if (!state.id) {
	            return state.name;
	        }
	        console.log('state', state);
	        
	        var $state = $(
	            '<div><span class="legend-indicator" style="background-color: ' + state.color + ' !important;"></span>' + state.text + '</div>'
	        );
	        return $state;
	    };
		
		// Общие функции. Загрузка статусов сделок при выборе в карточке статуса.
	    function formatRepoSelection (state) {
	        console.log('formatRepoSelection', state);
	        
	        $(".select2-selection__arrow").remove();
	
	        if (!state.color) {
	            state.color = state.title;
	        }
	
	        var $state = $(
	            '<div><span class="legend-indicator" style="background-color: ' + state.color + ' !important;"></span>' + state.text + '</div>'
	        );
	
	        return $state;
	    }


	
	    // Общие функции. Получаем менеджеров. (В большинстве случаев это общие функции, менеджер в компании имеет тот же доступ к графику объектов что и к графику доставок)
	    $.ajax({
	        url: config.routes.select,
	        type: 'get',
	        data: {
	            type: "id_manager"
	        },
	        success: function (data) {
	            console.log('id_manager', data);
	            
	            data.forEach((item) => {
	                $('#id_manager').append('<option value="' + item.id + '">' + item.name + '</option>');
	                $('#managerSelect').append('<option value="' + item.id + '">' + item.name + '</option>');
	            });
	        }
	    });
	
		// Общие функции. Поиск конечного адреса при заполнение полей адреса.
	    function ymapsInit(evt) {
	        console.log(`Произошло событие ${evt}`);
	        removeClass();
	        //addClass();
	
	        var suggestView = new ymaps.SuggestView(evt, {
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
	
		// Общие функции. Блокировка полей доступа на форме.
	    function elementsDisabled(e) {
	        //console.log(`Истина или ложь ${e}`)
	        if (e) {
	            span = 'none';
	        } else {
	            span = 'auto';
	        }
	
	        elements = document.getElementById("eventModal").getElementsByTagName("input");
	                                    
	        Array.from(elements).forEach(function(element) {
	            //console.log(elements['amount']);
	            element.disabled = e;
	            if (elements['amount'] || elements['distance']) {
	                elements['amount'].disabled = 'true';
	                elements['distance'].disabled = 'true';
	            }
	            
	        });
	                        
	        elements = document.getElementById("eventModal").getElementsByTagName("select");
	                                    
	        Array.from(elements).forEach(function(element) {
	            element.disabled = e;
	        });
	                        
	        elements = document.getElementById("eventModal").getElementsByTagName("textarea");
	                        
	        Array.from(elements).forEach(function(element) {
	            element.disabled = e;
	        });
	
	        elements = document.getElementById("eventModal").getElementsByTagName("button");
	                        
	        Array.from(elements).forEach(function(element) {
	            element.disabled = e;
	        });
	
	
	        $(".warehouse").css("pointer-events", span);
	
	    }

		// Общие функции. Показать или скрыть поле второго номера телефона.
	    $('input#nomer2').click(function () {
	        if ($('input#nomer2').is(':checked')) {
	            $('#nomer2s').fadeIn().show();
	            
	            return;
	        } else {
	            $('#nomer2s').fadeOut(300);
	            $('#nameContacts2').val('');
	            $('#phoneContacts2').val('');
	        }
	    });
	
		// Общие функции. Поле цена только цифры
	    $("#price").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });

		// Общие функции. Маска для телефонов и для электронных почт.
	    function getMasks() {
	
	        $('input.maskPhone').inputmask("(9{3})9{3}-9{2}-9{2}", {
	            clearIncomplete: true,
	            definitions: {
	                '*': {
	                    validator: "/^7|8/"
	                }
	            }
	        });
	        $('input.maskPhones').inputmask("79{3}9{3}9{2}9{2}", {
	            clearIncomplete: true
	        });
	        $('input.maskEmail').inputmask({
	            mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
	            clearIncomplete: true,
	            greedy: false,
	            onBeforePaste: function (pastedValue, opts) {
	                pastedValue = pastedValue.toLowerCase();
	                return pastedValue.replace("mailto:", "");
	            },
	            definitions: {
	                '*': {
	                    validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
	                    cardinality: 1,
	                    casing: "lower"
	                }
	            }
	        });
	    }

		// Общие функции. Переход на график с гет пораметрами и открытие карточки с последующим обнулением гет параметров, при загрузке страницы.
		var parseQueryString = function() {
        
            var str = window.location.search;
            var objURL = {};
        
            str.replace(
                new RegExp( "([^?=&]+)(=([^&]*))?", "g" ),
                function( $0, $1, $2, $3 ){
                    objURL[ $1 ] = $3;
                }
            );
            return objURL;
        };
        
        var params = parseQueryString();
        
        if (params["id"]) {
            
            //console.log(params["id"]);
            
            setTimeout(function () {
                
				//params["id"]
				calendar.trigger('eventClick', params["id"], 'true');
				var baseUrl = [location.protocol, '//', location.host, location.pathname].join('');
            	window.history.replaceState({}, "", baseUrl);
				

            }, 500)

        } else if (params["phoneHistory"]) {
            
            //console.log(params["phoneHistory"]);
            
            
        } else {
            
            console.log("Пусто или ошибка");
            
            var baseUrl = [location.protocol, '//', location.host, location.pathname].join('');
            window.history.replaceState({}, "", baseUrl);
            
            
        }


	    // Общие функции. Всплывающие окна. добавляем в DOM контейнер для сообщений.
	    if (!document.querySelector('.messages')) {
			const container = document.createElement('div');
			container.classList.add('messages');
			container.style.cssText = 'position: fixed; bottom: 15px; right: 15px; width: 350px; z-index: 999999;';
			document.body.appendChild(container);
	    }
	
	    // Общие функции. Всплывающие окна. получаем контейнер.
	    const messages = document.querySelector('.messages');

	
		// Общие функции. Автоподсказка для первого номера клиента.
	    const autoCompleteJS = new autoComplete({
	        placeHolder: "Добавление клиента",
	        selector: "#nameContacts",
	        data: {
	            src: async (query) => {
	                try {
	                    console.log('query', query);
	                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	                    const source = await fetch(config.routes.action, {
	                        method: 'post',
	                        body: JSON.stringify({
	                            search: query,
	                            type: 'phone'
	                        }),
	                        headers: {
	                            "Content-Type": "application/json",
	                            "Accept": "application/json, text-plain, */*",
	                            "X-Requested-With": "XMLHttpRequest",
	                            "X-CSRF-TOKEN": token
	                        },
	                    });
	                    data = await source.json();
	                    console.log('data', data);
	                    return data;
	                } catch (error) {
	                    return error;
	                }
	            },
	            keys: ["nameContacts", "phoneContacts"],
	            filter: (list) => list.filter((item) => item.key in item.value)
	        },
	        resultsList: {
	            maxResults: 15,
	            element: (list, data) =>
	            {
	                if (!data.results.length) {
	                    const message = document.createElement("li");
	                    message.innerHTML = `
	                        <div class="flex gap-2">
	                            <div class="whitespace-normal line-clamp-1">
	                                Нет результатов "${data.query}"
	                            </div>
	                        </div>`;
	                    list.prepend(message);
	                }
	            },
	            noResults: true,
	        },
	        resultItem: {
	            tag: "li",
	            element: (item, data) => {
	
	                if (data.key == 'nameContacts') {
	                    data.match = data.match + ' (' + data.value['phoneContacts'] + ')';
	                } else if (data.key == 'phoneContacts') {
	                    data.match = data.match + ' (' + data.value['nameContacts'] + ')';
	                }
	
	                item.innerHTML = `
	                <div class="flex gap-2">
	                    <div class="whitespace-normal line-clamp-1">
	                        ${data.match}
	                    </div>
	                </div>`;
	            },
	            highlight: true
	        },
	        events: {
	            input: {
	            focus(event) {
	                console.log('event', event);
	                autoCompleteJS.start();
	            },
	            selection: (event) => {
	                const feedback = event.detail;
	                const selection = feedback.selection.value[feedback.selection.key];
	                
	                const nameContacts = feedback.selection.value['nameContacts'];
	                const phoneContacts = feedback.selection.value['phoneContacts'];
	
	                autoCompleteJS.input.value = nameContacts;
	                $('#phoneContacts').val(phoneContacts);
	            }
	            }
	        }
	    });
	
		// Общие функции. Автоподсказка для второго номера клиента.
	    const autoCompleteJS2 = new autoComplete({
	        placeHolder: "Добавление клиента",
	        selector: "#nameContacts2",
	        data: {
	            src: async (query) => {
	                try {
	                    console.log('query', query);
	                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	                    const source = await fetch(config.routes.action, {
	                        method: 'post',
	                        body: JSON.stringify({
	                            search: query,
	                            type: 'phone'
	                        }),
	                        headers: {
	                            "Content-Type": "application/json",
	                            "Accept": "application/json, text-plain, */*",
	                            "X-Requested-With": "XMLHttpRequest",
	                            "X-CSRF-TOKEN": token
	                        },
	                    });
	                    data = await source.json();
	                    console.log('data', data);
	                    return data;
	                } catch (error) {
	                    return error;
	                }
	            },
	            keys: ["nameContacts", "phoneContacts"],
	            filter: (list) => list.filter((item) => item.key in item.value)
	        },
	        resultsList: {
	            maxResults: 15,
	            element: (list, data) =>
	            {
	                if (!data.results.length) {
	                    const message = document.createElement("li");
	                    message.innerHTML = `
	                        <div class="flex gap-2">
	                            <div class="whitespace-normal line-clamp-1">
	                                Нет результатов "${data.query}"
	                            </div>
	                        </div>`;
	                    list.prepend(message);
	                }
	            },
	            noResults: true,
	        },
	        resultItem: {
	            tag: "li",
	            element: (item, data) => {
	
	                if (data.key == 'nameContacts') {
	                    data.match = data.match + ' (' + data.value['phoneContacts'] + ')';
	                } else if (data.key == 'phoneContacts') {
	                    data.match = data.match + ' (' + data.value['nameContacts'] + ')';
	                }
	
	                item.innerHTML = `
	                <div class="flex gap-2">
	                    <div class="whitespace-normal line-clamp-1">
	                        ${data.match}
	                    </div>
	                </div>`;
	            },
	            highlight: true
	        },
	        events: {
	            input: {
	            focus(event) {
	                console.log('event', event);
	                autoCompleteJS2.start();
	            },
	            selection: (event) => {
	                const feedback = event.detail;
	                const selection = feedback.selection.value[feedback.selection.key];
	                
	                const nameContacts = feedback.selection.value['nameContacts'];
	                const phoneContacts = feedback.selection.value['phoneContacts'];
	
	                autoCompleteJS2.input.value = nameContacts;
	                $('#phoneContacts2').val(phoneContacts);
	            }
	            }
	        }
	    });
	
		// Общие функции. Поиск по сделкам в модальном окне.
        const autoCompleteJS3 = new autoComplete({
	        placeHolder: "Поиск по сделкам...",
	        selector: "#search",
	        data: {
	            src: async (query) => {
	                try {
	                    console.log('query', query);
	                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	                    const source = await fetch(config.routes.action, {
	                        method: 'post',
	                        body: JSON.stringify({
	                            search: query,
	                            type: 'search'
	                        }),
	                        headers: {
	                            "Content-Type": "application/json",
	                            "Accept": "application/json, text-plain, */*",
	                            "X-Requested-With": "XMLHttpRequest",
	                            "X-CSRF-TOKEN": token
	                        },
	                    });
	                    data = await source.json();
	                    console.log('data', data);
	                    return data;
	                } catch (error) {
	                    return error;
	                }
	            },
	            keys: ["phoneContacts", "id", "title"],
	            filter: (list) => list.filter((item) => item.key in item.value)
	        },
	        resultsList: {
	            maxResults: 15,
	            element: (list, data) =>
	            {
	                if (!data.results.length) {
	                    const message = document.createElement("li");
	                    message.innerHTML = `
	                        <div class="flex gap-2">
	                            <div class="whitespace-normal line-clamp-1">
	                                Нет результатов "${data.query}"
	                            </div>
	                        </div>`;
	                    list.prepend(message);
	                }
	            },
	            noResults: true,
	        },
	        resultItem: {
	            tag: "li",
	            element: (item, data) => {
	                console.log('data.match', data.match);
	                console.log('data.key', data.key);
	
	                data.match = data.match + ' (Сделка: <u>' + data.value['id'] + '</u> | <u>' + moment(data.value['start']).format('DD.MM.YYYY HH:mm') + '</u> | ' + data.value['title'] + ' | <u>' + data.value['price'] + 'р.</u> )';
	
	                /*
	                if (data.key == 'id') {
	                    data.match = data.match + ' (' + data.value['id'] + ' ' + data.value['title'] + ')';
	                } else if (data.key == 'phoneContacts') {
	                    data.match = data.match + ' (' + data.value['id'] + ' ' + data.value['title'] + ')';
	                }
	                    */
	
	                item.innerHTML = `
	                <div class="flex gap-2">
	                    <div class="whitespace-normal line-clamp-1">
	                        ${data.match}
	                    </div>
	                </div>`;
	            },
	            highlight: true
	        },
	        events: {
	            input: {
	            focus(event) {
	                console.log('event', event);
	                autoCompleteJS3.start();
	            },
	            selection: (event) => {
	
	
	
	                const feedback = event.detail;
	                const selection = feedback.selection.value[feedback.selection.key];
	
	                console.log('id', feedback.selection.value['id']);
					
	                $('#searchModal').modal('hide');
                    calendar.trigger('eventClick', feedback.selection.value['id'], 'true');
	
	            }
	            }
	        }
	    });


	
		// Общие функции. Клонировать сделку.
		$('#cloneEventBtn').on('click', function () {
	            console.log('cloneEventBtn');
	            if (confirm("Действительно хотите создать копию?")) {
	                var id = $('#id').val();
	
	                $.ajax({
	                    url: config.routes.action,
	                    type: "POST",
	                    data: {
	                        id: id,
	                        type: 'clone'
	                    },
	                    success: function (data) {
	                        formulario.reset();
	                        $(".popover").remove();
	                        $('#eventModal').modal('hide');
	                        calendar.refetchEvents();
	                    },
	                    error: function (xhr, status, error) {
	                        console.error(xhr.responseText);
	                        alert("Ошибка создания копии.");
	                    }
	                });
	            }
	    });
	
		// Общие функции. История изменений по сделке.
	    $('#historyEventBtn').on('click', function () {
	            console.log('historyEventBtn');
	                var id = $('#id').val();
	
	                $.ajax({
	                    url: config.routes.action,
	                    type: "POST",
	                    data: {
	                        id: id,
	                        type: 'history'
	                    },
	                    success: function (data) {
	                        console.log('data', data);
	                        $("#historyModalLabel").html('История изменений по карте: ' + id);
	                        $('#history_output').html(data.html);
	
	                        //formulario.reset();
	                        $('#eventModal').modal('hide');
	                        $('#historyModal').modal('show');
	                        //calendar.refetchEvents();
	                    },
	                    error: function (xhr, status, error) {
	                        console.error(xhr.responseText);
	                        alert("Ошибка загрузки истории.");
	                    }
	                });
	    });