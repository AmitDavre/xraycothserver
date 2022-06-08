<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Summernote with Bootstrap 4</title>
    
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
  	<link rel="stylesheet" href="../assets/plugins/summernote/dist/summernote-bs4.css?<?=time()?>">
  	
		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>
		<script src="../assets/js/popper.min.js"></script>
		<script src="../assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../assets/plugins/summernote/dist/summernote-bs4.js?<?=time()?>"></script>
		<script type="text/javascript" src="../assets/plugins/summernote/dist/summernote-cleaner.js?<?=time()?>"></script>

  </head>
	<style>
	div.note-statusbar {
		display:none;
	}
	</style>
	<body style="background:#eee">
		
		<div style="width:1000px; margin:0 auto; margin-top:50px">
			<div id="summernote"></div>
		</div>

		<script>
      $('#summernote').summernote({
        placeholder: 'Hello Lite',
        tabsize: 2,
        height: 500,
				disableResizeEditor: true,
				disableDragAndDrop: true,
				toolbar: [
					['style', ['style', 'bold', 'italic', 'underline', 'cleaner']],
					['fontsize', ['fontsize']],
					['fontname', ['fontname']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['insert', ['link', 'picture']],
					['misc', ['undo', 'redo']],
					['view', ['fullscreen', 'codeview']],
				],
				cleaner:{
          action: 'both', // both|button|paste.
          newline: '<br>', // Summernote's default is to use '<p><br></p>'
          notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
          icon: '<i class="fa fa-file-code-o"></i>',
          keepHtml: false, // Remove all Html formats
          keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
          keepClasses: false, // Remove Classes
          badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
          badAttributes: ['style', 'start'], // Remove attributes from remaining tags
          limitChars: false, // 0/false|# 0/false disables option
          limitDisplay: 'both', // text|html|both
          limitStop: false // true/false
    		}
      });
    </script>
  </body>
</html>