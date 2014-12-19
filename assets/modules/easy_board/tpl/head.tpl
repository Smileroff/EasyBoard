<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
    <link rel="stylesheet" type="text/css" href="media/style/[+theme+]/style.css" />
	<link rel="stylesheet" type="text/css" href="[+site_url+]assets/modules/easy_board/js/jquery.dataTables.css" />
	<link rel="stylesheet" type="text/css" href="[+site_url+]assets/modules/easy_board/js/jquery.formstyler.css" />
	<script src="[+site_url+]assets/modules/easy_board/js/jquery-1.11.1.min.js"></script>
	<script src="[+site_url+]assets/modules/easy_board/js/jquery.dataTables.min.js"></script>
	<script src="[+site_url+]assets/modules/easy_board/js/jquery.formstyler.min.js"></script>
    </head>
    <body>
     
    <br />
    <div class="sectionHeader">Easy Board - доска объявлений</div>
     
    <div class="sectionBody">
     
    <script language="JavaScript" type="text/javascript">
    function postForm(action, id){
    document.module.action.value=action;
    if (id != null) document.module.item_id.value=id;
    document.module.submit();
    }
	function postFormPag(boardPage){
    document.module.boardPage.value=boardPage;
    document.module.submit();
    }
	$(document).ready(function() {
    $('#tableboard').dataTable( {
        "paging":   false,
		"info":     false,
		"order": [[ 1, "desc" ]],
		"language": {
            "lengthMenu": "_MENU_ строк на страницу",
            "zeroRecords": "Ничего не найдено",
            "info": "Показана страницы _PAGE_ из _PAGES_",
            "infoEmpty": "Нет записей",
			"search": "Быстрая Фильтрация таблицы",
            "infoFiltered": "(filtered from _MAX_ total records)"
			}
		} );
	} );
	
  
   function ItemAjax(act, id, elementID){
      $("#"+elementID+""+id).load("/assets/modules/easy_board/easy_board.ajax.php","act="+act+"&item_id="+id);
   }
	
    </script>
	<script>  
(function($) {  
$(function() {  
  
  $('input, select, textarea, file').styler();  
  
})  
})(jQuery)  
</script> 
     
    <form name="module" method="post" enctype="multipart/form-data">
    <input name="action" type="hidden" value="" />
    <input name="item_id" type="hidden" value="" />
	<input name="boardPage" type="hidden" value="" />