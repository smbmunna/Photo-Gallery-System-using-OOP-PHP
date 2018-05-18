<?php include("includes/header.php"); ?>
<?php 

    if(!$session_object->is_signed_in()){

        redirect("login.php");

    }

 ?>
 <?php 

    $message = "";

    if(isset($_POST['submit'])){

        $photo = new Photo();
        $photo->title = $_POST['file-title'];
        $photo->description = $_POST['description'];
        $photo->set_file($_FILES['file-upload']);

        if($photo->save()){

        $message = "Photo uploaded successfully";
        }else{

        $message = join("<br>", $photo->custom_errors);
    }


    }
    




  ?>


        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            

        <?php include("includes/top_nav.php") ?>






            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        

        <?php include("includes/side_nav.php") ?>



            <!-- /.navbar-collapse -->
        </nav>






        <div id="page-wrapper">

           <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Upload
                            <small>Subheading</small>                            
                        </h1>

                        <div class="col-md-6">
                            <form action="upload.php" method="post" enctype="multipart/form-data">
                                
                                <div class="form-group">
                                    <input type="text" name="file-title" class="form-control" placeholder="Title">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="description" class="form-control" placeholder="Description">
                                </div>
                                <div class="form-group">
                                    <input type="file" name="file-upload">
                                </div>
                                <div class="form-group">
                                        <input type="submit" value="Submit" name="submit" class="form-control">
                                </div>

                            </form>
                        </div>


                        
                    </div>
                </div>
                <!-- /.row -->

            </div>


        </div>
        <!-- /#page-wrapper -->

  <?php include("includes/footer.php"); ?>	