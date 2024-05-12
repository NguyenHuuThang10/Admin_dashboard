/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 * Tích hợp và hướng dẫn bởi https://trungtrinh.com - Website chia sẻ bách khoa toàn thư */

CKEDITOR.editorConfig = function( config ) {
    config.filebrowserBrowseUrl = '/PHP/Module_06/radix/templaces/admin/assets/ckeditor/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = '/PHP/Module_06/radix/templaces/admin/assets/ckeditor/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = '/PHP/Module_06/radix/templaces/admin/assets/ckeditor/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = '/PHP/Module_06/radix/templaces/admin/assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = '/PHP/Module_06/radix/templaces/admin/assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = '/PHP/Module_06/radix/templaces/admin/assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};
