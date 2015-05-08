(function() {
    CKEDITOR.dialog.add( 'cke_autotext', function( editor )
    {
        var _dialog, _insertMode, _element, pluginName = 'cke_autotext',
            // TODO: fill names and variablres from server via AJAX query
            variablesUser = ['', 'user_first_name', 'user_last_name', 'user_email', 'user_skype', 'user_phone', 'user_user_extra_1', 'user_user_extra_2',
                'user_country', 'user_city', 'user_vkontakte', 'user_facebook', 'user_twitter', 'user_webPhoto', 'user_photoPath', 
                'user_partnerLinkFull', 'user_businessLinkFull'],
            namesUser = ['Выбрать из списка', 'Имя пользователя', 'Фамилия пользователя','Email', 'skype', 'Телефон', 'extra_1', 'extra_2',
                'Страна', 'Город', 'vkontakte', 'facebook', 'twitter', 'webPhoto', 'photoPath', 'partnerLinkFull', 'businessLinkFull'],
            selectedVariableUser;
        var variablesSponsor = ['', 'sponsor_first_name', 'sponsor_last_name', 'sponsor_skype', 'sponsor_phone', 'sponsor_sponsor_extra_1', 'sponsor_sponsor_extra_2', 
            'sponsor_country', 'sponsor_city','sponsor_email', 'sponsor_vkontakte', 'sponsor_facebook', 'sponsor_twitter', 'sponsor_webPhoto', 
            'sponsor_photoPath', 'sponsor_partnerLinkFull', 'sponsor_businessLinkFull'],
            namesSponsor = ['Выбрать из списка','Имя cпонсорa', 'Фамилия cпонсорa', 'skype', 'Телефон', 'extra_1', 'extra_2',
                'Страна', 'Город', 'Email', 'vkontakte', 'facebook', 'twitter', 'webPhoto', 'photoPath', 'partnerLinkFull', 'businessLinkFull'],
            selectedVariableSponsor;
        var arrUser = [];
        var arrSponsor = [];
        for (var i = 0; i < namesUser.length; i++) {
            arrUser[i] = [namesUser[i], variablesUser[i]];
        }
        for (var i = 0; i < namesSponsor.length; i++) {
            arrSponsor[i] = [namesSponsor[i], variablesSponsor[i]];
        }
        
        return {
            // название диалогового окна
            title : 'Insert Fields',
 
            // минимальная ширина
            minWidth : 200,
 
            // минимальная высота
            minHeight : 200,
 
            // элементы
            contents : [ 
                {
                    id : 'tab1',
                    label : 'Label',
                    title : 'Title',
                    expand : true,
                    padding : 0,
                    elements :
                    [
                        {
                            type : 'select',
                            // TODO: fill names and variablres and insert to Items array
                            items:arrUser,
                            id : 'fieldSelect',
                            label : 'Пользователь',
                            onChange: function( api ) {
                                selectedVariableUser = this.getValue();
                            }
                        },
                        {
                            type : 'select',
                            // TODO: fill names and variablres and insert to Items array
                            items: arrSponsor,
                            id : 'fieldSelect',
                            label : 'Спонсор',
                            onChange: function( api ) {
                                selectedVariableSponsor = this.getValue();
                            }
                        }
                    ]
                }
            ],
 
            // в окне будут 1 кнопки Cancel
            buttons : [
                CKEDITOR.dialog.okButton,
                CKEDITOR.dialog.cancelButton
            ],
 
            // обработчик нажатия на кнопку Ok
            onOk : function() { 
                    text = '';
 
                    if ((selectedVariableUser != undefined) && (selectedVariableUser !== '')) {
                        text = '{{ '+selectedVariableUser+' }}';
                        selectedVariableUser = '';
                    };
                    if ((selectedVariableSponsor != undefined) && (selectedVariableSponsor !== '')) {
                        text += '{{ '+selectedVariableSponsor+' }}';
                        selectedVariableSponsor = '';
                    };

                    editor.insertText(text);
            },
            onShow : function() {
 
//                // получаем элемент, который выбрали
//                var sel = editor.getSelection(),
//                    element = sel.getStartElement();
// 
//                _dialog = this;
// 
//                // если не <img> или нет атрибута 'myplugin', то создаем новый элемент
//                if ( !element || element.getName() != 'img' || !element.getAttribute( pluginName ) )
//                {
//                    var attributes = {
//                        // атрибут myplugin нужен для того, чтобы опознать наш элемент
//                        // среди других картинок в редакторе
//                        myplugin: '1',
// 
//                        // в качестве картинки у нас выступает прозрачный пиксель
//                        // внешний вид настраивается в CSS
//                        src : '/ckeditor/images/spacer.gif'
//                    };
// 
//                    element = editor.document.createElement( 'img', { attributes : attributes } );
//                    element.setAttribute( 'class', pluginName );
// 
//                    // вставляем элемент первый раз
//                    _insertMode = true;
//                }
//                else
//                    // редактируем существующий элемент
//                    _insertMode = false;
// 
//                _element = element;
// 
//                // заносим контент из атрибутов в форму
//                this.setupContent( _element );
            }
 
        };
 
    });
})();