<?php include("includes/header.php"); ?>

<?php 

    
    $page = !empty($_GET['page']) ? $_GET['page'] : 1;
    $items_per_page = 4;
    $items_total_count = Photo::count_all();

    

    $paginate = new Paginate($page, $items_per_page, $items_total_count);

    $sql = "SELECT * FROM photos ";
    $sql.= "LIMIT {$items_per_page} ";
    $sql.= "OFFSET {$paginate->offset()}";

    //echo $sql; exit;
    $photos = Photo::do_the_query($sql);

   // $photos = Photo::find_all();


 ?>


<div class="row">

            <!-- Blog Entries Column -->
    <div class="col-md-12">

        <div class="thumbnails row">


            <?php foreach ($photos as $photo) : ?>


              
                    
                    <div class="col-xs-6 col-md-3">
                        
                        <a class="thumbnail" href="photo.php?id=<?php echo $photo->id; ?>">
                             <img class="homepage_photos" src="admin/<?php echo $photo->picture_path(); ?>">
                        </a>

                           
                    </div>    

            <?php endforeach; ?>

        </div>


        <div class="row">
            
            <ul class="pagination">

                <?php 

                if($paginate->total_pages()>1){

                    if($paginate->has_next()){

                        echo "<li class='next'><a href='index.php?page={$paginate->next()}'>Next</a></li>";

                    }

           


                    for ($i=1; $i<=$paginate->total_pages(); $i++) { 
                        
                        if($i==$page){

                            echo "<li class='active'><a href='index.php?page={$i}'>{$i}</a></li>";
                        }else{

                            echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";
                        }
                    }



              

                    if($paginate->has_previous()){

                        echo "<li class='previous'><a href='index.php?page={$paginate->previous()}'>Previous</a></li>";
                    }
                }

                 ?>
               
                
            </ul>

        </div>




    </div>
</div><!-- row end -->   




            <!-- Blog Sidebar Widgets Column -->
            <!-- <div class="col-md-4"> -->

            
                 <?php // include("includes/sidebar.php"); ?>



        <!-- </div> -->
        <!-- /.row -->

        <?php include("includes/footer.php"); ?>
