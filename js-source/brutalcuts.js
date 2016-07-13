String.prototype.capitalize = function(lower) {
    return (lower ? this.toLowerCase() : this).replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
};

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;

var aauk = {};

aauk.isTouchDevice = false;
aauk.imagesLoaded = false;


(function( $ ) {
	

	if (typeof aauk.checkFileAPI === "undefined") { aauk.checkFileAPI = function() {
			
			if (window.File && window.FileList && window.FileReader) {
				return true;
			} else {
				return false;
			}
			
	};}
			
	
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
			return true;
		} else {
			return false;
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
		theDropZone : {},
		theDropZoneTrigger : {},
		theSubmitButton : {},
		dropzoneText : {},
		dropzoneResults : {},
		updateBar : {},
		uploadStatus : {},
		uploadDots : {},
		uploadDotCount : 1,
		
		init : function () {
			
			var _this = this;
			var dotString = '';
			var x = 0;
			_this.fileInput = $("#uploadFile");
			_this.theForm = $("#videoUploadForm");
			_this.theDropZone = $("#dropzone");
			_this.theSubmitButton = $("#submitButton");
			_this.theDropZoneTrigger = $("#dropzoneTrigger");
			_this.dropzoneText = $("#dropzone-text");
			_this.dropzoneResults = $("#dropzone-results");
			
			
				
			_this.theDropZoneTrigger.dropzone({ 
				url: "/ajax-upload.php",
				maxFilesize: 64,
				paramName: 'video-blob',
				acceptedFiles: 'image/*,video/*',
				
				fallback : function() {
				
					$("#dropzoneTrigger").remove();
					$("#bcInstructions").text("To get started, add an image or video here.");
					
				},
				
				uploadprogress: function(file, progress, bytesSent) {

					dotString = '';
					
					
				    _this.updateBar.width(progress + '%');
				    
				   if (progress >= 100)
				   {
					   console.log('complete');
					   _this.uploadStatus.text("Processing");
					   
				   }
				    
				 },
				 
				 complete: function(file) {

					
				   
				    
				 },
				
				previewsContainer : "#dropzone-results",
				
				
				init : function() {
					_this.fileInput.hide();
					_this.theSubmitButton.hide();
					_this.theDropZone.addClass('active');
					_this.theDropZoneTrigger.css('z-index',100);
					_this.dropzoneResults.hide();
					
					
					
					if (aauk.isTouchDeviceDetect())
					{
						$("#bcInstructions").text('To get started, click here to upload an image or video.');
					}
					
					
					this.on("addedfile", function(file) { 
						
						_this.updateBar = $(".dz-upload");
						
						$('.dz-progress').append('<div id="dz-upload-status" class="dz-upload-status"><span id="upload-stage">Uploading</span><span id="upload-dots">...</div></div>');
						
						_this.uploadStatus = $("#upload-stage");
						_this.uploadDots = $("#upload-dots");
						_this.dropzoneText.fadeOut('fast',function(){
							
							_this.dropzoneResults.fadeIn();
						});	
						
						_this.theDropZone.removeClass('active');
						
					});
					
					this.on("removedfile", function(file) { 
						
						_this.dropzoneResults.fadeOut(function(){
							
							_this.dropzoneText.fadeIn();
						})	
						
						_this.theDropZone.addClass('active');
					});
					

					
					
					this.on("success", function(file,response) { 
						
						$("#uploadRow").fadeOut(function() {
							_this.displayVideo(response);
						});
						
						
					});
					
				}
				
			});

			_this.theForm.on("submit", function(e) {

				_this.onSubmit(e);
				
			});
			
			$("#uploadFile").on("change",function(){
				if( typeof( window.FormData ) !== 'undefined' ) {
				_this.validateFile();
				}
			})
			
		},
		
		onSubmit : function (e) {
			
			var _this = this;

			
			
			if( typeof( window.FormData ) !== 'undefined' ) {
				
				var validation = _this.validateFile();
			
				e.preventDefault();
	
				
				if (validation.length < 1)
				{
					var fd = new FormData(_this.theForm[0]);
					
					$("#spinner-upload").show();
							
					
					$.ajax({
				        url: '/ajax-upload.php',
				        data: fd,
				        processData: false,
				        method: 'POST',
				        contentType : false,
				        dataType : 'json',
				        success: function (json) {
							
							$("#uploadRow").fadeOut(function() {
								_this.displayVideo(json);
							});				        
					        
				        }
				    });
					
					
				}
				
			}
			
		},
		
		displayVideo : function (json)
		{
			var wrapperClass;
			console.log(json);
			$("#submit-wrap-upload").show();
			$("#spinner-upload").hide();
			$("#getMediaCapture").hide();			
			$("#videoUploadForm").hide();
			
			if (json.width == '640')
			{
				wrapperClass = 'embed-responsive-16by9';
			} else {
				wrapperClass = 'embed-responsive-square';
			}
			        
			parent.postMessage({method : 'scroll'},"https://www.actionaid.org.uk");
			        
			$("#finalVideo").html('<div class="align-wrapper ' + wrapperClass + '"><div align="center" class="embed-responsive ' + wrapperClass + '" ><video id="brutalCut"  autoplay poster="' + json.poster + '" controls class="embed-responsive-item"><source src="' + json.url + '" type="video/mp4">Your browser does not support the video tag.</video></div></div>');
			$("#shareContainer").html('<p><a download target="_blank" class="red-box-button inline-button file-download-button" href="' + json.url + '"><span class="download-icon"></span>Download</a><a target="_blank" id="shareToTwitter" class="red-box-button twitter inline-button" href="twitter.php?vid=' + json.id + '" data-sharetype="TShareOauth" data-videoid="' + json.id + '" target="_blank" data-sharevideo="' + json.url + '"><span class="social-logo"></span>Share to twitter</a><a target="_blank" id="shareToFacebook" class="red-box-button facebook inline-button" href="facebook.php?vid=' + json.id + '" data-sharetype="facebookShare" data-videoid="' + json.id + '" target="_blank" data-sharevideo="' + json.url + '"><span class="social-logo"></span>Share to Facebook</a></p>');
			//Twitter caon only handle <15Mb and < 30secs...
			if ((Number(json.duration) > 29.99) || (Number(json.size) > 15728640))
			{	
				$("#shareToTwitter").hide();
				
				$('.shareWrapper').addClass('no-twitter');
				
			}
			
				        
			$("#finalVideo").fadeIn();
			
		
			
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
				if (_this.fileInfo.size > 64777216)
				{
					errors.push("This file is too big! Please upload a shorter video...");
				}
					
				$("#filesize").html("File size: " + aauk.bytesToSize(_this.fileInfo.size,2));
				
			} catch (err) {
				
				console.log(err);
			//	errors.push("Can't check file type");
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
			
		},
		
		loadNonAjax : function() {
			
			var _this = this;
			
			if (typeof uploadResponse != "undefined") 
			{	
				$("#uploadRow").hide();
				_this.displayVideo(uploadResponse);

			}
			
		},
		
		displayError : function() {
			
			var _this = this;
			
			if (typeof uploadError != "undefined") 
			{	
				_this.theForm.prepend('<div id="error-box" style="display:none;" class="error-box"><ul><li>' + uploadError + '</li></ul></div>');

								
				$('#error-box').slideDown();
				
			}
			
		}
		
	};}
	
	
		
	
	if (typeof aauk.videoShare === "undefined"){
		
		aauk.videoShare = {
			
			vid : '',
			charCount : 115,
			counterText : {},
			
						
						
			facebookLogin: function(){
				
				var _this = this;

				FB.login(function(response) {
				
				
			   	 if (response.authResponse) {
			        alert("Auth");
			         _this.shareToFacebook();
			       
			        
			      } else {
			        alert('User cancelled login or did not fully authorize.');
			        return false;
			      }
			    }, {scope: 'publish_actions'});
			    
			},


			facebookShare: function(){
				
				
				var _this = this;

				FB.getLoginStatus(function(response) {
				  if (response.status === 'connected') {
				    
				    _this.shareToFacebook();
				    
				  }
				  else {
				    _this.facebookLogin();
				  }
				});
			},
			
			
			shareToFacebook : function() {
					
					var _this = this;
					
					
			        $.ajax({
				        url: '/facebook.php?vid=' + _this.vid,
				        processData: false,
				        method: 'GET',
				        contentType : false,
				        dataType : 'json',
				        success: function (json) {
							
							console.log(json);	
									        
				        }
				    });
				    
				    		
				
				
			},
					
			
			
			uploadAndTweet : function() {
				
				
				var sendingNotification = $("#tweetSending");
				sendingNotification.show();
				
				
				if( typeof( window.FormData ) !== 'undefined' ) {
				
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
							
							$("h1").text('Thank you for tweeting your Brutal cut!');
							
							$("#tweeter").slideUp();
							$("#caseForSupport").slideDown();
							
									        
				        }
				    });
			    
			    } else {
				    
				    $.ajax({
			        url: '/sendtweet.php?vid=' + $('#vid').val() + '&tweetText=' + encodeURI($('#tweetText').val()),
			        processData: false,
			        method: 'GET',
			        contentType : false,
			        dataType : 'json',
			        success: function (json) {
						
						console.log(json);	
						sendingNotification.hide();	
						
						$("h1").text('Thank you for tweeting your Brutal cut!');
						
						$("#tweeter").slideUp();
						$("#caseForSupport").slideDown();
				
								        
			        }
			    });
				    
			    }

				
				
			},
			
			
			
			uploadAndFB : function() {
				
				
				var sendingNotification = $("#facebookSending");
				sendingNotification.show();
				
				
				if( typeof( window.FormData ) !== 'undefined' ) {
					
					var fd = new FormData($("#facebooker")[0]);
					
					
					$.ajax({
				        url: '/post-to-facebook.php',
				        data: fd,
				        processData: false,
				        method: 'POST',
				        contentType : false,
				        dataType : 'json',
				        success: function (json) {
							
							console.log(json);	
							sendingNotification.hide();	
													
							$("h1").text('Thank you for sharing your Brutal cut!');
							$("#facebooker").slideUp();
							$("#caseForSupport").slideDown();
									        
				        }
				    });
				
				} else {
					
					
					$.ajax({
			        url: '/post-to-facebook.php?vid=' + $('#vid').val() + '&facebookText=' + encodeURI($('#facebookText').val()),
			        processData: false,
			        method: 'GET',
			        contentType : false,
			        dataType : 'json',
			        success: function (json) {
						
						console.log(json);	
						sendingNotification.hide();	
						
						$("h1").text('Thank you for sharing your Brutal cut!');
						
						$("#facebooker").slideUp();
						$("#caseForSupport").slideDown();
				
								        
			        }
			    });

				}

				
				
			},
			
			
			bindCounter : function() {
			
				var _this = this, charsAvailable, charCount;
				
				_this.charCount = $(".shareText").data('limit');
				_this.counterText = $("#charCount");
			
				$(".shareText").on('keyup paste drop',function(){
					
					charsAvailable = _this.charCount - $(this).val().length;
					_this.counterText.text(charsAvailable);
					
					if (charsAvailable <= 0)
					{
						_this.counterText.addClass('error');

					} else if($(this).val().length < 1) {
						
						$("#noTextError").slideDown();
					
					} else {
						
						$("#noTextError").slideUp();
						_this.counterText.removeClass('error');
						
					}
					
					
				});
				
				$(".shareText").keyup();
				
				
			},
			
			
					
			init : function () {
				var _this = this,
				shareType = '';
				
							
				$('#finalVideo').on('click', '.shareButton', function(e){
					
					e.preventDefault();
					
					
					_this.vid = $(this).attr("data-videoid");
					shareType = $(this).attr('data-sharetype');
					
					
					switch(shareType) {
						case 'facebookShare' :
							
							window.open("facebook.php?vid=" + $(this).attr('data-videoid'),'_blank', 'width=600,height=800');						
							
							break;
							
						case 'TShareOauth':
						
							window.open("twitter.php?vid=" + $(this).attr('data-videoid'),'_blank', 'width=600,height=800');	
						
							break;
					}
				});
				
				$("#tweetVideo").on("click",function(e){
					
					e.preventDefault();
					
					charCount = _this.charCount - $(".shareText").val().length;
					
					if (charCount < 0)
					{
						
						aauk.shake($('.counter'));
						
						return false;
				
					} else if($(".shareText").val().length < 1) {
						
						$("#noTextError").slideDown();
						
						return false;
					}
					
					
					
					_this.uploadAndTweet();
					
				});
				
				$("#fbVideo").on("click",function(e){
					
					e.preventDefault();
					
					charCount = _this.charCount - $(".shareText").val().length;
					
					if (charCount < 0)
					{
						aauk.shake($('.counter'));
						
						return false();
					
					} else if($(".shareText").val().length < 1) {
						
						$("#noTextError").slideDown();
						
						return false;
					}
					
					
					
					_this.uploadAndFB();
					
				});
				
				if ($(".shareText").length > 0 ) {
					
					_this.bindCounter();
					
				}

				
			}
		}
		
	};
	
	if (typeof aauk.shake === "undefined") { aauk.shake = function(element,params){                                                                                                                                                                                            
	   
	    if (params instanceof Object == false) {
		   var params = {};
	    }
	    
	    params.positioning = typeof params.positioning !== 'undefined' ? params.positioning : 'relative';
	    params.side = typeof params.side !== 'undefined' ? params.side : 'left';
	    params.interval = typeof params.interval !== 'undefined' ? params.interval : 100;
	    params.distance = typeof params.distance !== 'undefined' ? params.distance : '10';
	    params.times = typeof params.times !== 'undefined' ? params.times : 4;
	
	    $(element).css('position',params.positioning);                                                                                  
	
		if (params.side == 'left') {
	
		    for(var iter=0;iter<(params.times+1);iter++){                                                                              
		        $(element).animate({ 
		            left:((iter%2==0 ? params.distance : params.distance*-1))
		            },params.interval);                                   
		    }
		
		    $(element).animate({ left: 0},params.interval); 
		} else {
			
			 for(var iter=0;iter<(params.times+1);iter++){                                                                              
		        $(element).animate({ 
		            right:((iter%2==0 ? params.distance : params.distance*-1))
		            },params.interval);                                   
		    }
		
		    $(element).animate({ right: 0},params.interval); 
			
		}                                                                               
	
	};}
	
	



})(jQuery);



jQuery(document).ready(function($) {


	
	//	if (aauk.hasGetUserMedia()){
	//		aauk.videoRecording.init();
			
			
	//	} else {
		if ($("#videoUploadForm").length > 0)
		{
			aauk.videoUploader.init();

		}
		
	//	}

		aauk.videoShare.init();
		aauk.videoUploader.loadNonAjax();
		aauk.videoUploader.displayError();
});




































