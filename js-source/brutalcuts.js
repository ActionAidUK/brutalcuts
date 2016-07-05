String.prototype.capitalize = function(lower) {
    return (lower ? this.toLowerCase() : this).replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
};

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;

var aauk = {};

aauk.isTouchDevice = false;
aauk.imagesLoaded = false;


(function( $ ) {
	if (typeof aauk.readQueryString === "undefined") { aauk.readQueryString = {
	 	

	 	init: function() {
			//Reads query string in to object...

			var hash;
			var q = document.URL.split('?')[1];
			var qs = {};
	
			if(q !== undefined)
			{
				q = q.split('&');

				for(var i = 0; i < q.length; i++)
				{
					hash = q[i].split('=');

					qs[hash[0]] = hash[1];
				}
			}
			return qs;
		}
	};}
	
	
	if (typeof aauk.isTouchDeviceDetect === "undefined") {  aauk.isTouchDeviceDetect = function() {
				
		var el = document.createElement('div');
		el.setAttribute('ongesturestart', 'return;'); // or try "ontouchstart"
		if (typeof el.ongesturestart === "function")
		{
			this.isTouchDevice = true;	
		}		
	};}
	
	
	
	if (typeof aauk.hasGetUserMedia === "undefined") {  aauk.hasGetUserMedia = function() {
				
		return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia || navigator.msGetUserMedia);
			
	};}
	
	
	
	
	if (typeof aauk.videoRecording === "undefined") { aauk.videoRecording = {
		 
		theVideo : {},
	 	
	 	startButton : {},
	 	stopButton : {},
	 	theStream : {},
	 	mediaSource : {},
	 	mediaRecorder : {},
	 	
	 	superBuffer : '',
	 	
	 	recordingIcon : {},
	 	
	 	recordedBlobs : [],
	 	superBuffer : {},
	 	sourceBuffer : {},
	 	camvideoWrapper : {},
	 	constraints : {},
	 	isSecureOrigin : '',
	 
	 	init: function() {
			
			var _this = this;
			
		
			$("#getMediaCapture").prepend('<fieldset id="camvideoWrapper"><div id="recording"></div><div align="center" class="embed-responsive embed-responsive-16by9" ><video id="camvideo" autoplay muted></video><video id="camplayer" controls></video></div><input type="button" class="recording-button start" id="startRecording" value="Start recording"><input type="button"  class="recording-button stop" id="stopRecording" value="Stop recording" disabled></fieldset>').show();
		
					
			_this.constraints = {
				audio: true,
				video:  {
			        aspectRatio: 1.7777777778,
			        width: { min: 640, ideal: 640 },
					height: { min: 360, ideal: 360 }		    
			    }
			};
			
			_this.camvideoWrapper = $("#camvideoWrapper");
			_this.startButton = $("#startRecording");
			_this.stopButton = $("#stopRecording");
			_this.recordingIcon = $("#recording");

			_this.theVideo = document.getElementById('camvideo');
			_this.thePlayer = document.getElementById('camplayer');
			_this.mediaSource = new MediaSource();
	
			
			_this.mediaSource.addEventListener('sourceopen', _this.handleSourceOpen, false);
			
			_this.isSecureOrigin = location.protocol === 'https:' || location.host === 'localhost';
			
			if (!_this.isSecureOrigin) {
				alert('getUserMedia() must be run from a secure origin: HTTPS or localhost.' +
				'\n\nChanging protocol to HTTPS');
				location.protocol = 'HTTPS';
			}
			
			navigator.mediaDevices.getUserMedia(
					_this.constraints).then(function(stream){
					_this.handleSuccess(stream);
			}).catch(function(error){
				_this.handleError(error);
			});
			
			
			_this.startButton.on('click',function(){
					
				_this.startRecording();
					
			});
				
			
			_this.stopButton.on('click',function(){
					
				_this.stopRecording();
					
			});

			
		},
		
		
		handleSuccess : function(stream) {
			
				
			var _this = this;
			
			$("#videoUploadForm").hide();
			
			$("#getMediaCapture").show();
			
			$("#saveVideo").on("click",function(event){
				_this.saveVideo();	
			});


			console.log('getUserMedia() got stream: ', stream );
			window.stream = stream ;
			
			
			if (window.URL) {
			    _this.theVideo.src = window.URL.createObjectURL(stream);
			} else {
			    _this.theVideo.src = stream;
			}
		
		},
		
		
		handleError : function(error) {
			
			console.log(error);
			$("#getMediaCapture").remove();
			aauk.videoUploader.init();
		},
		
		
		startRecording : function() {
			var _this = this;
			
			$("#submitButton").prop('disabled',true);
			
			if (window.URL) {
			    _this.theVideo.src = window.URL.createObjectURL(stream);
			} else {
			    _this.theVideo.src = stream;
			}
			
			_this.recordingIcon.show();			
			_this.startButton.prop("disabled",true);
			_this.stopButton.prop("disabled",false);
 			_this.thePlayer.style.display = 'none';
			_this.theVideo.style.display = 'block';
			
			
			  var options = {mimeType: 'video/webm'};
			  _this.recordedBlobs = [];
			  try {
			    _this.mediaRecorder = new MediaRecorder(window.stream, options);
			  } catch (e0) {
			    console.log('Unable to create MediaRecorder with options Object: ', e0);
			    try {
			      options = {mimeType: 'video/webm,codecs=vp9'};
			      _this.mediaRecorder = new MediaRecorder(window.stream, options);
			    } catch (e1) {
			      console.log('Unable to create MediaRecorder with options Object: ', e1);
			      try {
			        options = 'video/vp8'; // Chrome 47
			        _this.mediaRecorder = new MediaRecorder(window.stream, options);
			      } catch (e2) {
			        alert('MediaRecorder is not supported by this browser.\n\n' +
			            'Try Firefox 29 or later, or Chrome 47 or later, with Enable experimental Web Platform features enabled from chrome://flags.');
			        console.error('Exception while creating MediaRecorder:', e2);
			        return;
			      }
			    }
			  }
			  console.log('Created MediaRecorder', _this.mediaRecorder, 'with options', options);

			  _this.mediaRecorder.onstop = _this.handleStop;
			  _this.mediaRecorder.ondataavailable = function(event) { _this.handleDataAvailable(event) };
			  _this.mediaRecorder.start(10); // collect 10ms of data
			  console.log('MediaRecorder started', _this.mediaRecorder);
						
			
		},
		
		stopRecording : function() {
			var _this = this;
			
			_this.thePlayer.style.display = 'block';
			_this.theVideo.style.display = 'none';
			
			_this.recordingIcon.hide();
			_this.startButton.prop("disabled",false);
			_this.stopButton.prop("disabled",true);
		
			_this.theVideo.pause();
			
			_this.mediaRecorder.stop();
			console.log('Recorded Blobs: ', _this.recordedBlobs);
			
			_this.thePlayer.controls = true;
			
			$("#submitButton").prop('disabled',false);
			_this.playVideo();
		},
		
		saveVideo : function() {
			var _this = this;
			var blob = new Blob(_this.recordedBlobs, {type: 'video/webm'});
			var url = window.URL.createObjectURL(blob);
			var d = new Date();
			var n = d.getTime();			
			
			
			var fd = new FormData();
			
			
			fd.append('video-filename', 'video-' + n + '.webm');
			fd.append('video-blob', blob);
			
			$("#submit-wrap").hide();
			$("#spinner").show();

			$.ajax({
		        url: '/ajax-upload.php',
		        data: fd,
		        processData: false,
		        method: 'POST',
		        contentType : false,
		        dataType : 'json',
		        success: function (json) {
			        
			        
			        aauk.videoUploader.displayVideo(json);
			        			        
		        }
		    });
			
			
			
		},
		
		handleDataAvailable : function (event) {
			var _this = this;	
			if (event.data && event.data.size > 0) {
		    	_this.recordedBlobs.push(event.data);
		  	}
		},
		
		playVideo : function() {
			var _this = this;	
			_this.superBuffer = new Blob(_this.recordedBlobs, {type: 'video/webm'});
			_this.onPlay();
		},
		
		onPlay : function() {
			var _this = this;
			_this.thePlayer.src = window.URL.createObjectURL(_this.superBuffer);
		},
		
		handleSourceOpen : function (event) {
			var _this = this;
			console.log('MediaSource opened');
			_this.sourceBuffer = mediaSource.addSourceBuffer('video/webm; codecs="vp8"');
			console.log('Source buffer: ', _this.sourceBuffer);
		},
		
		handleStop : function (event) {
			console.log('Recorder stopped: ', event);
		},

		
				
	};}
	
	
	if (typeof aauk.bytesToSize === "undefined") { aauk.bytesToSize = function(bytes, precision) {
		
		var kilobyte = 1024;
	    var megabyte = kilobyte * 1024;
	    var gigabyte = megabyte * 1024;
	    var terabyte = gigabyte * 1024;
	   
	    if ((bytes >= 0) && (bytes < kilobyte)) {
	        return bytes + ' B';
	 
	    } else if ((bytes >= kilobyte) && (bytes < megabyte)) {
	        return (bytes / kilobyte).toFixed(precision) + ' KB';
	 
	    } else if ((bytes >= megabyte) && (bytes < gigabyte)) {
	        return (bytes / megabyte).toFixed(precision) + ' MB';
	 
	    } else if ((bytes >= gigabyte) && (bytes < terabyte)) {
	        return (bytes / gigabyte).toFixed(precision) + ' GB';
	 
	    } else if (bytes >= terabyte) {
	        return (bytes / terabyte).toFixed(precision) + ' TB';
	 
	    } else {
	        return bytes + ' B';
	    }
		
	};}
	
	
	if (typeof aauk.videoUploader === "undefined") { aauk.videoUploader = {
		
		fileInput : {},
		fileInfo : {},
		theForm : {},
		
		init : function () {
			
			var _this = this;
			_this.fileInput = $("#uploadFile");
			_this.theForm = $("#videoUploadForm");
		
			_this.theForm.on("submit", function(e) {

				e.preventDefault();
				_this.onSubmit();
				
			});
			
			$("#uploadFile").on("change",function(){
				_this.validateFile();
			})
			
		},
		
		onSubmit : function () {
			
			var _this = this;

			var validation = _this.validateFile();
			
			
			
			if (validation.length < 1)
			{
				var fd = new FormData(_this.theForm[0]);
				
				$("#submit-wrap-upload").hide();
				$("#spinner-upload").show();
						
				
				$.ajax({
			        url: '/ajax-upload.php',
			        data: fd,
			        processData: false,
			        method: 'POST',
			        contentType : false,
			        dataType : 'json',
			        success: function (json) {
						
						_this.displayVideo(json);				        
				        
			        }
			    });
				
				
			}
			
			
		},
		
		displayVideo : function (json)
		{
			
			console.log(json);
			$("#submit-wrap-upload").show();
			$("#spinner-upload").hide();
			$("#getMediaCapture").hide();			
			$("#videoUploadForm").hide();
			        
			$("#finalVideo").html('<div align="center" class="embed-responsive embed-responsive-16by9" ><video id="brutalCut"  autoplay poster="' + json.poster + '" controls class="embed-responsive-item"><source src="' + json.url + '" type="video/mp4">Your browser does not support the video tag.</video></div><p><br/><br/><a download target="_blank" class="button recording-button download-button" href="' + json.url + '">Download</a><a target="_blank" id="shareToTwitter" class="button recording-button twitter-button shareButton" href="twitter.php?vid=' + json.id + '" data-sharetype="TShareOauth" data-videoID="' + json.id + '" target="_blank" data-sharevideo="' + json.url + '">Share to twitter</a></p>');
				        
			$("#finalVideo").show();
			
			
			$("#filesize").html(json.command);
			
		},
		
		validateFile : function() {
			
			var _this = this;
			
			$("#error-box").slideUp(function(){
				$(this).remove();
			})
			
			var errors = Array();
			
			try {
				
				_this.fileInfo = _this.fileInput[0].files[0];
				
				//Check we acually have a file...
				if ((!_this.fileInfo) || (_this.fileInfo.length < 1))
				{
					errors.push("You haven’t selected a file");
					return errors;
				}
				
				//check the file type...
				var theType = _this.fileInfo.type;
				var matches = theType.match(/^video.+|image.+/);
				
				if (!matches)
				{
					errors.push("This doesn’t look like a video or an image! Please upload a video or an image.");
				}
				
				//check size
				if (_this.fileInfo.size > 16777216)
				{
					errors.push("This file is too big! Please upload a shorter video...");
				}
					
				$("#filesize").html("File size: " + aauk.bytesToSize(_this.fileInfo.size,2));
				
			} catch (err) {
				
				console.log(err);
				errors.push("Can't check file type");
			}
			
			if (errors.length > 0)
			{
				
				_this.theForm.prepend('<div id="error-box" style="display:none;" class="error-box"><ul></ul></div>');

				for (x in errors)
				{
					$('#error-box > ul').append('<li>' + errors[x] + '</li>');
				}
				
				$('#error-box').slideDown();
				
			}
			
			
			return errors;
			
		}
		
	};}
	
	
	if (typeof aauk.videoShare === "undefined"){
		
		aauk.videoShare = {
			
			shareFacebook: function(shareDetails){
											
				return true;
			},
			
						
			shareTwitterOauth: function(shareDetails){
				
				var _this = this;
				
				
				var url = $('').attr('data-sharevideo'),
					file = _this.dataURItoBlob(url);
				
				console.log(file);

				
				// Dev site
				//OAuth.initialize("xOoZR8QNYTQH31iAKdumCt8-rv0");
				
				// Live site
				OAuth.initialize("v3zCfC1Nl5YnPQ81pVTba7wssCE");
					
				OAuth.popup("twitter").then(function(result) {
					var data = new FormData();
					// Tweet text
					data.append('status', "Test tweet ");
					
					// Binary image
					data.append('media[]', file, 'safe-cities.jpg');
					
					// Post to Twitter as an update with media
					return result.post('/1.1/statuses/update_with_media.json', {
					  data: data,
					  cache: false,
					  processData: false,
					  contentType: false
					});
					
				// Success/Error Logging
				}).done(function(data){
					var str = JSON.stringify(data, null, 2);
					
					//$('#result').html("Success\n" + str).show()
				}).fail(function(e){
					//var errorTxt = JSON.stringify(e, null, 2)
					//$('#result').html("Error\n" + errorTxt).show()
				});	
				return true;
			},
			
			uploadAndTweet : function() {
				
				
				var sendingNotification = $("#tweetSending");
				sendingNotification.show();
				
				var fd = new FormData($("#tweeter")[0]);
				
				
				$.ajax({
			        url: '/sendtweet.php',
			        data: fd,
			        processData: false,
			        method: 'POST',
			        contentType : false,
			        dataType : 'json',
			        success: function (json) {
						
						console.log(json);	
						sendingNotification.hide();	
						
						$("#tweeter").slideUp().after('<p>Thank you for tweeting your Brutal cut!</p><p><a class="button recording-button stop-button">Make a donation</a></p>');
						
								        
			        }
			    });

				
				
			},
			
					
			init : function () {
				var _this = this,
				shareType = '';
				
							
				$('#finalVideo').on('click', '.shareButton', function(e){
					
					e.preventDefault();
					
					shareType = $(this).attr('data-sharetype');
					
					
					switch(shareType) {
						case 'facebookShare' :
							
							if(_this.shareFacebook($(this)) == true){
								
							}
						
							break;
							
						case 'TShareOauth':
						
							window.open("twitter.php?vid=" + $(this).attr('data-videoID'));	
						
							break;
					}
				});
				
				$("#tweetVideo").on("click",function(e){
					
					e.preventDefault();
					
					_this.uploadAndTweet();
					
				});
				

				
			}
		}
		
	};


})(jQuery);



jQuery(document).ready(function($) {
	
	
	aauk.isTouchDeviceDetect();

	
	//	if (aauk.hasGetUserMedia()){
	//		aauk.videoRecording.init();
			
			
	//	} else {
			 aauk.videoUploader.init();
	//	}

		aauk.videoShare.init();
});




































