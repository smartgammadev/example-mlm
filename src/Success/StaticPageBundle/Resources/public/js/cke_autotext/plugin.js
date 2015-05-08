(function() { 
    CKEDITOR.plugins.add( 'cke_autotext',
    {
        init: function( editor )
        {
            var pluginName = 'cke_autotext';
            // регистрируем диалоговое окно
            CKEDITOR.dialog.add( pluginName, this.path + 'dialogs/' + pluginName + '.js' );
 
            // связываем диалоговое окно с командой pluginName
            // команда pluginName отдается при нажатии иконки на тулбаре
            editor.addCommand( pluginName, new CKEDITOR.dialogCommand( pluginName ) );
 
            // добавляем css для иконки в редакторе
            var basicCss =
                'background:url(' + CKEDITOR.getUrl( this.path + 'images/editor_icon.gif' ) + ') no-repeat left center;' +
                'border:1px dotted #aaa;'+
                'display: inline-block;';
 
            CKEDITOR.addCss(
                '.cke_button_cke_autotext .cke_label{display:inline;}'+
                'img.' + pluginName +
                '{' +
                    basicCss +
                    'width:32px;' +
                    'min-height:31px;' +
                    // The default line-height on IE.
                    'height:1.15em;' +
                    // Opera works better with "middle" (even if not perfect)
                    'vertical-align:' + ( CKEDITOR.env.opera ? 'middle' : 'text-bottom' ) + ';' +
                    'border-collapse: collapse;' +
                '};');
 
            // обрабатываем двойной клик в редакторе
            editor.on( 'doubleclick', function( evt )
{
var element = evt.data.element;
 
// если <img> с атрибутом как название плагина, то откроем диалоговое окно
if ( element.is( 'img' ) && element.getAttribute( pluginName ) )
evt.data.dialog = pluginName;
} );
 
            // добавляем кнопку на тулбар
            if(editor.ui.addButton)
            {
                editor.ui.addButton( 'cke_autotext',
                {
                    label: 'cke_autotext',
                    command: pluginName,
                    icon: this.path + 'images/panel_icon.gif'
                } );
            }
 
        }
 
    });
 
})();