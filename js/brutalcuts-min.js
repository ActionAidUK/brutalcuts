String.prototype.capitalize=function(e){return(e?this.toLowerCase():this).replace(/(?:^|\s)\S/g,function(e){return e.toUpperCase()})},navigator.getUserMedia=navigator.getUserMedia||navigator.webkitGetUserMedia||navigator.mozGetUserMedia||navigator.msGetUserMedia;var aauk={};aauk.isTouchDevice=!1,aauk.imagesLoaded=!1,function($){"undefined"==typeof aauk.checkFileAPI&&(aauk.checkFileAPI=function(){return!!(window.File&&window.FileList&&window.FileReader)}),"undefined"==typeof aauk.readQueryString&&(aauk.readQueryString={init:function(){var e,o=document.URL.split("?")[1],t={};if(void 0!==o){o=o.split("&");for(var a=0;a<o.length;a++)e=o[a].split("="),t[e[0]]=e[1]}return t}}),"undefined"==typeof aauk.isTouchDeviceDetect&&(aauk.isTouchDeviceDetect=function(){var e=document.createElement("div");return e.setAttribute("ongesturestart","return;"),"function"==typeof e.ongesturestart?(this.isTouchDevice=!0,!0):!1}),"undefined"==typeof aauk.hasGetUserMedia&&(aauk.hasGetUserMedia=function(){return!!(navigator.getUserMedia||navigator.webkitGetUserMedia||navigator.mozGetUserMedia||navigator.msGetUserMedia)}),"undefined"==typeof aauk.videoRecording&&(aauk.videoRecording={theVideo:{},startButton:{},stopButton:{},theStream:{},mediaSource:{},mediaRecorder:{},superBuffer:"",recordingIcon:{},recordedBlobs:[],superBuffer:{},sourceBuffer:{},camvideoWrapper:{},constraints:{},isSecureOrigin:"",init:function(){var e=this;$("#getMediaCapture").prepend('<fieldset id="camvideoWrapper"><div id="recording"></div><div align="center" class="embed-responsive embed-responsive-16by9" ><video id="camvideo" autoplay muted></video><video id="camplayer" controls></video></div><input type="button" class="recording-button start" id="startRecording" value="Start recording"><input type="button"  class="recording-button stop" id="stopRecording" value="Stop recording" disabled></fieldset>').show(),e.constraints={audio:!0,video:{aspectRatio:1.7777777778,width:{min:640,ideal:640},height:{min:360,ideal:360}}},e.camvideoWrapper=$("#camvideoWrapper"),e.startButton=$("#startRecording"),e.stopButton=$("#stopRecording"),e.recordingIcon=$("#recording"),e.theVideo=document.getElementById("camvideo"),e.thePlayer=document.getElementById("camplayer"),e.mediaSource=new MediaSource,e.mediaSource.addEventListener("sourceopen",e.handleSourceOpen,!1),e.isSecureOrigin="https:"===location.protocol||"localhost"===location.host,e.isSecureOrigin||(alert("getUserMedia() must be run from a secure origin: HTTPS or localhost.\n\nChanging protocol to HTTPS"),location.protocol="HTTPS"),navigator.mediaDevices.getUserMedia(e.constraints).then(function(o){e.handleSuccess(o)})["catch"](function(o){e.handleError(o)}),e.startButton.on("click",function(){e.startRecording()}),e.stopButton.on("click",function(){e.stopRecording()})},handleSuccess:function(e){var o=this;$("#videoUploadForm").hide(),$("#getMediaCapture").show(),$("#saveVideo").on("click",function(e){o.saveVideo()}),console.log("getUserMedia() got stream: ",e),window.stream=e,window.URL?o.theVideo.src=window.URL.createObjectURL(e):o.theVideo.src=e},handleError:function(e){console.log(e),$("#getMediaCapture").remove(),aauk.videoUploader.init()},startRecording:function(){var e=this;$("#submitButton").prop("disabled",!0),window.URL?e.theVideo.src=window.URL.createObjectURL(stream):e.theVideo.src=stream,e.recordingIcon.show(),e.startButton.prop("disabled",!0),e.stopButton.prop("disabled",!1),e.thePlayer.style.display="none",e.theVideo.style.display="block";var o={mimeType:"video/webm"};e.recordedBlobs=[];try{e.mediaRecorder=new MediaRecorder(window.stream,o)}catch(t){console.log("Unable to create MediaRecorder with options Object: ",t);try{o={mimeType:"video/webm,codecs=vp9"},e.mediaRecorder=new MediaRecorder(window.stream,o)}catch(a){console.log("Unable to create MediaRecorder with options Object: ",a);try{o="video/vp8",e.mediaRecorder=new MediaRecorder(window.stream,o)}catch(i){return alert("MediaRecorder is not supported by this browser.\n\nTry Firefox 29 or later, or Chrome 47 or later, with Enable experimental Web Platform features enabled from chrome://flags."),void console.error("Exception while creating MediaRecorder:",i)}}}console.log("Created MediaRecorder",e.mediaRecorder,"with options",o),e.mediaRecorder.onstop=e.handleStop,e.mediaRecorder.ondataavailable=function(o){e.handleDataAvailable(o)},e.mediaRecorder.start(10),console.log("MediaRecorder started",e.mediaRecorder)},stopRecording:function(){var e=this;e.thePlayer.style.display="block",e.theVideo.style.display="none",e.recordingIcon.hide(),e.startButton.prop("disabled",!1),e.stopButton.prop("disabled",!0),e.theVideo.pause(),e.mediaRecorder.stop(),console.log("Recorded Blobs: ",e.recordedBlobs),e.thePlayer.controls=!0,$("#submitButton").prop("disabled",!1),e.playVideo()},saveVideo:function(){var e=this,o=new Blob(e.recordedBlobs,{type:"video/webm"}),t=window.URL.createObjectURL(o),a=new Date,i=a.getTime(),r=new FormData;r.append("video-filename","video-"+i+".webm"),r.append("video-blob",o),$("#submit-wrap").hide(),$("#spinner").show(),$.ajax({url:"/ajax-upload.php",data:r,processData:!1,method:"POST",contentType:!1,dataType:"json",success:function(e){aauk.videoUploader.displayVideo(e)}})},handleDataAvailable:function(e){var o=this;e.data&&e.data.size>0&&o.recordedBlobs.push(e.data)},playVideo:function(){var e=this;e.superBuffer=new Blob(e.recordedBlobs,{type:"video/webm"}),e.onPlay()},onPlay:function(){var e=this;e.thePlayer.src=window.URL.createObjectURL(e.superBuffer)},handleSourceOpen:function(e){var o=this;console.log("MediaSource opened"),o.sourceBuffer=mediaSource.addSourceBuffer('video/webm; codecs="vp8"'),console.log("Source buffer: ",o.sourceBuffer)},handleStop:function(e){console.log("Recorder stopped: ",e)}}),"undefined"==typeof aauk.bytesToSize&&(aauk.bytesToSize=function(e,o){var t=1024,a=1024*t,i=1024*a,r=1024*i;return e>=0&&t>e?e+" B":e>=t&&a>e?(e/t).toFixed(o)+" KB":e>=a&&i>e?(e/a).toFixed(o)+" MB":e>=i&&r>e?(e/i).toFixed(o)+" GB":e>=r?(e/r).toFixed(o)+" TB":e+" B"}),"undefined"==typeof aauk.videoUploader&&(aauk.videoUploader={fileInput:{},fileInfo:{},theForm:{},theDropZone:{},theDropZoneTrigger:{},theSubmitButton:{},dropzoneText:{},dropzoneResults:{},updateBar:{},uploadStatus:{},uploadDots:{},uploadDotCount:1,init:function(){var e=this,o="",t=0;e.fileInput=$("#uploadFile"),e.theForm=$("#videoUploadForm"),e.theDropZone=$("#dropzone"),e.theSubmitButton=$("#submitButton"),e.theDropZoneTrigger=$("#dropzoneTrigger"),e.dropzoneText=$("#dropzone-text"),e.dropzoneResults=$("#dropzone-results"),e.theDropZoneTrigger.dropzone({url:"/ajax-upload.php",maxFilesize:64,paramName:"video-blob",acceptedFiles:"image/*,video/*",uploadprogress:function(t,a,i){o="",e.updateBar.width(a+"%"),a>=100&&(console.log("complete"),e.uploadStatus.text("Processing"))},complete:function(e){},previewsContainer:"#dropzone-results",init:function(){e.fileInput.hide(),e.theSubmitButton.hide(),e.theDropZone.addClass("active"),e.theDropZoneTrigger.css("z-index",100),e.dropzoneResults.hide(),aauk.isTouchDeviceDetect()&&$("#bcInstructions").text("To get started, click here to upload an image or video."),this.on("addedfile",function(o){e.updateBar=$(".dz-upload"),$(".dz-progress").append('<div id="dz-upload-status" class="dz-upload-status"><span id="upload-stage">Uploading</span><span id="upload-dots">...</div></div>'),e.uploadStatus=$("#upload-stage"),e.uploadDots=$("#upload-dots"),e.dropzoneText.fadeOut("fast",function(){e.dropzoneResults.fadeIn()}),e.theDropZone.removeClass("active")}),this.on("removedfile",function(o){e.dropzoneResults.fadeOut(function(){e.dropzoneText.fadeIn()}),e.theDropZone.addClass("active")}),this.on("success",function(o,t){$("#uploadRow").fadeOut(function(){e.displayVideo(t)})})}}),e.theForm.on("submit",function(o){o.preventDefault(),e.onSubmit()}),$("#uploadFile").on("change",function(){e.validateFile()})},onSubmit:function(){var e=this,o=e.validateFile();if(o.length<1){var t=new FormData(e.theForm[0]);$("#submit-wrap-upload").hide(),$("#spinner-upload").show(),$.ajax({url:"/ajax-upload.php",data:t,processData:!1,method:"POST",contentType:!1,dataType:"json",success:function(o){e.displayVideo(o)}})}},displayVideo:function(e){var o;console.log(e),$("#submit-wrap-upload").show(),$("#spinner-upload").hide(),$("#getMediaCapture").hide(),$("#videoUploadForm").hide(),o="640"==e.width?"embed-responsive-16by9":"embed-responsive-square",$("#finalVideo").html('<div class="align-wrapper '+o+'"><div align="center" class="embed-responsive '+o+'" ><video id="brutalCut"  autoplay poster="'+e.poster+'" controls class="embed-responsive-item"><source src="'+e.url+'" type="video/mp4">Your browser does not support the video tag.</video></div></div>'),$("#shareContainer").html('<p><a download target="_blank" class="red-box-button inline-button file-download-button" href="'+e.url+'"><span class="download-icon"></span>Download</a><a target="_blank" id="shareToTwitter" class="red-box-button twitter inline-button" href="twitter.php?vid='+e.id+'" data-sharetype="TShareOauth" data-videoid="'+e.id+'" target="_blank" data-sharevideo="'+e.url+'"><span class="social-logo"></span>Share to twitter</a><a target="_blank" id="shareToFacebook" class="red-box-button facebook inline-button" href="facebook.php?vid='+e.id+'" data-sharetype="facebookShare" data-videoid="'+e.id+'" target="_blank" data-sharevideo="'+e.url+'"><span class="social-logo"></span>Share to Facebook</a></p>'),(Number(e.duration)>29.99||Number(e.size)>15728640)&&($("#shareToTwitter").hide(),$(".shareWrapper").addClass("no-twitter")),$("#finalVideo").fadeIn()},validateFile:function(){var e=this;$("#error-box").slideUp(function(){$(this).remove()});var o=Array();try{if(e.fileInfo=e.fileInput[0].files[0],!e.fileInfo||e.fileInfo.length<1)return o.push("You haven’t selected a file"),o;var t=e.fileInfo.type,a=t.match(/^video.+|image.+/);a||o.push("This doesn’t look like a video or an image! Please upload a video or an image."),e.fileInfo.size>64777216&&o.push("This file is too big! Please upload a shorter video..."),$("#filesize").html("File size: "+aauk.bytesToSize(e.fileInfo.size,2))}catch(i){console.log(i),o.push("Can't check file type")}if(o.length>0){e.theForm.prepend('<div id="error-box" style="display:none;" class="error-box"><ul></ul></div>');for(x in o)$("#error-box > ul").append("<li>"+o[x]+"</li>");$("#error-box").slideDown()}return o}}),"undefined"==typeof aauk.videoShare&&(aauk.videoShare={vid:"",facebookLogin:function(){var e=this;FB.login(function(o){return alert("Response"),o.authResponse?(alert("Auth"),void e.shareToFacebook()):(alert("User cancelled login or did not fully authorize."),!1)},{scope:"publish_actions"})},facebookShare:function(){var e=this;FB.getLoginStatus(function(o){"connected"===o.status?e.shareToFacebook():e.facebookLogin()})},shareToFacebook:function(){var e=this;$.ajax({url:"/facebook.php?vid="+e.vid,processData:!1,method:"GET",contentType:!1,dataType:"json",success:function(e){console.log(e),alert("Shared")}})},uploadAndTweet:function(){var e=$("#tweetSending");e.show();var o=new FormData($("#tweeter")[0]);$.ajax({url:"/sendtweet.php",data:o,processData:!1,method:"POST",contentType:!1,dataType:"json",success:function(o){console.log(o),e.hide(),$("#tweeter").slideUp().after('<h2>Thank you for tweeting your Brutal cut!</h2><p><a class="red-box-button" href="#">Make a donation</a></p>')}})},uploadAndFB:function(){var e=$("#facebookSending");e.show();var o=new FormData($("#facebooker")[0]);$.ajax({url:"/post-to-facebook.php",data:o,processData:!1,method:"POST",contentType:!1,dataType:"json",success:function(o){console.log(o),e.hide(),$("#facebooker").slideUp().after('<h2>Thank you for sharing your Brutal cut!</h2><p><a class="red-box-button" href="#">Make a donation</a></p>')}})},init:function(){var e=this,o="";$("#finalVideo").on("click",".shareButton",function(t){switch(t.preventDefault(),e.vid=$(this).attr("data-videoid"),o=$(this).attr("data-sharetype")){case"facebookShare":window.open("facebook.php?vid="+$(this).attr("data-videoid"),"_blank","width=600,height=800");break;case"TShareOauth":window.open("twitter.php?vid="+$(this).attr("data-videoid"),"_blank","width=600,height=800")}}),$("#tweetVideo").on("click",function(o){o.preventDefault(),e.uploadAndTweet()}),$("#fbVideo").on("click",function(o){o.preventDefault(),e.uploadAndFB()})}})}(jQuery),jQuery(document).ready(function($){$("#videoUploadForm").length>0&&aauk.videoUploader.init(),aauk.videoShare.init()});