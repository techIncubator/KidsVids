<?php
if(isset($_GET['error_msg'])) { ?>
    <div id="dialog-message" title="Message">
      <p>
        <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
            <?php echo $_GET['error_msg']; ?>
      </p>

    </div>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script>
    $(document).ready(function(){
      $( "#dialog-message" ).dialog({
          modal: true,

          buttons: {
            Ok: function() {
              $( this ).dialog( "close" );
            }
          }
        });

      });

    </script>
<?php 
} ?>