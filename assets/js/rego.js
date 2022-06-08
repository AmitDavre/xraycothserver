	
	function checkPassword(password) {
		var number = /([0-9])/;
		var lowers = /([a-z])/;
		var uppers = /([A-Z])/;
		var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
		var msg = '';
		if(password.length < 8) {
			msg = "Password should be atleast 8 characters<br>";
		}
		if(/([0-9])/.test(password) == false) {
			msg += "Password should contain atleast 1 number<br>";
		}
		if(/([A-Z])/.test(password) == false) {
			msg += "Password should contain atleast 1 UPPERCASE character<br>"; 
		}
		if(/([a-z])/.test(password) == false) {
			msg += "Password should contain atleast 1 lowercase character<br>"; 
		}
		if(/([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/.test(password) == false) {
			msg += "Password should contain atleast 1 special character<br>( ~ ! @ # $ % ^ & * - _ + = ? > < )"; 
		}
		return msg;
	}	
	
	Number.prototype.format = function(n, x) {
		 var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
		 return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
	};

	$(document).ready(function() {
			
		$("#hideHistoricData").click(function(){ 
			$.ajax({
				url: ROOT+"ajax/lock_historic_data.php",
				success: function(result){
					location.reload();
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		});
	
		$(document).on('click', 'table.selectable-table tbody tr', function(e){
			$("table.selectable-table tr").removeClass('selected_row');
			$(this).addClass("selected_row");
		});
			
		$(".logout").on('click', function(){ 


			navigator.sayswho= (function(){
			    var N= navigator.appName, ua= navigator.userAgent, tem,
			    M= ua.match(/(opera|chrome|safari|firefox|msie)\/?\s*([\d\.]+)/i);
			    if(M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
			    M= M? [M[1], M[2]]:[N, navigator.appVersion, '-?'];
			    return M.join(' ');
			})();
			
			var bname = navigator.sayswho;
			var ret = bname.split(" ");
			var browsername = ret[0];


			sessionStorage.removeItem('alreadyShow');


			$.ajax({
				url: ROOT+"ajax/logout.php",
				data:{browsername: browsername },
				success: function(result){
					//alert(ROOT+SUBDIR+'/index.php')
					window.location.href = ROOT+'index.php';
				},
				error:function (xhr, ajaxOptions, thrownError){
					//alert(thrownError);
				}
			});
		});

		$(".selectMonth").on('click', function(){ 
			var month = $(this).data('id');
			$.ajax({
				url: ROOT+"ajax/select_month.php",
				data:{month: month },
				success: function(result){
					//alert(result)
					location.reload();
				}
			});
		});
		
		$('.langbutton').on('click', function(){
			$.ajax({
				url: ROOT+"ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(result){
					//alert(result);
					location.reload();
				}
			});
		});
		
		$(".changeYear").on('click', function(){ 
			var year = $(this).data('year');
			$.ajax({
				url: ROOT+"ajax/change_cur_year.php",
				data:{year: year},
				success: function(result){
					//alert(result)
					location.reload();
				}
			});
		});
		
		$('body').on('focus', '.date_year', function() {
			$(this).datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: lang,//lang+'-th',
				startView: 'decade',
				todayHighlight: true,
				//startDate : startYear,
				//endDate   : endYear
			})
		})
		
		$('body').on('focus', '.date_month', function() {
			$(this).datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: lang,//lang+'-th',
				startView: 'year',
				todayHighlight: true,
				//startDate : startYear,
				//endDate   : endYear
			});
		});	
		
		$('body').on('focus', '.date_no_monday', function() {
			$(this).datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: lang,//lang+'-th',
				startView: 'year',
				todayHighlight: true,
				daysOfWeekDisabled: "0,2,3,4,5,6",
				//startDate : startYear,
				//endDate   : endYear
			});
		});	
		
		$('body').on('focus', '.datepick', function() {
			$(this).datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				//inline: true,
				language: lang,
				todayHighlight: true,
				//startDate : startYear,
				//endDate   : endYear
			})
		})		

		$('body').on('focus', '.datepick_ot', function() {
			$(this).datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				//inline: true,
				language: lang,
				todayHighlight: true,
				//startDate : startYear,
				//endDate   : endYear
				orientation: "auto",
			})
		})
		
		$("body").tooltip({ 
			container: 'body',
			selector: '[data-toggle=tooltip]',
			animated: 'fade',
			placement: 'top',
			html: true
		});
		
		$('.neg_numeric').bind('keypress', function (e) { 
			 var code = e.keyCode || e.which;
			 //alert(code)
			 var txt = this.value;
			 if(txt == '0'){txt = ''};
			 if(txt.length > 9){return false;}
			 if(code == 45) {
				  if(txt.indexOf('-') == 0 || txt.length > 0) {
						return false;
				  }else{
						return true;
				  }
			 }else if(code == 46){
				 	//alert((txt.split(".").length - 1))
				  if((txt.split(".").length - 1) == 0) {
						return true;
				  }else{
						return false;
				  }
			 }else{
				  if (code < 48 || code > 57)
						return false;
			 }
			 return true;
		});
		
		$(document).on("focus", "input:text.sel", function(){$(this).select();});
		
		$(document).on("focus", "input:text.float21", function(){
			$(this).numberField(
				{ ints: 2, floats: 1, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float22", function(){
			$(this).numberField(
				{ ints: 2, floats: 2, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float32", function(){
			$(this).numberField(
				{ ints: 3, floats: 2, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float42", function(){
			$(this).numberField(
				{ ints: 4, floats: 2, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float52", function(){
			$(this).numberField(
				{ ints: 5, floats: 2, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float62", function(){
			$(this).numberField(
				{ ints: 6, floats: 2, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float72", function(){
			$(this).numberField(
				{ ints: 7, floats: 2, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float92", function(){
			$(this).numberField(
				{ ints: 9, floats: 2, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float312", function(){
			$(this).numberField(
				{ ints: 3, floats: 12, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.float320", function(){
			$(this).numberField(
				{ ints: 3, floats: 20, separator: "."}			
			);
		});
		$(document).on("focus", "input:text.numeric2", function(){
			$(this).numberField({ ints: 2});
		});
		$(document).on("focus", "input:text.numeric3", function(){
			$(this).numberField({ ints: 3});
		});
		$(document).on("focus", "input:text.numeric4", function(){
			$(this).numberField({ ints: 4});
		});
		$(document).on("focus", "input:text.numeric5", function(){
			$(this).numberField({ ints: 5});
		});
		$(document).on("focus", "input:text.numeric6", function(){
			$(this).numberField({ ints: 6});
		});
		$(document).on("focus", "input:text.numeric", function(){
			$(this).numberField({ ints: 8});
		});
		$(document).on("focus", "input:text.numeric9", function(){
			$(this).numberField({ ints: 9});
		});
		$(document).on("focus", "input:text.numeric13", function(){
			$(this).numberField({ ints: 13});
		});
		$(document).on("focus", "input:text.numeric20", function(){
			$(this).numberField({ ints: 20});
		});
		
		$(".branch5").mask("99999", {placeholder: "*****"});
		$(".branch6").mask("999999", {placeholder: "******"});
		$(".dateinput").mask("99-99-9999", {placeholder: "__/__/____"});
		$(".bankaccount").mask("999-9-99999-9", {placeholder: "***_*_*****_*"});
		$(".tax_id_number").mask("9-9999-99999-99-9", {placeholder: "*_****_*****_**_*"});
		$(".sso_id_number").mask("99-9999999-9", {placeholder: "**_*******_*"});
		$(".hourFormat").mask("99:99", {placeholder: "00:00"});
		
	})