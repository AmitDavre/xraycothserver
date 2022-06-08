<style type="text/css">
	
	.btn .badge {
     position: relative; 
     top: 0px; 
}


.blink {
      animation: blink 2s steps(5, start) infinite;
      -webkit-animation: blink 1s steps(5, start) infinite;
    }
    @keyframes blink {
      to {
        visibility: hidden;
      }
    }
    @-webkit-keyframes blink {
      to {
        visibility: hidden;
      }
    }


</style>
<div class="container-fluid" style="padding:0">
	<div class="accordion" id="accordionExample1">

		<div class="item">
			<div class="accordion-header">
				<button class="btn" type="button" data-toggle="collapse" data-target="#Communicationcenter">
					<?=$lng['Communication center']?>  &nbsp; 
					<!-- <span class='badge badge-warning' id='lblCartCount'> <?php echo $count_request; ?> </span> -->
				</button>
			</div>
			<div id="Communicationcenter" class="accordion-body show" data-parent="#accordionExample1">
				<div class="accordion-content" style="padding:0">
					<input type="hidden" name="otID_hidden" id="otID_hidden" value="">
					<table class="accordion-table bordered">
						<tbody>
							
							<?= checkAnnouncementForMob($_SESSION['rego']['emp_id']); ?>
								
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>