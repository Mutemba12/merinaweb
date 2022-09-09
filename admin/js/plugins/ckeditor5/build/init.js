$(function(){
    'use strict';
    if($('textarea[data-editor="1"]').length){
        ClassicEditor.create(document.querySelector('textarea[data-editor="1"]'), {
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    'subscript',
                    'superscript',
                    'fontColor',
                    'removeFormat',
                    '|',
                    'alignment',
                    'blockQuote',
                    'insertTable',
                    'horizontalLine',
                    '|',
                    'undo',
                    'redo',
                    'findAndReplace',
                    '|',
                    'bulletedList',
                    'numberedList',
                    'outdent',
                    'indent',
                    '|',
                    'link',
                    'mediaEmbed',
                    'code',
                    '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },
            language: 'en',
            image: {
                toolbar: [
                    'imageTextAlternative',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side'
                ]
            },
            link: {
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    }
                }
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells',
                    'tableCellProperties',
                    'tableProperties'
                ]
            },
                licenseKey: '',
            })
            .then(editor => {
                window.editor = editor; 
            })
            .catch(error => {
                console.error('Oops, something went wrong!');
                console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                console.warn('Build id: t9s61whklazp-ya016jhffhfy');
                console.error(error);
            }
       );
   }
});
