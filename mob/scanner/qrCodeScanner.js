
$(document).ready(function() {
	//const qrcode = window.qrcode;
	
	const video = document.createElement("video");
	const canvasElement = document.getElementById("qr-canvas");
	const canvas = canvasElement.getContext("2d");
	
	const qrResult = document.getElementById("qr-result");
	const outputData = document.getElementById("outputData");
	const btnScanQR = document.getElementById("btn-scan-qr");
	
	let scanning = false;
	
	qrcode.callback = res => {
		if (res) {

			var datetime = new Date().today() + " @ " + new Date().timeNow();
			//outputData.innerText = res;
			outputData.innerText = 'en_name' + '\n' + datetime;
			scanning = false;
	
			video.srcObject.getTracks().forEach(track => {
				track.stop();
			});
	
			qrResult.hidden = false;
			canvasElement.hidden = true;
			btnScanQR.hidden = false;
			// After scan //
			//alert('scanned')
			$.ajax({
				url: "ajax/clock_in_out.php",
				type: "POST", 
				data: {id: id, col: col, val: val},
				success: function(response){
					//$('#dump').html(response);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
			
			
		}
	};
	
	btnScanQR.onclick = () => {
		navigator.mediaDevices
			.getUserMedia({ video: { facingMode: "environment" } })
			.then(function(stream) {
				scanning = true;
				qrResult.hidden = true;
				btnScanQR.hidden = true;
				canvasElement.hidden = false;
				video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
				video.srcObject = stream;
				video.play();
				tick();
				scan();
			});
	};
	
	function tick() {
		canvasElement.height = video.videoHeight;
		canvasElement.width = video.videoWidth;
		canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
	
		scanning && requestAnimationFrame(tick);
	}
	
	function scan() {
		try {
			qrcode.decode();
		} catch (e) {
			setTimeout(scan, 300);
		}
	}

})