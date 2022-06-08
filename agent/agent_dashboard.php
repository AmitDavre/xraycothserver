
	<div class="header dashboard_header" style="background:#066">
		REHO HR Agents
	</div>			
  
	<div style="position:absolute; top:0; bottom:0; left:0; right:0; padding-top:50px">
	
	<div class="dashboard">
      
		<div style="padding:20px 0; text-align:center; color:#fff; height:100%">
			
			<a href="index.php" class="logout"><i class="fa fa-sign-out"></i></a>
         
			<img class="picture" src="<?=ROOT.'admin/'.$_SESSION['agent']['img']?>?<?=time()?>">
         <p style="padding:10px 0 2px; font-size:20px;"><?=$_SESSION['agent']['name']?></p>
         <p style="padding:0 0 10px;"><?=$_SESSION['agent']['agent_id']?></p>
         
         <div style="padding:0 5px">
            
            <div class="mobbox">
               <div class="inner" onclick="window.location.href='index.php?mn=3'">
                  <i class="fa fa-id-card-o"></i>
                  <p><?=$lng['Personal data']?></p>
               </div>
            </div>
            <div class="mobbox">
               <div class="inner" onclick="window.location.href='index.php?mn=7'">
                  <i class="fa fa-bar-chart"></i>
                  <p><?=$lng['My account']?></p>
               </div>
            </div>
            <div class="mobbox">
               <div class="inner" onclick="window.location.href='index.php?mn=4'">
						<i class="fa fa-handshake-o"></i>
						<p>REGO Free Trial<? //=$lng['Free trial']?></p>
               </div>
            </div>
            <div class="mobbox">
               <div class="inner" onclick="window.location.href='index.php?mn=5'">
                  <i class="fa fa-money"></i>
                  <p>REGO Price table<? //=$lng['Year overview']?></p>
               </div>
            </div>
            <div class="mobbox">
               <div class="inner" onclick="window.location.href='index.php?mn=9'">
                  <i class="fa fa-comments-o"></i>
                  <p><?=$lng['Contact']?></p>
               </div>
            </div>
						<div class="mobbox">
               <div class="inner" onclick="window.location.href='index.php?mn=8'">
                  <i class="fa fa-unlock-alt"></i>
                  <p><?=$lng['Password']?></p>
               </div>
						</div>
				
				<div style="height:10px; clear:both"></div>
            
         </div>
         
      </div>
      
   </div>
	</div>
  














