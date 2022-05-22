<style>
    #accordion a {
        display: inline;
        font-size: 1rem;
    }
    .mb-0>a {
        display: block;
        position: relative;
    }

    .mb-0>a:after {
        content: "\f078";
        /* fa-chevron-down */
        font-family: 'FontAwesome';
        position: absolute;
        right: -30px;
    }

    .mb-0>a[aria-expanded="true"]:after {
        content: "\f077";
        /* fa-chevron-up */
    }
    .card-header p {
        display: inline;
        font-size: 1rem;
    }
</style>
<h3><?php echo "Categories"; ?></h3>
<div id="accordion">
    <div class="card">
        <?php
            $i = 0;
            $statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $i++;
        ?>
        <div class="card-header" id="heading-<?php echo $i; ?>">
            <h5 class="mb-0">
                <p><a href="product-category.php?id=<?php echo $row['tcat_id']; ?>&type=top-category"><?php echo $row['tcat_name']; ?></a></p>
                <a role="button" data-toggle="collapse" href="#collapse-<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $i; ?>">
                    
                </a>
            </h5>
        </div>
        <div id="collapse-<?php echo $i; ?>" class="collapse" data-parent="#accordion" aria-labelledby="heading-<?php echo $i; ?>">
            <div class="card-body">
                <div id="accordion-<?php echo $i; ?>">
                    <?php
                        $j = 0;
                        $statement1 = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id=?");
                        $statement1->execute(array($row['tcat_id']));
                        $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result1 as $row1) {
                            $j++;
                    ?>
                    <div class="card">
                        <div class="card-header" id="heading-<?php echo $i; ?>-<?php echo $j; ?>">
                            <h5 class="mb-0">
                                <p><a href="product-category.php?id=<?php echo $row1['mcat_id']; ?>&type=mid-category"><?php echo $row1['mcat_name']; ?></a></p>
                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-<?php echo $i; ?>-<?php echo $j; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $i; ?>-<?php echo $j; ?>">
                                </a>
                            </h5>
                        </div>
                        <div id="collapse-<?php echo $i; ?>-<?php echo $j; ?>" class="collapse" data-parent="#accordion-<?php echo $i; ?>" aria-labelledby="heading-<?php echo $i; ?>-<?php echo $j; ?>">
                            <div class="card-body">
                                <?php
                                    $k = 0;
                                    $statement2 = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id=?");
                                    $statement2->execute(array($row1['mcat_id']));
                                    $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result2 as $row2) {
                                        $k++;
                                ?>
                                <div><p><a href="product-category.php?id=<?php echo $row2['ecat_id']; ?>&type=end-category"><?php echo $row2['ecat_name']; ?></a></p></div><?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

            </div>
        </div>
        <?php } ?>
    </div>
</div>