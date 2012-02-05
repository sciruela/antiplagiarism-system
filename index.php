

<!DOCTYPE html>
<html>
<head><title>
	Anti-Plagiarism System 
</title>
    <style type="text/css">
		  body { font-family: Verdana, Arial, sans-serif; font-size: 90%;	}
		  h1, h2, h3, h4 { margin-top: 0px; }
		  div.row { margin-bottom: 10px; }
		  *:focus{ outline:none; }
		  .floatLeft { float: left; }
		  .floatRight { float: right; }
		  .clear { clear: both; }
		  
		  form {
		    padding: 20px;
		    border: 1px solid #cccccc;
		    border-radius:10px;
		    -moz-border-radius: 10px;
			  -webkit-box-shadow:0 0 10px #ccc;
			  -moz-box-shadow: 0 0 10px #ccc;
			  box-shadow: 0 0 10px #ccc;
		  
		    width: 400px;
		    margin: 20px auto;		  
			  background-image: -moz-linear-gradient(top, #ffffff,#f2f2f2);
			  background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#f2f2f2));			
		  }
		  
		  input {			
			  border:1px solid #ccc;
			  font-size: 13pt;			
			  padding: 5px 10px 5px 10px;			
			  border-radius:10px;
			  -moz-border-radius: 10px;			
			  -webkit-transition: all 0.5s ease-in-out;
	      -moz-transition: all 0.5s ease-in-out;
  			transition: all 0.5s ease-in-out;		
		  }
		  
		  input[type=button] {
		    background-image: -moz-linear-gradient(top, #ffffff, #dfdfdf);
	  		background-image: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#dfdfdf));
		  }  				  

		  
		input:focus {
			-webkit-box-shadow:0 0 10px #ccc;
			-moz-box-shadow: 0 0 10px #ccc;
			box-shadow: 0 0 5px #ccc;
			
			-webkit-transform: scale(1.05);
			-moz-transform: scale(1.05);
			transform: scale(1.05);			
		}
		
		#fileToUpload { width: 378px; }
		
		#progressIndicator { font-size: 10pt; }
		
		#fileInfo 
		{
		  font-size: 10pt;
		  font-style: italic;
		  color: #aaa;
		  margin-top: 10px;
		}
		
		#progressBar 		
		{		  
		  height: 14px;
		  border: 1px solid #cccccc;
		  display: none;
			border-radius:10px;
		  -moz-border-radius: 10px;		  
			background-image: -moz-linear-gradient(top, #66cc00, #4b9500);
			background-image: -webkit-gradient(linear, left top, left bottom, from(#66cc00), to(#4b9500));		  
		}
		
		#uploadResponse {
		  margin-top: 10px;
		  padding: 20px;
		  overflow: hidden;
		  display: none;
		  border-radius:10px;
		  -moz-border-radius: 10px;
		  border: 1px solid #ccc;		  
		  box-shadow: 0 0 5px #ccc;
			background-image: -moz-linear-gradient(top, #ff9900, #c77801);
			background-image: -webkit-gradient(linear, left top, left bottom, from(#ff9900), to(#c77801));		  
		}		  
    </style>
    
    <script type="text/javascript">
      var bytesUploaded = 0;
      var bytesTotal = 0;
      var previousBytesLoaded = 0;
      var intervalTimer = 0;

      function fileSelected() {
        var file = document.getElementById('fileToUpload').files[0];
        var fileSize = 0;
        if (file.size > 1024 * 1024)
          fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
        else
          fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
        document.getElementById('fileInfo').style.display = 'block';
        document.getElementById('fileName').innerHTML = 'Name: ' + file.name;
        document.getElementById('fileSize').innerHTML = 'Size: ' + fileSize;
        document.getElementById('fileType').innerHTML = 'Type: ' + file.type;
      }
      
      function uploadFile() {
        previousBytesLoaded = 0;
        document.getElementById('uploadResponse').style.display = 'none';
        document.getElementById('progressNumber').innerHTML = '';
        var progressBar = document.getElementById('progressBar');
        progressBar.style.display = 'block';
        progressBar.style.width = '0px';        
        
        /* If you want to upload only a file along with arbitary data that
           is not in the form, use this */
        var fd = new FormData();
        fd.append("author", "Sergio Ciruela");
        fd.append("name", "Antiplagiarism System");
        fd.append("fileToUpload", document.getElementById('fileToUpload').files[0]);

        var xhr = new XMLHttpRequest();        
        xhr.upload.addEventListener("progress", uploadProgress, false);
        xhr.addEventListener("load", uploadComplete, false);
        xhr.addEventListener("error", uploadFailed, false);
        xhr.addEventListener("abort", uploadCanceled, false);
        xhr.open("POST", "upload_file.php");
        xhr.send(fd);

        intervalTimer = setInterval(updateTransferSpeed, 500);
      }

      function updateTransferSpeed() {
        var currentBytes = bytesUploaded;
        var bytesDiff = currentBytes - previousBytesLoaded;
        if (bytesDiff == 0) return;
        previousBytesLoaded = currentBytes;
        bytesDiff = bytesDiff * 2;
        var bytesRemaining = bytesTotal - previousBytesLoaded;
        var secondsRemaining = bytesRemaining / bytesDiff;

        var speed = "";
        if (bytesDiff > 1024 * 1024)
          speed = (Math.round(bytesDiff * 100/(1024*1024))/100).toString() + 'MBps';
        else if (bytesDiff > 1024)
          speed =  (Math.round(bytesDiff * 100/1024)/100).toString() + 'KBps';
        else
          speed = bytesDiff.toString() + 'Bps';
        document.getElementById('transferSpeedInfo').innerHTML = speed;
        document.getElementById('timeRemainingInfo').innerHTML = '| ' + secondsToString(secondsRemaining);        
      }

      function secondsToString(seconds) {        
        var h = Math.floor(seconds / 3600);
        var m = Math.floor(seconds % 3600 / 60);
        var s = Math.floor(seconds % 3600 % 60);
        return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s);
      }

      function uploadProgress(evt) {
        if (evt.lengthComputable) {
          bytesUploaded = evt.loaded;
          bytesTotal = evt.total;
          var percentComplete = Math.round(evt.loaded * 100 / evt.total);
          var bytesTransfered = '';
          if (bytesUploaded > 1024*1024)
            bytesTransfered = (Math.round(bytesUploaded * 100/(1024*1024))/100).toString() + 'MB';
          else if (bytesUploaded > 1024)
            bytesTransfered = (Math.round(bytesUploaded * 100/1024)/100).toString() + 'KB';
          else
            bytesTransfered = (Math.round(bytesUploaded * 100)/100).toString() + 'Bytes';

          document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
          document.getElementById('progressBar').style.width = (percentComplete * 3.55).toString() + 'px';
          document.getElementById('transferBytesInfo').innerHTML = bytesTransfered;
          if (percentComplete == 100) {
            //document.getElementById('progressInfo').style.display = 'null';
            var uploadResponse = document.getElementById('uploadResponse');
            uploadResponse.innerHTML = '<span style="font-size: 18pt; font-weight: bold;">Please wait...</span>';
            uploadResponse.style.display = 'block';
          }
        }
        else {
          document.getElementById('progressBar').innerHTML = 'unable to compute';
        }  
      }

      function uploadComplete(evt) {
        clearInterval(intervalTimer);
        var uploadResponse = document.getElementById('uploadResponse');
        uploadResponse.innerHTML = evt.target.responseText;
        uploadResponse.style.display = 'block';
      }  
  
      function uploadFailed(evt) {
        clearInterval(intervalTimer);
        alert("An error occurred while uploading the file.");  
      }  
  
      function uploadCanceled(evt) {
        clearInterval(intervalTimer);
        alert("The upload has been canceled by the user or the browser dropped the connection.");  
      }  

    </script>
</head>
<body>
<form id="form1" enctype="multipart/form-data" method="post" action="upload_file.php">
  <div class="row">
    <label for="fileToUpload">Select a PDF File to Upload</label><br />
    <input type="file" name="fileToUpload" id="fileToUpload" onchange="fileSelected();"/>  
  </div>
  <div class="row">
  <input type="button" onclick="uploadFile()" value="Upload" />
  </div>
  <div id="fileInfo">
    <div id="fileName"></div>
    <div id="fileSize"></div>
    <div id="fileType"></div>
  </div>
  <div class="row"></div>
  <div id="progressIndicator">
    <div id="progressBar" class="floatLeft">
    </div>
    <div id="progressNumber" class="floatRight">&nbsp;</div>
    <div class="clear"></div>
    <div>
      <div id="transferSpeedInfo" class="floatLeft" style="width: 80px;">&nbsp;</div>
      <div id="timeRemainingInfo" class="floatLeft" style="margin-left: 10px;">&nbsp;</div>
      <div id="transferBytesInfo" class="floatRight" style="text-align: right;">&nbsp;</div>
      <div class="clear"></div>
    </div>    
    <div id="uploadResponse"></div>
  </div>  
</form>

</body>
</html>
