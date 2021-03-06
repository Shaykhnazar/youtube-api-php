<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>YouTube mini player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <!--Content-->
        <form action="search.php" method="post">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="q" value="<?php if (isset($_SESSION['q'])) echo $_SESSION['q']; ?>" placeholder="Enter keyword" aria-label="Search from youtube" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
            </div>
        </form>
        <div class="accordion" id="accordionExample">
            <?php
            $result = $_SESSION['result'];
            if(is_array($result)){

                for($i=0;$i<count($result);$i++){
            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header " id="heading<?php echo $i;?>">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
                            <?php echo $result[$i]['title'];?>
                        </button>
                        <small><?php echo $result[$i]['publishedAt'];?></small>
                        <p class="mb-1"><?php echo $result[$i]['channelTitle'];?></p>
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                            </svg>
                            <span style="color: red">
                                <?php echo $result[$i]['viewCount'];?>
                            </span>
                        </p>
                    </h2>
                    <div id="collapse<?php echo $i;?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $i;?>" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <iframe width="420" height="315"
                                    src="https://www.youtube.com/embed/<?php echo $result[$i]['videoId'];?>?autoplay=0">
                            </iframe>
                        </div>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </div>
        <hr>
        <div>
            Result of search by keyword: <b><?=$_SESSION['q']?></b>
        </div>
        <!--End of Content-->
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>