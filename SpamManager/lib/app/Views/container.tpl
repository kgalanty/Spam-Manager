<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TMD Feed Viewer</title>

    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
    <link href="/admin/templates/blend/css/all.min.css?v=a2add1" rel="stylesheet" />
    <link href="/710/assets/css/fontawesome-all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/quill-emoji@0.1.8/dist/quill-emoji.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <script type="text/javascript" src="/710/admin/templates/blend/js/scripts.min.js?v=a2add1"></script>
</head>


<style>
	#agreementCheckpoint .panel:hover 
	{
		box-shadow: 0 5px 15px rgba(0,0,0,0.3);
		transition: all .6s cubic-bezier(0.165, 0.84, 0.44, 1);
	}
    #agreementCheckpoint .label
    {
        display: initial;
        color: white;
    }
</style>
<div id="agreementCheckpoint" style="
    height: 100%;
    position: fixed;
    width: 100%;
    z-index: 2000;
    background: white;
    overflow-y:scroll;overflow-x:hidden;
    padding:20px;
"><p><img src="https://www.tmdhosting.com/wp-content/uploads/2017/07/logo.png" /> </p><p>Important Messages Board</p>
<h1 style="font-size: 2em;
    text-align: center;
">🔔 Important Announcement via TMD Feed 🔔</h1>
#newentries#
<div class="col-12" id="acknowledgeBtn">
    <a id="proceedBtn" href="#" class="btn btn-primary">Acknowledge & Proceed</a>
</div>
<script>
    jQuery("#proceedBtn").click (function (event) {
        jQuery('#proceedBtn').html('Accepting...Please wait').attr('disabled', true);
            jQuery.get( "index.php?kgacceptrss=1", function(response) {
               event.preventDefault();
                if(response === 'success')
                {
                   // jQuery('#agreementCheckpoint').fadeOut();
                    window.location.reload();
                }
                
            });
    });
    jQuery(function() {
        if( location.pathname.indexOf('feedentry.php') > -1)
        {
            jQuery("#acknowledgeBtn").hide();
            jQuery("#oldentries").hide();

        }
        else if(jQuery('.panel-info').length === 1)
        {
            jQuery("#acknowledgeBtn").appendTo(".panel-info > .panel-body");
        }
        // if(typeof csrfToken === 'undefined')
        // {
        //     jQuery('#acknowledgeBtn').hide();
        //     jQuery('#oldentries').hide();
        // }
    });
</script>
<br>
<hr><h1 id="oldentries" style="text-align:center">TMD Feed Archive</h1>
#oldentries#
</div></div>
