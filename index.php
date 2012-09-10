<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
        <style>
            progress {
                margin:10px 0 10px 0;
            }
            
            .content {
            margin-top:60px;
            }
        </style>
        <title>PHP 5.4 File Upload Progress Demo</title>
    </head>
    <body>

        <div class="topbar">
            <div class="fill">
                <div class="container">
                    <a class="brand" href="#">PHP5.4 Demo</a>

                    <ul class="nav">
                        <li><a href="http://chemicaloliver.net/">Main Site</a></li>
                        <li><a href="http://chemicaloliver.net/programming/php-5-4-file-upload-progress-and-html5-progress-bars/">Blog Post</a></li>
                    </ul>

                </div>
            </div>
        </div>

        <article class="container">
            <div class="content">
                <header class="page-header">
                    <h1>PHP 5.4 File Upload Progress Demo</h1>
                </header>

                <div class="row">
                    <div class="span16">

                        <h2>Upload</h2>
                        <p>Select one or two files to upload (Max total size 2MB)</p>
                        <form action="/upload.php" method="POST" enctype="multipart/form-data" id="upload">
                            <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="upload" />

                            <div class="clearfix">
                                <label for="file1">File 1</label>
                                <div class="input">
                                    <input type="file" name="file1" id="file1" />
                                </div>
                            </div>

                            <div class="clearfix">
                                <label for="file2">File 1</label>
                                <div class="input">
                                    <input type="file" name="file2" id="file2" />
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" class="btn primary" value="Upload"/>
                            </div>
                        </form>

                        <h2>Progress</h2>
                        <progress max="1" value="0" id="progress"></progress>
                        <p id="progress-txt"></p>
                    </div>
                </div>
            </div>

        </article>




        <!-- File containing Jquery and the Jquery form plugin-->
        <script src="jquery.js"></script>
        <script>
		
            //Holds the id from set interval
            var interval_id = 0;
        
            $(document).ready(function(){
	
                //jquery form options
                var options = { 
                    success:       stopProgress, //Once the upload completes stop polling the server
                    error:		   stopProgress
                };

                //Add the submit handler to the form
                $('#upload').submit(function(e){
                	
                    //check there is at least one file
                    if($('#file1').val() == '' && $('#file2').val() == '')
                    {
                        e.preventDefault();
                        return;
                    }
		
                    //Poll the server for progress
                    interval_id = setInterval(function() {
                        $.getJSON('/progress.php', function(data){
                            
                            //if there is some progress then update the status
                            if(data)
                            {
                                $('#progress').val(data.bytes_processed / data.content_length);
                                $('#progress-txt').html('Uploading '+ Math.round((data.bytes_processed / data.content_length)*100) + '%');
                            }
                            
                            //When there is no data the upload is complete
                            else
                            {
                                $('#progress').val('1');
                                $('#progress-txt').html('Complete');
                                stopProgress();
                            }
					
                        })
                    }, 200);
		
                    $('#upload').ajaxSubmit(); 
                    
                    e.preventDefault();
                });	
            });

            function stopProgress()
            {
                clearInterval(interval_id);
            }
        </script>

    </body>
</html>

