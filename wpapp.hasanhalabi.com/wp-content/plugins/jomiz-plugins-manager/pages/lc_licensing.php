<?php
$active_option = get_option("lc_irada_acticvation_key");
if ($active_option != false) {
    $data = json_decode($active_option);
}
?>
<html lang="en">

    <head>
        <style>
            .card{
                padding: 0px;
                box-shadow: -3px 4px 7px rgba(0,0,0,0.5);
            }
            LC{
                max-width: 100%;

            }

            .LC-header{
                background-color: #fff;
                border-bottom: 1px solid #dddddd;
                padding: 10px 10px 10px;
                margin: 40px 0px 20px;
            }
             

        </style>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <?php
        //  echo LC_PLUGIN_DIR_URL;
        ?>

        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-xs-12">
                    <div class="LC-header">
                        <h1 class="display-4">LC Accounting Settings</h1>
                    </div>

                </div>
            </div>




            <div class="row">
                <?php if ($active_option == false): ?>
                    <div class="col-md-6 col-lg-6">

                        <div class="card border-secondary bg-default">
                            <div class="card-header text-secondary">
                                Activation Key
                            </div>
                            <div class="card-body text-secondary">
                                <p class="card-text" id="">Please Enter your Activation Key.</p>
                                <div class="form-group">
                                    <input class="form-control input-sm" placeholder="" id="lc_manager_activation_key" type="text" required>
                                </div>
                                <div  id="" role="alert" hidden></div>

                                <button type="submit" class="btn btn-primary btn-sm" id= "lc_submit_key" onclick="lcManagerAjax.lcManagercheckkey()" style="float: right">submit</button> 
                                <p class="text-danger" id="input_manager_key" hidden></p>

                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-6 col-lg-6">
                        <div class="card border-secondary bg-default">
                            <div class="card-header text-secondary">
                                Congratulations
                            </div>
                            <div class="card-body text-secondary">
                                <p class="card-text" id=""><strong>Client Name:</strong><?php echo " " . $data->client_name ?></p>
                                <p class="card-text" id=""><strong>Date:</strong> <?php echo " " . $data->activation_date ?> </p>
                                <p class="card-text" id=""><strong>Client ID: </strong> <?php echo " " . $data->client_ref_id ?> </p> 

                            </div>
                        </div>


                    </div>
                <?php endif; ?>


            </div>
        </div>


    </body>

</html>