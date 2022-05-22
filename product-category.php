<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_product_category = $row['banner_product_category'];
}
?>

<?php
$cur_id;
$cur_type;
if (!isset($_REQUEST['id']) || !isset($_REQUEST['type'])) {
    header('location: index.php');
    exit;
} else {
    $cur_id = $_REQUEST['id'];
    $cur_type = $_REQUEST['type'];
    $page_no;
    if (isset($_REQUEST['page_no'])) {
        $page_no = $_REQUEST['page_no'];
    } else {
        $page_no = 1;
    }

    //echo "<script type='text/javascript'>alert('$page_no');</script>";

    if (($_REQUEST['type'] != 'top-category') && ($_REQUEST['type'] != 'mid-category') && ($_REQUEST['type'] != 'end-category')) {
        header('location: index.php');
        exit;
    } else {

        $statement = $pdo->prepare("SELECT * FROM tbl_top_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $top[] = $row['tcat_id'];
            $top1[] = $row['tcat_name'];
        }

        $statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $mid[] = $row['mcat_id'];
            $mid1[] = $row['mcat_name'];
            $mid2[] = $row['tcat_id'];
        }

        $statement = $pdo->prepare("SELECT * FROM tbl_end_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $end[] = $row['ecat_id'];
            $end1[] = $row['ecat_name'];
            $end2[] = $row['mcat_id'];
        }

        if ($_REQUEST['type'] == 'top-category') {
            if (!in_array($_REQUEST['id'], $top)) {
                header('location: index.php');
                exit;
            } else {
                for ($i = 0; $i < count($top); $i++) {
                    if ($top[$i] == $_REQUEST['id']) {
                        $title = $top1[$i];
                        break;
                    }
                }
                $arr1 = array();
                $arr2 = array();
                for ($i = 0; $i < count($mid); $i++) {
                    if ($mid2[$i] == $_REQUEST['id']) {
                        $arr1[] = $mid[$i];
                    }
                }
                for ($j = 0; $j < count($arr1); $j++) {
                    for ($i = 0; $i < count($end); $i++) {
                        if ($end2[$i] == $arr1[$j]) {
                            $arr2[] = $end[$i];
                        }
                    }
                }
                $final_ecat_ids = $arr2;
            }
        }

        if ($_REQUEST['type'] == 'mid-category') {
            if (!in_array($_REQUEST['id'], $mid)) {
                header('location: index.php');
                exit;
            } else {
                for ($i = 0; $i < count($mid); $i++) {
                    if ($mid[$i] == $_REQUEST['id']) {
                        $title = $mid1[$i];
                        break;
                    }
                }
                $arr2 = array();
                for ($i = 0; $i < count($end); $i++) {
                    if ($end2[$i] == $_REQUEST['id']) {
                        $arr2[] = $end[$i];
                    }
                }
                $final_ecat_ids = $arr2;
            }
        }

        if ($_REQUEST['type'] == 'end-category') {
            if (!in_array($_REQUEST['id'], $end)) {
                header('location: index.php');
                exit;
            } else {
                for ($i = 0; $i < count($end); $i++) {
                    if ($end[$i] == $_REQUEST['id']) {
                        $title = $end1[$i];
                        break;
                    }
                }
                $final_ecat_ids = array($_REQUEST['id']);
            }
        }
    }
}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_product_category; ?>)">
    <div class="inner">
        <h3><?php echo "Category:"; ?> <?php echo $title; ?></h3>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php require_once('sidebar-category.php'); ?>
            </div>
            <div class="col-md-9">

                <h3><?php echo "All Products Under"; ?> "<?php echo $title; ?>"</h3>
                <div class="product product-cat">

                    <div class="row">
                        <?php
                        $prod_count = 0;
                        $statement = $pdo->prepare("SELECT * FROM tbl_product");
                        $statement->execute();
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) {
                            $prod_table_ecat_ids[] = $row['ecat_id'];
                        }

                        for ($ii = 0; $ii < count($final_ecat_ids); $ii++) :
                            if (in_array($final_ecat_ids[$ii], $prod_table_ecat_ids)) {
                                $prod_count++;
                            }
                        endfor;

                        $arrow_exist = 0;
                        if ($prod_count == 0) {
                            echo '<div class="pl_15">' . "No Product Found" . '</div>';
                        } else {
                            $arrow_exist = 1;
                            $p_featured_photo = array();
                            $p_id = array();
                            $p_name = array();
                            $p_current_price = array();
                            $p_old_price = array();
                            $p_qty = array();
                            for ($ii = 0; $ii < count($final_ecat_ids); $ii++) {
                                $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE ecat_id=? AND p_is_active=?");
                                $statement->execute([$final_ecat_ids[$ii], 1]);
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $p_featured_photo[] = $row['p_featured_photo'];
                                    $p_id[] = $row['p_id'];
                                    $p_name[] = $row['p_name'];
                                    $p_current_price[] = $row['p_current_price'];
                                    $p_old_price[] = $row['p_old_price'];
                                    $p_qty[] = $row['p_qty'];
                                }
                            }

                            $limit = 12;
                            $total_pages = ceil(count($p_id) / $limit);

                            for ($ii = ($page_no - 1) * $limit; $ii < $page_no * $limit; $ii++) {
                                if (!isset($p_featured_photo[$ii])) break;
                        ?>
                                <div class="col-md-4 item item-product-cat">
                                    <div class="inner">
                                        <div class="thumb">
                                            <div class="photo" style="background-image:url(assets/uploads/<?php echo $p_featured_photo[$ii]; ?>);"></div>
                                            <div class="overlay"></div>
                                        </div>
                                        <div class="text">
                                            <h3><a href="product.php?id=<?php echo $p_id[$ii]; ?>"><?php echo $p_name[$ii]; ?></a></h3>
                                            <h4>
                                                <?php echo "Rp."; ?><?php echo $p_current_price[$ii]; ?>
                                                <?php if ($p_old_price[$ii] != '') : ?>
                                                    <del>
                                                        <?php echo "Rp."; ?><?php echo $p_old_price[$ii]; ?>
                                                    </del>
                                                <?php endif; ?>
                                            </h4>
                                            <div class="rating">
                                                <?php
                                                $t_rating = 0;
                                                $statement1 = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
                                                $statement1->execute(array($p_id[$ii]));
                                                $tot_rating = $statement1->rowCount();
                                                if ($tot_rating == 0) {
                                                    $avg_rating = 0;
                                                } else {
                                                    $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($result1 as $row1) {
                                                        $t_rating = $t_rating + $row1['rating'];
                                                    }
                                                    $avg_rating = $t_rating / $tot_rating;
                                                }
                                                ?>
                                                <?php
                                                if ($avg_rating == 0) {
                                                    echo '';
                                                } elseif ($avg_rating == 1.5) {
                                                    echo '
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-half-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    ';
                                                } elseif ($avg_rating == 2.5) {
                                                    echo '
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-half-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    ';
                                                } elseif ($avg_rating == 3.5) {
                                                    echo '
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-half-o"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    ';
                                                } elseif ($avg_rating == 4.5) {
                                                    echo '
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-half-o"></i>
                                                    ';
                                                } else {
                                                    for ($i = 1; $i <= 5; $i++) {
                                                ?>
                                                        <?php if ($i > $avg_rating) : ?>
                                                            <i class="fa fa-star-o"></i>
                                                        <?php else : ?>
                                                            <i class="fa fa-star"></i>
                                                        <?php endif; ?>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <?php if ($p_qty[$ii] == 0) : ?>
                                                <div class="out-of-stock">
                                                    <div class="inner">
                                                        Out Of Stock
                                                    </div>
                                                </div>
                                            <?php else : ?>
                                                <p><a href="product.php?id=<?php echo $p_id[$ii]; ?>"><i class="fa fa-shopping-cart"></i> <?php echo "Add to Cart"; ?></a></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>

                </div>

                <style>
                    .pages {
                        /* display: inline-block; */
                        float: left;
                    }

                    .page_current {
                        display: inline-block;
                        position: relative;
                        width: 40px;
                        height: 40px;
                        line-height: 40px;
                        cursor: pointer;
                        text-align: center;
                        margin-right: 16px;
                        background: #fe4c50;
                        border: none;
                    }

                    .page_current span {
                        color: #FFFFFF;
                    }

                    .page_selection {
                        display: block;
                        position: absolute;
                        right: 0;
                        top: 120%;
                        margin: 0;
                        width: 100%;
                        background: #FFFFFF;
                        visibility: hidden;
                        opacity: 0;
                        z-index: 1;
                        box-shadow: 0 15px 25px rgba(63, 78, 100, 0.15);
                        -webkit-transition: opacity 0.3s ease;
                        -moz-transition: opacity 0.3s ease;
                        -ms-transition: opacity 0.3s ease;
                        -o-transition: opacity 0.3s ease;
                        transition: all 0.3s ease;
                    }

                    .page_selection li {
                        display: block;
                        text-align: center;
                        padding-left: 10px;
                        padding-right: 10px;
                    }

                    .page_selection li a {
                        display: block;
                        height: 40px;
                        line-height: 40px;
                        border-bottom: solid 1px #dddddd;
                        color: #51545f;
                        -webkit-transition: opacity 0.3s ease;
                        -moz-transition: opacity 0.3s ease;
                        -ms-transition: opacity 0.3s ease;
                        -o-transition: opacity 0.3s ease;
                        transition: all 0.3s ease;
                    }

                    .page_selection li a:hover {
                        color: #b5aec4;
                    }

                    .page_current:hover .page_selection {
                        visibility: visible;
                        opacity: 1;
                        top: calc(100% + 1px);
                    }

                    .page_total {
                        display: inline-block;
                        line-height: 40px;
                        margin-right: 31px;
                    }

                    .page_total span {
                        margin-right: 14px;
                    }

                    .page_next,
                    .page_prev {
                        display: inline-block;
                        line-height: 40px;
                        cursor: pointer;
                    }

                    .page_next:hover i,
                    .page_prev:hover i {
                        color: #b5aec4;
                    }

                    .page_next i,
                    .page_prev i {
                        font-size: 18px;
                        color: #51545f;
                    }
                </style>

                <?php if ($arrow_exist) { ?>
                    <div class="pages d-flex flex-row align-items-center">
                        <?php
                        if ($page_no > 1) {
                        ?>
                            <div class="page_prev" style="margin-right: 31px;">
                                <a href="product-category.php?id=<?php echo $cur_id; ?>&type=<?php echo $cur_type ?>&page_no=<?php echo $page_no - 1 ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="page_current">
                            <span><?php echo $page_no; ?></span>
                            <ul class="page_selection">
                                <?php
                                for ($num = 1; $num <= $total_pages; $num++) {
                                ?>
                                    <li><a href="product-category.php?id=<?php echo $cur_id; ?>&type=<?php echo $cur_type ?>&page_no=<?php echo $num ?>"><?php echo $num; ?></a></li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="page_total"><span>of</span> <?php echo $total_pages; ?></div>
                        <?php
                        if ($page_no < $total_pages) {
                        ?>
                            <div class="page_next">
                                <a href="product-category.php?id=<?php echo $cur_id; ?>&type=<?php echo $cur_type ?>&page_no=<?php echo $page_no + 1 ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                <?php
                } ?>

            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>