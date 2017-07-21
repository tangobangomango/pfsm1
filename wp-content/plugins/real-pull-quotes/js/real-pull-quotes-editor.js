(function() {
    tinymce.create('tinymce.plugins.Realpullquotes', {
 
        init : function(ed, url) {
            //IMPORTANT: 'myquotes' needs to match tag in register_button in plugin php file
            ed.addButton('myquotes', { 
                title : 'PullQuote',
                cmd : 'myquotes',
                border : '1 1 1 1',
                text : 'Pullquote',
                tooltip : 'pullquote',
                icon: true,
                image : url + '/pullquote-left.png',
                size : 'small',
            });

            console.log('Here!');
             //IMPORTANT 'myquotes' needs to match tag in addButton above           
            ed.addCommand('myquotes', function() { 
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text = '[realpullquote alignment="" position="" border="" color="" size="" class="" before="" after=""]' + selected_text + '[/realpullquote]';
                console.log('Now here');
                ed.execCommand('mceInsertContent', 0, return_text);
            });
            
        },

    });
 
    // Register plugin
    tinymce.PluginManager.add( 'tdg', tinymce.plugins.Realpullquotes ); //IMPORTANT 'tdg' here needed to match tag in add-button function in plugin php file
})(); 